<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class HeroCinematicRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::HERO_CINEMATIC->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.hero_cinematic', [
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
            'title' => 'Cinematic Hero Showcase',
            'category' => 'Hero & Headers',
            'icon' => 'sparkles',
            'fields' => [
                'headline' => ['type' => 'text', 'default' => ''],
                'subheadline' => ['type' => 'textarea', 'default' => ''],
                'badge_text' => ['type' => 'text', 'default' => 'GI Certified • 100% Handcrafted'],
                'aspect_ratio' => ['type' => 'select', 'options' => ['16/9', '4/3', '1/1'], 'default' => '16/9'],
                'overlay_opacity' => ['type' => 'range', 'min' => 0, 'max' => 80, 'default' => 20],
            ],
        ];
    }
}
