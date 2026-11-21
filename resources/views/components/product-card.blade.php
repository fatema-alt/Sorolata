@props([
    'product',
])

@php
    $primaryMedia = $product->media->first();
    $imageUrl = $primaryMedia ? url($primaryMedia->file_path) : 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1000&auto=format&fit=crop';
    $hasDiscount = !empty($product->compare_at_price) && $product->compare_at_price > $product->price;
    $discountPercent = $hasDiscount ? round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) : 0;
    $districtName = $product->district?->name_en ?? __('Heritage District');
    $craftTitle = $product->artisan?->craft_type?->title() ?? __('Heritage Handcraft');
    $isOutOfStock = ! $product->is_in_stock || $product->status === 'out_of_stock' || (is_object($product->status) && $product->status->value === 'out_of_stock');
@endphp

<article {{ $attributes->merge(['class' => 'group relative bg-white rounded-[32px] p-4 shadow-soft-xs hover:shadow-soft-hover transition-luxury flex flex-col justify-between']) }}>
    
    <!-- Top Visual Stage -->
    <div class="relative w-full aspect-[4/5] rounded-[24px] overflow-hidden bg-[#F8F6F2]">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $product->getLocalized('title') }}" 
                class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out"
                loading="lazy"
            >
        </a>

        <!-- Floating Badges: Out of Stock, Discount & District -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10 pointer-events-none">
            @if($isOutOfStock)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#1D1D1D]/90 text-white backdrop-blur-xs tracking-wider uppercase">
                    {{ __('Sold Out / সমাপ্ত') }}
                </span>
            @elseif($hasDiscount)
                <x-badge variant="terracotta" size="xs">
                    {{ __(':percent% Off', ['percent' => $discountPercent]) }}
                </x-badge>
            @endif
            <x-badge variant="district" size="xs">
                <x-icon name="map-pin" class="w-3 h-3 text-[#214E34]" />
                <span>{{ $districtName }}</span>
            </x-badge>
        </div>

        <!-- Floating Action: Wishlist Icon -->
        @php
            $isWishlisted = in_array($product->id, session('sorolata_wishlist', []));
        @endphp
        <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-3 right-3 z-20">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button 
                type="submit" 
                aria-label="{{ $isWishlisted ? __('Remove from Wishlist') : __('Save to Wishlist') }}" 
                class="w-9 h-9 rounded-full {{ $isWishlisted ? 'bg-[#C96A3D] text-white shadow-md' : 'bg-white/90 backdrop-blur-md text-[#555555]' }} hover:text-white hover:bg-[#C96A3D] hover:scale-110 flex items-center justify-center shadow-soft-xs transition-luxury"
                title="{{ $isWishlisted ? __('Remove from Wishlist') : __('Save to Wishlist') }}"
            >
                <x-icon name="heart" class="w-4 h-4 {{ $isWishlisted ? 'fill-current' : '' }}" />
            </button>
        </form>

        <!-- Quick View Hover Reveal Dock -->
        <div class="absolute inset-x-3 bottom-3 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-luxury z-10">
            <a 
                href="{{ route('products.show', $product->slug) }}" 
                class="w-full py-2.5 px-4 bg-white/95 backdrop-blur-md hover:bg-[#214E34] text-[#1D1D1D] hover:text-white rounded-full text-xs font-semibold tracking-wide flex items-center justify-center gap-2 shadow-soft-md transition-colors"
            >
                <x-icon name="eye" class="w-3.5 h-3.5" />
                <span>{{ __('Explore Story & Details') }}</span>
            </a>
        </div>
    </div>

    <!-- Product Meta Info -->
    <div class="pt-4 px-2 pb-1 space-y-2.5">
        
        <!-- Header row: Material / Craft Tag & Rating -->
        <div class="flex items-center justify-between text-xs text-[#888888]">
            <span class="tracking-wider uppercase text-[10px] font-medium text-[#214E34]/80">
                {{ $craftTitle }}
            </span>
            <x-rating rating="5.0" count="12" size="xs" />
        </div>

        <!-- Title -->
        <h3 class="font-editorial-heading text-xl sm:text-2xl font-light text-[#1D1D1D] group-hover:text-[#214E34] transition-colors line-clamp-1">
            <a href="{{ route('products.show', $product->slug) }}">
                {{ $product->getLocalized('title') }}
            </a>
        </h3>

        <!-- Artisan Attribution -->
        <p class="text-xs text-[#888888] font-light">
            {{ __('By') }} <span class="text-[#555555] font-medium">{{ $product->artisan?->getLocalized('full_name') ?? __('Master Artisan Guild') }}</span>
        </p>

        <!-- Price & Cart CTA Row -->
        <div class="pt-2 border-t border-[#E8E8E8]/70 flex items-center justify-between">
            <x-price 
                :price="$product->price" 
                :compareAtPrice="$product->compare_at_price" 
                :currency="$product->currency->value"
                size="md"
            />
            
            <a 
                href="{{ route('products.show', $product->slug) }}" 
                class="w-8 h-8 rounded-full bg-[#F8F6F2] hover:bg-[#214E34] text-[#1D1D1D] hover:text-white flex items-center justify-center transition-luxury shadow-soft-xs"
                aria-label="{{ __('Acquire Product') }}"
            >
                <x-icon name="arrow-right" class="w-3.5 h-3.5" />
            </a>
        </div>

    </div>

</article>
