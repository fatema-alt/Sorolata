<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\District;
use App\Models\MediaAsset;
use App\Models\MediaAttachment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Admin\ActivityLogService;
use App\Services\Commerce\InventoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected ActivityLogService $activityLog
    ) {}

    public function index(Request $request): View
    {
        $query = Product::with(['artisan', 'district', 'category', 'media', 'variants']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->input('district_id'));
        }

        $products = $query->latest('created_at')->paginate(15)->withQueryString();
        $districts = District::all();

        return view('admin.products.index', [
            'products' => $products,
            'districts' => $districts,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
            'artisans' => Artisan::all(),
            'districts' => District::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'subtitle_bn' => 'nullable|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:100',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'artisan_id' => 'nullable|exists:artisans,id',
            'district_id' => 'nullable|exists:districts,id',
            'short_description' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'handmade_duration' => 'nullable|string',
            'image_file' => 'nullable|image|max:10240',
            'images.*' => 'nullable|image|max:10240',
            'image_url' => 'nullable|string|max:500',
        ]);

        $product = Product::create([
            'uuid' => (string) Str::uuid(),
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'title' => $validated['title'],
            'title_bn' => $validated['title_bn'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'subtitle_bn' => $validated['subtitle_bn'] ?? null,
            'sku' => strtoupper($validated['sku']),
            'price' => (float) $validated['price'],
            'compare_at_price' => !empty($validated['compare_at_price']) ? (float) $validated['compare_at_price'] : null,
            'stock_quantity' => 0,
            'category_id' => $validated['category_id'] ?? null,
            'artisan_id' => $validated['artisan_id'] ?? null,
            'district_id' => $validated['district_id'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'short_description_bn' => $validated['short_description_bn'] ?? null,
            'handmade_duration' => $validated['handmade_duration'] ?? null,
            'status' => ProductStatus::PUBLISHED,
        ]);

        $this->inventoryService->adjustStock(
            item: $product,
            delta: (int) $validated['stock_quantity'],
            type: StockMovementType::PURCHASE,
            reason: 'Initial Product Intake'
        );

        $this->handleMediaUpload($request, $product);

        $this->activityLog->log(
            action: 'product.created',
            description: "Created artifact: {$product->title} (SKU: {$product->sku})",
            subject: $product
        );

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', "Artifact [{$product->title}] created successfully!");
    }

    public function edit(int $id): View
    {
        $product = Product::with(['artisan', 'district', 'category', 'media', 'variants', 'inventoryLogs'])->findOrFail($id);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::all(),
            'artisans' => Artisan::all(),
            'districts' => District::all(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'subtitle_bn' => 'nullable|string',
            'short_description' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'long_description' => 'nullable|string',
            'long_description_bn' => 'nullable|string',
            'sku' => "required|string|max:100|unique:products,sku,{$product->id}",
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'shipping_class' => 'nullable|string',
            'delivery_estimate' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'artisan_id' => 'nullable|exists:artisans,id',
            'district_id' => 'nullable|exists:districts,id',
            'handmade_duration' => 'nullable|string',
            'traditional_origin' => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'status' => 'required|string',
            'image_file' => 'nullable|image|max:10240',
            'images.*' => 'nullable|image|max:10240',
            'image_url' => 'nullable|string|max:500',
        ]);

        $oldStock = $product->stock_quantity;
        $newStock = (int) $validated['stock_quantity'];

        $product->update($validated);
        $this->handleMediaUpload($request, $product);

        if ($newStock !== $oldStock) {
            $delta = $newStock - $oldStock;
            $this->inventoryService->adjustStock(
                item: $product,
                delta: $delta,
                type: StockMovementType::ADJUSTMENT,
                reason: 'Manual Inventory Reconciliation'
            );
        }

        $this->activityLog->log(
            action: 'product.updated',
            description: "Updated artifact specifications for: {$product->title}",
            subject: $product
        );

        return back()->with('success', 'Artifact details updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $title = $product->title;
        $product->delete();

        $this->activityLog->log(
            action: 'product.deleted',
            description: "Archived artifact: {$title}",
            subject: $product
        );

        return redirect()->route('admin.products.index')
            ->with('success', "Artifact [{$title}] archived.");
    }

    public function duplicate(int $id): RedirectResponse
    {
        $original = Product::findOrFail($id);
        $replica = $original->replicate([
            'uuid', 'sku', 'slug', 'created_at', 'updated_at'
        ]);

        $replica->uuid = (string) Str::uuid();
        $replica->sku = $original->sku . '-COPY-' . strtoupper(Str::random(3));
        $replica->slug = $original->slug . '-copy-' . strtolower(Str::random(4));
        $replica->title = $original->title . ' (Copy)';
        $replica->status = ProductStatus::DRAFT;
        $replica->save();

        $this->activityLog->log(
            action: 'product.duplicated',
            description: "Duplicated artifact from {$original->sku} to {$replica->sku}",
            subject: $replica
        );

        return redirect()->route('admin.products.edit', $replica->id)
            ->with('success', 'Artifact duplicated as draft.');
    }

    protected function handleMediaUpload(Request $request, Product $product): void
    {
        $uploadedFiles = [];
        if ($request->hasFile('image_file')) {
            $uploadedFiles[] = $request->file('image_file');
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $uploadedFiles[] = $file;
                }
            }
        }

        $highestOrder = (int) $product->media()->max('sort_order');
        $sortOrder = $highestOrder + 1;

        foreach ($uploadedFiles as $file) {
            $path = $file->store('products', 'public');
            $filePath = '/storage/' . $path;

            // Redundant copy directly to public/storage for shared hosting environments without symlink support
            try {
                $targetDir = public_path('storage/' . dirname($path));
                if (! is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            } catch (\Throwable) {
                // Fail silently if directory creation or copying is restricted
            }

            $media = MediaAsset::create([
                'uuid' => (string) Str::uuid(),
                'attachable_type' => Product::class,
                'attachable_id' => $product->id,
                'file_path' => $filePath,
                'disk' => 'public',
                'mime_type' => $file->getClientMimeType() ?: 'image/jpeg',
                'file_size' => $file->getSize() ?: 0,
                'alt_text' => $product->title,
                'sort_order' => $sortOrder,
            ]);

            MediaAttachment::create([
                'media_asset_id' => $media->id,
                'mediable_type' => Product::class,
                'mediable_id' => $product->id,
                'role' => $sortOrder === 1 ? 'hero' : 'gallery',
                'sort_order' => $sortOrder,
            ]);

            $sortOrder++;
        }

        if (empty($uploadedFiles) && $request->filled('image_url')) {
            $url = trim((string) $request->input('image_url'));
            if (!empty($url)) {
                $media = MediaAsset::create([
                    'uuid' => (string) Str::uuid(),
                    'attachable_type' => Product::class,
                    'attachable_id' => $product->id,
                    'file_path' => $url,
                    'disk' => 'public',
                    'mime_type' => 'image/jpeg',
                    'file_size' => 0,
                    'alt_text' => $product->title,
                    'sort_order' => $sortOrder,
                ]);

                MediaAttachment::create([
                    'media_asset_id' => $media->id,
                    'mediable_type' => Product::class,
                    'mediable_id' => $product->id,
                    'role' => $sortOrder === 1 ? 'hero' : 'gallery',
                    'sort_order' => $sortOrder,
                ]);
            }
        }
    }

    public function deleteMedia(int $id, int $mediaId): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $media = MediaAsset::where('attachable_type', Product::class)
            ->where('attachable_id', $product->id)
            ->where('id', $mediaId)
            ->firstOrFail();

        // Remove disk files if local
        if (!empty($media->file_path) && str_starts_with($media->file_path, '/storage/')) {
            $rel = substr($media->file_path, strlen('/storage/'));
            @unlink(storage_path('app/public/' . $rel));
            @unlink(public_path('storage/' . $rel));
        }

        MediaAttachment::where('media_asset_id', $media->id)->delete();
        $media->delete();

        return back()->with('success', 'Image removed successfully.');
    }
}
