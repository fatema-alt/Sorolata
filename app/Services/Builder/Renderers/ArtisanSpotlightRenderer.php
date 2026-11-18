<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class ArtisanSpotlightRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::ARTISAN_SPOTLIGHT->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.artisan_spotlight', [
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
            'title' => 'Artisan Atelier Spotlight',
            'category' => 'Heritage & Storytelling',
            'icon' => 'user-check',
            'fields' => [
                'headline' => ['type' => 'text', 'default' => 'Crafted By The Master'],
                'quote' => ['type' => 'textarea', 'default' => ''],
                'show_signature' => ['type' => 'boolean', 'default' => true],
                'layout_style' => ['type' => 'select', 'options' => ['split_editorial', 'centered_portrait', 'card_minimal'], 'default' => 'split_editorial'],
            ],
        ];
    }
}
