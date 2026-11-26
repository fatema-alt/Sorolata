@extends('layouts.storefront')

@section('seo_meta')
    <title>About Sorolata — Handmade in Bangladesh</title>
    <meta name="description" content="We help makers from the districts of Bangladesh sell their craft to the world, and we pay them fairly.">
    <link rel="canonical" href="{{ route('pages.about') }}">
@endsection

@section('content')
    @php
        $aboutArtisans = \App\Models\Artisan::with('district')->where('is_verified', true)->orderBy('id')->take(4)->get();
    @endphp

    <!-- Dark Green Jali Header -->
    <section style="position:relative; overflow:hidden; background:#1F4D3A; color:#FBF6EC; border-bottom:3px solid #B8892F">
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; inset:0; pointer-events:none; opacity:0.25"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
        <div style="position:relative; max-width:860px; margin:0 auto; padding:60px 24px; text-align:center">
            <h1 style="margin:0 0 12px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(38px,4.5vw,56px); line-height:1.05; color:#fff">{{ __('About Sorolata') }}</h1>
            <p style="margin:0 auto 24px; max-width:500px; font-size:15px; color:#E7EEE4; line-height:1.6">{{ __('We help makers from the districts of Bangladesh sell their craft to the world, and we pay them fairly.') }}</p>
            <a href="{{ route('shop.index') }}" style="display:inline-block; background:#B8892F; color:#fff; font-weight:700; font-size:14px; padding:11px 28px; border-radius:999px; text-decoration:none">{{ __('Shop Now') }}</a>
        </div>
    </section>

    <!-- Why we started Sorolata -->
    <section style="background:#F7F5F0">
        <div style="max-width:1060px; margin:0 auto; padding:68px 24px; display:flex; flex-wrap:wrap; gap:48px; align-items:center">
            <div style="flex:1 1 340px; min-width:0">
                <div style="border-radius:180px 180px 16px 16px; overflow:hidden; aspect-ratio:4/3; background:#B8553A; border:2px solid #D9C28A; box-shadow:0 16px 36px rgba(43,33,24,0.1)">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000&auto=format&fit=crop" alt="Bangladesh handloom workshop" style="width:100%; height:100%; object-fit:cover; display:block">
                </div>
            </div>
            <div style="flex:1.1 1 360px; min-width:0">
                <div style="font-size:11px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#B8553A; margin-bottom:8px">✦ {{ __('Our story') }}</div>
                <h2 style="margin:0 0 16px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:clamp(30px,3.6vw,42px); line-height:1.1; color:#1F2A24">{{ __('Why we started Sorolata') }}</h2>
                <p style="margin:0 0 14px; font-size:15px; color:#5A5044; line-height:1.7">{{ __('Bangladesh has many beautiful crafts, but many makers cannot reach buyers directly. Some of these crafts are slowly disappearing as factory-made goods replace patient handwork.') }}</p>
                <p style="margin:0; font-size:15px; color:#5A5044; line-height:1.7">{{ __('So we bring the maker and the buyer together. The money goes straight to the maker, so they can keep the craft alive and teach it to the next generation.') }}</p>
            </div>
        </div>
    </section>

    <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>

    <!-- What we promise -->
    <section style="background:#F0E6D0">
        <div style="max-width:1060px; margin:0 auto; padding:64px 24px">
            <div style="text-align:center; margin-bottom:36px">
                <h2 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#1F2A24">{{ __('What we promise') }}</h2>
                <p style="margin:0; font-size:14px; color:#5A5044">{{ __('Four things you can count on.') }}</p>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:18px">
                @foreach([
                    ['title' => __('Made by hand'), 'text' => __('No factory copies. Each piece is made by one person or one family.')],
                    ['title' => __('Fair pay'), 'text' => __('Makers get paid directly. We keep only a small share to run the site.')],
                    ['title' => __('True origin'), 'text' => __('Each piece shows who made it and where. We say "GI certified" only when it is true.')],
                    ['title' => __('Safe delivery'), 'text' => __('Every parcel is packed by hand and insured, in Bangladesh and abroad.')],
                ] as $promise)
                    <div style="background:#fff; border:1px solid #D9C28A; border-radius:14px; padding:22px 20px">
                        <h3 style="margin:0 0 8px; font-family:'Cormorant Garamond',Georgia,serif; font-size:22px; font-weight:600; color:#1F4D3A">{{ $promise['title'] }}</h3>
                        <p style="margin:0; font-size:13px; color:#5A5044; line-height:1.6">{{ $promise['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section style="background:#F7F5F0">
        <div style="max-width:1060px; margin:0 auto; padding:64px 24px; text-align:center">
            <h2 style="margin:0 0 38px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#1F2A24">{{ __('How it works') }}</h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:28px">
                @foreach([
                    ['n' => '1', 'title' => __('We meet the maker'), 'text' => __('We visit the workshop and see the work ourselves.')],
                    ['n' => '2', 'title' => __('The maker sets the price'), 'text' => __('We list the piece with a real photo and story.')],
                    ['n' => '3', 'title' => __('You order'), 'text' => __('Pay with bKash, Nagad, Visa or Mastercard.')],
                    ['n' => '4', 'title' => __('We pack and send'), 'text' => __('Insured delivery to your door.')],
                ] as $step)
                    <div>
                        <div style="width:44px; height:48px; border-radius:22px 22px 8px 8px; background:#1F4D3A; color:#F3DC9A; font-family:'Cormorant Garamond',Georgia,serif; font-size:22px; font-weight:700; display:flex; align-items:center; justify-content:center; margin:0 auto 14px">{{ $step['n'] }}</div>
                        <h3 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-size:21px; font-weight:600; color:#1F2A24">{{ $step['title'] }}</h3>
                        <p style="margin:0; font-size:13px; color:#5A5044; line-height:1.55">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Our makers -->
    <section style="background:#F0E6D0">
        <div style="max-width:1060px; margin:0 auto; padding:64px 24px">
            <div style="text-align:center; margin-bottom:36px">
                <h2 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#1F2A24">{{ __('Our makers') }}</h2>
                <p style="margin:0; font-size:14px; color:#5A5044">{{ __('The people behind each piece.') }}</p>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:20px">
                @foreach($aboutArtisans as $art)
                    <a href="{{ route('artisan.show', $art->slug ?? $art->uuid) }}" style="background:#fff; border:1px solid #D9C28A; border-radius:16px; padding:12px 12px 18px; text-align:center; text-decoration:none; color:inherit; display:block">
                        <div style="border-radius:120px 120px 10px 10px; overflow:hidden; aspect-ratio:4/3; background:#1F4D3A; margin-bottom:12px">
                            <img src="{{ $art->portrait_url }}" alt="{{ $art->full_name }}" style="width:100%; height:100%; object-fit:cover; object-position:center top; display:block">
                        </div>
                        <h3 style="margin:0 0 4px; font-family:'Cormorant Garamond',Georgia,serif; font-size:22px; font-weight:600; color:#1F4D3A">{{ $art->full_name }}</h3>
                        <div style="font-size:12px; color:#5A5044">{{ $art->craft_type?->title() }} · {{ $art->district?->name_en }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Inside the workshops -->
    <section style="background:#F7F5F0">
        <div style="max-width:1060px; margin:0 auto; padding:64px 24px">
            <div style="text-align:center; margin-bottom:36px">
                <h2 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#1F2A24">{{ __('Inside the workshops') }}</h2>
                <p style="margin:0; font-size:14px; color:#5A5044">{{ __('Real photos of hands, looms, clay and metalworking.') }}</p>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px">
                @foreach([
                    ['src' => 'https://images.unsplash.com/photo-1583391733956-6c78276477e2?q=80&w=800&auto=format&fit=crop', 'alt' => 'Handloom weaving'],
                    ['src' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=800&auto=format&fit=crop', 'alt' => 'Brass and clay workshop'],
                    ['src' => 'https://images.unsplash.com/photo-1590736969955-71cc94901144?q=80&w=800&auto=format&fit=crop', 'alt' => 'Packing with natural cloth'],
                ] as $wPhoto)
                    <div style="border:1.5px solid #B8892F; border-radius:16px; padding:6px; background:#FBF6EC">
                        <div style="border-radius:11px; overflow:hidden; aspect-ratio:4/3; background:#1F4D3A">
                            <img src="{{ $wPhoto['src'] }}" alt="{{ $wPhoto['alt'] }}" style="width:100%; height:100%; object-fit:cover; display:block">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Have a question CTA -->
    <section style="position:relative; overflow:hidden; background:#1F4D3A; color:#FBF6EC">
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; inset:0; pointer-events:none; opacity:0.2"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
        <div style="position:relative; max-width:720px; margin:0 auto; padding:56px 24px; text-align:center">
            <h2 style="margin:0 0 8px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#fff">{{ __('Have a question?') }}</h2>
            <p style="margin:0 0 22px; font-size:14px; color:#E7EEE4">{{ __('We reply in person, not with a robot.') }}</p>
            <a href="{{ route('pages.contact') }}" style="display:inline-block; background:#B8892F; color:#fff; font-weight:700; font-size:14px; padding:11px 28px; border-radius:999px; text-decoration:none">{{ __('Contact Us') }}</a>
        </div>
    </section>
@endsection
