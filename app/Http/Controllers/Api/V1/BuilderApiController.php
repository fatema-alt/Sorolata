<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\BuilderGlobalComponent;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductLayout;
use Illuminate\Http\JsonResponse;

class BuilderApiController extends BaseApiController
{
    /**
     * Get JSON layout sequence for headless rendering
     */
    public function layout(string $type, string $idOrSlug): JsonResponse
    {
        if ($type === 'product') {
            $product = Product::where('slug', $idOrSlug)
                ->orWhere('id', is_numeric($idOrSlug) ? (int) $idOrSlug : 0)
                ->firstOrFail();

            $layout = ProductLayout::where('product_id', $product->id)
                ->where('is_published', true)
                ->latest('version')
                ->first();

            $blocks = $layout?->block_payload ?? ($product->layout_config['sections'] ?? []);

            return $this->respondWithSuccess([
                'type' => 'product',
                'id' => $product->id,
                'title' => $product->title,
                'version' => $layout?->version ?? 1,
                'sections' => $blocks,
            ], 'Product builder layout retrieved successfully.');
        }

        $page = Page::where('slug', $idOrSlug)
            ->orWhere('id', is_numeric($idOrSlug) ? (int) $idOrSlug : 0)
            ->firstOrFail();

        return $this->respondWithSuccess([
            'type' => 'page',
            'id' => $page->id,
            'title' => $page->title,
            'version' => $page->version,
            'sections' => $page->layout_json['sections'] ?? [],
        ], 'Page builder layout retrieved successfully.');
    }

    /**
     * List global reusable builder components
     */
    public function globalComponents(): JsonResponse
    {
        $components = BuilderGlobalComponent::all();

        return $this->respondWithSuccess($components, 'Global builder components retrieved successfully.');
    }
}
