@php
  $type = $section['type'] ?? '';
  $s = $section['settings'] ?? [];
  $isBuilder = $isBuilder ?? false;
  $isBn = $isBuilder ? (($previewLocale ?? 'en') === 'bn') : (app()->getLocale() === 'bn');
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
  $linkHref = fn(string $url) => $isBuilder ? 'javascript:void(0)' : $resolveUrl($url);
@endphp

@if($type === 'home_header')
  @php
    $topBarBg = $s['topbar_bg'] ?? '#1F4D3A';
    $topBarColor = $s['topbar_color'] ?? '#FBF6EC';
    $headerBg = $s['bg_color'] ?? '#F7F5F0';
    $navLinks = $s['nav_links'] ?? [];
    if (!collect($navLinks)->contains(fn($l) => ($l['href'] ?? '') === '/' || strtolower((string) ($l['label'] ?? '')) === 'home')) {
        array_unshift($navLinks, ['label' => 'Home', 'href' => '/']);
    }
  @endphp
  @if(($s['show_topbar'] ?? true))
    <div style="background:{{ $topBarBg }}; color:{{ $topBarColor }}; font-size:12px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; text-align:center; padding:10px 16px">
      {{ $s['topbar_text'] ?? 'Free insured delivery to Bangladesh, USA, UK, Canada and Europe' }}
    </div>
  @endif
  @if(!$isBuilder)
    <style>
      .soro-mob-avatar-wrap { display: none; position: relative; }
      @media (max-width: 767px) {
        .soro-hdr-inner { justify-content: space-between !important; padding: 12px 16px !important; }
        .soro-hdr-nav, .soro-hdr-actions { display: none !important; }
        .soro-mob-avatar-wrap { display: block; }
        .soro-page-wrap { padding-bottom: 66px; }
      }
    </style>
  @else
    <style>.soro-mob-avatar-wrap { display: none; }</style>
  @endif
  <header style="background:{{ $headerBg }}; position:relative; z-index:40">
    <div class="soro-hdr-inner" style="max-width:1200px; margin:0 auto; padding:14px 24px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 28px">
      <a href="{{ $linkHref($s['brand_href'] ?? '#top') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none">
        <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:34px; color:#1F2A24; letter-spacing:-0.01em">{{ $s['brand_name'] ?? 'Sorolata' }}</span>
        @php $blkHeaderBrandBn = (($s['brand_bn'] ?? '') === 'সারলতা' || empty($s['brand_bn'])) ? 'সরলতা' : $s['brand_bn']; @endphp
        @if(!empty($blkHeaderBrandBn))
          <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#1F4D3A; background:#E7EEE4; border:1px solid #C5D3BF; border-radius:999px; padding:2px 12px">{{ $blkHeaderBrandBn }}</span>
        @endif
      </a>
      <nav aria-label="Main" class="soro-hdr-nav" style="display:flex; flex-wrap:wrap; gap:4px 26px; font-weight:500; font-size:15px; color:#3A332B">
        @foreach($navLinks as $n)
          <a href="{{ $linkHref($n['href'] ?? '#') }}" style="padding:10px 0">{{ $n['label'] ?? '' }}</a>
        @endforeach
      </nav>
      <div class="soro-hdr-actions" style="display:flex; align-items:center; gap:10px">
        @if($s['show_lang_toggle'] ?? true)
          <div style="display:flex; align-items:center; border:1px solid #D9D2C0; border-radius:999px; padding:3px; background:#fff; font-size:13px; font-weight:600">
            <button type="button" @if(!$isBuilder) onclick="window.location='{{ route('language.switch', 'en') }}'" @endif style="border:0; background:{{ !$isBn ? '#1F4D3A' : 'none' }}; color:{{ !$isBn ? '#fff' : '#3A332B' }}; border-radius:999px; padding:6px 12px; font-size:13px; font-weight:600">EN</button>
            <button type="button" @if(!$isBuilder) onclick="window.location='{{ route('language.switch', 'bn') }}'" @endif style="border:0; background:{{ $isBn ? '#1F4D3A' : 'none' }}; color:{{ $isBn ? '#fff' : '#3A332B' }}; border-radius:999px; padding:6px 12px; font-size:13px; font-weight:500; font-family:'Noto Serif Bengali',serif">বাং</button>
          </div>
        @endif
        <button type="button" aria-label="Search" @if(!$isBuilder) onclick="window.location='{{ $s['search_href'] ?? route('search.index') }}'" @endif style="width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0">
          <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-3.5-3.5"></path></svg>
        </button>
        @if($s['show_wishlist'] ?? true)
          @php $wlCount = $isBuilder ? 0 : count(session('sorolata_wishlist', [])); @endphp
          <button type="button" aria-label="Wishlist" @if(!$isBuilder) onclick="window.location='{{ $s['wishlist_href'] ?? route('wishlist.index') }}'" @endif style="position:relative; width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0">
            <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            @if($wlCount > 0)
              <span style="position:absolute; top:-2px; right:-2px; width:18px; height:18px; background:#B8553A; color:#fff; border-radius:50%; font-size:10px; font-weight:700; display:flex; align-items:center; justify-content:center">{{ $wlCount }}</span>
            @endif
          </button>
        @endif
        <button type="button" aria-label="Cart" @if(!$isBuilder) onclick="window.location='{{ $s['cart_href'] ?? route('cart.index') }}'" @endif style="position:relative; width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0">
          @php $cartCount = $isBuilder ? 0 : count(session('sorolata_collector_bag', [])); @endphp
          <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14l-1.5 12h-11L5 7z"></path><path d="M9 7a3 3 0 016 0"></path></svg>
          @if($cartCount > 0)
            <span style="position:absolute; top:-2px; right:-2px; width:18px; height:18px; background:#B8553A; color:#fff; border-radius:50%; font-size:10px; font-weight:700; display:flex; align-items:center; justify-content:center">{{ $cartCount }}</span>
          @endif
        </button>
        @if($s['show_account'] ?? true)
          @php
            $authUser = !$isBuilder ? auth()->user() : null;
            $accountUrl = $s['account_href'] ?? ($authUser ? route('account.index') : route('login'));
            $initials = $authUser ? strtoupper(mb_substr($authUser->name ?? 'S', 0, 1)) : '';
          @endphp
          <button type="button" aria-label="Account" @if(!$isBuilder) onclick="window.location='{{ $accountUrl }}'" @endif style="width:44px; height:44px; border:1px solid {{ $authUser ? '#1F4D3A' : '#D9D2C0' }}; background:{{ $authUser ? '#E7EEE4' : '#fff' }}; color:#1F4D3A; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; font-weight:700; font-size:15px">
            @if($authUser)
              <span>{{ $initials }}</span>
            @else
              <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            @endif
          </button>
        @endif
      </div>

      @if(!$isBuilder)
        @php
          $mobUser = auth()->user();
          $mobInitials = $mobUser ? strtoupper(mb_substr($mobUser->name ?? 'S', 0, 1)) : '';
          $mobWlCount = count(session('sorolata_wishlist', []));
          $mobBagCount = count(session('sorolata_collector_bag', []));
        @endphp
        <div class="soro-mob-avatar-wrap">
          <button type="button" aria-label="Menu"
                  onclick="event.stopPropagation(); var m=document.getElementById('soro-mob-acct-menu'); m.style.display=(m.style.display==='block'?'none':'block');"
                  style="position:relative; width:40px; height:40px; border:1.5px solid {{ $mobUser ? '#1F4D3A' : '#D9C28A' }}; background:{{ $mobUser ? '#E7EEE4' : '#fff' }}; color:#1F4D3A; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0; font-weight:700; font-size:14px; cursor:pointer">
            @if($mobUser)
              <span>{{ $mobInitials }}</span>
            @else
              <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            @endif
            @if($mobBagCount + $mobWlCount > 0)
              <span style="position:absolute; top:-2px; right:-2px; width:10px; height:10px; background:#B8553A; border-radius:50%; border:2px solid #F7F5F0"></span>
            @endif
          </button>
          <div id="soro-mob-acct-menu" onclick="event.stopPropagation()"
               style="display:none; position:absolute; right:0; top:48px; width:220px; background:#FBF6EC; border:1.5px solid #D9C28A; border-radius:16px; box-shadow:0 16px 36px rgba(43,33,24,0.18); padding:8px; z-index:60; font-size:14px; color:#2B2118">
            @if($mobUser)
              <a href="{{ route('account.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; text-decoration:none; color:#1F4D3A; font-weight:700">My Account</a>
            @else
              <a href="{{ route('login') }}" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; text-decoration:none; color:#1F4D3A; font-weight:700">Sign In</a>
              <a href="{{ route('register') }}" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; text-decoration:none; color:#2B2118; font-weight:600">Register</a>
            @endif
            <div style="height:1px; background:#E2D3AF; margin:4px 8px"></div>
            <a href="{{ route('wishlist.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#2B2118">
              <span>Wishlist</span>
              @if($mobWlCount > 0)<span style="background:#B8553A; color:#fff; font-size:11px; font-weight:700; padding:1px 7px; border-radius:999px">{{ $mobWlCount }}</span>@endif
            </a>
            <a href="{{ route('cart.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#2B2118">
              <span>Shopping Bag</span>
              @if($mobBagCount > 0)<span style="background:#1F4D3A; color:#fff; font-size:11px; font-weight:700; padding:1px 7px; border-radius:999px">{{ $mobBagCount }}</span>@endif
            </a>
            <a href="{{ route('search.index') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#2B2118">Search</a>
            <div style="height:1px; background:#E2D3AF; margin:4px 8px"></div>
            <a href="{{ route('language.switch', $isBn ? 'en' : 'bn') }}" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border-radius:10px; text-decoration:none; color:#1F4D3A; font-weight:600">
              <span>{{ $isBn ? 'Switch to English' : 'বাংলায় দেখুন' }}</span>
            </a>
          </div>
        </div>
        <script>document.addEventListener('click',function(){var m=document.getElementById('soro-mob-acct-menu');if(m)m.style.display='none';});</script>
      @endif
    </div>
    @if($s['show_stitch_border'] ?? true)
      <svg aria-hidden="true" width="100%" height="10" style="position:absolute; left:0; right:0; bottom:-5px; z-index:41; pointer-events:none; display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
    @endif
  </header>

