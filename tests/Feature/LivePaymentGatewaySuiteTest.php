<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LivePaymentGatewaySuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function createTestOrder(array $attributes = []): Order
    {
        return Order::create(array_merge([
            'uuid' => (string) Str::uuid(),
            'order_number' => 'SRL-' . strtoupper(Str::random(8)),
            'customer_name' => 'Test Collector',
            'customer_email' => 'collector@example.com',
            'customer_phone' => '01711223344',
            'total_amount' => 5000.00,
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
                'address' => 'House 1, Road 2, Dhanmondi',
                'district' => 'Dhaka',
            ],
        ], $attributes));
    }

    public function test_district_based_shipping_charge_and_cod_advance_charge(): void
    {
        $product = Product::first();

        // 1. Order inside Dhaka with COD
        $responseDhaka = $this->post(route('checkout.process'), [
            'customer_name' => 'Farhan Ahmed',
            'customer_email' => 'farhan@example.com',
            'customer_phone' => '01700112233',
            'delivery_address' => 'Mirpur DOHS, Dhaka',
            'city_district' => 'Dhaka',
            'payment_gateway' => 'cash_on_delivery',
        ]);

        $responseDhaka->assertRedirect();
        
        $orderDhaka = Order::where('customer_email', 'farhan@example.com')->latest()->first();
        $this->assertNotNull($orderDhaka);
        $this->assertEquals(80.00, (float) $orderDhaka->shipping_fee);
        $this->assertEquals(80.00, (float) $orderDhaka->advance_delivery_charge);
        $this->assertEquals($orderDhaka->total_amount, (float) $orderDhaka->due_amount);
        $this->assertEquals(PaymentStatus::UNPAID, $orderDhaka->payment_status);

        // 2. Order outside Dhaka with COD
        $responseOutside = $this->post(route('checkout.process'), [
            'customer_name' => 'Nusrat Jahan',
            'customer_email' => 'nusrat@example.com',
            'customer_phone' => '01800112233',
            'delivery_address' => 'GEC Circle, Chittagong',
            'city_district' => 'Chittagong',
            'payment_gateway' => 'cash_on_delivery',
        ]);

        $responseOutside->assertRedirect();

        $orderOutside = Order::where('customer_email', 'nusrat@example.com')->latest()->first();
        $this->assertNotNull($orderOutside);
        $this->assertEquals(150.00, (float) $orderOutside->shipping_fee);
        $this->assertEquals(150.00, (float) $orderOutside->advance_delivery_charge);
    }

    public function test_bkash_checkout_renders_and_processes_callback(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-TEST-BKSH',
            'total_amount' => 4500.00,
            'paid_amount' => 0.00,
            'due_amount' => 4500.00,
            'payment_gateway' => PaymentGateway::BKASH,
        ]);

        // Checkout view
        $viewResponse = $this->get(route('payment.bkash.checkout', ['order' => $order->order_number]));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('bKash Payment');
        $viewResponse->assertSee('SRL-TEST-BKSH');

        // Callback execution
        $callbackResponse = $this->post(route('payment.bkash.callback', ['order' => $order->order_number]), [
            'paymentID' => 'BKS_SIM_987654',
            'trxID' => 'TRX_BKASH_MOCK_101',
            'amount' => 4500.00,
            'status' => 'success',
        ]);

        $callbackResponse->assertRedirect(route('track.order', ['order_number' => $order->order_number]));

        $order->refresh();
        $this->assertEquals(4500.00, (float) $order->paid_amount);
        $this->assertEquals(0.00, (float) $order->due_amount);
        $this->assertEquals(PaymentStatus::PAID, $order->payment_status);
        $this->assertEquals(OrderStatus::CONFIRMED, $order->status);
        $this->assertEquals('TRX_BKASH_MOCK_101', $order->transaction_id);
    }

    public function test_cod_advance_delivery_fee_cleared_via_bkash_sets_partially_paid(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-COD-ADV',
            'total_amount' => 5080.00,
            'paid_amount' => 0.00,
            'due_amount' => 5080.00,
            'advance_delivery_charge' => 80.00,
            'payment_gateway' => PaymentGateway::COD,
        ]);

        // Customer pays ৳80 advance delivery fee
        $callbackResponse = $this->post(route('payment.bkash.callback', ['order' => $order->order_number]), [
            'paymentID' => 'BKS_SIM_ADV80',
            'trxID' => 'TRX_ADV_COURIER_80',
            'amount' => 80.00,
            'status' => 'success',
        ]);

        $callbackResponse->assertRedirect(route('track.order', ['order_number' => $order->order_number]));

        $order->refresh();
        $this->assertEquals(80.00, (float) $order->paid_amount);
        $this->assertEquals(5000.00, (float) $order->due_amount);
        $this->assertEquals(PaymentStatus::PARTIALLY_PAID, $order->payment_status);
        $this->assertEquals(OrderStatus::CONFIRMED, $order->status);
        $this->assertEquals('TRX_ADV_COURIER_80', $order->transaction_id);
    }

    public function test_sslcommerz_checkout_and_success_flow(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-SSLC-TEST',
            'total_amount' => 8200.00,
            'paid_amount' => 0.00,
            'due_amount' => 8200.00,
            'payment_gateway' => PaymentGateway::SSLCOMMERZ,
        ]);

        // SSLCommerz hosted checkout view
        $viewResponse = $this->get(route('payment.sslcommerz.checkout', ['order' => $order->order_number]));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('SSLCommerz');
        $viewResponse->assertSee('SRL-SSLC-TEST');

        // Success callback
        $successResponse = $this->post(route('payment.sslcommerz.success'), [
            'tran_id' => 'SSLC_SRL-SSLC-TEST_9999',
            'val_id' => 'VAL_SSLC_MOCK_SUCCESS',
            'amount' => 8200.00,
            'status' => 'VALID',
            'value_a' => $order->order_number,
            'card_type' => 'VISA-CityBank',
        ]);

        $successResponse->assertRedirect(route('track.order', ['order_number' => $order->order_number]));

        $order->refresh();
        $this->assertEquals(8200.00, (float) $order->paid_amount);
        $this->assertEquals(0.00, (float) $order->due_amount);
        $this->assertEquals(PaymentStatus::PAID, $order->payment_status);
        $this->assertEquals(OrderStatus::CONFIRMED, $order->status);
    }
}
