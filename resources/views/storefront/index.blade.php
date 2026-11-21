@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $homePage->seo_meta['title'] ?? 'Sorolata — Handcrafted Heritage & Living Artisan Sanctuary' }}</title>
    <meta name="description" content="{{ $homePage->seo_meta['description'] ?? 'Discover rare, handmade, and GI-certified heritage artifacts from master Bangladeshi artisans. Pure craftsmanship, human provenance, and timeless luxury.' }}">
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

    <!-- SECTION 01: Master Editorial Hero -->
    @php
        $heroSettings = $builderSections['hero_cinematic']['settings'] ?? [];
    @endphp
    <section class="relative pt-12 pb-24 lg:pt-20 lg:pb-36 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Editorial Text Block -->
                <div class="lg:col-span-7 space-y-6">
                    <x-badge variant="primary" size="md">
                        <x-icon name="sparkles" class="w-3.5 h-3.5 text-[#C96A3D]" />
                        <span>{{ !empty($heroSettings['badge_text']) ? $heroSettings['badge_text'] : __('Bengal Artisan Sanctuary • Vol. 01') }}</span>
                    </x-badge>
                    
                    <h1 class="font-editorial-heading text-5xl sm:text-6xl lg:text-[72px] font-light text-[#1D1D1D] leading-[1.08] tracking-tight">
                        {{ !empty($heroSettings['headline']) ? $heroSettings['headline'] : __('Where Every Artifact Tells An Irreplaceable Story.') }}
                    </h1>

                    <p class="font-editorial-body text-lg sm:text-xl text-[#555555] font-light max-w-2xl leading-relaxed">
                        {{ !empty($heroSettings['subheadline']) ? $heroSettings['subheadline'] : __('Centuries of handloom mastery, bell-metal forging, and botanical alchemy—curated for those who treasure authentic human provenance over industrial automation.') }}
                    </p>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <x-button href="{{ $heroSettings['primary_cta_url'] ?? route('shop.index') }}" variant="primary" size="lg" iconRight="arrow-right">
                            {{ !empty($heroSettings['primary_cta_text']) ? $heroSettings['primary_cta_text'] : __('Explore Archival Pieces') }}
                        </x-button>
                        <x-button href="{{ $heroSettings['secondary_cta_url'] ?? route('districts.index') }}" variant="secondary" size="lg" icon="map-pin">
                            {{ !empty($heroSettings['secondary_cta_text']) ? $heroSettings['secondary_cta_text'] : __('Explore By District') }}
                        </x-button>
                    </div>

                    <!-- Trust Metrics Ribbon -->
                    <div class="pt-8 border-t border-[#E8E8E8] grid grid-cols-3 gap-6 text-xs text-[#555555]">
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">{{ $heroSettings['metric_1_val'] ?? '100%' }}</span>
                            <span class="text-[#888888]">{{ $heroSettings['metric_1_lbl'] ?? __('GI Provenance') }}</span>
                        </div>
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">{{ $heroSettings['metric_2_val'] ?? '64' }}</span>
                            <span class="text-[#888888]">{{ $heroSettings['metric_2_lbl'] ?? __('Bengal Districts') }}</span>
                        </div>
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">{{ $heroSettings['metric_3_val'] ?? 'Direct' }}</span>
                            <span class="text-[#888888]">{{ $heroSettings['metric_3_lbl'] ?? __('Artisan Royalty') }}</span>
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
                            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold block">{{ $heroSettings['card_badge'] ?? __('Featured Archival Piece') }}</span>
                            <h4 class="font-editorial-heading text-xl font-light">{{ $heroSettings['card_title'] ?? __('Imperial Sonargaon Muslin Scarf') }}</h4>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-[#E8E8E8]/70 text-xs">
                                <span class="text-[#888888]">{{ $heroSettings['card_artisan'] ?? __('Master Weaver Alimuddin') }}</span>
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
    @endphp
    <section id="featured-collections" class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ !empty($collSettings['badge_text']) ? $collSettings['badge_text'] : __('Curated Drops') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ !empty($collSettings['headline']) ? $collSettings['headline'] : __('Featured Collections') }}
                    </h2>
                </div>
                <p class="text-xs uppercase tracking-widest text-[#888888] mt-4 sm:mt-0 font-medium">
                    {{ !empty($collSettings['subheadline']) ? $collSettings['subheadline'] : __('Curated for Living Room, Dining, Bedroom & Festival Gifting') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <x-collection-card 
                    :title="__('The Royal Muslin & Jamdani Drop')"
                    :subtitle="__('300-count gossamer threads spun along the misty morning banks of the Shitalakshya River.')"
                    :image="'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000'"
                    :link="route('collections.show', 'royal-muslin')"
                    tag="Limited 50 Pieces"
                />
                <x-collection-card 
                    :title="__('Ancestral Bell-Metal & Kansa Tableware')"
                    :subtitle="__('Hammer-beaten pure acoustic bell-metal from Dhamrai artisan guilds.')"
                    :image="'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1000'"
                    :link="route('collections.show', 'dhamrai-bell-metal')"
                    tag="Generational Foundry"
                />
            </div>
        </div>
    </section>

    <!-- SECTION 03: Categories / Crafts -->
    @php
        $catSettings = $builderSections['category_grid']['settings'] ?? [];
    @endphp
    <section id="categories-grid" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ !empty($catSettings['badge_text']) ? $catSettings['badge_text'] : __('Guild Specialization') }}</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    {{ !empty($catSettings['headline']) ? $catSettings['headline'] : __('Shop by Traditional Guild') }}
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    {{ !empty($catSettings['subheadline']) ? $catSettings['subheadline'] : __('Explore centuries-old techniques perfected across generations of master craftsmen.') }}
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
    @endphp
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ !empty($newArrivalsSettings['badge_text']) ? $newArrivalsSettings['badge_text'] : __('Loom Fresh') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ !empty($newArrivalsSettings['headline']) ? $newArrivalsSettings['headline'] : __('New Arrivals') }}
                    </h2>
                </div>
                <div class="flex items-center gap-3 mt-4 sm:mt-0 text-xs text-[#888888]">
                    <span>{{ !empty($newArrivalsSettings['subheadline']) ? $newArrivalsSettings['subheadline'] : __('Touch-friendly horizontal scroll') }}</span>
                    <span>→</span>
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
    @endphp
    <section id="curated-collection" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ !empty($bestSellerSettings['badge_text']) ? $bestSellerSettings['badge_text'] : __('Collector Favorites') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ !empty($bestSellerSettings['headline']) ? $bestSellerSettings['headline'] : __('Archival Best Sellers') }}
                    </h2>
                </div>
                <div class="mt-4 sm:mt-0">
                    <x-button href="{{ route('shop.index') }}" variant="ghost" size="sm" iconRight="arrow-right">
                        {{ !empty($bestSellerSettings['primary_cta_text']) ? $bestSellerSettings['primary_cta_text'] : __('View Entire Catalog') }}
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
    @endphp
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="bg-[#F8F6F2] rounded-[32px] p-8 sm:p-14 border border-[#E8E8E8] grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ !empty($editorChoiceSettings['badge_text']) ? $editorChoiceSettings['badge_text'] : __('Curator Distinction') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] leading-tight">
                        {{ !empty($editorChoiceSettings['headline']) ? $editorChoiceSettings['headline'] : __("Editor's Choice: Tangail Pure Mulberry Silk Jamdani") }}
                    </h2>
                    <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                        {{ !empty($editorChoiceSettings['subheadline']) ? $editorChoiceSettings['subheadline'] : __('Hand-knotted supplementary weft geometric motifs on pure organic mulberry silk. Woven without paper stencils over six weeks by master weaver Rabeya Begum in Delduar.') }}
                    </p>
                    <div class="pt-2 flex items-center gap-6">
                        <x-button href="{{ $editorChoiceSettings['primary_cta_url'] ?? route('shop.index') }}" variant="primary" size="md">
                            {{ !empty($editorChoiceSettings['primary_cta_text']) ? $editorChoiceSettings['primary_cta_text'] : __("Acquire Editor's Selection →") }}
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
    @endphp
    <section id="story-manifesto" class="py-28 bg-[#214E34] text-[#F8F6F2] relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 lg:px-12 text-center space-y-8 relative z-10">
            <span class="text-xs uppercase tracking-widest text-[#DE7D4E] font-bold">{{ !empty($manifestoSettings['badge_text']) ? $manifestoSettings['badge_text'] : __('Our Heritage Manifesto') }}</span>
            
            <blockquote class="font-editorial-heading text-3xl sm:text-5xl lg:text-6xl font-light text-[#F8F6F2] leading-[1.15]">
                {{ !empty($manifestoSettings['headline']) ? $manifestoSettings['headline'] : __('"We do not mass produce. We do not compromise. We preserve the human breath in every thread, hammer strike, and brushstroke."') }}
            </blockquote>

            <p class="font-editorial-body text-base sm:text-lg text-[#F8F6F2]/80 max-w-2xl mx-auto leading-relaxed">
                {{ !empty($manifestoSettings['subheadline']) ? $manifestoSettings['subheadline'] : __("In an era dominated by synthetic fast-fashion and automated machine replicas, Sorolata provides sanctuary for endangered heritage arts. By ensuring fair, direct compensation to rural guilds, we keep Bengal's living cultural soul vibrant.") }}
            </p>

            <div class="pt-4">
                <x-button href="{{ $manifestoSettings['primary_cta_url'] ?? route('pages.about') }}" variant="terracotta" size="lg">
                    {{ !empty($manifestoSettings['primary_cta_text']) ? $manifestoSettings['primary_cta_text'] : __('Read Complete Manifesto →') }}
                </x-button>
            </div>
        </div>
    </section>

    <!-- SECTION 08: Shop by District -->
    @php
        $distSettings = $builderSections['heritage_provenance']['settings'] ?? [];
    @endphp
    <section id="districts-grid" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ !empty($distSettings['badge_text']) ? $distSettings['badge_text'] : __('Geographical Provenance') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ !empty($distSettings['headline']) ? $distSettings['headline'] : __('Shop by Heritage District') }}
                    </h2>
                </div>
                <a href="{{ route('districts.index') }}" class="text-xs uppercase tracking-widest text-[#214E34] font-bold hover:underline mt-4 sm:mt-0">
                    {{ !empty($distSettings['secondary_cta_text']) ? $distSettings['secondary_cta_text'] : __('View All 64 Districts →') }}
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
    @endphp
    <section id="artisan-section" class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ !empty($artisanSettings['badge_text']) ? $artisanSettings['badge_text'] : __('Generational Masters') }}</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    {{ !empty($artisanSettings['headline']) ? $artisanSettings['headline'] : __('The Hands Behind The Heirlooms') }}
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    {{ !empty($artisanSettings['subheadline']) ? $artisanSettings['subheadline'] : __('Every artisan has a name, a studio, and an unbroken lineage of dedication.') }}
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
    @endphp
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ !empty($shelfSettings['badge_text']) ? $shelfSettings['badge_text'] : __('Fresh from the Ateliers') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ !empty($shelfSettings['headline']) ? $shelfSettings['headline'] : __('Trending Releases') }}
                    </h2>
                </div>
                <p class="text-xs uppercase tracking-widest text-[#888888] mt-4 sm:mt-0 font-medium">
                    {{ !empty($shelfSettings['subheadline']) ? $shelfSettings['subheadline'] : __('Limited pieces ready for immediate international dispatch') }}
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
    @endphp
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ !empty($testimonialSettings['badge_text']) ? $testimonialSettings['badge_text'] : __('Collector Reviews') }}</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    {{ !empty($testimonialSettings['headline']) ? $testimonialSettings['headline'] : __('Cherished by Discerning Collectors') }}
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    {{ !empty($testimonialSettings['subheadline']) ? $testimonialSettings['subheadline'] : __('Read unedited reviews from textile historians, interior designers, and global expat patrons.') }}
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
    @endphp
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ !empty($gallerySettings['badge_text']) ? $gallerySettings['badge_text'] : __('Visual Chronicle') }}</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        {{ !empty($gallerySettings['headline']) ? $gallerySettings['headline'] : '@SorolataHeritage' }}
                    </h2>
                </div>
                <p class="text-xs text-[#888888] mt-4 sm:mt-0">
                    {{ !empty($gallerySettings['subheadline']) ? $gallerySettings['subheadline'] : __('Follow the daily rhythms of our rural weaving clusters') }}
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $instaShots = [
                        'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop',
                    ];
                @endphp

                @foreach($instaShots as $shot)
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
    @endphp
    <section class="py-24 bg-white border-t border-[#E8E8E8]/70">
        <div class="max-w-3xl mx-auto px-6 lg:px-12 text-center space-y-6">
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ !empty($newsSettings['badge_text']) ? $newsSettings['badge_text'] : __("The Collector's Circle") }}</span>
            
            <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D]">
                {{ !empty($newsSettings['headline']) ? $newsSettings['headline'] : __('Receive Intimate Archival Dispatches') }}
            </h2>

            <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                {{ !empty($newsSettings['subheadline']) ? $newsSettings['subheadline'] : __('Join our private circle of patrons. We dispatch thoughtful notes when a generational weaver prepares a rare loom, with exclusive early access to one-off pieces.') }}
            </p>

            <form class="pt-4 flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                <input 
                    type="email" 
                    placeholder="{{ __('Enter your email address...') }}" 
                    class="flex-grow px-6 py-4 rounded-full border border-[#E8E8E8] bg-[#F8F6F2] text-sm text-[#1D1D1D] placeholder-[#888888] focus:outline-none focus:border-[#214E34] focus:ring-2 focus:ring-[#214E34]/10 transition-colors"
                    required
                >
                <x-button type="submit" variant="primary" size="md" class="whitespace-nowrap px-8">
                    {{ !empty($newsSettings['primary_cta_text']) ? $newsSettings['primary_cta_text'] : __('Join Collector Desk') }}
                </x-button>
            </form>
            <p class="text-[11px] text-[#888888]">{{ __('We honor your privacy. Unsubscribe at any moment with a single click.') }}</p>
        </div>
    </section>

@endsection
