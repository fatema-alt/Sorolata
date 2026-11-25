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
  @endphp
  @if(($s['show_topbar'] ?? true))
    <div style="background:{{ $topBarBg }}; color:{{ $topBarColor }}; font-size:12px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; text-align:center; padding:10px 16px">
      {{ $s['topbar_text'] ?? 'Free insured delivery to Bangladesh, USA, UK, Canada and Europe' }}
    </div>
  @endif
  <header style="background:{{ $headerBg }}">
    <div style="max-width:1200px; margin:0 auto; padding:14px 24px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 28px">
      <a href="{{ $linkHref($s['brand_href'] ?? '#top') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none">
        <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:34px; color:#1F2A24; letter-spacing:-0.01em">{{ $s['brand_name'] ?? 'Sorolata' }}</span>
        @if(!empty($s['brand_bn'] ?? 'সারলতা'))
          <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#1F4D3A; background:#E7EEE4; border:1px solid #C5D3BF; border-radius:999px; padding:2px 12px">{{ $s['brand_bn'] ?? 'সারলতা' }}</span>
        @endif
      </a>
      <nav aria-label="Main" style="display:flex; flex-wrap:wrap; gap:4px 26px; font-weight:500; font-size:15px; color:#3A332B">
        @foreach($navLinks as $n)
          <a href="{{ $linkHref($n['href'] ?? '#') }}" style="padding:10px 0">{{ $n['label'] ?? '' }}</a>
        @endforeach
      </nav>
      <div style="display:flex; align-items:center; gap:10px">
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
    </div>
    @if($s['show_stitch_border'] ?? true)
      <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
    @endif
  </header>

