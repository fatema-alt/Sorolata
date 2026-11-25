@extends('layouts.storefront')

@section('seo_meta')
    <title>Heritage Districts of Bengal — 64 District Provenance Hub | Sorolata</title>
    <meta name="description" content="Explore traditional crafts, Geographical Indications, and master artisan clusters across every district of Bangladesh.">
    <link rel="canonical" href="{{ route('districts.index') }}">
@endsection

@section('content')
    <x-heritage-header
        :eyebrow="__('Shop by District')"
        :title="__('Districts of Bengal')"
        :subtitle="__('From the humid morning river mist of Sonargaon to the bell-metal foundries of Bogura—discover how regional microclimates and soil minerals define our living arts.')"
        :breadcrumb="__('Districts')"
        :showBand="true"
    />

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
