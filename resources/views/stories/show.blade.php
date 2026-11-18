@extends('layouts.storefront')

@php
    $locale = app()->getLocale();
    $title = $story->getLocalized('title');
    $excerpt = $story->getLocalized('excerpt');
    $category = $story->getLocalized('category');
    $author = $story->getLocalized('author_name');
@endphp

@section('seo_meta')
    <title>{{ $title }} — {{ __('The Heritage Gazette') }} | Sorolata</title>
    <meta name="description" content="{{ $excerpt }}">
    <link rel="canonical" href="{{ route('stories.show', $story->slug) }}">
@endsection

@section('content')
    <article class="max-w-4xl mx-auto px-6 lg:px-12 py-16 space-y-12">
        
        <!-- Story Header -->
        <header class="space-y-5 text-center">
            <div class="flex items-center justify-center gap-3 text-xs text-[#888888] uppercase tracking-wider">
                <span class="text-[#C96A3D] font-bold bg-[#C96A3D]/10 px-3 py-1 rounded-full">{{ $category }}</span>
                <span>•</span>
                <span>{{ $story->reading_time }} {{ __('min read') }}</span>
                <span>•</span>
                <span>{{ $story->created_at->format('M d, Y') }}</span>
            </div>
            
            <h1 class="font-editorial-heading text-4xl sm:text-6xl font-light text-[#1D1D1D] leading-tight">
                {{ $title }}
            </h1>
            
            @if($excerpt)
                <p class="font-editorial-body text-lg sm:text-xl text-[#555555] max-w-2xl mx-auto leading-relaxed">
                    {{ $excerpt }}
                </p>
            @endif
        </header>

        <!-- Hero Image -->
        @if($story->hero_image)
            <div class="rounded-3xl overflow-hidden aspect-[16/9] shadow-sm bg-[#F8F6F2] border border-[#EBE7DF]">
                <img src="{{ $story->hero_image }}" alt="{{ $title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Story Content Body with Nested Shoppable Cards -->
        <div class="prose prose-lg max-w-none font-editorial-heading text-lg sm:text-xl text-[#2B2B2B] leading-relaxed space-y-6 pt-2">
            {!! $parsedContent !!}
        </div>

        <!-- Author Byline -->
        <div class="pt-8 border-t border-[#E8E8E8] flex flex-wrap items-center justify-between gap-4 text-xs text-[#888888]">
            <span>{{ __('Dispatch by') }}: <strong class="text-[#1D1D1D]">{{ $author }}</strong></span>
            <a href="{{ route('stories.index') }}" class="text-[#214E34] font-semibold hover:underline flex items-center gap-1">
                ← {{ __('All Gazette Dispatches') }}
            </a>
        </div>

        <!-- Featured Heirlooms in this Story (Shajgoj Shoppable Carousel/Grid) -->
        @if($featuredProducts->isNotEmpty())
            <section class="mt-16 pt-12 border-t-2 border-[#214E34]/10 not-prose">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-[11px] font-bold tracking-widest uppercase text-[#C96A3D] block mb-1">
                            {{ $locale === 'bn' ? 'আর্টিকেল কালেকশন' : 'STORY CURATION' }}
                        </span>
                        <h2 class="font-editorial-heading text-2xl sm:text-3xl text-[#1D1D1D]">
                            {{ __('Featured in this Story') }}
                        </h2>
                    </div>
                    <span class="text-xs text-[#888888] hidden sm:inline">
                        {{ $featuredProducts->count() }} {{ $locale === 'bn' ? 'টি হস্তশিল্প উল্লেখিত' : 'pieces featured' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($featuredProducts as $item)
                        @php
                            $itemTitle = $item->getLocalized('title');
                            $itemImage = $item->media->first()?->url ?? 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600';
                            $itemArtisan = $item->artisan?->getLocalized('full_name');
                        @endphp
                        <div class="group rounded-2xl border border-[#E8E4DC] bg-white p-4 flex flex-col justify-between hover:shadow-md transition-all">
                            <div>
                                <a href="{{ route('products.show', $item->slug) }}" class="block aspect-square rounded-xl overflow-hidden bg-[#F4F1EA] mb-3">
                                    <img src="{{ $itemImage }}" alt="{{ $itemTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </a>
                                @if($item->district)
                                    <span class="text-[10px] tracking-widest uppercase font-semibold text-[#C96A3D] block mb-1">
                                        {{ $locale === 'bn' ? $item->district->name_bn : $item->district->name_en }}
                                    </span>
                                @endif
                                <h4 class="font-editorial-heading text-base font-normal text-[#1D1D1D] group-hover:text-[#214E34] transition-colors line-clamp-2">
                                    <a href="{{ route('products.show', $item->slug) }}">
                                        {{ $itemTitle }}
                                    </a>
                                </h4>
                                @if($itemArtisan)
                                    <p class="text-xs text-[#777777] mt-1">{{ $itemArtisan }}</p>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-[#F0ECE1] flex items-center justify-between">
                                <span class="font-editorial-heading text-lg font-medium text-[#1D1D1D]">
                                    ৳ {{ number_format((float) $item->price) }}
                                </span>
                                <a href="{{ route('products.show', $item->slug) }}" class="text-xs font-semibold text-[#214E34] hover:underline">
                                    {{ __('Acquire Piece') }} →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Related Stories -->
        @if($relatedStories->isNotEmpty())
            <section class="mt-16 pt-10 border-t border-[#E8E8E8] not-prose">
                <h3 class="font-editorial-heading text-xl text-[#1D1D1D] mb-6">
                    {{ $locale === 'bn' ? 'আরও পড়ুন' : 'Continue Reading' }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($relatedStories as $rel)
                        <a href="{{ route('stories.show', $rel->slug) }}" class="group block space-y-2">
                            <div class="aspect-[16/10] rounded-xl overflow-hidden bg-[#F8F6F2]">
                                <img src="{{ $rel->hero_image }}" alt="{{ $rel->getLocalized('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <span class="text-[10px] uppercase font-bold text-[#C96A3D] tracking-wider block">
                                {{ $rel->getLocalized('category') }}
                            </span>
                            <h4 class="font-editorial-heading text-sm font-normal text-[#1D1D1D] group-hover:text-[#214E34] transition-colors line-clamp-2 leading-snug">
                                {{ $rel->getLocalized('title') }}
                            </h4>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </article>
@endsection