@elseif($type === 'home_hero')
  @php
    $bgColor = $s['bg_color'] ?? '#F7F5F0';
    $accentColor = $s['accent_color'] ?? '#B8892F';
    $primaryBg = $s['primary_color'] ?? '#1F4D3A';
    $trustItems = $s['trust_items'] ?? [];
  @endphp
  <section id="top" style="position:relative; overflow:hidden; background:{{ $bgColor }}">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:64px 24px 72px; display:flex; flex-wrap:wrap; align-items:center; gap:48px">
      <div style="flex:1 1 420px; min-width:0">
        <h1 style="margin:0 0 22px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(46px,5.8vw,80px); line-height:1.0; letter-spacing:-0.01em; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['headline'] ?? '' }}</h1>
        <p style="margin:0 0 32px; font-size:18px; color:#5A5044; max-width:500px">{{ $s['subheadline'] ?? '' }}</p>
        <div style="display:flex; flex-wrap:wrap; gap:14px; margin-bottom:36px">
          @if(!empty($s['primary_cta_text']))
            <a href="{{ $linkHref($s['primary_cta_href'] ?? '#products') }}" style="background:{{ $primaryBg }}; color:#fff; font-weight:600; padding:16px 30px; border-radius:999px; text-decoration:none; font-size:16px">{{ $s['primary_cta_text'] }}</a>
          @endif
          @if(!empty($s['secondary_cta_text']))
            <a href="{{ $linkHref($s['secondary_cta_href'] ?? '#districts') }}" style="border:1.5px solid {{ $primaryBg }}; color:{{ $primaryBg }}; font-weight:600; padding:14.5px 28px; border-radius:999px; text-decoration:none; font-size:16px; background:#fff">{{ $s['secondary_cta_text'] }}</a>
          @endif
        </div>
        @if(count($trustItems) > 0)
          <div style="display:flex; flex-wrap:wrap; gap:14px 30px; font-size:14px; font-weight:600; color:#2B2118">
            @foreach($trustItems as $t)
              <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:{{ $accentColor }}"><use href="#rosetteArt"></use></svg>{{ $t['text'] ?? $t }}</span>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Arch frame -->
      <div style="flex:1 1 380px; min-width:0; display:flex; justify-content:center">
        <div style="width:100%; max-width:460px; border:2px solid {{ $accentColor }}; border-radius:999px 999px 20px 20px; padding:10px; background:#FBF6EC; box-sizing:border-box">
          <div style="border:1px solid {{ $accentColor }}; border-radius:999px 999px 14px 14px; padding:8px; box-sizing:border-box">
            <div style="position:relative; min-height:560px; border-radius:999px 999px 10px 10px; overflow:hidden; background:linear-gradient(170deg,#E09A6E 0%,#B8553A 48%,#5E2618 100%)">
              @if(!empty($s['image_url']))
                <img src="{{ $s['image_url'] }}" alt="{{ $s['featured_title'] ?? 'Featured craft' }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
              @else
                <svg aria-hidden="true" width="100%" height="100%" viewBox="0 0 400 560" preserveAspectRatio="xMidYMid slice" style="position:absolute; left:0; top:0">
                  <rect width="400" height="560" fill="url(#patCloth)" opacity="0.5"></rect>
                  <g stroke="#FFE9C2" stroke-opacity="0.35" stroke-width="1.2" fill="none">
                    <path d="M200 560L-364 356M200 560L-260 174M200 560L-100 40M200 560L96 -31M200 560L200 -40M200 560L304 -31M200 560L500 40M200 560L660 174M200 560L764 356"></path>
                    <path d="M40 560A160 160 0 0 1 360 560"></path>
                    <path d="M-20 560A220 220 0 0 1 420 560"></path>
                    <path d="M-80 560A280 280 0 0 1 480 560" stroke-dasharray="2 5"></path>
                  </g>
                  <circle cx="200" cy="330" r="46" fill="#FFE9C2" fill-opacity="0.18" stroke="#FFE9C2" stroke-opacity="0.6" stroke-width="1.5"></circle>
                  <circle cx="200" cy="330" r="30" fill="none" stroke="#FFE9C2" stroke-opacity="0.6" stroke-width="1.2" stroke-dasharray="2 4"></circle>
                </svg>
              @endif
              <a href="{{ $linkHref($s['featured_href'] ?? '#products') }}" style="position:absolute; left:16px; right:16px; bottom:16px; background:#FBF6EC; border-radius:16px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; border:1px solid #D9C28A; text-decoration:none">
                <div>
                  <div style="font-size:12px; color:#5A5044; letter-spacing:0.06em; text-transform:uppercase">{{ $s['featured_eyebrow'] ?? '' }}</div>
                  <div style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:21px; color:#1F4D3A">{{ $s['featured_title'] ?? '' }}</div>
                </div>
                <div style="font-weight:700; font-size:17px; color:#8F3F26">{{ $s['featured_price'] ?? '' }}</div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
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
  @php $productsList = $s['items'] ?? []; @endphp
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
          <article style="flex:1 1 250px; min-width:0; background:#fff; border:1px solid #E3D8BC; border-radius:18px; overflow:hidden; display:flex; flex-direction:column">
            <div style="position:relative; height:280px; background:{{ $p['bg'] ?? 'linear-gradient(160deg,#C9B48A,#8E7545)' }}">
              @if(!empty($p['image_url']))
                <img src="{{ $p['image_url'] }}" alt="{{ $p['name'] ?? '' }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
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
              <button type="button" @if(!$isBuilder) onclick="window.location='{{ $resolveUrl($p['href'] ?? route('shop.index')) }}'" @endif style="margin-top:12px; background:#1F4D3A; color:#FBF6EC; border:0; border-radius:999px; padding:13px 20px; font-size:15px; font-weight:600; min-height:46px">{{ $p['button_text'] ?? 'Add to Cart' }}</button>
            </div>
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
  @php $makersList = $s['items'] ?? []; @endphp
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
        @foreach($whysList as $w)
          <div style="flex:1 1 230px; min-width:0; text-align:center; display:flex; flex-direction:column; align-items:center; gap:10px">
            <div style="position:relative; width:96px; height:96px; display:flex; align-items:center; justify-content:center">
              <svg aria-hidden="true" width="96" height="96" viewBox="0 0 48 48" style="position:absolute; left:0; top:0; color:#B8892F"><use href="#rosetteArt"></use></svg>
              <div style="position:relative; width:54px; height:54px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center">
                <svg aria-hidden="true" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $w['d'] ?? 'M12 4a8 8 0 100 16 8 8 0 000-16z' }}"></path></svg>
              </div>
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
  @php $storiesList = $s['items'] ?? []; @endphp
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
                  <img src="{{ $st['image_url'] }}" alt="{{ $st['title'] ?? '' }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover">
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
          @if(!empty($s['brand_bn'] ?? 'সারলতা'))
            <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#E3C26F; border:1px solid #E3C26F; border-radius:999px; padding:2px 12px">{{ $s['brand_bn'] ?? 'সারলতা' }}</span>
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
    $artisanYears = !empty($s['artisan_years']) ? $s['artisan_years'] : ($pObj?->artisan?->years_of_experience ? $pObj->artisan->years_of_experience . ' years of handcraft' : '');
    $subtitleText = !empty($s['subtitle']) ? $s['subtitle'] : ($pObj ? $pObj->getLocalized('subtitle') : '');
    $deliveryOptions = !empty($s['delivery_options']) && is_array($s['delivery_options']) ? $s['delivery_options'] : [
        ['label' => 'Inside Bangladesh', 'note' => 'Delivered in 2 to 4 days · Pay on delivery or online · Free over ৳ 5,000'],
        ['label' => 'Send abroad (USA, UK, EU, Canada)', 'note' => 'Tracked air parcel in 7 to 10 days · Customs receipt included'],
    ];
    $trustItems = $s['trust_items'] ?? [['text' => 'Made by hand'], ['text' => 'Fair pay to makers'], ['text' => 'Ships worldwide']];

    $priceVal = $pObj ? (float) $pObj->price : 18500;
    $formattedPrice = '৳ ' . number_format($priceVal, 0);
    $compareVal = $hasDiscount ? (float) $pObj->compare_at_price : null;
    $formattedCompare = $compareVal ? '৳ ' . number_format($compareVal, 0) : null;
  @endphp
  <style>
    .soro-sbar { display: none; }
    @media (max-width: 767px) {
      .soro-sbar {
        display: flex; position: fixed; left: 0; right: 0; bottom: 0; z-index: 45;
        background: rgba(251, 246, 236, 0.96); backdrop-filter: blur(8px);
        border-top: 1.5px solid #D9C28A; padding: 10px 16px;
        align-items: center; justify-content: space-between; gap: 12px;
        box-shadow: 0 -6px 24px rgba(43, 33, 24, 0.12);
      }
    }
  </style>
  <section style="position:relative; overflow:hidden; background:{{ $s['bg_color'] ?? '#F7F5F0' }}"
           x-data="{
               active: 0,
               lightbox: false,
               deliveryIdx: 0,
               qty: 1,
               images: @js($galleryImages),
               deliveryOptions: @js($deliveryOptions),
               prev() { this.active = (this.active - 1 + this.images.length) % this.images.length },
               next() { this.active = (this.active + 1) % this.images.length }
           }"
           @keydown.escape.window="lightbox = false">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:28px 24px 68px">
      @if($pObj)
        <nav aria-label="Breadcrumb" style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; font-size:13px; color:#5A5044; margin-bottom:26px">
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

      <div style="display:flex; flex-wrap:wrap; gap:48px; align-items:flex-start">
        <!-- Left: Brass-Framed Multi-Image Gallery -->
        <div style="flex:1 1 440px; min-width:0">
          <div style="position:relative; border:2px solid #B8892F; border-radius:20px; padding:10px; background:#FBF6EC; box-shadow:0 16px 36px rgba(43,33,24,0.08)">
            <!-- Corner Brackets -->
            <span aria-hidden="true" style="position:absolute; left:6px; top:6px; width:14px; height:14px; border-left:2px solid #1F4D3A; border-top:2px solid #1F4D3A; border-radius:4px 0 0 0; pointer-events:none"></span>
            <span aria-hidden="true" style="position:absolute; right:6px; top:6px; width:14px; height:14px; border-right:2px solid #1F4D3A; border-top:2px solid #1F4D3A; border-radius:0 4px 0 0; pointer-events:none"></span>
            <span aria-hidden="true" style="position:absolute; left:6px; bottom:6px; width:14px; height:14px; border-left:2px solid #1F4D3A; border-bottom:2px solid #1F4D3A; border-radius:0 0 0 4px; pointer-events:none"></span>
            <span aria-hidden="true" style="position:absolute; right:6px; bottom:6px; width:14px; height:14px; border-right:2px solid #1F4D3A; border-bottom:2px solid #1F4D3A; border-radius:0 0 4px 0; pointer-events:none"></span>

            <div style="border:1px solid #D9C28A; border-radius:14px; padding:6px; background:#fff">
              <div style="position:relative; aspect-ratio:4/5; border-radius:10px; overflow:hidden; background:#F0E6D0">
                <template x-for="(img, idx) in images" :key="idx">
                  <img x-show="active === idx" :src="img.url" :alt="img.alt"
                       @if(!$isBuilder) @click="lightbox = true" @endif
                       style="width:100%; height:100%; object-fit:cover; display:block; {{ !$isBuilder ? 'cursor:zoom-in;' : '' }}">
                </template>

                @if($isOutOfStock)
                  <div style="position:absolute; left:14px; top:14px; background:#2B2118; color:#FBF6EC; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px">Sold out</div>
                @elseif($hasDiscount)
                  <div style="position:absolute; left:14px; top:14px; background:#B8553A; color:#fff; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px">{{ $discountPercent }}% off</div>
                @endif

                @if(!$isBuilder)
                  <button type="button" @click="lightbox = true" aria-label="Zoom photo"
                          style="position:absolute; right:14px; top:14px; width:38px; height:38px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-size:16px; font-weight:700; display:flex; align-items:center; justify-content:center; cursor:pointer">⤢</button>
                @endif

                @if(count($galleryImages) > 1 && !$isBuilder)
                  <button type="button" @click="prev()" aria-label="Previous photo"
                          style="position:absolute; left:12px; top:50%; transform:translateY(-50%); width:38px; height:38px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-weight:700; display:flex; align-items:center; justify-content:center; cursor:pointer">←</button>
                  <button type="button" @click="next()" aria-label="Next photo"
                          style="position:absolute; right:12px; top:50%; transform:translateY(-50%); width:38px; height:38px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-weight:700; display:flex; align-items:center; justify-content:center; cursor:pointer">→</button>
                  <div style="position:absolute; right:14px; bottom:14px; background:rgba(31,42,36,0.82); color:#FBF6EC; font-size:12px; font-weight:700; padding:4px 12px; border-radius:999px"
                       x-text="(active + 1) + ' / ' + images.length">1 / {{ count($galleryImages) }}</div>
                @endif
              </div>
            </div>
          </div>

          @if(count($galleryImages) > 1)
            <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:14px">
              @foreach($galleryImages as $idx => $img)
                <button type="button"
                        @if(!$isBuilder) @click="active = {{ $idx }}" @endif
                        :style="active === {{ $idx }} ? 'border:2px solid #1F4D3A; padding:3px; background:#FBF6EC;' : 'border:1px solid #D9C28A; padding:3px; background:#fff; opacity:0.78;'"
                        style="width:72px; height:72px; border-radius:12px; overflow:hidden; cursor:pointer">
                  <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}" style="width:100%; height:100%; object-fit:cover; border-radius:8px; display:block">
                </button>
              @endforeach
            </div>
          @endif
        </div>

        <!-- Right: Product Info, Popping Price Pill, Delivery Selector & Actions -->
        <div style="flex:1 1 440px; min-width:0; padding-top:4px">
          <div style="display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">
            <span aria-hidden="true" style="display:inline-block; width:7px; height:7px; background:#B8892F; transform:rotate(45deg)"></span>
            <span>{{ $districtName }}</span>
          </div>

          <h1 style="margin:0 0 10px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(34px,4.1vw,52px); line-height:1.05; color:{{ $s['text_color'] ?? '#1F2A24' }}">
            {{ $pieceTitle }}
          </h1>

          <div style="font-size:15px; color:#5A5044; margin-bottom:18px">
            Made by
            @if($pObj && $pObj->artisan)
              <a href="{{ $linkHref(route('artisan.show', $pObj->artisan->slug ?? $pObj->artisan->uuid)) }}" style="color:#1F4D3A; font-weight:700; text-decoration:underline; text-underline-offset:4px">{{ $artisanName }}</a>
            @else
              <span style="color:#1F4D3A; font-weight:700">{{ $artisanName }}</span>
            @endif
            @if($artisanYears)
              <span> · {{ $artisanYears }}</span>
            @endif
          </div>

          <!-- High-Contrast Popping Brass-Bordered White Price Pill -->
          <div style="display:inline-flex; flex-wrap:wrap; align-items:baseline; gap:14px; background:#fff; border:1.5px solid #B8892F; border-radius:14px; padding:10px 22px; box-shadow:0 6px 20px rgba(43,33,24,0.08); margin-bottom:20px">
            <span style="font-family:'Manrope','Hind Siliguri',sans-serif; font-weight:800; font-size:28px; line-height:1.1; color:#1F4D3A">{{ $formattedPrice }}</span>
            @if($formattedCompare)
              <span style="font-family:'Manrope',sans-serif; font-size:16px; font-weight:600; color:#8F3F26; text-decoration:line-through">{{ $formattedCompare }}</span>
            @endif
          </div>

          @if($subtitleText)
            <p style="margin:0 0 22px; font-size:16px; color:#5A5044; line-height:1.65">{{ $subtitleText }}</p>
          @endif

          <!-- Interactive Delivery Destination Selector -->
          @if(count($deliveryOptions) > 0)
            <div style="margin-bottom:22px">
              <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5A5044; margin-bottom:10px">
                {{ $s['delivery_heading'] ?? 'Where should we send it?' }}
              </div>
              <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px">
                @foreach($deliveryOptions as $dIdx => $opt)
                  <button type="button"
                          @if(!$isBuilder) @click="deliveryIdx = {{ $dIdx }}" @endif
                          :style="deliveryIdx === {{ $dIdx }} ? 'border:1.5px solid #1F4D3A; background:#1F4D3A; color:#FBF6EC;' : 'border:1.5px solid #D9C28A; background:#fff; color:#2B2118;'"
                          style="border-radius:999px; padding:9px 16px; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s">
                    {{ $opt['label'] ?? 'Delivery option' }}
                  </button>
                @endforeach
              </div>
              <div style="background:#FBF6EC; border:1px solid #E2D3AF; border-radius:12px; padding:10px 14px; font-size:13px; color:#5A5044"
                   x-text="deliveryOptions[deliveryIdx] ? deliveryOptions[deliveryIdx].note : ''">
                {{ $deliveryOptions[0]['note'] ?? '' }}
              </div>
            </div>
          @endif

          @if(!$isBuilder && session('success'))
            <div style="margin-bottom:16px; padding:12px 16px; border-radius:14px; background:#E7EEE4; border:1px solid #C5D3BF; color:#1F4D3A; font-size:14px; font-weight:600">
              {{ session('success') }}
            </div>
          @endif

          @if(!$isOutOfStock)
            <form id="soro-add-cart-form" @if(!$isBuilder && $pObj) action="{{ route('cart.store') }}" method="POST" @else onsubmit="event.preventDefault();" @endif style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:14px">
              @if(!$isBuilder) @csrf @endif
              @if($pObj)<input type="hidden" name="product_id" value="{{ $pObj->id }}">@endif
              <input type="hidden" name="quantity" :value="qty">

              <div style="display:inline-flex; align-items:center; border:1.5px solid #D9C28A; border-radius:999px; background:#fff; padding:4px">
                <button type="button" @click="if(qty > 1) qty--" style="width:38px; height:38px; border:0; background:none; color:#1F4D3A; font-size:18px; font-weight:700; cursor:pointer">−</button>
                <span x-text="qty" style="min-width:32px; text-align:center; font-weight:700; font-size:15px; color:#2B2118">1</span>
                <button type="button" @click="qty++" style="width:38px; height:38px; border:0; background:none; color:#1F4D3A; font-size:18px; font-weight:700; cursor:pointer">+</button>
              </div>

              <button type="submit" style="flex:1 1 210px; position:relative; background:#1F4D3A; color:#FBF6EC; border:1.5px solid #1F4D3A; border-radius:999px; padding:14px 28px; font-size:16px; font-weight:600; min-height:50px; cursor:pointer; box-shadow:inset 0 0 0 3px #1F4D3A, inset 0 0 0 4px rgba(243,220,154,0.45)">
                {{ $s['add_to_cart_text'] ?? 'Bring it home' }}
              </button>
            </form>
          @else
            <form @if(!$isBuilder && $pObj) action="{{ route('products.notify-restock', $pObj->slug) }}" method="POST" @else onsubmit="event.preventDefault();" @endif style="background:#FBF6EC; border:1px solid #D9C28A; border-radius:18px; padding:18px; margin-bottom:14px">
              @if(!$isBuilder) @csrf @endif
              <div style="font-size:14px; font-weight:700; color:#1F2A24; margin-bottom:8px">This piece is sold out. Get notified when a new one is ready:</div>
              <div style="display:flex; flex-wrap:wrap; gap:10px">
                <input type="email" name="email" required placeholder="Your email" style="flex:1 1 200px; height:46px; border-radius:999px; border:1px solid #D9C28A; padding:0 18px; font-size:14px; background:#fff; color:#2B2118">
                <button type="submit" style="height:46px; border:0; border-radius:999px; background:#1F4D3A; color:#FBF6EC; font-weight:600; font-size:14px; padding:0 24px">Notify me</button>
              </div>
            </form>
          @endif

          <!-- Secondary Row: WhatsApp + Save for later -->
          <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center; margin-bottom:22px">
            @if(!empty($s['whatsapp_text']))
              <a href="{{ $linkHref($s['whatsapp_href'] ?? 'https://wa.me/8801711000000') }}" target="_blank" rel="noopener"
                 style="display:inline-flex; align-items:center; gap:8px; border:1.5px solid #D9C28A; background:#fff; color:#1F4D3A; border-radius:999px; padding:10px 20px; font-size:14px; font-weight:600">
                <span>{{ $s['whatsapp_text'] }}</span>
              </a>
            @endif

            <form @if(!$isBuilder && $pObj) action="{{ route('wishlist.toggle') }}" method="POST" @else onsubmit="event.preventDefault();" @endif style="margin:0">
              @if(!$isBuilder) @csrf @endif
              @if($pObj)<input type="hidden" name="product_id" value="{{ $pObj->id }}">@endif
              <button type="submit" style="display:inline-flex; align-items:center; gap:8px; border:1.5px solid #D9C28A; background:{{ $isWishlisted ? '#B8553A' : '#fff' }}; color:{{ $isWishlisted ? '#fff' : '#1F4D3A' }}; border-radius:999px; padding:10px 20px; font-size:14px; font-weight:600; cursor:pointer">
                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <span>{{ $isWishlisted ? ($s['wishlist_saved_text'] ?? 'Saved') : ($s['wishlist_text'] ?? 'Save for later') }}</span>
              </button>
            </form>
          </div>

          <!-- Maker Payout Highlight Box -->
          @if(!empty($s['payout_title']) || !empty($s['payout_note']))
            <div style="display:flex; align-items:flex-start; gap:14px; background:#FBF6EC; border:1px solid #D9C28A; border-radius:16px; padding:14px 18px; margin-bottom:22px">
              <div aria-hidden="true" style="flex:0 0 auto; width:34px; height:34px; border-radius:8px; background:#1F4D3A; border:1.5px solid #B8892F; display:flex; align-items:center; justify-content:center; transform:rotate(45deg); margin-top:4px">
                <span style="width:10px; height:10px; border-radius:50%; background:#F3DC9A"></span>
              </div>
              <div>
                <div style="font-size:14px; font-weight:700; color:#1F2A24; margin-bottom:2px">{{ $s['payout_title'] ?? 'Fair pay to the maker' }}</div>
                <div style="font-size:13px; color:#5A5044; line-height:1.5">{{ $s['payout_note'] ?? '' }}</div>
              </div>
            </div>
          @endif

          @if(count($trustItems) > 0)
            <div style="display:flex; flex-wrap:wrap; gap:12px 24px; font-size:14px; font-weight:600; color:#2B2118">
              @foreach($trustItems as $t)
                <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>{{ $t['text'] ?? $t }}</span>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Lightbox Modal -->
    @if(!$isBuilder)
      <div x-show="lightbox" x-cloak @click.self="lightbox = false"
           style="position:fixed; inset:0; z-index:90; background:rgba(20,28,23,0.9); display:flex; align-items:center; justify-content:center; padding:24px">
        <button type="button" @click="lightbox = false" aria-label="Close zoom"
                style="position:absolute; right:24px; top:24px; width:44px; height:44px; border-radius:50%; border:1px solid #D9C28A; background:#FBF6EC; color:#1F4D3A; font-size:20px; font-weight:700; cursor:pointer">✕</button>
        <img :src="images[active]?.url" :alt="images[active]?.alt"
             style="max-width:92vw; max-height:86vh; object-fit:contain; border-radius:16px; border:2px solid #B8892F; background:#FBF6EC">
      </div>
    @endif

    <!-- Mobile Sticky Bottom Bar -->
    @if(!$isBuilder && !$isOutOfStock)
      <div class="soro-sbar">
        <div>
          <div style="font-size:11px; color:#5A5044; font-weight:600">{{ \Illuminate\Support\Str::limit($pieceTitle, 26) }}</div>
          <div style="font-size:18px; font-weight:800; color:#1F4D3A">{{ $formattedPrice }}</div>
        </div>
        <button type="button" onclick="document.getElementById('soro-add-cart-form')?.submit()"
                style="background:#1F4D3A; color:#FBF6EC; border:0; border-radius:999px; padding:11px 22px; font-size:14px; font-weight:600; cursor:pointer">
          {{ $s['add_to_cart_text'] ?? 'Bring it home' }}
        </button>
      </div>
    @endif
  </section>
  @if($s['show_band'] ?? true)
    <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>
  @endif

