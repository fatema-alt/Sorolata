<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =========================================================================
        // 1. HERITAGE DOMAIN: Divisions, Craft Types, Materials, Workshops & Provenance
        // =========================================================================

        Schema::create('divisions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('bn_name');
            $table->string('slug')->unique();
            $table->json('coordinates')->nullable(); // lat, lng
            $table->timestamps();
        });

        // Add division_id to districts if missing
        Schema::table('districts', function (Blueprint $table): void {
            if (!Schema::hasColumn('districts', 'division_id')) {
                $table->foreignId('division_id')->nullable()->after('id')->constrained('divisions')->nullOnDelete();
            }
            if (!Schema::hasColumn('districts', 'coordinates')) {
                $table->json('coordinates')->nullable();
            }
            if (!Schema::hasColumn('districts', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::create('craft_types', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('bn_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('origin_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->boolean('is_gi_certified')->default(false)->index();
            $table->string('icon')->nullable(); // SVG icon token (strictly no AI icons)
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('materials', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('bn_name');
            $table->string('slug')->unique();
            $table->string('origin')->nullable();
            $table->boolean('is_sustainable')->default(true);
            $table->string('certification')->nullable(); // e.g. GOTS Certified Organic, OEKO-TEX
            $table->timestamps();
        });

        Schema::create('workshops', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('name')->index();
            $table->string('location');
            $table->string('craft_specialty')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->unsignedInteger('workers_count')->default(1);
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        // Dedicated Product Provenance & Living Origin Entity
        Schema::create('product_origins', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->foreignId('workshop_id')->nullable()->constrained('workshops')->nullOnDelete();
            $table->foreignId('craft_type_id')->nullable()->constrained('craft_types')->nullOnDelete();
            $table->string('gi_certificate_number')->nullable()->index();
            $table->string('harvest_season')->nullable();
            $table->text('provenance_story')->nullable();
            $table->json('geo_coordinates')->nullable();
            $table->timestamps();
        });

        Schema::create('product_materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->unsignedTinyInteger('percentage')->default(100);
            $table->unique(['product_id', 'material_id']);
        });

        // =========================================================================
        // 2. CONTENT & COMMERCE SEPARATION: Product Content, Specs, FAQs & Downloads
        // =========================================================================

        Schema::create('product_contents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();
            $table->string('story_title')->nullable();
            $table->longText('story_narrative')->nullable();
            $table->text('craft_technique')->nullable();
            $table->text('heritage_significance')->nullable();
            $table->text('care_guide')->nullable();
            $table->string('video_embed_url')->nullable();
            $table->foreignId('builder_layout_id')->nullable()->constrained('product_layouts')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('product_specifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('group_name')->default('General')->index();
            $table->string('attribute_name');
            $table->text('attribute_value');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('product_faqs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['product_id', 'is_active', 'sort_order']);
        });

        Schema::create('product_downloads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_url');
            $table->unsignedBigInteger('file_size')->default(0); // in bytes
            $table->unsignedInteger('download_limit')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Dynamic Product Attributes (Color, Size, Weave, Custom options)
        Schema::create('attributes', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('type')->default('select'); // select, radio, color, button
            $table->boolean('is_filterable')->default(true)->index();
            $table->boolean('is_searchable')->default(true);
            $table->timestamps();
        });

        Schema::create('attribute_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete();
            $table->string('label');
            $table->string('value');
            $table->string('color_hex', 7)->nullable();
            $table->string('swatch_image_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['attribute_id', 'sort_order']);
        });

        Schema::create('product_attribute_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->cascadeOnDelete();
            $table->unique(['product_id', 'attribute_value_id'], 'prod_attr_val_unique');
        });

        // Generalized Tags and Taggables
        Schema::create('tags', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('type')->default('product')->index(); // product, story, collection
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->morphs('taggable');
            $table->unique(['tag_id', 'taggable_id', 'taggable_type'], 'taggables_unique');
        });

        // =========================================================================
        // 3. CENTRALIZED POLYMORPHIC MEDIA ATTACHMENTS & SEO METADATA
        // =========================================================================

        Schema::create('media_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->morphs('mediable');
            $table->string('role')->default('gallery')->index(); // hero, gallery, thumbnail, video, document, signature
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['mediable_type', 'mediable_id', 'role']);
        });

        Schema::create('seo_metadata', function (Blueprint $table): void {
            $table->id();
            $table->morphs('seoable');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image_url')->nullable();
            $table->string('schema_type')->default('WebPage'); // Product, Article, Organization, Place
            $table->string('robots')->default('index,follow');
            $table->json('focus_keywords')->nullable();
            $table->boolean('is_indexable')->default(true);
            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id']);
        });

        Schema::create('url_redirects', function (Blueprint $table): void {
            $table->id();
            $table->string('source_url')->unique()->index();
            $table->string('destination_url');
            $table->unsignedSmallInteger('status_code')->default(301); // 301, 302
            $table->unsignedBigInteger('hits_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // =========================================================================
        // 4. CUSTOMER PROFILE, CRM NOTES, WISHLIST & PRODUCT COMPARES
        // =========================================================================

        Schema::create('customer_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('avatar_url')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('preferred_currency', 3)->default('BDT');
            $table->string('preferred_locale', 5)->default('en');
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->string('membership_tier')->default('bronze')->index(); // bronze, silver, gold, patron
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->unsignedInteger('orders_count')->default(0);
            $table->timestamps();
        });

        Schema::create('customer_notes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note');
            $table->boolean('is_pinned')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('wishlists', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });

        Schema::create('product_compares', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['session_id', 'product_id']);
        });

        // =========================================================================
        // 5. COMMERCE: Persistent Cart, Cart Items, Payments & Ledger
        // =========================================================================

        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->string('currency', 3)->default('BDT');
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->json('custom_options')->nullable();
            $table->timestamps();

            $table->index(['cart_id', 'product_id']);
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('gateway')->index(); // bkash, nagad, stripe, sslcommerz, cod
            $table->string('transaction_id')->unique()->nullable()->index();
            $table->string('currency', 3)->default('BDT');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending')->index(); // pending, completed, failed, refunded
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        // =========================================================================
        // 6. MULTI-WAREHOUSE & STOCK ADJUSTMENT AUDIT
        // =========================================================================

        Schema::create('warehouses', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('code')->unique();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address');
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('warehouse_stocks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('safety_stock')->default(5);
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id', 'variant_id'], 'wh_prod_var_unique');
        });

        Schema::create('stock_adjustments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->integer('delta'); // e.g. +10, -3
            $table->string('reason')->index(); // received, sold, damaged, count_reconciliation, return
            $table->nullableMorphs('reference'); // e.g. Order, ReturnRequest
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // =========================================================================
        // 7. MARKETING: Campaigns, Popups, Announcements & Email Templates
        // =========================================================================

        Schema::create('campaigns', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('banner_url')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->string('discount_type')->nullable(); // percentage, fixed_amount
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->text('terms')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('marketing_popups', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('trigger_type')->default('delay'); // delay, exit_intent, scroll
            $table->unsignedSmallInteger('delay_seconds')->default(5);
            $table->json('content'); // headline, body, cta_text, cta_url, bg_color
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->string('text');
            $table->string('bn_text')->nullable();
            $table->string('link_url')->nullable();
            $table->string('bg_color')->default('#214E34');
            $table->string('text_color')->default('#F8F6F2');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique()->index(); // order_confirmed, shipping_dispatched, artisan_certificate
            $table->string('subject');
            $table->longText('body_html');
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // =========================================================================
        // 8. NAVIGATION, MENUS & GLOBAL BUILDER COMPONENTS
        // =========================================================================

        Schema::create('menus', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique(); // header_main, mega_menu, footer_story, footer_support
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('title');
            $table->string('bn_title')->nullable();
            $table->string('url');
            $table->string('icon')->nullable(); // Clean utility icon only
            $table->string('target', 10)->default('_self');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['menu_id', 'parent_id', 'sort_order']);
        });

        Schema::create('builder_global_components', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('category')->default('general')->index(); // layout, commerce, media, story
            $table->json('content'); // Reusable block structure
            $table->timestamps();
        });

        Schema::create('design_tokens', function (Blueprint $table): void {
            $table->id();
            $table->string('token_key')->unique(); // color_primary, font_heading, spacing_unit
            $table->string('token_group')->default('theme')->index();
            $table->string('token_value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // =========================================================================
        // 9. ANALYTICS & TELEMETRY: Visitors, Events & Search Logs
        // =========================================================================

        Schema::create('site_visitors', function (Blueprint $table): void {
            $table->id();
            $table->string('ip_hash', 64)->index();
            $table->string('user_agent')->nullable();
            $table->string('device_type', 20)->default('desktop')->index(); // mobile, tablet, desktop
            $table->string('country_code', 3)->nullable()->index();
            $table->string('city')->nullable();
            $table->timestamp('first_visit_at')->nullable();
            $table->timestamp('last_visit_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('analytics_events', function (Blueprint $table): void {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_name')->index(); // view_product, add_to_cart, checkout_started, purchase_completed
            $table->string('event_category')->default('commerce')->index();
            $table->json('event_properties')->nullable();
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamp('created_at')->index();
        });

        Schema::create('search_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('query')->index();
            $table->unsignedInteger('results_count')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_hash', 64)->nullable();
            $table->string('session_id')->nullable();
            $table->foreignId('clicked_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamp('created_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('site_visitors');
        Schema::dropIfExists('design_tokens');
        Schema::dropIfExists('builder_global_components');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('marketing_popups');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('warehouse_stocks');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_compares');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('customer_notes');
        Schema::dropIfExists('customer_profiles');
        Schema::dropIfExists('url_redirects');
        Schema::dropIfExists('seo_metadata');
        Schema::dropIfExists('media_attachments');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('product_downloads');
        Schema::dropIfExists('product_faqs');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_contents');
        Schema::dropIfExists('product_materials');
        Schema::dropIfExists('product_origins');
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('craft_types');
        Schema::dropIfExists('divisions');
    }
};
