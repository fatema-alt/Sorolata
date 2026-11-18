<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class GenericElementRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return str_starts_with($blockType, 'element_');
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.generic_element', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Atomic Modular Element',
            'category' => 'Elements',
            'icon' => 'cube',
            'fields' => [
                'headline' => ['type' => 'text', 'default' => ''],
                'subheadline' => ['type' => 'textarea', 'default' => ''],
                'image_url' => ['type' => 'text', 'default' => ''],
                'video_url' => ['type' => 'text', 'default' => ''],
                'alignment' => ['type' => 'select', 'options' => ['left', 'center', 'right'], 'default' => 'center'],
            ],
        ];
    }
}
