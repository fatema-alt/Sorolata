<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Artisan;
use App\Models\District;
use App\Models\Product;
use App\Services\Builder\DynamicDataParserService;
use Tests\TestCase;

class BuilderBilingualSupportTest extends TestCase
{
    public function test_builder_block_bilingual_array_and_json_with_fallback(): void
    {
        $service = new DynamicDataParserService();

        // 1. Array with {en, bn}
        $bilingualArray = [
            'en' => 'Preserving Bengal Heritage',
            'bn' => 'বাংলার ঐতিহ্য সংরক্ষণ',
        ];

        app()->setLocale('bn');
        $this->assertSame('বাংলার ঐতিহ্য সংরক্ষণ', $service->parse($bilingualArray));

        app()->setLocale('en');
        $this->assertSame('Preserving Bengal Heritage', $service->parse($bilingualArray));

        // 2. JSON string with {en, bn}
        $bilingualJson = json_encode([
            'en' => 'Master Handloom Weavers',
            'bn' => 'মাস্টার তাঁত শিল্পী',
        ]);

        app()->setLocale('bn');
        $this->assertSame('মাস্টার তাঁত শিল্পী', $service->parse($bilingualJson));

        app()->setLocale('en');
        $this->assertSame('Master Handloom Weavers', $service->parse($bilingualJson));

        // 3. Fallback when bn is missing
        $englishOnlyArray = [
            'en' => 'Only English Title',
        ];

        app()->setLocale('bn');
        $this->assertSame('Only English Title', $service->parse($englishOnlyArray));
    }

    public function test_builder_token_replacements_are_localized(): void
    {
        $service = new DynamicDataParserService();

        $product = new Product([
            'title' => 'Jamdani Sari',
            'title_bn' => 'জামদানি শাড়ি',
        ]);

        $artisan = new Artisan([
            'full_name' => 'Alim Master',
            'full_name_bn' => 'আলিম ওস্তাদ',
        ]);

        $district = new District([
            'name_en' => 'Sonargaon',
            'name_bn' => 'সোনারগাঁও',
        ]);

        app()->setLocale('bn');
        $this->assertSame('জামদানি শাড়ি - আলিম ওস্তাদ - সোনারগাঁও', $service->parse('{{ product.title }} - {{ artisan.name }} - {{ district.name }}', $product, $artisan, $district));

        app()->setLocale('en');
        $this->assertSame('Jamdani Sari - Alim Master - Sonargaon', $service->parse('{{ product.title }} - {{ artisan.name }} - {{ district.name }}', $product, $artisan, $district));
    }
}
