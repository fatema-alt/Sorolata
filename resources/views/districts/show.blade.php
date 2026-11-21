@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $district->getLocalized('name') }} — Heritage GI Crafts & Master Artisans | Sorolata</title>
    <meta name="description" content="{{ $district->getLocalized('historical_narrative') ?? 'Discover heritage crafts and master artisans rooted in ' . $district->getLocalized('name') }}">
    <link rel="canonical" href="{{ route('district.show', $district->slug ?? $district->uuid) }}">
@endsection

@section('content')
    <!-- District Hero -->
    <section class="py-20 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="max-w-3xl space-y-4">
                <div class="flex items-center gap-2 text-xs text-[#888888] uppercase tracking-wider">
                    <a href="{{ url('/') }}" class="hover:text-[#214E34]">{{ __('Home') }}</a>
                    <span>/</span>
                    <a href="{{ route('districts.index') }}" class="hover:text-[#214E34]">{{ __('Districts') }}</a>
                    <span>/</span>
                    <span class="text-[#1D1D1D] font-semibold">{{ $district->getLocalized('name') }}</span>
                </div>
                <div class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-[#214E34] font-bold">
                    <span>{{ $district->division instanceof \BackedEnum ? $district->division->value : ($district->division ?? 'Bangladesh') }}</span>
                    <span>•</span>
                    <span class="font-editorial-bangla">{{ $district->name_bn }}</span>
                </div>
                <h1 class="font-editorial-heading text-4xl sm:text-6xl font-light text-[#1D1D1D]">
                    {{ $district->getLocalized('name') }}
                </h1>
                <p class="font-editorial-body text-lg text-[#555555] leading-relaxed">
                    {{ $district->getLocalized('historical_narrative') }}
                </p>
                <div class="pt-2 text-xs text-[#888888]">
                    <span>{{ __('River Basin:') }} <strong class="text-[#1D1D1D]">{{ $district->getLocalized('river_basin') ?? __('Bengal Floodplain') }}</strong></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Artisans in this District -->
    @if($artisans->isNotEmpty())
        <section class="py-16 bg-[#F8F6F2] border-b border-[#E8E8E8]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 space-y-8">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('Living Pedigree') }}</span>
                    <h2 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                        {{ __('Master Artisans of :district', ['district' => $district->getLocalized('name')]) }}
                    </h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($artisans as $artisan)
                        <div class="bg-white rounded-[32px] p-6 shadow-soft-xs flex items-center gap-5 border border-[#E8E8E8]">
                            <img src="{{ $artisan->portrait_url }}" alt="{{ $artisan->getLocalized('full_name') }}" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <h4 class="font-editorial-heading text-xl text-[#1D1D1D]">{{ $artisan->getLocalized('full_name') }}</h4>
                                <span class="text-xs text-[#888888] block">{{ $artisan->craft_type instanceof \App\Enums\ArtisanCraftType ? $artisan->craft_type->title() : ($artisan->craft_type ?? 'Master Artisan') }}</span>
                                <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" class="text-xs text-[#214E34] font-semibold mt-1 inline-block">{{ __('View Studio Profile →') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Artifacts from this District -->
    <section class="py-20 max-w-7xl mx-auto px-6 lg:px-12 space-y-8">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('GI Certified') }}</span>
            <h2 class="font-editorial-heading text-3xl sm:text-4xl font-light text-[#1D1D1D]">
                {{ __('Pieces Originating from :district', ['district' => $district->getLocalized('name')]) }}
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-4 text-center py-16 text-[#888888]">
                    {{ __('New archival lots currently being woven in :district looms.', ['district' => $district->getLocalized('name')]) }}
                </div>
            @endforelse
        </div>
    </section>
@endsection
