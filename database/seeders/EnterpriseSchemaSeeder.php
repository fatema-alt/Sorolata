<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Artisan;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\BuilderGlobalComponent;
use App\Models\Campaign;
use App\Models\CraftType;
use App\Models\CustomerProfile;
use App\Models\DesignToken;
use App\Models\District;
use App\Models\Division;
use App\Models\Material;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductFaq;
use App\Models\ProductOrigin;
use App\Models\ProductSpecification;
use App\Models\SeoMetadata;
use App\Models\Tag;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnterpriseSchemaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bangladesh Divisions
        $divisionsData = [
            ['name' => 'Dhaka', 'bn_name' => 'ঢাকা', 'slug' => 'dhaka', 'coordinates' => ['lat' => 23.8103, 'lng' => 90.4125]],
            ['name' => 'Chittagong', 'bn_name' => 'চট্টগ্রাম', 'slug' => 'chittagong', 'coordinates' => ['lat' => 22.3569, 'lng' => 91.7832]],
            ['name' => 'Rajshahi', 'bn_name' => 'রাজশাহী', 'slug' => 'rajshahi', 'coordinates' => ['lat' => 24.3745, 'lng' => 88.6042]],
            ['name' => 'Khulna', 'bn_name' => 'খুলনা', 'slug' => 'khulna', 'coordinates' => ['lat' => 22.8456, 'lng' => 89.5403]],
            ['name' => 'Sylhet', 'bn_name' => 'সিলেট', 'slug' => 'sylhet', 'coordinates' => ['lat' => 24.8949, 'lng' => 91.8687]],
            ['name' => 'Barisal', 'bn_name' => 'বরিশাল', 'slug' => 'barisal', 'coordinates' => ['lat' => 22.7010, 'lng' => 90.3535]],
            ['name' => 'Rangpur', 'bn_name' => 'রংপুর', 'slug' => 'rangpur', 'coordinates' => ['lat' => 25.7439, 'lng' => 89.2752]],
            ['name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ', 'slug' => 'mymensingh', 'coordinates' => ['lat' => 24.7471, 'lng' => 90.4203]],
        ];

        $divisions = [];
        foreach ($divisionsData as $d) {
            $divisions[$d['slug']] = Division::updateOrCreate(['slug' => $d['slug']], $d);
        }

        // Link existing districts to divisions
        District::whereIn('name_en', ['Tangail', 'Narayanganj', 'Dhaka', 'Gazipur', 'Manikganj', 'Munshiganj', 'Narsingdi'])
            ->update(['division_id' => $divisions['dhaka']->id]);
        District::whereIn('name_en', ['Rajshahi', 'Chapai Nawabganj', 'Bogra', 'Pabna', 'Sirajganj', 'Natore', 'Naogaon'])
            ->update(['division_id' => $divisions['rajshahi']->id]);
        District::whereIn('name_en', ['Jamalpur', 'Mymensingh', 'Netrokona', 'Sherpur'])
            ->update(['division_id' => $divisions['mymensingh']->id]);

        $dhakaDist = District::where('name_en', 'Narayanganj')->first() ?? District::first();

        // 2. Craft Types
        $jamdaniCraft = CraftType::updateOrCreate(['slug' => 'jamdani-handloom'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Jamdani Fine Discontinuous Weft Handloom',
            'bn_name' => 'ঐতিহ্যবাহী জামদানি বুননশিল্প',
            'description' => 'UNESCO Intangible Cultural Heritage of Humanity and Bangladesh GI certified muslin handloom weave.',
            'origin_district_id' => $dhakaDist?->id,
            'is_gi_certified' => true,
            'icon' => 'spark-loom',
        ]);

        $silkCraft = CraftType::updateOrCreate(['slug' => 'rajshahi-mulberry-silk'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Rajshahi Pure Mulberry Silk (Reshom)',
            'bn_name' => 'রাজশাহী তুত রেশম রেশমী বুনন',
            'description' => 'Geographical Indication certified pure mulberry sericulture weave with natural thermal balance.',
            'origin_district_id' => District::where('name_en', 'Rajshahi')->first()?->id,
            'is_gi_certified' => true,
            'icon' => 'silk-thread',
        ]);

        // 3. Materials
        $cottonMat = Material::updateOrCreate(['slug' => 'deshi-organic-cotton'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Indigenous Comilla Hill Organic Cotton',
            'bn_name' => 'দেশি পাহাড়ি অর্গানিক কার্পাস তুলা',
            'origin' => 'Chittagong Hill Tracts & Comilla Basin',
            'is_sustainable' => true,
            'certification' => 'GOTS Organic Certified Grade-A',
        ]);

        $silkMat = Material::updateOrCreate(['slug' => 'mulberry-silk-filament'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Padma Basin Mulberry Silk Filament',
            'bn_name' => 'পদ্মা অববাহিকার তুঁত রেশম আঁশ',
            'origin' => 'Bholahat, Chapai Nawabganj',
            'is_sustainable' => true,
            'certification' => 'Bangladesh Silk Development Board Certified',
        ]);

        // 4. Workshops
        $firstArtisan = Artisan::first();
        if ($firstArtisan && $dhakaDist) {
            Workshop::updateOrCreate(['name' => 'Sonargaon Heritage Loom Collective'], [
                'uuid' => (string) Str::uuid(),
                'artisan_id' => $firstArtisan->id,
                'district_id' => $dhakaDist->id,
                'location' => 'Mograpara, Sonargaon, Narayanganj',
                'craft_specialty' => '100+ Count Royal Jamdani Weaving',
                'founded_year' => 1978,
                'workers_count' => 18,
                'description' => 'Three generations of master weavers preserving geometric river motifs alongside the Shitalakshya River.',
                'images' => ['/images/workshops/sonargaon-atelier-01.jpg'],
                'is_active' => true,
            ]);
        }

        // 5. Attributes & Values
        $colorAttr = Attribute::updateOrCreate(['slug' => 'color'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Heritage Color Palettes',
            'type' => 'color',
            'is_filterable' => true,
            'is_searchable' => true,
        ]);

        AttributeValue::updateOrCreate(['attribute_id' => $colorAttr->id, 'value' => 'neel-indigo'], [
            'label' => 'Bengal Neel / Indigo',
            'color_hex' => '#1F3A52',
            'sort_order' => 1,
        ]);

        AttributeValue::updateOrCreate(['attribute_id' => $colorAttr->id, 'value' => 'terracotta-red'], [
            'label' => 'Rupganj Terracotta Red',
            'color_hex' => '#C96A3D',
            'sort_order' => 2,
        ]);

        AttributeValue::updateOrCreate(['attribute_id' => $colorAttr->id, 'value' => 'raw-ivory'], [
            'label' => 'Unbleached Muslin Ivory',
            'color_hex' => '#F8F6F2',
            'sort_order' => 3,
        ]);

        $countAttr = Attribute::updateOrCreate(['slug' => 'thread-count'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Weave Fineness & Thread Count',
            'type' => 'select',
            'is_filterable' => true,
            'is_searchable' => true,
        ]);

        AttributeValue::updateOrCreate(['attribute_id' => $countAttr->id, 'value' => '84-count'], [
            'label' => '84 Count Heirloom Weave',
            'sort_order' => 1,
        ]);

        AttributeValue::updateOrCreate(['attribute_id' => $countAttr->id, 'value' => '100-count-royal'], [
            'label' => '100 Count Imperial Royal Weave',
            'sort_order' => 2,
        ]);

        // 6. Warehouses
        $dhakaWarehouse = Warehouse::updateOrCreate(['code' => 'WH-DHK-01'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Dhaka Central Heritage Sanctuary Depot',
            'contact_person' => 'Mohiuddin Ahmed',
            'phone' => '+8801711000001',
            'email' => 'logistics@sorolata.com',
            'address' => 'Road 11, House 42, Block D, Banani, Dhaka 1213',
            'district_id' => District::where('name_en', 'Dhaka')->first()?->id ?? $dhakaDist?->id,
            'is_active' => true,
            'is_default' => true,
        ]);

        $narayanganjWarehouse = Warehouse::updateOrCreate(['code' => 'WH-NRG-02'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Shitalakshya River Loom Clearing Depot',
            'contact_person' => 'Zakir Hossain',
            'phone' => '+8801711000002',
            'email' => 'sonargaon-depot@sorolata.com',
            'address' => 'Near Folk Art Museum, Mograpara, Sonargaon, Narayanganj',
            'district_id' => $dhakaDist?->id,
            'is_active' => true,
            'is_default' => false,
        ]);

        // 7. Enrich First Product with Separated Content, Origin, Specs & FAQs
        $flagship = Product::first();
        if ($flagship) {
            ProductContent::updateOrCreate(['product_id' => $flagship->id], [
                'story_title' => 'Born of Shitalakshya River Moisture & Moonlit Looms',
                'story_narrative' => 'Woven exclusively during early morning mist when ambient humidity preserves the delicate 100-count cotton fibers from snapping under tension. Each floral karpash motif is laid without preliminary tracing, relying on hereditary muscle memory passed through five generations.',
                'craft_technique' => 'Discontinuous supplementary weft technique on two-pedal pit looms with tamarind-wood shuttles.',
                'heritage_significance' => 'Certified under Geographical Indication Act (GI Registration BD-01) protecting authentic Bengal handloom origin.',
                'care_guide' => 'Dry clean only using museum-grade hydrocarbon or gentle steam. Store wrapped in unbleached mulmul cloth; avoid plastic sealing to allow natural fiber breathability.',
                'video_embed_url' => 'https://vimeo.com/76979871',
            ]);

            $firstWorkshop = Workshop::first();
            ProductOrigin::updateOrCreate(['product_id' => $flagship->id], [
                'district_id' => $flagship->district_id,
                'artisan_id' => $flagship->artisan_id,
                'workshop_id' => $firstWorkshop?->id,
                'craft_type_id' => $jamdaniCraft->id,
                'gi_certificate_number' => 'GI-BD-2016-01-SRL',
                'harvest_season' => 'Winter Post-Monsoon Sericulture',
                'provenance_story' => 'Direct from the pit-looms of Mograpara, Sonargaon basin.',
                'geo_coordinates' => ['lat' => 23.6500, 'lng' => 90.6000],
            ]);

            // Product Materials
            $flagship->materials()->syncWithoutDetaching([
                $cottonMat->id => ['percentage' => 85],
                $silkMat->id => ['percentage' => 15],
            ]);

            // Specifications
            $specs = [
                ['group_name' => 'Textile Anatomy', 'attribute_name' => 'Warp Material', 'attribute_value' => 'Fine combed organic cotton (84 count)', 'sort_order' => 1],
                ['group_name' => 'Textile Anatomy', 'attribute_name' => 'Weft Supplementary', 'attribute_value' => 'Mulberry silk and golden zari inlay', 'sort_order' => 2],
                ['group_name' => 'Textile Anatomy', 'attribute_name' => 'Length & Width', 'attribute_value' => '5.5 Meters (12 Haat) with 0.8M matching blouse piece', 'sort_order' => 3],
                ['group_name' => 'Heritage Provenance', 'attribute_name' => 'Loom Type', 'attribute_value' => 'Traditional Two-Treadle Pit Loom', 'sort_order' => 4],
                ['group_name' => 'Heritage Provenance', 'attribute_name' => 'Creation Duration', 'attribute_value' => '84 Days of dedicated continuous craftsmanship', 'sort_order' => 5],
            ];

            foreach ($specs as $spec) {
                ProductSpecification::updateOrCreate([
                    'product_id' => $flagship->id,
                    'attribute_name' => $spec['attribute_name'],
                ], $spec);
            }

            // FAQs
            $faqs = [
                [
                    'question' => 'How can I verify the authentic Geographical Indication (GI) of this Jamdani?',
                    'answer' => 'Each Sorolata masterpiece arrives with a numbered Archival Certificate of Provenance containing the exact master artisan signature, loom GPS coordinates, and a verifiable serial registered with the Bangladesh Small & Cottage Industries Corporation.',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'question' => 'What is the recommended method to preserve this heirloom saree over decades?',
                    'answer' => 'Wrap the saree in a clean, breathable unbleached mulmul or muslin cloth. Refold the garment every 3-4 months along different crease lines to prevent fiber stress. Never use chemical mothballs directly on natural dyes.',
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'question' => 'Do you provide international insured shipping for collector pieces?',
                    'answer' => 'Yes. We provide temperature-stabilized, insured express transit via DHL Express to USA, UK, Canada, Australia and Europe with complete customs duty documentation included.',
                    'sort_order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($faqs as $faq) {
                ProductFaq::updateOrCreate([
                    'product_id' => $flagship->id,
                    'question' => $faq['question'],
                ], $faq);
            }

            // Warehouse Stocks
            WarehouseStock::updateOrCreate([
                'warehouse_id' => $dhakaWarehouse->id,
                'product_id' => $flagship->id,
                'variant_id' => null,
            ], [
                'quantity_on_hand' => 5,
                'quantity_reserved' => 1,
                'safety_stock' => 2,
            ]);

            WarehouseStock::updateOrCreate([
                'warehouse_id' => $narayanganjWarehouse->id,
                'product_id' => $flagship->id,
                'variant_id' => null,
            ], [
                'quantity_on_hand' => 3,
                'quantity_reserved' => 0,
                'safety_stock' => 1,
            ]);

            // SEO Metadata
            SeoMetadata::updateOrCreate([
                'seoable_type' => Product::class,
                'seoable_id' => $flagship->id,
            ], [
                'meta_title' => $flagship->title . ' — Genuine GI Certified Handloom | Sorolata',
                'meta_description' => 'Rare handcrafted Bengal Jamdani saree by master artisan. 100% authentic handloom, unbleached organic cotton, museum-grade textile provenance.',
                'canonical_url' => url('/products/' . $flagship->slug),
                'schema_type' => 'Product',
                'robots' => 'index,follow',
                'focus_keywords' => ['jamdani saree', 'bangladesh handloom', 'sonargaon heritage', 'gi certified'],
                'is_indexable' => true,
            ]);
        }

        // 8. Navigation Menus
        $headerMenu = Menu::updateOrCreate(['code' => 'header_main'], [
            'name' => 'Main Editorial Header Navigation',
            'location' => 'header',
        ]);

        $menuItems = [
            ['title' => 'Curated Atelier', 'bn_title' => 'হেরিটেজ শপ', 'url' => '/shop', 'sort_order' => 1],
            ['title' => 'Master Artisans', 'bn_title' => 'কারিগর গিল্ড', 'url' => '/artisans', 'sort_order' => 2],
            ['title' => 'River Basins & Districts', 'bn_title' => '৬৪ জেলা ঐতিহ্য', 'url' => '/districts', 'sort_order' => 3],
            ['title' => 'Heritage Gazette', 'bn_title' => 'গ্যাজেট স্টোরিজ', 'url' => '/stories', 'sort_order' => 4],
            ['title' => 'Collections', 'bn_title' => 'কিউরেটেড কালেকশন', 'url' => '/collections', 'sort_order' => 5],
            ['title' => 'About Sorolata', 'bn_title' => 'আমাদের দর্শন', 'url' => '/about', 'sort_order' => 6],
        ];

        foreach ($menuItems as $item) {
            MenuItem::updateOrCreate([
                'menu_id' => $headerMenu->id,
                'title' => $item['title'],
            ], $item);
        }

        // 9. Design Tokens & Global Components
        $tokens = [
            ['token_key' => 'color_primary_forest', 'token_group' => 'color', 'token_value' => '#214E34', 'description' => 'Primary Bengal Deep Forest Green'],
            ['token_key' => 'color_secondary_terracotta', 'token_group' => 'color', 'token_value' => '#C96A3D', 'description' => 'Secondary Riverbed Terracotta'],
            ['token_key' => 'color_background_cream', 'token_group' => 'color', 'token_value' => '#F8F6F2', 'description' => 'Warm Cream Archival Canvas'],
            ['token_key' => 'font_heading_editorial', 'token_group' => 'typography', 'token_value' => 'Cormorant Garamond', 'description' => 'Editorial Serif Display Font'],
            ['token_key' => 'font_body_manrope', 'token_group' => 'typography', 'token_value' => 'Manrope', 'description' => 'Modern Sans Body Typography'],
        ];

        foreach ($tokens as $t) {
            DesignToken::updateOrCreate(['token_key' => $t['token_key']], $t);
        }

        BuilderGlobalComponent::updateOrCreate(['name' => 'Artisan Provenance Seal'], [
            'uuid' => (string) Str::uuid(),
            'category' => 'story',
            'content' => [
                'type' => 'provenance_seal',
                'title' => 'Authenticated by National Crafts Council',
                'verified_badge' => true,
                'signature_line' => 'Handcrafted on heritage pit looms in Narayanganj basin.',
            ],
        ]);

        // 10. Customer Profiles for Users
        $admin = User::first();
        if ($admin) {
            CustomerProfile::updateOrCreate(['user_id' => $admin->id], [
                'phone_verified_at' => now(),
                'membership_tier' => 'patron',
                'loyalty_points' => 1250,
                'total_spent' => 45000.00,
                'orders_count' => 3,
                'preferred_currency' => 'BDT',
                'preferred_locale' => 'en',
            ]);
        }

        // 11. Global Announcement
        Announcement::updateOrCreate(['text' => 'Complimentary White-Glove Insured Delivery to USA, UK, Canada & Bangladesh on Orders Above ৳6,000'], [
            'bn_text' => '৳৬,০০০ এর অধিক অর্ডারে বাংলাদেশ, যুক্তরাষ্ট্র, যুক্তরাজ্য ও কানাডায় বিনামূল্যে ইনশিওরড হোম ডেলিভারি',
            'link_url' => '/shop',
            'bg_color' => '#214E34',
            'text_color' => '#F8F6F2',
            'is_active' => true,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonths(6),
        ]);
    }
}
