<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Enums\StockMovementType;
use App\Models\Discount;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductRelationship;
use App\Models\ProductVariant;
use App\Models\ShippingRate;
use App\Models\ShippingZone;
use App\Models\TaxClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommerceEngineSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Shipping Zones & Rates
        $dhakaZone = ShippingZone::firstOrCreate(['name' => 'Dhaka Metropolitan'], [
            'country_code' => 'BD',
            'regions' => ['Dhaka', 'Gazipur', 'Narayanganj'],
            'is_active' => true,
        ]);

        ShippingRate::firstOrCreate(['shipping_zone_id' => $dhakaZone->id, 'name' => 'Express White-Glove (Next Day)'], [
            'rate_type' => 'flat',
            'base_rate' => 80.0,
            'per_kg_rate' => 0.0,
            'free_shipping_min_amount' => 3000.0,
            'estimated_days' => '1-2 Days',
            'is_active' => true,
        ]);

        $bdZone = ShippingZone::firstOrCreate(['name' => 'Rest of Bangladesh'], [
            'country_code' => 'BD',
            'regions' => ['Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla', 'Bogra', 'Tangail'],
            'is_active' => true,
        ]);

        ShippingRate::firstOrCreate(['shipping_zone_id' => $bdZone->id, 'name' => 'Insured Ground Courier'], [
            'rate_type' => 'weight_based',
            'base_rate' => 130.0,
            'per_kg_rate' => 30.0,
            'free_shipping_min_amount' => 5000.0,
            'estimated_days' => '2-4 Days',
            'is_active' => true,
        ]);

        $globalZone = ShippingZone::firstOrCreate(['name' => 'Global Expat Courier (USA, UK, Canada, EU)'], [
            'country_code' => 'INT',
            'regions' => ['USA', 'UK', 'Canada', 'Australia', 'Germany', 'France'],
            'is_active' => true,
        ]);

        ShippingRate::firstOrCreate(['shipping_zone_id' => $globalZone->id, 'name' => 'DHL Express Air Heritage'], [
            'rate_type' => 'weight_based',
            'base_rate' => 3500.0,
            'per_kg_rate' => 800.0,
            'free_shipping_min_amount' => 35000.0,
            'estimated_days' => '3-5 Business Days',
            'is_active' => true,
        ]);

        // 2. Tax Classes
        TaxClass::firstOrCreate(['name' => 'Standard VAT'], [
            'rate' => 5.0,
            'is_compound' => false,
            'is_active' => true,
        ]);

        TaxClass::firstOrCreate(['name' => 'GI Certified Heritage Exemption'], [
            'rate' => 0.0,
            'is_compound' => false,
            'is_active' => true,
        ]);

        TaxClass::firstOrCreate(['name' => 'Export Zero-Rated'], [
            'rate' => 0.0,
            'is_compound' => false,
            'is_active' => true,
        ]);

        // 3. Promotional Discounts & Coupons
        Discount::firstOrCreate(['code' => 'BENGAL10'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'First Collector Privilege (10% Off)',
            'type' => DiscountType::PERCENTAGE,
            'value' => 10.0,
            'min_subtotal' => 5000.0,
            'max_discount_amount' => 2000.0,
            'usage_limit' => 500,
            'is_active' => true,
        ]);

        Discount::firstOrCreate(['code' => 'HERITAGE2000'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Generational Guild Reward (৳2,000 Off)',
            'type' => DiscountType::FIXED_AMOUNT,
            'value' => 2000.0,
            'min_subtotal' => 15000.0,
            'usage_limit' => 200,
            'is_active' => true,
        ]);

        Discount::firstOrCreate(['code' => 'FREESHIP'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Complimentary Shipping Voucher',
            'type' => DiscountType::FREE_SHIPPING,
            'value' => 0.0,
            'min_subtotal' => 3000.0,
            'is_active' => true,
        ]);

        // 4. Product Variants for Flagship Products
        $muslin = Product::where('slug', 'the-imperial-sonargaon-muslin-scarf')->first();
        if ($muslin) {
            $v1 = ProductVariant::firstOrCreate(['sku' => 'SRL-MSL-IVO-25'], [
                'uuid' => (string) Str::uuid(),
                'product_id' => $muslin->id,
                'title' => 'Ivory White • 2.5-Yard Handspun',
                'price' => 18500.0,
                'compare_at_price' => 22000.0,
                'stock_quantity' => 8,
                'weight' => 0.25,
                'attributes' => ['color' => 'Ivory White', 'length' => '2.5 Yards'],
                'is_active' => true,
            ]);

            $v2 = ProductVariant::firstOrCreate(['sku' => 'SRL-MSL-SAF-30'], [
                'uuid' => (string) Str::uuid(),
                'product_id' => $muslin->id,
                'title' => 'Golden Saffron • 3.0-Yard Archival',
                'price' => 22000.0,
                'compare_at_price' => 26000.0,
                'stock_quantity' => 5,
                'weight' => 0.30,
                'attributes' => ['color' => 'Golden Saffron', 'length' => '3.0 Yards'],
                'is_active' => true,
            ]);

            InventoryLog::firstOrCreate(['product_id' => $muslin->id, 'product_variant_id' => $v1->id], [
                'uuid' => (string) Str::uuid(),
                'type' => StockMovementType::PURCHASE,
                'quantity_delta' => 8,
                'previous_quantity' => 0,
                'new_quantity' => 8,
                'reason' => 'Initial Guild Batch Intake',
            ]);
        }

        $jamdani = Product::where('slug', 'heirloom-tangail-mulberry-silk-jamdani')->first();
        if ($jamdani) {
            $v3 = ProductVariant::firstOrCreate(['sku' => 'SRL-JAM-IND-06'], [
                'uuid' => (string) Str::uuid(),
                'product_id' => $jamdani->id,
                'title' => 'Midnight Indigo • 6-Yard Saree',
                'price' => 32000.0,
                'compare_at_price' => 38000.0,
                'stock_quantity' => 4,
                'weight' => 0.65,
                'attributes' => ['color' => 'Midnight Indigo', 'length' => '6 Yards'],
                'is_active' => true,
            ]);

            $v4 = ProductVariant::firstOrCreate(['sku' => 'SRL-JAM-MAR-06'], [
                'uuid' => (string) Str::uuid(),
                'product_id' => $jamdani->id,
                'title' => 'Imperial Maroon • 6-Yard Saree',
                'price' => 34500.0,
                'compare_at_price' => 40000.0,
                'stock_quantity' => 3,
                'weight' => 0.65,
                'attributes' => ['color' => 'Imperial Maroon', 'length' => '6 Yards'],
                'is_active' => true,
            ]);

            // Relationships (Cross-sell / Pairing)
            if ($muslin) {
                ProductRelationship::firstOrCreate([
                    'product_id' => $jamdani->id,
                    'related_product_id' => $muslin->id,
                    'relation_type' => 'frequently_bought_together',
                ], [
                    'sort_order' => 1,
                ]);

                ProductRelationship::firstOrCreate([
                    'product_id' => $muslin->id,
                    'related_product_id' => $jamdani->id,
                    'relation_type' => 'related',
                ], [
                    'sort_order' => 1,
                ]);
            }
        }
    }
}
