<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class StickyPurchaseBoxRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::STICKY_PURCHASE_BOX->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.sticky_purchase_box', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
            'artisan' => $product->artisan,
            'district' => $product->district,
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Editorial Purchase & Collector Box',
            'category' => 'Commerce & Conversion',
            'icon' => 'shopping-bag',
            'fields' => [
                'enable_floating_dock' => ['type' => 'boolean', 'default' => true],
                'guarantee_badge' => ['type' => 'text', 'default' => '100% Verified Heirloom Authenticity'],
                'dispatch_estimate' => ['type' => 'text', 'default' => 'Dispatched within 48 hours in luxury botanical packaging.'],
            ],
        ];
    }
}
