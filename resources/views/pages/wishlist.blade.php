@extends('layouts.storefront')

@section('seo_meta')
    <title>Saved Heirlooms — Your Wishlist | Sorolata</title>
    <meta name="description" content="View your saved Bengali handloom textiles, brassware, and artisan crafts.">
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16 space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-[#E8E8E8] pb-6 gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('Curator Board') }}</span>
                <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-1">
                    {{ __('Your Saved Heirlooms') }}
                </h1>
            </div>
            <div class="flex gap-3">
                <x-button variant="secondary" size="sm" onclick="navigator.clipboard.writeText(window.location.href); alert(@json(__('Wishlist link copied to clipboard!'), JSON_UNESCAPED_UNICODE));">
                    {{ __('Share Board 🔗') }}
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <span class="text-xl">✨</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($products->isEmpty())
            <div class="py-20 text-center rounded-[32px] border border-dashed border-[#DFD8CA] bg-white/60 p-8 space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#F5F2EB] flex items-center justify-center text-3xl">
                    🤍
                </div>
                <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">{{ __('Your Wishlist is currently empty') }}</h3>
                <p class="text-xs text-[#666666] max-w-md mx-auto">
                    {{ __('Save your favorite items from our master artisans across Dhakai Jamdani, brassware, and living heritage crafts.') }}
                </p>
                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#214E34] hover:bg-[#163824] text-white text-xs font-semibold tracking-wide transition-all shadow-md">
                        <span>{{ __('Explore Heritage Catalog') }}</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif

    </div>
@endsection