@elseif($type === 'home_hero')
  @php
    $bgColor = $s['bg_color'] ?? '#163829';
    $accentColor = $s['accent_color'] ?? '#B8892F';
    $primaryBg = $s['primary_color'] ?? '#1F4D3A';
    $trustItems = $s['trust_items'] ?? [];
    $heroSlides = !empty($s['slides']) && is_array($s['slides']) ? $s['slides'] : [
        [
            'eyebrow' => 'SONARGAON MUSLIN • 300-COUNT HANDLOOM',
            'headline' => !empty($s['headline']) ? $s['headline'] : 'Handmade in Bangladesh. Passed down from parent to child.',
            'subheadline' => !empty($s['subheadline']) ? $s['subheadline'] : 'Muslin, brass, clay and more. Every piece names its maker and the district it comes from.',
            'cta_text' => $s['primary_cta_text'] ?? 'Shop Now →',
            'cta_href' => $s['primary_cta_href'] ?? '/shop',
            'secondary_text' => $s['secondary_cta_text'] ?? 'Shop by District',
            'secondary_href' => $s['secondary_cta_href'] ?? '/districts',
            'image_url' => !empty($s['image_url']) ? $s['image_url'] : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1600&auto=format&fit=crop',
            'bg' => 'linear-gradient(135deg,#1F4D3A 0%,#163829 60%,#3B2314 100%)',
        ],
        [
            'eyebrow' => 'TANGAIL SILK & JAMDANI • WEAVING CLUSTERS',
            'headline' => 'Woven on wooden pit looms, one thread at a time.',
            'subheadline' => 'Heirloom sarees and scarves crafted by generational weavers who memorize every floral motif by heart.',
            'cta_text' => 'Explore Cloth →',
            'cta_href' => '/shop?category=cloth',
            'secondary_text' => 'Meet Our Makers',
            'secondary_href' => '/artisans',
            'image_url' => 'https://images.unsplash.com/photo-1583391733956-6c78276477e2?q=80&w=1600&auto=format&fit=crop',
            'bg' => 'linear-gradient(135deg,#2C2240 0%,#1F4D3A 65%,#5E2618 100%)',
        ],
        [
            'eyebrow' => 'BOGURA BRASS & BELL METAL • LOST-WAX CRAFT',
            'headline' => 'Hand-hammered brass, ringing with generations of craft.',
            'subheadline' => 'Kettles, plates, and bowls shaped in family foundries with zero machines and pure bell-metal resonance.',
            'cta_text' => 'Shop Brassware →',
            'cta_href' => '/shop?category=brass',
            'secondary_text' => 'Explore Bogura',
            'secondary_href' => '/districts',
            'image_url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop',
            'bg' => 'linear-gradient(135deg,#4A3510 0%,#2B2118 65%,#163829 100%)',
        ],
        [
            'eyebrow' => 'RANGPUR SHATRANJI & TERRACOTTA • NORTHERN PLAINS',
            'headline' => 'Earthy patterns from the river basins of Bengal.',
            'subheadline' => 'Thick hand-woven floor carpets and sun-baked clayware sent straight from the village workshop to your home.',
            'cta_text' => 'View All Pieces →',
            'cta_href' => '/shop',
            'secondary_text' => 'Read Stories',
            'secondary_href' => '/stories',
            'image_url' => 'https://images.unsplash.com/photo-1590736969955-71cc94901144?q=80&w=1600&auto=format&fit=crop',
            'bg' => 'linear-gradient(135deg,#5E2618 0%,#2B2118 60%,#1F4D3A 100%)',
        ],
    ];
    $sliderUid = 'soroHero_' . preg_replace('/[^a-zA-Z0-9_]/', '', (string) ($section['id'] ?? 'main'));
  @endphp
  <style>
    .{{ $sliderUid }}-wrap {
      position: relative;
      overflow: hidden;
      min-height: clamp(540px, 84vh, 760px);
      background: {{ $bgColor }};
      display: flex;
      align-items: center;
    }
    .{{ $sliderUid }}-slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.75s ease;
      display: flex;
      align-items: center;
    }
    .{{ $sliderUid }}-slide.is-active {
      opacity: 1;
      pointer-events: auto;
      z-index: 2;
    }
    .{{ $sliderUid }}-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      border: 1.5px solid rgba(243, 220, 154, 0.7);
      background: rgba(22, 56, 41, 0.65);
      color: #FBF6EC;
      font-size: 18px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      backdrop-filter: blur(4px);
      transition: background 0.2s, transform 0.2s;
    }
    .{{ $sliderUid }}-arrow:hover { background: #1F4D3A; border-color: #F3DC9A; }
    .{{ $sliderUid }}-prev { left: 20px; }
    .{{ $sliderUid }}-next { right: 20px; }
    @media (max-width: 767px) {
      .{{ $sliderUid }}-wrap { min-height: 540px; }
      .{{ $sliderUid }}-arrow { display: none; }
      .soro-hero-btns { gap: 10px !important; flex-wrap: nowrap !important; }
      .soro-hero-btns a { padding: 12px 16px !important; font-size: 14px !important; white-space: nowrap; }
    }
  </style>
  <section id="top" class="{{ $sliderUid }}-wrap">
    @foreach($heroSlides as $sIdx => $sl)
      <div class="{{ $sliderUid }}-slide {{ $sIdx === 0 ? 'is-active' : '' }}" data-slide="{{ $sIdx }}"
           style="background:{{ $sl['bg'] ?? 'linear-gradient(135deg,#1F4D3A 0%,#163829 60%,#3B2314 100%)' }}">
        @if(!empty($sl['image_url']))
          <img src="{{ $sl['image_url'] }}" alt="{{ $sl['headline'] ?? 'Sorolata Craft' }}"
               onerror="this.style.display='none'"
               style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
        @endif
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; inset:0; pointer-events:none; opacity:0.22"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
        <div aria-hidden="true" style="position:absolute; inset:0; background:linear-gradient(95deg, rgba(18,46,34,0.9) 0%, rgba(18,46,34,0.68) 48%, rgba(18,46,34,0.28) 100%)"></div>

        <div style="position:relative; z-index:3; width:100%; max-width:1200px; margin:0 auto; padding:56px 24px 88px">
          <div style="max-width:680px">
            @if(!empty($sl['eyebrow']))
              <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(251,246,236,0.12); border:1px solid rgba(243,220,154,0.45); color:#F3DC9A; border-radius:999px; padding:6px 14px; font-size:11px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:18px; backdrop-filter:blur(4px)">
                <span aria-hidden="true" style="width:6px; height:6px; background:#F3DC9A; transform:rotate(45deg)"></span>
                <span>{{ $sl['eyebrow'] }}</span>
              </div>
            @endif

            <h1 style="margin:0 0 18px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(38px,5.4vw,72px); line-height:1.02; letter-spacing:-0.01em; color:#FBF6EC">
              {{ $sl['headline'] ?? '' }}
            </h1>

            @if(!empty($sl['subheadline']))
              <p style="margin:0 0 28px; font-size:clamp(16px,1.5vw,19px); color:#E8E0CF; max-width:540px; line-height:1.6">
                {{ $sl['subheadline'] }}
              </p>
            @endif

            <div class="soro-hero-btns" style="display:flex; flex-wrap:wrap; gap:14px; margin-bottom:30px">
              @if(!empty($sl['cta_text']))
                <a href="{{ $linkHref($sl['cta_href'] ?? '/shop') }}"
                   style="background:#B8892F; color:#FBF6EC; font-weight:700; padding:15px 30px; border-radius:999px; text-decoration:none; font-size:15px; box-shadow:0 8px 22px rgba(0,0,0,0.22)">
                  {{ $sl['cta_text'] }}
                </a>
              @endif
              @if(!empty($sl['secondary_text']))
                <a href="{{ $linkHref($sl['secondary_href'] ?? '/districts') }}"
                   style="border:1.5px solid rgba(251,246,236,0.7); color:#FBF6EC; font-weight:600; padding:13.5px 28px; border-radius:999px; text-decoration:none; font-size:15px; background:rgba(251,246,236,0.08); backdrop-filter:blur(4px)">
                  {{ $sl['secondary_text'] }}
                </a>
              @endif
            </div>

            @if(count($trustItems) > 0)
              <div style="display:flex; flex-wrap:wrap; gap:12px 26px; font-size:13px; font-weight:600; color:#F3EDDF">
                @foreach($trustItems as $t)
                  <span style="display:flex; align-items:center; gap:7px"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 48 48" style="color:#F3DC9A"><use href="#rosetteArt"></use></svg>{{ $t['text'] ?? $t }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    @endforeach

    @if(count($heroSlides) > 1 && !$isBuilder)
      <button type="button" aria-label="Previous slide" class="{{ $sliderUid }}-arrow {{ $sliderUid }}-prev" data-hero-prev="1">←</button>
      <button type="button" aria-label="Next slide" class="{{ $sliderUid }}-arrow {{ $sliderUid }}-next" data-hero-next="1">→</button>
    @endif

    @if(count($heroSlides) > 1)
      <div style="position:absolute; left:0; right:0; bottom:22px; z-index:10; display:flex; align-items:center; justify-content:center; gap:10px; padding:0 16px">
        <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(22,56,41,0.72); border:1px solid rgba(243,220,154,0.45); border-radius:999px; padding:7px 14px; backdrop-filter:blur(6px)">
          @foreach($heroSlides as $sIdx => $sl)
            <button type="button" aria-label="Slide {{ $sIdx + 1 }}" data-hero-dot="{{ $sIdx }}"
                    style="width:{{ $sIdx === 0 ? '26px' : '9px' }}; height:9px; border-radius:999px; border:0; background:{{ $sIdx === 0 ? '#F3DC9A' : 'rgba(251,246,236,0.45)' }}; padding:0; cursor:pointer; transition:all 0.25s"></button>
          @endforeach
          <span data-hero-counter style="font-size:11px; font-weight:700; letter-spacing:0.08em; color:#F3DC9A; margin-left:4px">01 / {{ str_pad((string) count($heroSlides), 2, '0', STR_PAD_LEFT) }}</span>
        </div>
      </div>
    @endif
  </section>
  <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>

  @if(count($heroSlides) > 1 && !$isBuilder)
    <script>
      (function(){
        var root = document.querySelector('.{{ $sliderUid }}-wrap');
        if (!root) return;
        var slides = Array.from(root.querySelectorAll('.{{ $sliderUid }}-slide'));
        var dots = Array.from(root.querySelectorAll('[data-hero-dot]'));
        var counter = root.querySelector('[data-hero-counter]');
        var cur = 0;
        var total = slides.length;
        function show(idx) {
          cur = (idx + total) % total;
          slides.forEach(function(el, i){ el.classList.toggle('is-active', i === cur); });
          dots.forEach(function(d, i){
            d.style.width = (i === cur) ? '26px' : '9px';
            d.style.background = (i === cur) ? '#F3DC9A' : 'rgba(251,246,236,0.45)';
          });
          if (counter) {
            counter.textContent = String(cur + 1).padStart(2, '0') + ' / ' + String(total).padStart(2, '0');
          }
        }
        var prevBtn = root.querySelector('[data-hero-prev]');
        var nextBtn = root.querySelector('[data-hero-next]');
        if (prevBtn) prevBtn.addEventListener('click', function(){ show(cur - 1); resetTimer(); });
        if (nextBtn) nextBtn.addEventListener('click', function(){ show(cur + 1); resetTimer(); });
        dots.forEach(function(d, i){ d.addEventListener('click', function(){ show(i); resetTimer(); }); });
        var timer = setInterval(function(){ show(cur + 1); }, 5500);
        function resetTimer(){ clearInterval(timer); timer = setInterval(function(){ show(cur + 1); }, 5500); }
        root.addEventListener('mouseenter', function(){ clearInterval(timer); });
        root.addEventListener('mouseleave', function(){ resetTimer(); });
      })();
    </script>
  @endif
  @if($s['show_band'] ?? true)
    <svg aria-hidden="true" width="100%" height="48" style="display:block; background:#FBF6EC"><rect width="100%" height="48" fill="url(#patBandGold)"></rect></svg>
  @endif

@elseif($type === 'home_districts')
  @php
    $districtsList = $s['items'] ?? [];
    $mapBg = $s['map_card_bg'] ?? '#F0E6D0';
  @endphp
  <section id="districts" style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Shop by district' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Every district has its own craft' }}</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
        <p style="margin:0; font-size:17px; color:#5A5044">{{ $s['subtitle'] ?? '' }}</p>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:32px; align-items:stretch">
        <div style="position:relative; flex:1 1 360px; min-width:0; background:{{ $mapBg }}; border:1px solid #D9C28A; border-radius:24px; padding:{{ !empty($s['map_image_url']) ? '18px 18px 16px' : '28px' }}; display:flex; flex-direction:column; align-items:center; justify-content:center; overflow:hidden">
          <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
          @if(!empty($s['map_image_url']))
            <img src="{{ $s['map_image_url'] }}" alt="{{ $s['map_image_alt'] ?? 'Bangladesh craft districts map' }}" style="position:relative; width:100%; flex:1 1 auto; max-height:470px; object-fit:contain; mix-blend-mode:{{ ($s['map_blend_multiply'] ?? true) ? 'multiply' : 'normal' }}; border-radius:{{ ($s['map_blend_multiply'] ?? true) ? '0' : '16px' }}">
          @else
            <svg role="img" aria-label="Simple map of Bangladesh with four district pins" viewBox="0 0 300 420" width="100%" style="position:relative; max-width:340px; height:auto">
              <path d="M110 10 L140 18 L160 40 L195 50 L230 55 L262 70 L268 100 L250 125 L255 160 L245 200 L255 250 L270 300 L282 350 L270 395 L255 360 L245 320 L225 290 L200 300 L170 310 L150 330 L120 335 L100 320 L95 290 L105 250 L95 215 L70 195 L55 165 L68 135 L60 105 L75 75 L90 45 Z" fill="#EBDDB8" stroke="#1F4D3A" stroke-width="2.5" stroke-linejoin="round"></path>
              <path d="M110 10 L140 18 L160 40 L195 50 L230 55 L262 70 L268 100 L250 125 L255 160 L245 200 L255 250 L270 300 L282 350 L270 395 L255 360 L245 320 L225 290 L200 300 L170 310 L150 330 L120 335 L100 320 L95 290 L105 250 L95 215 L70 195 L55 165 L68 135 L60 105 L75 75 L90 45 Z" fill="url(#patDots)" stroke="none"></path>
              <g fill="#B8553A" stroke="#FBF6EC" stroke-width="3">
                <circle cx="105" cy="62" r="9"></circle>
                <circle cx="112" cy="125" r="9"></circle>
                <circle cx="150" cy="185" r="9"></circle>
                <circle cx="178" cy="225" r="9"></circle>
              </g>
              <g fill="none" stroke="#B8553A" stroke-width="1.2" stroke-opacity="0.6">
                <circle cx="105" cy="62" r="15"></circle><circle cx="112" cy="125" r="15"></circle><circle cx="150" cy="185" r="15"></circle><circle cx="178" cy="225" r="15"></circle>
              </g>
              <g font-family="Manrope, Hind Siliguri, sans-serif" font-size="13" font-weight="700" fill="#2B2118">
                <text x="126" y="58">Rangpur</text>
                <text x="133" y="121">Bogura</text>
                <text x="171" y="181">Tangail</text>
                <text x="199" y="233">Narayanganj</text>
              </g>
            </svg>
          @endif
        </div>
        <div style="flex:1.4 1 420px; min-width:0; display:flex; flex-direction:column; gap:14px">
          @foreach($districtsList as $d)
            <a href="{{ $linkHref($d['href'] ?? '#products') }}" style="position:relative; overflow:hidden; display:flex; align-items:center; gap:18px; background:#fff; border:1px solid #E3D8BC; border-radius:18px; padding:20px 24px; text-decoration:none">
              <svg aria-hidden="true" width="56" height="56" viewBox="0 0 56 56" style="position:absolute; right:0; top:0; color:#B8892F; opacity:0.75"><use href="#cornerArt"></use></svg>
              <div style="width:46px; height:46px; flex:none; border-radius:50%; background:#1F4D3A; color:#FBF6EC; border:2px solid #E3C26F; box-shadow:0 4px 14px rgba(31,77,58,0.28); display:flex; align-items:center; justify-content:center; font-family:'Manrope',sans-serif; font-weight:700; font-size:18px; line-height:1">{{ $d['n'] ?? ($loop->iteration) }}</div>
              <div style="flex:1 1 auto; min-width:0; padding-right:40px">
                <div style="display:flex; flex-wrap:wrap; align-items:baseline; gap:2px 12px">
                  <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:26px; line-height:1.15; color:#1F4D3A">{{ $d['name'] ?? '' }}</h3>
                  @if(!empty($d['bn']))
                    <span style="font-family:'Noto Serif Bengali',serif; font-size:14px; color:#5A5044">{{ $d['bn'] }}</span>
                  @endif
                </div>
                <div style="font-size:15px; color:#5A5044">{{ $d['craft'] ?? '' }}</div>
              </div>
              <div style="font-weight:700; font-size:14px; color:#8F3F26; white-space:nowrap">{{ $d['cta_text'] ?? 'See products →' }}</div>
            </a>
          @endforeach
          @if(!empty($s['coming_soon_text']))
            <div style="display:flex; align-items:center; gap:14px; padding:16px 24px; border:2px dashed #CDBB95; border-radius:18px; color:#5A5044; font-size:15px">
              <svg aria-hidden="true" width="28" height="28" viewBox="0 0 48 48" style="color:#B8892F; flex:none"><use href="#rosetteArt"></use></svg>
              {{ $s['coming_soon_text'] }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

@elseif($type === 'home_crafts')
  @php $craftsList = $s['items'] ?? []; @endphp
  <section style="background:{{ $s['bg_color'] ?? '#F0E6D0' }}; position:relative; overflow:hidden">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.5"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Shop by craft' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Four old crafts, still made by hand' }}</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:20px">
        @foreach($craftsList as $c)
          <a href="{{ $linkHref($c['href'] ?? '#products') }}" style="position:relative; flex:1 1 240px; min-width:0; min-height:380px; border-radius:200px 200px 20px 20px; overflow:hidden; display:flex; flex-direction:column; justify-content:flex-end; text-decoration:none; background:{{ $c['bg'] ?? 'linear-gradient(160deg,#5B6FAE,#26356A)' }}; color:#fff">
            @if(!empty($c['image_url']))
              <img src="{{ $c['image_url'] }}" alt="{{ $c['name'] ?? '' }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
            @else
              <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#{{ $c['pattern'] ?? 'patCloth' }})"></rect></svg>
            @endif
            <div aria-hidden="true" style="position:absolute; left:10px; right:10px; top:10px; bottom:10px; border:1px solid rgba(255,255,255,0.55); border-radius:190px 190px 12px 12px"></div>
            @if(empty($c['image_url']) && !empty($c['photo_label']))
              <div style="position:absolute; left:50%; top:120px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">{{ $c['photo_label'] }}</div>
            @endif
            <div style="position:relative; padding:90px 26px 28px; background:linear-gradient(to top, rgba(20,12,6,0.82) 35%, rgba(20,12,6,0)); text-align:center">
              <h3 style="margin:0 0 4px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; line-height:1.1">{{ $c['name'] ?? '' }}</h3>
              <div style="font-size:15px">{{ $c['line'] ?? '' }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

@elseif($type === 'home_products')
  @php
    $productsList = $s['items'] ?? [];
    $allPlaceholders = !empty($productsList) && collect($productsList)->every(
        fn ($item) => empty($item['product_id']) && empty($item['image_url'])
    );
    if (empty($productsList) || $allPlaceholders) {
        $liveProducts = \App\Models\Product::with(['artisan', 'district', 'media'])
            ->where('status', 'published')
            ->orderByDesc('is_featured')
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();
        if ($liveProducts->isNotEmpty()) {
            $productsList = $liveProducts->map(
                fn ($prod) => \App\Livewire\Builder\VisualPageBuilder::mapProductToCardItem($prod)
            )->values()->all();
        }
    }
  @endphp
  <section id="products" style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Our pieces' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Popular right now' }}</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:22px">
        @foreach($productsList as $p)
          @php
            $cardHref = $linkHref($p['href'] ?? route('shop.index'));
            $btnLabel = (empty($p['button_text']) || in_array($p['button_text'], ['Add to Cart', 'Bring it home'], true)) ? 'View Piece →' : $p['button_text'];
          @endphp
          <article style="flex:1 1 250px; min-width:0; background:#fff; border:1px solid #E3D8BC; border-radius:18px; overflow:hidden; display:flex; flex-direction:column; transition:box-shadow .2s, transform .2s"
                   onmouseover="this.style.boxShadow='0 10px 26px rgba(31,77,58,0.1)'; this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.boxShadow='none'; this.style.transform='none'">
            <a href="{{ $cardHref }}" style="display:flex; flex-direction:column; flex:1 1 auto; text-decoration:none; color:inherit">
              <div style="position:relative; height:280px; background:{{ $p['bg'] ?? 'linear-gradient(160deg,#C9B48A,#8E7545)' }}">
                @if(!empty($p['image_url']))
                  <img src="{{ $p['image_url'] }}" alt="{{ $p['name'] ?? '' }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600&auto=format&fit=crop';" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
                @else
                  <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#{{ $p['pattern'] ?? 'patCloth' }})"></rect></svg>
                  <div style="position:absolute; left:50%; bottom:22px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">Product photo</div>
                @endif
                <div aria-hidden="true" style="position:absolute; left:10px; right:10px; top:10px; bottom:10px; border:1px solid rgba(255,255,255,0.6); border-radius:10px"></div>
                @if(!empty($p['badge']))
                  <div style="position:absolute; right:18px; top:18px; background:#B8553A; color:#fff; font-weight:700; font-size:12px; padding:5px 11px; border-radius:999px">{{ $p['badge'] }}</div>
                @endif
              </div>
              <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
              <div style="padding:16px 20px 22px; display:flex; flex-direction:column; gap:6px; flex:1 1 auto">
                <div style="font-size:12px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#5A5044">{{ $p['district'] ?? '' }}</div>
                <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:24px; line-height:1.15; color:#1F4D3A">{{ $p['name'] ?? '' }}</h3>
                <div style="font-size:14px; color:#5A5044">Made by {{ $p['maker'] ?? '' }}</div>
                <div style="display:flex; align-items:baseline; gap:10px; margin-top:6px">
                  <span style="font-weight:700; font-size:19px; color:#2B2118">{{ $p['price'] ?? '' }}</span>
                  @if(!empty($p['old_price']))
                    <span style="font-size:14px; color:#6B5F50; text-decoration:line-through">{{ $p['old_price'] }}</span>
                  @endif
                </div>
                <span style="margin-top:auto; padding-top:12px; display:block">
                  <span style="display:flex; align-items:center; justify-content:center; background:#1F4D3A; color:#FBF6EC; border-radius:999px; padding:12px 20px; font-size:15px; font-weight:600; min-height:46px; box-sizing:border-box">{{ $btnLabel }}</span>
                </span>
              </div>
            </a>
          </article>
        @endforeach
      </div>
      @if(!empty($s['cta_text']))
        <div style="text-align:center; margin-top:36px">
          <a href="{{ $linkHref($s['cta_href'] ?? route('shop.index')) }}" style="display:inline-block; border:1.5px solid #1F4D3A; color:#1F4D3A; font-weight:600; padding:14px 30px; border-radius:999px; text-decoration:none; background:#fff">{{ $s['cta_text'] }}</a>
        </div>
      @endif
    </div>
  </section>

