@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $category->name }} — Bengal Heritage Guild | Sorolata</title>
    <meta name="description" content="{{ $category->description ?? 'Discover authentic Bengali handcrafted heirlooms.' }}">
    <link rel="canonical" href="{{ route('category.show', $category->slug) }}">
@endsection

@section('content')
    <!-- Category Hero Banner -->
    <section class="py-16 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="max-w-3xl space-y-4">
                <div class="flex items-center gap-2 text-xs text-[#888888] uppercase tracking-wider">
                    <a href="{{ url('/') }}" class="hover:text-[#214E34]">Home</a>
                    <span>/</span>
                    <a href="{{ route('shop.index') }}" class="hover:text-[#214E34]">Catalog</a>
                    <span>/</span>
                    <span class="text-[#1D1D1D] font-semibold">{{ $category->name }}</span>
                </div>
                <h1 class="font-editorial-heading text-4xl sm:text-6xl font-light text-[#1D1D1D]">
                    {{ $category->name }}
                </h1>
                <p class="font-editorial-body text-lg text-[#555555] leading-relaxed">
                    {{ $category->description ?? 'Centuries of generational guild mastery, hand-knotted and cast in authentic Bengali ateliers.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Subcategories Pill Ribbon -->
    @if($subcategories->isNotEmpty())
        <div class="bg-[#F8F6F2] border-b border-[#E8E8E8] py-4">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 flex items-center gap-3 overflow-x-auto text-xs">
                <span class="text-[#888888] uppercase font-semibold">Sub-guilds:</span>
                @foreach($subcategories as $sub)
                    <a href="{{ route('category.show', $sub->slug) }}" class="px-4 py-1.5 rounded-full bg-white border border-[#E8E8E8] text-[#555555] hover:text-[#214E34] whitespace-nowrap">
                        {{ $sub->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Category Products Grid -->
    <section class="py-16 max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-4 text-center py-20 space-y-4">
                    <h3 class="font-editorial-heading text-2xl text-[#1D1D1D]">Curators are cataloging new pieces in this guild</h3>
                    <p class="text-sm text-[#888888]">Visit our other traditional crafts in the meantime.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Heritage FAQ Accordion & SEO Content -->
    <section class="py-20 bg-white border-t border-[#E8E8E8]">
        <div class="max-w-4xl mx-auto px-6 lg:px-12 space-y-12">
            <div class="text-center space-y-2">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">Heritage FAQ</span>
                <h2 class="font-editorial-heading text-3xl sm:text-4xl font-light text-[#1D1D1D]">
                    Authenticity & Guild Care
                </h2>
            </div>

            <div class="divide-y divide-[#E8E8E8] text-sm">
                <div class="py-4 space-y-2">
                    <h4 class="font-semibold text-[#1D1D1D]">How is GI provenance verified for {{ $category->name }}?</h4>
                    <p class="text-[#555555] leading-relaxed">
                        Every single piece is inspected and assigned a unique registration number tracking its exact district coordinates and registered artisan guild certificate.
                    </p>
                </div>
                <div class="py-4 space-y-2">
                    <h4 class="font-semibold text-[#1D1D1D]">How should I clean and preserve these natural materials?</h4>
                    <p class="text-[#555555] leading-relaxed">
                        We recommend avoiding harsh industrial chemicals. For handloom silk and muslin, gentle cold rinses with soapnut are recommended. For brass and kansa, natural tamarind wash restores mirror luster.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
