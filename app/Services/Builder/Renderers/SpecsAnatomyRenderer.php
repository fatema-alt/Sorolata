<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class SpecsAnatomyRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::SPECS_ANATOMY->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.specs_anatomy', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
            'specs' => $product->heritage_specs ?? [],
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Heritage Specs & Material Anatomy',
            'category' => 'Editorial Details',
            'icon' => 'list',
            'fields' => [
                'headline' => ['type' => 'text', 'default' => 'Craft Anatomy & Formulation'],
            ],
        ];
    }
}
