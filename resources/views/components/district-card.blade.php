@props([
    'district',
])

<div {{ $attributes->merge(['class' => 'group relative bg-white rounded-[32px] p-6 shadow-soft-xs hover:shadow-soft-hover transition-luxury flex flex-col justify-between space-y-4 border border-[#E8E8E8]/60']) }}>
    <div>
        <div class="flex items-center justify-between mb-3">
            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">
                {{ $district->division->value }}
            </span>
            <x-icon name="map-pin" class="w-4 h-4 text-[#C96A3D]" />
        </div>
        
        <h4 class="font-editorial-heading text-2xl font-light text-[#1D1D1D] group-hover:text-[#214E34] transition-colors">
            {{ $district->name_en }}
        </h4>
        
        <span class="text-xs font-editorial-bangla text-[#888888] italic block mt-0.5">
            {{ $district->name_bn }}
        </span>
    </div>

    <div class="pt-3 border-t border-[#E8E8E8]">
        <span class="text-[10px] uppercase tracking-widest text-[#888888] block mb-1">GI Specialty</span>
        <p class="text-xs text-[#555555] line-clamp-2 leading-relaxed">
            {{ $district->gi_specialty ?? 'Heritage Artisan Cradle' }}
        </p>
    </div>

    <div class="pt-1 flex items-center justify-between text-xs text-[#214E34] font-semibold">
        <span>Explore District Artisans</span>
        <span class="transform group-hover:translate-x-1 transition-transform">→</span>
    </div>
</div>
