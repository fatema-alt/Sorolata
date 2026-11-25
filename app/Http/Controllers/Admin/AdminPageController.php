<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\Admin\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    public function index(Request $request): View
    {
        $query = Page::query();

        if ($request->filled('search')) {
            $term = (string) $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('slug', 'like', "%{$term}%")
                  ->orWhere('page_type', 'like', "%{$term}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('page_type', $request->input('type'));
        }

        $pages = $query->orderByRaw("CASE WHEN slug = 'home' THEN 1 WHEN slug = 'about' THEN 2 WHEN slug = 'contact' THEN 3 ELSE 4 END ASC")
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.index', [
            'pages' => $pages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'page_type' => 'required|string|in:landing,home,standard,campaign,story,festival',
            'status' => 'required|string|in:draft,published',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);

        $page = Page::create([
            'uuid' => (string) Str::uuid(),
            'title' => $validated['title'],
            'title_bn' => $validated['title_bn'] ?? null,
            'slug' => $slug,
            'page_type' => $validated['page_type'],
            'status' => $validated['status'],
            'layout_json' => [
                'version' => 1,
                'page_type' => $validated['page_type'],
                'sections' => \App\Livewire\Builder\VisualPageBuilder::getSorolataPageDefaults($slug, $validated['title']),
            ],
            'seo_meta' => [
                'title' => $validated['title'] . ' — Sorolata',
                'description' => 'Curated Bengali heritage handcrafted collection.',
            ],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        $this->activityLog->log('page_created', "Created page #{$page->id} ({$page->title})");

        return redirect()->route('builder.page', $page->id)->with('success', 'Page created! Launching Visual Page Builder.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $page = Page::findOrFail($id);

        // Protect system core pages from deletion
        if (in_array($page->slug, ['home', 'about', 'contact'], true)) {
            return redirect()->back()->with('error', 'Core system pages cannot be deleted.');
        }

        $title = $page->title;
        $page->delete();

        $this->activityLog->log('page_deleted', "Deleted page #{$id} ({$title})");

        return redirect()->route('admin.pages.index')->with('success', "Page '{$title}' removed.");
    }
}
