@props([
    'price',
    'compareAtPrice' => null,
    'currency' => 'BDT',
    'size' => 'md', // sm, md, lg, xl
    'color' => 'terracotta', // terracotta, primary, dark
])

@php
    $symbol = match($currency) {
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        default => '৳',
    };

    $formattedCurrent = is_numeric($price) 
        ? $symbol . ' ' . number_format((float) $price, $currency === 'BDT' ? 0 : 2)
        : $price;

    $formattedCompare = $compareAtPrice && is_numeric($compareAtPrice)
        ? $symbol . ' ' . number_format((float) $compareAtPrice, $currency === 'BDT' ? 0 : 2)
        : $compareAtPrice;

    $priceSizeClass = match($size) {
        'sm' => 'text-sm font-semibold',
        'lg' => 'text-2xl font-serif font-normal',
        'xl' => 'text-4xl sm:text-5xl font-serif font-light',
        default => 'text-lg font-serif font-medium',
    };

    $colorClass = match($color) {
        'primary' => 'text-[#214E34]',
        'dark' => 'text-[#1D1D1D]',
        default => 'text-[#C96A3D]',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-baseline gap-2']) }}>
    <span class="{{ $priceSizeClass }} {{ $colorClass }} tracking-tight">
        {{ $formattedCurrent }}
    </span>
    @if($formattedCompare)
        <span class="text-xs text-[#888888] line-through font-sans">
            {{ $formattedCompare }}
        </span>
    @endif
</div>
