<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AdminRole;
use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\AdminSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ManualPaymentSystemTest extends TestCase
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
            'customer_name' => 'Tahmid Hasan',
            'customer_email' => 'tahmid@example.com',
            'customer_phone' => '01712345678',
            'total_amount' => 4580.00,
            'subtotal_amount' => 4500.00,
            'discount_amount' => 0.00,
            'shipping_fee' => 80.00,
            'advance_delivery_charge' => 0.00,
            'paid_amount' => 0.00,
            'due_amount' => 4580.00,
            'currency' => Currency::BDT,
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
            'payment_gateway' => PaymentGateway::BKASH,
            'shipping_address' => [
                'address' => 'House 12, Road 4, Dhanmondi',
                'district' => 'Dhaka',
            ],
        ], $attributes));
    }

    public function test_checkout_with_manual_bkash_redirects_to_instruction_page(): void
    {
        $response = $this->post(route('checkout.process'), [
            'customer_name' => 'Adnan Sami',
            'customer_email' => 'adnan@example.com',
            'customer_phone' => '01700998877',
            'delivery_address' => 'Banani, Dhaka',
            'city_district' => 'Dhaka',
            'payment_gateway' => 'bkash',
        ]);

        $order = Order::where('customer_email', 'adnan@example.com')->latest()->firstOrFail();
        $response->assertRedirect(route('payment.manual.instruction', ['order' => $order->order_number]));

        // Instruction page renders with account details
        $viewResponse = $this->get(route('payment.manual.instruction', ['order' => $order->order_number]));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee($order->order_number);
        $viewResponse->assertSee('পেমেন্ট নির্দেশনা');
        $viewResponse->assertSee('bKash');
    }

    public function test_checkout_with_brac_bank_redirects_to_instruction_page(): void
    {
        $response = $this->post(route('checkout.process'), [
            'customer_name' => 'Sadia Islam',
            'customer_email' => 'sadia@example.com',
            'customer_phone' => '01800998877',
            'delivery_address' => 'Uttara, Dhaka',
            'city_district' => 'Dhaka',
            'payment_gateway' => 'brac_bank',
        ]);

        $order = Order::where('customer_email', 'sadia@example.com')->latest()->firstOrFail();
        $response->assertRedirect(route('payment.manual.instruction', ['order' => $order->order_number]));

        $viewResponse = $this->get(route('payment.manual.instruction', ['order' => $order->order_number]));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('BRAC Bank');
    }

    public function test_checkout_with_dbbl_redirects_to_instruction_page(): void
    {
        $response = $this->post(route('checkout.process'), [
            'customer_name' => 'Imran Khan',
            'customer_email' => 'imran@example.com',
            'customer_phone' => '01900998877',
            'delivery_address' => 'Khulshi, Chittagong',
            'city_district' => 'Chittagong',
            'payment_gateway' => 'dbbl',
        ]);

        $order = Order::where('customer_email', 'imran@example.com')->latest()->firstOrFail();
        $response->assertRedirect(route('payment.manual.instruction', ['order' => $order->order_number]));

        $viewResponse = $this->get(route('payment.manual.instruction', ['order' => $order->order_number]));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Dutch-Bangla');
    }

    public function test_customer_can_submit_transaction_id_and_sender_number(): void
    {
        $order = $this->createTestOrder(['order_number' => 'SRL-MANUAL-01']);

        $submitResponse = $this->post(route('payment.manual.submit', ['order' => $order->order_number]), [
            'transaction_id' => 'BKS7X89Q12',
            'sender_number' => '01711223344',
            'payment_method' => 'bkash',
            'customer_notes' => 'Paid from my personal bKash account.',
        ]);

        $submitResponse->assertRedirect(route('track.order', ['order_number' => $order->order_number]));

        $order->refresh();
        $this->assertEquals('BKS7X89Q12', $order->transaction_id);
        $this->assertEquals('01711223344', $order->payment_details['sender_number']);
        $this->assertEquals('bkash', $order->payment_details['method_used']);

        // Verify order timeline recorded
        $this->assertDatabaseHas('order_timelines', [
            'order_id' => $order->id,
            'actor_type' => 'customer',
        ]);
    }

    public function test_admin_can_verify_and_confirm_manual_payment(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-AUDIT-VERIFY',
            'total_amount' => 6000.00,
            'paid_amount' => 0.00,
            'due_amount' => 6000.00,
            'transaction_id' => 'TRX_CUSTOMER_SUBMITTED_888',
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
        ]);

        // Admin inspects order details
        $showResponse = $this->actingAs($this->adminUser)->get(route('admin.orders.show', $order->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('TRX_CUSTOMER_SUBMITTED_888');
        $showResponse->assertSee('Verify & Confirm Payment', false);

        // Admin verifies payment
        $verifyResponse = $this->actingAs($this->adminUser)->post(route('admin.orders.verify-payment', $order->id), [
            'transaction_id' => 'TRX_CUSTOMER_SUBMITTED_888',
            'note' => 'Bank statement checked and verified.',
        ]);

        $verifyResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals(6000.00, (float) $order->paid_amount);
        $this->assertEquals(0.00, (float) $order->due_amount);
        $this->assertEquals(PaymentStatus::PAID, $order->payment_status);
        $this->assertEquals(OrderStatus::CONFIRMED, $order->status);
        $this->assertEquals('Kazi Farhan (Master Curator)', $order->payment_details['verified_by']);
    }

    public function test_admin_can_verify_cod_advance_delivery_fee(): void
    {
        $order = $this->createTestOrder([
            'order_number' => 'SRL-COD-ADV-VERIFY',
            'total_amount' => 5080.00,
            'advance_delivery_charge' => 80.00,
            'paid_amount' => 0.00,
            'due_amount' => 5080.00,
            'payment_gateway' => PaymentGateway::COD,
            'transaction_id' => 'BKS_ADV_DC_777',
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
        ]);

        $verifyResponse = $this->actingAs($this->adminUser)->post(route('admin.orders.verify-payment', $order->id), [
            'transaction_id' => 'BKS_ADV_DC_777',
        ]);

        $verifyResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals(80.00, (float) $order->paid_amount);
        $this->assertEquals(5000.00, (float) $order->due_amount);
        $this->assertEquals(PaymentStatus::PARTIALLY_PAID, $order->payment_status);
        $this->assertEquals(OrderStatus::CONFIRMED, $order->status);
    }

    public function test_admin_can_update_bank_and_mfs_account_details_in_settings(): void
    {
        $updateResponse = $this->actingAs($this->adminUser)->put(route('admin.settings.update'), [
            'manual_bkash_number' => '01999887766',
            'manual_bkash_type' => 'Merchant Account',
            'manual_brac_account_number' => '999888777666555',
            'manual_dbbl_account_number' => '111222333444555',
            'cod_advance_charge_dhaka' => '90',
        ]);

        $updateResponse->assertRedirect();

        $this->assertEquals('01999887766', AdminSetting::where('key', 'manual_bkash_number')->value('value'));
        $this->assertEquals('Merchant Account', AdminSetting::where('key', 'manual_bkash_type')->value('value'));
        $this->assertEquals('999888777666555', AdminSetting::where('key', 'manual_brac_account_number')->value('value'));
        $this->assertEquals('111222333444555', AdminSetting::where('key', 'manual_dbbl_account_number')->value('value'));
        $this->assertEquals('90', AdminSetting::where('key', 'cod_advance_charge_dhaka')->value('value'));
    }
}
