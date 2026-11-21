<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BlockType;
use App\Models\Page;
use App\Models\PageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Homepage Master Template
        PageTemplate::updateOrCreate([
            'name' => 'Heritage Sanctuary Homepage Master',
        ], [
            'uuid' => (string) Str::uuid(),
            'category' => 'home',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600',
            'is_global' => true,
            'layout_json' => [
                'version' => 1,
                'page_type' => 'home',
                'sections' => [
                    [
                        'id' => 'sec_home_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'Bespoke Heirlooms of Bengal • GI Certified',
                            'headline' => 'Living Traditions of Master Bengali Artisans',
                            'subheadline' => 'Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship.',
                            'primary_cta_text' => 'Acquire Masterpieces',
                            'primary_cta_url' => '/shop',
                            'secondary_cta_text' => 'Artisan Gazette',
                            'secondary_cta_url' => '/stories',
                            'media_type' => 'video',
                            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                            'alignment' => 'center',
                            'text_color' => '#1D1D1D',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_home_artisan',
                        'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Meet Our Generational Master Artisans',
                            'subheadline' => 'Behind every thread and chisel lies a master whose family has perfected their craft over centuries.',
                            'badge_text' => 'Generational Custodians',
                            'media_type' => 'image',
                            'image_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=800',
                            'alignment' => 'left',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_home_provenance',
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'The Geography of Bengal Craftsmanship',
                            'subheadline' => 'From the humid mists of Shitalakshya to the rich sands of the Jamuna river basins.',
                            'badge_text' => 'Geographical Indication',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_home_gallery',
                        'type' => BlockType::INTERACTIVE_GALLERY->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Living Archive Visual Gallery',
                            'subheadline' => 'Macro details of Jamdani motifs, 300-count cotton threads, and hand-chiseled bell metal.',
                            'badge_text' => 'Curated Details',
                            'alignment' => 'center',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_home_testimonials',
                        'type' => BlockType::TESTIMONIAL_EDITORIAL->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Appraisals from Global Connoisseurs',
                            'subheadline' => 'Trusted by collectors across Dhaka, London, Toronto, and New York.',
                            'badge_text' => 'Verified Collector Seal',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
        ]);

        // 2. Product Detail Showcase Template
        PageTemplate::updateOrCreate([
            'name' => 'Imperial Handloom Product Landing',
        ], [
            'uuid' => (string) Str::uuid(),
            'category' => 'product_landing',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600',
            'is_global' => true,
            'layout_json' => [
                'version' => 1,
                'page_type' => 'product',
                'sections' => [
                    [
                        'id' => 'sec_hero_' . uniqid(),
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'GI Certified Heritage Artifact • 100% Handspun',
                            'headline' => '{{ product.title }}',
                            'subheadline' => '{{ product.subtitle }}',
                            'primary_cta_text' => 'Acquire Piece • {{ product.price }}',
                            'primary_cta_url' => '#purchase-box',
                            'alignment' => 'center',
                            'text_color' => '#1D1D1D',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_buy_' . uniqid(),
                        'type' => BlockType::STICKY_PURCHASE_BOX->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Collector Acquisition & Authenticity Seal',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '48px',
                            'padding_bottom' => '48px',
                        ],
                    ],
                    [
                        'id' => 'sec_artisan_' . uniqid(),
                        'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Crafted by {{ artisan.name }}',
                            'subheadline' => 'Generational master of the {{ artisan.craft }} tradition.',
                            'alignment' => 'left',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_specs_' . uniqid(),
                        'type' => BlockType::SPECS_ANATOMY->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Anatomy of Craft & Material Composition',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_gallery_' . uniqid(),
                        'type' => BlockType::INTERACTIVE_GALLERY->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Macro Details & Archival Motifs',
                            'alignment' => 'center',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_provenance_' . uniqid(),
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Geographical Origin: {{ district.name }}',
                            'subheadline' => 'Nourished by the {{ district.river_basin }} currents.',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_narrative_' . uniqid(),
                        'type' => BlockType::EDITORIAL_NARRATIVE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'The Loom Saga & Cultural Lineage',
                            'alignment' => 'left',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
        ]);

        // 3. About Us Living Manifesto Template
        PageTemplate::updateOrCreate([
            'name' => 'Living Manifesto (About Us)',
        ], [
            'uuid' => (string) Str::uuid(),
            'category' => 'about',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600',
            'is_global' => true,
            'layout_json' => [
                'version' => 1,
                'page_type' => 'about',
                'sections' => [
                    [
                        'id' => 'sec_about_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'The Living Manifesto of Bengal',
                            'headline' => 'Reviving Ancient Looms, Elevating Living Masters',
                            'subheadline' => 'Sorolata is not merely an e-commerce platform; it is an archival sanctuary dedicated to protecting the indigenous crafts of South Asia.',
                            'primary_cta_text' => 'Meet Our Artisans',
                            'primary_cta_url' => '/artisans',
                            'alignment' => 'center',
                            'text_color' => '#FFFFFF',
                            'bg_color' => '#214E34',
                            'padding_top' => '96px',
                            'padding_bottom' => '96px',
                        ],
                    ],
                    [
                        'id' => 'sec_about_narrative',
                        'type' => BlockType::EDITORIAL_NARRATIVE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Why Traditional Bengali Craft Demands Safeguarding',
                            'subheadline' => 'In an era of disposable fashion and industrial machines, our generational weavers still count threads by eye and mold alloys over clay furnaces.',
                            'alignment' => 'left',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_about_provenance',
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Our Geographic Footprint & River Lineages',
                            'subheadline' => 'Working across Tangail, Narayanganj, Rajshahi, and Dhamrai artisan clusters.',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_about_gallery',
                        'type' => BlockType::INTERACTIVE_GALLERY->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Inside the Living Ateliers',
                            'subheadline' => 'Witness the patience, tools, and generational techniques documented in our archives.',
                            'alignment' => 'center',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
        ]);

        // 4. Curator Concierge (Contact Us) Template
        PageTemplate::updateOrCreate([
            'name' => 'Curator Concierge & Inquiries (Contact)',
        ], [
            'uuid' => (string) Str::uuid(),
            'category' => 'contact',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?q=80&w=600',
            'is_global' => true,
            'layout_json' => [
                'version' => 1,
                'page_type' => 'contact',
                'sections' => [
                    [
                        'id' => 'sec_contact_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'Private Patron Concierge • Dhaka Atelier',
                            'headline' => 'Consult with Our Master Curators',
                            'subheadline' => 'Inquire about bespoke commissions, international insured courier dispatch, or private viewing appointments.',
                            'primary_cta_text' => 'Message on WhatsApp',
                            'primary_cta_url' => 'https://wa.me/8801700000000',
                            'secondary_cta_text' => 'Direct Curator Email',
                            'secondary_cta_url' => 'mailto:curator@sorolata.com',
                            'alignment' => 'center',
                            'text_color' => '#1D1D1D',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '80px',
                            'padding_bottom' => '80px',
                        ],
                    ],
                    [
                        'id' => 'sec_contact_provenance',
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Global Logistics & Insured White-Glove Courier',
                            'subheadline' => 'Doorstep delivery across Bangladesh, USA, UK, Canada, and Europe with authentication seals.',
                            'alignment' => 'center',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_contact_specs',
                        'type' => BlockType::SPECS_ANATOMY->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Atelier Viewing Hours & Authentication',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
        ]);

        // 5. Festival & Campaign Landing Template
        PageTemplate::updateOrCreate([
            'name' => 'Seasonal Festival Drop (Campaign)',
        ], [
            'uuid' => (string) Str::uuid(),
            'category' => 'campaign',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600',
            'is_global' => true,
            'layout_json' => [
                'version' => 1,
                'page_type' => 'campaign',
                'sections' => [
                    [
                        'id' => 'sec_campaign_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'Seasonal Festival Drop • Limited Batch',
                            'headline' => 'The Autumn Jamdani & Royal Brass Release',
                            'subheadline' => 'Exclusively reserved for private patrons and international expat collectors.',
                            'primary_cta_text' => 'Explore Drop Pieces',
                            'primary_cta_url' => '/shop',
                            'alignment' => 'center',
                            'text_color' => '#FFFFFF',
                            'bg_color' => '#C96A3D',
                            'padding_top' => '96px',
                            'padding_bottom' => '96px',
                        ],
                    ],
                    [
                        'id' => 'sec_campaign_gallery',
                        'type' => BlockType::INTERACTIVE_GALLERY->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Limited Edition Showcase',
                            'subheadline' => 'Each piece is hand-numbered and certified with archival seal.',
                            'alignment' => 'center',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_campaign_artisan',
                        'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Featured Master Weavers of the Drop',
                            'subheadline' => 'Crafting heirloom saris on 200-year-old pit looms.',
                            'alignment' => 'left',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                    [
                        'id' => 'sec_campaign_testimonial',
                        'type' => BlockType::TESTIMONIAL_EDITORIAL->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Patron Testimonials',
                            'alignment' => 'center',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
        ]);

        // Shajgoj Content-to-Commerce Magazine Homepage Template
        PageTemplate::updateOrCreate([
            'name' => 'Shajgoj Content-to-Commerce Magazine Homepage',
        ], [
            'uuid' => (string) Str::uuid(),
            'category' => 'home',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=800',
            'is_global' => true,
            'layout_json' => [
                'version' => 1,
                'page_type' => 'home',
                'sections' => [
                    [
                        'id' => 'sec_mag_hero',
                        'type' => BlockType::MAGAZINE_HERO_GRID->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'HERITAGE DISCOVERY • 5 MIN READ',
                            'headline' => 'Are you a Green or Red Flag? Have You Ever Discovered Your True Craft Soul Amidst Heritage?',
                            'subheadline' => 'Traveling across the historic river basins of Bengal where master weavers and metalsmiths breathe life into centuries of living traditions.',
                            'image_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1200',
                            'primary_cta_text' => 'Read Full Gazette Story',
                            'primary_cta_url' => '/stories',
                            'alignment' => 'left',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '40px',
                            'padding_bottom' => '32px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_bubbles',
                        'type' => BlockType::TOPIC_BUBBLES->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Explore Stories by Heritage Craft & Guild',
                            'badge_text' => 'TOPIC ARCHIVES',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '32px',
                            'padding_bottom' => '32px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_feed',
                        'type' => BlockType::EDITORIAL_FEED_SIDEBAR->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Heritage Gazette Stories & In-Depth Chronicles',
                            'badge_text' => 'CURATED JOURNAL',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '48px',
                            'padding_bottom' => '48px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_shelf',
                        'type' => BlockType::SHOPPABLE_PRODUCT_SHELF->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'From Story to Hand: Acquire the Pieces Featured Above',
                            'subheadline' => 'Handcrafted in limited archival batches by the master guilds profiled in this edition.',
                            'badge_text' => 'AS FEATURED IN STORIES',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '56px',
                            'padding_bottom' => '56px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_artisan',
                        'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Crafted by Ustad Alimullah Dewan',
                            'subheadline' => 'Third-generation master weaver preserving the royal Jamdani pit-loom on the Shitalakshya river bank.',
                            'badge_text' => 'Royal Master Loom Guild • Sonargaon',
                            'alignment' => 'left',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '56px',
                            'padding_bottom' => '56px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_reels',
                        'type' => BlockType::VIDEO_REELS_SHOWCASE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Living Chronicles: Watch the Hands Behind the Craft',
                            'badge_text' => 'LIVING CINEMATICS',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '48px',
                            'padding_bottom' => '48px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_provenance',
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Geographical Indication & Regional Heritage',
                            'subheadline' => 'Every piece certified for genuine river basin origin and fair-trade honorarium.',
                            'badge_text' => 'GI Certified Bengal Craftsmanship',
                            'alignment' => 'center',
                            'bg_color' => '#F8F6F2',
                            'padding_top' => '56px',
                            'padding_bottom' => '56px',
                        ],
                    ],
                    [
                        'id' => 'sec_mag_newsletter',
                        'type' => BlockType::HERITAGE_CLUB_NEWSLETTER->value,
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Join the Heritage Gazette & Collector Circle',
                            'badge_text' => 'SOROLATA HERITAGE GUILD',
                            'subheadline' => 'Receive our monthly investigative craft essays, private notifications for limited loom harvests, and invitations to exhibitions.',
                            'primary_cta_text' => 'Subscribe to Gazette',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '48px',
                            'padding_bottom' => '48px',
                        ],
                    ],
                ],
            ],
        ]);

        // Default CMS Campaign Page (ID: 1)
        Page::firstOrCreate([
            'id' => 1,
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Heritage Craft Exhibition',
            'slug' => 'heritage-craft-exhibition',
            'page_type' => 'campaign',
            'status' => 'draft',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'campaign',
                'sections' => [
                    [
                        'id' => 'sec_exhibit_1',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'GI Certified Heritage Artifact',
                            'headline' => 'Living Traditions of Sonargaon',
                            'subheadline' => 'An archival voyage into centuries of Bengali master artisans.',
                            'primary_cta_text' => 'Explore Exhibition',
                            'alignment' => 'center',
                            'text_color' => '#1D1D1D',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'Heritage Craft Exhibition',
                'description' => 'Bengal Craft Exhibition',
            ],
        ]);

        // System Core: Homepage (slug: 'home')
        Page::updateOrCreate([
            'slug' => 'home',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Heritage Living Sanctuary (Homepage)',
            'page_type' => 'home',
            'status' => 'published',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'home',
                'sections' => [
                    [
                        'id' => 'sec_home_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'বেঙ্গল হেরিটেজ স্যাংকচুয়ারি • ভলিয়ম ০১',
                            'headline' => 'যেখানে প্রতিটি স্মৃতি বলে এক অনন্য ইতিহাস।',
                            'subheadline' => 'শতবর্ষের তাঁতশিল্প, কাঁসা-পিতলের নিখুঁত ভাস্কর্য এবং প্রাকৃতিক উপাদানের জাদু—সংরক্ষিত তাদের জন্য, যারা যান্ত্রিক উৎপাদনের চেয়ে মানুষের হাতের স্পর্শকে মূল্যবান মনে করেন।',
                            'primary_cta_text' => 'Explore Archival Pieces',
                            'primary_cta_url' => '/shop',
                            'secondary_cta_text' => 'Explore By District',
                            'secondary_cta_url' => '/districts',
                            'metric_1_val' => '100%',
                            'metric_1_lbl' => 'GI Provenance',
                            'metric_2_val' => '64',
                            'metric_2_lbl' => 'Bengal Districts',
                            'metric_3_val' => 'Direct',
                            'metric_3_lbl' => 'Artisan Royalty',
                            'image_url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200&auto=format&fit=crop',
                            'card_badge' => 'Featured Archival Piece',
                            'card_title' => 'Imperial Sonargaon Muslin Scarf',
                            'card_artisan' => 'Master Weaver Alimuddin',
                            'card_price' => '৳ 18,500',
                        ],
                    ],
                    [
                        'id' => 'sec_home_collections',
                        'type' => BlockType::FEATURED_COLLECTIONS->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'নির্বাচিত কালেকশনসমূহ',
                            'headline' => 'বিশেষ কালেকশন',
                            'subheadline' => 'Curated for Living Room, Dining, Bedroom & Festival Gifting',
                        ],
                    ],
                    [
                        'id' => 'sec_home_categories',
                        'type' => BlockType::CATEGORY_GRID->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'কারুশিল্প শাখা',
                            'headline' => 'ঐতিহ্যের শ্রেষ্ঠ কারুশিল্প সম্ভার',
                            'subheadline' => 'Explore centuries-old techniques perfected across generations of master craftsmen.',
                        ],
                    ],
                    [
                        'id' => 'sec_home_new_arrivals',
                        'type' => BlockType::PRODUCT_CAROUSEL->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'নতুন সংগ্রহ',
                            'headline' => 'নতুন আগমন',
                            'subheadline' => 'Touch-friendly horizontal scroll →',
                        ],
                    ],
                    [
                        'id' => 'sec_home_best_sellers',
                        'type' => BlockType::BEST_SELLERS->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'সংগ্রাহকদের প্রিয়',
                            'headline' => 'আমাদের বিশেষ সংকলন',
                            'primary_cta_text' => 'View Entire Catalog',
                        ],
                    ],
                    [
                        'id' => 'sec_home_editor_choice',
                        'type' => BlockType::EDITOR_CHOICE->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'কিউরেটর স্পেশাল',
                            'headline' => "এডিটরস চয়েস: টাঙ্গাইল পিওর মালবেরি সিল্ক জামদানি",
                            'subheadline' => 'অর্গানিক মালবেরি সিল্কে হাতে বোনা জ্যামিতিক মোটিফ। দেলদুয়ারের ওস্তাদ তাঁতি রাবেয়া বেগমের ছয় সপ্তাহের সাধনার ফসল।',
                            'primary_cta_text' => 'সংগ্রহ করুন →',
                            'price' => 24500,
                            'compare_price' => 29000,
                            'image_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000',
                        ],
                    ],
                    [
                        'id' => 'sec_home_manifesto',
                        'type' => BlockType::EDITORIAL_MANIFESTO->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'আমাদের ঐতিহ্য ঘোষণা',
                            'headline' => '"আমাদের কারুশিল্প কেবল কোনো পণ্য নয়; এটি আমাদের পূর্বপুরুষদের আত্মা, নিঃশ্বাস এবং বেঁচে থাকার লড়াইয়ের অমর গাথা। শিল্পের এই স্পর্শ আমরা হারিয়ে যেতে দেব না।"',
                            'subheadline' => 'কৃত্রিম ফাস্ট ফ্যাশন ও যান্ত্রিক প্রতিলিপির যুগে সরলতা বিলুপ্তপ্রায় ঐতিহ্যবাহী শিল্পের আশ্রয়স্থল। গ্রামীণ কারিগরদের ন্যায্য মূল্যায়ন নিশ্চিত করে আমরা বাংলার জীবন্ত ঐতিহ্যকে বাঁচিয়ে রাখি।',
                            'primary_cta_text' => 'সম্পূর্ণ ম্যানিফেস্টো পড়ুন →',
                            'primary_cta_url' => '/pages/about',
                        ],
                    ],
                    [
                        'id' => 'sec_home_districts',
                        'type' => BlockType::HERITAGE_PROVENANCE->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'ভৌগোলিক পরিচয়',
                            'headline' => 'বাংলার ৬৪ জেলার ভৌগোলিক ঐতিহ্য',
                            'secondary_cta_text' => 'সবগুলো জেলা দেখুন →',
                        ],
                    ],
                    [
                        'id' => 'sec_home_artisan',
                        'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'বংশানুক্রমিক ওস্তাদ',
                            'headline' => 'জীবন্ত কিংবদন্তি: ওস্তাদ কারিগরবৃন্দ',
                            'subheadline' => 'প্রতিটি শিল্পকর্মের পেছনে রয়েছে একজন শিল্পীর নাম, স্টুডিও এবং আজীবনের সাধনা।',
                        ],
                    ],
                    [
                        'id' => 'sec_home_trending',
                        'type' => BlockType::SHOPPABLE_PRODUCT_SHELF->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'তাজা শিল্পকর্ম',
                            'headline' => 'জনপ্রিয় ঐতিহ্যবাহী শিল্পকর্ম',
                            'subheadline' => 'Limited pieces ready for immediate international dispatch',
                        ],
                    ],
                    [
                        'id' => 'sec_home_testimonial',
                        'type' => BlockType::TESTIMONIAL_EDITORIAL->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'সংগ্রাহকদের মতামত',
                            'headline' => 'সংগ্রাহকদের অনুভূতি ও স্মৃতিচারণ',
                            'subheadline' => 'টেক্সটাইল গবেষক, ইন্টেরিয়র ডিজাইনার এবং বৈশ্বিক শিল্প সংগ্রাহকদের অকৃত্রিম প্রতিক্রিয়া।',
                        ],
                    ],
                    [
                        'id' => 'sec_home_gallery',
                        'type' => BlockType::INTERACTIVE_GALLERY->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'ভিজ্যুয়াল ক্রনিকল',
                            'headline' => '@SorolataHeritage',
                            'subheadline' => 'আমাদের তাঁতি ও কারিগর পল্লীর দৈনন্দিন জীবনের গল্প ও ছন্দ',
                        ],
                    ],
                    [
                        'id' => 'sec_home_newsletter',
                        'type' => BlockType::HERITAGE_CLUB_NEWSLETTER->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'সংগ্রাহক সার্কেল',
                            'headline' => 'সংগ্রাহক সার্কেলে যোগ দিন',
                            'subheadline' => 'Receive Intimate Archival Dispatches',
                            'primary_cta_text' => 'Join Collector Desk',
                        ],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'Sorolata — Handcrafted Bengali Heirlooms',
                'description' => 'Preserving ancestral loom lineages and master artisans.',
            ],
        ]);

        // System Core: About Us (slug: 'about')
        Page::firstOrCreate([
            'slug' => 'about',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'About Sorolata (Living Manifesto)',
            'page_type' => 'standard',
            'status' => 'published',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'standard',
                'sections' => [
                    [
                        'id' => 'sec_about_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'The Living Manifesto of Bengal',
                            'headline' => 'Reviving Ancient Looms, Elevating Living Masters',
                            'subheadline' => 'Sorolata is an archival sanctuary safeguarding traditional South Asian crafts.',
                            'primary_cta_text' => 'Explore Guild Artisans',
                            'primary_cta_url' => '/artisans',
                            'alignment' => 'center',
                            'text_color' => '#FFFFFF',
                            'bg_color' => '#214E34',
                            'padding_top' => '80px',
                            'padding_bottom' => '80px',
                        ],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'About Sorolata — The Living Manifesto',
                'description' => 'Our dedication to traditional South Asian craft, ethics, and master artisan preservation.',
            ],
        ]);

        // System Core: Contact (slug: 'contact')
        Page::firstOrCreate([
            'slug' => 'contact',
        ], [
            'uuid' => (string) Str::uuid(),
            'title' => 'Curator Concierge (Contact Us)',
            'page_type' => 'standard',
            'status' => 'published',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'standard',
                'sections' => [
                    [
                        'id' => 'sec_contact_hero',
                        'type' => BlockType::HERO_CINEMATIC->value,
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'Private Patron Concierge • Dhaka Atelier',
                            'headline' => 'Consult with Our Master Curators',
                            'subheadline' => 'Inquire about bespoke commissions, international insured courier dispatch, or private viewing appointments.',
                            'primary_cta_text' => 'Contact via WhatsApp',
                            'primary_cta_url' => 'https://wa.me/8801700000000',
                            'alignment' => 'center',
                            'text_color' => '#1D1D1D',
                            'bg_color' => '#FFFFFF',
                            'padding_top' => '64px',
                            'padding_bottom' => '64px',
                        ],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'Curator Concierge — Contact Sorolata',
                'description' => 'Connect with our master curation desk for bespoke commissions and inquiries.',
            ],
        ]);

        // Shajgoj Style Magazine Content-to-Commerce Page
        $shajgojTemplate = PageTemplate::where('name', 'Shajgoj Content-to-Commerce Magazine Homepage')->first();
        if ($shajgojTemplate) {
            Page::updateOrCreate([
                'slug' => 'shajgoj-magazine',
            ], [
                'uuid' => (string) Str::uuid(),
                'title' => 'Shajgoj Style Content-to-Commerce Magazine',
                'page_type' => 'home',
                'status' => 'published',
                'layout_json' => $shajgojTemplate->layout_json,
                'seo_meta' => [
                    'title' => 'Sorolata — Shajgoj Content-to-Commerce Gazette',
                    'description' => 'Where authentic artisan stories meet bespoke handcrafted commerce.',
                ],
            ]);
        }
    }
}
