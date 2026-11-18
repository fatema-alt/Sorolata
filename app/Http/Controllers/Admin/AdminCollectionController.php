<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use App\Services\Admin\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCollectionController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    public function index(Request $request): View
    {
        $query = Collection::withCount('products');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('type', 'like', "%{$term}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $collections = $query->latest()->paginate(15)->withQueryString();
        $allProducts = Product::select('id', 'title', 'price', 'sku')->orderBy('title')->get();

        return view('admin.collections.index', [
            'collections' => $collections,
            'allProducts' => $allProducts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug',
            'type' => 'required|string|in:manual,seasonal,featured,rule_based',
            'description' => 'nullable|string|max:2000',
            'description_bn' => 'nullable|string|max:2000',
            'cover_image' => 'nullable|string|max:500',
            'cover_image_file' => 'nullable|image|max:5120',
            'banner_url' => 'nullable|string|max:500',
            'is_featured' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        
        // Ensure slug uniqueness
        $baseSlug = $slug;
        $counter = 1;
        while (Collection::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $coverImage = $validated['cover_image'] ?? null;
        if ($request->hasFile('cover_image_file')) {
            $path = $request->file('cover_image_file')->store('collections', 'public');
            $coverImage = '/storage/' . $path;
        }

        $collection = Collection::create([
            'uuid' => (string) Str::uuid(),
            'slug' => $slug,
            'title' => $validated['title'],
            'title_bn' => $validated['title_bn'] ?? null,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'cover_image' => $coverImage,
            'banner_url' => $validated['banner_url'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if (!empty($validated['product_ids'])) {
            $syncData = [];
            foreach ($validated['product_ids'] as $index => $pid) {
                $syncData[$pid] = ['sort_order' => $index + 1];
            }
            $collection->products()->sync($syncData);
        }

        $this->activityLog->log(
            action: 'collection.created',
            description: "Created collection '{$collection->title}' with " . count($validated['product_ids'] ?? []) . " products",
            subject: $collection
        );

        return back()->with('success', "Collection '{$collection->title}' created successfully!");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $collection = Collection::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug,' . $collection->id,
            'type' => 'required|string|in:manual,seasonal,featured,rule_based',
            'description' => 'nullable|string|max:2000',
            'description_bn' => 'nullable|string|max:2000',
            'cover_image' => 'nullable|string|max:500',
            'cover_image_file' => 'nullable|image|max:5120',
            'banner_url' => 'nullable|string|max:500',
            'is_featured' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $coverImage = $validated['cover_image'] ?? $collection->cover_image;
        if ($request->hasFile('cover_image_file')) {
            $path = $request->file('cover_image_file')->store('collections', 'public');
            $coverImage = '/storage/' . $path;
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : $collection->slug;

        $collection->update([
            'title' => $validated['title'],
            'title_bn' => $validated['title_bn'] ?? null,
            'slug' => $slug,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'cover_image' => $coverImage,
            'banner_url' => $validated['banner_url'] ?? $collection->banner_url,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if (isset($validated['product_ids'])) {
            $syncData = [];
            foreach ($validated['product_ids'] as $index => $pid) {
                $syncData[$pid] = ['sort_order' => $index + 1];
            }
            $collection->products()->sync($syncData);
        }

        $this->activityLog->log(
            action: 'collection.updated',
            description: "Updated collection '{$collection->title}'",
            subject: $collection
        );

        return back()->with('success', "Collection '{$collection->title}' updated successfully!");
    }

    public function destroy(int $id): RedirectResponse
    {
        $collection = Collection::findOrFail($id);
        $title = $collection->title;

        $collection->products()->detach();
        $collection->delete();

        $this->activityLog->log(
            action: 'collection.deleted',
            description: "Deleted collection '{$title}'"
        );

        return back()->with('success', "Collection '{$title}' deleted successfully!");
    }
}
