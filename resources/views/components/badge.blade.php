@props([
    'variant' => 'subtle', // primary, terracotta, district, material, discount, success, subtle
    'size' => 'sm',
])

@php
    $sizeClasses = match($size) {
        'xs' => 'text-[10px] px-2 py-0.5 tracking-wider uppercase font-semibold',
        'md' => 'text-xs px-3.5 py-1.5 font-medium tracking-wide',
        default => 'text-[11px] px-2.5 py-1 font-medium tracking-wide',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-[#214E34] text-white',
        'terracotta' => 'bg-[#C96A3D] text-white',
        'district' => 'bg-[#EAE5DE] text-[#214E34] border border-[#214E34]/15',
        'material' => 'bg-[#F2ECE4] text-[#6E645F] border border-[#E8E8E8]',
        'discount' => 'bg-[#C96A3D]/10 text-[#C96A3D] border border-[#C96A3D]/25 font-bold',
        'success' => 'bg-[#2D6A4F]/10 text-[#2D6A4F] border border-[#2D6A4F]/25',
        default => 'bg-[#E8E8E8] text-[#555555]',
    };

    $classes = "inline-flex items-center gap-1.5 rounded-full {$sizeClasses} {$variantClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
