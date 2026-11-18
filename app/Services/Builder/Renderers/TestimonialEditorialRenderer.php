<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class TestimonialEditorialRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::TESTIMONIAL_EDITORIAL->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.testimonial_editorial', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Collector Impressions & Authenticity',
            'category' => 'Editorial Details',
            'icon' => 'message-circle',
            'fields' => [
                'collector_name' => ['type' => 'text', 'default' => 'Curator & Textile Historian'],
                'collector_location' => ['type' => 'text', 'default' => 'London, United Kingdom'],
                'impression' => ['type' => 'textarea', 'default' => 'The tactile presence of this piece carries an undeniable gravitas.'],
            ],
        ];
    }
}
