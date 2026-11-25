@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $metaTags['title'] }}</title>
    <meta name="description" content="{{ $metaTags['description'] }}">
    <link rel="canonical" href="{{ $metaTags['canonical'] }}">

    <meta property="og:title" content="{{ $metaTags['og:title'] }}">
    <meta property="og:description" content="{{ $metaTags['og:description'] }}">
    <meta property="og:image" content="{{ $metaTags['og:image'] }}">
    <meta property="og:url" content="{{ $metaTags['og:url'] }}">
    <meta property="og:type" content="product">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTags['twitter:title'] }}">
    <meta name="twitter:description" content="{{ $metaTags['twitter:description'] }}">
    <meta name="twitter:image" content="{{ $metaTags['twitter:image'] }}">

    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
@php
    $savedSections = $product->layout_config['sections'] ?? [];
    $hasHomeBlocks = !empty($savedSections) && collect($savedSections)->contains(
        fn ($s) => is_array($s) && str_starts_with((string) ($s['type'] ?? ''), 'home_')
    );
    $sectionsToRender = $hasHomeBlocks
        ? array_values(array_filter($savedSections, fn ($s) => is_array($s) && ($s['is_active'] ?? true)))
        : \App\Livewire\Builder\VisualPageBuilder::getSorolataProductDefaults($product);

    $districtName = $product->district?->name_en ?? $product->district?->getLocalized('name') ?? __('Bangladesh');
    $artisanName = $product->artisan?->getLocalized('full_name') ?? __('Master Maker');
@endphp

<!-- Dynamic Product Page Builder Sections -->
@foreach($sectionsToRender as $section)
    @if(str_starts_with($section['type'] ?? '', 'home_'))
        @include('storefront.sections.render-block', ['section' => $section, 'isBuilder' => false, 'product' => $product])
    @endif
@endforeach

