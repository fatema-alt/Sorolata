@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $collection->title }} — Curated Thematic Drop | Sorolata</title>
    <meta name="description" content="{{ $collection->description ?? 'Explore limited edition curated heritage drops.' }}">
    <link rel="canonical" href="{{ route('collection.show', $collection->slug) }}">
@endsection

@section('content')
    <!-- Luxury Collection Landing Hero -->
    <section class="relative py-24 lg:py-36 bg-[#214E34] text-[#F8F6F2] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
            <div class="max-w-3xl space-y-6">
                <span class="inline-block text-xs uppercase tracking-widest text-[#DE7D4E] font-bold">
                    {{ __('Archival Seasonal Collection') }}
                </span>
                <h1 class="font-editorial-heading text-5xl sm:text-7xl font-light text-[#F8F6F2] leading-tight">
                    {{ $collection->title }}
                </h1>
                <p class="font-editorial-body text-lg sm:text-xl text-[#F8F6F2]/80 leading-relaxed font-light">
                    {{ $collection->description ?? __('A curated selection of living artifacts brought together across distinct Bengal districts.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Collection Story Section -->
    <section class="py-20 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center space-y-6">
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('The Curation Thesis') }}</span>
            <blockquote class="font-editorial-heading text-2xl sm:text-3xl text-[#1D1D1D] font-light leading-relaxed italic">
                {{ __('"When you gather these pieces, you assemble centuries of quiet, patient river-basin civilization into your modern living space."') }}
            </blockquote>
        </div>
    </section>

    <!-- Featured Products in Collection -->
    <section class="py-20 max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endsection
