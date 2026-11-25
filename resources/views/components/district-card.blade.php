@props([
    'district',
])

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden bg-white border border-[#E3D8BC] rounded-[18px] p-6 hover:border-[#B8892F] hover:shadow-md transition-all flex flex-col justify-between space-y-4']) }}>
    <svg aria-hidden="true" width="56" height="56" viewBox="0 0 56 56" style="position:absolute; right:0; top:0; color:#B8892F; opacity:0.75; pointer-events:none"><use href="#cornerArt"></use></svg>
    <div class="pr-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] uppercase tracking-[0.14em] text-[#8F3F26] font-bold">
                {{ $district->division->value }}
            </span>
        </div>
        
        <h4 class="font-editorial-heading text-2xl font-semibold text-[#1F4D3A] group-hover:text-[#8F3F26] transition-colors">
            {{ $district->name_en }}
        </h4>
        
        <span class="text-sm font-editorial-bangla text-[#5A5044] block mt-0.5">
            {{ $district->name_bn }}
        </span>
    </div>

    <div class="pt-3 border-t border-[#E3D8BC]/70">
        <span class="text-[10px] uppercase tracking-widest text-[#8F3F26] font-bold block mb-1">{{ __('GI Specialty') }}</span>
        <p class="text-sm text-[#5A5044] line-clamp-2 leading-relaxed">
            {{ $district->getLocalized('gi_specialty') ?? __('Heritage Artisan Cradle') }}
        </p>
    </div>

    <div class="pt-1 flex items-center justify-between text-sm text-[#8F3F26] font-bold">
        <span>{{ __('See products') }}</span>
        <span class="transform group-hover:translate-x-1 transition-transform">→</span>
    </div>
</div>
