<section id="districts-section" class="py-20 lg:py-28 bg-white border-b border-[#E8E8E8]">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="max-w-3xl mb-12">
            <div class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-[#214E34] font-bold mb-3">
                <span class="w-2 h-2 rounded-full bg-[#214E34]"></span>
                <span>Geographical Indication & Regional Provenance</span>
            </div>
            <h2 class="font-editorial-heading text-3xl sm:text-4xl lg:text-5xl font-light text-[#1D1D1D]">
                Rooted in {{ $district?->name_en ?? 'Bengal' }} (<span class="font-editorial-bangla">{{ $district?->name_bn ?? 'বাংলাদেশ' }}</span>)
            </h2>
            <p class="font-editorial-body text-base text-[#555555] mt-4 leading-relaxed">
                Soil, microclimate, and river humidity shape the personality of every craft in Bangladesh. Learn why this exact district is the irreplaceable cradle of this tradition.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1: GI Specialty -->
            <div class="p-8 rounded-[32px] bg-[#F8F6F2] border border-[#E8E8E8] space-y-4 shadow-soft-xs hover-lift">
                <div class="w-10 h-10 rounded-full bg-[#214E34]/10 flex items-center justify-center text-[#214E34]">
                    <x-icon name="shield-check" class="w-5 h-5 text-[#214E34]" />
                </div>
                <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">GI Heritage Specialty</h3>
                <p class="text-sm text-[#555555] leading-relaxed">
                    {{ $district?->gi_specialty ?? 'World-renowned handloom textile weaving registered under national geographical indication.' }}
                </p>
            </div>

            <!-- Card 2: River Basin Climate -->
            <div class="p-8 rounded-[32px] bg-[#F8F6F2] border border-[#E8E8E8] space-y-4 shadow-soft-xs hover-lift">
                <div class="w-10 h-10 rounded-full bg-[#C96A3D]/10 flex items-center justify-center text-[#C96A3D]">
                    <x-icon name="sparkles" class="w-5 h-5 text-[#C96A3D]" />
                </div>
                <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">River Basin & Microclimate</h3>
                <p class="text-sm text-[#555555] leading-relaxed">
                    {{ $district?->river_basin ?? 'Shitalakshya & Jamuna River Basin' }} — the ambient river air provides the exact morning humidity essential for ultra-fine thread tension without snapping.
                </p>
            </div>

            <!-- Card 3: Historical Narrative -->
            <div class="p-8 rounded-[32px] bg-[#F8F6F2] border border-[#E8E8E8] space-y-4 shadow-soft-xs hover-lift">
                <div class="w-10 h-10 rounded-full bg-[#214E34]/10 flex items-center justify-center text-[#214E34]">
                    <x-icon name="map-pin" class="w-5 h-5 text-[#214E34]" />
                </div>
                <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">Historical Chronicle</h3>
                <p class="text-sm text-[#555555] leading-relaxed">
                    {{ $district?->historical_narrative ?? 'Documented in traveler chronicles since the 14th century, cultivated by imperial patronage and preserved through generational resilience.' }}
                </p>
            </div>
        </div>
    </div>
</section>
