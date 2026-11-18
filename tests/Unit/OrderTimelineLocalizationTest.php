<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\OrderTimeline;
use Tests\TestCase;

class OrderTimelineLocalizationTest extends TestCase
{
    public function test_order_timeline_localized_status_and_note(): void
    {
        $timeline = new OrderTimeline([
            'status' => 'pending',
            'note' => 'Order placed through storefront one-page checkout.',
            'actor_type' => 'customer',
        ]);

        app()->setLocale('bn');
        $this->assertSame('অর্ডার গৃহীত (যাচাই চলছে)', $timeline->localizedStatus());
        $this->assertSame('স্টোরফ্রন্ট ওয়ান-পেজ চেকআউটের মাধ্যমে অর্ডার সম্পন্ন হয়েছে।', $timeline->localizedNote());

        app()->setLocale('en');
        $this->assertSame('Pending Review', $timeline->localizedStatus());
        $this->assertSame('Order placed through storefront one-page checkout.', $timeline->localizedNote());
    }

    public function test_order_timeline_dynamic_regex_notes(): void
    {
        $timeline = new OrderTimeline([
            'status' => 'confirmed',
            'note' => 'Advance delivery fee of ৳150 paid via bKash (TrxID: TRX12345). Parcel queued for packaging. Remaining ৳2,000 due on delivery.',
            'actor_type' => 'payment_gateway',
        ]);

        app()->setLocale('bn');
        $this->assertStringContainsString('বিকাশের মাধ্যমে ৳150 অগ্রিম ডেলিভারি ফি পরিশোধ করা হয়েছে', $timeline->localizedNote());
        $this->assertStringContainsString('TRX12345', $timeline->localizedNote());

        app()->setLocale('en');
        $this->assertStringContainsString('Advance delivery fee of ৳150 paid via bKash', $timeline->localizedNote());
    }
}
