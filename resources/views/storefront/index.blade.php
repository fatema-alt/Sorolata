@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $homePage?->seo_meta['title'] ?? 'Sorolata — Handcrafted Heritage & Living Artisan Sanctuary' }}</title>
    <meta name="description" content="{{ $homePage?->seo_meta['description'] ?? 'Discover rare, handmade, and GI-certified heritage artifacts from master Bangladeshi artisans. Pure craftsmanship, human provenance, and timeless luxury.' }}">
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

    @php
        $isBn = app()->getLocale() === 'bn';
        $getLoc = function(array $settings, string $key, string $defaultEn = '', string $defaultBn = '') use ($isBn) {
            if ($isBn) {
                if (!empty($settings[$key . '_bn'])) {
                    return $settings[$key . '_bn'];
                }
                if (!empty($settings[$key]) && preg_match('/[\x{0980}-\x{09FF}]/u', (string)$settings[$key])) {
                    return $settings[$key];
                }
                return !empty($defaultBn) ? $defaultBn : ($settings[$key] ?? $defaultEn);
            } else {
                if (!empty($settings[$key]) && !preg_match('/[\x{0980}-\x{09FF}]/u', (string)$settings[$key])) {
                    return $settings[$key];
                }
                return !empty($defaultEn) ? $defaultEn : ($settings[$key] ?? $defaultBn);
            }
        };
    @endphp

    <!-- SECTION 01: Master Editorial Hero -->
    @php
        $heroSettings = $builderSections['hero_cinematic']['settings'] ?? [];
        $heroBadge = $getLoc($heroSettings, 'badge_text', 'BENGAL HERITAGE LIVING SANCTUARY • VOL 01', 'বেঙ্গল হেরিটেজ স্যাংকচুয়ারি • ভলিয়ম ০১');
        $heroHeadline = $getLoc($heroSettings, 'headline', 'Where Every Memory Recalls a Timeless History.', 'যেখানে প্রতিটি স্মৃতি বলে এক অনন্য ইতিহাস।');
        $heroSubheadline = $getLoc($heroSettings, 'subheadline', 'Centuries of master weaving, bell-metal forging, and botanical alchemy—curated for those who cherish authentic human touch over mechanical assembly.', 'শতবর্ষের তাঁতশিল্প, কাঁসা-পিতলের নিখুঁত ভাস্কর্য এবং প্রাকৃতিক উপাদানের জাদু—সংরক্ষিত তাদের জন্য, যারা যান্ত্রিক উৎপাদনের চেয়ে মানুষের হাতের স্পর্শকে মূল্যবান মনে করেন।');
        $heroPrimaryCta = $getLoc($heroSettings, 'primary_cta_text', 'Explore Archival Pieces', 'সংগ্রহশালা দেখুন');
        $heroSecondaryCta = $getLoc($heroSettings, 'secondary_cta_text', 'Explore By District', 'জেলা ভিত্তিক অন্বেষণ');
        $heroM1Lbl = $getLoc($heroSettings, 'metric_1_lbl', 'GI Provenance', 'জিআই সনদপ্রাপ্ত');
        $heroM2Lbl = $getLoc($heroSettings, 'metric_2_lbl', 'Bengal Districts', 'ঐতিহ্যবাহী জেলা');
        $heroM3Lbl = $getLoc($heroSettings, 'metric_3_lbl', 'Artisan Royalty', 'কারিগরদের ন্যায্যসম্মান');
        $heroCardBadge = $getLoc($heroSettings, 'card_badge', 'Featured Archival Piece', 'বিশেষ শিল্পকর্ম');
        $heroCardTitle = $getLoc($heroSettings, 'card_title', 'Imperial Sonargaon Muslin Scarf', 'সোনারগাঁয়ের শাহী মসলিন স্কার্ফ');
        $heroCardArtisan = $getLoc($heroSettings, 'card_artisan', 'Master Weaver Alimuddin', 'ওস্তাদ তাঁতি আলিমুদ্দিন');
    @endphp
    <section class="relative pt-12 pb-24 lg:pt-20 lg:pb-36 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Editorial Text Block -->
                <div class="lg:col-span-7 space-y-6">
                    <x-badge variant="primary" size="md">
                        <x-icon name="sparkles" class="w-3.5 h-3.5 text-[#C96A3D]" />
                        <span>{{ $heroBadge }}</span>
                    </x-badge>
                    
                    <h1 class="font-editorial-heading text-5xl sm:text-6xl lg:text-[72px] font-light text-[#1D1D1D] leading-[1.08] tracking-tight">
                        {{ $heroHeadline }}
                    </h1>

                    <p class="font-editorial-body text-lg sm:text-xl text-[#555555] font-light max-w-2xl leading-relaxed">
                        {{ $heroSubheadline }}
                    </p>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <x-button href="{{ $heroSettings['primary_cta_url'] ?? route('shop.index') }}" variant="primary" size="lg" iconRight="arrow-right">
                            {{ $heroPrimaryCta }}
                        </x-button>
                        <x-button href="{{ $heroSettings['secondary_cta_url'] ?? route('districts.index') }}" variant="secondary" size="lg" icon="map-pin">
                            {{ $heroSecondaryCta }}
                        </x-button>
                    </div>

                    <!-- Trust Metrics Ribbon -->
                    <div class="pt-8 border-t border-[#E8E8E8] grid grid-cols-3 gap-6 text-xs text-[#555555]">
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">{{ $heroSettings['metric_1_val'] ?? '100%' }}</span>
                            <span class="text-[#888888]">{{ $heroM1Lbl }}</span>
                        </div>
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">{{ $heroSettings['metric_2_val'] ?? '64' }}</span>
                            <span class="text-[#888888]">{{ $heroM2Lbl }}</span>
                        </div>
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">{{ $heroSettings['metric_3_val'] ?? 'Direct' }}</span>
                            <span class="text-[#888888]">{{ $heroM3Lbl }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Visual Stage -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-[32px] overflow-hidden bg-[#F8F6F2] shadow-soft-lg aspect-[4/5] hover-lift">
                        <img 
                            src="{{ !empty($heroSettings['image_url']) ? $heroSettings['image_url'] : 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200&auto=format&fit=crop' }}" 
                            alt="Master Handloom Sonargaon Muslin" 
                            class="w-full h-full object-cover object-center"
                            loading="eager"
                            fetchpriority="high"
                        >
                        <div class="absolute bottom-6 left-6 right-6 sorolata-glass rounded-[24px] p-4 text-[#1D1D1D] shadow-soft-md">
                            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold block">{{ $heroCardBadge }}</span>
                            <h4 class="font-editorial-heading text-xl font-light">{{ $heroCardTitle }}</h4>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-[#E8E8E8]/70 text-xs">
                                <span class="text-[#888888]">{{ $heroCardArtisan }}</span>
                                <span class="font-bold text-[#214E34]">{{ $heroSettings['card_price'] ?? '৳ 18,500' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 02: Featured Collections -->
    @php
        $collSettings = $builderSections['featured_collections']['settings'] ?? [];
        $collBadge = $getLoc($collSettings, 'badge_text', 'Curated Drops', 'নির্বাচিত কালেকশনসমূহ');
        $collHeadline = $getLoc($collSettings, 'headline', 'Featured Collections', 'বিশেষ কালেকশন');
        $collSubheadline = $getLoc($collSettings, 'subheadline', 'Curated for Living Room, Dining, Bedroom & Festival Gifting', 'লিভিং রুম, ডাইনিং, বেডরুম ও উৎসবের উপহারের জন্য বিশেষভাবে কিউরেটেড');
    @endphp
    <section id="featured-collections" class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ $collBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ $collHeadline }}
                    </h2>
                </div>
                <p class="text-xs uppercase tracking-widest text-[#888888] mt-4 sm:mt-0 font-medium">
                    {{ $collSubheadline }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <x-collection-card 
                    :title="__('The Royal Muslin & Jamdani Drop')"
                    :subtitle="__('300-count gossamer threads spun along the misty morning banks of the Shitalakshya River.')"
                    :imageUrl="'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000'"
                    :image="'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000'"
                    :link="route('collection.show', 'royal-muslin')"
                    tag="Limited 50 Pieces"
                />
                <x-collection-card 
                    :title="__('Ancestral Bell-Metal & Kansa Tableware')"
                    :subtitle="__('Hammer-beaten pure acoustic bell-metal from Dhamrai artisan guilds.')"
                    :imageUrl="'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1000'"
                    :image="'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1000'"
                    :link="route('collection.show', 'dhamrai-bell-metal')"
                    tag="Generational Foundry"
                />
            </div>
        </div>
    </section>

    <!-- SECTION 03: Categories / Crafts -->
    @php
        $catSettings = $builderSections['category_grid']['settings'] ?? [];
        $catBadge = $getLoc($catSettings, 'badge_text', 'Heritage Craft Guilds', 'কারুশিল্প শাখা');
        $catHeadline = $getLoc($catSettings, 'headline', 'Timeless Guilds of Bengal Craftsmanship', 'ঐতিহ্যের শ্রেষ্ঠ কারুশিল্প সম্ভার');
        $catSubheadline = $getLoc($catSettings, 'subheadline', 'Explore centuries-old techniques perfected across generations of master craftsmen.', 'প্রজন্ম থেকে প্রজন্মে সিদ্ধহস্ত কারিগরদের শতবর্ষী ঐতিহ্যের সন্ধান।');
    @endphp
    <section id="categories-grid" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ $catBadge }}</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    {{ $catHeadline }}
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    {{ $catSubheadline }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 04: New Arrivals (Horizontal Slider / Carousel) -->
    @php
        $newArrivalsSettings = $builderSections['product_carousel']['settings'] ?? [];
        $newBadge = $getLoc($newArrivalsSettings, 'badge_text', 'Fresh From Ateliers', 'নতুন সংগ্রহ');
        $newHeadline = $getLoc($newArrivalsSettings, 'headline', 'New Arrivals & Fresh Weaves', 'নতুন আগমন');
        $newSubheadline = $getLoc($newArrivalsSettings, 'subheadline', 'Touch-friendly horizontal scroll →', 'হরিজন্টাল স্ক্রোল করে দেখুন →');
    @endphp
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ $newBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ $newHeadline }}
                    </h2>
                </div>
                <div class="flex items-center gap-3 mt-4 sm:mt-0 text-xs text-[#888888]">
                    <span>{{ $newSubheadline }}</span>
                </div>
            </div>

            <!-- Horizontal Carousel -->
            <div class="flex gap-6 overflow-x-auto pb-6 scrollbar-none snap-x snap-mandatory">
                @foreach($products as $product)
                    <div class="w-80 sm:w-96 flex-shrink-0 snap-start">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 05: Best Seller -->
    @php
        $bestSellerSettings = $builderSections['best_sellers']['settings'] ?? [];
        $bestBadge = $getLoc($bestSellerSettings, 'badge_text', 'Collector Favorites', 'সংগ্রাহকদের প্রিয়');
        $bestHeadline = $getLoc($bestSellerSettings, 'headline', 'Archival Best Sellers & Acclaimed Masterpieces', 'আমাদের বিশেষ সংকলন');
        $bestCta = $getLoc($bestSellerSettings, 'primary_cta_text', 'View Entire Catalog', 'সম্পূর্ণ ক্যাটালগ দেখুন');
    @endphp
    <section id="curated-collection" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ $bestBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ $bestHeadline }}
                    </h2>
                </div>
                <div class="mt-4 sm:mt-0">
                    <x-button href="{{ route('shop.index') }}" variant="ghost" size="sm" iconRight="arrow-right">
                        {{ $bestCta }}
                    </x-button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($bestSellers as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 06: Editor's Choice -->
    @php
        $editorChoiceSettings = $builderSections['editor_choice']['settings'] ?? [];
        $editorBadge = $getLoc($editorChoiceSettings, 'badge_text', 'Curator Distinction', 'কিউরেটর স্পেশাল');
        $editorHeadline = $getLoc($editorChoiceSettings, 'headline', "Editor's Choice: Heirloom Tangail Mulberry Silk Jamdani", 'এডিটরস চয়েস: টাঙ্গাইল পিওর মালবেরি সিল্ক জামদানি');
        $editorSubheadline = $getLoc($editorChoiceSettings, 'subheadline', 'Hand-knotted supplementary weft geometric motifs on pure organic mulberry silk. Woven without paper stencils over six weeks by master weaver Rabeya Begum in Delduar.', 'অর্গানিক মালবেরি সিল্কে হাতে বোনা জ্যামিতিক মোটিফ। দেলদুয়ারের ওস্তাদ তাঁতি রাবেয়া বেগমের ছয় সপ্তাহের সাধনার ফসল।');
        $editorCta = $getLoc($editorChoiceSettings, 'primary_cta_text', "Acquire Heirloom Piece →", 'সংগ্রহ করুন →');
    @endphp
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="bg-[#F8F6F2] rounded-[32px] p-8 sm:p-14 border border-[#E8E8E8] grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ $editorBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] leading-tight">
                        {{ $editorHeadline }}
                    </h2>
                    <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                        {{ $editorSubheadline }}
                    </p>
                    <div class="pt-2 flex items-center gap-6">
                        <x-button href="{{ $editorChoiceSettings['primary_cta_url'] ?? route('shop.index') }}" variant="primary" size="md">
                            {{ $editorCta }}
                        </x-button>
                        <x-price :price="$editorChoiceSettings['price'] ?? 24500" :compareAtPrice="$editorChoiceSettings['compare_price'] ?? 29000" size="lg" />
                    </div>
                </div>
                <div class="lg:col-span-6">
                    <div class="rounded-[28px] overflow-hidden aspect-[4/3] shadow-soft-md bg-white">
                        <img src="{{ !empty($editorChoiceSettings['image_url']) ? $editorChoiceSettings['image_url'] : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000' }}" alt="Editor's Choice Jamdani" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 07: Story Banner (Editorial Manifesto) -->
    @php
        $manifestoSettings = $builderSections['editorial_manifesto']['settings'] ?? [];
        $manBadge = $getLoc($manifestoSettings, 'badge_text', 'Our Heritage Manifesto', 'আমাদের ঐতিহ্য ঘোষণা');
        $manHeadline = $getLoc($manifestoSettings, 'headline', '"Our craftsmanship is not merely merchandise; it is the soul, breath, and eternal resilience of our ancestors. We shall never let the sacred human touch fade away."', '"আমাদের কারুশিল্প কেবল কোনো পণ্য নয়; এটি আমাদের পূর্বপুরুষদের আত্মা, নিঃশ্বাস এবং বেঁচে থাকার লড়াইয়ের অমর গাথা। শিল্পের এই স্পর্শ আমরা হারিয়ে যেতে দেব না।"');
        $manSubheadline = $getLoc($manifestoSettings, 'subheadline', "In an era dominated by synthetic fast-fashion and automated machine replicas, Sorolata provides sanctuary for endangered heritage arts. By ensuring fair, direct compensation to rural guilds, we keep Bengal's living cultural soul vibrant.", 'কৃত্রিম ফাস্ট ফ্যাশন ও যান্ত্রিক প্রতিলিপির যুগে সরলতা বিলুপ্তপ্রায় ঐতিহ্যবাহী শিল্পের আশ্রয়স্থল। গ্রামীণ কারিগরদের ন্যায্য মূল্যায়ন নিশ্চিত করে আমরা বাংলার জীবন্ত ঐতিহ্যকে বাঁচিয়ে রাখি।');
        $manCta = $getLoc($manifestoSettings, 'primary_cta_text', 'Read Complete Manifesto →', 'সম্পূর্ণ ম্যানিফেস্টো পড়ুন →');
    @endphp
    <section id="story-manifesto" class="py-28 bg-[#214E34] text-[#F8F6F2] relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 lg:px-12 text-center space-y-8 relative z-10">
            <span class="text-xs uppercase tracking-widest text-[#DE7D4E] font-bold">{{ $manBadge }}</span>
            
            <blockquote class="font-editorial-heading text-3xl sm:text-5xl lg:text-6xl font-light text-[#F8F6F2] leading-[1.15]">
                {{ $manHeadline }}
            </blockquote>

            <p class="font-editorial-body text-base sm:text-lg text-[#F8F6F2]/80 max-w-2xl mx-auto leading-relaxed">
                {{ $manSubheadline }}
            </p>

            <div class="pt-4">
                <x-button href="{{ $manifestoSettings['primary_cta_url'] ?? route('pages.about') }}" variant="terracotta" size="lg">
                    {{ $manCta }}
                </x-button>
            </div>
        </div>
    </section>

    <!-- SECTION 08: Shop by District -->
    @php
        $distSettings = $builderSections['heritage_provenance']['settings'] ?? [];
        $distBadge = $getLoc($distSettings, 'badge_text', 'Geographical Provenance', 'ভৌগোলিক পরিচয়');
        $distHeadline = $getLoc($distSettings, 'headline', 'Shop by Heritage District', 'বাংলার ৬৪ জেলার ভৌগোলিক ঐতিহ্য');
        $distCta = $getLoc($distSettings, 'secondary_cta_text', 'View All 64 Districts →', 'সবগুলো জেলা দেখুন →');
    @endphp
    <section id="districts-grid" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ $distBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ $distHeadline }}
                    </h2>
                </div>
                <a href="{{ route('districts.index') }}" class="text-xs uppercase tracking-widest text-[#214E34] font-bold hover:underline mt-4 sm:mt-0">
                    {{ $distCta }}
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($districts as $district)
                    <a href="{{ route('district.show', $district->slug ?? $district->uuid) }}" class="block">
                        <x-district-card :district="$district" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 09: Meet The Artisan -->
    @php
        $artisanSettings = $builderSections['artisan_spotlight']['settings'] ?? [];
        $artBadge = $getLoc($artisanSettings, 'badge_text', 'Generational Masters', 'বংশানুক্রমিক ওস্তাদ');
        $artHeadline = $getLoc($artisanSettings, 'headline', 'The Hands Behind The Heirlooms', 'জীবন্ত কিংবদন্তি: ওস্তাদ কারিগরবৃন্দ');
        $artSubheadline = $getLoc($artisanSettings, 'subheadline', 'Every artisan has a name, a studio, and an unbroken lineage of dedication.', 'প্রতিটি শিল্পকর্মের পেছনে রয়েছে একজন শিল্পীর নাম, স্টুডিও এবং আজীবনের সাধনা।');
    @endphp
    <section id="artisan-section" class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ $artBadge }}</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    {{ $artHeadline }}
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    {{ $artSubheadline }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($artisans as $artisan)
                    <div class="bg-[#F8F6F2] rounded-[32px] p-6 shadow-soft-xs hover:shadow-soft-hover transition-luxury flex flex-col justify-between space-y-4">
                        <div class="aspect-square rounded-[24px] overflow-hidden bg-[#E8E8E8]">
                            <img 
                                src="{{ $artisan->portrait_url }}" 
                                alt="{{ $artisan->getLocalized('full_name') }}" 
                                class="w-full h-full object-cover grayscale contrast-125 hover:grayscale-0 transition-all duration-700"
                                loading="lazy"
                            >
                        </div>

                        <div class="space-y-1">
                            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">
                                {{ $artisan->craft_type->title() }}
                            </span>
                            <h4 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                                {{ $artisan->getLocalized('full_name') }}
                            </h4>
                            <p class="text-xs text-[#888888]">
                                {{ $artisan->getLocalized('atelier_name') }} • {{ $artisan->district?->getLocalized('name') }}
                            </p>
                        </div>

                        <p class="text-xs text-[#555555] line-clamp-2 italic font-editorial-heading text-sm">
                            "{{ $artisan->getLocalized('quote') }}"
                        </p>

                        <div class="pt-2 border-t border-[#E8E8E8] flex items-center justify-between text-xs">
                            <span class="text-[#888888]">{{ __(':count Years Mastery', ['count' => $artisan->years_of_mastery]) }}</span>
                            <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" class="text-[#214E34] font-semibold hover:underline">{{ __('Profile →') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 10: Trending Products -->
    @php
        $shelfSettings = $builderSections['shoppable_product_shelf']['settings'] ?? [];
        $shelfBadge = $getLoc($shelfSettings, 'badge_text', 'Fresh from the Ateliers', 'তাজা শিল্পকর্ম');
        $shelfHeadline = $getLoc($shelfSettings, 'headline', 'Trending Releases', 'জনপ্রিয় ঐতিহ্যবাহী শিল্পকর্ম');
        $shelfSubheadline = $getLoc($shelfSettings, 'subheadline', 'Limited pieces ready for immediate international dispatch', 'সীমিত সংস্করণের সংরক্ষিত শিল্পকর্ম, আন্তর্জাতিক ডেলিভারির জন্য প্রস্তুত');
    @endphp
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ $shelfBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ $shelfHeadline }}
                    </h2>
                </div>
                <p class="text-xs uppercase tracking-widest text-[#888888] mt-4 sm:mt-0 font-medium">
                    {{ $shelfSubheadline }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($trendingProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 11: Customer Reviews -->
    @php
        $testimonialSettings = $builderSections['testimonial_editorial']['settings'] ?? [];
        $testBadge = $getLoc($testimonialSettings, 'badge_text', 'Collector Reviews', 'সংগ্রাহকদের মতামত');
        $testHeadline = $getLoc($testimonialSettings, 'headline', 'Cherished by Discerning Collectors', 'সংগ্রাহকদের অনুভূতি ও স্মৃতিচারণ');
        $testSubheadline = $getLoc($testimonialSettings, 'subheadline', 'Read unedited reviews from textile historians, interior designers, and global expat patrons.', 'টেক্সটাইল গবেষক, ইন্টেরিয়র ডিজাইনার এবং বৈশ্বিক শিল্প সংগ্রাহকদের অকৃত্রিম প্রতিক্রিয়া।');
    @endphp
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ $testBadge }}</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    {{ $testHeadline }}
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    {{ $testSubheadline }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-testimonial-card 
                    :quote="__('Unboxing the Sonargaon Muslin scarf in London was breathtaking. The signed provenance certificate and the unrefined botanical ecru texture is simply incomparable.')"
                    :author="__('Dr. Eleanor Vance')"
                    :location="__('Textile Historian • Kensington, London')"
                />
                <x-testimonial-card 
                    :quote="__('The hand-chiseled Bogura brass kettle serves as the crown jewel of our dining room. It holds heat with incredible acoustic resonance.')"
                    :author="__('Tariqul Rahman')"
                    :location="__('Architect & Expat Collector • Toronto, Canada')"
                />
                <x-testimonial-card 
                    :quote="__('The Tangail Mulberry Jamdani is a museum-grade textile. Knowing the exact weaver who crafted it makes it our family\'s most valued heirloom.')"
                    :author="__('Farhana Chowdhury')"
                    :location="__('Art Collector • Gulshan, Dhaka')"
                />
            </div>
        </div>
    </section>

    <!-- SECTION 12: Instagram Feed -->
    @php
        $gallerySettings = $builderSections['interactive_gallery']['settings'] ?? [];
        $galBadge = $getLoc($gallerySettings, 'badge_text', 'Visual Chronicle', 'ভিজ্যুয়াল ক্রনিকল');
        $galHeadline = $getLoc($gallerySettings, 'headline', '@SorolataHeritage', '@SorolataHeritage ভিজ্যুয়াল জার্নাল');
        $galSubheadline = $getLoc($gallerySettings, 'subheadline', 'Follow the daily rhythms of our rural weaving clusters', 'আমাদের তাঁতি ও কারিগর পল্লীর দৈনন্দিন জীবনের গল্প ও ছন্দ');
    @endphp
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ $galBadge }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ $galHeadline }}
                    </h2>
                </div>
                <p class="text-xs text-[#888888] mt-4 sm:mt-0">
                    {{ $galSubheadline }}
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $instaShots = $instaShots ?? [
                        'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop',
                    ];
                @endphp

                @foreach(($instaShots ?? []) as $shot)
                    <div class="group relative rounded-[24px] overflow-hidden aspect-square bg-[#E8E8E8] shadow-soft-xs hover:shadow-soft-md transition-luxury">
                        <img 
                            src="{{ $shot }}" 
                            alt="{{ __('Atelier Life in Bengal') }}" 
                            class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white">
                            <x-icon name="sparkles" class="w-6 h-6 text-[#DE7D4E]" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 13: Newsletter -->
    @php
        $newsSettings = $builderSections['heritage_club_newsletter']['settings'] ?? [];
        $newsBadge = $getLoc($newsSettings, 'badge_text', "The Collector's Circle", 'সংগ্রাহক সার্কেল');
        $newsHeadline = $getLoc($newsSettings, 'headline', 'Receive Intimate Archival Dispatches', 'সংগ্রাহক সার্কেলে যোগ দিন');
        $newsSubheadline = $getLoc($newsSettings, 'subheadline', 'Join our private circle of patrons. We dispatch thoughtful notes when a generational weaver prepares a rare loom, with exclusive early access to one-off pieces.', 'নতুন সংগ্রহশালা উন্মোচন ও সীমিত সংস্করণের শিল্পকর্মের আগাম তথ্য পান সরাসরি।');
        $newsCta = $getLoc($newsSettings, 'primary_cta_text', 'Join Collector Desk', 'যুক্ত হন');
    @endphp
    <section class="py-24 bg-white border-t border-[#E8E8E8]/70">
        <div class="max-w-3xl mx-auto px-6 lg:px-12 text-center space-y-6">
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ $newsBadge }}</span>
            
            <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D]">
                {{ $newsHeadline }}
            </h2>

            <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                {{ $newsSubheadline }}
            </p>

            <form class="pt-4 flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                <input 
                    type="email" 
                    placeholder="{{ __('Enter your email address...') }}" 
                    class="flex-grow px-6 py-4 rounded-full border border-[#E8E8E8] bg-[#F8F6F2] text-sm text-[#1D1D1D] placeholder-[#888888] focus:outline-none focus:border-[#214E34] focus:ring-2 focus:ring-[#214E34]/10 transition-colors"
                    required
                >
                <x-button type="submit" variant="primary" size="md" class="whitespace-nowrap px-8">
                    {{ $newsCta }}
                </x-button>
            </form>
            <p class="text-[11px] text-[#888888]">{{ __('We honor your privacy. Unsubscribe at any moment with a single click.') }}</p>
        </div>
    </section>
            <p class="text-[11px] text-[#888888]">{{ __('We honor your privacy. Unsubscribe at any moment with a single click.') }}</p>
        </div>
    </section>

@endsection
