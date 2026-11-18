<section id="block-{{ $block->id }}" class="relative pt-12 pb-20 lg:pt-16 lg:pb-28 overflow-hidden bg-[#F8F6F2]">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Editorial Text Block -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Heritage Badge -->
                <x-badge variant="district" size="md">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C96A3D]"></span>
                    <span>{{ $settings['badge_text'] ?? __('GI Certified Heritage Artifact') }}</span>
                </x-badge>

                <!-- Product Title & Subtitle -->
                <h1 class="font-editorial-heading text-4xl sm:text-5xl lg:text-6xl font-light text-[#1D1D1D] leading-[1.12]">
                    {{ $product->getLocalized('title') }}
                </h1>

                @if(!empty($product->getLocalized('subtitle')))
                    <p class="font-editorial-body text-lg text-[#555555] font-light leading-relaxed">
                        {{ $product->getLocalized('subtitle') }}
                    </p>
                @endif

                <!-- Provenance Summary -->
                <div class="pt-4 border-t border-[#E8E8E8] grid grid-cols-2 gap-4 text-xs text-[#555555]">
                    <div>
                        <span class="block uppercase tracking-wider text-[10px] text-[#888888]">{{ __('Master Artisan') }}</span>
                        <span class="font-medium text-[#1D1D1D]">{{ $artisan?->getLocalized('full_name') ?? __('Generational Guild') }}</span>
                    </div>
                    <div>
                        <span class="block uppercase tracking-wider text-[10px] text-[#888888]">{{ __('Origin & District') }}</span>
                        <span class="font-medium text-[#1D1D1D]">{{ $district?->getLocalized('name') ?? __('Bengal Heritage Basin') }}</span>
                    </div>
                </div>

                <!-- Quick Action CTA -->
                <div class="pt-4 flex items-center space-x-6">
                    <x-button href="#purchase-section" variant="primary" size="lg">
                        {{ __('Acquire Piece • :price', ['price' => $product->formatted_price]) }}
                    </x-button>
                    <a href="#artisan-section" class="text-xs uppercase tracking-widest text-[#555555] hover:text-[#214E34] underline underline-offset-4 transition-colors font-medium">
                        {{ __('Read Atelier Story ↓') }}
                    </a>
                </div>
            </div>

            <!-- Right Hero Photography Column -->
            <div class="lg:col-span-7">
                <div class="relative rounded-[32px] overflow-hidden bg-white shadow-soft-lg aspect-[4/3] lg:aspect-[16/11]">
                    @php
                        $primaryMedia = $product->media->first();
                        $imageUrl = $primaryMedia ? url($primaryMedia->file_path) : 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop';
                    @endphp
                    <img 
                        src="{{ $imageUrl }}" 
                        alt="{{ $product->getLocalized('title') }}" 
                        class="w-full h-full object-cover object-center transform hover:scale-105 transition-transform duration-700 ease-out"
                        loading="eager"
                        fetchpriority="high"
                    >
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[11px] text-[#1D1D1D] font-medium tracking-wide shadow-soft-xs">
                        {{ __('Original Heritage Photography') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
