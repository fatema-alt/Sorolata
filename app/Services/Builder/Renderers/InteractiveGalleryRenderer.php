<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class InteractiveGalleryRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::INTERACTIVE_GALLERY->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.interactive_gallery', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
            'media' => $product->media,
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Macro Visual Gallery',
            'category' => 'Media & Exhibition',
            'icon' => 'image',
            'fields' => [
                'columns' => ['type' => 'number', 'min' => 1, 'max' => 4, 'default' => 2],
                'caption' => ['type' => 'text', 'default' => 'Observe the intricate handloom motifs.'],
            ],
        ];
    }
}
