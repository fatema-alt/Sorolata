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
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        align-items: center;
        padding: 6px 2px calc(6px + env(safe-area-inset-bottom, 0px));
        box-sizing: border-box;
    }
    .soro-mobile-bnav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 4px 1px;
        text-decoration: none;
        font-size: 9.5px;
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        color: #5A5044;
    }
    .soro-mobile-bnav a svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5z"/></svg>
        <span>{{ __('Home') }}</span>
    </a>

    <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14l-1.5 12h-11L5 7z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
        <span>{{ __('Shop') }}</span>
    </a>

    <a href="{{ route('districts.index') }}" class="{{ request()->routeIs('districts.*') || request()->routeIs('district.*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-6-5.3-6-10a6 6 0 1 1 12 0c0 4.7-6 10-6 10z"/><circle cx="12" cy="11" r="2.2"/></svg>
        <span>{{ __('Districts') }}</span>
    </a>

    <a href="{{ route('artisans.index') }}" class="{{ request()->routeIs('artisans.*') || request()->routeIs('artisan.*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>{{ __('Makers') }}</span>
    </a>

    <a href="{{ route('stories.index') }}" class="{{ request()->routeIs('stories.*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>{{ __('Stories') }}</span>
    </a>

    <a href="{{ route('pages.about') }}" class="{{ request()->routeIs('pages.about') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        <span>{{ __('About') }}</span>
    </a>

    <a href="{{ route('pages.contact') }}" class="{{ request()->routeIs('pages.contact') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>{{ __('Contact') }}</span>
    </a>
</nav>
