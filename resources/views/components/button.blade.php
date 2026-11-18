@props([
    'variant' => 'primary', // primary, secondary, ghost, text, terracotta, sticky
    'size' => 'md',        // sm, md, lg
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
    'disabled' => false,
    'loading' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-luxury focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none cursor-pointer';

    $sizeClasses = match($size) {
        'sm' => 'text-xs px-4 py-2 gap-1.5 rounded-full',
        'lg' => 'text-base px-8 py-4 gap-2.5 rounded-full',
        default => 'text-sm px-6 py-3 gap-2 rounded-full',
    };

    $variantClasses = match($variant) {
        'secondary' => 'border border-[#214E34] text-[#214E34] bg-transparent hover:bg-[#214E34]/5 focus:ring-[#214E34]',
        'terracotta' => 'bg-[#C96A3D] text-white hover:bg-[#B0582E] shadow-soft-xs hover:shadow-soft-md focus:ring-[#C96A3D]',
        'ghost' => 'text-[#1D1D1D] bg-transparent hover:bg-[#1D1D1D]/5 focus:ring-[#1D1D1D]',
        'text' => 'text-[#214E34] bg-transparent hover:text-[#173825] p-0 underline underline-offset-4 focus:ring-0',
        'sticky' => 'bg-[#214E34] text-white shadow-soft-lg hover:shadow-soft-hover hover:scale-[1.02] focus:ring-[#214E34]',
        default => 'bg-[#214E34] text-white hover:bg-[#173825] shadow-soft-xs hover:shadow-soft-md focus:ring-[#214E34]',
    };

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-icon :name="$icon" class="w-4 h-4" />
        @endif
        <span>{{ $slot }}</span>
        @if($iconRight)
            <x-icon :name="$iconRight" class="w-4 h-4" />
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon)
            <x-icon :name="$icon" class="w-4 h-4" />
        @endif
        <span>{{ $slot }}</span>
        @if($iconRight)
            <x-icon :name="$iconRight" class="w-4 h-4" />
        @endif
    </button>
@endif
