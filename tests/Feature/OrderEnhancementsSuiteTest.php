<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AdminRole;
use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Enums\StockMovementType;
use App\Mail\OrderConfirmationMail;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\Logistics\SteadfastCourierService;
use App\Services\Notification\SmsService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderEnhancementsSuiteTest extends TestCase
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

    protected function createTestOrder(array $attributes = []): Order
    {
        return Order::create(array_merge([
            'uuid' => (string) Str::uuid(),
            'order_number' => 'SRL-' . strtoupper(Str::random(8)),
            'customer_name' => 'Farhana Kabir',
            'customer_email' => 'farhana@example.com',
            'customer_phone' => '01711223344',
            'total_amount' => 4500.00,
            'subtotal_amount' => 4420.00,
            'discount_amount' => 0.00,
            'shipping_fee' => 80.00,
            'advance_delivery_charge' => 0.00,
            'paid_amount' => 0.00,
            'due_amount' => 4500.00,
            'currency' => Currency::BDT,
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
            'payment_gateway' => PaymentGateway::BKASH,
            'shipping_address' => [
                'address' => 'House 14, Road 5, Dhanmondi',
                'district' => 'Dhaka',
            ],
        ], $attributes));
    }

    public function test_sms_service_sends_templated_messages_safely(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-SMS123',
            'customer_name' => 'Farhana Kabir',
            'customer_phone' => '01711223344',
            'total_amount' => 4500,
        ]);

        $smsService = app(SmsService::class);

        $sentPlaced = $smsService->sendOrderPlacedSms($order);
        $this->assertTrue($sentPlaced);

        $order->status = OrderStatus::CONFIRMED;
        $order->save();
        $sentConfirmed = $smsService->sendOrderConfirmedSms($order);
        $this->assertTrue($sentConfirmed);

        $order->courier_name = 'Steadfast Courier';
        $order->tracking_number = 'STDF998877';
        $order->status = OrderStatus::SHIPPED;
        $order->save();
        $sentShipped = $smsService->sendOrderShippedSms($order);
        $this->assertTrue($sentShipped);

        $this->assertDatabaseHas('order_timelines', [
            'order_id' => $order->id,
            'actor_type' => 'system',
        ]);
    }

    public function test_order_confirmation_email_mailable_renders_accurately(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-MAIL99',
            'customer_name' => 'Nusrat Jahan',
            'customer_email' => 'nusrat@example.com',
            'total_amount' => 5200,
            'subtotal_amount' => 5000,
            'shipping_fee' => 200,
            'due_amount' => 5200,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_title' => 'Jamdani Royal Sari',
            'artisan_name' => 'Ustad Karim',
            'sku' => 'JMD-001',
            'unit_price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
        ]);

        $mailable = new OrderConfirmationMail($order);
        $mailable->assertHasSubject("Your Sorolata Heirloom Order Confirmed — #SRL-MAIL99");
        $mailable->assertSeeInHtml('Jamdani Royal Sari');
        $mailable->assertSeeInHtml('Ustad Karim');
        $mailable->assertSeeInHtml('৳ 5,200');
    }

    public function test_admin_can_dispatch_order_via_steadfast_courier_in_one_click(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-STDF01',
            'customer_name' => 'Rahim Chowdhury',
            'customer_phone' => '01811223344',
            'total_amount' => 3200,
            'due_amount' => 3200,
            'status' => OrderStatus::CONFIRMED,
            'payment_status' => PaymentStatus::PARTIALLY_PAID,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.orders.dispatch-steadfast', $order->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals(OrderStatus::SHIPPED, $order->status);
        $this->assertEquals('Steadfast Courier', $order->courier_name);
        $this->assertNotEmpty($order->tracking_number);
        $this->assertStringStartsWith('STDF', $order->tracking_number);

        $this->assertDatabaseHas('order_timelines', [
            'order_id' => $order->id,
            'status' => OrderStatus::SHIPPED->value,
            'actor_type' => 'staff',
        ]);
    }

    public function test_customer_can_submit_cancellation_request_from_tracking_page(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-CANCEL01',
            'status' => OrderStatus::PENDING,
        ]);

        $response = $this->post(route('track.order.cancel-request', $order->order_number), [
            'reason' => 'ভুল প্রোডাক্ট বা কোয়ান্টিটি অর্ডার করেছি',
            'notes' => 'Please change to size L or cancel order.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('order_timelines', [
            'order_id' => $order->id,
            'actor_type' => 'customer',
        ]);

        // Customer cannot cancel if already shipped
        $order->status = OrderStatus::SHIPPED;
        $order->save();

        $dispatchedResponse = $this->post(route('track.order.cancel-request', $order->order_number), [
            'reason' => 'অন্যান্য কারণ',
        ]);

        $dispatchedResponse->assertSessionHas('error');
    }

    public function test_admin_can_approve_cancellation_and_restock_inventory(): void
    {
        $product = Product::firstOrFail();
        $product->update([
            'stock_quantity' => 10,
            'track_inventory' => true,
            'is_unlimited_stock' => false,
        ]);

        $order = $this->createTestOrder([
            'order_number' => 'SRL-RESTOCK01',
            'status' => OrderStatus::PENDING,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_title' => $product->title,
            'sku' => $product->sku ?? 'SC-01',
            'unit_price' => 1500,
            'quantity' => 2,
            'subtotal' => 3000,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.orders.cancellation-approve', $order->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $product->refresh();

        $this->assertEquals(OrderStatus::CANCELLED, $order->status);
        $this->assertEquals(12, $product->stock_quantity); // 10 + 2 = 12

        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $product->id,
            'type' => StockMovementType::RETURN->value,
            'quantity_delta' => 2,
        ]);
    }
}
