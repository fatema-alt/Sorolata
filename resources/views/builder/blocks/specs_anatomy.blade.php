<section class="py-20 lg:py-28 bg-[#F8F6F2] border-t border-[#E8E8E8]">
    <div class="max-w-5xl mx-auto px-6 lg:px-12">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('Material Formulation') }}</span>
            <h2 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-2">
                {{ __('Pure Formulation & Metrics') }}
            </h2>
            <p class="text-sm text-[#555555] mt-3">
                {{ __('Transparent composition details. No synthetic polymers. No chemical bleaches.') }}
            </p>
        </div>

        <div class="bg-white rounded-[32px] p-8 sm:p-12 border border-[#E8E8E8] shadow-soft-md divide-y divide-[#E8E8E8]">
            @php
                $defaultSpecs = [
                    'Fiber / Material' => '100% Hand-Ginned Indigenous Cotton / Mulberry Silk',
                    'Weave Density' => '120 × 120 thread count pit-loom configuration',
                    'Dye Formulation' => 'Botanical Madder Root, Pomegranate Rind, Indigo Fermentation',
                    'Crafting Duration' => '180 hours of handloom dedication per single piece',
                    'Dimensions' => '220 cm × 75 cm (Generous Archival Drape)',
                    'Care Instructions' => 'Delicate cold hand rinse with natural soapnut, shade dry',
                ];
                $activeSpecs = !empty($specs) ? array_merge($defaultSpecs, $specs) : $defaultSpecs;
            @endphp

            @foreach($activeSpecs as $label => $value)
                <div class="py-5 flex flex-col sm:flex-row sm:items-center justify-between text-sm gap-2">
                    <span class="font-semibold text-[#1D1D1D] uppercase tracking-wider text-xs sm:w-1/3">{{ __($label) }}</span>
                    <span class="text-[#555555] font-light sm:w-2/3">{{ __($value) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
