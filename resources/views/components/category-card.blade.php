@props([
    'category',
    'imageUrl' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=800&auto=format&fit=crop',
])

<a href="#curated-collection" {{ $attributes->merge(['class' => 'group relative block rounded-[32px] overflow-hidden aspect-[4/5] bg-[#F8F6F2] shadow-soft-xs hover:shadow-soft-hover transition-luxury']) }}>
    <img 
        src="{{ $imageUrl }}" 
        alt="{{ $category->name }}" 
        class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out"
        loading="lazy"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex flex-col justify-end p-6 text-white">
        <span class="text-[10px] uppercase tracking-widest text-white/80 font-semibold mb-1">{{ __('Authentic Guild') }}</span>
        <h4 class="font-editorial-heading text-2xl font-light text-white group-hover:text-[#F8F6F2] transition-colors">
            {{ $category->name }}
        </h4>
        <p class="text-xs text-white/70 mt-1 line-clamp-1 font-light">
            {{ $category->description ?? __('Generational techniques passed down since antiquity.') }}
        </p>
    </div>
</a>
