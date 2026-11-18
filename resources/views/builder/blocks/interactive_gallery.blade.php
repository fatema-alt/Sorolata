<section id="gallery-section" class="py-20 lg:py-28 bg-[#F4EFEA]">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs uppercase tracking-widest text-[#9E4732] font-semibold">Macro Visual Study</span>
                <h2 class="font-serif-luxury text-3xl sm:text-4xl lg:text-5xl font-light text-[#241F1E] mt-2">
                    Tactile Intricacies
                </h2>
            </div>
            <p class="text-sm text-[#786C65] max-w-sm mt-4 md:mt-0 font-serif-luxury italic text-lg">
                Notice the deliberate irregularities that prove human hand execution over automated mechanics.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $sampleImages = [
                    'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=1000&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=1000&auto=format&fit=crop',
                ];
            @endphp

            @foreach($sampleImages as $index => $img)
                <div class="group relative rounded-3xl overflow-hidden bg-[#E2DBD3] aspect-[3/4] shadow-md">
                    <img 
                        src="{{ $img }}" 
                        alt="Macro craftsmanship study {{ $index + 1 }}" 
                        class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <span class="text-white text-xs font-medium tracking-wide">Macro Detail 0{{ $index + 1 }} • Hand Loom Tension</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