@elseif($type === 'home_process_steps')
  <section style="position:relative; overflow:hidden; background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:64px 24px">
      <div style="text-align:center; max-width:640px; margin:0 auto 44px">
        @if(!empty($s['eyebrow']))
          <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] }}</div>
        @endif
        <h2 style="margin:0 0 12px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(32px,4vw,46px); line-height:1.08; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'How it is made' }}</h2>
        <svg aria-hidden="true" width="100" height="8" style="display:block; margin:0 auto"><rect width="100" height="8" fill="url(#patStitchGold)"></rect></svg>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:22px">
        @foreach(($s['items'] ?? []) as $idx => $step)
          <div style="background:#fff; border:1px solid #D9C28A; border-radius:20px; padding:26px 22px; box-shadow:0 8px 22px rgba(43,33,24,0.04)">
            <div style="width:46px; height:52px; border-radius:23px 23px 8px 8px; background:#1F4D3A; border:1.5px solid #B8892F; color:#F3DC9A; font-family:'Cormorant Garamond',Georgia,serif; font-size:24px; font-weight:700; display:flex; align-items:center; justify-content:center; margin-bottom:16px">
              {{ $step['step'] ?? ($idx + 1) }}
            </div>
            <h3 style="margin:0 0 8px; font-family:'Cormorant Garamond',Georgia,serif; font-size:24px; font-weight:600; color:#1F2A24">{{ $step['title'] ?? '' }}</h3>
            <p style="margin:0; font-size:14px; color:#5A5044; line-height:1.6">{{ $step['text'] ?? '' }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @if($s['show_band'] ?? true)
    <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>
  @endif

@elseif($type === 'home_maker_feature')
  @php
    $pObj = $product ?? null;
    $makerImg = !empty($s['image_url'])
        ? $s['image_url']
        : ($pObj?->artisan?->portrait_url ?: ($pObj?->artisan?->avatar_url ?: 'https://images.unsplash.com/photo-1595079676339-1534801ad6cf?q=80&w=900&auto=format&fit=crop'));
  @endphp
  <section style="position:relative; overflow:hidden; background:{{ $s['bg_color'] ?? '#F0E6D0' }}">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    @endif
    <div style="position:relative; max-width:1120px; margin:0 auto; padding:68px 24px; display:flex; flex-wrap:wrap; gap:48px; align-items:center">
      <div style="flex:0 1 360px; margin:0 auto">
        <div style="border:2px solid #B8892F; border-radius:190px 190px 14px 14px; padding:10px; background:#FBF6EC; box-shadow:0 18px 40px rgba(43,33,24,0.1)">
          <div style="border-radius:180px 180px 8px 8px; overflow:hidden; aspect-ratio:3/4; background:#E5D5B5">
            <img src="{{ $makerImg }}" alt="{{ $s['name'] ?? 'Master Maker' }}" style="width:100%; height:100%; object-fit:cover; display:block">
          </div>
        </div>
      </div>

      <div style="flex:1 1 440px; min-width:0">
        @if(!empty($s['eyebrow']))
          <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $s['eyebrow'] }}</div>
        @endif
        <h2 style="margin:0 0 8px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(34px,4vw,48px); line-height:1.06; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['name'] ?? ($pObj?->artisan?->getLocalized('full_name') ?? 'Master Maker') }}</h2>
        @if(!empty($s['meta']))
          <div style="font-size:15px; font-weight:600; color:#1F4D3A; margin-bottom:22px">{{ $s['meta'] }}</div>
        @endif
        @if(!empty($s['quote']))
          <blockquote style="margin:0 0 28px; padding:4px 0 4px 20px; border-left:3px solid #B8892F; font-family:'Cormorant Garamond',Georgia,serif; font-size:24px; font-style:italic; line-height:1.45; color:#2B2118">
            “{{ $s['quote'] }}”
          </blockquote>
        @endif
        @if(!empty($s['cta_text']))
          <a href="{{ $linkHref($s['cta_href'] ?? '/artisans') }}"
             style="display:inline-flex; align-items:center; gap:8px; border:1.5px solid #1F4D3A; background:#1F4D3A; color:#FBF6EC; border-radius:999px; padding:12px 26px; font-size:14px; font-weight:600">
            {{ $s['cta_text'] }}
          </a>
        @endif
      </div>
    </div>
  </section>
  @if($s['show_band'] ?? true)
    <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>
  @endif

