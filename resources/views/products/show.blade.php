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
    $galleryImages = $product->media->map(function ($m) use ($product) {
        return [
            'url' => url($m->file_path),
            'alt' => $m->alt_text ?: $product->getLocalized('title'),
        ];
    })->values()->all();

    if (empty($galleryImages)) {
        $galleryImages = [[
            'url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1000&auto=format&fit=crop',
            'alt' => $product->getLocalized('title'),
        ]];
    }

    $hasDiscount = !empty($product->compare_at_price) && $product->compare_at_price > $product->price;
    $discountPercent = $hasDiscount ? round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) : 0;
    $isOutOfStock = !$product->is_in_stock || $product->status === 'out_of_stock' || (is_object($product->status) && $product->status->value === 'out_of_stock');
    $isWishlisted = in_array($product->id, session('sorolata_wishlist', []));
    $districtName = $product->district?->name_en ?? $product->district?->getLocalized('name') ?? __('Bangladesh');
    $artisanName = $product->artisan?->getLocalized('full_name') ?? __('Master Maker');
@endphp

<!-- 1. Product Hero & Multi-Image Gallery -->
<section style="position:relative; overflow:hidden; background:#F7F5F0">
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliDark)"></rect></svg>

    <div style="position:relative; max-width:1200px; margin:0 auto; padding:32px 24px 72px">
        <!-- Simple Breadcrumb -->
        <nav aria-label="Breadcrumb" style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; font-size:13px; color:#5A5044; margin-bottom:28px">
            <a href="{{ url('/') }}" style="color:#1F4D3A; font-weight:600">{{ __('Home') }}</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}" style="color:#1F4D3A; font-weight:600">{{ __('Shop') }}</a>
            @if($product->district)
                <span>/</span>
                <a href="{{ route('district.show', $product->district->slug ?? $product->district->uuid) }}" style="color:#1F4D3A; font-weight:600">{{ $districtName }}</a>
            @endif
            <span>/</span>
            <span>{{ $product->getLocalized('title') }}</span>
        </nav>

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
                                <img x-show="active === idx"
                                     :src="img.url"
                                     :alt="img.alt"
                                     style="width:100%; height:100%; object-fit:cover; display:block">
                            </template>

                            @if($isOutOfStock)
                                <div style="position:absolute; right:16px; top:16px; background:#2B2118; color:#FBF6EC; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px">
                                    {{ __('Sold out') }}
                                </div>
                            @elseif($hasDiscount)
                                <div style="position:absolute; right:16px; top:16px; background:#B8553A; color:#fff; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px">
                                    {{ __(':percent% off', ['percent' => $discountPercent]) }}
                                </div>
                            @endif

                            @if(count($galleryImages) > 1)
                                <button type="button" @click="prev()" aria-label="{{ __('Previous photo') }}"
                                        style="position:absolute; left:12px; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-weight:700; display:flex; align-items:center; justify-content:center">
                                    ←
                                </button>
                                <button type="button" @click="next()" aria-label="{{ __('Next photo') }}"
                                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; border:1px solid #D9C28A; background:rgba(251,246,236,0.92); color:#1F4D3A; font-weight:700; display:flex; align-items:center; justify-content:center">
                                    →
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                @if(count($galleryImages) > 1)
                    <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:16px">
                        @foreach($galleryImages as $idx => $img)
                            <button type="button"
                                    @click="active = {{ $idx }}"
                                    :style="active === {{ $idx }} ? 'border:2px solid #1F4D3A; padding:3px; background:#FBF6EC;' : 'border:1px solid #D9C28A; padding:3px; background:#fff; opacity:0.78;'"
                                    style="width:80px; height:80px; border-radius:14px; overflow:hidden; cursor:pointer; transition:all 0.2s">
                                <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}" style="width:100%; height:100%; object-fit:cover; border-radius:10px; display:block">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Product Info & Simple Purchase Controls -->
            <div style="flex:1 1 440px; min-width:0; padding-top:8px">
                <div style="font-size:12px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">
                    {{ $districtName }}
                </div>

                <h1 style="margin:0 0 10px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.2vw,54px); line-height:1.04; color:#1F2A24">
                    {{ $product->getLocalized('title') }}
                </h1>

                <div style="font-size:16px; color:#5A5044; margin-bottom:18px">
                    {{ __('Made by') }}
                    @if($product->artisan)
                        <a href="{{ route('artisan.show', $product->artisan->slug ?? $product->artisan->uuid) }}" style="color:#1F4D3A; font-weight:700; text-decoration:underline; text-underline-offset:4px">{{ $artisanName }}</a>
                    @else
                        <span style="color:#1F4D3A; font-weight:700">{{ $artisanName }}</span>
                    @endif
                </div>

                <div style="display:flex; align-items:baseline; gap:14px; margin-bottom:20px">
                    <x-price :price="$product->price" :compareAtPrice="$product->compare_at_price" :currency="$product->currency->value" size="lg" />
                </div>

                @if($product->subtitle)
                    <p style="margin:0 0 24px; font-size:17px; color:#5A5044; line-height:1.6">
                        {{ $product->getLocalized('subtitle') }}
                    </p>
                @endif

                <svg aria-hidden="true" width="100%" height="10" style="display:block; margin-bottom:24px"><rect width="100%" height="10" fill="url(#patStitchGold)"></rect></svg>

                @if(session('success'))
                    <div style="margin-bottom:16px; padding:12px 16px; border-radius:14px; background:#E7EEE4; border:1px solid #C5D3BF; color:#1F4D3A; font-size:14px; font-weight:600">
                        {{ session('success') }}
                    </div>
                @endif

                @if(!$isOutOfStock)
                    <form action="{{ route('cart.store') }}" method="POST" style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:24px" x-data="{ qty: 1 }">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" :value="qty">

                        <div style="display:inline-flex; align-items:center; border:1.5px solid #D9C28A; border-radius:999px; background:#fff; padding:4px">
                            <button type="button" @click="if(qty > 1) qty--" style="width:38px; height:38px; border:0; background:none; color:#1F4D3A; font-size:18px; font-weight:700">−</button>
                            <span x-text="qty" style="min-width:32px; text-align:center; font-weight:700; font-size:15px; color:#2B2118">1</span>
                            <button type="button" @click="qty++" style="width:38px; height:38px; border:0; background:none; color:#1F4D3A; font-size:18px; font-weight:700">+</button>
                        </div>

                        <button type="submit" style="flex:1 1 200px; background:#1F4D3A; color:#FBF6EC; border:0; border-radius:999px; padding:15px 30px; font-size:16px; font-weight:600; min-height:50px">
                            {{ __('Add to Cart') }}
                        </button>
                    </form>
                @else
                    <form action="{{ route('products.notify-restock', $product->slug) }}" method="POST" style="background:#FBF6EC; border:1px solid #D9C28A; border-radius:18px; padding:18px; margin-bottom:24px">
                        @csrf
                        <div style="font-size:14px; font-weight:700; color:#1F2A24; margin-bottom:8px">{{ __('This piece is sold out. Get notified when a new one is ready:') }}</div>
                        <div style="display:flex; flex-wrap:wrap; gap:10px">
                            <input type="email" name="email" required placeholder="{{ __('Your email') }}" style="flex:1 1 200px; height:46px; border-radius:999px; border:1px solid #D9C28A; padding:0 18px; font-size:14px; background:#fff; color:#2B2118">
                            <button type="submit" style="height:46px; border:0; border-radius:999px; background:#1F4D3A; color:#FBF6EC; font-weight:600; font-size:14px; padding:0 24px">
                                {{ __('Notify me') }}
                            </button>
                        </div>
                    </form>
                @endif

                <form action="{{ route('wishlist.toggle') }}" method="POST" style="margin-bottom:28px">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" style="display:inline-flex; align-items:center; gap:8px; border:1.5px solid #D9C28A; background:{{ $isWishlisted ? '#B8553A' : '#fff' }}; color:{{ $isWishlisted ? '#fff' : '#1F4D3A' }}; border-radius:999px; padding:10px 22px; font-size:14px; font-weight:600">
                        <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <span>{{ $isWishlisted ? __('Saved in Wishlist') : __('Save to Wishlist') }}</span>
                    </button>
                </form>

                <!-- Simple Trust Strip (Same as Homepage Hero) -->
                <div style="display:flex; flex-wrap:wrap; gap:14px 28px; font-size:14px; font-weight:600; color:#2B2118">
                    <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>{{ __('Made by hand') }}</span>
                    <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>{{ __('Fair pay to makers') }}</span>
                    <span style="display:flex; align-items:center; gap:8px"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 48 48" style="color:#B8892F"><use href="#rosetteArt"></use></svg>{{ __('Ships worldwide') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jamdani Band -->
