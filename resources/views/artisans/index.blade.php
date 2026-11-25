@extends('layouts.storefront')

@section('seo_meta')
    <title>Master Artisans & Ateliers of Bengal | Sorolata</title>
    <meta name="description" content="Meet the master craftsmen preserving centuries of handloom weaving, lost-wax bell metal casting, and folk arts in Bangladesh.">
    <link rel="canonical" href="{{ route('artisans.index') }}">
@endsection

@section('content')
    <x-heritage-header
        :eyebrow="__('Our Makers')"
        :title="__('Meet the hands behind every piece')"
        :subtitle="__('We believe the maker is just as important as the artifact. Discover the generational guild masters keeping Bengal\'s living cultural flame alive.')"
        :breadcrumb="__('Our Makers')"
    />

    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($artisans as $artisan)
                <article class="text-center group">
                    <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" class="block text-decoration-none">
                        <div style="border:1.5px solid #B8892F; border-radius:999px 999px 16px 16px; padding:7px">
                            <div style="position:relative; height:310px; border-radius:999px 999px 10px 10px; overflow:hidden; background:#F0E6D0">
                                <img src="{{ $artisan->portrait_url }}" alt="{{ $artisan->getLocalized('full_name') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            </div>
                        </div>
                        <h3 class="mt-4 mb-0.5 font-editorial-heading font-semibold text-2xl text-[#1F4D3A] group-hover:text-[#8F3F26] transition-colors">{{ $artisan->getLocalized('full_name') }}</h3>
                        <div class="text-sm font-semibold text-[#2B2118]">{{ $artisan->craft_type->title() }}</div>
                        <div class="text-xs text-[#5A5044] mt-0.5">{{ $artisan->district?->getLocalized('name') ?? $artisan->getLocalized('atelier_name') }} · {{ __(':count years of work', ['count' => $artisan->years_of_mastery]) }}</div>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
@endsection
