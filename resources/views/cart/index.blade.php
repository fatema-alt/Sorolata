@extends('layouts.storefront')

@section('seo_meta')
    <title>Your Collector Bag — Handcrafted Heirlooms | Sorolata</title>
    <meta name="description" content="Review your curated heirloom bag, request complimentary artisan gift wrapping, and proceed to checkout.">
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mb-8">
            Your Collector Bag
        </h1>

        @if($items->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Left: Cart Items List -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-[32px] p-6 sm:p-8 border border-[#E8E8E8] shadow-soft-xs divide-y divide-[#E8E8E8]">
                        @foreach($items as $item)
                            <div class="py-6 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-center gap-6">
                                <img 
                                    src="{{ $item['product']->media->first()?->file_path ?? 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=300' }}" 
                                    alt="{{ $item['product']->title }}" 
                                    class="w-24 h-24 rounded-[18px] object-cover bg-[#F8F6F2]"
                                >
                                <div class="flex-grow space-y-1 text-center sm:text-left">
                                    <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">
                                        {{ $item['product']->district?->name_en ?? 'Heritage Origin' }}
                                    </span>
                                    <h3 class="font-editorial-heading text-xl text-[#1D1D1D]">
                                        {{ $item['product']->title }}
                                    </h3>
                                    <p class="text-xs text-[#888888]">By {{ $item['product']->artisan?->full_name }}</p>
                                </div>
                                <div class="text-center sm:text-right space-y-2">
                                    <x-price :price="$item['subtotal']" size="md" />
                                    <div class="text-xs text-[#888888]">Qty: {{ $item['quantity'] }}</div>
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-[#B91C1C] hover:underline cursor-pointer">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Gift Note & Packaging Options -->
                    <div class="bg-white rounded-[32px] p-6 sm:p-8 border border-[#E8E8E8] shadow-soft-xs space-y-4">
                        <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-[#214E34] font-bold">
                            <x-icon name="sparkles" class="w-4 h-4 text-[#C96A3D]" />
                            <span>Complimentary Botanical Gift Wrap & Handwritten Letter</span>
                        </div>
                        <p class="text-xs text-[#555555]">
                            Every order is packaged in natural unbleached handmade jute or wooden casing. Include a personal dedication below.
                        </p>
                        <textarea 
                            rows="2" 
                            placeholder="Write your dedication message here..." 
                            class="w-full text-xs p-3 rounded-[18px] border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34]"
                        ></textarea>
                    </div>
                </div>

                <!-- Right: Order Summary Sidebar -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-md space-y-6">
                        <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D] pb-4 border-b border-[#E8E8E8]">
                            Summary
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-[#555555]">
                                <span>Bag Subtotal</span>
                                <x-price :price="$subtotal" size="sm" color="dark" />
                            </div>
                            <div class="flex justify-between text-[#555555]">
                                <span>Insured Express Courier</span>
                                <span class="text-[#2D6A4F] font-semibold">Complimentary</span>
                            </div>
                            <div class="flex justify-between text-[#555555]">
                                <span>Artisan Direct Royalty</span>
                                <span class="text-[#214E34] font-medium">Included ✓</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#E8E8E8] flex justify-between items-baseline">
                            <span class="font-editorial-heading text-lg text-[#1D1D1D]">Total Amount</span>
                            <x-price :price="$subtotal" size="lg" color="terracotta" />
                        </div>

                        <x-button href="{{ route('checkout.index') }}" variant="primary" size="lg" class="w-full justify-center">
                            Proceed to Express Checkout →
                        </x-button>

                        <div class="text-[11px] text-center text-[#888888] space-y-1">
                            <p>🔒 256-bit Bank Grade Security</p>
                            <p>Direct bKash, Nagad, Cards & Global Wire accepted.</p>
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
                    Your Collector Bag is Empty
                </h2>
                <p class="text-sm text-[#555555]">
                    Explore our living archive of Bangladeshi handloom textiles and brassware to find a meaningful heirloom.
                </p>
                <div>
                    <x-button href="{{ route('shop.index') }}" variant="primary" size="md">
                        Explore Curated Catalog
                    </x-button>
                </div>
            </div>
        @endif

    </div>
@endsection
