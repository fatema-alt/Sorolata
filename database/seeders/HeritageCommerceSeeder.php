<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ArtisanCraftType;
use App\Enums\BlockType;
use App\Enums\Currency;
use App\Enums\DistrictRegion;
use App\Enums\ProductStatus;
use App\Enums\ThemeMode;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\District;
use App\Models\MediaAsset;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HeritageCommerceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Heritage Categories
        $textiles = Category::firstOrCreate([
            'slug' => 'heritage-handloom-textiles',
        ], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Heritage Handloom Textiles',
            'description' => 'Rare handloom, muslin, silk, and jamdani textiles woven on traditional wooden pit-looms.',
            'sort_order' => 1,
        ]);

        $metalware = Category::firstOrCreate([
            'slug' => 'bell-metal-brassware',
        ], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Bell Metal & Brassware',
            'description' => 'Hand-forged brass, bronze, and bell-metal tableware and heirlooms from ancestral ateliers.',
            'sort_order' => 2,
        ]);

        $homeCarpets = Category::firstOrCreate([
            'slug' => 'traditional-rugs-carpets',
        ], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Traditional Rugs & Shatranji',
            'description' => 'Centuries-old flatweave floor art woven with natural fibers in Rangpur.',
            'sort_order' => 3,
        ]);

        // 1.1 Create Curated Collections
        \App\Models\Collection::firstOrCreate([
            'slug' => 'royal-muslin-jamdani-drop',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'The Royal Muslin & Jamdani Drop',
            'description' => '300-count gossamer threads spun along the misty morning banks of the Shitalakshya River.',
            'cover_image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1200&auto=format&fit=crop',
            'is_featured' => true,
        ]);

        \App\Models\Collection::firstOrCreate([
            'slug' => 'ancestral-bell-metal-kansa',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Ancestral Bell-Metal & Kansa Tableware',
            'description' => 'Hand-chiseled bronze alloys cast with ancient Mahasthangarh methods in Bogura.',
            'cover_image' => 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1200&auto=format&fit=crop',
            'is_featured' => true,
        ]);

        // 2. Create Heritage Districts
        $narayanganj = District::firstOrCreate([
            'name_en' => 'Narayanganj (Sonargaon)',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'narayanganj-sonargaon',
            'name_bn' => 'নারায়ণগঞ্জ (সোনারগাঁ)',
            'division' => DistrictRegion::DHAKA,
            'gi_specialty' => 'Dhakai Muslin & Traditional Jamdani Weaving',
            'historical_narrative' => 'Sonargaon was the historic golden capital of Bengal. The Shitalakshya River provided the unique atmospheric humidity required to spin fine 300+ count muslin threads without breakage.',
            'river_basin' => 'Shitalakshya & Meghna River Basin',
            'latitude' => 23.6495,
            'longitude' => 90.5985,
        ]);

        $tangail = District::firstOrCreate([
            'name_en' => 'Tangail',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'tangail',
            'name_bn' => 'টাঙ্গাইল',
            'division' => DistrictRegion::DHAKA,
            'gi_specialty' => 'Tangail Saree & Pure Silk Weaving',
            'historical_narrative' => 'The Basak artisan community migrated to the fertile banks of the Louhajang River during the British period, perfecting fine jacquard and hand-knotted borders.',
            'river_basin' => 'Jamuna & Louhajang River Basin',
            'latitude' => 24.2513,
            'longitude' => 89.9167,
        ]);

        $bogura = District::firstOrCreate([
            'name_en' => 'Bogura',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'bogura',
            'name_bn' => 'বগুড়া',
            'division' => DistrictRegion::RAJSHAHI,
            'gi_specialty' => 'Hand-Chiseled Brass & Bell-Metal Craft',
            'historical_narrative' => 'Dating back to the Mahasthangarh era (3rd century BC), Bogura metalworkers cast brassware using ancient lost-wax and hand-hammering techniques.',
            'river_basin' => 'Karatoya River Basin',
            'latitude' => 24.8465,
            'longitude' => 89.3777,
        ]);

        $rangpur = District::firstOrCreate([
            'name_en' => 'Rangpur (Nisbetganj)',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'rangpur-nisbetganj',
            'name_bn' => 'রংপুর (নিসবেতগঞ্জ)',
            'division' => DistrictRegion::RANGPUR,
            'gi_specialty' => 'Shatranji Flatweave Tapestries & Carpets',
            'historical_narrative' => 'The Mughals commissioned Shatranji carpets for imperial courts. In 1830, British collector Mr. Nisbet revitalized the weaver village now honored as Nisbetganj.',
            'river_basin' => 'Ghaghat & Teesta River Basin',
            'latitude' => 25.7439,
            'longitude' => 89.2752,
        ]);

        // 3. Create Master Artisans
        $artisanAlimuddin = Artisan::firstOrCreate([
            'full_name' => 'Master Weaver Alimuddin',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'master-weaver-alimuddin',
            'district_id' => $narayanganj->id,
            'atelier_name' => 'Shitalakshya Muslin Guild',
            'craft_type' => ArtisanCraftType::MUSLIN_HANDSPUN,
            'years_of_mastery' => 42,
            'biography' => 'Born into the fifth generation of Dhakai weavers, Alimuddin dedicated four decades to resurrecting the lost technique of Phuti Karpas hand-spinning.',
            'quote' => 'When dawn mist rises off the Shitalakshya, the cotton thread becomes as supple as river water.',
            'portrait_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1000&auto=format&fit=crop',
            'is_verified' => true,
            'awards' => ['National Crafts Council Master Award 2021', 'UNESCO Heritage Living Legend Recognition'],
        ]);

        $artisanRabeya = Artisan::firstOrCreate([
            'full_name' => 'Rabeya Begum',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'rabeya-begum',
            'district_id' => $tangail->id,
            'atelier_name' => 'Louhajang Handloom Atelier',
            'craft_type' => ArtisanCraftType::JAMDANI_WEAVING,
            'years_of_mastery' => 38,
            'biography' => 'Rabeya works purely from memory without paper stencils, counting each motif thread in rhythmic cadence.',
            'quote' => 'A true Jamdani has no backside; both surfaces are woven identically with the patience of prayer.',
            'portrait_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1000&auto=format&fit=crop',
            'is_verified' => true,
            'awards' => ['Shilpacharya Lifetime Merit Award'],
        ]);

        $artisanGourango = Artisan::firstOrCreate([
            'full_name' => 'Gourango Karmakar',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'gourango-karmakar',
            'district_id' => $bogura->id,
            'atelier_name' => 'Karatoya Bell-Metal Foundry',
            'craft_type' => ArtisanCraftType::BRASS_BELL_METAL,
            'years_of_mastery' => 46,
            'biography' => 'Gourango hand-hammers molten bell metal using coal embers and hand bellows, creating acoustic resonance found nowhere else.',
            'quote' => 'Strike the bell metal true, and it rings with the sound of pure silver.',
            'portrait_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1000&auto=format&fit=crop',
            'is_verified' => true,
            'awards' => ['National Folk Art Council Gold Medal'],
        ]);

        $artisanMofizul = Artisan::firstOrCreate([
            'full_name' => 'Mofizul Islam',
        ], [
            'uuid' => (string) Str::uuid(),
            'slug' => 'mofizul-islam',
            'district_id' => $rangpur->id,
            'atelier_name' => 'Nisbetganj Royal Shatranji Guild',
            'craft_type' => ArtisanCraftType::SHATRANJI_FLATWEAVE,
            'years_of_mastery' => 32,
            'biography' => 'Leading 24 master weavers in Nisbetganj, Mofizul uses handspun jute and indigenous vegetable indigo dyes.',
            'quote' => 'The geometric diamonds in Shatranji represent the rising sun across the Teesta plains.',
            'portrait_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1000&auto=format&fit=crop',
            'is_verified' => true,
            'awards' => ['President Award for Traditional Handicrafts'],
        ]);

        // 4. Create Flagship Products with JSON Page Builder Layouts
        $muslinProduct = Product::firstOrCreate([
            'slug' => 'the-imperial-sonargaon-muslin-scarf',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'The Imperial Sonargaon Muslin Scarf',
            'subtitle' => 'Handspun 300-count Phuti Karpas cotton woven on a wooden pit-loom over 220 hours.',
            'sku' => 'SRL-MSL-001',
            'price' => 18500.00,
            'compare_at_price' => 22000.00,
            'cost_price' => 12000.00,
            'stock_quantity' => 4,
            'currency' => Currency::BDT,
            'status' => ProductStatus::PUBLISHED,
            'category_id' => $textiles->id,
            'artisan_id' => $artisanAlimuddin->id,
            'district_id' => $narayanganj->id,
            'is_featured' => true,
            'published_at' => now(),
            'theme_tokens' => [
                'mode' => ThemeMode::WARM_HERITAGE->value,
                'accent_color' => '#9E4732',
                'bg_color' => '#FBF9F5',
            ],
            'heritage_specs' => [
                'Fiber' => '100% Handspun Phuti Karpas (Fine Gossamer Cotton)',
                'Thread Count' => '300s Count pit-loom warp & weft',
                'Weaving Time' => '220 hours of single-artisan labor',
                'Dye Technique' => 'Unbleached natural ecru, washed in Shitalakshya river water',
                'Weight' => '28 grams (so light it passes through an heirloom ring)',
            ],
            'story_meta' => [
                'origin_era' => 'Resurrected 17th Century Mughal Court Weave',
                'preservation_tier' => 'National Living Heritage',
            ],
            'layout_config' => [
                'version' => '1.0',
                'blocks' => [
                    [
                        'id' => 'hero_01',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'order' => 1,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'GI Certified Heritage Muslin • 100% Handspun',
                        ],
                    ],
                    [
                        'id' => 'sticky_buy_02',
                        'type' => BlockType::STICKY_PURCHASE_BOX->value,
                        'order' => 2,
                        'is_active' => true,
                        'settings' => [],
                    ],
                    [
                        'id' => 'artisan_03',
                        'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                        'order' => 3,
                        'is_active' => true,
                        'settings' => [],
                    ],
                    [
                        'id' => 'gallery_04',
                        'type' => BlockType::INTERACTIVE_GALLERY->value,
                        'order' => 4,
                        'is_active' => true,
                        'settings' => [],
                    ],
                    [
                        'id' => 'provenance_05',
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'order' => 5,
                        'is_active' => true,
                        'settings' => [],
                    ],
                    [
                        'id' => 'specs_06',
                        'type' => BlockType::SPECS_ANATOMY->value,
                        'order' => 6,
                        'is_active' => true,
                        'settings' => [],
                    ],
                    [
                        'id' => 'narrative_07',
                        'type' => BlockType::EDITORIAL_NARRATIVE->value,
                        'order' => 7,
                        'is_active' => true,
                        'settings' => [],
                    ],
                    [
                        'id' => 'testimonial_08',
                        'type' => BlockType::TESTIMONIAL_EDITORIAL->value,
                        'order' => 8,
                        'is_active' => true,
                        'settings' => [],
                    ],
                ],
            ],
        ]);

        MediaAsset::create([
            'uuid' => (string) Str::uuid(),
            'attachable_type' => Product::class,
            'attachable_id' => $muslinProduct->id,
            'file_path' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'file_size' => 420000,
            'width' => 1600,
            'height' => 1100,
            'aspect_ratio' => '16/11',
            'alt_text' => 'The Imperial Sonargaon Muslin Scarf on Natural Linen',
            'sort_order' => 1,
        ]);

        // Product 2: Tangail Silk Jamdani
        $jamdaniProduct = Product::firstOrCreate([
            'slug' => 'heirloom-tangail-mulberry-silk-jamdani',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Heirloom Tangail Mulberry Silk Jamdani',
            'subtitle' => 'Hand-knotted supplementary weft geometric motifs on pure organic mulberry silk.',
            'sku' => 'SRL-JMD-002',
            'price' => 24500.00,
            'compare_at_price' => 29000.00,
            'cost_price' => 16000.00,
            'stock_quantity' => 2,
            'currency' => Currency::BDT,
            'status' => ProductStatus::PUBLISHED,
            'category_id' => $textiles->id,
            'artisan_id' => $artisanRabeya->id,
            'district_id' => $tangail->id,
            'is_featured' => true,
            'published_at' => now(),
            'theme_tokens' => [
                'mode' => ThemeMode::NATURAL_LINEN->value,
            ],
            'heritage_specs' => [
                'Fabric' => 'Pure Mulberry Silk with Zari supplementary weft',
                'Motif Tradition' => 'Terchi & Panna Hajar (Thousand Emeralds geometry)',
                'Weaving Duration' => '6 Weeks of dual-weaver synchronization',
                'Loom Style' => 'Generational Tangail Jacquard Pit Loom',
            ],
            'layout_config' => null, // Uses default block collection cleanly
        ]);

        MediaAsset::create([
            'uuid' => (string) Str::uuid(),
            'attachable_type' => Product::class,
            'attachable_id' => $jamdaniProduct->id,
            'file_path' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1600&auto=format&fit=crop',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'file_size' => 380000,
            'width' => 1600,
            'height' => 1200,
            'aspect_ratio' => '4/3',
            'alt_text' => 'Heirloom Tangail Mulberry Silk Jamdani Drape',
            'sort_order' => 1,
        ]);

        // Product 3: Bogura Brass Kettle
        $brassProduct = Product::firstOrCreate([
            'slug' => 'hand-chiseled-royal-bogura-brass-kettle',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Hand-Chiseled Royal Bogura Brass Kettle',
            'subtitle' => 'Sand-cast bell metal, hand-chiseled with floral relief motifs and hand-polished with tamarind.',
            'sku' => 'SRL-BRS-003',
            'price' => 12800.00,
            'cost_price' => 7500.00,
            'stock_quantity' => 6,
            'currency' => Currency::BDT,
            'status' => ProductStatus::PUBLISHED,
            'category_id' => $metalware->id,
            'artisan_id' => $artisanGourango->id,
            'district_id' => $bogura->id,
            'is_featured' => true,
            'published_at' => now(),
            'heritage_specs' => [
                'Metal Alloy' => '80% Copper, 20% Tin (Pure Heritage Kansa Bell-Metal)',
                'Weight' => '2.4 kg of solid hand-hammered metal',
                'Thermal Properties' => 'Keeps beverages piping hot for 90 minutes naturally',
                'Finish' => 'Natural mirror polish using tamarind pulp and river silt',
            ],
        ]);

        MediaAsset::create([
            'uuid' => (string) Str::uuid(),
            'attachable_type' => Product::class,
            'attachable_id' => $brassProduct->id,
            'file_path' => 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1600&auto=format&fit=crop',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'file_size' => 410000,
            'width' => 1600,
            'height' => 1100,
            'aspect_ratio' => '16/11',
            'alt_text' => 'Hand-Chiseled Royal Bogura Brass Kettle',
            'sort_order' => 1,
        ]);

        // Product 4: Rangpur Shatranji Carpet
        $shatranjiProduct = Product::firstOrCreate([
            'slug' => 'rangpur-royal-shatranji-flatweave-carpet',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Rangpur Royal Shatranji Flatweave Carpet',
            'subtitle' => 'Woven without knotting on a horizontal bamboo frame with natural indigo and madder dyed jute.',
            'sku' => 'SRL-SHT-004',
            'price' => 15600.00,
            'cost_price' => 9000.00,
            'stock_quantity' => 3,
            'currency' => Currency::BDT,
            'status' => ProductStatus::PUBLISHED,
            'category_id' => $homeCarpets->id,
            'artisan_id' => $artisanMofizul->id,
            'district_id' => $rangpur->id,
            'is_featured' => true,
            'published_at' => now(),
            'heritage_specs' => [
                'Yarn Material' => 'Natural Golden Jute & Unrefined Handspun Cotton',
                'Colorants' => 'Indigofera Tinctoria (Blue), Madder Root (Red), Turmeric (Gold)',
                'Technique' => 'Hand-pressed bamboo pin flatweave (reversible)',
                'Dimensions' => '180 cm × 120 cm (Living Room Statement Size)',
            ],
        ]);

        MediaAsset::create([
            'uuid' => (string) Str::uuid(),
            'attachable_type' => Product::class,
            'attachable_id' => $shatranjiProduct->id,
            'file_path' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1600&auto=format&fit=crop',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'file_size' => 490000,
            'width' => 1600,
            'height' => 1200,
            'aspect_ratio' => '4/3',
            'alt_text' => 'Rangpur Royal Shatranji Flatweave Carpet in Minimal Living Room',
            'sort_order' => 1,
        ]);

        // 5. Create Heritage Journal Stories
        \App\Models\Story::firstOrCreate([
            'slug' => 'resurrecting-phuti-karpas-the-gossamer-muslin-of-bengal',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Resurrecting Phuti Karpas: The Gossamer Muslin of Bengal',
            'excerpt' => 'How a dedicated collective of researchers and generational pit-loom weavers brought the 300-count imperial textile back from extinction.',
            'content' => 'For nearly two centuries, imperial Dhakai Muslin was mourned as a lost wonder of civilization. Spun from the elusive Phuti Karpas cotton plant that flourished exclusively along the misty morning banks of the Shitalakshya River, its threads were so fine that an entire fifty-meter sari could pass through an ancestral ring...',
            'category' => 'Loom & Heritage',
            'author_name' => 'Dr. Eleanor Vance & Chief Curator',
            'hero_image' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop',
            'reading_time' => 7,
            'is_published' => true,
            'published_at' => now(),
        ]);

        \App\Models\Story::firstOrCreate([
            'slug' => 'the-sacred-geometry-of-jamdani-motifs',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'The Sacred Geometry of Jamdani Motifs: Memory Over Stencils',
            'excerpt' => 'Master weavers in Delduar and Sonargaon memorize centuries of floral and diamond symmetries without ever sketching them on paper.',
            'content' => 'Jamdani is not simply textile decoration; it is spoken mathematics translated into thread tension. The master weaver sings rhythmic cadences called boles to the apprentice seated beside the wooden loom...',
            'category' => 'Weaving Philosophy',
            'author_name' => 'Curator Desk',
            'hero_image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1600&auto=format&fit=crop',
            'reading_time' => 5,
            'is_published' => true,
            'published_at' => now(),
        ]);

        // 6. Create Verified Collector Reviews
        \App\Models\Review::firstOrCreate([
            'author_name' => 'Dr. Eleanor Vance',
            'title' => 'A transcendent museum-grade artifact',
        ], [
            'uuid' => (string) Str::uuid(),
            'product_id' => $muslinProduct->id,
            'artisan_id' => $artisanAlimuddin->id,
            'author_location' => 'Kensington, London',
            'rating' => 5,
            'content' => 'Unboxing this piece in London was a transcendent experience. The tactile weight, the aroma of natural river washed cotton, and the signed provenance letter from Master Weaver Alimuddin makes it the most prized textile in my entire collection.',
            'is_verified_purchase' => true,
            'likes_count' => 24,
        ]);

        \App\Models\Review::firstOrCreate([
            'author_name' => 'Tariqul Rahman',
            'title' => 'Acoustic resonance and breathtaking metalwork',
        ], [
            'uuid' => (string) Str::uuid(),
            'product_id' => $brassProduct->id,
            'artisan_id' => $artisanGourango->id,
            'author_location' => 'Toronto, Canada',
            'rating' => 5,
            'content' => 'The hand-chiseled Bogura brass kettle serves as the crown jewel of our dining room. It holds heat with incredible acoustic resonance.',
            'is_verified_purchase' => true,
            'likes_count' => 19,
        ]);
    }
}
