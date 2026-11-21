<section class="py-20 lg:py-28 bg-[#F4EFEA] border-t border-[#241F1E]/5">
    <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center space-y-6">
        <span class="text-xs uppercase tracking-widest text-[#9E4732] font-semibold">{{ !empty($settings['badge_text']) ? $settings['badge_text'] : __('Collector Impressions') }}</span>

        <blockquote class="font-serif-luxury text-2xl sm:text-3xl lg:text-4xl italic text-[#241F1E] font-light leading-snug">
            "{{ !empty($settings['subheadline']) ? $settings['subheadline'] : ($settings['impression'] ?? __('Unboxing this piece in London was a transcendent experience. The tactile weight, the aroma of natural river washed cotton, and the signed provenance letter from Master Weaver Alimuddin makes it the most prized textile in my entire collection.')) }}"
        </blockquote>

        <div class="pt-4">
            <span class="block font-medium text-sm text-[#241F1E]">{{ !empty($settings['headline']) ? $settings['headline'] : ($settings['collector_name'] ?? __('Dr. Eleanor Vance')) }}</span>
            <span class="block text-xs text-[#8C837D] mt-0.5">{{ $settings['collector_location'] ?? __('Textile Historian & Collector • Kensington, London') }}</span>
        </div>
    </div>
</section>