@elseif($type === 'home_story_timeline')
  @php
    $stepsList = $s['items'] ?? [];
    $bgColor = $s['bg_color'] ?? '#163829';
  @endphp
  <section style="background:{{ $bgColor }}; color:{{ $s['text_color'] ?? '#FBF6EC' }}; position:relative; overflow:hidden">
    @if($s['show_bands'] ?? true)
      <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
    @endif
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)" opacity="0.55"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:700px; margin:0 auto 56px; background:{{ $bgColor }}; padding:8px 24px 16px; border-radius:20px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#E3C26F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#E3C26F; margin-bottom:10px">{{ $s['eyebrow'] ?? '' }}</div>
        <h2 style="margin:0 0 12px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05">{{ $s['heading'] ?? '' }}</h2>
        <p style="margin:0; font-size:17px; color:#E8E0CF">{{ $s['subtitle'] ?? '' }}</p>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:36px 28px">
        @foreach($stepsList as $st)
          <div style="flex:1 1 220px; min-width:0; text-align:center; background:{{ $bgColor }}; padding:8px 8px 12px; border-radius:16px">
            <svg aria-hidden="true" width="76" height="76" viewBox="0 0 48 48" style="display:block; margin:0 auto 10px; color:#E3C26F"><use href="#rosetteArt"></use></svg>
            <div style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:40px; line-height:1; color:#E3C26F; margin-bottom:10px">{{ $st['when'] ?? '' }}</div>
            <svg aria-hidden="true" width="100" height="10" style="display:block; margin:0 auto 12px"><rect width="100" height="10" fill="url(#patStitchLight)"></rect></svg>
            <p style="margin:0 auto; font-size:16px; color:#F3EDDF; max-width:260px">{{ $st['text'] ?? '' }}</p>
          </div>
        @endforeach
      </div>
      @if(!empty($s['cta_text']))
        <div style="margin-top:48px; text-align:center">
          <a href="{{ $linkHref($s['cta_href'] ?? '#stories') }}" style="display:inline-block; background:#E3C26F; color:#2B2118; font-weight:700; padding:15px 30px; border-radius:999px; text-decoration:none">{{ $s['cta_text'] }}</a>
        </div>
      @endif
    </div>
    @if($s['show_bands'] ?? true)
      <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
    @endif
  </section>

