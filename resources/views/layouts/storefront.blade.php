<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo_meta')

    <!-- Fonts: Cormorant Garamond (Heading), Manrope (Body), Hind Siliguri (Bangla) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Hind+Siliguri:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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

                <!-- Account / Concierge -->
                @auth
                    <a href="{{ route('account.index') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-[#214E34] border border-emerald-200 transition-colors text-xs font-semibold shrink-0" aria-label="{{ __('Account') }}">
                        <x-icon name="user" class="w-3.5 h-3.5" />
                        <span class="max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-black/5 text-[#555555] hover:text-[#214E34] transition-colors text-xs font-medium shrink-0" aria-label="{{ __('Sign In') }}">
                        <x-icon name="user" class="w-4 h-4" />
                        <span>{{ __('Sign In') }}</span>
                    </a>
                @endauth

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

    <!-- Global Floating WhatsApp Concierge Widget -->
    <a 
        href="https://wa.me/8801700000000" 
        target="_blank" 
        rel="noopener"
        class="fixed bottom-20 md:bottom-8 right-6 z-40 bg-[#25D366] text-white p-3.5 rounded-full shadow-soft-lg hover:scale-110 transition-luxury flex items-center gap-2 group"
        aria-label="{{ __('WhatsApp Concierge') }}"
    >
        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/>
        </svg>
        <span class="hidden group-hover:inline text-xs font-semibold pr-1">{{ __('Concierge Live') }}</span>
    </a>

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
