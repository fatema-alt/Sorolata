@props([
    'title',
    'subtitle',
    'imageUrl' => null,
    'image' => null,
    'tag' => null,
    'link' => '#',
])

@php
    $displayImage = $imageUrl ?: ($image ?: 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000&auto=format&fit=crop');
@endphp

<div {{ $attributes->merge(['class' => 'group relative rounded-[32px] overflow-hidden aspect-[16/10] bg-[#F8F6F2] shadow-soft-xs hover:shadow-soft-hover transition-luxury']) }}>
    <a href="{{ $link }}" class="absolute inset-0 z-10" aria-label="{{ $title }}"></a>
    <img 
        src="{{ $displayImage }}" 
        alt="{{ $title }}" 
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
        loading="lazy"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex flex-col justify-end p-8 text-white pointer-events-none z-20">
        <span class="inline-block text-[10px] tracking-widest uppercase font-bold text-[#DE7D4E] mb-1">
            {{ $tag ? __($tag) : __('Limited Drop') }}
        </span>
        <h3 class="font-editorial-heading text-3xl font-light text-white mb-2 leading-tight">
            {{ $title }}
        </h3>
        <p class="text-sm text-white/80 font-light max-w-md line-clamp-2 leading-relaxed">
            {{ $subtitle }}
        </p>
        <div class="mt-4 pointer-events-auto">
            <x-button :href="$link" variant="secondary" size="sm" class="border-white/80 text-white hover:bg-white/20">
                {{ __('Explore Drop →') }}
            </x-button>
        </div>
    </div>
</div>
