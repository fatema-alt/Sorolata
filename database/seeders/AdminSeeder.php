<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\ActivityLog;
use App\Models\AdminNotification;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Staff Users
        $admin = User::firstOrCreate(['email' => 'admin@sorolata.com'], [
            'name' => 'Kazi Farhan (Master Curator)',
            'password' => Hash::make('password'),
            'role' => AdminRole::SUPER_ADMIN,
            'phone' => '+8801700000001',
        ]);

        $manager = User::firstOrCreate(['email' => 'manager@sorolata.com'], [
            'name' => 'Tasnima Rahman (Guild Director)',
            'password' => Hash::make('password'),
            'role' => AdminRole::MANAGER,
            'phone' => '+8801700000002',
        ]);

        // 2. Default Storefront Settings
        $defaults = [
            'store_name' => 'Sorolata Commerce House',
            'support_email' => 'curator@sorolata.com',
            'support_phone' => '+880 1700 000 000',
            'currency' => 'BDT',
            'shipping_free_threshold' => '5000',
            'shipping_default_fee' => '80',
            'payment_bkash_enabled' => '1',
            'payment_nagad_enabled' => '1',
            'payment_rocket_enabled' => '1',
            'payment_sslcommerz_enabled' => '1',
            'payment_stripe_enabled' => '1',
            'payment_paypal_enabled' => '1',
            'payment_cod_enabled' => '1',
            // Manual Payment Channels (Bank & MFS)
            'manual_bkash_enabled' => '1',
            'manual_bkash_number' => '01711002233',
            'manual_bkash_type' => 'Personal (Send Money)',
            'manual_bkash_instructions' => 'বিকাশ অ্যাপ থেকে উপরোক্ত নাম্বারে "Send Money" করুন। রেফারেন্সের ঘরে আপনার অর্ডার নাম্বারটি লিখুন।',
            'manual_nagad_enabled' => '1',
            'manual_nagad_number' => '01811002233',
            'manual_nagad_type' => 'Personal (Send Money)',
            'manual_nagad_instructions' => 'নগদ অ্যাপ থেকে "Send Money" করুন এবং রেফারেন্সে আপনার অর্ডার নাম্বারটি লিখুন।',
            'manual_brac_enabled' => '1',
            'manual_brac_account_name' => 'Sorolata Heritage Ltd',
            'manual_brac_account_number' => '1501204859632001',
            'manual_brac_branch' => 'Gulshan Branch, Dhaka',
            'manual_brac_routing_number' => '060261726',
            'manual_brac_instructions' => 'ব্র্যাক ব্যাংক (Astha App / ব্রাঞ্চ ডিপোজিট) করে ডিপোজিট স্লিপ বা ট্রানজেকশন রেফারেন্স প্রদান করুন।',
            'manual_dbbl_enabled' => '1',
            'manual_dbbl_account_name' => 'Sorolata Artisan Commerce',
            'manual_dbbl_account_number' => '1151200034567',
            'manual_dbbl_branch' => 'Dhanmondi Branch, Dhaka',
            'manual_dbbl_routing_number' => '090261345',
            'manual_dbbl_instructions' => 'ডাচ-বাংলা ব্যাংক (NexusPay App / রকেট / ব্যাংক ট্রান্সফার) করে ট্রানজেকশন রেফারেন্স প্রদান করুন।',
            'cod_advance_charge_dhaka' => '80',
            'cod_advance_charge_outside' => '150',
            'cod_instructions' => 'ফেক অর্ডার ও পার্সেল রিটার্ন রোধে শুধুমাত্র কুরিয়ার চার্জ অগ্রিম বিকাশ, নগদ বা ব্যাংকে পরিশোধ করে TrxID সাবমিট করুন। অবশিষ্ট মূল্য পণ্য হাতে পাওয়ার পর প্রদান করবেন।',
        ];

        foreach ($defaults as $key => $val) {
            $group = str_contains($key, 'payment_') ? 'payment' : (str_contains($key, 'shipping_') ? 'shipping' : 'general');
            AdminSetting::firstOrCreate(['key' => $key], [
                'value' => $val,
                'group' => $group,
            ]);
        }

        // 3. Centralized Notifications
        AdminNotification::firstOrCreate(['title' => 'Imperial Sonargaon Muslin Batch Dispatched'], [
            'uuid' => (string) Str::uuid(),
            'type' => 'order',
            'message' => 'Insured courier dispatched for Order #SRL-BENGAL-01 to London, UK.',
            'action_url' => '/admin/orders',
            'is_read' => false,
        ]);

        // 4. Initial Activity Logs
        ActivityLog::firstOrCreate(['action' => 'system.initialized'], [
            'user_id' => $admin->id,
            'description' => 'Sorolata Studio Enterprise CMS initialized with full artisan ledger.',
            'created_at' => now(),
        ]);

        // 5. Flagship Seeded Order for Logistics & Timeline
        $product = \App\Models\Product::first();
        if ($product) {
            $order = \App\Models\Order::firstOrCreate(['order_number' => 'SRL-BENGAL-01'], [
                'uuid' => (string) Str::uuid(),
                'user_id' => $admin->id,
                'customer_name' => 'Prof. Anisuzzaman Al-Hadi',
                'customer_email' => 'collector@bengalarchive.org',
                'customer_phone' => '+8801711223344',
                'total_amount' => $product->price,
                'subtotal_amount' => $product->price,
                'discount_amount' => 0.0,
                'shipping_fee' => 0.0,
                'currency' => \App\Enums\Currency::BDT,
                'status' => \App\Enums\OrderStatus::CONFIRMED,
                'payment_status' => \App\Enums\PaymentStatus::PAID,
                'payment_gateway' => \App\Enums\PaymentGateway::SSLCOMMERZ,
                'shipping_address' => [
                    'line_1' => 'Road 79, Gulshan-2',
                    'city' => 'Dhaka',
                    'country' => 'Bangladesh',
                ],
            ]);

            \App\Models\OrderItem::firstOrCreate(['order_id' => $order->id, 'product_id' => $product->id], [
                'product_title' => $product->title,
                'sku' => $product->sku,
                'unit_price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
            ]);

            \App\Models\OrderTimeline::firstOrCreate(['order_id' => $order->id, 'status' => 'confirmed'], [
                'actor_type' => 'curator',
                'note' => 'Order verified by master curator. Ready for insured dispatch.',
                'customer_notified' => true,
            ]);
        }
    }
}