@elseif($type === 'home_product_details')
  <section style="position:relative; overflow:hidden; background:{{ $s['bg_color'] ?? '#F7F5F0' }}">
    @if($s['show_pattern'] ?? true)
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    @endif
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:68px 24px">
      <div style="display:flex; flex-wrap:wrap; gap:40px; align-items:stretch">
        <!-- Left: Crisp White Spec Table -->
        <div style="flex:1 1 520px; min-width:0">
          @if(!empty($s['eyebrow']))
            <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:8px">{{ $s['eyebrow'] }}</div>
          @endif
          <h2 style="margin:0 0 22px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); line-height:1.1; color:{{ $s['text_color'] ?? '#1F2A24' }}">{{ $s['heading'] ?? 'Product details' }}</h2>

          <div style="background:#fff; border:1px solid #D9C28A; border-radius:18px; overflow:hidden; box-shadow:0 8px 24px rgba(43,33,24,0.04)">
            @foreach(($s['specs'] ?? []) as $rowIdx => $spec)
              <div style="display:flex; flex-wrap:wrap; gap:8px 16px; padding:15px 22px; {{ $rowIdx > 0 ? 'border-top:1px solid #EFE4CC;' : '' }}">
                <div style="flex:0 0 160px; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#8F3F26">{{ $spec['label'] ?? '' }}</div>
                <div style="flex:1 1 220px; font-size:15px; color:#2B2118; line-height:1.55">{{ $spec['value'] ?? '' }}</div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Right: Deep Green Arch-Top District Spotlight Card -->
        <div style="flex:0 1 380px; margin:0 auto; display:flex">
          <div style="width:100%; background:#1F4D3A; border:2px solid #B8892F; border-radius:190px 190px 20px 20px; padding:54px 32px 36px; text-align:center; color:#FBF6EC; display:flex; flex-direction:column; justify-content:center; align-items:center; box-shadow:0 18px 40px rgba(31,77,58,0.16)">
            <svg aria-hidden="true" width="38" height="38" viewBox="0 0 48 48" style="color:#F3DC9A; margin-bottom:16px"><use href="#rosetteArt"></use></svg>
            @if(!empty($s['district_eyebrow']))
              <div style="font-size:11px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#F3DC9A; margin-bottom:8px">{{ $s['district_eyebrow'] }}</div>
            @endif
            <h3 style="margin:0 0 4px; font-family:'Cormorant Garamond',Georgia,serif; font-size:34px; font-weight:500; color:#fff">{{ $s['district_title'] ?? 'Bangladesh' }}</h3>
            @if(!empty($s['district_bn']))
              <div style="font-family:'Hind Siliguri',sans-serif; font-size:16px; color:#F3DC9A; margin-bottom:16px">{{ $s['district_bn'] }}</div>
            @endif
            @if(!empty($s['district_body']))
              <p style="margin:0 0 24px; font-size:14px; color:#E7EEE4; line-height:1.65">{{ $s['district_body'] }}</p>
            @endif
            @if(!empty($s['district_cta_text']))
              <a href="{{ $linkHref($s['district_cta_href'] ?? '/districts') }}"
                 style="display:inline-flex; align-items:center; gap:8px; border:1.5px solid #F3DC9A; color:#F3DC9A; border-radius:999px; padding:10px 22px; font-size:14px; font-weight:600">
                {{ $s['district_cta_text'] }}
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  @if($s['show_band'] ?? false)
    <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>
  @endif
@endif
