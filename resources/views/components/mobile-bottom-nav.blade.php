@php
    $bagCount = count(session('sorolata_collector_bag', []));
    $wishlistCount = count(session('sorolata_wishlist', []));
@endphp
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 sorolata-glass border-t border-[#E8E8E8] px-4 py-2 flex items-center justify-around shadow-soft-lg">
    <a href="{{ route('storefront.home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('storefront.home') ? 'text-[#214E34] font-bold' : 'text-[#555555]' }} text-[10px] py-1">
        <x-icon name="sparkles" class="w-5 h-5" />
        <span>হোম</span>
    </a>
    
    <a href="{{ route('shop.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('shop.index') ? 'text-[#214E34] font-bold' : 'text-[#555555]' }} text-[10px] py-1 transition-colors">
        <x-icon name="shopping-bag" class="w-5 h-5" />
        <span>শপ</span>
    </a>

    <a href="{{ route('wishlist.index') }}" class="relative flex flex-col items-center gap-1 {{ request()->routeIs('wishlist.index') ? 'text-[#C96A3D] font-bold' : 'text-[#555555]' }} text-[10px] py-1 transition-colors">
        <x-icon name="heart" class="w-5 h-5" />
        @if($wishlistCount > 0)
            <span class="absolute top-0 right-1 w-3.5 h-3.5 bg-[#C96A3D] text-white rounded-full text-[8px] flex items-center justify-center font-bold">{{ $wishlistCount }}</span>
        @endif
        <span>উইশলিস্ট</span>
    </a>

    <a href="{{ auth()->check() ? route('account.index') : route('login') }}" class="flex flex-col items-center gap-1 {{ (request()->routeIs('account.*') || request()->routeIs('login') || request()->routeIs('register')) ? 'text-[#214E34] font-bold' : 'text-[#555555]' }} text-[10px] py-1 transition-colors">
        <x-icon name="user" class="w-5 h-5" />
        <span>{{ auth()->check() ? 'একাউন্ট' : 'লগইন' }}</span>
    </a>

    <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center gap-1 {{ request()->routeIs('cart.index') ? 'text-[#214E34] font-bold' : 'text-[#555555]' }} text-[10px] py-1 transition-colors">
        <x-icon name="shopping-bag" class="w-5 h-5" />
        @if($bagCount > 0)
            <span class="absolute top-0 right-1 w-3.5 h-3.5 bg-[#C96A3D] text-white rounded-full text-[8px] flex items-center justify-center font-bold">{{ $bagCount }}</span>
        @endif
        <span>ব্যাগ</span>
    </a>
</nav>