@elseif($type === 'home_makers')
  @php
    $makersList = $s['items'] ?? [];
    $allMakerPlaceholders = !empty($makersList) && collect($makersList)->every(
        fn ($item) => empty($item['artisan_id']) && empty($item['image_url'])
    );
    if (empty($makersList) || $allMakerPlaceholders) {
        $liveArtisans = \App\Models\Artisan::with('district')
            ->where('is_verified', true)
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();
        if ($liveArtisans->isNotEmpty()) {
            $makersList = $liveArtisans->map(
                fn ($a) => \App\Livewire\Builder\VisualPageBuilder::mapArtisanToCardItem($a)
            )->values()->all();
        }
    }
  @endphp
  <section id="makers" style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Our makers' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Meet the hands behind every piece' }}</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
        <p style="margin:0; font-size:17px; color:#5A5044">{{ $s['subtitle'] ?? '' }}</p>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:22px">
        @foreach($makersList as $m)
          <article style="flex:1 1 250px; min-width:0; text-align:center">
            <a href="{{ $linkHref($m['href'] ?? '/artisans') }}" style="display:block; text-decoration:none; color:inherit">
              <div style="border:1.5px solid #B8892F; border-radius:999px 999px 16px 16px; padding:7px; box-sizing:border-box">
                <div style="position:relative; height:310px; border-radius:999px 999px 10px 10px; overflow:hidden; background:{{ $m['bg'] ?? 'linear-gradient(160deg,#6E7FB8,#2A3A6B)' }}">
                  @if(!empty($m['image_url']))
                    <img src="{{ $m['image_url'] }}" alt="{{ $m['name'] ?? '' }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
                  @else
                    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
                    <svg aria-hidden="true" viewBox="0 0 200 240" width="100%" height="100%" preserveAspectRatio="xMidYMax meet" style="position:absolute; left:0; top:0">
                      <circle cx="100" cy="96" r="38" fill="#FFFFFF" fill-opacity="0.38"></circle>
                      <path d="M30 240c0-52 31-84 70-84s70 32 70 84z" fill="#FFFFFF" fill-opacity="0.38"></path>
                    </svg>
                    <div style="position:absolute; left:50%; bottom:14px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">{{ $m['photo_label'] ?? 'Real portrait photo' }}</div>
                  @endif
                </div>
              </div>
              <h3 style="margin:16px 0 2px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; line-height:1.1; color:#1F4D3A">{{ $m['name'] ?? '' }}</h3>
              <div style="font-size:15px; font-weight:600; color:#2B2118">{{ $m['craft'] ?? '' }}</div>
              <div style="font-size:14px; color:#5A5044">{{ $m['place'] ?? '' }} · {{ $m['years'] ?? '' }} years of work</div>
            </a>
          </article>
        @endforeach
      </div>
    </div>
  </section>

