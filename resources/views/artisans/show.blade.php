@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $artisan->getLocalized('full_name') }} — Master Artisan Profile | Sorolata</title>
    <meta name="description" content="{{ $artisan->getLocalized('biography') ?? 'Explore the studio profile and handcrafted artifacts by ' . $artisan->getLocalized('full_name') }}">
    <link rel="canonical" href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}">
@endsection

@section('content')
    <!-- Artisan Studio Cover Banner -->
    <section class="relative py-24 bg-[#214E34] text-[#F8F6F2] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10 flex flex-col md:flex-row items-center gap-12">
            <div class="w-36 h-36 sm:w-44 sm:h-44 rounded-full overflow-hidden border-4 border-white/20 shadow-soft-lg flex-shrink-0">
                <img src="{{ $artisan->portrait_url }}" alt="{{ $artisan->getLocalized('full_name') }}" class="w-full h-full object-cover">
            </div>
            <div class="space-y-3 text-center md:text-left">
                <span class="inline-block text-xs uppercase tracking-widest text-[#DE7D4E] font-bold">
                    {{ $artisan->craft_type->title() }} • {{ $artisan->district?->getLocalized('name') }}
                </span>
                <h1 class="font-editorial-heading text-4xl sm:text-6xl font-light text-[#F8F6F2]">
                    {{ $artisan->getLocalized('full_name') }}
                </h1>
                <p class="text-sm text-[#F8F6F2]/80 font-light">
                    {{ __('Atelier:') }} <strong class="text-white">{{ $artisan->getLocalized('atelier_name') }}</strong> • {{ __(':count Years Generational Mastery', ['count' => $artisan->years_of_mastery]) }}
                </p>
            </div>
        </div>
    </section>

    <!-- Artisan Biography & Pull Quote -->
    <section class="py-20 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-4xl mx-auto px-6 lg:px-12 space-y-8">
            @if($artisan->getLocalized('quote'))
                <blockquote class="font-editorial-heading text-2xl sm:text-3xl italic text-[#1D1D1D] text-center font-light leading-relaxed">
                    "{{ $artisan->getLocalized('quote') }}"
                </blockquote>
            @endif

            <div class="font-editorial-body text-base text-[#555555] leading-relaxed space-y-4">
                <p>{{ $artisan->getLocalized('biography') }}</p>
            </div>

            <!-- Awards & Recognitions -->
            @if(!empty($artisan->awards))
                <div class="pt-6 border-t border-[#E8E8E8] space-y-3">
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-[#1D1D1D]">{{ __('Honors & Guild Recognitions') }}</h4>
                    <ul class="text-xs text-[#555555] space-y-1.5 list-disc list-inside">
                        @foreach($artisan->awards as $award)
                            <li>{{ $award }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    <!-- Artifacts by this Master Artisan -->
    <section class="py-20 max-w-7xl mx-auto px-6 lg:px-12 space-y-8">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('Atelier Catalogue') }}</span>
            <h2 class="font-editorial-heading text-3xl sm:text-4xl font-light text-[#1D1D1D]">
                {{ __('Pieces Handcrafted by :artisan', ['artisan' => $artisan->getLocalized('full_name')]) }}
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-4 text-center py-16 text-[#888888]">
                    {{ __(':artisan is currently working on the next loom cycle.', ['artisan' => $artisan->getLocalized('full_name')]) }}
                </div>
            @endforelse
        </div>
    </section>
@endsection
