<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class HeritageProvenanceRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::HERITAGE_PROVENANCE->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.heritage_provenance', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
            'district' => $product->district,
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Geographical Indication & District Provenance',
            'category' => 'Heritage & Storytelling',
            'icon' => 'map-pin',
            'fields' => [
                'headline' => ['type' => 'text', 'default' => 'Rooted in Soil & River'],
                'show_river_basin' => ['type' => 'boolean', 'default' => true],
                'show_coordinates' => ['type' => 'boolean', 'default' => true],
            ],
        ];
    }
}
