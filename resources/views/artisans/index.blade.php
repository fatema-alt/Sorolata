@extends('layouts.storefront')

@section('seo_meta')
    <title>Master Artisans & Ateliers of Bengal | Sorolata</title>
    <meta name="description" content="Meet the master craftsmen preserving centuries of handloom weaving, lost-wax bell metal casting, and folk arts in Bangladesh.">
    <link rel="canonical" href="{{ route('artisans.index') }}">
@endsection

@section('content')
    <section class="py-16 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('Human Provenance') }}</span>
            <h1 class="font-editorial-heading text-4xl sm:text-6xl font-light text-[#1D1D1D]">
                {{ __('The Master Artisans') }}
            </h1>
            <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                {{ __("We believe the maker is just as important as the artifact. Discover the generational guild masters keeping Bengal's living cultural flame alive.") }}
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($artisans as $artisan)
                <div class="bg-white rounded-[32px] p-6 shadow-soft-xs hover:shadow-soft-hover transition-luxury flex flex-col justify-between space-y-4 border border-[#E8E8E8]">
                    <div class="aspect-square rounded-[24px] overflow-hidden bg-[#F8F6F2]">
                        <img src="{{ $artisan->portrait_url }}" alt="{{ $artisan->getLocalized('full_name') }}" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">{{ $artisan->craft_type->title() }}</span>
                        <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">{{ $artisan->getLocalized('full_name') }}</h3>
                        <p class="text-xs text-[#888888]">{{ $artisan->getLocalized('atelier_name') }} • {{ $artisan->district?->getLocalized('name') }}</p>
                    </div>
                    <div class="pt-2 border-t border-[#E8E8E8] flex items-center justify-between text-xs">
                        <span class="text-[#888888]">{{ __(':count Years Mastery', ['count' => $artisan->years_of_mastery]) }}</span>
                        <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" class="text-[#214E34] font-semibold hover:underline">{{ __('Studio Profile →') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
