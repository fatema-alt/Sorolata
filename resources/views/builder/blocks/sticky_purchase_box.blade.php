<section id="purchase-section" class="py-20 lg:py-28 bg-[#F8F6F2] border-t border-[#E8E8E8]">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">
        <div class="bg-white rounded-[32px] p-8 sm:p-14 border border-[#E8E8E8] shadow-soft-lg space-y-8">
            
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between border-b border-[#E8E8E8] pb-8 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ !empty($settings['badge_text']) ? $settings['badge_text'] : __('Immediate Acquisition') }}</span>
                    <h2 class="font-editorial-heading text-3xl sm:text-4xl text-[#1D1D1D] mt-1">{{ !empty($settings['headline']) ? $settings['headline'] : $product->getLocalized('title') }}</h2>
                    <p class="text-xs text-[#888888] mt-1">{{ !empty($settings['subheadline']) ? $settings['subheadline'] : __('Single Batch Archive Edition • Hand-inspected by Chief Curator') }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="font-editorial-heading text-4xl sm:text-5xl font-normal text-[#C96A3D]">
                        {{ $product->formatted_price }}
                    </span>
                    @if($product->formatted_compare_price)
                        <span class="block text-sm line-through text-[#888888] mt-1">{{ $product->formatted_compare_price }}</span>
                    @endif
                </div>
            </div>

            <!-- Delivery & Concierge Perks -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs text-[#555555]">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full bg-[#214E34]/10 flex items-center justify-center flex-shrink-0 text-[#214E34]">
                        ✓
                    </div>
                    <div>
                        <span class="font-semibold text-[#1D1D1D] block">{{ __('Insured Courier') }}</span>
                        <span>{{ __('Delivered in tamper-evident wooden or botanical box.') }}</span>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full bg-[#214E34]/10 flex items-center justify-center flex-shrink-0 text-[#214E34]">
                        ✓
                    </div>
                    <div>
                        <span class="font-semibold text-[#1D1D1D] block">{{ __('Artisan Royalty') }}</span>
                        <span>{{ __('Fair remuneration contractually deposited to artisan guild.') }}</span>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full bg-[#214E34]/10 flex items-center justify-center flex-shrink-0 text-[#214E34]">
                        ✓
                    </div>
                    <div>
                        <span class="font-semibold text-[#1D1D1D] block">{{ __('Provenance Certificate') }}</span>
                        <span>{{ __('Numbered certificate signed by the craftsman and guild leader.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Add to Collector Bag Form -->
            <form action="{{ route('cart.store') }}" method="POST" class="pt-4 space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="sm:w-28 flex items-center border border-[#E8E8E8] rounded-full px-4 py-3 bg-[#F8F6F2] justify-between">
                        <span class="text-xs text-[#888888] uppercase">{{ __('Qty') }}</span>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-12 text-center text-sm font-semibold text-[#1D1D1D] focus:outline-none bg-transparent">
                    </div>

                    <button type="submit" class="flex-grow py-4 px-8 bg-[#214E34] hover:bg-[#173825] text-white rounded-full font-medium tracking-wide text-sm transition-luxury shadow-soft-xs hover:shadow-soft-md flex items-center justify-center space-x-3 cursor-pointer">
                        <span>{{ !empty($settings['primary_cta_text']) ? $settings['primary_cta_text'] : __('Acquire Piece & Add To Collector Bag') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>

                <!-- Instant payment reassurance -->
                <div class="flex items-center justify-center space-x-4 pt-3 text-[11px] text-[#888888]">
                    <span>{{ __('Accepted Payments:') }}</span>
                    <span class="font-medium text-[#1D1D1D]">{{ __('bKash Direct') }}</span>
                    <span>•</span>
                    <span class="font-medium text-[#1D1D1D]">{{ __('Nagad') }}</span>
                    <span>•</span>
                    <span class="font-medium text-[#1D1D1D]">{{ __('Visa / MasterCard') }}</span>
                    <span>•</span>
                    <span class="font-medium text-[#1D1D1D]">{{ __('Amex') }}</span>
                    <span>•</span>
                    <span class="font-medium text-[#1D1D1D]">{{ __('Global Wire') }}</span>
                </div>
            </form>

        </div>
    </div>
</section>
