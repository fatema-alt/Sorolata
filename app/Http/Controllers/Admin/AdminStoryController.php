<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Story;
use App\Services\Admin\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminStoryController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    public function index(Request $request): View
    {
        $query = Story::withCount('products');

        if ($request->filled('search')) {
            $term = (string) $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('title_bn', 'like', "%{$term}%")
                  ->orWhere('category', 'like', "%{$term}%");
            });
        }

        $stories = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.stories.index', [
            'stories' => $stories,
        ]);
    }

    public function create(): View
    {
        $products = Product::orderBy('title')->get();

        return view('admin.stories.form', [
            'story' => new Story(),
            'products' => $products,
            'linkedProductIds' => [],
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:stories,slug',
            'category' => 'required|string|max:100',
            'category_bn' => 'nullable|string|max:100',
            'author_name' => 'required|string|max:150',
            'author_name_bn' => 'nullable|string|max:150',
            'hero_image' => 'nullable|url|max:500',
            'reading_time' => 'required|integer|min:1',
            'excerpt' => 'nullable|string|max:1000',
            'excerpt_bn' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'content_bn' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);

        $story = Story::create([
            'uuid' => (string) Str::uuid(),
            'slug' => $slug,
            'title' => $validated['title'],
            'title_bn' => $validated['title_bn'] ?? null,
            'category' => $validated['category'],
            'category_bn' => $validated['category_bn'] ?? null,
            'author_name' => $validated['author_name'],
            'author_name_bn' => $validated['author_name_bn'] ?? null,
            'hero_image' => $validated['hero_image'] ?? 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200',
            'reading_time' => (int) $validated['reading_time'],
            'excerpt' => $validated['excerpt'] ?? null,
            'excerpt_bn' => $validated['excerpt_bn'] ?? null,
            'content' => $validated['content'],
            'content_bn' => $validated['content_bn'] ?? null,
            'is_published' => (bool) ($request->has('is_published')),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        if (!empty($validated['product_ids'])) {
            $story->products()->sync($validated['product_ids']);
        }

        $this->activityLog->log('story_created', "Published or saved story #{$story->id} ({$story->title})");

        return redirect()->route('admin.stories.index')->with('success', 'Story created successfully.');
    }

    public function edit(int $id): View
    {
        $story = Story::with('products')->findOrFail($id);
        $products = Product::orderBy('title')->get();
        $linkedProductIds = $story->products->pluck('id')->toArray();

        return view('admin.stories.form', [
            'story' => $story,
            'products' => $products,
            'linkedProductIds' => $linkedProductIds,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $story = Story::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:stories,slug,' . $story->id,
            'category' => 'required|string|max:100',
            'category_bn' => 'nullable|string|max:100',
            'author_name' => 'required|string|max:150',
            'author_name_bn' => 'nullable|string|max:150',
            'hero_image' => 'nullable|url|max:500',
            'reading_time' => 'required|integer|min:1',
            'excerpt' => 'nullable|string|max:1000',
            'excerpt_bn' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'content_bn' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $story->update([
            'slug' => Str::slug($validated['slug']),
            'title' => $validated['title'],
            'title_bn' => $validated['title_bn'] ?? null,
            'category' => $validated['category'],
            'category_bn' => $validated['category_bn'] ?? null,
            'author_name' => $validated['author_name'],
            'author_name_bn' => $validated['author_name_bn'] ?? null,
            'hero_image' => $validated['hero_image'] ?? $story->hero_image,
            'reading_time' => (int) $validated['reading_time'],
            'excerpt' => $validated['excerpt'] ?? null,
            'excerpt_bn' => $validated['excerpt_bn'] ?? null,
            'content' => $validated['content'],
            'content_bn' => $validated['content_bn'] ?? null,
            'is_published' => (bool) ($request->has('is_published')),
            'published_at' => $request->has('is_published') && !$story->published_at ? now() : $story->published_at,
        ]);

        if (isset($validated['product_ids'])) {
            $story->products()->sync($validated['product_ids']);
        } else {
            $story->products()->detach();
        }

        $this->activityLog->log('story_updated', "Updated story #{$story->id} ({$story->title})");

        return redirect()->route('admin.stories.index')->with('success', 'Story updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $story = Story::findOrFail($id);
        $title = $story->title;
        $story->delete();

        $this->activityLog->log('story_deleted', "Deleted story #{$id} ({$title})");

        return redirect()->route('admin.stories.index')->with('success', 'Story removed successfully.');
    }
}
