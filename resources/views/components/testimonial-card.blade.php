@props([
    'quote',
    'author',
    'location',
    'verified' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-[32px] p-8 shadow-soft-xs hover:shadow-soft-md transition-luxury flex flex-col justify-between border border-[#E8E8E8]/70 space-y-6']) }}>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <x-rating rating="5.0" size="xs" />
            @if($verified)
                <span class="inline-flex items-center gap-1 text-[10px] text-[#2D6A4F] font-semibold uppercase tracking-wider">
                    <x-icon name="shield-check" class="w-3.5 h-3.5 text-[#2D6A4F]" />
                    Verified Collector
                </span>
            @endif
        </div>
        
        <blockquote class="font-editorial-heading text-xl font-light text-[#1D1D1D] leading-relaxed italic">
            "{{ $quote }}"
        </blockquote>
    </div>

    <div class="pt-4 border-t border-[#E8E8E8]">
        <h5 class="text-sm font-semibold text-[#1D1D1D]">{{ $author }}</h5>
        <span class="text-xs text-[#888888]">{{ $location }}</span>
    </div>
</div>