<svg aria-hidden="true" width="100%" height="48" style="display:block; background:#FBF6EC"><rect width="100%" height="48" fill="url(#patBandGold)"></rect></svg>

<!-- 2. About This Piece -->
@if($product->description || $product->heritage_story || !empty($product->materials))
<section style="background:#F7F5F0">
    <div style="max-width:1000px; margin:0 auto; padding:72px 24px">
        <div style="text-align:center; margin-bottom:36px">
            <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
            <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:8px">{{ __('Craft & Details') }}</div>
            <h2 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(32px,4vw,48px); line-height:1.05; color:#1F2A24">{{ __('About this piece') }}</h2>
            <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 0"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
        </div>

        <div style="background:#fff; border:1px solid #E3D8BC; border-radius:20px; padding:32px; space-y:16px">
            @if($product->description)
                <div style="font-size:17px; color:#2B2118; line-height:1.75; white-space:pre-line">{{ $product->getLocalized('description') }}</div>
            @endif
            @if($product->heritage_story)
                <div style="margin-top:20px; padding-top:20px; border-top:1px dashed #D9C28A; font-size:16px; color:#5A5044; line-height:1.75; white-space:pre-line">{{ $product->getLocalized('heritage_story') }}</div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- 3. Meet the Maker -->
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

<!-- 4. Story -->
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

<!-- 5. Customer Reviews -->
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

        <!-- Simple Write a Review Form -->
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