<!-- Meet the Maker -->
@if($product->artisan)
<section style="background:#F0E6D0; position:relative; overflow:hidden">
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.4"></rect></svg>
    <div style="position:relative; max-width:1000px; margin:0 auto; padding:72px 24px; display:flex; flex-wrap:wrap; align-items:center; gap:40px">
        <div style="flex:0 0 260px; margin:0 auto">
            <div style="border:1.5px solid #B8892F; border-radius:999px 999px 16px 16px; padding:7px; background:#FBF6EC">
                <div style="height:300px; border-radius:999px 999px 10px 10px; overflow:hidden; background:#6E7FB8">
                    <img src="{{ $product->artisan->portrait_url }}" alt="{{ $artisanName }}" style="width:100%; height:100%; object-fit:cover; display:block">
                </div>
            </div>
        </div>
        <div style="flex:1 1 340px; min-width:0">
            <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:8px">{{ __('Our makers') }}</div>
            <h2 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:38px; line-height:1.1; color:#1F4D3A">{{ $artisanName }}</h2>
            <div style="font-size:15px; font-weight:600; color:#2B2118; margin-bottom:14px">
                {{ $product->artisan->craft_type?->title() }} · {{ $districtName }} · {{ __(':count years of work', ['count' => $product->artisan->years_of_mastery ?? 25]) }}
            </div>
            @if($product->artisan->bio_en || $product->artisan->bio_bn)
                <p style="margin:0 0 22px; font-size:16px; color:#5A5044; line-height:1.65">{{ $product->artisan->getLocalized('bio') }}</p>
            @endif
            <a href="{{ route('artisan.show', $product->artisan->slug ?? $product->artisan->uuid) }}" style="display:inline-block; background:#1F4D3A; color:#FBF6EC; font-weight:600; padding:12px 26px; border-radius:999px; text-decoration:none; font-size:14px">
                {{ __('See maker profile →') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- Story -->
@if(isset($relatedStories) && $relatedStories->isNotEmpty())
@php $story = $relatedStories->first(); @endphp
<section style="background:#F7F5F0">
    <div style="max-width:1000px; margin:0 auto; padding:64px 24px">
        <div style="background:#fff; border:1px solid #E3D8BC; border-radius:20px; padding:28px; display:flex; flex-wrap:wrap; gap:28px; align-items:center">
            <div style="flex:1 1 280px; border-radius:14px; overflow:hidden; aspect-ratio:16/10; background:#F0E6D0">
                <img src="{{ $story->hero_image }}" alt="{{ $story->getLocalized('title') }}" style="width:100%; height:100%; object-fit:cover; display:block">
            </div>
            <div style="flex:1.2 1 300px; min-width:0">
                <div style="font-size:12px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#8F3F26; margin-bottom:8px">
                    {{ __('Story') }} · {{ $story->reading_time }} {{ __('min read') }}
                </div>
                <h3 style="margin:0 0 10px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:30px; line-height:1.15; color:#1F4D3A">
                    {{ $story->getLocalized('title') }}
                </h3>
                <p style="margin:0 0 18px; font-size:15px; color:#5A5044; line-height:1.6">{{ $story->getLocalized('excerpt') }}</p>
                <a href="{{ route('stories.show', $story->slug) }}" style="display:inline-block; border:1.5px solid #1F4D3A; color:#1F4D3A; font-weight:600; padding:10px 24px; border-radius:999px; text-decoration:none; font-size:14px">
                    {{ __('Read the story →') }}
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Customer Reviews -->
<section id="reviews" style="background:#F0E6D0">
    <svg aria-hidden="true" width="100%" height="10" style="display:block"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>
    <div style="max-width:1000px; margin:0 auto; padding:72px 24px">
        @if(session('review_success'))
            <div style="margin-bottom:24px; padding:14px 18px; border-radius:14px; background:#E7EEE4; border:1px solid #C5D3BF; color:#1F4D3A; font-size:14px; font-weight:600">
                {{ session('review_success') }}
            </div>
        @endif

        <div style="text-align:center; max-width:680px; margin:0 auto 44px">
            <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
            <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ __('Customer reviews') }}</div>
            <h2 style="margin:0 0 10px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(34px,4vw,50px); line-height:1.05; color:#1F2A24">{{ __('What our customers say') }}</h2>
            <div style="font-size:14px; color:#5A5044">
                {{ number_format($averageRating, 1) }} / 5 ★ ({{ $reviews->count() }} {{ __('reviews') }})
            </div>
        </div>

        @if($reviews->isNotEmpty())
            <div style="display:flex; flex-wrap:wrap; gap:20px; margin-bottom:40px">
                @foreach($reviews as $rev)
                    <div style="position:relative; flex:1 1 280px; min-width:0; border:2px dashed #BBA57B; border-radius:18px; padding:26px; background:#FBF6EC; overflow:hidden">
                        <svg aria-hidden="true" width="56" height="56" viewBox="0 0 56 56" style="position:absolute; right:0; top:0; color:#B8892F; opacity:0.7"><use href="#cornerArt"></use></svg>
                        <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:56px; line-height:0.7; color:#B8892F; height:24px" aria-hidden="true">“</div>
                        @if($rev->title)
                            <div style="font-weight:700; font-size:15px; color:#1F4D3A; margin-bottom:6px">{{ $rev->title }}</div>
                        @endif
                        <p style="margin:0 0 14px; font-family:'Cormorant Garamond',Georgia,serif; font-size:21px; line-height:1.35; color:#2B2118">{{ $rev->content }}</p>
                        <div style="font-size:13px; color:#5A5044">{{ $rev->author_name }}@if($rev->author_location), {{ $rev->author_location }}@endif</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="background:#fff; border:1px solid #E3D8BC; border-radius:20px; padding:32px">
            <h3 style="margin:0 0 16px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:28px; color:#1F4D3A">
                {{ __('Write a review') }}
            </h3>
            <form action="{{ route('products.review.submit', $product->slug) }}" method="POST" style="display:flex; flex-direction:column; gap:16px" x-data="{ rating: 5 }">
                @csrf
                <div style="display:flex; align-items:center; gap:8px">
                    <span style="font-size:14px; font-weight:600; color:#2B2118">{{ __('Rating:') }}</span>
                    <template x-for="star in 5" :key="star">
                        <button type="button" @click="rating = star" style="border:0; background:none; font-size:22px; padding:0 2px" :style="star <= rating ? 'color:#B8892F' : 'color:#D9D2C0'">★</button>
                    </template>
                    <input type="hidden" name="rating" :value="rating">
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:16px">
                    <input type="text" name="author_name" required value="{{ old('author_name', auth()->user()?->name ?? '') }}" placeholder="{{ __('Your name *') }}" style="flex:1 1 220px; height:46px; border:1px solid #D9C28A; border-radius:999px; padding:0 18px; font-size:14px; background:#FBF6EC; color:#2B2118">
                    <input type="text" name="author_location" value="{{ old('author_location') }}" placeholder="{{ __('City / District') }}" style="flex:1 1 220px; height:46px; border:1px solid #D9C28A; border-radius:999px; padding:0 18px; font-size:14px; background:#FBF6EC; color:#2B2118">
                </div>

                <input type="text" name="title" value="{{ old('title') }}" placeholder="{{ __('Review title (optional)') }}" style="height:46px; border:1px solid #D9C28A; border-radius:999px; padding:0 18px; font-size:14px; background:#FBF6EC; color:#2B2118">

                <textarea name="content" rows="3" required placeholder="{{ __('Write your review here...') }}" style="border:1px solid #D9C28A; border-radius:16px; padding:14px 18px; font-size:14px; background:#FBF6EC; color:#2B2118">{{ old('content') }}</textarea>

                <div>
                    <button type="submit" style="background:#1F4D3A; color:#FBF6EC; border:0; border-radius:999px; padding:13px 28px; font-size:15px; font-weight:600">
                        {{ __('Submit review →') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.sorolataAnalytics) {
            window.sorolataAnalytics.pushViewItem({
                id: '{{ $product->id }}',
                name: @json($product->getLocalized('title')),
                price: {{ (float) $product->price }},
                category: @json($product->category?->name ?? 'Heritage Handcraft')
            });
        }
    });
</script>
@endsection
