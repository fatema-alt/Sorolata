<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use App\Enums\ProductStatus;
use App\Enums\ThemeMode;
use App\Traits\HasBilingualAttributes;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuid, SoftDeletes, HasBilingualAttributes;

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'title_bn',
        'short_title',
        'subtitle',
        'subtitle_bn',
        'short_description',
        'short_description_bn',
        'long_description',
        'long_description_bn',
        'sku',
        'barcode',
        'internal_code',
        'price',
        'sale_price',
        'compare_at_price',
        'cost_price',
        'wholesale_price',
        'tax_class',
        'stock_quantity',
        'track_inventory',
        'reserved_stock',
        'low_stock_threshold',
        'out_of_stock_behavior',
        'is_unlimited_stock',
        'weight',
        'dimensions',
        'packaging_weight',
        'shipping_class',
        'delivery_estimate',
        'free_shipping_eligible',
        'product_type',
        'visibility',
        'currency',
        'status',
        'category_id',
        'artisan_id',
        'district_id',
        'handmade_duration',
        'traditional_origin',
        'workshop_name',
        'care_instructions',
        'layout_config',
        'theme_tokens',
        'heritage_specs',
        'story_meta',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'compare_at_price' => 'float',
        'cost_price' => 'float',
        'wholesale_price' => 'float',
        'stock_quantity' => 'integer',
        'reserved_stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_inventory' => 'boolean',
        'is_unlimited_stock' => 'boolean',
        'free_shipping_eligible' => 'boolean',
        'weight' => 'float',
        'packaging_weight' => 'float',
        'dimensions' => 'array',
        'status' => ProductStatus::class,
        'currency' => Currency::class,
        'layout_config' => 'array',
        'theme_tokens' => 'array',
        'heritage_specs' => 'array',
        'story_meta' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Artisan, $this>
     */
    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    /**
     * @return BelongsTo<District, $this>
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @return HasMany<ProductLayout, $this>
     */
    public function layouts(): HasMany
    {
        return $this->hasMany(ProductLayout::class);
    }

    /**
     * @return MorphMany<MediaAsset, $this>
     */
    public function media(): MorphMany
    {
        return $this->morphMany(MediaAsset::class, 'attachable')->orderBy('sort_order');
    }

    /**
     * @return BelongsToMany<Story, $this>
     */
    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'story_product')
            ->withPivot(['sort_order', 'context_note_en', 'context_note_bn'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * @return HasMany<ProductVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * @return HasMany<InventoryLog, $this>
     */
    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    /**
     * @return HasMany<ProductRelationship, $this>
     */
    public function relationships(): HasMany
    {
        return $this->hasMany(ProductRelationship::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_product')->withPivot('sort_order');
    }

    public function content(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductContent::class);
    }

    public function origin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductOrigin::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(ProductDownload::class);
    }

    public function materials(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'product_materials')->withPivot('percentage');
    }

    public function attributeValues(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function seo(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function mediaAttachments(): MorphMany
    {
        return $this->morphMany(MediaAttachment::class, 'mediable')->orderBy('sort_order');
    }

    public function warehouseStocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getAvailableStockAttribute(): int
    {
        if ($this->is_unlimited_stock) {
            return 9999;
        }

        return max(0, $this->stock_quantity - $this->reserved_stock);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->is_unlimited_stock || $this->available_stock > 0;
    }

    public function getEffectivePriceAttribute(): float
    {
        return ($this->sale_price !== null && $this->sale_price > 0) ? $this->sale_price : $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->currency) {
            return $this->currency->format($this->price ?? $this->base_price);
        }

        $amt = (float) ($this->price ?? $this->base_price ?? 0);
        return '৳ ' . number_format($amt);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        $comp = $this->compare_at_price ?? $this->compare_price;
        if (! $comp) {
            return null;
        }

        if ($this->currency) {
            return $this->currency->format($comp);
        }

        return '৳ ' . number_format((float) $comp);
    }

    public function getThemeMode(): ThemeMode
    {
        $mode = $this->theme_tokens['mode'] ?? ThemeMode::WARM_HERITAGE->value;
        return ThemeMode::tryFrom($mode) ?? ThemeMode::WARM_HERITAGE;
    }
}
