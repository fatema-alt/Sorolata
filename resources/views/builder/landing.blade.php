@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $page->seo_meta['title'] ?? $page->title }} — Sorolata</title>
    <meta name="description" content="{{ $page->seo_meta['description'] ?? 'Curated Bengali heritage handcrafted landing page.' }}">
    <meta property="og:title" content="{{ $page->seo_meta['title'] ?? $page->title }}">
    <meta property="og:description" content="{{ $page->seo_meta['description'] ?? '' }}">
@endsection

@section('content')
<article class="w-full flex flex-col">
    @forelse ($sections as $section)
        @php
            $settings = $section['settings'] ?? [];
            $parsedHeadline = $parser->parse($settings['headline'] ?? '');
            $parsedSubheadline = $parser->parse($settings['subheadline'] ?? '');
            $parsedBadge = $parser->parse($settings['badge_text'] ?? '');
            $type = $section['type'] ?? 'hero_cinematic';

            $align = $settings['alignment'] ?? 'center';
            $alignClass = match($align) {
                'left' => 'text-left items-start',
                'right' => 'text-right items-end',
                default => 'text-center items-center',
            };
            $justifyClass = match($align) {
                'left' => 'justify-start',
                'right' => 'justify-end',
                default => 'justify-center',
            };
            $textColor = $settings['text_color'] ?? '#1D1D1D';
            $headlineSize = $settings['headline_size'] ?? 'text-4xl sm:text-5xl lg:text-6xl';
            $imageUrl = $settings['image_url'] ?? null;
            $videoUrl = $settings['video_url'] ?? null;
            $videoData = !empty($videoUrl) ? $parser->parseVideoUrl($videoUrl) : null;
            $containerWidth = ($settings['container_width'] ?? 'boxed') === 'full' ? 'w-full px-4' : 'max-w-7xl mx-auto px-6 lg:px-12';
        @endphp

        <section class="w-full transition-colors {{ $settings['custom_class'] ?? '' }}"
                 style="background-color: {{ $settings['bg_color'] ?? '#FFFFFF' }}; padding-top: {{ $settings['padding_top'] ?? '64px' }}; padding-bottom: {{ $settings['padding_bottom'] ?? '64px' }};">
            <div class="{{ $containerWidth }}">
                
                @if ($type === 'hero_cinematic')
                    <div class="flex flex-col {{ $alignClass }} max-w-4xl mx-auto py-10">
                        @if ($parsedBadge)
                            <span class="inline-block text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-[#214E34]/30 text-[#214E34] bg-[#214E34]/5 mb-6">
                                {{ $parsedBadge }}
                            </span>
                        @endif
                        <h1 class="font-editorial-heading {{ $headlineSize }} tracking-tight mb-6" style="color: {{ $textColor }};">
                            {{ $parsedHeadline ?: $page->title }}
                        </h1>
                        @if ($parsedSubheadline)
                            <p class="text-base sm:text-xl text-[#555555] font-light leading-relaxed mb-8 max-w-2xl">
                                {{ $parsedSubheadline }}
                            </p>
                        @endif

                        <!-- Video or Image Embed -->
                        @if ($videoData && $videoData['type'] !== 'none')
                            <div class="w-full my-6 rounded-3xl overflow-hidden shadow-2xl border border-gray-200 aspect-video bg-black flex items-center justify-center">
                                @if ($videoData['type'] === 'mp4')
                                    <video controls class="w-full h-full object-cover">
                                        <source src="{{ $videoData['embed_url'] }}" type="video/mp4">
                                    </video>
                                @else
                                    <iframe src="{{ $videoData['embed_url'] }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                @endif
                            </div>
                        @elseif (!empty($imageUrl))
                            <div class="w-full my-6 rounded-3xl overflow-hidden shadow-xl border border-gray-200 aspect-[16/9] bg-stone-100">
                                <img src="{{ $imageUrl }}" alt="Hero Showcase" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center {{ $justifyClass }} gap-4 mt-2">
                            <a href="{{ $settings['primary_cta_url'] ?? route('shop.index') }}" 
                               class="px-8 py-3.5 rounded-full bg-[#214E34] hover:bg-[#183926] text-white text-xs font-semibold uppercase tracking-wider transition-all shadow-sm">
                                {{ $settings['primary_cta_text'] ?? 'Explore Curated Catalog' }}
                            </a>
                            @if (!empty($settings['secondary_cta_text']))
                                <a href="{{ $settings['secondary_cta_url'] ?? route('stories.index') }}" 
                                   class="px-8 py-3.5 rounded-full border border-[#1D1D1D]/20 hover:border-[#1D1D1D] text-[#1D1D1D] text-xs font-semibold uppercase tracking-wider transition-all">
                                    {{ $settings['secondary_cta_text'] }}
                                </a>
                            @endif
                        </div>
                    </div>

                @elseif ($type === 'artisan_spotlight')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center py-8">
                        <div class="rounded-3xl overflow-hidden aspect-[4/3] bg-stone-100 flex items-center justify-center text-stone-400 border border-[#E8E8E8] shadow-sm">
                            @if (!empty($imageUrl))
                                <img src="{{ $imageUrl }}" alt="Master Artisan" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=800" alt="Master Artisan" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex flex-col {{ $alignClass }} space-y-4">
                            <span class="text-xs font-bold uppercase tracking-widest text-[#C96A3D] block">
                                {{ $parsedBadge ?: 'Generational Custodians' }}
                            </span>
                            <h2 class="font-editorial-heading {{ $headlineSize }}" style="color: {{ $textColor }};">
                                {{ $parsedHeadline ?: 'The Hands Behind The Heritage' }}
                            </h2>
                            <p class="text-[#555555] text-base leading-relaxed font-light">
                                {{ $parsedSubheadline ?: 'Each thread is spun with water from sacred river basins, guided by intuition passed down through generations of master artisans.' }}
                            </p>
                            <div class="pt-2">
                                <a href="{{ $settings['primary_cta_url'] ?? route('artisans.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-[#214E34] hover:text-[#C96A3D] transition-colors underline underline-offset-4">
                                    <span>{{ $settings['primary_cta_text'] ?? 'Discover All Guild Artisans' }}</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>

                @elseif ($type === 'heritage_provenance')
                    <div class="py-8">
                        <div class="flex flex-col {{ $alignClass }} max-w-3xl mx-auto mb-10">
                            <span class="text-xs font-bold uppercase tracking-widest text-[#214E34] block mb-2">
                                {{ $parsedBadge ?: 'Authentic Provenance' }}
                            </span>
                            <h2 class="font-editorial-heading {{ $headlineSize }}" style="color: {{ $textColor }};">
                                {{ $parsedHeadline ?: 'Geographical Origin & GI Protection' }}
                            </h2>
                            @if($parsedSubheadline)
                                <p class="text-sm text-gray-500 mt-2">{{ $parsedSubheadline }}</p>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-6 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8] text-center">
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Geographical Status</div>
                                <div class="text-lg font-serif font-bold text-[#1D1D1D]">Certified GI Heritage</div>
                                <p class="text-xs text-gray-500 mt-2">Protected by the Geographical Indication registry of Bangladesh.</p>
                            </div>
                            <div class="p-6 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8] text-center">
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">River Basin</div>
                                <div class="text-lg font-serif font-bold text-[#1D1D1D]">Shitalakshya & Meghna</div>
                                <p class="text-xs text-gray-500 mt-2">Humid atmospheric river currents essential for fiber suppleness.</p>
                            </div>
                            <div class="p-6 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8] text-center">
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Guild Seal</div>
                                <div class="text-lg font-serif font-bold text-[#1D1D1D]">100% Handspun Guild</div>
                                <p class="text-xs text-gray-500 mt-2">Each piece is individually numbered and archived in the master ledger.</p>
                            </div>
                        </div>
                    </div>

                @elseif ($type === 'editorial_narrative')
                    <div class="max-w-4xl mx-auto py-8 space-y-6">
                        <div class="flex flex-col {{ $alignClass }}">
                            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">
                                {{ $parsedBadge ?: 'Living Cultural Chronicle' }}
                            </span>
                            <h2 class="font-editorial-heading {{ $headlineSize }} mt-2" style="color: {{ $textColor }};">
                                {{ $parsedHeadline ?: 'Slow Commerce & Sovereign Craftsmanship' }}
                            </h2>
                        </div>
                        <div class="font-editorial-heading text-xl sm:text-2xl text-[#2B2B2B] leading-relaxed pt-4 first-letter:text-6xl first-letter:float-left first-letter:mr-4 first-letter:text-[#214E34]">
                            <p>{{ $parsedSubheadline ?: 'In an era where industrial factories churn out disposable synthetic goods within seconds, the slow rhythm of traditional Bengal master artisans feels like another universe. Each creation breathes with generational dedication.' }}</p>
                        </div>
                        @if (!empty($imageUrl))
                            <div class="my-6 rounded-2xl overflow-hidden shadow-sm aspect-[16/9]">
                                <img src="{{ $imageUrl }}" alt="Story Image" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <p class="font-editorial-body text-base text-[#666666] leading-relaxed">
                            When you welcome one of these pieces into your home, you are directly anchoring an endangered cultural knowledge system, allowing master artisans to teach their children that their inherited hands possess sacred dignity.
                        </p>
                    </div>

                @elseif ($type === 'interactive_gallery')
                    <div class="py-8">
                        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
                            <div>
                                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">
                                    {{ $parsedBadge ?: 'Visual Study' }}
                                </span>
                                <h2 class="font-editorial-heading {{ $headlineSize }} mt-1" style="color: {{ $textColor }};">
                                    {{ $parsedHeadline ?: 'Tactile Intricacies & Macro Details' }}
                                </h2>
                            </div>
                            <p class="text-sm text-[#777777] max-w-sm mt-3 md:mt-0 italic">
                                {{ $parsedSubheadline ?: 'Notice the deliberate irregularities that prove human hand execution over automated mechanics.' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach([
                                'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=800',
                                'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800',
                                'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800'
                            ] as $img)
                                <div class="group relative rounded-2xl overflow-hidden aspect-[4/3] bg-[#EAE6DF] shadow-sm">
                                    <img src="{{ $img }}" alt="Craft Gallery" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif ($type === 'specs_anatomy')
                    <div class="py-8 max-w-4xl mx-auto">
                        <div class="flex flex-col {{ $alignClass }} mb-8">
                            <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">
                                {{ $parsedBadge ?: 'Technical Specifications' }}
                            </span>
                            <h2 class="font-editorial-heading {{ $headlineSize }} mt-1" style="color: {{ $textColor }};">
                                {{ $parsedHeadline ?: 'Material Anatomy & Composition' }}
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-5 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8]">
                                <h4 class="font-semibold text-sm text-[#1D1D1D] mb-1">Purity of Medium</h4>
                                <p class="text-xs text-[#666666]">100% natural, unadulterated raw ingredients sourced strictly from indigenous varieties.</p>
                            </div>
                            <div class="p-5 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8]">
                                <h4 class="font-semibold text-sm text-[#1D1D1D] mb-1">Ethical Honorarium</h4>
                                <p class="text-xs text-[#666666]">Artisans receive fair, sovereign compensation above national fair-trade thresholds.</p>
                            </div>
                        </div>
                    </div>

                @elseif ($type === 'testimonial_editorial')
                    <div class="py-8 max-w-3xl mx-auto flex flex-col {{ $alignClass }}">
                        <div class="text-2xl text-[#C96A3D] mb-3">★★★★★</div>
                        <blockquote class="font-editorial-heading text-2xl sm:text-3xl leading-snug italic mb-4" style="color: {{ $textColor }};">
                            "{{ $parsedSubheadline ?: 'To touch these pieces is to hold four centuries of living history. An unmatched heirloom of authentic Bengal.' }}"
                        </blockquote>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">
                            {{ $parsedHeadline ?: 'International Patron Review • London, UK' }}
                        </span>
                    </div>

                @elseif ($type === 'sticky_purchase_box')
                    <div class="p-8 rounded-3xl bg-white border border-[#E8E8E8] shadow-md max-w-xl mx-auto text-center space-y-4">
                        <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Collector Acquisition</span>
                        <h3 class="font-editorial-heading text-3xl text-[#1D1D1D]">
                            {{ $parsedHeadline ?: 'Acquire Certified Heirloom Pieces' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $parsedSubheadline ?: 'Each piece comes encased in archival packaging with an official provenance certificate.' }}
                        </p>
                        <a href="{{ $settings['primary_cta_url'] ?? route('shop.index') }}" class="inline-block w-full py-3.5 rounded-full bg-[#214E34] hover:bg-[#1a3f2a] text-white text-xs font-semibold uppercase tracking-wider transition-all">
                            {{ $settings['primary_cta_text'] ?? 'Browse Complete Artifact Archive →' }}
                        </a>
                    </div>

                @elseif ($type === 'element_heading')
                    <div class="flex flex-col {{ $alignClass }} py-3">
                        <h2 class="font-editorial-heading {{ $headlineSize }} font-bold tracking-tight" style="color: {{ $textColor }};">
                            {{ $parsedHeadline ?: 'New Editorial Heading' }}
                        </h2>
                    </div>

                @elseif ($type === 'element_paragraph')
                    <div class="flex flex-col {{ $alignClass }} py-3">
                        <div class="prose max-w-3xl text-sm sm:text-base leading-relaxed text-[#555555]" style="color: {{ $textColor }};">
                            <p>{{ $parsedSubheadline ?: 'Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship across historical Bengal.' }}</p>
                        </div>
                    </div>

                @elseif ($type === 'element_image')
                    <div class="flex flex-col {{ $alignClass }} py-3">
                        @if (!empty($imageUrl))
                            <div class="w-full max-w-3xl rounded-3xl overflow-hidden shadow-lg border border-stone-200">
                                <img src="{{ $imageUrl }}" alt="Craft Artwork" class="w-full h-auto object-cover">
                            </div>
                        @endif
                    </div>

                @elseif ($type === 'element_video')
                    <div class="flex flex-col {{ $alignClass }} py-3">
                        @if ($videoData && $videoData['type'] !== 'none')
                            <div class="w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl aspect-video bg-black">
                                @if ($videoData['type'] === 'mp4')
                                    <video controls class="w-full h-full object-cover">
                                        <source src="{{ $videoData['embed_url'] }}" type="video/mp4">
                                    </video>
                                @else
                                    <iframe src="{{ $videoData['embed_url'] }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                @endif
                            </div>
                        @endif
                    </div>

                @elseif ($type === 'element_button')
                    <div class="flex items-center {{ $justifyClass }} py-3">
                        <a href="{{ $settings['primary_cta_url'] ?? '#' }}" 
                           class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider inline-block shadow-sm hover:opacity-95 transition-all"
                           style="background-color: {{ $settings['bg_color'] ?? '#214E34' }}; color: {{ $textColor === '#1D1D1D' ? '#FFFFFF' : $textColor }};">
                            {{ $settings['primary_cta_text'] ?? 'Explore Artifacts' }}
                        </a>
                    </div>

                @elseif ($type === 'element_product_card')
                    <div class="flex flex-col {{ $alignClass }} py-4">
                        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm max-w-xs text-center transition-all hover:shadow-md">
                            @if (!empty($imageUrl))
                                <div class="w-full h-52 rounded-2xl overflow-hidden mb-3">
                                    <img src="{{ $imageUrl }}" alt="Product" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <span class="text-[10px] uppercase font-bold tracking-widest text-[#214E34] block mb-1">
                                {{ $parsedBadge ?: 'GI Certified Heritage' }}
                            </span>
                            <h4 class="font-editorial-heading text-lg font-bold text-gray-900 mb-1">
                                {{ $parsedHeadline ?: 'Handcrafted Jamdani Piece' }}
                            </h4>
                            <div class="text-sm font-bold text-[#C96A3D] mb-4">
                                {{ $settings['price'] ?? '৳ 18,500' }}
                            </div>
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $settings['product_id'] ?? 1 }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-2.5 rounded-full bg-[#214E34] hover:bg-[#183926] text-white text-xs font-semibold tracking-wide transition-colors">
                                    Add to Archival Bag
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif ($type === 'element_artisan_quote')
                    <div class="max-w-3xl mx-auto p-8 rounded-3xl bg-[#F8F6F2] border-l-4 border-[#214E34] shadow-sm my-4">
                        <p class="font-editorial-heading text-2xl italic text-gray-800 leading-snug mb-4">
                            "{{ $parsedSubheadline ?: 'Our hands carry the memory of three centuries of loom heritage on the Shitalakshya.' }}"
                        </p>
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="font-bold text-gray-900">— {{ $parsedHeadline ?: 'Ustad Alimullah Dewan' }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-[#214E34] font-semibold">{{ $parsedBadge ?: 'Royal Master Loom Guild' }}</span>
                        </div>
                    </div>

                @elseif ($type === 'element_divider')
                    <div class="w-full max-w-2xl mx-auto my-6 flex items-center justify-center space-x-4">
                        <div class="h-[1px] flex-1 bg-stone-300"></div>
                        <span class="text-[#D4AF37] text-sm">❖</span>
                        <div class="h-[1px] flex-1 bg-stone-300"></div>
                    </div>

                @elseif ($type === 'magazine_hero_grid')
                    <div class="py-4 max-w-7xl mx-auto">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <!-- Main Featured Story (Left 8 cols) -->
                            <div class="lg:col-span-8 group relative rounded-3xl overflow-hidden shadow-xl aspect-[16/11] sm:aspect-[16/10] bg-stone-900 flex flex-col justify-end p-6 sm:p-10 transition-transform duration-300">
                                <img src="{{ $imageUrl ?: 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1200' }}" 
                                     alt="Featured Magazine Story" 
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/45 to-transparent"></div>
                                
                                <div class="relative z-10 space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase bg-[#C96A3D] text-white shadow-md">
                                            {{ $parsedBadge ?: 'HERITAGE DISCOVERY' }}
                                        </span>
                                        <span class="text-white/80 text-xs font-medium flex items-center space-x-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>5 min read</span>
                                        </span>
                                    </div>

                                    <h2 class="font-editorial-heading text-2xl sm:text-4xl text-white font-bold tracking-tight leading-snug group-hover:text-[#A3D9B1] transition-colors">
                                        {{ $parsedHeadline ?: 'Are you a Green or Red Flag? Have You Ever Discovered Your True Craft Soul Amidst Heritage?' }}
                                    </h2>

                                    <p class="text-sm sm:text-base text-gray-200 line-clamp-2 font-light leading-relaxed">
                                        {{ $parsedSubheadline ?: 'Traveling across the ancestral river basins of Bengal where generational master weavers and metalsmiths preserve centuries of living stories.' }}
                                    </p>

                                    <div class="pt-2 flex items-center justify-between border-t border-white/20">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full overflow-hidden border border-white/50">
                                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200" alt="Curator" class="w-full h-full object-cover">
                                            </div>
                                            <div class="text-xs text-white">
                                                <span class="font-semibold block">Curator Raisa Alom</span>
                                                <span class="text-[10px] text-gray-300">Shajgoj & Sorolata Heritage Guild</span>
                                            </div>
                                        </div>
                                        <a href="{{ $settings['primary_cta_url'] ?? route('stories.index') }}" 
                                           class="inline-flex items-center space-x-1 px-4 py-2 rounded-full bg-white/15 hover:bg-[#214E34] text-white text-xs font-semibold backdrop-blur transition-all border border-white/30">
                                            <span>Read Story</span>
                                            <span>→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Stacked Editorial Cards (Right 4 cols) -->
                            <div class="lg:col-span-4 flex flex-col gap-6">
                                <!-- Stacked Card 1 -->
                                <div class="group relative rounded-3xl overflow-hidden shadow-lg flex-1 min-h-[240px] bg-stone-900 flex flex-col justify-end p-5 transition-transform duration-300">
                                    <img src="https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600" 
                                         alt="Sub Story 1" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                    <div class="relative z-10 space-y-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-[#214E34] text-white inline-block">
                                            LOOM TRADITIONS
                                        </span>
                                        <h3 class="font-editorial-heading text-lg sm:text-xl text-white font-bold leading-tight group-hover:text-[#A3D9B1] transition-colors">
                                            The Secret 300-Count Thread: How Sonargaon Muslin Was Resurrected
                                        </h3>
                                        <div class="text-[11px] text-gray-300 flex items-center justify-between pt-1">
                                            <span>By Ustad Alimullah</span>
                                            <span class="text-[#A3D9B1] font-semibold">Explore →</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stacked Card 2 -->
                                <div class="group relative rounded-3xl overflow-hidden shadow-lg flex-1 min-h-[240px] bg-stone-900 flex flex-col justify-end p-5 transition-transform duration-300">
                                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600" 
                                         alt="Sub Story 2" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                    <div class="relative z-10 space-y-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-amber-700 text-white inline-block">
                                            ANCIENT BRASS
                                        </span>
                                        <h3 class="font-editorial-heading text-lg sm:text-xl text-white font-bold leading-tight group-hover:text-[#A3D9B1] transition-colors">
                                            Lost-Wax Bronze of Dhamrai: Pouring Liquid Fire into Earthen Moulds
                                        </h3>
                                        <div class="text-[11px] text-gray-300 flex items-center justify-between pt-1">
                                            <span>By Master Sukumar</span>
                                            <span class="text-[#A3D9B1] font-semibold">Explore →</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif ($type === 'topic_bubbles')
                    <div class="py-6 max-w-7xl mx-auto">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-stone-200">
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-widest text-[#214E34]">TOPIC ARCHIVES</span>
                                <h3 class="font-editorial-heading text-2xl text-gray-900 font-bold">
                                    {{ $parsedHeadline ?: 'Explore Stories by Heritage Craft & Guild' }}
                                </h3>
                            </div>
                            <a href="{{ route('stories.index') }}" class="text-xs font-semibold text-[#214E34] hover:underline flex items-center space-x-1">
                                <span>View All Gazette Topics</span>
                                <span>→</span>
                            </a>
                        </div>

                        <!-- Topic Circular Bubbles Row -->
                        <div class="flex items-center space-x-6 sm:space-x-8 overflow-x-auto pb-4 pt-1 builder-scroll no-scrollbar">
                            @php
                                $topics = [
                                    ['name' => 'Jamdani Weave', 'count' => '24 stories', 'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=250', 'url' => route('stories.index')],
                                    ['name' => 'Nakshi Kantha', 'count' => '18 stories', 'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=250', 'url' => route('stories.index')],
                                    ['name' => 'Dhamrai Brass', 'count' => '15 stories', 'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=250', 'url' => route('shop.index')],
                                    ['name' => 'River Clay', 'count' => '12 stories', 'img' => 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?q=80&w=250', 'url' => route('shop.index')],
                                    ['name' => 'Tangail Silk', 'count' => '19 stories', 'img' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=250', 'url' => route('shop.index')],
                                    ['name' => 'Natural Indigo', 'count' => '9 stories', 'img' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=250', 'url' => route('stories.index')],
                                    ['name' => 'Master Artisans', 'count' => '32 profiles', 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=250', 'url' => route('artisans.index')],
                                    ['name' => 'GI Certified', 'count' => '14 hubs', 'img' => 'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?q=80&w=250', 'url' => route('districts.index')],
                                ];
                            @endphp

                            @foreach ($topics as $topic)
                                <a href="{{ $topic['url'] }}" class="flex flex-col items-center text-center group shrink-0 transition-transform hover:-translate-y-1">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full p-1 border-2 border-dashed border-[#214E34]/30 group-hover:border-[#214E34] transition-all bg-white shadow-sm mb-2.5">
                                        <div class="w-full h-full rounded-full overflow-hidden">
                                            <img src="{{ $topic['img'] }}" alt="{{ $topic['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 group-hover:text-[#214E34] transition-colors truncate max-w-[100px]">
                                        {{ $topic['name'] }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 mt-0.5">
                                        {{ $topic['count'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                @elseif ($type === 'editorial_feed_sidebar')
                    <div class="py-8 max-w-7xl mx-auto">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                            
                            <!-- Left 8 Columns: Magazine Stories Grid -->
                            <div class="lg:col-span-8 space-y-6">
                                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                                    <h3 class="font-editorial-heading text-2xl font-bold text-gray-900">
                                        {{ $parsedHeadline ?: 'Heritage Gazette Stories & In-Depth Chronicles' }}
                                    </h3>
                                    <span class="text-xs text-gray-400 font-medium">Curated Journal</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    @php
                                        $articles = [
                                            [
                                                'title' => 'The Sacred Geometry of Jamdani Motifs: Memory Over Stencils',
                                                'badge' => 'WEAVING PHILOSOPHY',
                                                'time' => '4 min read',
                                                'date' => 'Nov 14, 2026',
                                                'excerpt' => 'Why master weavers refuse graph paper, committing four hundred floral patterns entirely to song rhythms.',
                                                'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600',
                                            ],
                                            [
                                                'title' => 'Resurrecting Phuti Karpas: The Gossamer Muslin of Bengal',
                                                'badge' => 'COTTON BOTANY',
                                                'time' => '7 min read',
                                                'date' => 'Nov 12, 2026',
                                                'excerpt' => 'Agronomists and guild elders collaborate to revive the lost indigenous cotton plant along the river Megna.',
                                                'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600',
                                            ],
                                            [
                                                'title' => 'The River Clay of Rayer Bazar: Alchemical Terracotta Vessels',
                                                'badge' => 'POTTERY HERITAGE',
                                                'time' => '5 min read',
                                                'date' => 'Nov 09, 2026',
                                                'excerpt' => 'Generational potters demonstrate how silt sedimentation from monsoon floods creates heat-retaining clay.',
                                                'img' => 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?q=80&w=600',
                                            ],
                                            [
                                                'title' => 'Nakshi Kantha: Embroidering Proverbs into Recycled Quilted Silks',
                                                'badge' => 'FOLK TEXTILES',
                                                'time' => '6 min read',
                                                'date' => 'Nov 06, 2026',
                                                'excerpt' => 'Rural women in Jessore transcribe village folklore, monsoon rains, and sacred animals into chain-stitch tapestries.',
                                                'img' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=600',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($articles as $art)
                                        <div class="rounded-3xl bg-white border border-stone-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                                            <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-stone-100">
                                                <img src="{{ $art['img'] }}" alt="{{ $art['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#214E34] text-white shadow-sm">
                                                    {{ $art['badge'] }}
                                                </span>
                                            </div>
                                            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                                                <div>
                                                    <div class="flex items-center space-x-2 text-[11px] text-gray-400 mb-1.5">
                                                        <span>{{ $art['date'] }}</span>
                                                        <span>•</span>
                                                        <span>{{ $art['time'] }}</span>
                                                    </div>
                                                    <h4 class="font-editorial-heading text-lg font-bold text-gray-900 leading-snug group-hover:text-[#214E34] transition-colors">
                                                        <a href="{{ route('stories.index') }}">{{ $art['title'] }}</a>
                                                    </h4>
                                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                                                        {{ $art['excerpt'] }}
                                                    </p>
                                                </div>
                                                <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                                                    <span class="text-[11px] font-bold text-[#C96A3D]">Read Story</span>
                                                    <span class="text-[#214E34] group-hover:translate-x-1 transition-transform">→</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right 4 Columns: Trending Categories Directory & Curator Card -->
                            <div class="lg:col-span-4 space-y-6">
                                <!-- Trending Categories Card -->
                                <div class="rounded-3xl bg-white border border-stone-200 p-6 shadow-sm">
                                    <h4 class="font-editorial-heading text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-stone-100 flex items-center justify-between">
                                        <span>Trending Topics</span>
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-stone-100 text-stone-600">DIRECTORIES</span>
                                    </h4>
                                    <ul class="space-y-3">
                                        @php
                                            $trending = [
                                                ['name' => 'Handloom Muslin & Jamdani', 'count' => 24, 'url' => route('stories.index')],
                                                ['name' => 'Dhamrai Bell-Metal & Brass', 'count' => 18, 'url' => route('shop.index')],
                                                ['name' => 'Tangail Tant & Khadi', 'count' => 15, 'url' => route('shop.index')],
                                                ['name' => 'River Basin Pottery', 'count' => 12, 'url' => route('shop.index')],
                                                ['name' => 'Natural Plant & Indigo Dyes', 'count' => 9, 'url' => route('stories.index')],
                                                ['name' => 'Heirloom Shital Pati & Cane', 'count' => 7, 'url' => route('shop.index')],
                                            ];
                                        @endphp
                                        @foreach ($trending as $cat)
                                            <li>
                                                <a href="{{ $cat['url'] }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-[#F8F6F2] transition-colors group">
                                                    <span class="text-xs font-semibold text-gray-700 group-hover:text-[#214E34]">
                                                        {{ $cat['name'] }}
                                                    </span>
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-stone-100 group-hover:bg-[#214E34] group-hover:text-white transition-colors text-gray-500">
                                                        {{ $cat['count'] }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Master Curator Seal & Fair Trade Pledge Card -->
                                <div class="rounded-3xl bg-[#F8F6F2] border border-[#E8E8E8] p-6 shadow-sm space-y-3">
                                    <div class="flex items-center space-x-2 text-[#214E34]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        <span class="text-xs font-bold uppercase tracking-wider">Curator Provenance Seal</span>
                                    </div>
                                    <p class="text-xs text-gray-600 leading-relaxed italic">
                                        "Every piece we profile is authenticated by living guild elders. Reading the story connects you directly to the artisan; acquiring it ensures 100% fair honorarium returns to their loom atelier."
                                    </p>
                                    <div class="pt-2 border-t border-stone-200 flex items-center justify-between text-[11px] text-gray-500">
                                        <span class="font-bold text-gray-900">Dr. Niaz Chowdhury</span>
                                        <span>Chairman, Guild Council</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                @elseif ($type === 'shoppable_product_shelf')
                    <div class="py-8 max-w-7xl mx-auto">
                        <!-- Section Header with Commerce CTA -->
                        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 pb-4 border-b border-stone-200 gap-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-widest text-[#C96A3D] block mb-1">
                                    AS FEATURED IN OUR STORIES
                                </span>
                                <h3 class="font-editorial-heading text-2xl sm:text-3xl font-bold text-gray-900">
                                    {{ $parsedHeadline ?: 'From Story to Hand: Acquire the Pieces Featured Above' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-xl">
                                    {{ $parsedSubheadline ?: 'Crafted in limited archival batches by the master guilds profiled in this edition.' }}
                                </p>
                            </div>
                            <a href="{{ route('shop.index') }}" class="px-5 py-2.5 rounded-full bg-[#214E34] hover:bg-[#183926] text-white text-xs font-semibold uppercase tracking-wider transition-all shrink-0">
                                Browse Complete Shop Archive →
                            </a>
                        </div>

                        <!-- 4-Column Product Shelf Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @php
                                $shelfProducts = [
                                    [
                                        'id' => 1,
                                        'title' => 'The Imperial Sonargaon Muslin Scarf',
                                        'price' => '৳ 18,500',
                                        'badge' => 'GI Certified • Sonargaon',
                                        'atelier' => 'Dewan Royal Loom',
                                        'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600',
                                    ],
                                    [
                                        'id' => 2,
                                        'title' => 'Heirloom Tangail Mulberry Silk Jamdani',
                                        'price' => '৳ 24,500',
                                        'badge' => 'Master Guild • Tangail',
                                        'atelier' => 'Basak Silk Guild',
                                        'img' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=600',
                                    ],
                                    [
                                        'id' => 3,
                                        'title' => 'Hand-Chiseled Royal Bogura Brass Kettle',
                                        'price' => '৳ 12,800',
                                        'badge' => 'Lost-Wax Cast • Bogura',
                                        'atelier' => 'Karmakar Bronze Forge',
                                        'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600',
                                    ],
                                    [
                                        'id' => 4,
                                        'title' => 'Rangpur Royal Shatranji Flatweave Carpet',
                                        'price' => '৳ 15,600',
                                        'badge' => 'GI Protected • Rangpur',
                                        'atelier' => 'Nisbet Ganj Cooperative',
                                        'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600',
                                    ],
                                ];
                            @endphp

                            @foreach ($shelfProducts as $prod)
                                <div class="rounded-3xl bg-white border border-stone-200 p-4 shadow-sm hover:shadow-lg transition-all group flex flex-col justify-between">
                                    <div>
                                        <div class="w-full h-64 rounded-2xl overflow-hidden bg-stone-100 mb-3 relative">
                                            <img src="{{ $prod['img'] }}" alt="{{ $prod['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur text-[#214E34] border border-[#214E34]/20 shadow-sm">
                                                {{ $prod['badge'] }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">
                                            {{ $prod['atelier'] }}
                                        </div>
                                        <h4 class="font-editorial-heading text-base font-bold text-gray-900 group-hover:text-[#214E34] transition-colors leading-snug mb-2">
                                            {{ $prod['title'] }}
                                        </h4>
                                    </div>

                                    <div class="pt-3 border-t border-stone-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs text-gray-400">Authentic Price</span>
                                            <span class="text-base font-bold text-[#C96A3D] font-mono">{{ $prod['price'] }}</span>
                                        </div>
                                        <form action="{{ route('cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $prod['id'] }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-full py-2.5 rounded-full bg-[#214E34] hover:bg-[#183926] text-white text-xs font-semibold tracking-wide transition-colors flex items-center justify-center space-x-1.5 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                <span>Acquire Piece</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif ($type === 'video_reels_showcase')
                    <div class="py-8 max-w-7xl mx-auto">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-stone-200">
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-widest text-[#C96A3D]">LIVING CINEMATICS</span>
                                <h3 class="font-editorial-heading text-2xl sm:text-3xl text-gray-900 font-bold">
                                    {{ $parsedHeadline ?: 'Living Chronicles: Watch the Hands Behind the Craft' }}
                                </h3>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Video Stories</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @php
                                $reels = [
                                    [
                                        'title' => 'The Rhythmic Shuttle of Dewan Loom',
                                        'location' => 'Sonargaon • Jamdani',
                                        'duration' => '2:45',
                                        'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600',
                                    ],
                                    [
                                        'title' => 'Smelting Bell Metal at 1100°C',
                                        'location' => 'Dhamrai • Bronze Cast',
                                        'duration' => '3:20',
                                        'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600',
                                    ],
                                    [
                                        'title' => 'Indigo Fermentation in Earthen Pots',
                                        'location' => 'Rangpur • Plant Dye',
                                        'duration' => '1:50',
                                        'img' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=600',
                                    ],
                                    [
                                        'title' => 'Folk Needlework of Nakshi Kantha',
                                        'location' => 'Jessore • Quilt Tapestry',
                                        'duration' => '4:10',
                                        'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600',
                                    ],
                                ];
                            @endphp

                            @foreach ($reels as $reel)
                                <div class="relative rounded-3xl overflow-hidden aspect-[9/14] bg-stone-900 group shadow-md flex flex-col justify-end p-5 transition-transform duration-300 hover:scale-[1.02]">
                                    <img src="{{ $reel['img'] }}" alt="{{ $reel['title'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-85">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                                    
                                    <!-- Center Play Button Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-12 h-12 rounded-full bg-white/25 backdrop-blur border border-white/60 flex items-center justify-center text-white shadow-xl group-hover:scale-110 group-hover:bg-[#214E34] transition-all">
                                            <svg class="w-5 h-5 ml-0.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>

                                    <div class="relative z-10 space-y-1.5">
                                        <div class="flex items-center justify-between text-[10px] text-white/80">
                                            <span class="font-bold text-[#A3D9B1] uppercase tracking-wider">{{ $reel['location'] }}</span>
                                            <span class="bg-black/60 px-2 py-0.5 rounded-full backdrop-blur font-mono">{{ $reel['duration'] }}</span>
                                        </div>
                                        <h4 class="font-editorial-heading text-base text-white font-bold leading-snug group-hover:text-[#A3D9B1] transition-colors">
                                            {{ $reel['title'] }}
                                        </h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif ($type === 'heritage_club_newsletter')
                    <div class="py-6 max-w-5xl mx-auto">
                        <div class="rounded-3xl bg-[#214E34] text-white p-8 sm:p-12 text-center shadow-2xl relative overflow-hidden">
                            <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
                            <div class="absolute -left-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
                            
                            <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                                <span class="text-xs uppercase tracking-widest text-[#A3D9B1] font-bold block">
                                    {{ $parsedBadge ?: 'SOROLATA HERITAGE GUILD' }}
                                </span>
                                <h3 class="font-editorial-heading text-2xl sm:text-4xl font-bold text-white tracking-tight">
                                    {{ $parsedHeadline ?: 'Join the Heritage Gazette & Collector Circle' }}
                                </h3>
                                <p class="text-sm sm:text-base text-white/80 font-light leading-relaxed">
                                    {{ $parsedSubheadline ?: 'Receive our monthly investigative craft essays, private notifications for limited loom harvests, and invitations to master artisan exhibitions.' }}
                                </p>

                                <div class="pt-2 max-w-md mx-auto">
                                    <form onsubmit="event.preventDefault(); alert('ধন্যবাদ! ঐতিহ্য গেজেটে সাবস্ক্রিপশন সম্পন্ন হয়েছে।');" class="flex flex-col sm:flex-row gap-2">
                                        <input type="email" required placeholder="Enter your email address..." class="flex-1 px-4 py-3 rounded-full bg-white/10 border border-white/25 text-white placeholder-white/60 text-xs focus:outline-none focus:bg-white/20 transition-all">
                                        <button type="submit" class="px-6 py-3 rounded-full bg-[#C96A3D] hover:bg-[#b5582c] text-white text-xs font-bold uppercase tracking-wider shadow-lg transition-colors shrink-0">
                                            {{ $settings['primary_cta_text'] ?? 'Subscribe' }}
                                        </button>
                                    </form>
                                    <p class="text-[10px] text-white/60 mt-2">Respecting your privacy. No spam, only genuine cultural curation.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="py-8 flex flex-col {{ $alignClass }} max-w-3xl mx-auto">
                        <h3 class="font-editorial-heading {{ $headlineSize }} mb-2" style="color: {{ $textColor }};">
                            {{ $parsedHeadline ?: 'Heritage Section' }}
                        </h3>
                        <p class="text-base text-[#555555] font-light leading-relaxed">
                            {{ $parsedSubheadline ?: 'Archival craft presented with timeless editorial precision.' }}
                        </p>
                    </div>
                @endif

            </div>
        </section>
    @empty
        <div class="min-h-[50vh] flex flex-col items-center justify-center text-center px-4">
            <h2 class="font-editorial-heading text-3xl text-[#1D1D1D] mb-2">Page Under Archival Curation</h2>
            <p class="text-sm text-gray-500">This landing page has no published sections yet.</p>
        </div>
    @endforelse
</article>
@endsection
