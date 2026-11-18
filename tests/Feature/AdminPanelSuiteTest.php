<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AdminRole;
use App\Enums\OrderStatus;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Services\Admin\AdminSettingService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            CommerceEngineSeeder::class,
            AdminSeeder::class,
        ]);

        $this->adminUser = User::where('role', AdminRole::SUPER_ADMIN)->firstOrFail();
    }

    public function test_admin_dashboard_renders_with_kpi_metrics(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');
        $response->assertSee("Today's Sales", false);
        $response->assertSee('Total Revenue');
        $response->assertSee('Orders Count');
        $response->assertSee('Top Craft Basins');
    }

    public function test_command_palette_search_api_returns_structured_results(): void
    {
        $product = Product::firstOrFail();

        $response = $this->actingAs($this->adminUser)
            ->getJson(route('admin.command-search', ['q' => substr($product->title, 0, 8)]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['actions', 'products', 'orders', 'customers']);
        $this->assertNotEmpty($response->json('products'));
    }

    public function test_admin_products_index_and_filters(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Artifacts & Heritage Catalog', false);
        $response->assertSee('Search title or SKU');
    }

    public function test_admin_product_creation_and_automatic_inventory_log(): void
    {
        $payload = [
            'title' => 'Imperial Tangail Jamdani Royal Scarf',
            'sku' => 'SRL-TNG-999',
            'price' => 24500.0,
            'compare_at_price' => 28000.0,
            'stock_quantity' => 7,
            'short_description' => 'Woven with gold and silver zari on pit looms.',
            'handmade_duration' => '45 Days Handloom',
        ];

        $response = $this->actingAs($this->adminUser)->post(route('admin.products.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'sku' => 'SRL-TNG-999',
            'stock_quantity' => 7,
        ]);

        $createdProduct = Product::where('sku', 'SRL-TNG-999')->firstOrFail();

        // Check inventory log
        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $createdProduct->id,
            'quantity_delta' => 7,
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'product.created',
        ]);
    }

    public function test_admin_product_update_and_inventory_reconciliation(): void
    {
        $product = Product::firstOrFail();

        $payload = [
            'title' => $product->title . ' (Curated Heritage)',
            'sku' => $product->sku,
            'price' => $product->price + 1000,
            'stock_quantity' => $product->stock_quantity + 5,
            'status' => 'published',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.products.update', $product->id), $payload);

        $response->assertRedirect();

        $product->refresh();
        $this->assertStringContainsString('(Curated Heritage)', $product->title);

        // Inventory log created for stock change
        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $product->id,
            'quantity_delta' => 5,
        ]);
    }

    public function test_admin_product_duplication(): void
    {
        $original = Product::firstOrFail();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.products.duplicate', $original->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'title' => $original->title . ' (Copy)',
            'status' => 'draft',
        ]);
    }

    public function test_admin_orders_index_and_detail_inspection(): void
    {
        $order = Order::firstOrFail();

        $indexRes = $this->actingAs($this->adminUser)->get(route('admin.orders.index'));
        $indexRes->assertStatus(200);
        $indexRes->assertSee($order->order_number);

        $showRes = $this->actingAs($this->adminUser)->get(route('admin.orders.show', $order->id));
        $showRes->assertStatus(200);
        $showRes->assertSee($order->order_number);
        $showRes->assertSee('Acquired Heirloom Artifacts');
        $showRes->assertSee('Immutable Order Audit Timeline');
    }

    public function test_admin_order_status_transition_and_timeline_logging(): void
    {
        $order = Order::firstOrFail();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.orders.update-status', $order->id), [
                'status' => OrderStatus::PACKED->value,
                'note' => 'Carefully packaged with silk wrap and wax seal.',
            ]);

        $response->assertRedirect();
        $order->refresh();

        $this->assertEquals(OrderStatus::PACKED, $order->status);

        $this->assertDatabaseHas('order_timelines', [
            'order_id' => $order->id,
            'status' => OrderStatus::PACKED->value,
            'note' => 'Carefully packaged with silk wrap and wax seal.',
        ]);
    }

    public function test_admin_order_shipping_tracking_update(): void
    {
        $order = Order::firstOrFail();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.orders.update-shipping', $order->id), [
                'courier_name' => 'DHL Express Air',
                'tracking_number' => 'DHL-BENGAL-998877',
            ]);

        $response->assertRedirect();
        $order->refresh();

        $this->assertEquals('DHL Express Air', $order->courier_name);
        $this->assertEquals('DHL-BENGAL-998877', $order->tracking_number);
        $this->assertEquals(OrderStatus::SHIPPED, $order->status);
    }

    public function test_admin_order_invoice_certificate_render(): void
    {
        $order = Order::firstOrFail();

        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.invoice', $order->id));

        $response->assertStatus(200);
        $response->assertSee('Master Artisan Guild Provenance Ledger');
        $response->assertSee($order->order_number);
    }

    public function test_admin_review_moderation_and_curator_reply(): void
    {
        $product = Product::firstOrFail();

        $review = Review::create([
            'product_id' => $product->id,
            'author_name' => 'Farhana Begum',
            'rating' => 5,
            'content' => 'The handloom density and drape of this muslin scarf is unparalleled.',
            'is_verified_purchase' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.reviews.reply', $review->id), [
                'admin_reply' => 'Thank you for supporting our fourth-generation weavers.',
            ]);

        $response->assertRedirect();
        $review->refresh();

        $this->assertEquals('Thank you for supporting our fourth-generation weavers.', $review->admin_reply);
        $this->assertNotNull($review->admin_replied_at);
    }

    public function test_admin_promotional_coupon_creation_and_removal(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.discounts.store'), [
            'code' => 'TESTPRIVILEGE20',
            'name' => 'Curator Circle 20% Off',
            'type' => 'percentage',
            'value' => 20.0,
            'min_subtotal' => 8000.0,
            'usage_limit' => 50,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('discounts', [
            'code' => 'TESTPRIVILEGE20',
            'value' => 20.0,
        ]);

        $created = Discount::where('code', 'TESTPRIVILEGE20')->firstOrFail();

        $deleteRes = $this->actingAs($this->adminUser)->delete(route('admin.discounts.destroy', $created->id));
        $deleteRes->assertRedirect();
        $this->assertDatabaseMissing('discounts', ['code' => 'TESTPRIVILEGE20']);
    }

    public function test_admin_settings_persistence(): void
    {
        $response = $this->actingAs($this->adminUser)->put(route('admin.settings.update'), [
            'store_name' => 'Sorolata Royal Atelier',
            'shipping_free_threshold' => 6000,
        ]);

        $response->assertRedirect();

        /** @var AdminSettingService $settingService */
        $settingService = app(AdminSettingService::class);
        $this->assertEquals('Sorolata Royal Atelier', $settingService->get('store_name'));
        $this->assertEquals(6000, (int) $settingService->get('shipping_free_threshold'));
    }

    public function test_admin_activity_logs_listing(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Administrative Activity Ledger', false);
    }

    public function test_admin_artisan_and_district_directory_views(): void
    {
        $artisanRes = $this->actingAs($this->adminUser)->get(route('admin.artisans.index'));
        $artisanRes->assertStatus(200);
        $artisanRes->assertSee('Artisan Guild Masters');

        $distRes = $this->actingAs($this->adminUser)->get(route('admin.districts.index'));
        $distRes->assertStatus(200);
        $distRes->assertSee('Geographical Basins');

        $collRes = $this->actingAs($this->adminUser)->get(route('admin.collections.index'));
        $collRes->assertStatus(200);
        $collRes->assertSee('Curated Heritage Collections');
    }

    public function test_admin_can_create_update_and_delete_collection(): void
    {
        $product = Product::firstOrFail();

        // 1. Create Collection
        $createRes = $this->actingAs($this->adminUser)->post(route('admin.collections.store'), [
            'title' => 'Nakshi Kantha Heritage Archive',
            'type' => 'seasonal',
            'description' => 'Fine embroidered vintage kanthas of Jessore.',
            'cover_image' => 'https://images.unsplash.com/photo-1600000000000',
            'is_featured' => 1,
            'product_ids' => [$product->id],
        ]);

        $createRes->assertRedirect();
        $createRes->assertSessionHas('success');

        $this->assertDatabaseHas('collections', [
            'title' => 'Nakshi Kantha Heritage Archive',
            'type' => 'seasonal',
            'is_featured' => true,
        ]);

        $collection = \App\Models\Collection::where('title', 'Nakshi Kantha Heritage Archive')->firstOrFail();
        $this->assertDatabaseHas('collection_product', [
            'collection_id' => $collection->id,
            'product_id' => $product->id,
        ]);

        // 2. Update Collection
        $updateRes = $this->actingAs($this->adminUser)->put(route('admin.collections.update', $collection->id), [
            'title' => 'Nakshi Kantha Heritage Archive (Updated)',
            'slug' => 'nakshi-kantha-updated',
            'type' => 'featured',
            'description' => 'Updated description.',
            'is_featured' => 0,
            'product_ids' => [$product->id],
        ]);

        $updateRes->assertRedirect();
        $updateRes->assertSessionHas('success');

        $collection->refresh();
        $this->assertEquals('Nakshi Kantha Heritage Archive (Updated)', $collection->title);
        $this->assertEquals('nakshi-kantha-updated', $collection->slug);
        $this->assertEquals('featured', $collection->type);

        // 3. Delete Collection
        $deleteRes = $this->actingAs($this->adminUser)->delete(route('admin.collections.destroy', $collection->id));
        $deleteRes->assertRedirect();
        $deleteRes->assertSessionHas('success');

        $this->assertSoftDeleted('collections', [
            'id' => $collection->id,
        ]);
    }
}
