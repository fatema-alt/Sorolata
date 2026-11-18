<div 
    x-data="{ open: false, query: '' }" 
    @keydown.window.escape="open = false" 
    class="relative"
>
    <!-- Trigger Button -->
    <button 
        type="button" 
        @click="open = true; $nextTick(() => $refs.searchInput.focus())"
        class="flex items-center gap-2 text-xs tracking-wide px-3.5 py-2 rounded-full bg-white/70 hover:bg-white text-[#555555] hover:text-[#1D1D1D] border border-[#E8E8E8] transition-luxury shadow-soft-xs"
        aria-label="{{ __('Search Handcrafted Heritage') }}"
    >
        <x-icon name="search" class="w-3.5 h-3.5 text-[#214E34]" />
        <span class="hidden xl:inline whitespace-nowrap">{{ __('Search artifacts, districts...') }}</span>
        <span class="xl:hidden hidden sm:inline whitespace-nowrap">{{ __('Search...') }}</span>
        <kbd class="hidden md:inline-block px-1.5 py-0.5 text-[9px] bg-[#E8E8E8] text-[#888888] rounded">⌘K</kbd>
    </button>

    <!-- Glass Floating Flyout / Modal -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="open = false"
        class="fixed inset-x-4 top-24 max-w-2xl mx-auto sorolata-glass-white rounded-[32px] p-6 shadow-soft-lg z-50 border border-[#E8E8E8]"
        style="display: none;"
    >
        <div class="flex items-center gap-3 pb-4 border-b border-[#E8E8E8]">
            <x-icon name="search" class="w-5 h-5 text-[#214E34]" />
            <input 
                x-ref="searchInput"
                x-model="query"
                type="text" 
                placeholder="{{ __('Search by craft, district (e.g. Tangail, Sonargaon), or artisan...') }}"
                class="w-full bg-transparent text-base font-editorial-body text-[#1D1D1D] placeholder-[#888888] focus:outline-none"
            >
            <button @click="open = false" class="p-1 rounded-full text-[#888888] hover:text-[#1D1D1D]">
                <x-icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <!-- Quick Heritage Tags & Recent Searches -->
        <div class="pt-4 space-y-4">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-[#888888] font-bold block mb-2">{{ __('Popular Heritage Searches') }}</span>
                <div class="flex flex-wrap gap-2 text-xs">
                    <a href="#curated-collection" @click="open = false" class="px-3 py-1.5 rounded-full bg-[#F8F6F2] hover:bg-[#214E34] hover:text-white text-[#555555] transition-colors">
                        {{ __('Sonargaon Muslin') }}
                    </a>
                    <a href="#curated-collection" @click="open = false" class="px-3 py-1.5 rounded-full bg-[#F8F6F2] hover:bg-[#214E34] hover:text-white text-[#555555] transition-colors">
                        {{ __('Tangail Mulberry Silk') }}
                    </a>
                    <a href="#curated-collection" @click="open = false" class="px-3 py-1.5 rounded-full bg-[#F8F6F2] hover:bg-[#214E34] hover:text-white text-[#555555] transition-colors">
                        {{ __('Bogura Brass Kettle') }}
                    </a>
                    <a href="#curated-collection" @click="open = false" class="px-3 py-1.5 rounded-full bg-[#F8F6F2] hover:bg-[#214E34] hover:text-white text-[#555555] transition-colors">
                        {{ __('Rangpur Shatranji Carpet') }}
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-[#E8E8E8]/60 flex items-center justify-between text-xs text-[#888888]">
                <span>{{ __('ESC to close') }}</span>
                <span class="text-[#214E34] font-medium">{{ __('Scout Indexed • Typesense Fast') }}</span>
            </div>
        </div>
    </div>
</div>
