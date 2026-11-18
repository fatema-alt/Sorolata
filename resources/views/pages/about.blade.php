@extends('layouts.storefront')

@section('seo_meta')
    <title>Our Manifesto & Mission — The Bengal Artisan Sanctuary | Sorolata</title>
    <meta name="description" content="Sorolata was founded to preserve endangered Bangladeshi handcrafted heritage through direct artisan royalties and uncompromising quality.">
    <link rel="canonical" href="{{ route('pages.about') }}">
@endsection

@section('content')
    <!-- Editorial About Hero -->
    <section class="py-24 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center space-y-6">
            <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('Heritage Manifesto') }}</span>
            <h1 class="font-editorial-heading text-5xl sm:text-7xl font-light text-[#1D1D1D] leading-tight">
                {{ __('Craft as a Sacred Living Continuum') }}
            </h1>
            <p class="font-editorial-body text-xl text-[#555555] font-light leading-relaxed">
                {{ __('We believe that true luxury is not machine precision; it is the patient, deliberate human touch that cannot be replicated by algorithms.') }}
            </p>
        </div>
    </section>

    <!-- Narrative Pillars -->
    <section class="py-20 max-w-5xl mx-auto px-6 lg:px-12 space-y-16">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-4">
                <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('01 • The Extinction Crisis') }}</span>
                <h2 class="font-editorial-heading text-3xl sm:text-4xl font-light text-[#1D1D1D]">
                    {{ __('Rescuing What Power Looms Erased') }}
                </h2>
                <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                    {{ __('Over the past seventy years, synthetic fast fashion and automated industrial mills have decimated ancient artisanal guilds across rural Bangladesh. Generational weavers were forced into urban garment factories because hand craftsmanship lacked dignified international access.') }}
                </p>
            </div>
            <div class="rounded-[32px] overflow-hidden bg-[#F8F6F2] aspect-square shadow-soft-md">
                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=800" alt="Master Weaver at loom" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="rounded-[32px] overflow-hidden bg-[#F8F6F2] aspect-square shadow-soft-md order-2 md:order-1">
                <img src="https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=800" alt="Hand hammered brassware" class="w-full h-full object-cover">
            </div>
            <div class="space-y-4 order-1 md:order-2">
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('02 • Uncompromising Equity') }}</span>
                <h2 class="font-editorial-heading text-3xl sm:text-4xl font-light text-[#1D1D1D]">
                    {{ __('Direct Guild Royalties') }}
                </h2>
                <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                    {{ __("Sorolata operates on an unwavering principle: the artisan is the primary stakeholder. We eliminate exploitative middlemen traders, guarantee dignified upfront advances, and deposit contractually bound royalties on every acquisition directly to the artisan's guild fund.") }}
                </p>
            </div>
        </div>

    </section>
@endsection
