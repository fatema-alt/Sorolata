<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no" class="scroll-smooth antialiased notranslate">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo_meta')

    <!-- Fonts: Cormorant Garamond (Heading), Manrope (Body), Hind Siliguri (Bangla) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Hind+Siliguri:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Dynamic Tracking, GTM, Meta Pixel & DataLayer (PDF Spec) -->
    @include('components.analytics.tracking-head')
</head>
<body 
    x-data="{ 
        scrolled: false, 
        megaMenu: false, 
        mobileMenu: false, 
        showTop: false 
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 20); showTop = (window.pageYOffset > 400)"
    class="bg-[#F8F6F2] text-[#555555] font-editorial-body min-h-screen flex flex-col selection:bg-[#DE7D4E]/20 selection:text-[#214E34]"
>

    <!-- Tracking NoScript & Body Injections -->
    @include('components.analytics.tracking-body')

    <!-- 1. Announcement Bar -->
    <aside class="bg-[#214E34] text-[#F8F6F2] text-xs py-2 px-4 text-center tracking-widest uppercase font-medium flex items-center justify-center gap-4">
        <span>{{ __('GI Certified Bengal Craftsmanship') }}</span>
        <span class="hidden sm:inline opacity-40">•</span>
        <span class="hidden sm:inline">{{ __('Complimentary Insured Courier to BD, USA, UK, Canada & Europe') }}</span>
    </aside>

        <!-- 2. Transparent Floating Glass Navbar with Mega Menu -->
    <header 
        class="sticky top-0 z-50 transition-luxury"
        :class="scrolled ? 'sorolata-glass shadow-soft-xs' : 'bg-[#F8F6F2]/90 backdrop-blur-md border-b border-[#E8E8E8]/70'"
    >
        <div class="w-full max-w-[1536px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 h-20 flex items-center justify-between gap-4 lg:gap-8">
            
            <!-- Left: Wordmark & Heritage Seal -->
            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ url('/') }}" class="group flex items-center space-x-3 shrink-0">
                    <span class="font-editorial-heading text-3xl sm:text-4xl font-normal tracking-tight text-[#1D1D1D] group-hover:text-[#214E34] transition-colors whitespace-nowrap">
                        Sorolata
                    </span>
                    <span class="text-[10px] tracking-widest uppercase px-2.5 py-0.5 rounded-full border border-[#214E34]/20 text-[#214E34] font-semibold bg-[#214E34]/5 whitespace-nowrap">
                        সারলতা
                    </span>
                </a>
            </div>

            <!-- Center: Editorial Navigation & Mega Menu Trigger -->
            <nav class="hidden lg:flex items-center space-x-6 xl:space-x-8 text-sm tracking-wide text-[#555555] shrink-0">
                <a href="{{ route('shop.index') }}" class="hover:text-[#214E34] font-medium transition-colors whitespace-nowrap">
                    {{ __('Shop Catalog') }}
                </a>
                
                <!-- Mega Menu Dropdown Trigger -->
                <div class="relative shrink-0" @mouseenter="megaMenu = true" @mouseleave="megaMenu = false">
                    <button type="button" class="inline-flex items-center gap-1 hover:text-[#214E34] font-medium transition-colors py-2 cursor-pointer whitespace-nowrap">
                        <span>{{ __('Heritage Curations') }}</span>
                        <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                    </button>

                    <!-- Mega Menu Glass Panel -->
                    <div 
                        x-show="megaMenu"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute -left-20 top-full w-[640px] sorolata-glass-white rounded-[28px] p-8 shadow-soft-lg border border-[#E8E8E8] grid grid-cols-3 gap-8 z-50"
                        style="display: none;"
                    >
                        <!-- Col 1: Categories -->
                        <div class="space-y-3">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-[#214E34]">{{ __('Traditional Guilds') }}</h4>
                            <ul class="text-xs space-y-2 text-[#555555]">
                                <li><a href="{{ route('shop.index', ['category' => 'heritage-handloom-textiles']) }}" class="hover:text-[#214E34] transition-colors">{{ __('Handloom Textiles') }}</a></li>
                                <li><a href="{{ route('shop.index', ['category' => 'bell-metal-brassware']) }}" class="hover:text-[#214E34] transition-colors">{{ __('Bell-Metal & Kansa') }}</a></li>
                                <li><a href="{{ route('shop.index', ['category' => 'traditional-rugs-carpets']) }}" class="hover:text-[#214E34] transition-colors">{{ __('Shatranji Carpets') }}</a></li>
                                <li><a href="{{ route('shop.index') }}" class="text-[#C96A3D] font-semibold hover:underline block pt-1">{{ __('All Guilds →') }}</a></li>
                            </ul>
                        </div>

                        <!-- Col 2: Districts -->
                        <div class="space-y-3">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-[#214E34]">{{ __('Geographical Hubs') }}</h4>
                            <ul class="text-xs space-y-2 text-[#555555]">
                                <li><a href="{{ route('district.show', 'narayanganj-sonargaon') }}" class="hover:text-[#214E34] transition-colors">{{ __('Narayanganj (Sonargaon)') }}</a></li>
                                <li><a href="{{ route('district.show', 'tangail') }}" class="hover:text-[#214E34] transition-colors">{{ __('Tangail Handloom') }}</a></li>
                                <li><a href="{{ route('district.show', 'bogura') }}" class="hover:text-[#214E34] transition-colors">{{ __('Bogura Brass Foundries') }}</a></li>
                                <li><a href="{{ route('district.show', 'rangpur-nisbetganj') }}" class="hover:text-[#214E34] transition-colors">{{ __('Rangpur Shatranji Guild') }}</a></li>
                                <li><a href="{{ route('districts.index') }}" class="text-[#214E34] font-semibold hover:underline block pt-1">{{ __('All 64 Districts →') }}</a></li>
                            </ul>
                        </div>

                        <!-- Col 3: Artisans & Stories -->
                        <div class="space-y-3">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-[#214E34]">{{ __('Human Provenance') }}</h4>
                            <ul class="text-xs space-y-2 text-[#555555]">
                                <li><a href="{{ route('artisans.index') }}" class="hover:text-[#214E34] transition-colors">{{ __('Master Artisans') }}</a></li>
                                <li><a href="{{ route('stories.index') }}" class="hover:text-[#214E34] transition-colors">{{ __('The Heritage Gazette') }}</a></li>
                                <li><a href="{{ route('pages.about') }}" class="hover:text-[#214E34] transition-colors">{{ __('Our Living Manifesto') }}</a></li>
                                <li><a href="{{ route('pages.contact') }}" class="hover:text-[#214E34] transition-colors">{{ __('Curator Concierge') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <a href="{{ route('districts.index') }}" class="hover:text-[#214E34] font-medium transition-colors whitespace-nowrap">{{ __('Districts') }}</a>
                <a href="{{ route('artisans.index') }}" class="hover:text-[#214E34] font-medium transition-colors whitespace-nowrap">{{ __('Master Artisans') }}</a>
                <a href="{{ route('stories.index') }}" class="hover:text-[#214E34] font-medium transition-colors flex items-center gap-1.5 whitespace-nowrap">
                    <span>{{ __('Stories') }}</span>
                    <span class="text-[9px] uppercase tracking-widest px-1.5 py-0.5 rounded bg-[#C96A3D]/10 text-[#C96A3D] font-bold whitespace-nowrap">
                        {{ __('Magazine') }}
                    </span>
                </a>
            </nav>

            <!-- Right: Search, Language Switcher, Account, Wishlist & Cart -->
            <div class="flex items-center space-x-3 sm:space-x-4 shrink-0">
                <!-- Bilingual Language Switcher (EN | বাংলা) -->
                <div class="flex items-center text-xs font-semibold rounded-full border border-[#D8D3C7] bg-[#ECE8DF] p-0.5 shrink-0" title="{{ __('Language') }}">
                    <a href="{{ route('language.switch', 'en') }}" 
                       class="px-2 sm:px-2.5 py-1 rounded-full transition-all text-[11px] {{ app()->getLocale() === 'en' ? 'bg-[#214E34] text-white shadow-xs' : 'text-[#666666] hover:text-[#1D1D1D]' }}">
                        EN
                    </a>
                    <a href="{{ route('language.switch', 'bn') }}" 
                       class="px-2 sm:px-2.5 py-1 rounded-full transition-all text-[11px] {{ app()->getLocale() === 'bn' ? 'bg-[#214E34] text-white shadow-xs' : 'text-[#666666] hover:text-[#1D1D1D]' }}">
                        বাং
                    </a>
                </div>

                <!-- Floating Glass Search -->
                <x-floating-search />

                <!-- Customer Profile Dropdown Section -->
                <div class="relative shrink-0" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                    @auth
                        <!-- Logged-in Button -->
                        <button 
                            type="button" 
                            @click="profileOpen = !profileOpen"
                            class="flex items-center gap-2 py-1.5 px-3 rounded-full bg-[#ECE8DF] hover:bg-[#E2DDD3] border border-[#D8D3C7] text-[#1D1D1D] transition-all text-xs font-semibold cursor-pointer group"
                            aria-label="{{ __('Customer Profile') }}"
                        >
                            <span class="w-6 h-6 rounded-full bg-[#214E34] text-white flex items-center justify-center font-bold text-[11px] shrink-0 uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="hidden md:inline max-w-[110px] truncate">{{ auth()->user()->name }}</span>
                            <x-icon name="chevron-down" class="w-3 h-3 text-gray-500 group-hover:text-gray-800 transition-transform duration-200" x-bind:class="profileOpen ? 'rotate-180' : ''" />
                        </button>

                        <!-- Logged-in Profile Menu -->
                        <div 
                            x-show="profileOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                            class="absolute right-0 top-full mt-2 w-64 bg-white/95 backdrop-blur-md rounded-2xl p-2 shadow-soft-lg border border-[#E8E8E8] z-50 text-xs space-y-1"
                            style="display: none;"
                        >
                            <!-- User Header Card -->
                            <div class="px-3 py-2.5 bg-[#F8F6F2] rounded-xl border border-[#ECE8DF] space-y-0.5">
                                <div class="font-bold text-gray-900 truncate">{{ auth()->user()->name }}</div>
                                <div class="text-[11px] text-gray-500 truncate font-mono">{{ auth()->user()->email ?? auth()->user()->phone }}</div>
                                <div class="pt-1">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                        {{ auth()->user()->isAdmin() ? __('Admin') : __('Collector') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Links -->
                            <div class="py-1">
                                <a href="{{ route('account.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:bg-[#F8F6F2] hover:text-[#214E34] transition font-medium">
                                    <svg class="w-4 h-4 text-[#214E34]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>{{ __('My Orders') }}</span>
                                </a>
                                <a href="{{ route('track.order') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:bg-[#F8F6F2] hover:text-[#214E34] transition font-medium">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('Track Order') }}</span>
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:bg-[#F8F6F2] hover:text-[#C96A3D] transition font-medium">
                                    <svg class="w-4 h-4 text-[#C96A3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    <span>{{ __('Saved Wishlist') }}</span>
                                </a>
                                <a href="{{ route('account.index') }}#profile" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-gray-700 hover:bg-[#F8F6F2] hover:text-[#214E34] transition font-medium">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>{{ __('Account Settings') }}</span>
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-amber-900 bg-amber-50 hover:bg-amber-100 transition font-bold">
                                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>{{ __('Admin Studio') }}</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Logout Button -->
                            <div class="border-t border-gray-100 pt-1">
                                <form method="POST" action="{{ route('customer.logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 transition font-medium cursor-pointer text-left">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span>{{ __('Sign Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <!-- Guest Button -->
                        <button 
                            type="button" 
                            @click="profileOpen = !profileOpen"
                            class="flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-[#ECE8DF] hover:bg-[#E2DDD3] border border-[#D8D3C7] text-[#1D1D1D] hover:text-[#214E34] transition-all text-xs font-semibold cursor-pointer group"
                            aria-label="{{ __('Customer Profile') }}"
                        >
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-gray-700 shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="hidden sm:inline">{{ __('Account') }}</span>
                            <x-icon name="chevron-down" class="w-3 h-3 text-gray-500 group-hover:text-gray-800 transition-transform duration-200" x-bind:class="profileOpen ? 'rotate-180' : ''" />
                        </button>

                        <!-- Guest Dropdown Menu -->
                        <div 
                            x-show="profileOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                            class="absolute right-0 top-full mt-2 w-64 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-soft-lg border border-[#E8E8E8] z-50 text-xs space-y-3"
                            style="display: none;"
                        >
                            <div class="space-y-1">
                                <div class="font-bold text-gray-900 text-sm">{{ __('Welcome to Sorolata') }}</div>
                                <p class="text-[11px] text-gray-500 leading-relaxed">{{ __('Sign in to view orders, track packages, and curate your heritage wishlist.') }}</p>
                            </div>

                            <div class="space-y-2 pt-1">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center py-2.5 px-4 rounded-xl bg-[#214E34] hover:bg-[#183926] text-white font-bold text-xs transition shadow-xs">
                                    {{ __('Sign In') }}
                                </a>
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center py-2.5 px-4 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-800 font-semibold text-xs transition">
                                    {{ __('Register New Account') }}
                                </a>
                            </div>

                            <div class="border-t border-gray-100 pt-2">
                                <a href="{{ route('track.order') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#214E34] text-[11px] font-medium py-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('Track Order (Without Login)') }}</span>
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Wishlist Trigger -->
                <a href="{{ route('wishlist.index') }}" class="relative hidden sm:flex p-2 text-[#555555] hover:text-[#C96A3D] transition-colors shrink-0" aria-label="{{ __('Wishlist') }}">
                    <x-icon name="heart" class="w-5 h-5" />
                    @php
                        $wishlistCount = count(session('sorolata_wishlist', []));
                    @endphp
                    @if($wishlistCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 text-[10px] bg-[#C96A3D] text-white rounded-full flex items-center justify-center font-bold">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>

                <!-- Cart / Bag Trigger -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-[#1D1D1D] hover:text-[#214E34] transition-colors shrink-0" aria-label="{{ __('Collector Bag') }}">
                    <x-icon name="shopping-bag" class="w-5 h-5" />
                    @php
                        $bagCount = count(session('sorolata_collector_bag', []));
                    @endphp
                    @if($bagCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 text-[10px] bg-[#C96A3D] text-white rounded-full flex items-center justify-center font-bold">
                            {{ $bagCount }}
                        </span>
                    @endif
                </a>
            </div>

        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow pb-16 md:pb-0">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Dock (Thumb-friendly) -->
    <x-mobile-bottom-nav />

    <!-- Global Luxury Floating Concierge & Contact Widget -->
    <x-floating-contact />

    <!-- Back to Top Floating Button -->
    <button 
        x-show="showTop"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-75"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-75"
        class="fixed bottom-20 md:bottom-8 left-6 z-40 bg-white/90 backdrop-blur-md text-[#1D1D1D] p-3 rounded-full shadow-soft-md border border-[#E8E8E8] hover:bg-[#214E34] hover:text-white transition-luxury cursor-pointer"
        aria-label="{{ __('Back to Top') }}"
        style="display: none;"
    >
        <x-icon name="chevron-down" class="w-4 h-4 transform rotate-180" />
    </button>

    <!-- 16. Editorial Footer -->
    <footer class="bg-[#1D1D1D] text-[#F8F6F2] border-t border-[#333333] pt-20 pb-16 mt-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
            
            <!-- Col 1: Brand Story & Mission -->
            <div class="md:col-span-5 space-y-4">
                <span class="font-editorial-heading text-4xl font-light text-[#F8F6F2]">Sorolata</span>
                <p class="text-sm text-[#AAAAAA] leading-relaxed max-w-md font-editorial-body">
                    {{ __('A timeless sanctuary celebrating Bangladeshi handcrafted heritage. We preserve endangered living traditions by bringing master artisan artifacts directly to thoughtful collectors worldwide.') }}
                </p>
                <div class="flex flex-wrap gap-3 pt-2 text-xs text-[#E8E8E8]">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15">
                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-[#C96A3D]" />
                        {{ __('100% Verified GI Provenance') }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15">
                        <x-icon name="sparkles" class="w-3.5 h-3.5 text-[#C96A3D]" />
                        {{ __('Direct Atelier Compensation') }}
                    </span>
                </div>
            </div>

            <!-- Col 2: Heritage Clusters -->
            <div class="md:col-span-2 space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-[#F8F6F2]">{{ __('Heritage Districts') }}</h4>
                <ul class="text-sm space-y-2 text-[#AAAAAA]">
                    <li><a href="{{ route('district.show', 'narayanganj-sonargaon') }}" class="hover:text-white transition-colors">{{ __('Sonargaon (Muslin)') }}</a></li>
                    <li><a href="{{ route('district.show', 'tangail') }}" class="hover:text-white transition-colors">{{ __('Tangail (Jamdani Silk)') }}</a></li>
                    <li><a href="{{ route('district.show', 'bogura') }}" class="hover:text-white transition-colors">{{ __('Bogura (Bell Metal)') }}</a></li>
                    <li><a href="{{ route('district.show', 'rangpur-nisbetganj') }}" class="hover:text-white transition-colors">{{ __('Rangpur (Shatranji)') }}</a></li>
                    <li><a href="{{ route('districts.index') }}" class="hover:text-white transition-colors">{{ __('All 64 Districts') }}</a></li>
                </ul>
            </div>

            <!-- Col 3: Collections & Support -->
            <div class="md:col-span-2 space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-[#F8F6F2]">{{ __('Quick Links') }}</h4>
                <ul class="text-sm space-y-2 text-[#AAAAAA]">
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">{{ __('Curated Catalog') }}</a></li>
                    <li><a href="{{ route('artisans.index') }}" class="hover:text-white transition-colors">{{ __('Master Artisans') }}</a></li>
                    <li><a href="{{ route('stories.index') }}" class="hover:text-white transition-colors">{{ __('The Heritage Gazette') }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-white transition-colors">{{ __('Our Manifesto') }}</a></li>
                    <li><a href="{{ route('track.order') }}" class="hover:text-white transition-colors">{{ __('Track Order') }}</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-white transition-colors">{{ __('Concierge Desk') }}</a></li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div class="md:col-span-3 space-y-4">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-[#F8F6F2]">{{ __("The Collector's Desk") }}</h4>
                <p class="text-xs text-[#AAAAAA] leading-relaxed">
                    {{ __('Receive intimate notifications when a master craftsman finishes a limited archival drop.') }}
                </p>
                <form class="space-y-3">
                    <div class="relative">
                        <input 
                            type="email" 
                            placeholder="{{ __('Enter your email address...') }}" 
                            class="w-full bg-white/10 border border-white/20 text-xs px-4 py-3 text-white placeholder-white/50 rounded-full focus:outline-none focus:border-[#C96A3D] transition-colors"
                        >
                    </div>
                    <x-button variant="terracotta" size="sm" class="w-full justify-center">
                        {{ __('Subscribe to Collector Dispatches') }}
                    </x-button>
                </form>
            </div>

        </div>

        <!-- Bottom Copyright & Payment Trust -->
        <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-[#888888] gap-4">
            <p>{{ __('© :year Sorolata Commerce Platform. All rights reserved.', ['year' => date('Y')]) }}</p>
            <div class="flex items-center gap-3 text-[11px] text-[#AAAAAA]">
                <span>{{ __('Secured with bKash Direct') }}</span>
                <span>•</span>
                <span>{{ __('Nagad') }}</span>
                <span>•</span>
                <span>{{ __('Visa / MC') }}</span>
                <span>•</span>
                <span>{{ __('Stripe Global') }}</span>
            </div>
            <p class="font-editorial-heading italic text-sm text-[#AAAAAA]">{{ __('Crafted with quiet reverence for human touch.') }}</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
