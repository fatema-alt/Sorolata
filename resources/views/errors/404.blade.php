@extends('layouts.storefront')

@section('seo_meta')
    <title>404 — Artifact Not Found | Sorolata</title>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto px-6 lg:px-12 py-28 text-center space-y-6">
        <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('Uncharted Domain') }}</span>
        <h1 class="font-editorial-heading text-6xl sm:text-8xl font-light text-[#1D1D1D]">
            404
        </h1>
        <h2 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
            {{ __('This Thread Has Drawn to a Close') }}
        </h2>
        <p class="font-editorial-body text-base text-[#555555] leading-relaxed">
            {{ __('The archival piece or story you are searching for may have been archived, transferred to a private collector, or moved to a new loom catalog.') }}
        </p>

        <div class="pt-6 flex flex-wrap items-center justify-center gap-4">
            <x-button href="{{ url('/') }}" variant="primary" size="md">
                {{ __('Return to Living Sanctuary') }}
            </x-button>
            <x-button href="{{ route('shop.index') }}" variant="secondary" size="md">
                {{ __('Browse Complete Archive') }}
            </x-button>
        </div>
    </div>
@endsection
