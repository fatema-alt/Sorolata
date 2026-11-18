<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enrich Products with Enterprise Commerce, Shipping & Heritage Fields
        Schema::table('products', function (Blueprint $table): void {
            $table->string('barcode')->nullable()->index()->after('sku');
            $table->string('internal_code')->nullable()->index()->after('barcode');
            $table->string('short_title')->nullable()->after('title');
            $table->text('short_description')->nullable()->after('subtitle');
            $table->longText('long_description')->nullable()->after('short_description');
            $table->string('product_type')->default('physical')->index()->after('status');
            $table->string('visibility')->default('public')->index()->after('product_type');
            $table->decimal('sale_price', 12, 2)->nullable()->after('price');
            $table->decimal('wholesale_price', 12, 2)->nullable()->after('cost_price');
            $table->string('tax_class')->default('standard')->after('wholesale_price');
            $table->boolean('track_inventory')->default(true)->after('stock_quantity');
            $table->integer('reserved_stock')->default(0)->after('track_inventory');
            $table->integer('low_stock_threshold')->default(5)->after('reserved_stock');
            $table->string('out_of_stock_behavior')->default('deny')->after('low_stock_threshold');
            $table->boolean('is_unlimited_stock')->default(false)->after('out_of_stock_behavior');
            $table->decimal('weight', 8, 3)->nullable()->after('is_unlimited_stock'); // in kg
            $table->json('dimensions')->nullable()->after('weight'); // length, width, height, unit
            $table->decimal('packaging_weight', 8, 3)->nullable()->after('dimensions');
            $table->string('shipping_class')->default('standard')->after('packaging_weight');
            $table->string('delivery_estimate')->nullable()->after('shipping_class');
            $table->boolean('free_shipping_eligible')->default(false)->after('delivery_estimate');
            $table->string('handmade_duration')->nullable()->after('district_id');
            $table->string('traditional_origin')->nullable()->after('handmade_duration');
            $table->string('workshop_name')->nullable()->after('traditional_origin');
            $table->text('care_instructions')->nullable()->after('workshop_name');
        });

        // 2. Product Variants (Color, Size, Material, Custom Specs)
        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique()->index();
            $table->string('barcode')->nullable()->index();
            $table->string('title'); // e.g. "Crimson Red / 6-Yard"
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reserved_stock')->default(0);
            $table->decimal('weight', 8, 3)->nullable();
            $table->json('attributes'); // ['color' => 'Crimson', 'size' => '6-Yard']
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 3. Product Relationships (Related, Similar, Bundles, Accessories)
        Schema::create('product_relationships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('related_product_id')->constrained('products')->cascadeOnDelete();
            $table->string('relation_type')->default('related')->index(); // related, similar, frequently_bought_together, accessory, bundle_item
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'related_product_id', 'relation_type'], 'prod_rel_unique');
        });

        // 4. Enrich Categories & Collections for Enterprise CMS & Rules
        Schema::table('categories', function (Blueprint $table): void {
            $table->string('icon_url')->nullable()->after('hero_image');
            $table->json('seo_meta')->nullable()->after('icon_url');
            $table->json('layout_json')->nullable()->after('seo_meta');
        });

        Schema::table('collections', function (Blueprint $table): void {
            $table->string('type')->default('manual')->after('title'); // manual, automatic, rule_based
            $table->json('rules')->nullable()->after('type');
            $table->string('banner_url')->nullable()->after('cover_image');
            $table->string('icon_url')->nullable()->after('banner_url');
            $table->json('seo_meta')->nullable()->after('icon_url');
            $table->json('layout_json')->nullable()->after('seo_meta');
        });

        Schema::create('collection_product', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['collection_id', 'product_id']);
        });

        // 5. Inventory Engine & Stock Movement Logs
        Schema::create('inventory_logs', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('type')->index(); // purchase, adjustment, sale, return, damage, reservation
            $table->integer('quantity_delta');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->nullableMorphs('reference');
            $table->string('reason')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 6. Discounts & Promotions Engine
        Schema::create('discounts', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->nullable()->unique()->index();
            $table->string('name');
            $table->string('type')->default('percentage')->index(); // percentage, fixed_amount, buy_x_get_y, free_shipping, bundle
            $table->decimal('value', 12, 2);
            $table->decimal('min_subtotal', 12, 2)->default(0);
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->json('conditions')->nullable(); // eligible categories, products, expat flags
            $table->timestamps();
        });

        // 7. Shipping Engine & Zones
        Schema::create('shipping_zones', function (Blueprint $table): void {
            $table->id();
            $table->string('name'); // Dhaka Metro, Rest of Bangladesh, Global Courier
            $table->string('country_code', 3)->default('BD');
            $table->json('regions')->nullable(); // list of district names or postal codes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('shipping_rates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained('shipping_zones')->cascadeOnDelete();
            $table->string('name'); // Express Ground, Insured White-Glove, DHL Air
            $table->string('rate_type')->default('flat'); // flat, weight_based, price_based
            $table->decimal('base_rate', 12, 2);
            $table->decimal('per_kg_rate', 12, 2)->default(0);
            $table->decimal('min_weight', 8, 3)->nullable();
            $table->decimal('max_weight', 8, 3)->nullable();
            $table->decimal('free_shipping_min_amount', 12, 2)->nullable();
            $table->string('estimated_days')->default('2-4 Business Days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Tax Classes
        Schema::create('tax_classes', function (Blueprint $table): void {
            $table->id();
            $table->string('name'); // Standard VAT, Handicraft Exemption, Zero-Rated Export
            $table->decimal('rate', 5, 2)->default(0); // e.g. 5.00 for 5%
            $table->boolean('is_compound')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 9. Enrich Orders with Gift Wrapping, Tax & Shipping Details
        Schema::table('orders', function (Blueprint $table): void {
            $table->decimal('tax_amount', 12, 2)->default(0)->after('shipping_fee');
            $table->boolean('is_gift')->default(false)->after('notes');
            $table->boolean('gift_wrap')->default(false)->after('is_gift');
            $table->decimal('gift_wrap_fee', 12, 2)->default(0)->after('gift_wrap');
            $table->string('gift_recipient_name')->nullable()->after('gift_wrap_fee');
            $table->text('gift_message')->nullable()->after('gift_recipient_name');
            $table->boolean('gift_receipt')->default(false)->after('gift_message');
            $table->foreignId('shipping_rate_id')->nullable()->after('payment_gateway')->constrained('shipping_rates')->nullOnDelete();
            $table->string('tracking_number')->nullable()->after('shipping_rate_id');
            $table->string('courier_name')->nullable()->after('tracking_number');
        });

        Schema::table('order_items', function (Blueprint $table): void {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            $table->string('variant_title')->nullable()->after('product_title');
        });

        // 10. Order Timelines (Immutable Audit Trail)
        Schema::create('order_timelines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status'); // pending, confirmed, packed, shipped, delivered, completed, cancelled, refunded
            $table->string('actor_type')->default('system'); // system, staff, customer
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->text('note')->nullable();
            $table->boolean('customer_notified')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 11. Customer Saved Addresses
        Schema::create('user_addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->default('Home');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('division')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code', 3)->default('BD');
            $table->boolean('is_default_shipping')->default(false);
            $table->boolean('is_default_billing')->default(false);
            $table->timestamps();
        });

        // 12. Return & Refund Request Engine
        Schema::create('return_requests', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending')->index(); // pending, approved, rejected, item_received, refunded
            $table->string('reason'); // defective, damaged_in_transit, wrong_item, collector_choice
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->string('refund_type')->default('full'); // full, partial, store_credit
            $table->json('evidence_images')->nullable();
            $table->timestamps();
        });

        // 13. Enrich Reviews with Admin Reply
        Schema::table('reviews', function (Blueprint $table): void {
            $table->text('admin_reply')->nullable()->after('content');
            $table->timestamp('admin_replied_at')->nullable()->after('admin_reply');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table): void {
            $table->dropColumn(['admin_reply', 'admin_replied_at']);
        });

        Schema::dropIfExists('return_requests');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('order_timelines');

        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn(['product_variant_id', 'variant_title']);
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['shipping_rate_id']);
            $table->dropColumn([
                'tax_amount', 'is_gift', 'gift_wrap', 'gift_wrap_fee',
                'gift_recipient_name', 'gift_message', 'gift_receipt',
                'shipping_rate_id', 'tracking_number', 'courier_name'
            ]);
        });

        Schema::dropIfExists('tax_classes');
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('collection_product');

        Schema::table('collections', function (Blueprint $table): void {
            $table->dropColumn(['type', 'rules', 'banner_url', 'icon_url', 'seo_meta', 'layout_json']);
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['icon_url', 'seo_meta', 'layout_json']);
        });

        Schema::dropIfExists('product_relationships');
        Schema::dropIfExists('product_variants');

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn([
                'barcode', 'internal_code', 'short_title', 'short_description', 'long_description',
                'product_type', 'visibility', 'sale_price', 'wholesale_price', 'tax_class',
                'track_inventory', 'reserved_stock', 'low_stock_threshold', 'out_of_stock_behavior',
                'is_unlimited_stock', 'weight', 'dimensions', 'packaging_weight', 'shipping_class',
                'delivery_estimate', 'free_shipping_eligible', 'handmade_duration', 'traditional_origin',
                'workshop_name', 'care_instructions'
            ]);
        });
    }
};
