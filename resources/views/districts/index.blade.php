@extends('layouts.storefront')

@section('seo_meta')
    <title>Heritage Districts of Bengal — 64 District Provenance Hub | Sorolata</title>
    <meta name="description" content="Explore traditional crafts, Geographical Indications, and master artisan clusters across every district of Bangladesh.">
    <link rel="canonical" href="{{ route('districts.index') }}">
@endsection

@section('content')
    <section class="py-16 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Geographical Provenance</span>
            <h1 class="font-editorial-heading text-4xl sm:text-6xl font-light text-[#1D1D1D]">
                Districts of Bengal
            </h1>
            <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
                From the humid morning river mist of Sonargaon to the bell-metal foundries of Bogura—discover how regional microclimates and soil minerals define our living arts.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($districts as $district)
                <a href="{{ route('district.show', $district->slug ?? $district->uuid) }}" class="block">
                    <x-district-card :district="$district" />
                </a>
            @endforeach
        </div>
    </div>
@endsection
