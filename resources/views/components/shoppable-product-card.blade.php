@props(['product'])

@php
    $locale = app()->getLocale();
    $title = $product->getLocalized('title');
    $subtitle = $product->getLocalized('subtitle');
    $artisan = $product->artisan;
    $artisanName = $artisan ? $artisan->getLocalized('full_name') : null;
    $district = $product->district;
    $districtName = $district ? ($locale === 'bn' ? $district->name_bn : $district->name_en) : null;
    $image = $product->media->first()?->url ?? 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600';
@endphp

<div x-data="{ 
        adding: false, 
        added: false,
        buttonText: '{{ __('Add to Bag') }}',
        addToCart() {
            this.adding = true;
            fetch('{{ route('cart.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: {{ $product->id }}, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                this.adding = false;
                this.added = true;
                this.buttonText = '✓ {{ $locale === 'bn' ? 'ঝুলিতে যোগ হয়েছে' : 'Added to Bag' }}';
                window.dispatchEvent(new CustomEvent('cart-count-updated', { detail: { count: data.cart_count } }));
                setTimeout(() => {
                    this.added = false;
                    this.buttonText = '{{ __('Add to Bag') }}';
                }, 3000);
            })
            .catch(err => {
                this.adding = false;
                alert('Something went wrong. Please try again.');
            });
        }
     }"
     class="my-8 not-prose rounded-2xl sm:rounded-3xl border border-[#E8E4DC] bg-[#FAF8F5] p-4 sm:p-6 shadow-sm hover:shadow-md transition-all duration-300">
    
    <div class="flex flex-col sm:flex-row gap-5 items-center">
        <!-- Product Thumbnail with Hover Zoom -->
        <a href="{{ route('products.show', $product->slug) }}" class="relative shrink-0 w-full sm:w-44 aspect-square rounded-xl sm:rounded-2xl overflow-hidden bg-[#ECE8DF] group">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @if($product->free_shipping_eligible)
                <span class="absolute top-2 left-2 text-[10px] tracking-wider uppercase font-semibold bg-[#214E34]/90 text-white px-2 py-0.5 rounded-full backdrop-blur-sm">
                    {{ __('Free Shipping') }}
                </span>
            @endif
        </a>

        <!-- Product Meta & Story Context -->
        <div class="flex-1 w-full space-y-2 text-left">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                @if($districtName)
                    <span class="font-medium text-[#C96A3D] uppercase tracking-wider text-[11px] bg-[#C96A3D]/10 px-2.5 py-0.5 rounded-full">
                        {{ $districtName }}
                    </span>
                @endif
                @if($product->district?->gi_specialty)
                    <span class="text-[#214E34] text-[10px] font-semibold tracking-widest uppercase bg-[#214E34]/10 px-2 py-0.5 rounded-full border border-[#214E34]/20">
                        GI CERTIFIED
                    </span>
                @endif
                <span class="text-xs text-[#888888]">•</span>
                <span class="text-xs text-[#214E34] font-medium flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                    {{ __('In Stock') }}
                </span>
            </div>

            <!-- Title -->
            <h3 class="font-editorial-heading text-xl sm:text-2xl font-normal text-[#1D1D1D] hover:text-[#214E34] transition-colors leading-snug">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $title }}
                </a>
            </h3>

            <!-- Artisan Credit -->
            @if($artisanName)
                <p class="text-xs sm:text-sm text-[#666666]">
                    {{ $locale === 'bn' ? 'মাস্টার কারিগর:' : 'Master Artisan:' }}
                    <strong class="text-[#1D1D1D]">{{ $artisanName }}</strong>
                    @if($artisan->district)
                        <span class="text-[#888888]">({{ $locale === 'bn' ? $artisan->district->name_bn : $artisan->district->name_en }})</span>
                    @endif
                </p>
            @endif

            <!-- Price & Actions Row -->
            <div class="pt-2 flex flex-wrap items-center justify-between gap-4 border-t border-[#EAE6DE]">
                <div class="flex items-baseline gap-2">
                    <span class="font-editorial-heading text-2xl font-medium text-[#1D1D1D]">
                        ৳ {{ number_format((float) $product->price) }}
                    </span>
                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                        <span class="text-xs text-[#999999] line-through">
                            ৳ {{ number_format((float) $product->compare_at_price) }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <!-- Instant Add to Bag Button -->
                    <button type="button"
                            @click="addToCart()"
                            :disabled="adding || added"
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 px-5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider text-white transition-all shadow-sm active:scale-95"
                            :class="added ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-[#214E34] hover:bg-[#1a3f2a]'">
                        <template x-if="adding">
                            <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!adding && !added">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </template>
                        <span x-text="buttonText"></span>
                    </button>

                    <!-- View Details Link -->
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="px-3.5 py-2.5 rounded-xl border border-[#D5CFBF] hover:border-[#1D1D1D] bg-white text-xs font-medium text-[#1D1D1D] transition-colors whitespace-nowrap"
                       title="{{ __('View Full Details') }}">
                        {{ __('View Full Details') }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
