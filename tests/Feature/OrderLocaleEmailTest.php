<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderLocaleEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_locale_accessor_and_mail_localization(): void
    {
        $orderBn = Order::create([
            'order_number' => 'SRL-BN01',
            'customer_name' => 'Fatema',
            'customer_email' => 'customer.bn@example.com',
            'customer_phone' => '01711223344',
            'total_amount' => 1200.00,
            'subtotal_amount' => 1100.00,
            'status' => \App\Enums\OrderStatus::PENDING,
            'shipping_address' => [
                'address' => 'Mirpur, Dhaka',
                'district' => 'Dhaka',
                'locale' => 'bn',
            ],
        ]);

        $this->assertSame('bn', $orderBn->locale);

        $orderEn = Order::create([
            'order_number' => 'SRL-EN01',
            'customer_name' => 'Fatema',
            'customer_email' => 'customer.en@example.com',
            'customer_phone' => '01711223355',
            'total_amount' => 1200.00,
            'subtotal_amount' => 1100.00,
            'status' => \App\Enums\OrderStatus::PENDING,
            'shipping_address' => [
                'address' => 'Gulshan, Dhaka',
                'district' => 'Dhaka',
                'locale' => 'en',
            ],
        ]);

        $this->assertSame('en', $orderEn->locale);

        // Test envelope subject in Bangla
        app()->setLocale('bn');
        $mailableBn = new OrderConfirmationMail($orderBn);
        $mailableBn->assertHasSubject('আপনার সরলতা অর্ডার নিশ্চিতকরণ — #SRL-BN01');

        // Test envelope subject in English
        app()->setLocale('en');
        $mailableEn = new OrderConfirmationMail($orderEn);
        $mailableEn->assertHasSubject('Your Sorolata Heirloom Order Confirmed — #SRL-EN01');
    }
}
