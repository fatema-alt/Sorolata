<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no" class="scroll-smooth antialiased notranslate">
<head>
    @include('components.analytics.tracking-head')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo_meta')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Hind+Siliguri:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=Noto+Serif+Bengali:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            margin: 0;
            background: #F7F5F0;
            color: #2B2118;
            font-family: 'Manrope', 'Hind Siliguri', system-ui, sans-serif;
        }
        .font-editorial-heading {
            font-family: 'Cormorant Garamond', Georgia, serif !important;
        }
        .font-editorial-body {
            font-family: 'Manrope', 'Hind Siliguri', system-ui, sans-serif !important;
        }
        .font-editorial-bangla {
            font-family: 'Noto Serif Bengali', 'Hind Siliguri', serif !important;
        }
    </style>
</head>
<body
    x-data="{
        scrolled: false,
        showTop: false
    }"
    @scroll.window="scrolled = (window.pageYOffset > 20); showTop = (window.pageYOffset > 400)"
    class="bg-[#F7F5F0] text-[#2B2118] font-editorial-body min-h-screen flex flex-col selection:bg-[#B8892F]/20 selection:text-[#1F4D3A]"
>
    @include('components.analytics.tracking-body')
    @include('storefront.sections.defs')

    @php
        $isBn = app()->getLocale() === 'bn';
        $anchorMap = [
            '#top' => '/',
            '#products' => '/shop',
            '#districts' => '/districts',
            '#makers' => '/artisans',
            '#stories' => '/stories',
            '#why' => '/about',
            '#contact' => '/contact',
        ];
        $resolveUrl = fn(string $url) => $anchorMap[trim($url)] ?? $url;

        $homePageRecord = \App\Models\Page::where('slug', 'home')->first();
        $savedSections = collect($homePageRecord?->layout_json['sections'] ?? []);
        $defaultSections = collect(\App\Livewire\Builder\VisualPageBuilder::getSorolataHomepageSections());

        $headerBlock = $savedSections->firstWhere('type', 'home_header') ?? $defaultSections->firstWhere('type', 'home_header');
        $footerBlock = $savedSections->firstWhere('type', 'home_footer') ?? $defaultSections->firstWhere('type', 'home_footer');

        $hs = $headerBlock['settings'] ?? [];
        $fs = $footerBlock['settings'] ?? [];
        $navLinks = $hs['nav_links'] ?? [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Shop', 'href' => '/shop'],
            ['label' => 'Districts', 'href' => '/districts'],
            ['label' => 'Our Makers', 'href' => '/artisans'],
            ['label' => 'Stories', 'href' => '/stories'],
            ['label' => 'About Us', 'href' => '/about'],
            ['label' => 'Contact', 'href' => '/contact'],
        ];
        if (!collect($navLinks)->contains(fn($l) => ($l['href'] ?? '') === '/' || strtolower((string) ($l['label'] ?? '')) === 'home')) {
            array_unshift($navLinks, ['label' => 'Home', 'href' => '/']);
        }
        $bagCount = count(session('sorolata_collector_bag', []));
    @endphp

    <!-- Top Announcement Bar (Homepage Essence) -->
    @if($hs['show_topbar'] ?? true)
        @php $topbarText = $hs['topbar_text'] ?? 'Free insured delivery to Bangladesh, USA, UK, Canada and Europe'; @endphp
        <div class="soro-marquee-wrap" style="background:{{ $hs['topbar_bg'] ?? '#1F4D3A' }}; color:{{ $hs['topbar_color'] ?? '#FBF6EC' }}; font-size:12px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; padding:10px 0">
            <div class="soro-marquee-track">
                @for($mGroup = 0; $mGroup < 2; $mGroup++)
                    <div aria-hidden="{{ $mGroup === 1 ? 'true' : 'false' }}" style="display:inline-flex; align-items:center">
                        @for($mRep = 0; $mRep < 4; $mRep++)
                            <span style="padding:0 28px">{{ $topbarText }}</span>
                            <span aria-hidden="true" style="color:#E3C26F; font-size:10px">◆</span>
                        @endfor
                    </div>
                @endfor
            </div>
        </div>
    @endif

    <!-- Heritage Header (Homepage Essence) -->
    <style>
        .soro-mob-avatar-wrap { display: none; position: relative; }
        @media (max-width: 767px) {
            .soro-hdr-inner { justify-content: space-between !important; padding: 12px 16px !important; }
            .soro-hdr-nav, .soro-hdr-actions { display: none !important; }
            .soro-mob-avatar-wrap { display: block; }
            .soro-page-wrap { padding-bottom: 66px; }
        }
    </style>
    <header style="background:{{ $hs['bg_color'] ?? '#F7F5F0' }}; position:sticky; top:0; z-index:40">
        <div class="soro-hdr-inner" style="max-width:1200px; margin:0 auto; padding:14px 24px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 28px">
            <a href="{{ url('/') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none">
                <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:34px; color:#1F2A24; letter-spacing:-0.01em; line-height:1">{{ $hs['brand_name'] ?? 'Sorolata' }}</span>
                @php $headerBrandBn = (($hs['brand_bn'] ?? '') === 'সারলতা' || empty($hs['brand_bn'])) ? 'সরলতা' : $hs['brand_bn']; @endphp
                @if(!empty($headerBrandBn))
                    <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#1F4D3A; background:#E7EEE4; border:1px solid #C5D3BF; border-radius:999px; padding:2px 12px">{{ $headerBrandBn }}</span>
                @endif
            </a>

            <nav aria-label="Main" class="soro-hdr-nav hidden md:flex" style="flex-wrap:wrap; gap:4px 26px; font-weight:500; font-size:15px; color:#3A332B">
                @foreach($navLinks as $n)
                    @php
                        $href = $resolveUrl($n['href'] ?? '/');
                        $isActiveNav = request()->is(ltrim($href, '/')) || (request()->is('/') && $href === '/');
                    @endphp
                    <a href="{{ $href }}" style="padding:10px 0; text-decoration:none; color:{{ $isActiveNav ? '#1F4D3A' : '#3A332B' }}; font-weight:{{ $isActiveNav ? '700' : '500' }}; border-bottom:{{ $isActiveNav ? '2px solid #B8892F' : '2px solid transparent' }}">
                        {{ $n['label'] ?? '' }}
                    </a>
                @endforeach
            </nav>

            <div class="soro-hdr-actions" style="display:flex; align-items:center; gap:10px">
                @if($hs['show_lang_toggle'] ?? true)
                    <div style="display:flex; align-items:center; border:1px solid #D9D2C0; border-radius:999px; padding:3px; background:#fff; font-size:13px; font-weight:600">
                        <button type="button" onclick="window.location='{{ route('language.switch', 'en') }}'" style="border:0; background:{{ !$isBn ? '#1F4D3A' : 'none' }}; color:{{ !$isBn ? '#fff' : '#3A332B' }}; border-radius:999px; padding:6px 12px; font-size:13px; font-weight:600; cursor:pointer">EN</button>
                        <button type="button" onclick="window.location='{{ route('language.switch', 'bn') }}'" style="border:0; background:{{ $isBn ? '#1F4D3A' : 'none' }}; color:{{ $isBn ? '#fff' : '#3A332B' }}; border-radius:999px; padding:6px 12px; font-size:13px; font-weight:500; font-family:'Noto Serif Bengali',serif; cursor:pointer">বাং</button>
                    </div>
                @endif

                <a href="{{ $hs['search_href'] ?? route('search.index') }}" aria-label="{{ __('Search') }}" style="width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-3.5-3.5"></path></svg>
                </a>

                @if($hs['show_wishlist'] ?? true)
                    @php $wishlistCount = count(session('sorolata_wishlist', [])); @endphp
                    <a href="{{ $hs['wishlist_href'] ?? route('wishlist.index') }}" aria-label="{{ __('Wishlist') }}" style="position:relative; width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        @if($wishlistCount > 0)
                            <span style="position:absolute; top:-2px; right:-2px; width:18px; height:18px; background:#B8553A; color:#fff; border-radius:50%; font-size:10px; font-weight:700; display:flex; align-items:center; justify-content:center">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                @endif

                <a href="{{ $hs['cart_href'] ?? route('cart.index') }}" aria-label="{{ __('Cart') }}" style="position:relative; width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14l-1.5 12h-11L5 7z"></path><path d="M9 7a3 3 0 016 0"></path></svg>
                    @if($bagCount > 0)
                        <span style="position:absolute; top:-2px; right:-2px; width:18px; height:18px; background:#B8553A; color:#fff; border-radius:50%; font-size:10px; font-weight:700; display:flex; align-items:center; justify-content:center">{{ $bagCount }}</span>
                    @endif
                </a>

                @if($hs['show_account'] ?? true)
                    @php
                        $authUser = auth()->user();
                        $accountUrl = $hs['account_href'] ?? ($authUser ? route('account.index') : route('login'));
                        $initials = $authUser ? strtoupper(mb_substr($authUser->name ?? 'S', 0, 1)) : '';
                    @endphp
                    <a href="{{ $accountUrl }}" aria-label="{{ __('Account') }}" style="width:44px; height:44px; border:1px solid {{ $authUser ? '#1F4D3A' : '#D9D2C0' }}; background:{{ $authUser ? '#E7EEE4' : '#fff' }}; color:#1F4D3A; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none; font-weight:700; font-size:15px">
                        @if($authUser)
                            <span>{{ $initials }}</span>
                        @else
                            <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        @endif
                    </a>
                @endif
            </div>

            <!-- Mobile Right Avatar + Dropdown Menu -->
            @php
                $mobUser = auth()->user();
                $mobInitials = $mobUser ? strtoupper(mb_substr($mobUser->name ?? 'S', 0, 1)) : '';
                $mobWlCount = count(session('sorolata_wishlist', []));
            @endphp
            <div class="soro-mob-avatar-wrap">
                <button type="button" aria-label="{{ __('Menu') }}"
                        onclick="event.stopPropagation(); var m=document.getElementById('soro-mob-acct-menu'); m.style.display=(m.style.display==='block'?'none':'block');"
                        style="position:relative; width:40px; height:40px; border:1.5px solid {{ $mobUser ? '#1F4D3A' : '#D9C28A' }}; background:{{ $mobUser ? '#E7EEE4' : '#fff' }}; color:#1F4D3A; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; font-weight:700; font-size:14px; cursor:pointer">
                    @if($mobUser)
                        <span>{{ $mobInitials }}</span>
                    @else
                        <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    @endif
                    @if($bagCount + $mobWlCount > 0)
                        <span style="position:absolute; top:-2px; right:-2px; width:10px; height:10px; background:#B8553A; border-radius:50%; border:2px solid #F7F5F0"></span>
                    @endif
                </button>
                <div id="soro-mob-acct-menu" onclick="event.stopPropagation()"
                     style="display:none; position:absolute; right:0; top:48px; width:220px; background:#FBF6EC; border:1.5px solid #D9C28A; border-radius:16px; box-shadow:0 16px 36px rgba(43,33,24,0.18); padding:8px; z-index:60; font-size:14px; color:#2B2118">
                    @if($mobUser)
                        <a href="{{ route('account.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; text-decoration:none; color:#1F4D3A; font-weight:700">{{ __('My Account') }}</a>
                    @else
                        <a href="{{ route('login') }}" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; text-decoration:none; color:#1F4D3A; font-weight:700">{{ __('Sign In') }}</a>
                        <a href="{{ route('register') }}" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; text-decoration:none; color:#2B2118; font-weight:600">{{ __('Register') }}</a>
                    @endif
                    <div style="height:1px; background:#E2D3AF; margin:4px 8px"></div>
                    <a href="{{ route('wishlist.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#2B2118">
                        <span>{{ __('Wishlist') }}</span>
                        @if($mobWlCount > 0)<span style="background:#B8553A; color:#fff; font-size:11px; font-weight:700; padding:1px 7px; border-radius:999px">{{ $mobWlCount }}</span>@endif
                    </a>
                    <a href="{{ route('cart.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#2B2118">
                        <span>{{ __('Shopping Bag') }}</span>
                        @if($bagCount > 0)<span style="background:#1F4D3A; color:#fff; font-size:11px; font-weight:700; padding:1px 7px; border-radius:999px">{{ $bagCount }}</span>@endif
                    </a>
                    <a href="{{ route('search.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#2B2118">{{ __('Search') }}</a>
                    <div style="height:1px; background:#E2D3AF; margin:4px 8px"></div>
                    <a href="{{ route('language.switch', $isBn ? 'en' : 'bn') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#1F4D3A; font-weight:600">
                        <span>{{ $isBn ? 'Switch to English' : 'বাংলায় দেখুন' }}</span>
                    </a>
                </div>
            </div>
            <script>document.addEventListener('click',function(){var m=document.getElementById('soro-mob-acct-menu');if(m)m.style.display='none';});</script>
        </div>
        @if($hs['show_stitch_border'] ?? true)
            <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
        @endif
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow pb-16 md:pb-0">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Dock -->
    <x-mobile-bottom-nav />

    <!-- Floating Concierge & Contact Widget -->
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
        class="fixed bottom-20 md:bottom-8 left-6 z-40 bg-[#FBF6EC] text-[#1F4D3A] p-3 rounded-full shadow-md border border-[#D9C28A] hover:bg-[#1F4D3A] hover:text-white transition-all cursor-pointer"
        aria-label="{{ __('Back to Top') }}"
        style="display: none;"
    >
        <x-icon name="chevron-down" class="w-4 h-4 transform rotate-180" />
    </button>

    <!-- Heritage Footer (Homepage Essence) -->
    @php
        $shopLinks = $fs['shop_links'] ?? [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'All products', 'href' => '/shop'],
            ['label' => 'By district', 'href' => '/districts'],
            ['label' => 'Our makers', 'href' => '/artisans'],
            ['label' => 'Stories', 'href' => '/stories'],
            ['label' => 'Wishlist', 'href' => '/wishlist'],
        ];
        if (!collect($shopLinks)->contains(fn($l) => ($l['href'] ?? '') === '/' || strtolower((string) ($l['label'] ?? '')) === 'home')) {
            array_unshift($shopLinks, ['label' => 'Home', 'href' => '/']);
        }
        $helpLinks = $fs['help_links'] ?? [
            ['label' => 'Track your order', 'href' => '/track-order'],
            ['label' => 'My account', 'href' => '/account'],
            ['label' => 'Delivery and returns', 'href' => '/contact'],
            ['label' => 'FAQ & About', 'href' => '/about'],
            ['label' => 'Contact us', 'href' => '/contact'],
        ];
        $contactLines = $fs['contact_lines'] ?? [
            ['text' => 'WhatsApp: Chat with Sorolata', 'href' => '/contact'],
            ['text' => 'Email: hello@sorolata.com', 'href' => 'mailto:hello@sorolata.com'],
            ['text' => 'Facebook: /sorolatabangladesh', 'href' => 'https://facebook.com/sorolatabangladesh'],
        ];
    @endphp
    <footer id="contact" style="background:{{ $fs['bg_color'] ?? '#163829' }}; color:#F3EDDF; position:relative; overflow:hidden; margin-top:64px">
        <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliLight)" opacity="0.4"></rect></svg>
        <div style="position:relative; max-width:1200px; margin:0 auto; padding:56px 24px 28px; display:flex; flex-wrap:wrap; gap:40px">
            <div style="flex:1.5 1 300px; min-width:0">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px">
                    <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:38px; color:#FBF6EC">{{ $fs['brand_name'] ?? 'Sorolata' }}</span>
                    @php $footerBrandBn = (($fs['brand_bn'] ?? '') === 'সারলতা' || empty($fs['brand_bn'])) ? 'সরলতা' : $fs['brand_bn']; @endphp
                    @if(!empty($footerBrandBn))
                        <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#E3C26F; border:1px solid #E3C26F; border-radius:999px; padding:2px 12px">{{ $footerBrandBn }}</span>
                    @endif
                </div>
                <p style="margin:0; font-size:15px; max-width:380px; color:#E8E0CF">{{ $fs['tagline'] ?? 'Handmade things from the districts of Bangladesh, sent from the maker to your home.' }}</p>
            </div>
            <div style="flex:1 1 160px; min-width:0">
                <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">{{ $fs['col1_title'] ?? 'Shop' }}</h3>
                <div style="display:flex; flex-direction:column; gap:4px; font-size:15px">
                    @foreach($shopLinks as $f)
                        <a href="{{ $resolveUrl($f['href'] ?? '/shop') }}" style="padding:6px 0; color:#F3EDDF; text-decoration:none">{{ $f['label'] ?? '' }}</a>
                    @endforeach
                </div>
            </div>
            <div style="flex:1 1 160px; min-width:0">
                <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">{{ $fs['col2_title'] ?? 'Help' }}</h3>
                <div style="display:flex; flex-direction:column; gap:4px; font-size:15px">
                    @foreach($helpLinks as $f)
                        <a href="{{ $resolveUrl($f['href'] ?? '/contact') }}" style="padding:6px 0; color:#F3EDDF; text-decoration:none">{{ $f['label'] ?? '' }}</a>
                    @endforeach
                </div>
            </div>
            <div style="flex:1 1 220px; min-width:0">
                <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">{{ $fs['col3_title'] ?? 'Talk to us' }}</h3>
                <div style="display:flex; flex-direction:column; gap:8px; font-size:15px">
                    @foreach($contactLines as $c)
                        @php
                            $cText = is_array($c) ? ($c['text'] ?? '') : (string) $c;
                            $cHref = is_array($c) ? ($c['href'] ?? '') : '';
                            if (empty($cHref)) {
                                if (str_contains(strtolower($cText), 'facebook')) {
                                    $cHref = 'https://facebook.com/sorolatabangladesh';
                                } elseif (str_contains(strtolower($cText), 'email') || str_contains($cText, '@')) {
                                    $cHref = 'mailto:hello@sorolata.com';
                                } else {
                                    $cHref = '/contact';
                                }
                            }
                            $isExt = str_starts_with($cHref, 'http');
                        @endphp
                        <a href="{{ $resolveUrl($cHref) }}" @if($isExt) target="_blank" rel="noopener" @endif style="color:#F3EDDF; text-decoration:none; padding:2px 0">{{ $cText }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div style="position:relative; max-width:1200px; margin:0 auto; padding:0 24px 32px">
            <svg aria-hidden="true" width="100%" height="10" style="display:block; margin-bottom:16px"><rect width="100%" height="10" fill="url(#patStitchLight)"></rect></svg>
            <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; font-size:13px; color:#CFC7B4">
                <span>{{ $fs['copyright_text'] ?? '© 2026 Sorolata. All rights reserved.' }}</span>
                <span>{{ $fs['payment_text'] ?? 'We accept bKash, Nagad, Visa and Mastercard' }}</span>
            </div>
        </div>
    </footer>

    <x-mobile-bottom-nav />
    <x-sorolata-animations />

    @livewireScripts
</body>
</html>