@elseif($type === 'home_why')
  @php $whysList = $s['items'] ?? []; @endphp
  <section id="why" style="background:{{ $s['bg_color'] ?? '#F0E6D0' }}; position:relative; overflow:hidden">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.45"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:72px 24px">
      <div style="text-align:center; margin:0 auto 44px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(34px,4vw,50px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Why people buy from Sorolata' }}</h2>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:28px">
        @php
          $refinedWhyIcons = [
            'M12 2L14.4 8.6L21 11L14.4 13.4L12 20L9.6 13.4L3 11L9.6 8.6L12 2Z',
            'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z',
            'M12 21s-6-5.3-6-10a6 6 0 1 1 12 0c0 4.7-6 10-6 10zm0-7a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
            'M12 2l7 4v6c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-4zm-2 10l2 2 4-4',
          ];
        @endphp
        @foreach($whysList as $wIdx => $w)
          <div style="flex:1 1 230px; min-width:0; text-align:center; display:flex; flex-direction:column; align-items:center; gap:12px">
            <div style="width:66px; height:66px; border-radius:50%; background:#fff; border:1.5px solid #D9C28A; box-shadow:0 4px 14px rgba(43,33,24,0.06); display:flex; align-items:center; justify-content:center">
              <svg aria-hidden="true" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $refinedWhyIcons[$wIdx % 4] ?? ($w['d'] ?? 'M12 4a8 8 0 100 16 8 8 0 000-16z') }}"></path></svg>
            </div>
            <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:26px; line-height:1.1; color:#1F4D3A">{{ $w['title'] ?? '' }}</h3>
            <p style="margin:0; font-size:15px; color:#5A5044; max-width:260px">{{ $w['text'] ?? '' }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

@elseif($type === 'home_gifts')
  @php $giftsList = $s['items'] ?? []; @endphp
  <section style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Gift ideas' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'A gift with a story inside' }}</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
        <p style="margin:0; font-size:17px; color:#5A5044">{{ $s['subtitle'] ?? '' }}</p>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:20px">
        @foreach($giftsList as $g)
          <a href="{{ $linkHref($g['href'] ?? '#products') }}" style="position:relative; overflow:hidden; flex:1 1 250px; min-width:0; background:{{ $g['bg'] ?? 'linear-gradient(160deg,#2F6B50,#163829)' }}; color:#fff; border-radius:18px; padding:30px 26px; min-height:220px; display:flex; flex-direction:column; justify-content:space-between; text-decoration:none">
            @if(!empty($g['image_url']))
              <img src="{{ $g['image_url'] }}" alt="{{ $g['name'] ?? '' }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
            @else
              <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#{{ $g['pattern'] ?? 'patJaliLight' }})"></rect></svg>
            @endif
            <div aria-hidden="true" style="position:absolute; left:9px; right:9px; top:9px; bottom:9px; border:1px solid rgba(255,255,255,0.5); border-radius:12px"></div>
            <div style="position:relative; background:rgba(20,12,6,0.55); padding:12px 14px; border-radius:12px">
              <h3 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; line-height:1.1">{{ $g['name'] ?? '' }}</h3>
              <p style="margin:0; font-size:15px">{{ $g['text'] ?? '' }}</p>
            </div>
            <div style="position:relative; font-weight:700; font-size:15px; margin-top:20px; padding-left:14px">{{ $g['cta_text'] ?? 'See ideas →' }}</div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

@elseif($type === 'home_reviews')
  @php $reviewsList = $s['items'] ?? []; @endphp
  <section style="background:{{ $s['bg_color'] ?? '#F0E6D0' }}">
    <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
    <div style="max-width:1200px; margin:0 auto; padding:72px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 44px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Customer reviews' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'What our customers say' }}</h2>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:20px">
        @foreach($reviewsList as $r)
          <div style="position:relative; flex:1 1 300px; min-width:0; border:2px dashed #BBA57B; border-radius:18px; padding:28px 26px 22px; background:#FBF6EC; overflow:hidden">
            <svg aria-hidden="true" width="56" height="56" viewBox="0 0 56 56" style="position:absolute; right:0; top:0; color:#B8892F; opacity:0.7"><use href="#cornerArt"></use></svg>
            <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:64px; line-height:0.7; color:#B8892F; height:28px" aria-hidden="true">“</div>
            <p style="margin:0 0 18px; font-family:'Cormorant Garamond',Georgia,serif; font-size:23px; line-height:1.3; color:#2B2118">{{ $r['text'] ?? '' }}</p>
            <div style="font-size:14px; color:#5A5044">{{ $r['who'] ?? '' }}</div>
          </div>
        @endforeach
      </div>
      @if(!empty($s['note']))
        <p style="margin:20px 0 0; font-size:14px; color:#5A5044; text-align:center">{{ $s['note'] }}</p>
      @endif
    </div>
  </section>

@elseif($type === 'home_stories')
  @php
    $storiesList = $s['items'] ?? [];
    $allStoryPlaceholders = !empty($storiesList) && collect($storiesList)->every(
        fn ($item) => empty($item['story_id']) && empty($item['image_url'])
    );
    if (empty($storiesList) || $allStoryPlaceholders) {
        $liveStories = \App\Models\Story::where('is_published', true)
            ->orderByDesc('published_at')
            ->orderBy('id', 'asc')
            ->take(3)
            ->get();
        if ($liveStories->isNotEmpty()) {
            $storiesList = $liveStories->map(
                fn ($st) => \App\Livewire\Builder\VisualPageBuilder::mapStoryToCardItem($st)
            )->values()->all();
        }
    }
  @endphp
  <section id="stories" style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] ?? 'Stories' }}</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Read about the crafts' }}</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:22px">
        @foreach($storiesList as $st)
          <article style="flex:1 1 300px; min-width:0">
            <a href="{{ $linkHref($st['href'] ?? '#stories') }}" style="display:block; text-decoration:none">
              <div style="position:relative; height:210px; border-radius:18px; overflow:hidden; background:{{ $st['bg'] ?? 'linear-gradient(160deg,#6E7FB8,#2A3A6B)' }}">
                @if(!empty($st['image_url']))
                  <img src="{{ $st['image_url'] }}" alt="{{ $st['title'] ?? '' }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=800&auto=format&fit=crop';" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
                @else
                  <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#{{ $st['pattern'] ?? 'patCloth' }})"></rect></svg>
                  <div style="position:absolute; left:50%; bottom:20px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">{{ $st['photo_label'] ?? 'Story photo' }}</div>
                @endif
                <div aria-hidden="true" style="position:absolute; left:9px; right:9px; top:9px; bottom:9px; border:1px solid rgba(255,255,255,0.55); border-radius:11px"></div>
              </div>
              <div style="font-size:13px; font-weight:600; color:#5A5044; margin:14px 0 6px; letter-spacing:0.04em">{{ $st['tag'] ?? '' }} · {{ $st['read'] ?? '' }}</div>
              <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:27px; line-height:1.15; color:#1F4D3A">{{ $st['title'] ?? '' }}</h3>
            </a>
          </article>
        @endforeach
      </div>
      @if(!empty($s['cta_text']))
        <div style="text-align:center; margin-top:36px">
          <a href="{{ $linkHref($s['cta_href'] ?? route('stories.index')) }}" style="display:inline-block; border:1.5px solid #1F4D3A; color:#1F4D3A; font-weight:600; padding:14px 30px; border-radius:999px; text-decoration:none; background:#fff">{{ $s['cta_text'] }}</a>
        </div>
      @endif
    </div>
  </section>

