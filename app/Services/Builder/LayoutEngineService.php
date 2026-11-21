<?php

declare(strict_types=1);

namespace App\Services\Builder;

use App\Contracts\LayoutEngineContract;
use App\DTO\LayoutBlockDTO;
use App\Enums\BlockType;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LayoutEngineService implements LayoutEngineContract
{
    public function __construct(
        protected BlockRegistry $blockRegistry,
        protected DynamicDataParserService $parserService
    ) {}

    /**
     * @return Collection<int, LayoutBlockDTO>
     */
    public function parseBlocks(array|string|null $payload): Collection
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        if (empty($payload)) {
            return $this->getDefaultBlockCollection();
        }

        $rawBlocks = $payload['sections'] ?? $payload['blocks'] ?? null;

        if (! is_array($rawBlocks) || empty($rawBlocks)) {
            return $this->getDefaultBlockCollection();
        }

        return collect($rawBlocks)
            ->filter(fn ($item) => is_array($item) && isset($item['type']) && BlockType::tryFrom($item['type']) !== null)
            ->map(fn (array $blockData, int $index) => LayoutBlockDTO::fromArray(array_merge(['order' => $index + 1], $blockData)))
            ->sortBy('order')
            ->values();
    }

    /**
     * @return array<int, string>
     */
    public function renderProductBlocks(Product $product): array
    {
        $blocks = $this->parseBlocks($product->layout_config);
        $renderedBlocks = [];

        foreach ($blocks as $block) {
            if (! $block->isActive) {
                continue;
            }

            try {
                $parsedSettings = $this->parserService->parseSettings($block->settings, $product);
                $parsedBlock = new LayoutBlockDTO(
                    id: $block->id,
                    type: $block->type,
                    order: $block->order,
                    isActive: $block->isActive,
                    settings: $parsedSettings
                );

                $renderer = $this->blockRegistry->getRenderer($block->type->value);
                $renderedHtml = (string) $renderer->render($parsedBlock, $product);
                $renderedBlocks[] = $renderedHtml;
            } catch (\Throwable $e) {
                Log::error("Failed to render block [{$block->type->value}]: {$e->getMessage()}", [
                    'product_id' => $product->id,
                    'block_id' => $block->id,
                ]);
            }
        }

        return $renderedBlocks;
    }

    public function validatePayload(array $payload): array
    {
        $errors = [];

        $rawBlocks = $payload['sections'] ?? $payload['blocks'] ?? null;

        if (! is_array($rawBlocks)) {
            $errors['blocks'] = 'Payload must include an array of sections or blocks.';
            return $errors;
        }

        foreach ($rawBlocks as $index => $block) {
            if (! isset($block['type']) || BlockType::tryFrom($block['type']) === null) {
                $errors["blocks.{$index}.type"] = "Invalid or missing block type at index {$index}.";
            }
        }

        return $errors;
    }

    /**
     * @return Collection<int, LayoutBlockDTO>
     */
    protected function getDefaultBlockCollection(): Collection
    {
        return collect([
            new LayoutBlockDTO(
                id: 'blk_hero_default',
                type: BlockType::HERO_CINEMATIC,
                order: 1,
                isActive: true,
                settings: [
                    'badge_text' => 'GI Certified Heritage Artifact',
                    'aspect_ratio' => '16/9',
                ]
            ),
            new LayoutBlockDTO(
                id: 'blk_sticky_buy_default',
                type: BlockType::STICKY_PURCHASE_BOX,
                order: 2,
                isActive: true,
                settings: [
                    'enable_floating_dock' => true,
                ]
            ),
            new LayoutBlockDTO(
                id: 'blk_artisan_default',
                type: BlockType::ARTISAN_SPOTLIGHT,
                order: 3,
                isActive: true,
                settings: [
                    'layout_style' => 'split_editorial',
                ]
            ),
            new LayoutBlockDTO(
                id: 'blk_gallery_default',
                type: BlockType::INTERACTIVE_GALLERY,
                order: 4,
                isActive: true,
                settings: [
                    'columns' => 2,
                ]
            ),
            new LayoutBlockDTO(
                id: 'blk_provenance_default',
                type: BlockType::HERITAGE_PROVENANCE,
                order: 5,
                isActive: true,
                settings: [
                    'show_river_basin' => true,
                ]
            ),
            new LayoutBlockDTO(
                id: 'blk_specs_default',
                type: BlockType::SPECS_ANATOMY,
                order: 6,
                isActive: true,
                settings: []
            ),
            new LayoutBlockDTO(
                id: 'blk_testimonial_default',
                type: BlockType::TESTIMONIAL_EDITORIAL,
                order: 7,
                isActive: true,
                settings: []
            ),
        ]);
    }
}
