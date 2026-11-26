@extends('layouts.storefront')

@section('seo_meta')
    <title>Your Collector Bag — Handcrafted Heirlooms | Sorolata</title>
    <meta name="description" content="Review your curated heirloom bag, request complimentary artisan gift wrapping, and proceed to checkout.">
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mb-8">
            {{ __('Your Collector Bag') }}
        </h1>

        @if($items->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Left: Cart Items List -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-[32px] p-6 sm:p-8 border border-[#E8E8E8] shadow-soft-xs divide-y divide-[#E8E8E8]">
                        @foreach($items as $item)
                            @php
                                $itemImg = $item['product']->media->first()?->file_path
                                    ? url($item['product']->media->first()->file_path)
                                    : 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=300';
                            @endphp
                            <div class="py-6 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-center gap-6">
                                <a href="{{ route('products.show', $item['product']->slug) }}" class="shrink-0">
                                    <img 
                                        src="{{ $itemImg }}" 
                                        alt="{{ $item['product']->title }}" 
                                        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=300';"
                                        class="w-24 h-24 rounded-[18px] object-cover bg-[#F8F6F2]"
                                    >
                                </a>
                                <div class="flex-grow space-y-1 text-center sm:text-left">
                                    <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">
                                        {{ $item['product']->district?->name_en ?? __('Heritage Origin') }}
                                    </span>
                                    <h3 class="font-editorial-heading text-xl text-[#1D1D1D]">
                                        <a href="{{ route('products.show', $item['product']->slug) }}" class="hover:text-[#1F4D3A]">
                                            {{ $item['product']->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-[#888888]">{{ __('By') }} {{ $item['product']->artisan?->full_name }}</p>
                                </div>
                                <div class="flex flex-col items-center sm:items-end gap-2.5">
                                    <x-price :price="$item['subtotal']" size="md" />
                                    <div style="display:inline-flex; align-items:center; border:1.5px solid #D9C28A; border-radius:999px; background:#FBF6EC; padding:2px 4px; gap:4px">
                                        <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" style="margin:0">
                                            @csrf
                                            <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                                            <button type="submit" aria-label="{{ __('Decrease quantity') }}" style="width:28px; height:28px; border:0; background:none; color:#1F4D3A; font-size:16px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center">−</button>
                                        </form>
                                        <span style="min-width:24px; text-align:center; font-size:13px; font-weight:700; color:#2B2118">{{ $item['quantity'] }}</span>
                                        <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" style="margin:0">
                                            @csrf
                                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                            <button type="submit" aria-label="{{ __('Increase quantity') }}" style="width:28px; height:28px; border:0; background:none; color:#1F4D3A; font-size:16px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center">+</button>
                                        </form>
                                    </div>
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" style="margin:0">
                                        @csrf
                                        <button type="submit" class="text-xs text-[#B91C1C] hover:underline cursor-pointer">{{ __('Remove') }}</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Gift Note & Packaging Options -->
                    <div class="bg-white rounded-[32px] p-6 sm:p-8 border border-[#E8E8E8] shadow-soft-xs space-y-4">
                        <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-[#214E34] font-bold">
                            <x-icon name="sparkles" class="w-4 h-4 text-[#C96A3D]" />
                            <span>{{ __('Complimentary Botanical Gift Wrap & Handwritten Letter') }}</span>
                        </div>
                        <p class="text-xs text-[#555555]">
                            {{ __('Every order is packaged in natural unbleached handmade jute or wooden casing. Include a personal dedication below.') }}
                        </p>
                        <textarea 
                            rows="2" 
                            placeholder="{{ __('Write your dedication message here...') }}" 
                            class="w-full text-xs p-3 rounded-[18px] border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34]"
                        ></textarea>
                    </div>
                </div>

                <!-- Right: Order Summary Sidebar -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-md space-y-6">
                        <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D] pb-4 border-b border-[#E8E8E8]">
                            {{ __('Summary') }}
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-[#555555]">
                                <span>{{ __('Bag Subtotal') }}</span>
                                <x-price :price="$subtotal" size="sm" color="dark" />
                            </div>
                            <div class="flex justify-between text-[#555555]">
                                <span>{{ __('Insured Express Courier') }}</span>
                                <span class="text-[#2D6A4F] font-semibold">{{ __('Complimentary') }}</span>
                            </div>
                            <div class="flex justify-between text-[#555555]">
                                <span>{{ __('Artisan Direct Royalty') }}</span>
                                <span class="text-[#214E34] font-medium">{{ __('Included ✓') }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#E8E8E8] flex justify-between items-baseline">
                            <span class="font-editorial-heading text-lg text-[#1D1D1D]">{{ __('Total Amount') }}</span>
                            <x-price :price="$subtotal" size="lg" color="terracotta" />
                        </div>

                        <x-button href="{{ route('checkout.index') }}" variant="primary" size="lg" class="w-full justify-center">
                            {{ __('Proceed to Express Checkout →') }}
                        </x-button>

                        <div class="text-[11px] text-center text-[#888888] space-y-1">
                            <p>{{ __('🔒 256-bit Bank Grade Security') }}</p>
                            <p>{{ __('Direct bKash, Nagad, Cards & Global Wire accepted.') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-[32px] p-16 text-center max-w-xl mx-auto border border-[#E8E8E8] shadow-soft-xs space-y-6">
                <div class="w-16 h-16 rounded-full bg-[#F8F6F2] text-[#214E34] flex items-center justify-center mx-auto">
                    <x-icon name="shopping-bag" class="w-8 h-8" />
                </div>
                <h2 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                    {{ __('Your Collector Bag is Empty') }}
                </h2>
                <p class="text-sm text-[#555555]">
                    {{ __('Explore our living archive of Bangladeshi handloom textiles and brassware to find a meaningful heirloom.') }}
                </p>
                <div>
                    <x-button href="{{ route('shop.index') }}" variant="primary" size="md">
                        {{ __('Explore Curated Catalog') }}
                    </x-button>
                </div>
            </div>
        @endif

    </div>
@endsection