@elseif($type === 'home_newsletter')
  @php $cardBg = $s['card_bg'] ?? '#1F4D3A'; @endphp
  <section style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:0 24px 80px">
      <div style="position:relative; overflow:hidden; background:{{ $cardBg }}; color:#FBF6EC; border-radius:26px; padding:48px 40px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:28px">
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
        <div aria-hidden="true" style="position:absolute; left:10px; right:10px; top:10px; bottom:10px; border:1px solid rgba(227,194,111,0.55); border-radius:18px"></div>
        <div style="position:relative; flex:1 1 320px; min-width:0; background:{{ $cardBg }}; padding:6px 10px; border-radius:12px">
          <h2 style="margin:0 0 8px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(32px,3.6vw,44px); line-height:1.05">{{ $s['heading'] ?? 'Get updates from Sorolata' }}</h2>
          <p style="margin:0; font-size:16px; color:#E8E0CF">{{ $s['subtitle'] ?? '' }}</p>
        </div>
        <form @if(!$isBuilder) onsubmit="event.preventDefault(); this.reset();" @else onsubmit="event.preventDefault();" @endif style="position:relative; flex:1 1 360px; min-width:0; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; background:{{ $cardBg }}; padding:6px 10px; border-radius:12px">
          <div style="flex:1 1 220px; min-width:0; display:flex; flex-direction:column; gap:6px">
            <label for="email-{{ $section['id'] ?? 'nl' }}" style="font-size:14px; font-weight:600">{{ $s['email_label'] ?? 'Your email' }}</label>
            <input id="email-{{ $section['id'] ?? 'nl' }}" type="email" placeholder="{{ $s['email_placeholder'] ?? 'name@example.com' }}" style="height:50px; border-radius:999px; border:0; padding:0 22px; font-size:15px; background:#FBF6EC; color:#2B2118; box-sizing:border-box; width:100%">
          </div>
          <button type="submit" style="height:50px; border:0; border-radius:999px; background:#E3C26F; color:#2B2118; font-weight:700; font-size:15px; padding:0 28px">{{ $s['button_text'] ?? 'Join' }}</button>
        </form>
      </div>
    </div>
  </section>

