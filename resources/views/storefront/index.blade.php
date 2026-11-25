<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('components.analytics.tracking-head')
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $homePage?->seo_meta['title'] ?? 'Sorolata Homepage' }}</title>
  <meta name="description" content="{{ $homePage?->seo_meta['description'] ?? 'Handmade in Bangladesh. Passed down from parent to child. Muslin, brass, clay and more.' }}">
  <link rel="canonical" href="{{ url('/') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Hind+Siliguri:wght@400;500;600&family=Manrope:wght@400;500;600;700&family=Noto+Serif+Bengali:wght@500;700&display=swap" rel="stylesheet">

  <style>
    body{margin:0}
    a{color:inherit;text-decoration:none}
    a:hover{text-decoration:underline}
    button{font-family:inherit;cursor:pointer}
    input{font-family:inherit}
  </style>
</head>
<body>
@include('components.analytics.tracking-body')

@php
  $isBn = app()->getLocale() === 'bn';

  $navLinks = [
    ['label' => 'Shop', 'href' => '#products'],
    ['label' => 'Districts', 'href' => '#districts'],
    ['label' => 'Our Makers', 'href' => '#makers'],
    ['label' => 'Stories', 'href' => '#stories'],
    ['label' => 'About Us', 'href' => '#why'],
    ['label' => 'Contact', 'href' => '#contact'],
  ];

  $districtsList = [
    ['n' => '1', 'name' => 'Narayanganj (Sonargaon)', 'bn' => 'নারায়ণগঞ্জ (সোনারগাঁ)', 'craft' => 'Muslin and Jamdani cloth'],
    ['n' => '2', 'name' => 'Tangail', 'bn' => 'টাঙ্গাইল', 'craft' => 'Tangail sarees and silk'],
    ['n' => '3', 'name' => 'Bogura', 'bn' => 'বগুড়া', 'craft' => 'Brass and bell-metal ware'],
    ['n' => '4', 'name' => 'Rangpur (Nisbetganj)', 'bn' => 'রংপুর (নিসবেতগঞ্জ)', 'craft' => 'Shatranji carpets'],
  ];

  $craftsList = [
    ['name' => 'Cloth', 'line' => 'Muslin, Jamdani and silk', 'photo' => 'Photo: cloth', 'bg' => 'linear-gradient(160deg,#5B6FAE,#26356A)', 'fill' => 'url(#patCloth)'],
    ['name' => 'Brass and Bell Metal', 'line' => 'Kettles, plates and bowls', 'photo' => 'Photo: metalwork', 'bg' => 'linear-gradient(160deg,#D6A94A,#7A5710)', 'fill' => 'url(#patBrass)'],
    ['name' => 'Carpets and Mats', 'line' => 'Shatranji and woven floor art', 'photo' => 'Photo: carpets', 'bg' => 'linear-gradient(160deg,#C9795A,#7C3523)', 'fill' => 'url(#patCarpet)'],
    ['name' => 'Clay and Terracotta', 'line' => 'Coming soon', 'photo' => 'Photo: clay pots', 'bg' => 'linear-gradient(160deg,#B58A6A,#5E3C28)', 'fill' => 'url(#patClay)'],
  ];

  $productsList = [
    ['district' => 'Narayanganj (Sonargaon)', 'name' => 'Imperial Sonargaon Muslin Scarf', 'maker' => 'Alimuddin', 'price' => '৳ 18,500', 'old' => '৳ 22,000', 'hasOld' => true, 'badge' => '16% off', 'hasBadge' => true, 'bg' => 'linear-gradient(160deg,#C9B48A,#8E7545)', 'fill' => 'url(#patCloth)'],
    ['district' => 'Tangail', 'name' => 'Heirloom Tangail Silk Jamdani', 'maker' => 'Rabeya Begum', 'price' => '৳ 24,500', 'old' => '৳ 29,000', 'hasOld' => true, 'badge' => '16% off', 'hasBadge' => true, 'bg' => 'linear-gradient(160deg,#7A4B8A,#3B1F52)', 'fill' => 'url(#patCloth)'],
    ['district' => 'Bogura', 'name' => 'Hand-Chiseled Bogura Kettle', 'maker' => 'Gourango Karmakar', 'price' => '৳ 12,800', 'old' => '', 'hasOld' => false, 'badge' => '', 'hasBadge' => false, 'bg' => 'linear-gradient(160deg,#D8B25A,#8A6410)', 'fill' => 'url(#patBrass)'],
    ['district' => 'Rangpur (Nisbetganj)', 'name' => 'Rangpur Shatranji Carpet', 'maker' => 'Mofizul Islam', 'price' => '৳ 15,600', 'old' => '', 'hasOld' => false, 'badge' => '', 'hasBadge' => false, 'bg' => 'linear-gradient(160deg,#C9795A,#6E2E1E)', 'fill' => 'url(#patCarpet)'],
  ];

  $stepsList = [
    ['when' => '1600s', 'text' => 'Dhaka muslin is known across the world, and loved in royal courts.'],
    ['when' => '1800s', 'text' => 'Cheap machine-made cloth arrives. The finest muslin almost disappears.'],
    ['when' => '2013', 'text' => 'UNESCO lists Jamdani weaving as part of the world’s cultural heritage.'],
    ['when' => 'Today', 'text' => 'Weavers in Sonargaon and Tangail still make it by hand, one thread at a time.'],
  ];

  $makersList = [
    ['name' => 'Alimuddin', 'craft' => 'Muslin weaver', 'place' => 'Narayanganj (Sonargaon)', 'years' => '42', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)'],
    ['name' => 'Rabeya Begum', 'craft' => 'Jamdani weaver', 'place' => 'Tangail', 'years' => '38', 'bg' => 'linear-gradient(160deg,#A06AB0,#4A2862)'],
    ['name' => 'Gourango Karmakar', 'craft' => 'Bell-metal maker', 'place' => 'Bogura', 'years' => '46', 'bg' => 'linear-gradient(160deg,#C89A3A,#7A5710)'],
    ['name' => 'Mofizul Islam', 'craft' => 'Shatranji weaver', 'place' => 'Rangpur (Nisbetganj)', 'years' => '32', 'bg' => 'linear-gradient(160deg,#D98A62,#7C3523)'],
  ];

  $whysList = [
    ['title' => 'Made by hand', 'text' => 'No factory. Each piece is made slowly by one maker.', 'd' => 'M4 6h16M4 12h16M4 18h16M8 4v16M16 4v16'],
    ['title' => 'Fair pay to makers', 'text' => 'The people who make your piece are paid fairly.', 'd' => 'M12 4a8 8 0 100 16 8 8 0 000-16zM12 8v8M9.5 10.5h3.5a1.5 1.5 0 010 3h-3a1.5 1.5 0 000 3h3.5'],
    ['title' => 'Know where it is from', 'text' => 'Every piece shows its maker and its district.', 'd' => 'M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11zM12 12a2 2 0 100-4 2 2 0 000 4z'],
    ['title' => 'Safe delivery', 'text' => 'Packed with care and insured, to Bangladesh and abroad.', 'd' => 'M3 8l9-5 9 5v8l-9 5-9-5V8zM3 8l9 5 9-5M12 13v8'],
  ];

  $giftsList = [
    ['name' => 'Eid gifts', 'text' => 'Silk, scarves and brass for the ones you love.', 'bg' => 'linear-gradient(160deg,#2F6B50,#163829)', 'fill' => 'url(#patJaliLight)'],
    ['name' => 'Pohela Boishakh', 'text' => 'Bring red and white colour and craft into the new year.', 'bg' => 'linear-gradient(160deg,#C9654A,#7C2E1E)', 'fill' => 'url(#patCloth)'],
    ['name' => 'Wedding gifts', 'text' => 'A gift that will be kept for a lifetime.', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)', 'fill' => 'url(#patCarpet)'],
    ['name' => 'Gifts from abroad', 'text' => 'Send a piece of home to family in Bangladesh, or to yourself.', 'bg' => 'linear-gradient(160deg,#B8892F,#6B4A0D)', 'fill' => 'url(#patBrass)'],
  ];

  $reviewsList = [
    ['text' => 'A short review from a customer in Bangladesh goes here.', 'who' => 'Customer name, City'],
    ['text' => 'A short review from a customer abroad goes here.', 'who' => 'Customer name, Country'],
    ['text' => 'A photo of the piece in a customer’s home goes here, with a few words.', 'who' => 'Customer name, City'],
  ];

  $storiesList = [
    ['tag' => 'Cloth', 'read' => '4 min read', 'title' => 'How Jamdani and Muslin are coming back', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)', 'fill' => 'url(#patCloth)'],
    ['tag' => 'History', 'read' => '7 min read', 'title' => 'Phuti Karpas: the lost cotton behind fine muslin', 'bg' => 'linear-gradient(160deg,#C89A3A,#7A5710)', 'fill' => 'url(#patBrass)'],
    ['tag' => 'Weaving', 'read' => '5 min read', 'title' => 'How weavers remember Jamdani patterns without drawing them', 'bg' => 'linear-gradient(160deg,#D98A62,#7C3523)', 'fill' => 'url(#patCarpet)'],
  ];

  $footShop = [
    ['label' => 'All products', 'href' => '#products'],
    ['label' => 'By district', 'href' => '#districts'],
    ['label' => 'By craft', 'href' => '#products'],
    ['label' => 'Gifts', 'href' => '#products'],
  ];

  $footHelp = [
    ['label' => 'Track your order', 'href' => '#contact'],
    ['label' => 'Delivery and returns', 'href' => '#contact'],
    ['label' => 'FAQ', 'href' => '#contact'],
    ['label' => 'Privacy', 'href' => '#contact'],
    ['label' => 'Terms', 'href' => '#contact'],
  ];
@endphp

<div style="background:#F7F5F0; color:#2B2118; font-family:'Manrope','Hind Siliguri',system-ui,sans-serif; line-height:1.6; font-size:16px; min-width:0; position:relative">

  <!-- Shared pattern library (used by every section below) -->
  <svg aria-hidden="true" width="0" height="0" style="position:absolute; width:0; height:0; overflow:hidden">
    <defs>
      <!-- 8-petal rosette, 48 x 48 -->
      <g id="rosetteArt" fill="none" stroke="currentColor" stroke-width="1.2">
        <ellipse cx="24" cy="24" rx="3.4" ry="17"></ellipse>
        <ellipse cx="24" cy="24" rx="3.4" ry="17" transform="rotate(45 24 24)"></ellipse>
        <ellipse cx="24" cy="24" rx="3.4" ry="17" transform="rotate(90 24 24)"></ellipse>
        <ellipse cx="24" cy="24" rx="3.4" ry="17" transform="rotate(135 24 24)"></ellipse>
        <circle cx="24" cy="24" r="20" stroke-dasharray="1.4 2.6"></circle>
        <circle cx="24" cy="24" r="23"></circle>
        <circle cx="24" cy="24" r="8"></circle>
        <circle cx="24" cy="24" r="3" fill="currentColor"></circle>
        <path d="M24 12L28 24L24 36L20 24Z" stroke-width="0.9"></path>
        <path d="M12 24L24 20L36 24L24 28Z" stroke-width="0.9"></path>
      </g>

      <!-- Ornament line with rosette, 180 x 24 -->
      <g id="ornArt" fill="none" stroke="currentColor" stroke-width="1.2">
        <path d="M2 12H64M116 12H178"></path>
        <path d="M10 8H58M122 8H170" stroke-dasharray="1.4 3"></path>
        <path d="M10 16H58M122 16H170" stroke-dasharray="1.4 3"></path>
        <path d="M70 12L74 8L78 12L74 16ZM102 12L106 8L110 12L106 16Z"></path>
        <g transform="translate(78 0) scale(0.5)"><use href="#rosetteArt"></use></g>
      </g>

      <!-- Jamdani band, 120 x 48 -->
      <g id="bandArt" fill="none" stroke="currentColor" stroke-width="1.2">
        <path d="M0 2.5H120M0 5.5H120M0 42.5H120M0 45.5H120"></path>
        <polyline points="0,10 5,14 10,10 15,14 20,10 25,14 30,10 35,14 40,10 45,14 50,10 55,14 60,10 65,14 70,10 75,14 80,10 85,14 90,10 95,14 100,10 105,14 110,10 115,14 120,10"></polyline>
        <polyline points="0,38 5,34 10,38 15,34 20,38 25,34 30,38 35,34 40,38 45,34 50,38 55,34 60,38 65,34 70,38 75,34 80,38 85,34 90,38 95,34 100,38 105,34 110,38 115,34 120,38"></polyline>
        <ellipse cx="60" cy="24" rx="3" ry="10"></ellipse>
        <ellipse cx="60" cy="24" rx="3" ry="10" transform="rotate(45 60 24)"></ellipse>
        <ellipse cx="60" cy="24" rx="3" ry="10" transform="rotate(90 60 24)"></ellipse>
        <ellipse cx="60" cy="24" rx="3" ry="10" transform="rotate(135 60 24)"></ellipse>
        <circle cx="60" cy="24" r="12.5" stroke-dasharray="1.4 2.6"></circle>
        <circle cx="60" cy="24" r="2.6" fill="currentColor"></circle>
        <path d="M24 14L34 24L24 34L14 24Z"></path>
        <path d="M24 18.5L29.5 24L24 29.5L18.5 24Z"></path>
        <circle cx="24" cy="24" r="1.4" fill="currentColor"></circle>
        <path d="M96 14L106 24L96 34L86 24Z"></path>
        <path d="M96 18.5L101.5 24L96 29.5L90.5 24Z"></path>
        <circle cx="96" cy="24" r="1.4" fill="currentColor"></circle>
        <path d="M36 24H46M74 24H84M108 24H120M0 24H12" stroke-dasharray="1.4 2.6"></path>
        <path d="M40 18L44 24L40 30M80 18L76 24L80 30"></path>
        <circle cx="60" cy="9" r="1.2" fill="currentColor"></circle>
        <circle cx="60" cy="39" r="1.2" fill="currentColor"></circle>
      </g>

      <!-- Jali lattice with 8-point star, 64 x 64 -->
      <g id="jaliArt" fill="none" stroke="currentColor" stroke-width="1">
        <path d="M14 14H50V50H14Z"></path>
        <path d="M32 6L58 32L32 58L6 32Z"></path>
        <circle cx="32" cy="32" r="11"></circle>
        <circle cx="32" cy="32" r="5"></circle>
        <circle cx="32" cy="32" r="1.6" fill="currentColor"></circle>
        <path d="M14 14L0 0M50 14L64 0M50 50L64 64M14 50L0 64"></path>
        <circle cx="0" cy="0" r="3"></circle><circle cx="64" cy="0" r="3"></circle><circle cx="0" cy="64" r="3"></circle><circle cx="64" cy="64" r="3"></circle>
        <path d="M32 0V6M32 58V64M0 32H6M58 32H64"></path>
      </g>

      <!-- Kantha running stitch, 60 x 10 -->
      <g id="stitchArt" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
        <path d="M0 5q7.5-4 15 0t15 0t15 0t15 0" stroke-dasharray="5 4"></path>
      </g>

      <!-- Corner fan, 56 x 56 -->
      <g id="cornerArt" fill="none" stroke="currentColor" stroke-width="1.2">
        <path d="M44 0A12 12 0 0 0 56 12"></path>
        <path d="M34 0A22 22 0 0 0 56 22"></path>
        <path d="M24 0A32 32 0 0 0 56 32" stroke-dasharray="1.4 2.6"></path>
        <path d="M14 0A42 42 0 0 0 56 42"></path>
        <path d="M56 0L30 26" stroke-dasharray="1.4 2.6"></path>
        <circle cx="56" cy="0" r="3" fill="currentColor"></circle>
      </g>

      <pattern id="patBandGold" width="120" height="48" patternUnits="userSpaceOnUse"><use href="#bandArt" style="color:#B8892F"></use></pattern>
      <pattern id="patBandLight" width="120" height="48" patternUnits="userSpaceOnUse"><use href="#bandArt" style="color:#E3C26F"></use></pattern>
      <pattern id="patJaliLight" width="64" height="64" patternUnits="userSpaceOnUse"><use href="#jaliArt" style="color:#E3C26F; opacity:0.3"></use></pattern>
      <pattern id="patJaliDark" width="64" height="64" patternUnits="userSpaceOnUse"><use href="#jaliArt" style="color:#1F4D3A; opacity:0.13"></use></pattern>
      <pattern id="patJaliGold" width="64" height="64" patternUnits="userSpaceOnUse"><use href="#jaliArt" style="color:#B8892F; opacity:0.35"></use></pattern>
      <pattern id="patStitchGold" width="60" height="10" patternUnits="userSpaceOnUse"><use href="#stitchArt" style="color:#B8892F"></use></pattern>
      <pattern id="patStitchLight" width="60" height="10" patternUnits="userSpaceOnUse"><use href="#stitchArt" style="color:#E3C26F"></use></pattern>
      <pattern id="patDots" width="14" height="14" patternUnits="userSpaceOnUse"><circle cx="7" cy="7" r="1.1" fill="#1F4D3A" fill-opacity="0.2"></circle></pattern>

      <!-- Craft patterns that sit on top of coloured photo boxes -->
      <pattern id="patCloth" width="44" height="44" patternUnits="userSpaceOnUse">
        <path d="M22 2L42 22L22 42L2 22Z" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <path d="M22 10L34 22L22 34L10 22Z" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <path d="M22 16L28 22L22 28L16 22Z" fill="#FFFFFF" fill-opacity="0.22"></path>
        <circle cx="22" cy="22" r="1.6" fill="#FFFFFF" fill-opacity="0.7"></circle>
        <circle cx="0" cy="0" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="44" cy="0" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle>
        <circle cx="0" cy="44" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="44" cy="44" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle>
        <path d="M22 2V0M22 42V44M2 22H0M42 22H44" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      </pattern>
      <pattern id="patBrass" width="40" height="40" patternUnits="userSpaceOnUse">
        <circle cx="20" cy="20" r="17" fill="none" stroke="#FFFFFF" stroke-opacity="0.45" stroke-width="1.2"></circle>
        <circle cx="20" cy="20" r="12" fill="none" stroke="#FFFFFF" stroke-opacity="0.45" stroke-width="1.2" stroke-dasharray="1.6 2.4"></circle>
        <circle cx="20" cy="20" r="7" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></circle>
        <circle cx="20" cy="20" r="2.2" fill="#FFFFFF" fill-opacity="0.7"></circle>
        <circle cx="0" cy="0" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="40" cy="0" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle>
        <circle cx="0" cy="40" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="40" cy="40" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle>
      </pattern>
      <pattern id="patCarpet" width="48" height="48" patternUnits="userSpaceOnUse">
        <path d="M24 0L48 24L24 48L0 24Z" fill="#FFFFFF" fill-opacity="0.09" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <path d="M24 9L39 24L24 39L9 24Z" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <path d="M24 17L31 24L24 31L17 24Z" fill="#FFFFFF" fill-opacity="0.3"></path>
        <path d="M24 0V9M24 39V48M0 24H9M39 24H48" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      </pattern>
      <pattern id="patClay" width="48" height="40" patternUnits="userSpaceOnUse">
        <path d="M0 8q6-6 12 0t12 0t12 0t12 0" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <path d="M0 20q6-6 12 0t12 0t12 0t12 0" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <path d="M0 32q6-6 12 0t12 0t12 0t12 0" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
        <circle cx="6" cy="14" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle><circle cx="18" cy="26" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle>
        <circle cx="30" cy="14" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle><circle cx="42" cy="26" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle>
        <circle cx="6" cy="38" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle><circle cx="30" cy="38" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle>
      </pattern>
    </defs>
  </svg>

  <!-- Top bar -->
  <div style="background:#1F4D3A; color:#FBF6EC; font-size:12px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; text-align:center; padding:10px 16px">
    Free insured delivery to Bangladesh, USA, UK, Canada and Europe
  </div>

  <!-- Header -->
  <header style="background:#F7F5F0">
    <div style="max-width:1200px; margin:0 auto; padding:14px 24px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 28px">
      <a href="#top" style="display:flex; align-items:center; gap:12px; text-decoration:none">
        <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:34px; color:#1F2A24; letter-spacing:-0.01em">Sorolata</span>
        <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#1F4D3A; background:#E7EEE4; border:1px solid #C5D3BF; border-radius:999px; padding:2px 12px">সারলতা</span>
      </a>
      <nav aria-label="Main" style="display:flex; flex-wrap:wrap; gap:4px 26px; font-weight:500; font-size:15px; color:#3A332B">
        @foreach($navLinks as $n)
          <a href="{{ $n['href'] }}" style="padding:10px 0">{{ $n['label'] }}</a>
        @endforeach
      </nav>
      <div style="display:flex; align-items:center; gap:10px">
        <div style="display:flex; align-items:center; border:1px solid #D9D2C0; border-radius:999px; padding:3px; background:#fff; font-size:13px; font-weight:600">
          <button type="button" onclick="window.location='{{ route('language.switch', 'en') }}'" style="border:0; background:{{ !$isBn ? '#1F4D3A' : 'none' }}; color:{{ !$isBn ? '#fff' : '#3A332B' }}; border-radius:999px; padding:6px 12px; font-size:13px; font-weight:600">EN</button>
          <button type="button" onclick="window.location='{{ route('language.switch', 'bn') }}'" style="border:0; background:{{ $isBn ? '#1F4D3A' : 'none' }}; color:{{ $isBn ? '#fff' : '#3A332B' }}; border-radius:999px; padding:6px 12px; font-size:13px; font-weight:500; font-family:'Noto Serif Bengali',serif">বাং</button>
        </div>
        <button type="button" aria-label="Search" onclick="window.location='{{ route('search.index') }}'" style="width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0">
          <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-3.5-3.5"></path></svg>
        </button>
        <button type="button" aria-label="Cart" onclick="window.location='{{ route('cart.index') }}'" style="width:44px; height:44px; border:1px solid #D9D2C0; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0">
          <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14l-1.5 12h-11L5 7z"></path><path d="M9 7a3 3 0 016 0"></path></svg>
        </button>
      </div>
    </div>
    <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
  </header>

  <!-- Hero -->
  <section id="top" style="position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:64px 24px 72px; display:flex; flex-wrap:wrap; align-items:center; gap:48px">
      <div style="flex:1 1 420px; min-width:0">
        <div style="display:inline-flex; align-items:center; gap:10px; background:#1F4D3A; color:#fff; font-size:12px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; border-radius:999px; padding:7px 16px; margin-bottom:22px">
          <svg aria-hidden="true" width="16" height="16" viewBox="0 0 48 48" style="color:#E3C26F"><use href="#rosetteArt"></use></svg>
          Handmade in 64 districts of Bangladesh
        </div>
        <h1 style="margin:0 0 22px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(46px,5.8vw,80px); line-height:1.0; letter-spacing:-0.01em; color:#1F2A24">Handmade in Bangladesh. Passed down from parent to child.</h1>
        <p style="margin:0 0 32px; font-size:18px; color:#5A5044; max-width:500px">Muslin, brass, clay and more. Every piece names its maker and the district it comes from.</p>
        <div style="display:flex; flex-wrap:wrap; gap:14px; margin-bottom:36px">
          <a href="#products" style="background:#1F4D3A; color:#fff; font-weight:600; padding:16px 30px; border-radius:999px; text-decoration:none; font-size:16px">Shop Now →</a>
          <a href="#districts" style="border:1.5px solid #1F4D3A; color:#1F4D3A; font-weight:600; padding:14.5px 28px; border-radius:999px; text-decoration:none; font-size:16px; background:#fff">Shop by District</a>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:14px 30px; font-size:14px; font-weight:600; color:#2B2118">
          <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>Made by hand</span>
          <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>Fair pay to makers</span>
          <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>Ships worldwide</span>
        </div>
      </div>

      <!-- Arch frame -->
      <div style="flex:1 1 380px; min-width:0; display:flex; justify-content:center">
        <div style="width:100%; max-width:460px; border:2px solid #B8892F; border-radius:999px 999px 20px 20px; padding:10px; background:#FBF6EC; box-sizing:border-box">
          <div style="border:1px solid #B8892F; border-radius:999px 999px 14px 14px; padding:8px; box-sizing:border-box">
            <div style="position:relative; min-height:560px; border-radius:999px 999px 10px 10px; overflow:hidden; background:linear-gradient(170deg,#E09A6E 0%,#B8553A 48%,#5E2618 100%)">
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
              <div style="position:absolute; left:50%; top:46px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:6px 14px; border-radius:999px; white-space:nowrap">Photo: a weaver's hands at the loom</div>
              <div style="position:absolute; left:16px; right:16px; bottom:16px; background:#FBF6EC; border-radius:16px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; border:1px solid #D9C28A">
                <div>
                  <div style="font-size:12px; color:#5A5044; letter-spacing:0.06em; text-transform:uppercase">Featured · Narayanganj (Sonargaon)</div>
                  <div style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:21px; color:#1F4D3A">Imperial Sonargaon Muslin Scarf</div>
                </div>
                <div style="font-weight:700; font-size:17px; color:#8F3F26">৳ 18,500</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Jamdani band -->
  <svg aria-hidden="true" width="100%" height="48" style="display:block; background:#FBF6EC"><rect width="100%" height="48" fill="url(#patBandGold)"></rect></svg>

  <!-- Shop by district -->
  <section id="districts" style="max-width:1200px; margin:0 auto; padding:80px 24px">
    <div style="text-align:center; max-width:680px; margin:0 auto 48px">
      <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
      <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Shop by district</div>
      <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">Every district has its own craft</h2>
      <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      <p style="margin:0; font-size:17px; color:#5A5044">Tap a district to see what is made there, and who makes it.</p>
    </div>
    <div style="display:flex; flex-wrap:wrap; gap:32px; align-items:stretch">
      <div style="position:relative; flex:1 1 320px; min-width:0; background:#F0E6D0; border:1px solid #D9C28A; border-radius:24px; padding:28px; display:flex; flex-direction:column; align-items:center; justify-content:center; overflow:hidden">
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>
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
        <div style="position:relative; font-size:13px; color:#5A5044; margin-top:10px">A simple drawing, not to scale</div>
      </div>
      <div style="flex:1.4 1 420px; min-width:0; display:flex; flex-direction:column; gap:14px">
        @foreach($districtsList as $d)
          <a href="#products" style="position:relative; overflow:hidden; display:flex; align-items:center; gap:18px; background:#fff; border:1px solid #E3D8BC; border-radius:18px; padding:20px 24px; text-decoration:none">
            <svg aria-hidden="true" width="56" height="56" viewBox="0 0 56 56" style="position:absolute; right:0; top:0; color:#B8892F; opacity:0.75"><use href="#cornerArt"></use></svg>
            <div style="position:relative; width:52px; height:52px; flex:none; display:flex; align-items:center; justify-content:center">
              <svg aria-hidden="true" width="52" height="52" viewBox="0 0 48 48" style="position:absolute; left:0; top:0; color:#B8892F"><use href="#rosetteArt"></use></svg>
              <div style="position:relative; width:30px; height:30px; border-radius:50%; background:#1F4D3A; color:#FBF6EC; display:flex; align-items:center; justify-content:center; font-family:'Cormorant Garamond',Georgia,serif; font-weight:700; font-size:18px">{{ $d['n'] }}</div>
            </div>
            <div style="flex:1 1 auto; min-width:0; padding-right:40px">
              <div style="display:flex; flex-wrap:wrap; align-items:baseline; gap:2px 12px">
                <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:26px; line-height:1.15; color:#1F4D3A">{{ $d['name'] }}</h3>
                <span style="font-family:'Noto Serif Bengali',serif; font-size:14px; color:#5A5044">{{ $d['bn'] }}</span>
              </div>
              <div style="font-size:15px; color:#5A5044">{{ $d['craft'] }}</div>
            </div>
            <div style="font-weight:700; font-size:14px; color:#8F3F26; white-space:nowrap">See products →</div>
          </a>
        @endforeach
        <div style="display:flex; align-items:center; gap:14px; padding:16px 24px; border:2px dashed #CDBB95; border-radius:18px; color:#5A5044; font-size:15px">
          <svg aria-hidden="true" width="28" height="28" viewBox="0 0 48 48" style="color:#B8892F; flex:none"><use href="#rosetteArt"></use></svg>
          More districts are coming soon, including clay and terracotta from the villages.
        </div>
      </div>
    </div>
  </section>

  <!-- Shop by craft -->
  <section style="background:#F0E6D0; position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.5"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 48px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Shop by craft</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">Four old crafts, still made by hand</h2>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:20px">
        @foreach($craftsList as $c)
          <a href="#products" style="position:relative; flex:1 1 240px; min-width:0; min-height:380px; border-radius:200px 200px 20px 20px; overflow:hidden; display:flex; flex-direction:column; justify-content:flex-end; text-decoration:none; background:{{ $c['bg'] }}; color:#fff">
            <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="{{ $c['fill'] }}"></rect></svg>
            <div aria-hidden="true" style="position:absolute; left:10px; right:10px; top:10px; bottom:10px; border:1px solid rgba(255,255,255,0.55); border-radius:190px 190px 12px 12px"></div>
            <div style="position:absolute; left:50%; top:120px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">{{ $c['photo'] }}</div>
            <div style="position:relative; padding:90px 26px 28px; background:linear-gradient(to top, rgba(20,12,6,0.82) 35%, rgba(20,12,6,0)); text-align:center">
              <h3 style="margin:0 0 4px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; line-height:1.1">{{ $c['name'] }}</h3>
              <div style="font-size:15px">{{ $c['line'] }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Products -->
  <section id="products" style="max-width:1200px; margin:0 auto; padding:80px 24px">
    <div style="text-align:center; max-width:680px; margin:0 auto 48px">
      <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
      <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Our pieces</div>
      <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">Popular right now</h2>
      <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
    </div>
    <div style="display:flex; flex-wrap:wrap; gap:22px">
      @foreach($productsList as $p)
        <article style="flex:1 1 250px; min-width:0; background:#fff; border:1px solid #E3D8BC; border-radius:18px; overflow:hidden; display:flex; flex-direction:column">
          <div style="position:relative; height:280px; background:{{ $p['bg'] }}">
            <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="{{ $p['fill'] }}"></rect></svg>
            <div aria-hidden="true" style="position:absolute; left:10px; right:10px; top:10px; bottom:10px; border:1px solid rgba(255,255,255,0.6); border-radius:10px"></div>
            <div style="position:absolute; left:50%; bottom:22px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">Product photo</div>
            @if($p['hasBadge'])
              <div style="position:absolute; right:18px; top:18px; background:#B8553A; color:#fff; font-weight:700; font-size:12px; padding:5px 11px; border-radius:999px">{{ $p['badge'] }}</div>
            @endif
          </div>
          <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
          <div style="padding:16px 20px 22px; display:flex; flex-direction:column; gap:6px; flex:1 1 auto">
            <div style="font-size:12px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#5A5044">{{ $p['district'] }}</div>
            <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:24px; line-height:1.15; color:#1F4D3A">{{ $p['name'] }}</h3>
            <div style="font-size:14px; color:#5A5044">Made by {{ $p['maker'] }}</div>
            <div style="display:flex; align-items:baseline; gap:10px; margin-top:6px">
              <span style="font-weight:700; font-size:19px; color:#2B2118">{{ $p['price'] }}</span>
              @if($p['hasOld'])
                <span style="font-size:14px; color:#6B5F50; text-decoration:line-through">{{ $p['old'] }}</span>
              @endif
            </div>
            <button type="button" onclick="window.location='{{ route('shop.index') }}'" style="margin-top:12px; background:#1F4D3A; color:#FBF6EC; border:0; border-radius:999px; padding:13px 20px; font-size:15px; font-weight:600; min-height:46px">Add to Cart</button>
          </div>
        </article>
      @endforeach
    </div>
    <div style="text-align:center; margin-top:36px">
      <a href="{{ route('shop.index') }}" style="display:inline-block; border:1.5px solid #1F4D3A; color:#1F4D3A; font-weight:600; padding:14px 30px; border-radius:999px; text-decoration:none; background:#fff">View all products →</a>
    </div>
  </section>

  <!-- Story + timeline -->
  <section style="background:#163829; color:#FBF6EC; position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)" opacity="0.55"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:80px 24px">
      <div style="text-align:center; max-width:700px; margin:0 auto 56px; background:#163829; padding:8px 24px 16px; border-radius:20px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#E3C26F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#E3C26F; margin-bottom:10px">The story of Muslin and Jamdani</div>
        <h2 style="margin:0 0 12px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05">A cloth so fine, the world wanted it</h2>
        <p style="margin:0; font-size:17px; color:#E8E0CF">When you buy a piece from Sorolata, you help this story go on.</p>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:36px 28px">
        @foreach($stepsList as $s)
          <div style="flex:1 1 220px; min-width:0; text-align:center; background:#163829; padding:8px 8px 12px; border-radius:16px">
            <svg aria-hidden="true" width="76" height="76" viewBox="0 0 48 48" style="display:block; margin:0 auto 10px; color:#E3C26F"><use href="#rosetteArt"></use></svg>
            <div style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:40px; line-height:1; color:#E3C26F; margin-bottom:10px">{{ $s['when'] }}</div>
            <svg aria-hidden="true" width="100" height="10" style="display:block; margin:0 auto 12px"><rect width="100" height="10" fill="url(#patStitchLight)"></rect></svg>
            <p style="margin:0 auto; font-size:16px; color:#F3EDDF; max-width:260px">{{ $s['text'] }}</p>
          </div>
        @endforeach
      </div>
      <div style="margin-top:48px; text-align:center">
        <a href="#stories" style="display:inline-block; background:#E3C26F; color:#2B2118; font-weight:700; padding:15px 30px; border-radius:999px; text-decoration:none">Read the full story</a>
      </div>
    </div>
    <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
  </section>

  <!-- Makers -->
  <section id="makers" style="max-width:1200px; margin:0 auto; padding:80px 24px">
    <div style="text-align:center; max-width:680px; margin:0 auto 48px">
      <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
      <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Our makers</div>
      <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">Meet the hands behind every piece</h2>
      <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      <p style="margin:0; font-size:17px; color:#5A5044">Each maker has spent a lifetime learning their craft.</p>
    </div>
    <div style="display:flex; flex-wrap:wrap; gap:22px">
      @foreach($makersList as $m)
        <article style="flex:1 1 250px; min-width:0; text-align:center">
          <div style="border:1.5px solid #B8892F; border-radius:999px 999px 16px 16px; padding:7px; box-sizing:border-box">
            <div style="position:relative; height:310px; border-radius:999px 999px 10px 10px; overflow:hidden; background:{{ $m['bg'] }}">
              <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
              <svg aria-hidden="true" viewBox="0 0 200 240" width="100%" height="100%" preserveAspectRatio="xMidYMax meet" style="position:absolute; left:0; top:0">
                <circle cx="100" cy="96" r="38" fill="#FFFFFF" fill-opacity="0.38"></circle>
                <path d="M30 240c0-52 31-84 70-84s70 32 70 84z" fill="#FFFFFF" fill-opacity="0.38"></path>
              </svg>
              <div style="position:absolute; left:50%; bottom:14px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">Real portrait photo</div>
            </div>
          </div>
          <h3 style="margin:16px 0 2px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; line-height:1.1; color:#1F4D3A">{{ $m['name'] }}</h3>
          <div style="font-size:15px; font-weight:600; color:#2B2118">{{ $m['craft'] }}</div>
          <div style="font-size:14px; color:#5A5044">{{ $m['place'] }} · {{ $m['years'] }} years of work</div>
        </article>
      @endforeach
    </div>
  </section>

  <!-- Why Sorolata -->
  <section id="why" style="background:#F0E6D0; position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.45"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:72px 24px">
      <div style="text-align:center; margin:0 auto 44px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(34px,4vw,50px); line-height:1.05; color:#1F2A24">Why people buy from Sorolata</h2>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:28px">
        @foreach($whysList as $w)
          <div style="flex:1 1 230px; min-width:0; text-align:center; display:flex; flex-direction:column; align-items:center; gap:10px">
            <div style="position:relative; width:96px; height:96px; display:flex; align-items:center; justify-content:center">
              <svg aria-hidden="true" width="96" height="96" viewBox="0 0 48 48" style="position:absolute; left:0; top:0; color:#B8892F"><use href="#rosetteArt"></use></svg>
              <div style="position:relative; width:54px; height:54px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center">
                <svg aria-hidden="true" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1F4D3A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $w['d'] }}"></path></svg>
              </div>
            </div>
            <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:26px; line-height:1.1; color:#1F4D3A">{{ $w['title'] }}</h3>
            <p style="margin:0; font-size:15px; color:#5A5044; max-width:260px">{{ $w['text'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Gifts -->
  <section style="max-width:1200px; margin:0 auto; padding:80px 24px">
    <div style="text-align:center; max-width:680px; margin:0 auto 48px">
      <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
      <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Gift ideas</div>
      <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">A gift with a story inside</h2>
      <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
      <p style="margin:0; font-size:17px; color:#5A5044">Every piece arrives with the name of its maker.</p>
    </div>
    <div style="display:flex; flex-wrap:wrap; gap:20px">
      @foreach($giftsList as $g)
        <a href="#products" style="position:relative; overflow:hidden; flex:1 1 250px; min-width:0; background:{{ $g['bg'] }}; color:#fff; border-radius:18px; padding:30px 26px; min-height:220px; display:flex; flex-direction:column; justify-content:space-between; text-decoration:none">
          <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="{{ $g['fill'] }}"></rect></svg>
          <div aria-hidden="true" style="position:absolute; left:9px; right:9px; top:9px; bottom:9px; border:1px solid rgba(255,255,255,0.5); border-radius:12px"></div>
          <div style="position:relative; background:rgba(20,12,6,0.55); padding:12px 14px; border-radius:12px">
            <h3 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; line-height:1.1">{{ $g['name'] }}</h3>
            <p style="margin:0; font-size:15px">{{ $g['text'] }}</p>
          </div>
          <div style="position:relative; font-weight:700; font-size:15px; margin-top:20px; padding-left:14px">See ideas →</div>
        </a>
      @endforeach
    </div>
  </section>

  <!-- Reviews -->
  <section style="background:#F0E6D0">
    <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
    <div style="max-width:1200px; margin:0 auto; padding:72px 24px">
      <div style="text-align:center; max-width:680px; margin:0 auto 44px">
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Customer reviews</div>
        <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">What our customers say</h2>
      </div>
      <div style="display:flex; flex-wrap:wrap; gap:20px">
        @foreach($reviewsList as $r)
          <div style="position:relative; flex:1 1 300px; min-width:0; border:2px dashed #BBA57B; border-radius:18px; padding:28px 26px 22px; background:#FBF6EC; overflow:hidden">
            <svg aria-hidden="true" width="56" height="56" viewBox="0 0 56 56" style="position:absolute; right:0; top:0; color:#B8892F; opacity:0.7"><use href="#cornerArt"></use></svg>
            <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:64px; line-height:0.7; color:#B8892F; height:28px" aria-hidden="true">“</div>
            <p style="margin:0 0 18px; font-family:'Cormorant Garamond',Georgia,serif; font-size:23px; line-height:1.3; color:#2B2118">{{ $r['text'] }}</p>
            <div style="font-size:14px; color:#5A5044">{{ $r['who'] }}</div>
          </div>
        @endforeach
      </div>
      <p style="margin:20px 0 0; font-size:14px; color:#5A5044; text-align:center">These are empty boxes for the demo. Fill them with real reviews and photos from your buyers.</p>
    </div>
  </section>

  <!-- Stories -->
  <section id="stories" style="max-width:1200px; margin:0 auto; padding:80px 24px">
    <div style="text-align:center; max-width:680px; margin:0 auto 48px">
      <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
      <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">Stories</div>
      <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,56px); line-height:1.05; color:#1F2A24">Read about the crafts</h2>
      <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
    </div>
    <div style="display:flex; flex-wrap:wrap; gap:22px">
      @foreach($storiesList as $st)
        <article style="flex:1 1 300px; min-width:0">
          <div style="position:relative; height:210px; border-radius:18px; overflow:hidden; background:{{ $st['bg'] }}">
            <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="{{ $st['fill'] }}"></rect></svg>
            <div aria-hidden="true" style="position:absolute; left:9px; right:9px; top:9px; bottom:9px; border:1px solid rgba(255,255,255,0.55); border-radius:11px"></div>
            <div style="position:absolute; left:50%; bottom:20px; transform:translateX(-50%); background:rgba(30,18,10,0.62); color:#FBF6EC; font-size:12px; padding:5px 12px; border-radius:999px; white-space:nowrap">Story photo</div>
          </div>
          <div style="font-size:13px; font-weight:600; color:#5A5044; margin:14px 0 6px; letter-spacing:0.04em">{{ $st['tag'] }} · {{ $st['read'] }}</div>
          <h3 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:27px; line-height:1.15; color:#1F4D3A">{{ $st['title'] }}</h3>
        </article>
      @endforeach
    </div>
    <div style="text-align:center; margin-top:36px">
      <a href="{{ route('stories.index') }}" style="display:inline-block; border:1.5px solid #1F4D3A; color:#1F4D3A; font-weight:600; padding:14px 30px; border-radius:999px; text-decoration:none; background:#fff">All stories →</a>
    </div>
  </section>

  <!-- Newsletter -->
  <section style="max-width:1200px; margin:0 auto; padding:0 24px 80px">
    <div style="position:relative; overflow:hidden; background:#1F4D3A; color:#FBF6EC; border-radius:26px; padding:48px 40px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:28px">
      <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
      <div aria-hidden="true" style="position:absolute; left:10px; right:10px; top:10px; bottom:10px; border:1px solid rgba(227,194,111,0.55); border-radius:18px"></div>
      <div style="position:relative; flex:1 1 320px; min-width:0; background:#1F4D3A; padding:6px 10px; border-radius:12px">
        <h2 style="margin:0 0 8px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(32px,3.6vw,44px); line-height:1.05">Get updates from Sorolata</h2>
        <p style="margin:0; font-size:16px; color:#E8E0CF">We write when a maker finishes a new batch. No spam.</p>
      </div>
      <form onsubmit="event.preventDefault(); this.reset();" style="position:relative; flex:1 1 360px; min-width:0; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; background:#1F4D3A; padding:6px 10px; border-radius:12px">
        <div style="flex:1 1 220px; min-width:0; display:flex; flex-direction:column; gap:6px">
          <label for="email" style="font-size:14px; font-weight:600">Your email</label>
          <input id="email" type="email" placeholder="name@example.com" style="height:50px; border-radius:999px; border:0; padding:0 22px; font-size:15px; background:#FBF6EC; color:#2B2118; box-sizing:border-box; width:100%">
        </div>
        <button type="submit" style="height:50px; border:0; border-radius:999px; background:#E3C26F; color:#2B2118; font-weight:700; font-size:15px; padding:0 28px">Join</button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" style="background:#163829; color:#F3EDDF; position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="48" style="display:block; position:relative"><rect width="100%" height="48" fill="url(#patBandLight)"></rect></svg>
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0"><rect width="100%" height="100%" fill="url(#patJaliLight)" opacity="0.4"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:56px 24px 28px; display:flex; flex-wrap:wrap; gap:40px">
      <div style="flex:1.5 1 300px; min-width:0">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px">
          <span style="font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:38px; color:#FBF6EC">Sorolata</span>
          <span style="font-family:'Noto Serif Bengali',serif; font-weight:500; font-size:13px; color:#E3C26F; border:1px solid #E3C26F; border-radius:999px; padding:2px 12px">সারলতা</span>
        </div>
        <p style="margin:0; font-size:15px; max-width:380px; color:#E8E0CF">Handmade things from the districts of Bangladesh, sent from the maker to your home.</p>
      </div>
      <div style="flex:1 1 160px; min-width:0">
        <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">Shop</h3>
        <div style="display:flex; flex-direction:column; gap:4px; font-size:15px">
          @foreach($footShop as $f)
            <a href="{{ $f['href'] }}" style="padding:6px 0">{{ $f['label'] }}</a>
          @endforeach
        </div>
      </div>
      <div style="flex:1 1 160px; min-width:0">
        <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">Help</h3>
        <div style="display:flex; flex-direction:column; gap:4px; font-size:15px">
          @foreach($footHelp as $f)
            <a href="{{ $f['href'] }}" style="padding:6px 0">{{ $f['label'] }}</a>
          @endforeach
        </div>
      </div>
      <div style="flex:1 1 220px; min-width:0">
        <h3 style="margin:0 0 12px; font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#E3C26F">Talk to us</h3>
        <div style="display:flex; flex-direction:column; gap:8px; font-size:15px">
          <span>WhatsApp: [your real number]</span>
          <span>Phone: [your real number]</span>
          <span>Facebook: facebook.com/sorolatabangladesh</span>
        </div>
      </div>
    </div>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:0 24px 32px">
      <svg aria-hidden="true" width="100%" height="10" style="display:block; margin-bottom:16px"><rect width="100%" height="10" fill="url(#patStitchLight)"></rect></svg>
      <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; font-size:13px; color:#CFC7B4">
        <span>© 2026 Sorolata. All rights reserved.</span>
        <span>We accept bKash, Nagad, Visa and Mastercard</span>
      </div>
    </div>
  </footer>

</div>
</body>
</html>
