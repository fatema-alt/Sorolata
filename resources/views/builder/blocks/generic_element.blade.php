@php
    $type = $block->type->value;
    $settings = $settings ?? [];
    $align = $settings['alignment'] ?? 'center';
    $alignClass = match($align) {
        'left' => 'text-left items-start justify-start',
        'right' => 'text-right items-end justify-end',
        default => 'text-center items-center justify-center',
    };
    $textColor = $settings['text_color'] ?? '#1D1D1D';
    $headlineSize = $settings['headline_size'] ?? 'text-3xl sm:text-4xl';
    $imageUrl = $settings['image_url'] ?? null;
    $videoUrl = $settings['video_url'] ?? null;
@endphp

<div class="w-full flex flex-col {{ $alignClass }} py-4">
    @if ($type === 'element_heading')
        <h2 class="font-editorial-heading {{ $headlineSize }} font-bold tracking-tight" style="color: {{ $textColor }};">
            {{ $settings['headline'] ?? __('Heading Element') }}
        </h2>

    @elseif ($type === 'element_paragraph')
        <div class="prose max-w-3xl text-sm sm:text-base leading-relaxed text-[#555555]" style="color: {{ $textColor }};">
            <p>{{ $settings['subheadline'] ?? __('Storytelling and craft description narrative text.') }}</p>
        </div>

    @elseif ($type === 'element_image')
        @if (!empty($imageUrl))
            <div class="w-full max-w-3xl rounded-2xl overflow-hidden shadow-sm my-2">
                <img src="{{ $imageUrl }}" alt="{{ $settings['headline'] ?? __('Handcrafted Art') }}" class="w-full h-auto object-cover">
            </div>
        @else
            <div class="w-full max-w-md h-48 rounded-xl bg-stone-100 border-2 border-dashed border-stone-300 flex items-center justify-center text-stone-400 text-xs">
                {{ __('No image uploaded yet') }}
            </div>
        @endif

    @elseif ($type === 'element_video')
        @if (!empty($videoUrl))
            <div class="w-full max-w-3xl aspect-video rounded-2xl overflow-hidden shadow-lg bg-black my-2">
                <iframe src="{{ $videoUrl }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        @endif

    @elseif ($type === 'element_button')
        <div class="my-2">
            <a href="{{ $settings['primary_cta_url'] ?? '#' }}" 
               class="px-8 py-3 rounded-full text-xs font-semibold uppercase tracking-wider inline-block shadow-sm transition-all"
               style="background-color: {{ $settings['bg_color'] ?? '#214E34' }}; color: {{ $settings['text_color'] ?? '#FFFFFF' }};">
                {{ $settings['primary_cta_text'] ?? __('Explore Now') }}
            </a>
        </div>

    @elseif ($type === 'element_product_card')
        <div class="p-4 rounded-2xl border border-stone-200 bg-white max-w-xs shadow-sm text-center">
            @if(!empty($imageUrl))
                <img src="{{ $imageUrl }}" alt="Product" class="w-full h-44 object-cover rounded-xl mb-3">
            @endif
            <div class="text-xs uppercase font-semibold text-[#214E34] tracking-wider mb-1">{{ __('GI Certified') }}</div>
            <h4 class="font-editorial-heading text-lg font-bold text-gray-900 mb-1">{{ $settings['headline'] ?? __('Handcrafted Bengali Heirloom') }}</h4>
            <div class="text-sm font-bold text-[#C96A3D] mb-3">{{ $settings['price'] ?? '৳ 18,500' }}</div>
            <button class="w-full py-2 rounded-full bg-[#214E34] text-white text-xs font-semibold">
                {{ __('Add to Bag') }}
            </button>
        </div>

    @elseif ($type === 'element_artisan_quote')
        <div class="p-6 rounded-2xl bg-[#F8F6F2] border-l-4 border-[#214E34] max-w-2xl my-3">
            <p class="font-editorial-heading text-xl italic text-gray-800 mb-2">
                "{{ $settings['subheadline'] ?? __('Our hands carry the memory of three centuries of loom heritage.') }}"
            </p>
            <div class="text-xs font-semibold text-gray-600">
                — {{ $settings['headline'] ?? __('Master Artisan Ustad Alimullah') }} ({{ $settings['badge_text'] ?? __('Royal Weaver Guild') }})
            </div>
        </div>

    @elseif ($type === 'element_divider')
        <div class="w-full max-w-xl my-4 flex items-center justify-center space-x-3">
            <div class="h-[1px] flex-1 bg-stone-300"></div>
            <span class="text-[#D4AF37] text-xs">❖</span>
            <div class="h-[1px] flex-1 bg-stone-300"></div>
        </div>
    @endif
</div>
