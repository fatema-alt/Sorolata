<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Announcement;
use App\Models\Collection;
use App\Models\District;
use App\Models\MarketingPopup;
use App\Models\MenuItem;
use App\Models\OrderTimeline;
use App\Models\Page;
use App\Models\ProductFaq;
use App\Models\ProductSpecification;
use Tests\TestCase;

class HasBilingualAttributesModelTest extends TestCase
{
    public function test_district_bilingual_and_name_compatibility(): void
    {
        $district = new District([
            'name_en' => 'Tangail',
            'name_bn' => 'টাঙ্গাইল',
            'gi_specialty' => 'Tangail Weaving',
            'gi_specialty_bn' => 'টাঙ্গাইল তাঁতশিল্প',
        ]);

        app()->setLocale('en');
        $this->assertSame('Tangail', $district->name_en);
        $this->assertSame('টাঙ্গাইল', $district->name_bn);
        $this->assertSame('Tangail', $district->getLocalized('name'));
        $this->assertSame('Tangail', $district->name);
        $this->assertSame('Tangail Weaving', $district->getLocalized('gi_specialty'));

        app()->setLocale('bn');
        $this->assertSame('Tangail', $district->name_en);
        $this->assertSame('টাঙ্গাইল', $district->name_bn);
        $this->assertSame('টাঙ্গাইল', $district->getLocalized('name'));
        $this->assertSame('টাঙ্গাইল', $district->name);
        $this->assertSame('টাঙ্গাইল তাঁতশিল্প', $district->getLocalized('gi_specialty'));
    }

    public function test_collection_bilingual(): void
    {
        $collection = new Collection([
            'title' => 'Monsoon Silks',
            'title_bn' => 'বর্ষার সিল্ক কালেকশন',
            'description' => 'Pure handwoven silk.',
            'description_bn' => 'খাঁটি হাতে বোনা সিল্ক।',
        ]);

        app()->setLocale('en');
        $this->assertSame('Monsoon Silks', $collection->getLocalized('title'));
        $this->assertSame('Monsoon Silks', $collection->getLocalized('name'));
        $this->assertSame('Pure handwoven silk.', $collection->getLocalized('description'));

        app()->setLocale('bn');
        $this->assertSame('বর্ষার সিল্ক কালেকশন', $collection->getLocalized('title'));
        $this->assertSame('বর্ষার সিল্ক কালেকশন', $collection->getLocalized('name'));
        $this->assertSame('খাঁটি হাতে বোনা সিল্ক।', $collection->getLocalized('description'));
    }

    public function test_menu_item_and_announcement_bilingual(): void
    {
        $menuItem = new MenuItem([
            'title' => 'Artisans',
            'bn_title' => 'কারিগরবৃন্দ',
        ]);

        app()->setLocale('en');
        $this->assertSame('Artisans', $menuItem->getLocalized('title'));
        $this->assertSame('Artisans', $menuItem->label);

        app()->setLocale('bn');
        $this->assertSame('কারিগরবৃন্দ', $menuItem->getLocalized('title'));
        $this->assertSame('কারিগরবৃন্দ', $menuItem->label);

        $announcement = new Announcement([
            'text' => 'Complimentary Delivery',
            'bn_text' => 'ফ্রি ডেলিভারি',
        ]);

        app()->setLocale('en');
        $this->assertSame('Complimentary Delivery', $announcement->getLocalized('text'));

        app()->setLocale('bn');
        $this->assertSame('ফ্রি ডেলিভারি', $announcement->getLocalized('text'));
    }
}
