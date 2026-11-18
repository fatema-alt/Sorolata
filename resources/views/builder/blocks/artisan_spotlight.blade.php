<section id="artisan-section" class="py-20 lg:py-28 bg-[#F8F6F2] border-y border-[#E8E8E8]">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

            <!-- Artisan Portrait / Studio Shot -->
            <div class="lg:col-span-5 order-2 lg:order-1">
                <div class="relative rounded-[32px] overflow-hidden bg-white aspect-[4/5] shadow-soft-lg">
                    @php
                        $portrait = $artisan?->portrait_url ?? 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1000&auto=format&fit=crop';
                    @endphp
                    <img 
                        src="{{ $portrait }}" 
                        alt="{{ $artisan?->full_name }}" 
                        class="w-full h-full object-cover grayscale contrast-125 hover:grayscale-0 transition-all duration-700"
                        loading="lazy"
                    >
                    <div class="absolute bottom-6 left-6 right-6 bg-[#1D1D1D]/80 backdrop-blur-md p-5 rounded-[24px] text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase tracking-widest text-[#AAAAAA]">{{ __('Atelier') }}</span>
                                <h4 class="font-editorial-heading text-xl font-light">{{ $artisan?->atelier_name ?? __('Bengal Heritage Loom') }}</h4>
                            </div>
                            <span class="text-xs px-3 py-1 bg-[#214E34] text-white rounded-full font-medium">
                                {{ __(':years Years Mastery', ['years' => $artisan?->years_of_mastery ?? 35]) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artisan Biographical Narrative -->
            <div class="lg:col-span-7 order-1 lg:order-2 space-y-6">
                <div class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-[#C96A3D] font-bold">
                    <span class="w-2 h-2 rounded-full bg-[#C96A3D]"></span>
                    <span>{{ __('The Master Artisan Behind This Piece') }}</span>
                </div>

                <h2 class="font-editorial-heading text-3xl sm:text-4xl lg:text-5xl font-light text-[#1D1D1D] leading-tight">
                    {{ $artisan?->full_name ?? __('Master Craftsman of Bengal') }}
                </h2>

                <p class="text-xs uppercase tracking-widest text-[#888888]">
                    {{ __('Specialty:') }} {{ $artisan?->craft_type?->title() ?? __('Heritage Handloom Weaving') }} • {{ $district?->name_en ?? __('Tangail') }}, {{ __('Bangladesh') }}
                </p>

                <!-- Editorial Pull Quote -->
                @if(!empty($artisan?->quote))
                    <blockquote class="pl-6 border-l-2 border-[#C96A3D] italic font-editorial-heading text-xl sm:text-2xl text-[#1D1D1D] my-6 leading-relaxed">
                        "{{ $artisan->quote }}"
                    </blockquote>
                @endif

                <div class="text-[#555555] text-base leading-relaxed space-y-4 font-editorial-body">
                    <p>
                        {{ $artisan?->biography ?? __('Carrying forward an uninterrupted lineage of artisan mastery. Every piece represents weeks of patient handcrafting using indigenous techniques passed down through generations of guild masters.') }}
                    </p>
                </div>

                <!-- Provenance & Fair Trade Verification -->
                <div class="pt-6 grid grid-cols-2 sm:grid-cols-3 gap-6 border-t border-[#E8E8E8] text-xs">
                    <div>
                        <span class="block text-[#888888] uppercase tracking-wider text-[10px]">{{ __('Artisan Royalty') }}</span>
                        <span class="font-medium text-[#1D1D1D]">{{ __('Direct Atelier Compensation') }}</span>
                    </div>
                    <div>
                        <span class="block text-[#888888] uppercase tracking-wider text-[10px]">{{ __('Verification') }}</span>
                        <span class="font-medium text-[#2D6A4F]">{{ __('Verified Heritage Guild ✓') }}</span>
                    </div>
                    <div>
                        <span class="block text-[#888888] uppercase tracking-wider text-[10px]">{{ __('Origin Tag') }}</span>
                        <span class="font-medium text-[#1D1D1D]">{{ __('100% Traceable Lot') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