@elseif($type === 'home_footer')
  @php
    $shopLinks = $s['shop_links'] ?? [];
    if (!collect($shopLinks)->contains(fn($l) => ($l['href'] ?? '') === '/' || strtolower((string) ($l['label'] ?? '')) === 'home')) {
        array_unshift($shopLinks, ['label' => 'Home', 'href' => '/']);
    }
    $helpLinks = $s['help_links'] ?? [];
    $contactLines = $s['contact_lines'] ?? [];
  @endphp
  <footer id="contact" style="background:{{ $s['bg_color'] ?? '#163829' }}; color:#F3EDDF; position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)" opacity="0.4"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:56px 24px 28px; display:flex; flex-wrap:wrap; gap:40px">
      <div style="flex:1.5 1 300px; min-width:0">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px">
          <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:38px; color:#FBF6EC">{{ $s['brand_name'] ?? 'Sorolata' }}</span>
          @php $blkFooterBrandBn = (($s['brand_bn'] ?? '') === 'সারলতা' || empty($s['brand_bn'])) ? 'সরলতা' : $s['brand_bn']; @endphp
          @if(!empty($blkFooterBrandBn))
            <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#E3C26F; border:1px solid #E3C26F; border-radius:999px; padding:2px 12px">{{ $blkFooterBrandBn }}</span>
          @endif
        </div>
        <p style="margin:0; font-size:15px; max-width:380px; color:#E8E0CF">{{ $s['tagline'] ?? '' }}</p>
      </div>
      <div style="flex:1 1 160px; min-width:0">
        <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">{{ $s['col1_title'] ?? 'Shop' }}</h3>
        <div style="display:flex; flex-direction:column; gap:4px; font-size:15px">
          @foreach($shopLinks as $f)
            <a href="{{ $linkHref($f['href'] ?? '#') }}" style="padding:6px 0">{{ $f['label'] ?? '' }}</a>
          @endforeach
        </div>
      </div>
      <div style="flex:1 1 160px; min-width:0">
        <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">{{ $s['col2_title'] ?? 'Help' }}</h3>
        <div style="display:flex; flex-direction:column; gap:4px; font-size:15px">
          @foreach($helpLinks as $f)
            <a href="{{ $linkHref($f['href'] ?? '#') }}" style="padding:6px 0">{{ $f['label'] ?? '' }}</a>
          @endforeach
        </div>
      </div>
      <div style="flex:1 1 220px; min-width:0">
        <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">{{ $s['col3_title'] ?? 'Talk to us' }}</h3>
        <div style="display:flex; flex-direction:column; gap:8px; font-size:15px">
          @foreach($contactLines as $c)
            <span>{{ $c['text'] ?? $c }}</span>
          @endforeach
        </div>
      </div>
    </div>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:0 24px 32px">
      <svg aria-hidden="true" width="100%" height="10" style="display:block; margin-bottom:16px"><rect width="100%" height="10" fill="url(#patStitchLight)"></rect></svg>
      <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; font-size:13px; color:#CFC7B4">
        <span>{{ $s['copyright_text'] ?? '© 2026 Sorolata. All rights reserved.' }}</span>
        <span>{{ $s['payment_text'] ?? 'We accept bKash, Nagad, Visa and Mastercard' }}</span>
      </div>
    </div>
  </footer>

@elseif($type === 'home_page_header')
  <section style="position:relative; overflow:hidden; background:{{ $s['bg_color'] ?? '#F0E6D0' }}; border-bottom:1px solid #E3D8BC">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.4"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:56px 24px; text-align:center">
      @if(!empty($s['breadcrumb']))
        <div style="display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#5A5044; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:14px">
          <a href="{{ $linkHref('/') }}" style="color:#1F4D3A; font-weight:600">Home</a>
          <span>/</span>
          <span>{{ $s['breadcrumb'] }}</span>
        </div>
      @endif
      <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
      @if(!empty($s['eyebrow']))
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] }}</div>
      @endif
      <h1 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.5vw,58px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['title'] ?? 'Sorolata Heritage' }}</h1>
      <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      @if(!empty($s['subtitle']))
        <p style="margin:0 auto; max-width:680px; font-size:17px; color:#5A5044; line-height:1.6">{{ $s['subtitle'] }}</p>
      @endif
    </div>
  </section>
  @if($s['show_band'] ?? false)
    <svg aria-hidden="true" width="100%" height="48" style="display:block; background:#FBF6EC"><rect width="100%" height="48" fill="url(#patBandGold)"></rect></svg>
  @endif

@elseif($type === 'home_rich_text')
  <section style="background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    <div style="max-width:1200px; margin:0 auto; padding:64px 24px; display:flex; flex-wrap:wrap; align-items:center; gap:48px">
      <div style="flex:1.2 1 380px; min-width:0">
        @if(!empty($s['eyebrow']))
          <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] }}</div>
        @endif
        @if(!empty($s['heading']))
          <h2 style="margin:0 0 14px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(32px,3.8vw,48px); line-height:1.08; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] }}</h2>
          <svg aria-hidden="true" width="120" height="10" style="display:block; margin:0 0 18px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
        @endif
        @if(!empty($s['body']))
          <div style="font-size:17px; color:#5A5044; line-height:1.75; white-space:pre-line">{{ $s['body'] }}</div>
        @endif
        @if(!empty($s['cta_text']))
          <div style="margin-top:24px">
            <a href="{{ $linkHref($s['cta_href'] ?? '/shop') }}" style="display:inline-block; background:#1F4D3A; color:#FBF6EC; font-weight:600; padding:14px 28px; border-radius:999px; text-decoration:none; font-size:15px">{{ $s['cta_text'] }}</a>
          </div>
        @endif
      </div>
      @if(!empty($s['image_url']))
        <div style="flex:1 1 340px; min-width:0">
          <div style="border:1.5px solid #B8892F; border-radius:24px; padding:8px; background:#FBF6EC">
            <img src="{{ $s['image_url'] }}" alt="{{ $s['heading'] ?? 'Sorolata' }}" style="width:100%; max-height:420px; object-fit:cover; border-radius:16px; display:block">
          </div>
        </div>
      @endif
    </div>
  </section>

