<?php

declare(strict_types=1);

namespace App\Services\Builder\Renderers;

use App\Contracts\BlockRendererContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class EditorialNarrativeRenderer implements BlockRendererContract
{
    public function handles(string $blockType): bool
    {
        return $blockType === BlockType::EDITORIAL_NARRATIVE->value;
    }

    public function render(LayoutBlockDTO $block, Product $product): View|string
    {
        return ViewFacade::make('builder.blocks.editorial_narrative', [
            'block' => $block,
            'settings' => $block->settings,
            'product' => $product,
        ]);
    }

    public function schema(): array
    {
        return [
            'title' => 'Editorial Story & Cultural Context',
            'category' => 'Editorial Details',
            'icon' => 'book-open',
            'fields' => [
                'headline' => ['type' => 'text', 'default' => 'A Living Chronicle'],
                'story_paragraphs' => ['type' => 'textarea', 'default' => ''],
            ],
        ];
    }
}
