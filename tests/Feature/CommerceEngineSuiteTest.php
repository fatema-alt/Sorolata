<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\PaymentGatewayContract;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ReturnStatus;
use App\Enums\StockMovementType;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingRate;
use App\Models\ShippingZone;
use App\Models\User;
use App\Services\Commerce\CartService;
use App\Services\Commerce\CommerceAnalyticsService;
use App\Services\Commerce\DiscountService;
use App\Services\Commerce\InventoryService;
use App\Services\Commerce\OrderService;
use App\Services\Commerce\Payment\PaymentGatewayManager;
use App\Services\Commerce\ReturnService;
use App\Services\Commerce\ShippingService;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommerceEngineSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            CommerceEngineSeeder::class,
        ]);
    }

    public function test_product_variants_and_stock_properties(): void
    {
        $product = Product::with('variants')->firstOrFail();
        $variant = $product->variants->first();

        $this->assertNotNull($variant);
        $this->assertIsFloat($variant->price);
        $this->assertIsArray($variant->attributes);
        $this->assertTrue($variant->is_in_stock);
        $this->assertGreaterThan(0, $variant->available_stock);
        $this->assertStringContainsString('৳', $variant->formatted_price);
    }

    public function test_inventory_service_tracks_and_logs_stock_adjustments(): void
    {
        /** @var InventoryService $inventory */
        $inventory = app(InventoryService::class);
        $product = Product::firstOrFail();

        $initialStock = $product->stock_quantity;

        // Purchase addition
        $log = $inventory->adjustStock(
            item: $product,
            delta: 10,
            type: StockMovementType::PURCHASE,
            reason: 'Guild Artisan Delivery'
        );

        $product->refresh();
        $this->assertEquals($initialStock + 10, $product->stock_quantity);
        $this->assertEquals(StockMovementType::PURCHASE, $log->type);
        $this->assertEquals(10, $log->quantity_delta);

        // Reservation
        $reserved = $inventory->reserveStock($product, 3);
        $this->assertTrue($reserved);
        $product->refresh();
        $this->assertEquals(3, $product->reserved_stock);
        $this->assertEquals($product->stock_quantity - 3, $product->available_stock);

        // Release Reservation
        $released = $inventory->releaseStock($product, 3);
        $this->assertTrue($released);
        $product->refresh();
        $this->assertEquals(0, $product->reserved_stock);
    }

    public function test_discount_engine_calculates_percentage_and_enforces_limits(): void
    {
        /** @var DiscountService $discountService */
        $discountService = app(DiscountService::class);

        // Subtotal below minimum requirement (৳5000 required for BENGAL10)
        $resLow = $discountService->applyDiscount('BENGAL10', 3000.0);
        $this->assertNull($resLow['discount']);
        $this->assertEquals(0.0, $resLow['amount']);

        // Subtotal meeting minimum requirement
        $resOk = $discountService->applyDiscount('BENGAL10', 10000.0);
        $this->assertNotNull($resOk['discount']);
        $this->assertEquals(1000.0, $resOk['amount']); // 10% of 10000

        // Subtotal hitting maximum cap (max discount ৳2000)
        $resCapped = $discountService->applyDiscount('BENGAL10', 30000.0);
        $this->assertEquals(2000.0, $resCapped['amount']);
    }

    public function test_shipping_engine_resolves_zones_and_calculates_rates(): void
    {
        /** @var ShippingService $shippingService */
        $shippingService = app(ShippingService::class);

        // Dhaka zone
        $dhakaRates = $shippingService->getAvailableRates('Dhaka Metro');
        $this->assertNotEmpty($dhakaRates);
        $this->assertEquals('Express White-Glove (Next Day)', $dhakaRates->first()->name);

        // Weight-based calculation for outside Dhaka
        $bdRates = $shippingService->getAvailableRates('Chittagong');
        $this->assertNotEmpty($bdRates);
        $rate = $bdRates->first();

        // 1kg = base 130
        $fee1kg = $rate->calculateFee(1.0, 2000.0);
        $this->assertEquals(130.0, $fee1kg);

        // 3kg = base 130 + (2 * 30) = 190
        $fee3kg = $rate->calculateFee(3.0, 2000.0);
        $this->assertEquals(190.0, $fee3kg);

        // Free shipping qualification above ৳5000
        $feeFree = $rate->calculateFee(3.0, 6000.0);
        $this->assertEquals(0.0, $feeFree);
    }

    public function test_cart_service_lifecycle_and_gift_presentation(): void
    {
        /** @var CartService $cart */
        $cart = app(CartService::class);
        $cart->clear();

        $product = Product::firstOrFail();
        $variant = $product->variants->first();

        // Add item
        $item = $cart->addItem($product->id, $variant?->id, 2);
        $this->assertEquals(2, $item['quantity']);

        $totals = $cart->getTotals();
        $this->assertEquals(2, $totals['items_count']);
        $this->assertGreaterThan(0, $totals['subtotal']);

        // Gift Wrap
        $cart->setGiftWrap(
            enabled: true,
            recipient: 'Her Highness Begum Rokeya',
            message: 'A royal handloom tribute from Bengal.',
            receipt: true
        );

        $totalsWithGift = $cart->getTotals();
        $this->assertEquals(250.0, $totalsWithGift['gift_wrap_fee']);

        // Save for later
        $cart->saveForLater($item['key']);
        $contents = $cart->getContents();
        $this->assertEmpty($contents['items']);
        $this->assertNotEmpty($contents['saved_for_later']);

        // Move back to cart
        $cart->moveToCart($item['key']);
        $contentsRestored = $cart->getContents();
        $this->assertNotEmpty($contentsRestored['items']);
    }

    public function test_order_creation_with_timeline_audit_and_stock_deduction(): void
    {
        /** @var CartService $cart */
        $cart = app(CartService::class);
        $cart->clear();

        /** @var OrderService $orderService */
        $orderService = app(OrderService::class);

        $product = Product::firstOrFail();
        $initialStock = $product->stock_quantity;

        $cart->addItem($product->id, null, 1);

        $customerData = [
            'name' => 'Dr. Kazi Anis Ahmed',
            'email' => 'anis@bengalheritage.org',
            'phone' => '+8801711000000',
            'payment_gateway' => 'sslcommerz',
            'address' => 'Road 79, Gulshan-2, Dhaka',
            'city' => 'Dhaka',
        ];

        $order = $orderService->createOrderFromCart($cart, $customerData);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Dr. Kazi Anis Ahmed',
            'status' => OrderStatus::PENDING->value,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        // Stock deduction verified
        $product->refresh();
        $this->assertEquals($initialStock - 1, $product->stock_quantity);

        // Timeline audit entry
        $this->assertDatabaseHas('order_timelines', [
            'order_id' => $order->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        // Status Transition
        $orderService->transitionStatus($order, OrderStatus::CONFIRMED, 'Archival verification passed');
        $order->refresh();
        $this->assertEquals(OrderStatus::CONFIRMED, $order->status);
        $this->assertCount(2, $order->timelines);
    }

    public function test_payment_gateway_manager_resolves_all_configured_drivers(): void
    {
        /** @var PaymentGatewayManager $manager */
        $manager = app(PaymentGatewayManager::class);

        $gateways = ['bkash', 'nagad', 'rocket', 'sslcommerz', 'stripe', 'paypal', 'cash_on_delivery'];

        foreach ($gateways as $gatewayKey) {
            $driver = $manager->driver($gatewayKey);
            $this->assertInstanceOf(PaymentGatewayContract::class, $driver);
            $this->assertTrue($driver->isAvailable());
            $this->assertNotEmpty($driver->getDisplayName());
        }

        $available = $manager->getAvailableGateways();
        $this->assertCount(7, $available);
    }

    public function test_return_and_refund_lifecycle_restocks_inventory(): void
    {
        /** @var OrderService $orderService */
        $orderService = app(OrderService::class);
        /** @var ReturnService $returnService */
        $returnService = app(ReturnService::class);
        /** @var CartService $cart */
        $cart = app(CartService::class);
        $cart->clear();

        $product = Product::firstOrFail();
        $initialStock = $product->stock_quantity;

        $cart->addItem($product->id, null, 2);
        $order = $orderService->createOrderFromCart($cart, [
            'name' => 'Tariq Rahman',
            'email' => 'tariq@gmail.com',
            'phone' => '+8801811223344',
            'address' => 'Dhanmondi, Dhaka',
        ]);

        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stock_quantity);

        // Submit return request
        $returnReq = $returnService->createReturnRequest(
            order: $order,
            reason: 'Damaged in transit packaging',
            customerNote: 'Corner weaving slightly frayed.'
        );

        $this->assertEquals(ReturnStatus::PENDING, $returnReq->status);

        // Approve return
        $returnService->approveReturn($returnReq, $order->total_amount, 'Approved after photo inspection');
        $returnReq->refresh();
        $this->assertEquals(ReturnStatus::APPROVED, $returnReq->status);

        // Process refund and verify restocking
        $returnService->processRefund($returnReq);
        $returnReq->refresh();
        $order->refresh();
        $product->refresh();

        $this->assertEquals(ReturnStatus::REFUNDED, $returnReq->status);
        $this->assertEquals(OrderStatus::REFUNDED, $order->status);
        $this->assertEquals($initialStock, $product->stock_quantity); // Restocked 2 items!
    }

    public function test_commerce_rest_api_v1_endpoints(): void
    {
        // 1. Products API
        $prodResponse = $this->getJson('/api/v1/products');
        $prodResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data', 'pagination']);

        $sampleProduct = Product::firstOrFail();
        $singleResponse = $this->getJson("/api/v1/products/{$sampleProduct->slug}");
        $singleResponse->assertStatus(200)
            ->assertJsonPath('data.title', $sampleProduct->title);

        // 2. Cart API
        $cartGet = $this->getJson('/api/v1/cart');
        $cartGet->assertStatus(200)->assertJsonPath('success', true);

        $addResponse = $this->postJson('/api/v1/cart/items', [
            'product_id' => $sampleProduct->id,
            'quantity' => 1,
        ]);
        $addResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('item.product_id', $sampleProduct->id);

        // 3. Checkout Gateways API
        $gatewaysResponse = $this->getJson('/api/v1/checkout/gateways');
        $gatewaysResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['gateways']);

        // 4. One-Page Checkout API
        $checkoutResponse = $this->postJson('/api/v1/checkout', [
            'name' => 'Sharmeen Huq',
            'email' => 'sharmeen@collector.com',
            'phone' => '+8801912345678',
            'address' => 'Banani DOHS, Dhaka',
            'payment_gateway' => 'bkash',
            'notes' => 'Please deliver in afternoon.',
        ]);

        $checkoutResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'order' => ['order_number', 'total_amount', 'status'],
                'payment' => ['redirect_url'],
            ]);

        $orderNumber = $checkoutResponse->json('order.order_number');

        // 5. Order Tracking & Timeline API
        $orderResponse = $this->getJson("/api/v1/orders/{$orderNumber}");
        $orderResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.order_number', $orderNumber)
            ->assertJsonStructure(['data' => ['items', 'timelines']]);
    }
}
