<?php

declare(strict_types=1);

namespace App\Services\Builder;

use App\Contracts\BlockRendererContract;
use App\Enums\BlockType;
use App\Services\Builder\Renderers\ArtisanSpotlightRenderer;
use App\Services\Builder\Renderers\EditorialNarrativeRenderer;
use App\Services\Builder\Renderers\HeritageProvenanceRenderer;
use App\Services\Builder\Renderers\HeroCinematicRenderer;
use App\Services\Builder\Renderers\InteractiveGalleryRenderer;
use App\Services\Builder\Renderers\SpecsAnatomyRenderer;
use App\Services\Builder\Renderers\StickyPurchaseBoxRenderer;
use App\Services\Builder\Renderers\TestimonialEditorialRenderer;
use InvalidArgumentException;

class BlockRegistry
{
    /**
     * @var array<string, class-string<BlockRendererContract>>
     */
    protected array $renderers = [
        BlockType::HERO_CINEMATIC->value => HeroCinematicRenderer::class,
        BlockType::ARTISAN_SPOTLIGHT->value => ArtisanSpotlightRenderer::class,
        BlockType::HERITAGE_PROVENANCE->value => HeritageProvenanceRenderer::class,
        BlockType::INTERACTIVE_GALLERY->value => InteractiveGalleryRenderer::class,
        BlockType::SPECS_ANATOMY->value => SpecsAnatomyRenderer::class,
        BlockType::STICKY_PURCHASE_BOX->value => StickyPurchaseBoxRenderer::class,
        BlockType::EDITORIAL_NARRATIVE->value => EditorialNarrativeRenderer::class,
        BlockType::TESTIMONIAL_EDITORIAL->value => TestimonialEditorialRenderer::class,
        BlockType::ELEMENT_HEADING->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_PARAGRAPH->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_IMAGE->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_VIDEO->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_BUTTON->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_PRODUCT_CARD->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_ARTISAN_QUOTE->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
        BlockType::ELEMENT_DIVIDER->value => \App\Services\Builder\Renderers\GenericElementRenderer::class,
    ];

    public function register(string $blockType, string $rendererClass): void
    {
        $this->renderers[$blockType] = $rendererClass;
    }

    public function getRenderer(string $blockType): BlockRendererContract
    {
        if (str_starts_with($blockType, 'element_')) {
            return app(\App\Services\Builder\Renderers\GenericElementRenderer::class);
        }

        if (! isset($this->renderers[$blockType])) {
            throw new InvalidArgumentException("No renderer registered for block type [{$blockType}].");
        }

        /** @var BlockRendererContract $instance */
        $instance = app($this->renderers[$blockType]);
        return $instance;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getAvailableBlockSchemas(): array
    {
        $schemas = [];
        foreach ($this->renderers as $type => $class) {
            /** @var BlockRendererContract $instance */
            $instance = app($class);
            $schemas[$type] = $instance->schema();
        }

        return $schemas;
    }
}
