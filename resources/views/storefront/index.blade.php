@extends('layouts.storefront')

@section('seo_meta')
    <title>Sorolata — Handcrafted Heritage & Living Artisan Sanctuary</title>
    <meta name="description" content="Discover rare, handmade, and GI-certified heritage artifacts from master Bangladeshi artisans. Pure craftsmanship, human provenance, and timeless luxury.">
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

    <!-- SECTION 03: Master Editorial Hero -->
    <section class="relative pt-12 pb-24 lg:pt-20 lg:pb-36 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Editorial Text Block -->
                <div class="lg:col-span-7 space-y-6">
                    <x-badge variant="primary" size="md">
                        <x-icon name="sparkles" class="w-3.5 h-3.5 text-[#C96A3D]" />
                        <span>Bengal Artisan Sanctuary • Vol. 01</span>
                    </x-badge>
                    
                    <h1 class="font-editorial-heading text-5xl sm:text-6xl lg:text-[72px] font-light text-[#1D1D1D] leading-[1.08] tracking-tight">
                        Where Every Artifact Tells An Irreplaceable Story.
                    </h1>

                    <p class="font-editorial-body text-lg sm:text-xl text-[#555555] font-light max-w-2xl leading-relaxed">
                        Centuries of handloom mastery, bell-metal forging, and botanical alchemy—curated for those who treasure authentic human provenance over industrial automation.
                    </p>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <x-button href="{{ route('shop.index') }}" variant="primary" size="lg" iconRight="arrow-right">
                            Explore Archival Pieces
                        </x-button>
                        <x-button href="{{ route('districts.index') }}" variant="secondary" size="lg" icon="map-pin">
                            Explore By District
                        </x-button>
                    </div>

                    <!-- Trust Metrics Ribbon -->
                    <div class="pt-8 border-t border-[#E8E8E8] grid grid-cols-3 gap-6 text-xs text-[#555555]">
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">100%</span>
                            <span class="text-[#888888]">GI Provenance</span>
                        </div>
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">64</span>
                            <span class="text-[#888888]">Bengal Districts</span>
                        </div>
                        <div>
                            <span class="block font-editorial-heading text-2xl font-normal text-[#1D1D1D]">Direct</span>
                            <span class="text-[#888888]">Artisan Royalty</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Visual Stage -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-[32px] overflow-hidden bg-[#F8F6F2] shadow-soft-lg aspect-[4/5] hover-lift">
                        <img 
                            src="https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200&auto=format&fit=crop" 
                            alt="Master Handloom Sonargaon Muslin" 
                            class="w-full h-full object-cover object-center"
                            loading="eager"
                            fetchpriority="high"
                        >
                        <div class="absolute bottom-6 left-6 right-6 sorolata-glass rounded-[24px] p-4 text-[#1D1D1D] shadow-soft-md">
                            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold block">Featured Archival Piece</span>
                            <h4 class="font-editorial-heading text-xl font-light">Imperial Sonargaon Muslin Scarf</h4>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-[#E8E8E8]/70 text-xs">
                                <span class="text-[#888888]">Master Weaver Alimuddin</span>
                                <x-price price="18500" size="sm" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 04: Featured Collections -->
    <section id="featured-collections" class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">Curated Drops</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        Featured Collections
                    </h2>
                </div>
                <p class="text-xs uppercase tracking-widest text-[#888888] mt-4 sm:mt-0 font-medium">
                    Curated for Living Room, Dining, Bedroom & Festival Gifting
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <x-collection-card 
                    title="The Royal Muslin & Jamdani Drop"
                    subtitle="300-count gossamer threads spun along the misty morning banks of the Shitalakshya River."
                    imageUrl="https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1200&auto=format&fit=crop"
                    tag="Drop 01 • Sonargaon & Tangail"
                />
                <x-collection-card 
                    title="Ancestral Bell-Metal & Kansa Tableware"
                    subtitle="Hand-chiseled bronze alloys cast with ancient Mahasthangarh methods in Bogura."
                    imageUrl="https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1200&auto=format&fit=crop"
                    tag="Drop 02 • Bogura Foundries"
                />
            </div>
        </div>
    </section>

    <!-- SECTION 05: Shop by Category -->
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Craft Taxonomies</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    Shop by Traditional Guild
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    Explore centuries-old techniques perfected across generations of master craftsmen.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 06: New Arrivals (Horizontal Slider / Carousel) -->
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Loom Fresh</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        New Arrivals
                    </h2>
                </div>
                <div class="flex items-center gap-3 mt-4 sm:mt-0 text-xs text-[#888888]">
                    <span>Touch-friendly horizontal scroll</span>
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

    <!-- SECTION 07: Best Seller -->
    <section id="curated-collection" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">Collector Favorites</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        Archival Best Sellers
                    </h2>
                </div>
                <div class="mt-4 sm:mt-0">
                    <x-button href="{{ route('shop.index') }}" variant="ghost" size="sm" iconRight="arrow-right">
                        View Entire Catalog
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

    <!-- SECTION 08: Editor's Choice -->
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="bg-[#F8F6F2] rounded-[32px] p-8 sm:p-14 border border-[#E8E8E8] grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Curator Distinction</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] leading-tight">
                        Editor's Choice: Tangail Pure Mulberry Silk Jamdani
                    </h2>
                    <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                        Hand-knotted supplementary weft geometric motifs on pure organic mulberry silk. Woven without paper stencils over six weeks by master weaver Rabeya Begum in Delduar.
                    </p>
                    <div class="pt-2 flex items-center gap-6">
                        <x-button href="{{ route('shop.index') }}" variant="primary" size="md">
                            Acquire Editor's Selection →
                        </x-button>
                        <x-price price="24500" compareAtPrice="29000" size="lg" />
                    </div>
                </div>
                <div class="lg:col-span-6">
                    <div class="rounded-[28px] overflow-hidden aspect-[4/3] shadow-soft-md bg-white">
                        <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000" alt="Editor's Choice Jamdani" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 09: Story Banner (Editorial Manifesto) -->
    <section id="story-manifesto" class="py-28 bg-[#214E34] text-[#F8F6F2] relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 lg:px-12 text-center space-y-8 relative z-10">
            <span class="text-xs uppercase tracking-widest text-[#DE7D4E] font-bold">Our Heritage Manifesto</span>
            
            <blockquote class="font-editorial-heading text-3xl sm:text-5xl lg:text-6xl font-light text-[#F8F6F2] leading-[1.15]">
                "We do not mass produce. We do not compromise. We preserve the human breath in every thread, hammer strike, and brushstroke."
            </blockquote>

            <p class="font-editorial-body text-base sm:text-lg text-[#F8F6F2]/80 max-w-2xl mx-auto leading-relaxed">
                In an era dominated by synthetic fast-fashion and automated machine replicas, Sorolata provides sanctuary for endangered heritage arts. By ensuring fair, direct compensation to rural guilds, we keep Bengal's living cultural soul vibrant.
            </p>

            <div class="pt-4">
                <x-button href="{{ route('pages.about') }}" variant="terracotta" size="lg">
                    Read Complete Manifesto →
                </x-button>
            </div>
        </div>
    </section>

    <!-- SECTION 10: Shop by District -->
    <section id="districts-grid" class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Geographical Provenance</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        Shop by Heritage District
                    </h2>
                </div>
                <a href="{{ route('districts.index') }}" class="text-xs uppercase tracking-widest text-[#214E34] font-bold hover:underline mt-4 sm:mt-0">
                    View All 64 Districts →
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

    <!-- SECTION 11: Meet The Artisan -->
    <section id="artisan-section" class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">Generational Masters</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    The Hands Behind The Heirlooms
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    Every artisan has a name, a studio, and an unbroken lineage of dedication.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($artisans as $artisan)
                    <div class="bg-[#F8F6F2] rounded-[32px] p-6 shadow-soft-xs hover:shadow-soft-hover transition-luxury flex flex-col justify-between space-y-4">
                        <div class="aspect-square rounded-[24px] overflow-hidden bg-[#E8E8E8]">
                            <img 
                                src="{{ $artisan->portrait_url }}" 
                                alt="{{ $artisan->full_name }}" 
                                class="w-full h-full object-cover grayscale contrast-125 hover:grayscale-0 transition-all duration-700"
                                loading="lazy"
                            >
                        </div>

                        <div class="space-y-1">
                            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">
                                {{ $artisan->craft_type->title() }}
                            </span>
                            <h4 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                                {{ $artisan->full_name }}
                            </h4>
                            <p class="text-xs text-[#888888]">
                                {{ $artisan->atelier_name }} • {{ $artisan->district?->name_en }}
                            </p>
                        </div>

                        <p class="text-xs text-[#555555] line-clamp-2 italic font-editorial-heading text-sm">
                            "{{ $artisan->quote }}"
                        </p>

                        <div class="pt-2 border-t border-[#E8E8E8] flex items-center justify-between text-xs">
                            <span class="text-[#888888]">{{ $artisan->years_of_mastery }} Years Mastery</span>
                            <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" class="text-[#214E34] font-semibold hover:underline">Profile →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 12: Trending Products -->
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Fresh from the Ateliers</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        Trending Releases
                    </h2>
                </div>
                <p class="text-xs uppercase tracking-widest text-[#888888] mt-4 sm:mt-0 font-medium">
                    Limited pieces ready for immediate international dispatch
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($trendingProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 13: Customer Reviews -->
    <section class="py-24 bg-white border-y border-[#E8E8E8]/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">Collector Reviews</span>
                <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                    Cherished by Discerning Collectors
                </h2>
                <p class="text-sm text-[#555555] mt-3 leading-relaxed">
                    Read unedited reviews from textile historians, interior designers, and global expat patrons.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-testimonial-card 
                    quote="Unboxing the Sonargaon Muslin scarf in London was breathtaking. The signed provenance certificate and the unrefined botanical ecru texture is simply incomparable."
                    author="Dr. Eleanor Vance"
                    location="Textile Historian • Kensington, London"
                />
                <x-testimonial-card 
                    quote="The hand-chiseled Bogura brass kettle serves as the crown jewel of our dining room. It holds heat with incredible acoustic resonance."
                    author="Tariqul Rahman"
                    location="Architect & Expat Collector • Toronto, Canada"
                />
                <x-testimonial-card 
                    quote="The Tangail Mulberry Jamdani is a museum-grade textile. Knowing the exact weaver who crafted it makes it our family's most valued heirloom."
                    author="Farhana Chowdhury"
                    location="Art Collector • Gulshan, Dhaka"
                />
            </div>
        </div>
    </section>

    <!-- SECTION 14: Instagram Feed -->
    <section class="py-24 bg-[#F8F6F2]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Visual Chronicle</span>
                    <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                        @SorolataHeritage
                    </h2>
                </div>
                <p class="text-xs text-[#888888] mt-4 sm:mt-0">
                    Follow the daily rhythms of our rural weaving clusters
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
                            alt="Atelier Life in Bengal" 
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

    <!-- SECTION 15: Newsletter -->
    <section class="py-24 bg-white border-t border-[#E8E8E8]/70">
        <div class="max-w-3xl mx-auto px-6 lg:px-12 text-center space-y-6">
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">The Collector's Circle</span>
            
            <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D]">
                Receive Intimate Archival Dispatches
            </h2>

            <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                Join our private circle of patrons. We dispatch thoughtful notes when a generational weaver prepares a rare loom, with exclusive early access to one-off pieces.
            </p>

            <form class="pt-4 flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                <input 
                    type="email" 
                    placeholder="Enter your email address..." 
                    class="flex-grow px-6 py-4 rounded-full border border-[#E8E8E8] bg-[#F8F6F2] text-sm text-[#1D1D1D] placeholder-[#888888] focus:outline-none focus:border-[#214E34] focus:ring-2 focus:ring-[#214E34]/10 transition-colors"
                    required
                >
                <x-button type="submit" variant="primary" size="md" class="whitespace-nowrap px-8">
                    Join Collector Desk
                </x-button>
            </form>
            <p class="text-[11px] text-[#888888]">We honor your privacy. Unsubscribe at any moment with a single click.</p>
        </div>
    </section>

@endsection