@elseif($type === 'home_product_showcase')
  @php
    $pObj = $product ?? null;
    $galleryImages = $pObj ? $pObj->media->map(fn($m) => [
        'url' => url($m->file_path),
        'alt' => $m->alt_text ?: $pObj->getLocalized('title'),
    ])->values()->all() : [];

    if (empty($galleryImages)) {
        $galleryImages = [[
            'url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1000&auto=format&fit=crop',
            'alt' => $s['title'] ?? 'Sorolata Piece',
        ]];
    }

    $hasDiscount = $pObj && !empty($pObj->compare_at_price) && $pObj->compare_at_price > $pObj->price;
    $discountPercent = $hasDiscount ? round((($pObj->compare_at_price - $pObj->price) / $pObj->compare_at_price) * 100) : 0;
    $isOutOfStock = $pObj && (!$pObj->is_in_stock || $pObj->status === 'out_of_stock' || (is_object($pObj->status) && $pObj->status->value === 'out_of_stock'));
    $isWishlisted = (!$isBuilder && $pObj) ? in_array($pObj->id, session('sorolata_wishlist', [])) : false;
    $districtName = !empty($s['district_label']) ? $s['district_label'] : ($pObj?->district?->name_en ?? $pObj?->district?->getLocalized('name') ?? 'Bangladesh');
    $pieceTitle = !empty($s['title']) ? $s['title'] : ($pObj ? $pObj->getLocalized('title') : 'Handcrafted Piece');
    $artisanName = !empty($s['artisan_label']) ? $s['artisan_label'] : ($pObj?->artisan?->getLocalized('full_name') ?? 'Master Maker');
    $subtitleText = !empty($s['subtitle']) ? $s['subtitle'] : ($pObj ? $pObj->getLocalized('subtitle') : '');
    $trustItems = $s['trust_items'] ?? [['text' => 'Made by hand'], ['text' => 'Fair pay to makers'], ['text' => 'Ships worldwide']];

    $priceVal = $pObj ? (float) $pObj->price : 18500;
    $formattedPrice = '৳ ' . number_format($priceVal, 0);
    $compareVal = $hasDiscount ? (float) $pObj->compare_at_price : null;
    $formattedCompare = $compareVal ? '৳ ' . number_format($compareVal, 0) : null;
  @endphp
  <section style="position:relative; overflow:hidden; background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:32px 24px 72px">
      @if($pObj)
        <nav aria-label="Breadcrumb" style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; font-size:13px; color:#5A5044; margin-bottom:28px">
          <a href="{{ $linkHref('/') }}" style="color:#1F4D3A; font-weight:600">Home</a>
          <span>/</span>
          <a href="{{ $linkHref('/shop') }}" style="color:#1F4D3A; font-weight:600">Shop</a>
          @if($pObj->district)
            <span>/</span>
            <a href="{{ $linkHref(route('district.show', $pObj->district->slug ?? $pObj->district->uuid)) }}" style="color:#1F4D3A; font-weight:600">{{ $districtName }}</a>
          @endif
          <span>/</span>
          <span>{{ $pieceTitle }}</span>
        </nav>
      @endif

      <div style="display:flex; flex-wrap:wrap; gap:48px; align-items:flex-start"
           x-data="{
               active: 0,
               images: @js($galleryImages),
               prev() { this.active = (this.active - 1 + this.images.length) % this.images.length },
               next() { this.active = (this.active + 1) % this.images.length }
           }">

        <!-- Left: Multi-Image Gallery -->
        <div style="flex:1 1 440px; min-width:0">
          <div style="border:2px solid #B8892F; border-radius:24px; padding:10px; background:#FBF6EC">
            <div style="border:1px solid #B8892F; border-radius:16px; padding:8px">
              <div style="position:relative; aspect-ratio:4/5; border-radius:12px; overflow:hidden; background:#F0E6D0">
                <template x-for="(img, idx) in images" :key="idx">
                  <img x-show="active === idx" :src="img.url" :alt="img.alt" style="width:100%; height:100%; object-fit:cover; display:block">
                </template>

                @if($isOutOfStock)
                  <div style="position:absolute; right:16px; top:16px; background:#2B2118; color:#FBF6EC; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px">Sold out</div>
                @elseif($hasDiscount)
                  <div style="position:absolute; right:16px; top:16px; background:#B8553A; color:#fff; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px">{{ $discountPercent }}% off</div>
                @endif

                @if(count($galleryImages) > 1 && !$isBuilder)
                  <button type="button" @click="prev()" aria-label="Previous photo"
                          style="position:absolute; left:12px; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-weight:700; display:flex; align-items:center; justify-content:center">←</button>
                  <button type="button" @click="next()" aria-label="Next photo"
                          style="position:absolute; right:12px; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-weight:700; display:flex; align-items:center; justify-content:center">→</button>
                @endif
              </div>
            </div>
          </div>

          @if(count($galleryImages) > 1)
            <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:16px">
              @foreach($galleryImages as $idx => $img)
                <button type="button"
                        @if(!$isBuilder) @click="active = {{ $idx }}" @endif
                        :style="active === {{ $idx }} ? 'border:2px solid #1F4D3A; padding:3px; background:#FBF6EC;' : 'border:1px solid #D9C28A; padding:3px; background:#fff; opacity:0.78;'"
                        style="width:80px; height:80px; border-radius:14px; overflow:hidden; cursor:pointer">
                  <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}" style="width:100%; height:100%; object-fit:cover; border-radius:10px; display:block">
                </button>
              @endforeach
            </div>
          @endif
        </div>

        <!-- Right: Product Info & Popping Price -->
        <div style="flex:1 1 440px; min-width:0; padding-top:8px">
          <div style="font-size:12px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">
            {{ $districtName }}
          </div>

          <h1 style="margin:0 0 10px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,54px); line-height:1.04; color:{{ $s['text_color'] ?? '#1F2A24' }}">
            {{ $pieceTitle }}
          </h1>

          <div style="font-size:16px; color:#5A5044; margin-bottom:18px">
            Made by
            @if($pObj && $pObj->artisan)
              <a href="{{ $linkHref(route('artisan.show', $pObj->artisan->slug ?? $pObj->artisan->uuid)) }}" style="color:#1F4D3A; font-weight:700; text-decoration:underline; text-underline-offset:4px">{{ $artisanName }}</a>
            @else
              <span style="color:#1F4D3A; font-weight:700">{{ $artisanName }}</span>
            @endif
          </div>

          <!-- High-Contrast Popping Price Card -->
          <div style="display:inline-flex; flex-wrap:wrap; align-items:baseline; gap:14px; background:#FBF6EC; border:1.5px solid #D9C28A; border-radius:14px; padding:10px 20px; box-shadow:0 4px 14px rgba(31,77,58,0.08); margin-bottom:22px">
            <span style="font-family:'Manrope','Hind Siliguri',sans-serif; font-weight:800; font-size:28px; line-height:1.1; color:#1F4D3A">{{ $formattedPrice }}</span>
            @if($formattedCompare)
              <span style="font-family:'Manrope',sans-serif; font-size:16px; font-weight:600; color:#8F3F26; text-decoration:line-through">{{ $formattedCompare }}</span>
            @endif
          </div>

          @if($subtitleText)
            <p style="margin:0 0 24px; font-size:17px; color:#5A5044; line-height:1.6">{{ $subtitleText }}</p>
          @endif

          <svg aria-hidden="true" width="100%" height="10" style="display:block; margin-bottom:24px"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>

          @if(!$isBuilder && session('success'))
            <div style="margin-bottom:16px; padding:12px 16px; border-radius:14px; background:#E7EEE4; border:1px solid #C5D3BF; color:#1F4D3A; font-size:14px; font-weight:600">
              {{ session('success') }}
            </div>
          @endif

          @if(!$isOutOfStock)
            <form @if(!$isBuilder && $pObj) action="{{ route('cart.store') }}" method="POST" @else onsubmit="event.preventDefault();" @endif style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:20px" x-data="{ qty: 1 }">
              @if(!$isBuilder) @csrf @endif
              @if($pObj)<input type="hidden" name="product_id" value="{{ $pObj->id }}">@endif
              <input type="hidden" name="quantity" :value="qty">

              <div style="display:inline-flex; align-items:center; border:1.5px solid #D9C28A; border-radius:999px; background:#fff; padding:4px">
                <button type="button" @click="if(qty > 1) qty--" style="width:38px; height:38px; border:0; background:none; color:#1F4D3A; font-size:18px; font-weight:700">−</button>
                <span x-text="qty" style="min-width:32px; text-align:center; font-weight:700; font-size:15px; color:#2B2118">1</span>
                <button type="button" @click="qty++" style="width:38px; height:38px; border:0; background:none; color:#1F4D3A; font-size:18px; font-weight:700">+</button>
              </div>

              <button type="submit" style="flex:1 1 200px; background:#1F4D3A; color:#FBF6EC; border:0; border-radius:999px; padding:15px 30px; font-size:16px; font-weight:600; min-height:50px">
                {{ (empty($s['add_to_cart_text']) || $s['add_to_cart_text'] === 'Add to Cart') ? 'Bring it home' : $s['add_to_cart_text'] }}
              </button>
            </form>
          @else
            <form @if(!$isBuilder && $pObj) action="{{ route('products.notify-restock', $pObj->slug) }}" method="POST" @else onsubmit="event.preventDefault();" @endif style="background:#FBF6EC; border:1px solid #D9C28A; border-radius:18px; padding:18px; margin-bottom:20px">
              @if(!$isBuilder) @csrf @endif
              <div style="font-size:14px; font-weight:700; color:#1F2A24; margin-bottom:8px">This piece is sold out. Get notified when a new one is ready:</div>
              <div style="display:flex; flex-wrap:wrap; gap:10px">
                <input type="email" name="email" required placeholder="Your email" style="flex:1 1 200px; height:46px; border-radius:999px; border:1px solid #D9C28A; padding:0 18px; font-size:14px; background:#fff; color:#2B2118">
                <button type="submit" style="height:46px; border:0; border-radius:999px; background:#1F4D3A; color:#FBF6EC; font-weight:600; font-size:14px; padding:0 24px">Notify me</button>
              </div>
            </form>
          @endif

          <form @if(!$isBuilder && $pObj) action="{{ route('wishlist.toggle') }}" method="POST" @else onsubmit="event.preventDefault();" @endif style="margin-bottom:28px">
            @if(!$isBuilder) @csrf @endif
            @if($pObj)<input type="hidden" name="product_id" value="{{ $pObj->id }}">@endif
            <button type="submit" style="display:inline-flex; align-items:center; gap:8px; border:1.5px solid #D9C28A; background:{{ $isWishlisted ? '#B8553A' : '#fff' }}; color:{{ $isWishlisted ? '#fff' : '#1F4D3A' }}; border-radius:999px; padding:10px 22px; font-size:14px; font-weight:600">
              <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              <span>{{ $isWishlisted ? ($s['wishlist_saved_text'] ?? 'Saved in Wishlist') : ($s['wishlist_text'] ?? 'Save to Wishlist') }}</span>
            </button>
          </form>

          @if(count($trustItems) > 0)
            <div style="display:flex; flex-wrap:wrap; gap:14px 28px; font-size:14px; font-weight:600; color:#2B2118">
              @foreach($trustItems as $t)
                <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>{{ $t['text'] ?? $t }}</span>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  @if($s['show_band'] ?? true)
    <svg aria-hidden="true" width="100%" height="48" style="display:block; background:#FBF6EC"><rect width="100%" height="48" fill="url(#patBandGold)"></rect></svg>
  @endif
@endif
