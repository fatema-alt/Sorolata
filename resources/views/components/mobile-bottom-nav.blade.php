@php
    $bagCount = count(session('sorolata_collector_bag', []));
    $wishlistCount = count(session('sorolata_wishlist', []));
    $isBn = app()->getLocale() === 'bn';
@endphp
<style>
    .soro-mobile-bnav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 50;
        background: #FBF6EC;
        border-top: 1px solid #D9C28A;
        box-shadow: 0 -6px 24px rgba(43, 33, 24, 0.12);
        display: flex;
        align-items: center;
        justify-content: flex-start;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 6px 6px calc(6px + env(safe-area-inset-bottom, 0px));
        gap: 2px;
        box-sizing: border-box;
    }
    .soro-mobile-bnav::-webkit-scrollbar { display: none; }
    .soro-mobile-bnav a {
        flex: 0 0 auto;
        min-width: 58px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 4px 4px;
        text-decoration: none;
        font-size: 10px;
        line-height: 1.15;
        position: relative;
        color: #5A5044;
    }
    .soro-mobile-bnav a svg {
        width: 18px;
        height: 18px;
    }
    .soro-mobile-bnav a.is-active {
        color: #1F4D3A;
        font-weight: 700;
    }
    @media (min-width: 768px) {
        .soro-mobile-bnav { display: none !important; }
    }
</style>
<nav aria-label="Mobile Navigation" class="soro-mobile-bnav">
    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5z"/></svg>
        <span>{{ __('Home') }}</span>
    </a>

    <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14l-1.5 12h-11L5 7z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
        <span>{{ __('Shop') }}</span>
    </a>

    <a href="{{ route('districts.index') }}" class="{{ request()->routeIs('districts.*') || request()->routeIs('district.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-6-5.3-6-10a6 6 0 1 1 12 0c0 4.7-6 10-6 10z"/><circle cx="12" cy="11" r="2.2"/></svg>
        <span>{{ __('Districts') }}</span>
    </a>

    <a href="{{ route('artisans.index') }}" class="{{ request()->routeIs('artisans.*') || request()->routeIs('artisan.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>{{ __('Makers') }}</span>
    </a>

    <a href="{{ route('stories.index') }}" class="{{ request()->routeIs('stories.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>{{ __('Stories') }}</span>
    </a>

    <a href="{{ route('pages.about') }}" class="{{ request()->routeIs('pages.about') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        <span>{{ __('About') }}</span>
    </a>

    <a href="{{ route('pages.contact') }}" class="{{ request()->routeIs('pages.contact') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>{{ __('Contact') }}</span>
    </a>

    <a href="{{ route('search.index') }}" class="{{ request()->routeIs('search.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
        <span>{{ __('Search') }}</span>
    </a>

    <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        @if($wishlistCount > 0)
            <span style="position:absolute; top:1px; right:12px; min-width:15px; height:15px; padding:0 3px; background:#B8553A; color:#fff; border-radius:999px; font-size:9px; font-weight:700; display:flex; align-items:center; justify-content:center">{{ $wishlistCount }}</span>
        @endif
        <span>{{ __('Wishlist') }}</span>
    </a>

    <a href="{{ auth()->check() ? route('account.index') : route('login') }}" class="{{ (request()->routeIs('account.*') || request()->routeIs('login') || request()->routeIs('register')) ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>{{ auth()->check() ? __('Account') : __('Sign In') }}</span>
    </a>

    <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'is-active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14l-1.5 12h-11L5 7z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
        @if($bagCount > 0)
            <span style="position:absolute; top:1px; right:12px; min-width:15px; height:15px; padding:0 3px; background:#B8553A; color:#fff; border-radius:999px; font-size:9px; font-weight:700; display:flex; align-items:center; justify-content:center">{{ $bagCount }}</span>
        @endif
        <span>{{ __('Bag') }}</span>
    </a>

    <a href="{{ route('language.switch', $isBn ? 'en' : 'bn') }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3.6 9h16.8"/><path d="M3.6 15h16.8"/><path d="M12 3a15.3 15.3 0 0 1 4 9 15.3 15.3 0 0 1-4 9 15.3 15.3 0 0 1-4-9 15.3 15.3 0 0 1 4-9z"/></svg>
        <span>{{ $isBn ? 'EN' : 'বাং' }}</span>
    </a>
</nav>
