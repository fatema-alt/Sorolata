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

<article {{ $attributes->merge(['class' => 'group relative bg-white border border-[#E3D8BC] rounded-[18px] overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between']) }}>
    
    <!-- Top Visual Stage -->
    <div class="relative w-full aspect-square overflow-hidden bg-[#F0E6D0]">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $product->getLocalized('title') }}" 
                class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out"
                loading="lazy"
            >
        </a>
        <div aria-hidden="true" class="pointer-events-none absolute inset-[10px] border border-white/60 rounded-[10px]"></div>

        <!-- Floating Badges: Out of Stock & Discount -->
        <div class="absolute top-4 right-4 flex flex-col items-end gap-1.5 z-10 pointer-events-none">
            @if($isOutOfStock)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#2B2118]/90 text-[#FBF6EC] tracking-wider uppercase">
                    {{ __('Sold Out') }}
                </span>
            @elseif($hasDiscount)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#B8553A] text-white">
                    {{ __(':percent% off', ['percent' => $discountPercent]) }}
                </span>
            @endif
        </div>

        <!-- Floating Action: Wishlist Icon -->
        @php
            $isWishlisted = in_array($product->id, session('sorolata_wishlist', []));
        @endphp
        <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-4 left-4 z-20">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button 
                type="submit" 
                aria-label="{{ $isWishlisted ? __('Remove from Wishlist') : __('Save to Wishlist') }}" 
                class="w-9 h-9 rounded-full {{ $isWishlisted ? 'bg-[#B8553A] text-white shadow-md' : 'bg-[#FBF6EC]/90 text-[#5A5044]' }} hover:text-white hover:bg-[#B8553A] flex items-center justify-center border border-[#D9C28A] transition-all cursor-pointer"
                title="{{ $isWishlisted ? __('Remove from Wishlist') : __('Save to Wishlist') }}"
            >
                <x-icon name="heart" class="w-4 h-4 {{ $isWishlisted ? 'fill-current' : '' }}" />
            </button>
        </form>
    </div>

    <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>

    <!-- Product Meta Info -->
    <div class="p-3.5 flex flex-col gap-1 flex-1 justify-between">
        <div class="space-y-1">
            <div class="text-[10px] font-bold tracking-[0.1em] uppercase text-[#5A5044] truncate">
                {{ $districtName }}
            </div>

            <h3 class="font-editorial-heading text-lg font-semibold leading-snug text-[#1F4D3A] group-hover:text-[#8F3F26] transition-colors line-clamp-1">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->getLocalized('title') }}
                </a>
            </h3>

            <p class="text-xs text-[#5A5044] truncate">
                {{ __('Made by') }} {{ $product->artisan?->getLocalized('full_name') ?? __('Master Artisan Guild') }}
            </p>
        </div>

        <div class="pt-2 space-y-2">
            <x-price 
                :price="$product->price" 
                :compareAtPrice="$product->compare_at_price" 
                :currency="$product->currency->value"
                size="sm"
            />
            <a href="{{ route('products.show', $product->slug) }}" class="w-full inline-flex items-center justify-center rounded-full bg-[#1F4D3A] hover:bg-[#163829] text-[#FBF6EC] font-semibold text-xs py-2 px-4 transition-colors">
                {{ __('View Piece →') }}
            </a>
        </div>
    </div>

</article>
