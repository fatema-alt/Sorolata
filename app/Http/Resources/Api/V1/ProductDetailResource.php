<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_title' => $this->short_title ?? $this->title,
            'subtitle' => $this->subtitle,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'internal_code' => $this->internal_code,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'compare_at_price' => $this->compare_at_price ? (float) $this->compare_at_price : null,
            'effective_price' => (float) $this->effective_price,
            'formatted_price' => $this->formatted_price,
            'formatted_compare_price' => $this->formatted_compare_price,
            'currency' => $this->currency->value ?? 'BDT',
            'stock_quantity' => (int) $this->stock_quantity,
            'available_stock' => (int) $this->available_stock,
            'is_in_stock' => (bool) $this->is_in_stock,
            'weight' => $this->weight ? (float) $this->weight : null,
            'dimensions' => $this->dimensions ?? [],
            'shipping_class' => $this->shipping_class,
            'delivery_estimate' => $this->delivery_estimate,
            'free_shipping_eligible' => (bool) $this->free_shipping_eligible,
            'is_featured' => (bool) $this->is_featured,
            'status' => $this->status->value ?? 'published',
            
            // Central Media Gallery
            'media' => $this->media->map(fn($m) => [
                'id' => $m->id,
                'url' => $m->file_path,
                'alt' => $m->alt_text,
                'aspect_ratio' => $m->aspect_ratio,
                'blur_placeholder' => $m->blur_placeholder,
            ]),

            // Category & Heritage
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'district' => $this->district ? [
                'id' => $this->district->id,
                'name_en' => $this->district->name_en,
                'name_bn' => $this->district->name_bn,
                'gi_specialty' => $this->district->gi_specialty,
                'river_basin' => $this->district->river_basin,
            ] : null,
            'artisan' => $this->artisan ? [
                'id' => $this->artisan->id,
                'full_name' => $this->artisan->full_name,
                'atelier_name' => $this->artisan->atelier_name,
                'years_of_mastery' => $this->artisan->years_of_mastery,
                'quote' => $this->artisan->quote,
                'is_verified' => (bool) $this->artisan->is_verified,
            ] : null,

            // Separated Content Architecture (Part 07)
            'content' => new ProductContentResource($this->content),

            // Provenance & GI Certification
            'origin' => $this->origin ? [
                'gi_certificate_number' => $this->origin->gi_certificate_number,
                'harvest_season' => $this->origin->harvest_season,
                'provenance_story' => $this->origin->provenance_story,
                'geo_coordinates' => $this->origin->geo_coordinates,
                'craft_type' => $this->origin->craftType ? [
                    'name' => $this->origin->craftType->name,
                    'bn_name' => $this->origin->craftType->bn_name,
                    'is_gi_certified' => (bool) $this->origin->craftType->is_gi_certified,
                ] : null,
            ] : null,

            // Materials
            'materials' => $this->materials->map(fn($mat) => [
                'name' => $mat->name,
                'bn_name' => $mat->bn_name,
                'is_sustainable' => (bool) $mat->is_sustainable,
                'percentage' => (int) $mat->pivot->percentage,
            ]),

            // Variants
            'variants' => ProductVariantResource::collection($this->variants),

            // Specifications & FAQs
            'specifications' => ProductSpecificationResource::collection($this->specifications),
            'faqs' => ProductFaqResource::collection($this->faqs),

            // SEO Metadata
            'seo' => $this->seo ? [
                'meta_title' => $this->seo->meta_title,
                'meta_description' => $this->seo->meta_description,
                'canonical_url' => $this->seo->canonical_url,
                'schema_type' => $this->seo->schema_type,
            ] : null,

            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
