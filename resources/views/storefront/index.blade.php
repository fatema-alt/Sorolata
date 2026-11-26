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
  $sectionsToRender = $homeSections ?? \App\Livewire\Builder\VisualPageBuilder::getSorolataHomepageSections();
@endphp

<div class="soro-page-wrap" style="background:#F7F5F0; color:#2B2118; font-family:'Manrope','Hind Siliguri',system-ui,sans-serif; line-height:1.6; font-size:16px; min-width:0; position:relative">
  @include('storefront.sections.defs')

  @foreach($sectionsToRender as $section)
    @if(($section['is_active'] ?? true) && str_starts_with($section['type'] ?? '', 'home_'))
      @include('storefront.sections.render-block', ['section' => $section, 'isBuilder' => false])
    @endif
  @endforeach
</div>

<x-mobile-bottom-nav />
<x-sorolata-animations />

</body>
</html>
