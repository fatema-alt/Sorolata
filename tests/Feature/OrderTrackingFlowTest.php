<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTimeline;
use App\Models\Product;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderTrackingFlowTest extends TestCase
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
        ]);
    }

    protected function createDetailedOrder(array $attributes = []): Order
    {
        $product = Product::firstOrFail();

        $order = Order::create(array_merge([
            'uuid' => (string) Str::uuid(),
            'order_number' => 'SRL-' . strtoupper(Str::random(8)),
            'customer_name' => 'Khandaker Rafiq',
            'customer_email' => 'rafiq@example.com',
            'customer_phone' => '01719876543',
            'total_amount' => 5080.00,
            'subtotal_amount' => 5000.00,
            'discount_amount' => 0.00,
            'shipping_fee' => 80.00,
            'advance_delivery_charge' => 0.00,
            'paid_amount' => 0.00,
            'due_amount' => 5080.00,
            'currency' => Currency::BDT,
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
            'payment_gateway' => PaymentGateway::BKASH,
            'shipping_address' => [
                'address' => 'House 9, Road 3, Dhanmondi',
                'district' => 'Dhaka',
            ],
        ], $attributes));

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_title' => $product->title,
            'sku' => $product->sku,
            'unit_price' => 5000.00,
            'quantity' => 1,
            'subtotal' => 5000.00,
        ]);

        return $order;
    }

    public function test_track_order_page_loads_and_finds_by_order_number(): void
    {
        $order = $this->createDetailedOrder(['order_number' => 'SRL-TRACK-101']);

        $response = $this->get(route('track.order', ['order_number' => 'SRL-TRACK-101']));
        $response->assertStatus(200);
        $response->assertSee('SRL-TRACK-101');
        $response->assertSee('Khandaker Rafiq');
        $response->assertSee('Dhanmondi');
    }

    public function test_track_order_finds_by_customer_phone_number(): void
    {
        $order = $this->createDetailedOrder([
            'order_number' => 'SRL-PHONE-FIND',
            'customer_phone' => '01912345678',
        ]);

        $response = $this->get(route('track.order', ['order_number' => '01912345678']));
        $response->assertStatus(200);
        $response->assertSee('SRL-PHONE-FIND');
    }

    public function test_track_order_renders_real_audit_timeline_events(): void
    {
        $order = $this->createDetailedOrder(['order_number' => 'SRL-TIMELINE-VIEW']);

        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'actor_type' => 'customer',
            'note' => 'Customer placed order through one-page checkout.',
            'customer_notified' => true,
        ]);

        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => 'confirmed',
            'actor_type' => 'staff',
            'note' => 'Payment verified by Admin Kazi Farhan (TrxID: BKS999888).',
            'customer_notified' => true,
        ]);

        $response = $this->get(route('track.order', ['order_number' => 'SRL-TIMELINE-VIEW']));
        $response->assertStatus(200);
        $response->assertSee('Customer placed order through one-page checkout.');
        $response->assertSee('Payment verified by Admin Kazi Farhan (TrxID: BKS999888).');
    }

    public function test_track_order_shows_courier_tracking_info_when_dispatched(): void
    {
        $order = $this->createDetailedOrder([
            'order_number' => 'SRL-DISPATCHED-01',
            'status' => OrderStatus::SHIPPED,
            'payment_status' => PaymentStatus::PAID,
            'courier_name' => 'Steadfast Courier',
            'tracking_number' => 'STDFST-889977',
        ]);

        $response = $this->get(route('track.order', ['order_number' => 'SRL-DISPATCHED-01']));
        $response->assertStatus(200);
        $response->assertSee('Steadfast Courier');
        $response->assertSee('STDFST-889977');
    }

    public function test_track_order_shows_partially_paid_advance_dc_breakdown(): void
    {
        $order = $this->createDetailedOrder([
            'order_number' => 'SRL-COD-TRACK',
            'payment_gateway' => PaymentGateway::COD,
            'payment_status' => PaymentStatus::PARTIALLY_PAID,
            'total_amount' => 5080.00,
            'advance_delivery_charge' => 80.00,
            'paid_amount' => 80.00,
            'due_amount' => 5000.00,
            'transaction_id' => 'BKS_ADV_DC_123',
        ]);

        $response = $this->get(route('track.order', ['order_number' => 'SRL-COD-TRACK']));
        $response->assertStatus(200);
        $response->assertSee('BKS_ADV_DC_123');
        $response->assertSee('5,000.00'); // Due amount
        $response->assertSee('80.00'); // Paid amount
    }
}
