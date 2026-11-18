<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Collection;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\EnterpriseSchemaSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiArchitectureSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            CommerceEngineSeeder::class,
            AdminSeeder::class,
            EnterpriseSchemaSeeder::class,
        ]);
    }

    public function test_auth_registration_login_profile_and_logout_lifecycle(): void
    {
        // 1. Register
        $regResponse = $this->postJson('/api/v1/auth/register', [
            'name' => 'Nazmul Huda',
            'email' => 'nazmul@example.com',
            'phone' => '+8801700000001',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
        ]);

        $regResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role', 'profile'],
                    'token',
                    'token_type',
                ],
            ]);

        $token = $regResponse->json('data.token');

        // 2. Profile with Sanctum Bearer Token
        $meResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me');

        $meResponse->assertStatus(200)
            ->assertJsonPath('data.user.email', 'nazmul@example.com');

        // 3. Logout
        $logoutResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $logoutResponse->assertStatus(200);

        // 4. Login
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'nazmul@example.com',
            'password' => 'secret12345',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_products_catalog_with_filters_and_sorting(): void
    {
        // 1. List with limit
        $response = $this->getJson('/api/v1/products?limit=2');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'uuid', 'slug', 'title', 'price', 'effective_price', 'currency'],
                ],
                'meta' => [
                    'pagination' => ['current_page', 'per_page', 'total'],
                ],
            ]);

        // 2. Sorting by price asc
        $sortedRes = $this->getJson('/api/v1/products?sort=price_asc');
        $sortedRes->assertStatus(200);

        // 3. Detail payload with variants, content, provenance & specs
        $firstProduct = Product::firstOrFail();
        $detailRes = $this->getJson("/api/v1/products/{$firstProduct->slug}");
        $detailRes->assertStatus(200)
            ->assertJsonPath('data.id', $firstProduct->id)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'uuid',
                    'slug',
                    'title',
                    'price',
                    'effective_price',
                    'media',
                    'category',
                    'district',
                    'artisan',
                    'content',
                    'origin',
                    'materials',
                    'variants',
                    'specifications',
                    'faqs',
                ],
            ]);
    }

    public function test_taxonomies_and_heritage_endpoints(): void
    {
        $this->getJson('/api/v1/categories')->assertStatus(200)->assertJsonPath('success', true);
        $this->getJson('/api/v1/collections')->assertStatus(200)->assertJsonPath('success', true);
        $this->getJson('/api/v1/divisions')->assertStatus(200)->assertJsonPath('success', true);
        $this->getJson('/api/v1/districts')->assertStatus(200)->assertJsonPath('success', true);
        $this->getJson('/api/v1/artisans')->assertStatus(200)->assertJsonPath('success', true);

        $firstDistrict = District::firstOrFail();
        $this->getJson("/api/v1/districts/{$firstDistrict->name_en}")
            ->assertStatus(200)
            ->assertJsonPath('data.district.id', $firstDistrict->id);

        $firstArtisan = Artisan::firstOrFail();
        $this->getJson("/api/v1/artisans/{$firstArtisan->uuid}")
            ->assertStatus(200)
            ->assertJsonPath('data.artisan.id', $firstArtisan->id);
    }

    public function test_cart_lifecycle_and_coupon_application(): void
    {
        $product = Product::firstOrFail();
        $session = 'test_guest_session_999';

        // 1. Get empty cart
        $cartRes = $this->withHeader('X-Session-ID', $session)->getJson('/api/v1/cart');
        $cartRes->assertStatus(200)->assertJsonPath('data.items_count', 0);
        $cartUuid = $cartRes->json('data.uuid');

        // 2. Add item
        $addRes = $this->withHeader('X-Session-ID', $session)->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $addRes->assertStatus(200)->assertJsonPath('success', true);
        $itemId = $addRes->json('data.item.id');

        // 3. Update quantity
        $updateRes = $this->withHeader('X-Session-ID', $session)->putJson("/api/v1/cart/items/{$itemId}", [
            'quantity' => 3,
        ]);
        $updateRes->assertStatus(200)->assertJsonPath('data.items_count', 3);

        // 4. Remove item
        $deleteRes = $this->withHeader('X-Session-ID', $session)->deleteJson("/api/v1/cart/items/{$itemId}");
        $deleteRes->assertStatus(200)->assertJsonPath('data.items_count', 0);
    }

    public function test_checkout_calculation_and_order_placement(): void
    {
        // 1. Gateways
        $gatewaysRes = $this->getJson('/api/v1/checkout/gateways');
        $gatewaysRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => [['code', 'name', 'type']]]);

        // 2. Create Cart with Item
        $product = Product::firstOrFail();
        $session = 'checkout_test_session';
        $this->withHeader('X-Session-ID', $session)->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cart = Cart::where('session_id', $session)->firstOrFail();

        // 3. Pre-calculate totals
        $calcRes = $this->postJson('/api/v1/checkout/calculate', [
            'cart_uuid' => $cart->uuid,
        ]);
        $calcRes->assertStatus(200)->assertJsonPath('success', true);

        // 4. Place order
        $initialStock = $product->stock_quantity;
        $orderRes = $this->postJson('/api/v1/checkout/process', [
            'cart_uuid' => $cart->uuid,
            'customer_name' => 'Sultana Razia',
            'customer_email' => 'sultana@example.com',
            'customer_phone' => '+8801811223344',
            'shipping_address' => [
                'address' => 'Road 4, House 12, Dhanmondi',
                'city' => 'Dhaka',
                'district' => 'Dhaka',
            ],
            'payment_gateway' => 'bkash',
        ]);

        $orderRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'order' => ['id', 'order_number', 'status', 'total_amount', 'items', 'timeline'],
                    'payment_redirect_url',
                ],
            ]);

        // Stock decreased
        $this->assertEquals($initialStock - 1, $product->fresh()->stock_quantity);
    }

    public function test_orders_history_tracking_and_invoice(): void
    {
        $order = Order::firstOrFail();

        // 1. Show order
        $showRes = $this->getJson("/api/v1/orders/{$order->order_number}");
        $showRes->assertStatus(200)->assertJsonPath('data.order_number', $order->order_number);

        // 2. Tracking timeline
        $trackRes = $this->getJson("/api/v1/orders/{$order->order_number}/tracking");
        $trackRes->assertStatus(200)->assertJsonStructure(['data' => ['timeline']]);

        // 3. Invoice certificate
        $invRes = $this->getJson("/api/v1/orders/{$order->order_number}/invoice");
        $invRes->assertStatus(200)->assertJsonPath('data.archival_seal_verified', true);
    }

    public function test_customer_profile_and_wishlist_toggle(): void
    {
        $product = Product::firstOrFail();
        $session = 'wishlist_session_1';

        // 1. Toggle add
        $toggleAdd = $this->withHeader('X-Session-ID', $session)->postJson('/api/v1/customer/wishlist/toggle', [
            'product_id' => $product->id,
        ]);
        $toggleAdd->assertStatus(200)->assertJsonPath('data.in_wishlist', true);

        // 2. Fetch wishlist
        $listRes = $this->withHeader('X-Session-ID', $session)->getJson('/api/v1/customer/wishlist');
        $listRes->assertStatus(200)->assertJsonPath('data.0.id', $product->id);

        // 3. Toggle remove
        $toggleRemove = $this->withHeader('X-Session-ID', $session)->postJson('/api/v1/customer/wishlist/toggle', [
            'product_id' => $product->id,
        ]);
        $toggleRemove->assertStatus(200)->assertJsonPath('data.in_wishlist', false);
    }

    public function test_cms_mobile_homepage_sections_and_stories(): void
    {
        // 1. Homepage aggregated mobile payload
        $homeRes = $this->getJson('/api/v1/homepage/sections');
        $homeRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'announcement',
                    'hero' => ['headline', 'featured_products'],
                    'collections',
                    'artisans',
                    'districts',
                    'stories',
                    'trust_badges',
                ],
            ]);

        // 2. Stories list
        $storiesRes = $this->getJson('/api/v1/stories');
        $storiesRes->assertStatus(200)->assertJsonPath('success', true);
    }

    public function test_headless_builder_layout_delivery(): void
    {
        $product = Product::firstOrFail();

        $builderRes = $this->getJson("/api/v1/builder/layout/product/{$product->id}");
        $builderRes->assertStatus(200)
            ->assertJsonPath('data.type', 'product')
            ->assertJsonStructure(['data' => ['type', 'id', 'title', 'version', 'sections']]);
    }

    public function test_search_and_autocomplete_suggestions(): void
    {
        // 1. Unified search
        $searchRes = $this->getJson('/api/v1/search?q=jamdani');
        $searchRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['query', 'products', 'districts', 'artisans', 'stories'],
            ]);

        // 2. Suggestions
        $suggRes = $this->getJson('/api/v1/search/suggestions?q=jam');
        $suggRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['suggestions', 'trending'],
            ]);
    }

    public function test_system_configuration_and_feature_flags(): void
    {
        $configRes = $this->getJson('/api/v1/system/config');
        $configRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'store',
                    'supported_currencies' => [['code', 'symbol', 'name']],
                    'supported_languages',
                    'feature_flags',
                    'design_tokens',
                ],
            ]);
    }

    public function test_interactive_api_docs_route(): void
    {
        $response = $this->get('/docs/api');
        $response->assertStatus(200);
        $response->assertSee('Sorolata REST API Documentation');
    }
}
