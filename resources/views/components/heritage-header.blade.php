@props([
    'eyebrow' => '',
    'title' => '',
    'subtitle' => '',
    'breadcrumb' => null,
    'showBand' => false,
])

<section style="position:relative; overflow:hidden; background:#F0E6D0; border-bottom:1px solid #E3D8BC">
    <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; left:0; top:0; pointer-events:none"><rect width="100%" height="100%" fill="url(#patJaliGold)" opacity="0.4"></rect></svg>
    <div style="position:relative; max-width:1200px; margin:0 auto; padding:56px 24px; text-align:center">
        @if($breadcrumb)
            <div style="display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#5A5044; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:14px">
                <a href="{{ url('/') }}" style="color:#1F4D3A; font-weight:600">{{ __('Home') }}</a>
                <span>/</span>
                <span>{{ $breadcrumb }}</span>
            </div>
        @endif
        <svg aria-hidden="true" width="180" height="24" viewBox="0 0 180 24" style="display:block; margin:0 auto 14px; color:#B8892F"><use href="#ornArt"></use></svg>
        @if($eyebrow)
            <div style="font-size:12px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color:#8F3F26; margin-bottom:10px">{{ $eyebrow }}</div>
        @endif
        <h1 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(36px,4.5vw,58px); line-height:1.05; color:#1F2A24">{{ $title }}</h1>
        <svg aria-hidden="true" width="120" height="10" style="display:block; margin:14px auto 14px"><rect width="120" height="10" fill="url(#patStitchGold)"></rect></svg>
        @if($subtitle)
            <p style="margin:0 auto; max-width:680px; font-size:17px; color:#5A5044; line-height:1.6">{{ $subtitle }}</p>
        @endif
    </div>
</section>
@if($showBand)
    <svg aria-hidden="true" width="100%" height="48" style="display:block; background:#FBF6EC"><rect width="100%" height="48" fill="url(#patBandGold)"></rect></svg>
@endif
