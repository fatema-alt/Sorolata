<div class="h-full flex flex-col bg-[#12141a] text-[#E0E2EC] overflow-hidden">
    
    <!-- TOP CONTROL BAR -->
    <header class="h-14 bg-[#1a1d24] border-b border-[#282d37] px-4 flex items-center justify-between shrink-0 z-30 select-none">
        
        <!-- Left: Brand & Page Context -->
        <div class="flex items-center space-x-3">
            <a href="{{ $productId ? route('products.show', $product?->slug ?? '') : url('/') }}" 
               class="p-1.5 text-gray-400 hover:text-white hover:bg-[#282d37] rounded-lg transition-colors"
               title="Exit to Storefront">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="h-5 w-px bg-[#282d37]"></div>

            <div class="flex items-center space-x-2">
                <span class="font-editorial-heading text-xl text-white font-normal tracking-tight">Sorolata</span>
                <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 rounded-full bg-[#214E34]/30 text-[#A3D9B1] border border-[#214E34]/50 font-semibold">
                    Studio Engine
                </span>
            </div>

            <div class="h-5 w-px bg-[#282d37]"></div>

            <!-- Page Title Input -->
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-400 font-mono">{{ strtoupper($pageType) }}:</span>
                <input type="text" 
                       wire:model.live.debounce.400ms="title" 
                       class="bg-transparent text-sm font-medium text-white px-2 py-1 rounded border border-transparent hover:border-[#282d37] focus:border-[#214E34] focus:bg-[#222731] focus:outline-none transition-all w-48 sm:w-64 truncate">
            </div>
        </div>

        <!-- Center: Device Switcher & Auto-save Status -->
        <div class="flex items-center space-x-4">
            
            <!-- Viewport Switcher -->
            <div class="bg-[#12141a] p-1 rounded-lg border border-[#282d37] flex items-center space-x-1">
                <button type="button" 
                        wire:click="setViewport('desktop')"
                        class="px-2.5 py-1 rounded text-xs font-medium flex items-center space-x-1.5 transition-all {{ $activeViewport === 'desktop' ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-400 hover:text-gray-200 hover:bg-[#222731]' }}"
                        title="Desktop (1280px+)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden md:inline">Desktop</span>
                </button>

                <button type="button" 
                        wire:click="setViewport('tablet')"
                        class="px-2.5 py-1 rounded text-xs font-medium flex items-center space-x-1.5 transition-all {{ $activeViewport === 'tablet' ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-400 hover:text-gray-200 hover:bg-[#222731]' }}"
                        title="Tablet (768px)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden md:inline">Tablet</span>
                </button>

                <button type="button" 
                        wire:click="setViewport('mobile')"
                        class="px-2.5 py-1 rounded text-xs font-medium flex items-center space-x-1.5 transition-all {{ $activeViewport === 'mobile' ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-400 hover:text-gray-200 hover:bg-[#222731]' }}"
                        title="Mobile (375px)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden md:inline">Mobile</span>
                </button>
            </div>

            <!-- Auto-save Status Indicator -->
            <div class="hidden lg:flex items-center space-x-2 text-xs text-gray-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $statusMessage }}</span>
            </div>
        </div>

        <!-- Right: Actions & Publish -->
        <div class="flex items-center space-x-2.5">
            
            @if ($productId && $product)
                <a href="{{ route('products.show', $product->slug) }}" 
                   target="_blank"
                   class="px-3 py-1.5 rounded-lg border border-[#282d37] hover:border-gray-500 bg-[#222731] hover:bg-[#282d37] text-xs font-medium text-gray-200 transition-colors flex items-center space-x-1.5">
                    <span>Preview Live</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            @endif

            <button type="button" 
                    wire:click="autoSave"
                    class="px-3 py-1.5 rounded-lg border border-[#282d37] hover:bg-[#222731] text-xs font-medium text-gray-300 transition-colors">
                Save Draft
            </button>

            <button type="button" 
                    wire:click="publish"
                    wire:loading.attr="disabled"
                    class="px-4 py-1.5 rounded-lg bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold tracking-wide shadow-sm transition-all flex items-center space-x-1.5 disabled:opacity-50">
                <span wire:loading.remove wire:target="publish">Publish Live</span>
                <span wire:loading wire:target="publish">Publishing...</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </div>
    </header>

    <!-- MAIN STUDIO BODY (3-COLUMN ELEMENTOR/FRAMER WORKSPACE) -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- 1. LEFT SIDEBAR (LIBRARY, TEMPLATES, REVISIONS, SEO) -->
        <aside class="w-80 bg-[#1a1d24] border-r border-[#282d37] flex flex-col shrink-0 z-20">
            
            <!-- Sidebar Navigation Tabs (Elementor Style: Elements, Blocks, Templates, History, SEO) -->
            <div class="flex border-b border-[#282d37] bg-[#14161d] text-xs font-medium text-gray-400">
                <button type="button" 
                        wire:click="setActiveTab('elements')"
                        class="flex-1 py-3 text-center border-b-2 transition-colors {{ $activeTab === 'elements' ? 'border-[#214E34] text-white bg-[#1a1d24] font-bold' : 'border-transparent hover:text-gray-200' }}">
                    Elements
                </button>
                <button type="button" 
                        wire:click="setActiveTab('library')"
                        class="flex-1 py-3 text-center border-b-2 transition-colors {{ $activeTab === 'library' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent hover:text-gray-200' }}">
                    Blocks
                </button>
                <button type="button" 
                        wire:click="setActiveTab('templates')"
                        class="flex-1 py-3 text-center border-b-2 transition-colors {{ $activeTab === 'templates' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent hover:text-gray-200' }}">
                    Templates
                </button>
                <button type="button" 
                        wire:click="setActiveTab('revisions')"
                        class="flex-1 py-3 text-center border-b-2 transition-colors {{ $activeTab === 'revisions' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent hover:text-gray-200' }}">
                    History
                </button>
                <button type="button" 
                        wire:click="setActiveTab('seo')"
                        class="flex-1 py-3 text-center border-b-2 transition-colors {{ $activeTab === 'seo' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent hover:text-gray-200' }}">
                    SEO
                </button>
            </div>

            <!-- Tab 0: Modular Elements (Widgets) -->
            @if ($activeTab === 'elements')
                <div class="p-3 border-b border-[#282d37]">
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.150ms="searchQuery" 
                               placeholder="Search widgets & elements..."
                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white pl-8 pr-3 py-2 focus:border-[#214E34] focus:outline-none transition-colors placeholder-gray-500">
                        <svg class="w-4 h-4 text-gray-500 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto builder-scroll p-3 space-y-4">
                    <!-- Typography Category -->
                    <div>
                        <h4 class="text-[10px] font-bold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Typography & Text</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Text</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Heading Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_heading'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_heading')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-blue-500/20 text-blue-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <span class="font-bold text-sm">H</span>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Heading</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>

                            <!-- Paragraph Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_paragraph'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_paragraph')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-cyan-500/20 text-cyan-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h12" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Paragraph</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>
                        </div>
                    </div>

                    <!-- Media Category -->
                    <div>
                        <h4 class="text-[10px] font-bold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Media & Visuals</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Upload</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Image Upload Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_image'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_image')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Image (Upload)</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>

                            <!-- Video Embed Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_video'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_video')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-red-500/20 text-red-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Video Player</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>
                        </div>
                    </div>

                    <!-- Commerce Category -->
                    <div>
                        <h4 class="text-[10px] font-bold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Commerce & Products</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Sales</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Product Card Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_product_card'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_product_card')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-amber-500/20 text-amber-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Product Card</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>

                            <!-- Button Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_button'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_button')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-purple-500/20 text-purple-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Action Button</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>
                        </div>
                    </div>

                    <!-- Heritage & Layout Category -->
                    <div>
                        <h4 class="text-[10px] font-bold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Heritage & Layout</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Culture</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Artisan Quote Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_artisan_quote'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_artisan_quote')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-amber-500/20 text-amber-300 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <span class="font-serif font-bold text-base">“</span>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Artisan Quote</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>

                            <!-- Divider Element (Draggable) -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'element_divider'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addElement('element_divider')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex flex-col items-center text-center group select-none shadow-sm">
                                <div class="w-8 h-8 rounded-md bg-gray-500/20 text-gray-300 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                                    <span class="font-bold text-xs">—❖—</span>
                                </div>
                                <span class="text-xs font-semibold text-white group-hover:text-[#A3D9B1]">Divider</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Drag to Canvas</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tab 1: Block Library -->
            @if ($activeTab === 'library')
                <div class="p-3 border-b border-[#282d37]">
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.150ms="searchQuery" 
                               placeholder="Search artisan blocks..."
                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white pl-8 pr-3 py-2 focus:border-[#214E34] focus:outline-none transition-colors placeholder-gray-500">
                        <svg class="w-4 h-4 text-gray-500 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto builder-scroll p-3 space-y-5">
                    
                    <!-- Category: Shajgoj Content-to-Commerce Magazine Blocks -->
                    <div>
                        <h4 class="text-[11px] font-semibold tracking-wider uppercase text-emerald-400 mb-2 flex items-center justify-between">
                            <span>Content to Commerce</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold">SHAJGOJ STYLE</span>
                        </h4>
                        <div class="grid grid-cols-1 gap-2">
                            
                            <!-- Magazine Hero Grid -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'magazine_hero_grid'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('magazine_hero_grid')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-[#214E34]/20 text-[#A3D9B1] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Magazine Hero Grid</div>
                                    <div class="text-[11px] text-gray-400 truncate">Large lead story with dual stacked sub-articles</div>
                                </div>
                            </div>

                            <!-- Topic Bubbles Carousel -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'topic_bubbles'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('topic_bubbles')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Topic Craft Bubbles</div>
                                    <div class="text-[11px] text-gray-400 truncate">Circular craft highlight avatars (Jamdani, Brass, etc.)</div>
                                </div>
                            </div>

                            <!-- Editorial Feed + Trending Sidebar -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'editorial_feed_sidebar'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('editorial_feed_sidebar')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Gazette Feed + Sidebar</div>
                                    <div class="text-[11px] text-gray-400 truncate">2-col story cards + trending category list</div>
                                </div>
                            </div>

                            <!-- Shoppable Product Shelf -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'shoppable_product_shelf'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('shoppable_product_shelf')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-[#C96A3D]/20 text-[#E79770] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">In-Story Shoppable Shelf</div>
                                    <div class="text-[11px] text-gray-400 truncate">4-item product row directly linked to articles</div>
                                </div>
                            </div>

                            <!-- Video Reels Showcase -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'video_reels_showcase'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('video_reels_showcase')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Artisan Video Reels</div>
                                    <div class="text-[11px] text-gray-400 truncate">Living craft video clips with play overlay</div>
                                </div>
                            </div>

                            <!-- Heritage Club Newsletter -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'heritage_club_newsletter'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('heritage_club_newsletter')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Guild Gazette Newsletter</div>
                                    <div class="text-[11px] text-gray-400 truncate">Collector club invitation & email subscription</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category: Heritage & Provenance -->
                    <div>
                        <h4 class="text-[11px] font-semibold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Heritage & Artisans</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Curated</span>
                        </h4>
                        <div class="grid grid-cols-1 gap-2">
                            
                            <!-- Artisan Spotlight Block -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'artisan_spotlight'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('artisan_spotlight')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-[#214E34]/20 text-[#A3D9B1] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Artisan Spotlight</div>
                                    <div class="text-[11px] text-gray-400 truncate">Master weaver portrait, atelier quote & heritage loom</div>
                                </div>
                            </div>

                            <!-- Heritage Provenance & GI Block -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'heritage_provenance'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('heritage_provenance')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-[#C96A3D]/20 text-[#E79770] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Heritage Provenance</div>
                                    <div class="text-[11px] text-gray-400 truncate">GI Seal, river basin geography & craft certification</div>
                                </div>
                            </div>

                            <!-- Specs & Craft Anatomy -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'specs_anatomy'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('specs_anatomy')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Specs & Craft Anatomy</div>
                                    <div class="text-[11px] text-gray-400 truncate">Thread count, alloy mix, motif origins & care guide</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category: Commerce & Conversion -->
                    <div>
                        <h4 class="text-[11px] font-semibold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Commerce Conversion</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Revenue</span>
                        </h4>
                        <div class="grid grid-cols-1 gap-2">
                            
                            <!-- Hero Showcase -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'hero_cinematic'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('hero_cinematic')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Cinematic Hero Showcase</div>
                                    <div class="text-[11px] text-gray-400 truncate">Large lifestyle framing, editorial heading & dynamic pricing</div>
                                </div>
                            </div>

                            <!-- Sticky Purchase Box -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'sticky_purchase_box'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('sticky_purchase_box')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Editorial Purchase Box</div>
                                    <div class="text-[11px] text-gray-400 truncate">Variant selector, add-to-cart & collector warranty</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category: Editorial & Storytelling -->
                    <div>
                        <h4 class="text-[11px] font-semibold tracking-wider uppercase text-gray-400 mb-2 flex items-center justify-between">
                            <span>Editorial & Story</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#282d37] text-gray-300">Culture</span>
                        </h4>
                        <div class="grid grid-cols-1 gap-2">
                            
                            <!-- Interactive Visual Gallery -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'interactive_gallery'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('interactive_gallery')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Visual Gallery & Macro Details</div>
                                    <div class="text-[11px] text-gray-400 truncate">High-definition closeups of artisanal weave & patina</div>
                                </div>
                            </div>

                            <!-- Editorial Narrative -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'editorial_narrative'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('editorial_narrative')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Editorial Narrative</div>
                                    <div class="text-[11px] text-gray-400 truncate">Longform story of ancient techniques & royal patronage</div>
                                </div>
                            </div>

                            <!-- Testimonials -->
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('text/plain', 'testimonial_editorial'); event.dataTransfer.effectAllowed = 'copy';"
                                 wire:click="addSection('testimonial_editorial')"
                                 title="Drag & Drop onto Canvas or Click to Append"
                                 class="group p-2.5 rounded-lg border border-[#282d37] hover:border-[#214E34] bg-[#222731] hover:bg-[#252b36] cursor-grab active:cursor-grabbing transition-all flex items-start space-x-3 select-none">
                                <div class="w-8 h-8 rounded-md bg-sky-500/20 text-sky-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] transition-colors">Collector Impressions</div>
                                    <div class="text-[11px] text-gray-400 truncate">Verified global expat & connoisseur appraisals</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tab 2: Pre-built Flagship Templates -->
            @if ($activeTab === 'templates')
                <div class="p-3 border-b border-[#282d37] bg-[#14161d]">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Pre-Designed Archival Templates</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">1-Click import for Home, Product, About, Contact & Campaigns.</p>
                </div>
                <div class="flex-1 overflow-y-auto builder-scroll p-3 space-y-3">
                    @forelse ($templates as $tmpl)
                        <div class="rounded-xl border border-[#282d37] hover:border-[#214E34] bg-[#222731] overflow-hidden transition-all group">
                            @if (!empty($tmpl->thumbnail_url))
                                <div class="h-24 w-full overflow-hidden relative">
                                    <img src="{{ $tmpl->thumbnail_url }}" alt="{{ $tmpl->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <span class="absolute top-2 right-2 text-[9px] uppercase px-2 py-0.5 rounded-full bg-[#12141a]/80 backdrop-blur text-[#A3D9B1] border border-[#214E34] font-semibold">
                                        {{ $tmpl->category ?? 'Template' }}
                                    </span>
                                </div>
                            @endif
                            <div class="p-3">
                                <span class="text-xs font-bold text-white block mb-1">{{ $tmpl->name }}</span>
                                <div class="text-[10px] text-gray-400 mb-3 flex items-center space-x-2">
                                    <span>Sections: {{ count($tmpl->layout_json['sections'] ?? []) }}</span>
                                    <span>•</span>
                                    <span class="capitalize">{{ str_replace('_', ' ', $tmpl->category ?? 'Standard') }}</span>
                                </div>
                                <button type="button" 
                                        wire:click="importTemplate({{ $tmpl->id }})"
                                        wire:confirm="This will replace current canvas sections with this template. Proceed?"
                                        class="w-full py-1.5 rounded-lg bg-[#1a1d24] hover:bg-[#214E34] text-xs font-semibold text-gray-200 hover:text-white border border-[#282d37] hover:border-transparent transition-all flex items-center justify-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span>Import This Template</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 text-xs">
                            No templates configured yet.
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Tab 3: Revisions & Rollback History -->
            @if ($activeTab === 'revisions')
                <div class="p-3 border-b border-[#282d37] bg-[#14161d]">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Version Timeline</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Every modification is checkpointed for safe 1-click restore.</p>
                </div>
                <div class="flex-1 overflow-y-auto builder-scroll p-3 space-y-2">
                    @forelse ($revisions as $rev)
                        <div class="p-2.5 rounded-lg border border-[#282d37] bg-[#222731] flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-xs font-bold text-white">v{{ $rev->version }}</span>
                                    @if ($rev->is_published)
                                        <span class="text-[9px] px-1.5 rounded bg-emerald-500/20 text-emerald-400 font-medium">Published</span>
                                    @else
                                        <span class="text-[9px] px-1.5 rounded bg-gray-600/30 text-gray-400 font-medium">Draft</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-gray-400">{{ $rev->created_at->diffForHumans() }}</div>
                            </div>
                            <button type="button" 
                                    wire:click="rollback({{ $rev->id }})"
                                    class="px-2.5 py-1 rounded bg-[#1a1d24] hover:bg-[#C96A3D] text-[11px] font-medium text-gray-300 hover:text-white border border-[#282d37] transition-all">
                                Restore
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 text-xs">
                            No revision checkpoints yet.
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Tab 4: SEO & Meta Architecture -->
            @if ($activeTab === 'seo')
                <div class="p-3 border-b border-[#282d37] bg-[#14161d]">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">SEO & Rich Snippets</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Configure meta tags for Google Rich Search & OpenGraph.</p>
                </div>
                <div class="flex-1 overflow-y-auto builder-scroll p-4 space-y-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Page Title (Meta Title)</label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="seoTitle" 
                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Meta Description</label>
                        <textarea wire:model.live.debounce.300ms="seoDescription" 
                                  rows="3"
                                  class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                    </div>

                    <!-- Google SERP Snippet Preview -->
                    <div class="p-3 rounded-lg bg-[#14161d] border border-[#282d37]">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gray-500 block mb-2">Google SERP Preview</span>
                        <div class="text-sm text-[#8ab4f8] hover:underline cursor-pointer truncate font-medium">
                            {{ $seoTitle ?: $title }}
                        </div>
                        <div class="text-[11px] text-[#34a853] truncate mt-0.5">
                            https://sorolata.com/{{ $pageType }}/{{ $slug }}
                        </div>
                        <div class="text-xs text-[#bdc1c6] mt-1 line-clamp-2">
                            {{ $seoDescription ?: 'Discover genuine Bangladeshi handloom, royal brass, and heritage masterpieces certified by local artisan guilds.' }}
                        </div>
                    </div>
                </div>
            @endif

        </aside>

        <!-- 2. CENTER CANVAS (RESPONSIVE VIEWPORT STAGE) -->
        <main class="flex-1 bg-[#0f1115] overflow-y-auto builder-scroll p-4 md:p-8 flex flex-col items-center"
              @dragover.prevent="event.dataTransfer.dropEffect = 'copy'">
            
            <!-- VIEWPORT CONTAINER: Desktop (100%), Tablet (768px), Mobile (375px) -->
            <div class="w-full transition-all duration-300 {{ 
                $activeViewport === 'mobile' ? 'max-w-[385px] border-[10px] border-[#222731] rounded-[44px] shadow-2xl bg-[#F8F6F2] overflow-hidden my-4' : 
                ($activeViewport === 'tablet' ? 'max-w-[780px] border-[6px] border-[#222731] rounded-[28px] shadow-2xl bg-[#F8F6F2] overflow-hidden my-4' : 
                'max-w-[1240px] bg-[#F8F6F2] rounded-2xl shadow-xl border border-[#282d37]') 
            }}"
                 @dragover.prevent="event.dataTransfer.dropEffect = 'copy'">

                <!-- Inner Content Container -->
                <div class="min-h-[800px] flex flex-col divide-y divide-[#E8E8E8]"
                     @dragover.prevent="event.dataTransfer.dropEffect = 'copy'">
                    
                    <!-- Elementor Top Dropzone (Index 0) -->
                    @if (count($sections) > 0)
                        <div x-data="{ isOver: false }"
                             @dragover.prevent="isOver = true; event.dataTransfer.dropEffect = 'copy'"
                             @dragleave.prevent="isOver = false"
                             @drop.prevent="
                                 isOver = false;
                                 const reorderFrom = event.dataTransfer.getData('sorolata/reorder');
                                 const itemType = event.dataTransfer.getData('text/plain');
                                 if (reorderFrom !== '') {
                                     $wire.reorderSections(parseInt(reorderFrom), 0);
                                 } else if (itemType) {
                                     $wire.insertElementAt(itemType, 0);
                                 }
                             "
                             class="w-full transition-all duration-200 flex items-center justify-center cursor-pointer group my-1"
                             :class="isOver ? 'bg-[#214E34]/25 border-2 border-dashed border-[#214E34] py-6 rounded-xl shadow-inner scale-[1.01]' : 'h-3 hover:h-8 hover:bg-[#214E34]/15 rounded-lg border border-transparent hover:border-dashed hover:border-[#214E34]/40'">
                            <div class="hidden group-hover:flex items-center space-x-1.5 text-[11px] font-bold text-[#214E34]"
                                 :class="isOver ? '!flex text-white bg-[#214E34] px-4 py-1.5 rounded-full shadow-lg' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Drop Element / Block at Top of Page</span>
                            </div>
                        </div>
                    @endif

                    @forelse ($sections as $index => $section)
                        @php
                            $secId = $section['id'] ?? 'sec_' . $index;
                            $isSelected = $selectedSectionId === $secId;
                            $isActive = $section['is_active'] ?? true;
                            $settings = $section['settings'] ?? [];
                            $parsedHeadline = $parser->parse($settings['headline'] ?? '', $product);
                            $parsedSubheadline = $parser->parse($settings['subheadline'] ?? '', $product);
                            $parsedBadge = $parser->parse($settings['badge_text'] ?? '', $product);
                        @endphp

                        <!-- Section Wrapper with Direct Section Dropzone -->
                        <section wire:key="sec-{{ $secId }}"
                                 wire:click="selectSection('{{ $secId }}')"
                                 x-data="{ dropPos: null }"
                                 @dragover.prevent="
                                     event.dataTransfer.dropEffect = event.dataTransfer.types.includes('sorolata/reorder') ? 'move' : 'copy';
                                     const rect = $el.getBoundingClientRect();
                                     dropPos = (event.clientY < rect.top + (rect.height / 2)) ? 'top' : 'bottom';
                                 "
                                 @dragleave.prevent="
                                     if (!$el.contains(event.relatedTarget)) { dropPos = null; }
                                 "
                                 @drop.prevent="
                                     const reorderFrom = event.dataTransfer.getData('sorolata/reorder');
                                     const itemType = event.dataTransfer.getData('text/plain');
                                     const targetIndex = (dropPos === 'top') ? {{ $index }} : {{ $index + 1 }};
                                     dropPos = null;
                                     if (reorderFrom !== '') {
                                         $wire.reorderSections(parseInt(reorderFrom), targetIndex);
                                     } else if (itemType) {
                                         $wire.insertElementAt(itemType, targetIndex);
                                     }
                                 "
                                 class="relative group transition-all cursor-pointer {{ $isSelected ? 'ring-2 ring-[#214E34] z-10' : 'hover:outline hover:outline-1 hover:outline-[#214E34]/40' }} {{ ! $isActive ? 'opacity-40 grayscale' : '' }}"
                                 :class="{
                                     '!ring-4 !ring-[#214E34] !ring-offset-2 shadow-2xl': dropPos !== null,
                                 }"
                                 style="background-color: {{ $settings['bg_color'] ?? '#FFFFFF' }}; padding-top: {{ $settings['padding_top'] ?? '48px' }}; padding-bottom: {{ $settings['padding_bottom'] ?? '48px' }};">
                            
                            <!-- Visual Drop Indicator Above Section -->
                            <div x-show="dropPos === 'top'" 
                                 x-cloak
                                 class="absolute -top-3.5 left-0 right-0 z-30 flex items-center justify-center pointer-events-none transition-all">
                                <span class="bg-[#214E34] text-white text-[11px] font-bold px-4 py-1.5 rounded-full shadow-2xl flex items-center space-x-1.5 border border-white/20 animate-bounce">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>+ Drop Here: Insert Before Section #{{ $index + 1 }}</span>
                                </span>
                            </div>

                            <!-- Visual Drop Indicator Below Section -->
                            <div x-show="dropPos === 'bottom'" 
                                 x-cloak
                                 class="absolute -bottom-3.5 left-0 right-0 z-30 flex items-center justify-center pointer-events-none transition-all">
                                <span class="bg-[#214E34] text-white text-[11px] font-bold px-4 py-1.5 rounded-full shadow-2xl flex items-center space-x-1.5 border border-white/20 animate-bounce">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>+ Drop Here: Insert After Section #{{ $index + 1 }}</span>
                                </span>
                            </div>
                            
                            <!-- Floating Section Action Bar (Elementor / Framer style) -->
                            <div class="absolute -top-3.5 left-4 z-20 flex items-center space-x-1 opacity-0 group-hover:opacity-100 {{ $isSelected ? '!opacity-100' : '' }} transition-opacity">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $isSelected ? 'bg-[#214E34] text-white shadow' : 'bg-[#1a1d24] text-gray-200 border border-[#282d37]' }}">
                                    {{ str_replace('_', ' ', $section['type']) }} #{{ $index + 1 }}
                                </span>

                                <div class="bg-[#1a1d24] border border-[#282d37] rounded-full p-0.5 flex items-center space-x-0.5 shadow-lg">
                                    
                                    <!-- Drag Handle to Reorder -->
                                    <div draggable="true"
                                         @dragstart="event.dataTransfer.setData('sorolata/reorder', '{{ $index }}'); event.dataTransfer.effectAllowed = 'move';"
                                         class="p-1 text-gray-400 hover:text-white hover:bg-[#282d37] rounded-full transition-colors cursor-grab active:cursor-grabbing"
                                         title="Drag handle to reorder section">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                        </svg>
                                    </div>

                                    <!-- Move Up -->
                                    <button type="button" 
                                            wire:click.stop="moveSection('{{ $secId }}', 'up')"
                                            class="p-1 text-gray-400 hover:text-white hover:bg-[#282d37] rounded-full transition-colors"
                                            title="Move Section Up">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>

                                    <!-- Move Down -->
                                    <button type="button" 
                                            wire:click.stop="moveSection('{{ $secId }}', 'down')"
                                            class="p-1 text-gray-400 hover:text-white hover:bg-[#282d37] rounded-full transition-colors"
                                            title="Move Section Down">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <!-- Duplicate -->
                                    <button type="button" 
                                            wire:click.stop="duplicateSection('{{ $secId }}')"
                                            class="p-1 text-gray-400 hover:text-white hover:bg-[#282d37] rounded-full transition-colors"
                                            title="Duplicate Section">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>

                                    <!-- Toggle Visibility -->
                                    <button type="button" 
                                            wire:click.stop="toggleSection('{{ $secId }}')"
                                            class="p-1 text-gray-400 hover:text-white hover:bg-[#282d37] rounded-full transition-colors"
                                            title="{{ $isActive ? 'Hide Section' : 'Show Section' }}">
                                        @if ($isActive)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                            </svg>
                                        @endif
                                    </button>

                                    <!-- Delete -->
                                    <button type="button" 
                                            wire:click.stop="deleteSection('{{ $secId }}')"
                                            wire:confirm="Remove this section from the layout?"
                                            class="p-1 text-red-400 hover:text-red-300 hover:bg-[#282d37] rounded-full transition-colors"
                                            title="Delete Section">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- LIVE SECTION RENDER (DYNAMIC DATA PARSED & ELEMENTOR STYLED) -->
                            @php
                                $align = $settings['alignment'] ?? 'center';
                                $alignClass = match($align) {
                                    'left' => 'text-left items-start',
                                    'right' => 'text-right items-end',
                                    default => 'text-center items-center',
                                };
                                $justifyClass = match($align) {
                                    'left' => 'justify-start',
                                    'right' => 'justify-end',
                                    default => 'justify-center',
                                };
                                $textColor = $settings['text_color'] ?? '#1D1D1D';
                                $headlineSize = $settings['headline_size'] ?? 'text-4xl sm:text-5xl lg:text-6xl';
                                $imageUrl = $settings['image_url'] ?? null;
                                $videoUrl = $settings['video_url'] ?? null;
                                $videoData = !empty($videoUrl) ? $parser->parseVideoUrl($videoUrl) : null;
                                $containerWidth = ($settings['container_width'] ?? 'boxed') === 'full' ? 'w-full px-4' : 'max-w-6xl mx-auto px-6 lg:px-12';
                            @endphp

                            <div class="{{ $containerWidth }} pointer-events-none">
                                
                                @if ($section['type'] === 'hero_cinematic')
                                    <div class="py-6 lg:py-10 text-left">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                                            
                                            <!-- Left Editorial Text Block -->
                                            <div class="lg:col-span-5 space-y-5">
                                                <!-- Heritage Badge -->
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase bg-[#C96A3D]/10 text-[#C96A3D] border border-[#C96A3D]/25">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#C96A3D]"></span>
                                                    <span>{{ $parsedBadge ?: ($settings['badge_text'] ?? 'GI Certified Heritage Artifact') }}</span>
                                                </span>

                                                <!-- Product Title & Subtitle -->
                                                <h1 class="font-editorial-heading {{ $headlineSize }} font-light leading-[1.12]" style="color: {{ $textColor }};">
                                                    {{ $parsedHeadline ?: ($product?->getLocalized('title') ?? $product?->title ?? 'Bengali Heritage Masterpiece') }}
                                                </h1>

                                                @php
                                                    $displaySubtitle = $parsedSubheadline ?: ($product?->getLocalized('subtitle') ?? $product?->subtitle ?? '');
                                                @endphp
                                                @if(!empty($displaySubtitle))
                                                    <p class="font-editorial-body text-base text-[#555555] font-light leading-relaxed">
                                                        {{ $displaySubtitle }}
                                                    </p>
                                                @endif

                                                <!-- Provenance Summary -->
                                                <div class="pt-4 border-t border-[#E8E8E8] grid grid-cols-2 gap-4 text-xs text-[#555555]">
                                                    <div>
                                                        <span class="block uppercase tracking-wider text-[10px] text-[#888888]">Master Artisan</span>
                                                        <span class="font-medium text-[#1D1D1D]">{{ $product?->artisan?->getLocalized('full_name') ?? $product?->artisan?->full_name ?? 'Generational Guild' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block uppercase tracking-wider text-[10px] text-[#888888]">Origin & District</span>
                                                        <span class="font-medium text-[#1D1D1D]">{{ $product?->district?->getLocalized('name') ?? $product?->district?->name_en ?? 'Bengal Heritage Basin' }}</span>
                                                    </div>
                                                </div>

                                                <!-- Quick Action CTA -->
                                                <div class="pt-3 flex flex-wrap items-center gap-4">
                                                    <span class="px-6 py-3 rounded-full bg-[#214E34] text-white text-xs font-semibold tracking-wide shadow-sm">
                                                        {{ $settings['primary_cta_text'] ?? ($product ? 'Acquire Piece • ' . $product->formatted_price : 'Acquire Piece • ৳ 18,500') }}
                                                    </span>
                                                    <span class="text-xs uppercase tracking-widest text-[#555555] underline underline-offset-4 font-medium">
                                                        Read Atelier Story ↓
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Right Hero Photography Column -->
                                            <div class="lg:col-span-7">
                                                <div class="relative rounded-[32px] overflow-hidden bg-white shadow-soft-lg aspect-[4/3] lg:aspect-[16/11]">
                                                    @php
                                                        $primaryMedia = $product?->media?->first();
                                                        $heroDisplayImg = !empty($imageUrl) ? $imageUrl : ($primaryMedia ? url($primaryMedia->file_path) : 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop');
                                                    @endphp
                                                    @if ($videoData && $videoData['type'] !== 'none')
                                                        <div class="w-full h-full bg-black flex items-center justify-center">
                                                            @if ($videoData['type'] === 'mp4')
                                                                <video controls class="w-full h-full object-cover">
                                                                    <source src="{{ $videoData['embed_url'] }}" type="video/mp4">
                                                                </video>
                                                            @else
                                                                <iframe src="{{ $videoData['embed_url'] }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <img src="{{ $heroDisplayImg }}" alt="Hero Media" class="w-full h-full object-cover object-center">
                                                    @endif
                                                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[10px] text-[#1D1D1D] font-medium tracking-wide shadow-sm">
                                                        Original Heritage Photography
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'sticky_purchase_box')
                                    <div class="py-8">
                                        <div class="bg-white rounded-[32px] p-8 sm:p-12 border border-[#E8E8E8] shadow-soft-lg space-y-6 max-w-4xl mx-auto text-left">
                                            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between border-b border-[#E8E8E8] pb-6 gap-4">
                                                <div>
                                                    <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">
                                                        {{ $parsedBadge ?: ($settings['badge_text'] ?? 'Immediate Acquisition') }}
                                                    </span>
                                                    <h2 class="font-editorial-heading text-2xl sm:text-3xl text-[#1D1D1D] mt-1">
                                                        {{ $parsedHeadline ?: ($product?->getLocalized('title') ?? $product?->title ?? 'Product Acquisition') }}
                                                    </h2>
                                                    <p class="text-xs text-[#888888] mt-1">
                                                        {{ $parsedSubheadline ?: ($settings['subheadline'] ?? 'Single Batch Archive Edition • Hand-inspected by Chief Curator') }}
                                                    </p>
                                                </div>
                                                <div class="text-left sm:text-right">
                                                    <span class="font-editorial-heading text-3xl sm:text-4xl font-normal text-[#C96A3D]">
                                                        {{ $product ? $product->formatted_price : '৳ 18,500' }}
                                                    </span>
                                                    @if($product?->formatted_compare_price)
                                                        <span class="block text-xs line-through text-[#888888] mt-0.5">{{ $product->formatted_compare_price }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Delivery & Concierge Perks -->
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-[#555555]">
                                                <div class="flex items-start space-x-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-[#214E34]/10 flex items-center justify-center shrink-0 text-[#214E34] text-xs font-bold">✓</div>
                                                    <div>
                                                        <span class="font-semibold text-[#1D1D1D] block">Insured Courier</span>
                                                        <span class="text-[11px] text-[#777777]">Delivered in tamper-evident wooden or botanical box.</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-[#214E34]/10 flex items-center justify-center shrink-0 text-[#214E34] text-xs font-bold">✓</div>
                                                    <div>
                                                        <span class="font-semibold text-[#1D1D1D] block">Artisan Royalty</span>
                                                        <span class="text-[11px] text-[#777777]">Fair remuneration contractually deposited to artisan guild.</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-[#214E34]/10 flex items-center justify-center shrink-0 text-[#214E34] text-xs font-bold">✓</div>
                                                    <div>
                                                        <span class="font-semibold text-[#1D1D1D] block">Provenance Certificate</span>
                                                        <span class="text-[11px] text-[#777777]">Numbered certificate signed by craftsman & guild leader.</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Add to Collector Bag Form -->
                                            <div class="pt-2 flex flex-col sm:flex-row gap-4 items-center">
                                                <div class="flex items-center border border-[#E8E8E8] rounded-full px-4 py-2.5 bg-[#F8F6F2] space-x-4 text-xs">
                                                    <span class="text-gray-400 font-bold">-</span>
                                                    <span class="font-bold text-[#1D1D1D]">১</span>
                                                    <span class="text-gray-400 font-bold">+</span>
                                                </div>
                                                <button type="button" class="w-full flex-1 py-3.5 px-6 rounded-full bg-[#214E34] text-white text-xs font-bold tracking-wider uppercase shadow-sm flex items-center justify-center space-x-2">
                                                    <span>{{ $settings['primary_cta_text'] ?? 'Acquire Piece & Add To Collector Bag' }}</span>
                                                    <span>→</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'artisan_spotlight')
                                    <div class="py-8 text-left">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                                            <!-- Artisan Portrait -->
                                            <div class="lg:col-span-5 order-2 lg:order-1">
                                                <div class="relative rounded-[32px] overflow-hidden bg-white aspect-[4/5] shadow-soft-lg">
                                                    @php
                                                        $portraitImg = !empty($imageUrl) ? $imageUrl : ($product?->artisan?->portrait_url ?? 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=800');
                                                    @endphp
                                                    <img src="{{ $portraitImg }}" alt="Artisan" class="w-full h-full object-cover grayscale contrast-125">
                                                    <div class="absolute bottom-5 left-5 right-5 bg-[#1D1D1D]/80 backdrop-blur-md p-4 rounded-[20px] text-white">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <span class="text-[9px] uppercase tracking-widest text-[#AAAAAA]">Atelier</span>
                                                                <h4 class="font-editorial-heading text-base font-light">{{ $product?->artisan?->atelier_name ?? 'Dewan Royal Loom' }}</h4>
                                                            </div>
                                                            <span class="text-[11px] px-2.5 py-0.5 bg-[#214E34] text-white rounded-full font-medium">
                                                                {{ $product?->artisan?->years_of_mastery ?? 35 }} Years Mastery
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Biographical Narrative -->
                                            <div class="lg:col-span-7 order-1 lg:order-2 space-y-4">
                                                <div class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-[#C96A3D] font-bold">
                                                    <span class="w-2 h-2 rounded-full bg-[#C96A3D]"></span>
                                                    <span>{{ $parsedBadge ?: ($settings['badge_text'] ?? 'The Master Artisan Behind This Piece') }}</span>
                                                </div>

                                                <h2 class="font-editorial-heading {{ $headlineSize }} font-light leading-tight" style="color: {{ $textColor }};">
                                                    {{ $parsedHeadline ?: ($product?->artisan?->getLocalized('full_name') ?? $product?->artisan?->full_name ?? 'Ustad Alimullah Dewan') }}
                                                </h2>

                                                <p class="text-xs uppercase tracking-widest text-[#888888]">
                                                    Specialty: {{ $product?->artisan?->craft_type?->title() ?? 'Heritage Handloom Weaving' }} • {{ $product?->district?->getLocalized('name') ?? $product?->district?->name_en ?? 'Tangail' }}, Bangladesh
                                                </p>

                                                @php
                                                    $artisanQuote = $parsedSubheadline ?: ($product?->artisan?->getLocalized('quote') ?? $product?->artisan?->quote ?? '');
                                                @endphp
                                                @if(!empty($artisanQuote))
                                                    <blockquote class="pl-5 border-l-2 border-[#C96A3D] italic font-editorial-heading text-lg sm:text-xl text-[#1D1D1D] my-4 leading-relaxed">
                                                        "{{ $artisanQuote }}"
                                                    </blockquote>
                                                @endif

                                                <p class="text-[#555555] text-xs sm:text-sm leading-relaxed font-editorial-body">
                                                    {{ $product?->artisan?->getLocalized('biography') ?? $product?->artisan?->biography ?? 'Carrying forward an uninterrupted lineage of artisan mastery. Every piece represents weeks of patient handcrafting using indigenous techniques passed down through generations of guild masters.' }}
                                                </p>

                                                <!-- Provenance & Fair Trade -->
                                                <div class="pt-4 grid grid-cols-3 gap-4 border-t border-[#E8E8E8] text-xs">
                                                    <div>
                                                        <span class="block text-[#888888] uppercase tracking-wider text-[9px]">Artisan Royalty</span>
                                                        <span class="font-medium text-[#1D1D1D] text-[11px]">Direct Compensation</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-[#888888] uppercase tracking-wider text-[9px]">Verification</span>
                                                        <span class="font-medium text-[#2D6A4F] text-[11px]">Verified Guild ✓</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-[#888888] uppercase tracking-wider text-[9px]">Origin Tag</span>
                                                        <span class="font-medium text-[#1D1D1D] text-[11px]">100% Traceable</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'interactive_gallery')
                                    <div class="py-10 text-left">
                                        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
                                            <div>
                                                <span class="text-xs uppercase tracking-widest text-[#9E4732] font-semibold">
                                                    {{ $parsedBadge ?: ($settings['badge_text'] ?? 'Macro Visual Study') }}
                                                </span>
                                                <h2 class="font-serif-luxury text-2xl sm:text-3xl lg:text-4xl font-light text-[#241F1E] mt-1" style="color: {{ $textColor }};">
                                                    {{ $parsedHeadline ?: ($settings['headline'] ?? 'Tactile Intricacies') }}
                                                </h2>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#786C65] max-w-sm mt-2 md:mt-0 font-serif-luxury italic">
                                                {{ $parsedSubheadline ?: ($settings['subheadline'] ?? 'Notice the deliberate irregularities that prove human hand execution over automated mechanics.') }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @php
                                                $gallerySample = [
                                                    'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=1000&auto=format&fit=crop',
                                                    'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop',
                                                    'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=1000&auto=format&fit=crop',
                                                ];
                                                if (!empty($imageUrl)) {
                                                    $gallerySample[0] = $imageUrl;
                                                }
                                            @endphp
                                            @foreach($gallerySample as $gIndex => $gImg)
                                                <div class="group relative rounded-3xl overflow-hidden bg-[#E2DBD3] aspect-[3/4] shadow-sm">
                                                    <img src="{{ $gImg }}" alt="Gallery Image {{ $gIndex + 1 }}" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-4">
                                                        <span class="text-white text-xs font-medium tracking-wide">Macro Detail {{ sprintf('%02d', $gIndex + 1) }} • Hand Loom Tension</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'heritage_provenance')
                                    <div class="py-10 text-left">
                                        <div class="max-w-3xl mb-8">
                                            <div class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-[#214E34] font-bold mb-2">
                                                <span class="w-2 h-2 rounded-full bg-[#214E34]"></span>
                                                <span>{{ $parsedBadge ?: ($settings['badge_text'] ?? 'Geographical Indication & Regional Provenance') }}</span>
                                            </div>
                                            <h2 class="font-editorial-heading text-2xl sm:text-3xl lg:text-4xl font-light text-[#1D1D1D]" style="color: {{ $textColor }};">
                                                {{ $parsedHeadline ?: ('Rooted in ' . ($product?->district?->name_en ?? 'Bengal') . ' (' . ($product?->district?->name_bn ?? 'বাংলাদেশ') . ')') }}
                                            </h2>
                                            <p class="font-editorial-body text-xs sm:text-sm text-[#555555] mt-2 leading-relaxed">
                                                {{ $parsedSubheadline ?: 'Soil, microclimate, and river humidity shape the personality of every craft in Bangladesh. Learn why this exact district is the irreplaceable cradle of this tradition.' }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Card 1: GI Specialty -->
                                            <div class="p-6 rounded-[28px] bg-[#F8F6F2] border border-[#E8E8E8] space-y-3 shadow-soft-xs text-left">
                                                <div class="w-9 h-9 rounded-full bg-[#214E34]/10 flex items-center justify-center text-[#214E34] font-bold text-sm">
                                                    ✓
                                                </div>
                                                <h3 class="font-editorial-heading text-lg font-light text-[#1D1D1D]">GI Heritage Specialty</h3>
                                                <p class="text-xs text-[#555555] leading-relaxed">
                                                    {{ $product?->district?->gi_specialty ?? 'World-renowned handloom textile weaving registered under national geographical indication.' }}
                                                </p>
                                            </div>

                                            <!-- Card 2: River Basin Climate -->
                                            <div class="p-6 rounded-[28px] bg-[#F8F6F2] border border-[#E8E8E8] space-y-3 shadow-soft-xs text-left">
                                                <div class="w-9 h-9 rounded-full bg-[#C96A3D]/10 flex items-center justify-center text-[#C96A3D] font-bold text-sm">
                                                    ✦
                                                </div>
                                                <h3 class="font-editorial-heading text-lg font-light text-[#1D1D1D]">River Basin & Microclimate</h3>
                                                <p class="text-xs text-[#555555] leading-relaxed">
                                                    {{ $product?->district?->river_basin ?? 'Shitalakshya & Jamuna River Basin' }} — the ambient river air provides the exact morning humidity essential for ultra-fine thread tension.
                                                </p>
                                            </div>

                                            <!-- Card 3: Historical Chronicle -->
                                            <div class="p-6 rounded-[28px] bg-[#F8F6F2] border border-[#E8E8E8] space-y-3 shadow-soft-xs text-left">
                                                <div class="w-9 h-9 rounded-full bg-[#214E34]/10 flex items-center justify-center text-[#214E34] font-bold text-sm">
                                                    📍
                                                </div>
                                                <h3 class="font-editorial-heading text-lg font-light text-[#1D1D1D]">Historical Chronicle</h3>
                                                <p class="text-xs text-[#555555] leading-relaxed">
                                                    {{ $product?->district?->historical_narrative ?? 'Documented in traveler chronicles since the 14th century, cultivated by imperial patronage.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'specs_anatomy')
                                    <div class="py-10">
                                        <div class="text-center max-w-2xl mx-auto mb-10">
                                            <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold block mb-1">
                                                {{ $parsedBadge ?: ($settings['badge_text'] ?? 'Material Formulation') }}
                                            </span>
                                            <h2 class="font-editorial-heading text-2xl sm:text-3xl lg:text-4xl font-light text-[#1D1D1D]" style="color: {{ $textColor }};">
                                                {{ $parsedHeadline ?: ($settings['headline'] ?? 'Pure Formulation & Metrics') }}
                                            </h2>
                                            <p class="text-xs sm:text-sm text-[#555555] mt-2">
                                                {{ $parsedSubheadline ?: ($settings['subheadline'] ?? 'Transparent composition details. No synthetic polymers. No chemical bleaches.') }}
                                            </p>
                                        </div>

                                        <div class="bg-white rounded-[32px] p-6 sm:p-10 border border-[#E8E8E8] shadow-soft-md divide-y divide-[#E8E8E8] max-w-3xl mx-auto text-left">
                                            @php
                                                $specsData = [
                                                    'Fiber / Material' => '100% Hand-Ginned Indigenous Cotton / Mulberry Silk',
                                                    'Weave Density' => '120 × 120 thread count pit-loom configuration',
                                                    'Dye Formulation' => 'Botanical Madder Root, Pomegranate Rind, Indigo Fermentation',
                                                    'Crafting Duration' => '180 hours of handloom dedication per single piece',
                                                    'Dimensions' => '220 cm × 75 cm (Generous Archival Drape)',
                                                    'Care Instructions' => 'Delicate cold hand rinse with natural soapnut, shade dry',
                                                ];
                                                if (!empty($product?->heritage_specs)) {
                                                    $specsData = array_merge($specsData, $product->heritage_specs);
                                                }
                                            @endphp
                                            @foreach($specsData as $sLabel => $sValue)
                                                <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between text-xs sm:text-sm gap-2">
                                                    <span class="font-semibold text-[#1D1D1D] uppercase tracking-wider text-xs sm:w-1/3">{{ $sLabel }}</span>
                                                    <span class="text-[#555555] font-light sm:w-2/3">{{ $sValue }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'testimonial_editorial')
                                    <div class="py-10 max-w-3xl mx-auto text-center space-y-4">
                                        <span class="text-xs uppercase tracking-widest text-[#9E4732] font-semibold block">
                                            {{ $parsedBadge ?: ($settings['badge_text'] ?? 'Collector Impressions') }}
                                        </span>

                                        <blockquote class="font-serif-luxury text-xl sm:text-2xl lg:text-3xl italic text-[#241F1E] font-light leading-snug">
                                            "{{ $parsedSubheadline ?: ($settings['impression'] ?? 'Unboxing this piece in London was a transcendent experience. The tactile weight, the aroma of natural river washed cotton, and the signed provenance letter from Master Weaver Alimuddin makes it the most prized textile in my entire collection.') }}"
                                        </blockquote>

                                        <div class="pt-2">
                                            <span class="block font-medium text-sm text-[#241F1E]">
                                                {{ $parsedHeadline ?: ($settings['collector_name'] ?? 'Dr. Eleanor Vance') }}
                                            </span>
                                            <span class="block text-xs text-[#8C837D] mt-0.5">
                                                {{ $settings['collector_location'] ?? 'Textile Historian & Collector • Kensington, London' }}
                                            </span>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'element_heading')
                                    <div class="flex flex-col {{ $alignClass }} py-2">
                                        <h2 class="font-editorial-heading {{ $headlineSize }} tracking-tight font-bold" style="color: {{ $textColor }};">
                                            {{ $parsedHeadline ?: 'New Editorial Heading' }}
                                        </h2>
                                    </div>

                                @elseif ($section['type'] === 'element_paragraph')
                                    <div class="flex flex-col {{ $alignClass }} py-2">
                                        <div class="prose max-w-3xl text-sm sm:text-base leading-relaxed text-[#555555]" style="color: {{ $textColor }};">
                                            <p>{{ $parsedSubheadline ?: 'Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship across historical Bengal.' }}</p>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'element_image')
                                    <div class="flex flex-col {{ $alignClass }} py-2">
                                        @if (!empty($imageUrl))
                                            <div class="w-full max-w-2xl rounded-2xl overflow-hidden shadow-md border border-stone-200">
                                                <img src="{{ $imageUrl }}" alt="Uploaded Image" class="w-full h-auto object-cover">
                                            </div>
                                        @else
                                            <div class="w-full max-w-xl p-8 rounded-2xl border-2 border-dashed border-[#282d37] bg-stone-50 flex flex-col items-center justify-center text-center">
                                                <svg class="w-10 h-10 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs font-semibold text-gray-700">Image Element (ছবি আপলোড করা হয়নি)</span>
                                                <span class="text-[11px] text-gray-500 mt-1">Select this block & upload an image from the right Inspector panel.</span>
                                            </div>
                                        @endif
                                    </div>

                                @elseif ($section['type'] === 'element_video')
                                    <div class="flex flex-col {{ $alignClass }} py-2">
                                        @if ($videoData && $videoData['type'] !== 'none')
                                            <div class="w-full max-w-3xl rounded-2xl overflow-hidden shadow-xl aspect-video bg-black">
                                                @if ($videoData['type'] === 'mp4')
                                                    <video controls class="w-full h-full object-cover">
                                                        <source src="{{ $videoData['embed_url'] }}" type="video/mp4">
                                                    </video>
                                                @else
                                                    <iframe src="{{ $videoData['embed_url'] }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                                @endif
                                            </div>
                                        @else
                                            <div class="w-full max-w-xl p-6 rounded-2xl border-2 border-dashed border-gray-300 bg-stone-50 text-center">
                                                <span class="text-xs font-semibold text-gray-700">Video Player (Paste YouTube or MP4 URL in Inspector)</span>
                                            </div>
                                        @endif
                                    </div>

                                @elseif ($section['type'] === 'element_button')
                                    <div class="flex items-center {{ $justifyClass }} py-2">
                                        <span class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm"
                                              style="background-color: {{ $settings['bg_color'] ?? '#214E34' }}; color: {{ $textColor === '#1D1D1D' ? '#FFFFFF' : $textColor }};">
                                            {{ $settings['primary_cta_text'] ?? 'Explore Artifacts' }}
                                        </span>
                                    </div>

                                @elseif ($section['type'] === 'element_product_card')
                                    <div class="flex flex-col {{ $alignClass }} py-2">
                                        <div class="p-5 rounded-2xl bg-white border border-stone-200 shadow-sm max-w-xs text-center">
                                            @if (!empty($imageUrl))
                                                <div class="w-full h-48 rounded-xl overflow-hidden mb-3">
                                                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-full h-48 rounded-xl bg-stone-100 flex items-center justify-center text-stone-400 mb-3 text-xs">
                                                    Craft Artifact Preview
                                                </div>
                                            @endif
                                            <span class="text-[10px] uppercase font-bold tracking-widest text-[#214E34] block mb-1">
                                                {{ $parsedBadge ?: 'GI Certified Heritage' }}
                                            </span>
                                            <h4 class="font-editorial-heading text-lg font-bold text-gray-900 mb-1">
                                                {{ $parsedHeadline ?: 'Handcrafted Jamdani Piece' }}
                                            </h4>
                                            <div class="text-sm font-bold text-[#C96A3D] mb-3">
                                                {{ $settings['price'] ?? '৳ 18,500' }}
                                            </div>
                                            <button class="w-full py-2.5 rounded-full bg-[#214E34] text-white text-xs font-semibold tracking-wide">
                                                Add to Archival Bag
                                            </button>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'element_artisan_quote')
                                    <div class="max-w-2xl mx-auto p-6 rounded-2xl bg-[#F8F6F2] border-l-4 border-[#214E34] shadow-sm my-2">
                                        <p class="font-editorial-heading text-xl italic text-gray-800 mb-3">
                                            "{{ $parsedSubheadline ?: 'Our hands carry the memory of three centuries of loom heritage on the Shitalakshya.' }}"
                                        </p>
                                        <div class="flex items-center space-x-2 text-xs">
                                            <span class="font-bold text-gray-900">— {{ $parsedHeadline ?: 'Ustad Alimullah Dewan' }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-[#214E34] font-medium">{{ $parsedBadge ?: 'Royal Master Loom Guild' }}</span>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'element_divider')
                                    <div class="w-full max-w-xl mx-auto my-3 flex items-center justify-center space-x-4">
                                        <div class="h-[1px] flex-1 bg-stone-300"></div>
                                        <span class="text-[#D4AF37] text-sm">❖</span>
                                        <div class="h-[1px] flex-1 bg-stone-300"></div>
                                    </div>

                                @elseif ($section['type'] === 'magazine_hero_grid')
                                    <div class="py-4 max-w-6xl mx-auto w-full">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                                            <!-- Main Featured Story Card -->
                                            <div class="lg:col-span-8 relative rounded-2xl overflow-hidden shadow-lg aspect-[16/10] bg-stone-900 flex flex-col justify-end p-6">
                                                <img src="{{ $imageUrl ?: 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1200' }}" alt="Magazine Hero" class="absolute inset-0 w-full h-full object-cover opacity-85">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/45 to-transparent"></div>
                                                <div class="relative z-10 space-y-2">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold tracking-widest uppercase bg-[#C96A3D] text-white">
                                                            {{ $parsedBadge ?: 'HERITAGE DISCOVERY' }}
                                                        </span>
                                                        <span class="text-white/80 text-[10px]">5 min read</span>
                                                    </div>
                                                    <h2 class="font-editorial-heading text-xl sm:text-2xl text-white font-bold leading-tight">
                                                        {{ $parsedHeadline ?: 'Are you a Green or Red Flag? Have You Ever Discovered Your True Craft Soul Amidst Heritage?' }}
                                                    </h2>
                                                    <p class="text-xs text-gray-200 line-clamp-2">
                                                        {{ $parsedSubheadline ?: 'Traveling across ancestral river basins of Bengal where master weavers preserve living stories.' }}
                                                    </p>
                                                    <div class="pt-2 flex items-center justify-between text-xs text-white border-t border-white/20">
                                                        <span>By Curator Raisa Alom</span>
                                                        <span class="px-3 py-1 rounded-full bg-white/20 text-[11px] font-semibold">Read Story →</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Stacked Cards -->
                                            <div class="lg:col-span-4 flex flex-col gap-4">
                                                <div class="relative rounded-2xl overflow-hidden shadow-md flex-1 min-h-[160px] bg-stone-900 flex flex-col justify-end p-4">
                                                    <img src="https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600" class="absolute inset-0 w-full h-full object-cover opacity-80">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                                    <div class="relative z-10 space-y-1">
                                                        <span class="text-[9px] font-bold uppercase text-[#A3D9B1]">LOOM TRADITIONS</span>
                                                        <h3 class="font-editorial-heading text-sm text-white font-bold leading-snug">The Secret 300-Count Thread: Sonargaon Muslin</h3>
                                                    </div>
                                                </div>
                                                <div class="relative rounded-2xl overflow-hidden shadow-md flex-1 min-h-[160px] bg-stone-900 flex flex-col justify-end p-4">
                                                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600" class="absolute inset-0 w-full h-full object-cover opacity-80">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                                    <div class="relative z-10 space-y-1">
                                                        <span class="text-[9px] font-bold uppercase text-amber-300">ANCIENT BRASS</span>
                                                        <h3 class="font-editorial-heading text-sm text-white font-bold leading-snug">Lost-Wax Bronze of Dhamrai: Molten Liquid Fire</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'topic_bubbles')
                                    <div class="py-4 max-w-6xl mx-auto w-full">
                                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-stone-200">
                                             <div>
                                                 <span class="text-[10px] font-bold uppercase tracking-widest text-[#214E34]">TOPIC ARCHIVES</span>
                                                 <h4 class="font-editorial-heading text-lg font-bold text-gray-900">{{ $parsedHeadline ?: 'Explore Stories by Heritage Craft & Guild' }}</h4>
                                             </div>
                                             <span class="text-xs text-[#214E34] font-semibold">View All Topics →</span>
                                        </div>
                                        <div class="flex items-center space-x-6 overflow-x-auto pb-2">
                                             @foreach ([
                                                 ['name' => 'Jamdani Weave', 'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=200'],
                                                 ['name' => 'Nakshi Kantha', 'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=200'],
                                                 ['name' => 'Dhamrai Brass', 'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=200'],
                                                 ['name' => 'River Clay', 'img' => 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?q=80&w=200'],
                                                 ['name' => 'Tangail Silk', 'img' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=200'],
                                                 ['name' => 'Master Guilds', 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200'],
                                             ] as $bubble)
                                                 <div class="flex flex-col items-center text-center shrink-0">
                                                     <div class="w-16 h-16 rounded-full p-0.5 border-2 border-dashed border-[#214E34]/40 bg-white shadow-sm mb-1.5 overflow-hidden">
                                                         <img src="{{ $bubble['img'] }}" class="w-full h-full object-cover rounded-full">
                                                     </div>
                                                     <span class="text-[11px] font-bold text-gray-800">{{ $bubble['name'] }}</span>
                                                 </div>
                                             @endforeach
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'editorial_feed_sidebar')
                                    <div class="py-6 max-w-6xl mx-auto w-full">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                                             <div class="lg:col-span-8 space-y-4">
                                                 <div class="flex items-center justify-between pb-2 border-b border-stone-200">
                                                     <h4 class="font-editorial-heading text-xl font-bold text-gray-900">{{ $parsedHeadline ?: 'Heritage Gazette Stories & In-Depth Chronicles' }}</h4>
                                                     <span class="text-xs text-gray-400">Curated Journal</span>
                                                 </div>
                                                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                     @foreach ([
                                                         ['title' => 'The Sacred Geometry of Jamdani Motifs', 'badge' => 'WEAVING', 'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=400'],
                                                         ['title' => 'Resurrecting Phuti Karpas: Gossamer Muslin', 'badge' => 'BOTANY', 'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=400'],
                                                     ] as $card)
                                                         <div class="rounded-2xl bg-white border border-stone-200 overflow-hidden shadow-sm">
                                                             <div class="h-36 bg-stone-100 relative">
                                                                 <img src="{{ $card['img'] }}" class="w-full h-full object-cover">
                                                                 <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[9px] font-bold bg-[#214E34] text-white">{{ $card['badge'] }}</span>
                                                             </div>
                                                             <div class="p-3">
                                                                 <h5 class="font-editorial-heading text-sm font-bold text-gray-900 leading-tight">{{ $card['title'] }}</h5>
                                                             </div>
                                                         </div>
                                                     @endforeach
                                                 </div>
                                             </div>
                                             <div class="lg:col-span-4 space-y-4">
                                                 <div class="rounded-2xl bg-white border border-stone-200 p-4 shadow-sm">
                                                     <h5 class="font-bold text-xs uppercase text-gray-800 mb-2 pb-1 border-b">Trending Topics</h5>
                                                     <div class="space-y-1.5 text-xs text-gray-600">
                                                         <div class="flex justify-between"><span>Handloom Muslin</span><span class="font-bold text-emerald-800">24</span></div>
                                                         <div class="flex justify-between"><span>Dhamrai Brass</span><span class="font-bold text-emerald-800">18</span></div>
                                                         <div class="flex justify-between"><span>Tangail Tant</span><span class="font-bold text-emerald-800">15</span></div>
                                                     </div>
                                                 </div>
                                             </div>
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'shoppable_product_shelf')
                                    <div class="py-6 max-w-6xl mx-auto w-full">
                                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-stone-200">
                                             <div>
                                                 <span class="text-[10px] font-bold uppercase tracking-widest text-[#C96A3D]">AS FEATURED IN STORIES</span>
                                                 <h4 class="font-editorial-heading text-xl font-bold text-gray-900">{{ $parsedHeadline ?: 'From Story to Hand: Acquire the Pieces' }}</h4>
                                             </div>
                                             <span class="text-xs text-[#214E34] font-semibold">Browse Archive →</span>
                                        </div>
                                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                             @foreach ([
                                                 ['title' => 'Imperial Sonargaon Muslin', 'price' => '৳ 18,500', 'badge' => 'Sonargaon', 'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=400'],
                                                 ['title' => 'Tangail Mulberry Silk Jamdani', 'price' => '৳ 24,500', 'badge' => 'Tangail', 'img' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=400'],
                                                 ['title' => 'Royal Bogura Brass Kettle', 'price' => '৳ 12,800', 'badge' => 'Bogura', 'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=400'],
                                                 ['title' => 'Rangpur Royal Shatranji', 'price' => '৳ 15,600', 'badge' => 'Rangpur', 'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=400'],
                                             ] as $p)
                                                 <div class="rounded-2xl bg-white border border-stone-200 p-3 shadow-sm flex flex-col justify-between">
                                                     <div class="h-40 rounded-xl overflow-hidden bg-stone-100 mb-2 relative">
                                                         <img src="{{ $p['img'] }}" class="w-full h-full object-cover">
                                                         <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded text-[8px] font-bold bg-white text-[#214E34]">{{ $p['badge'] }}</span>
                                                     </div>
                                                     <div class="font-bold text-xs text-gray-900 truncate">{{ $p['title'] }}</div>
                                                     <div class="text-xs font-bold text-[#C96A3D] my-1">{{ $p['price'] }}</div>
                                                     <button class="w-full py-1.5 rounded-full bg-[#214E34] text-white text-[10px] font-semibold">Acquire Piece</button>
                                                 </div>
                                             @endforeach
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'video_reels_showcase')
                                    <div class="py-6 max-w-6xl mx-auto w-full">
                                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-stone-200">
                                             <h4 class="font-editorial-heading text-xl font-bold text-gray-900">{{ $parsedHeadline ?: 'Living Chronicles: Watch the Hands Behind the Craft' }}</h4>
                                             <span class="text-xs text-gray-400">Video Reels</span>
                                        </div>
                                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                             @foreach ([
                                                 ['title' => 'Dewan Loom Shuttle', 'time' => '2:45', 'img' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=400'],
                                                 ['title' => 'Bell Metal at 1100°C', 'time' => '3:20', 'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=400'],
                                                 ['title' => 'Indigo Fermentation', 'time' => '1:50', 'img' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=400'],
                                                 ['title' => 'Needlework Nakshi', 'time' => '4:10', 'img' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=400'],
                                             ] as $r)
                                                 <div class="relative rounded-2xl overflow-hidden aspect-[9/13] bg-stone-900 flex flex-col justify-end p-3 shadow-sm">
                                                     <img src="{{ $r['img'] }}" class="absolute inset-0 w-full h-full object-cover opacity-80">
                                                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                                                     <div class="relative z-10 text-white">
                                                         <div class="text-[9px] text-[#A3D9B1]">{{ $r['time'] }}</div>
                                                         <div class="font-bold text-xs truncate">{{ $r['title'] }}</div>
                                                     </div>
                                                 </div>
                                             @endforeach
                                        </div>
                                    </div>

                                @elseif ($section['type'] === 'heritage_club_newsletter')
                                    <div class="py-6 max-w-5xl mx-auto w-full">
                                        <div class="rounded-2xl bg-[#214E34] text-white p-6 sm:p-8 text-center shadow-lg">
                                             <span class="text-[10px] font-bold uppercase tracking-widest text-[#A3D9B1]">SOROLATA HERITAGE GUILD</span>
                                             <h3 class="font-editorial-heading text-2xl font-bold mt-1">{{ $parsedHeadline ?: 'Join the Heritage Gazette & Collector Circle' }}</h3>
                                             <p class="text-xs text-white/80 max-w-xl mx-auto mt-2 mb-4">{{ $parsedSubheadline ?: 'Receive our monthly investigative craft essays, private notifications for limited loom harvests, and invitations to exhibitions.' }}</p>
                                             <div class="max-w-md mx-auto flex gap-2">
                                                 <input type="email" placeholder="Enter your email address..." class="flex-1 px-4 py-2 rounded-full bg-white/10 border border-white/25 text-xs text-white placeholder-white/60">
                                                 <button class="px-5 py-2 rounded-full bg-[#C96A3D] text-white text-xs font-bold uppercase">Subscribe</button>
                                             </div>
                                        </div>
                                    </div>

                                @else
                                    <!-- Dynamic Flexible Block Renderer (Elementor Style) -->
                                    <div class="py-6 flex flex-col {{ $alignClass }}">
                                        @if ($parsedBadge)
                                            <span class="text-xs font-semibold uppercase tracking-widest text-[#214E34] block mb-2">
                                                {{ $parsedBadge }}
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold uppercase tracking-widest text-[#214E34] block mb-2">
                                                {{ str_replace('_', ' ', $section['type']) }}
                                            </span>
                                        @endif
                                        <h3 class="font-editorial-heading {{ $headlineSize }} mb-3" style="color: {{ $textColor }};">
                                            {{ $parsedHeadline ?: 'Heritage Section Content' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 max-w-2xl leading-relaxed mb-6">
                                            {{ $parsedSubheadline ?: 'Visual storytelling block rendered via Sorolata JSON Layout Engine.' }}
                                        </p>

                                        <!-- Optional Video or Image in any block -->
                                        @if ($videoData && $videoData['type'] !== 'none')
                                            <div class="w-full max-w-3xl my-4 rounded-2xl overflow-hidden shadow-xl aspect-video bg-black">
                                                <iframe src="{{ $videoData['embed_url'] }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                            </div>
                                        @elseif (!empty($imageUrl))
                                            <div class="w-full max-w-3xl my-4 rounded-2xl overflow-hidden shadow-md aspect-[16/9] bg-stone-100">
                                                <img src="{{ $imageUrl }}" alt="Section Media" class="w-full h-full object-cover">
                                            </div>
                                        @endif

                                        @if (!empty($settings['primary_cta_text']))
                                            <div class="flex items-center {{ $justifyClass }} space-x-3 mt-4">
                                                <span class="px-6 py-2.5 rounded-full bg-[#214E34] text-white text-xs font-semibold">
                                                    {{ $settings['primary_cta_text'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Embedded Child Elements in this Section -->
                                @if(!empty($section['elements']))
                                    <div class="mt-6 pt-4 border-t border-dashed border-stone-300 space-y-3 pointer-events-auto">
                                        @foreach($section['elements'] as $elIdx => $childEl)
                                            @php
                                                $childType = $childEl['type'] ?? 'element_heading';
                                                $childSettings = $childEl['settings'] ?? [];
                                            @endphp
                                            <div wire:key="child-{{ $childEl['id'] }}"
                                                 class="relative group/child p-3 rounded-xl border border-stone-200 hover:border-[#214E34] bg-white/80 transition-all text-left">
                                                <div class="absolute -top-2.5 right-2 z-20 hidden group-hover/child:flex items-center space-x-1">
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-[#1a1d24] text-[#A3D9B1]">
                                                        {{ str_replace('element_', '', $childType) }}
                                                    </span>
                                                    <button type="button" 
                                                            wire:click.stop="removeElementFromSection('{{ $secId }}', {{ $elIdx }})"
                                                            class="p-1 rounded bg-red-600 hover:bg-red-700 text-white text-[10px]"
                                                            title="Delete element">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>

                                                @if ($childType === 'element_heading')
                                                    <h3 class="font-editorial-heading {{ $childSettings['headline_size'] ?? 'text-2xl' }} font-bold text-[#1D1D1D] text-{{ $childSettings['alignment'] ?? 'center' }}">
                                                        {{ $childSettings['headline'] ?? 'New Heading' }}
                                                    </h3>
                                                @elseif ($childType === 'element_paragraph')
                                                    <p class="text-sm text-[#555555] leading-relaxed text-{{ $childSettings['alignment'] ?? 'left' }}">
                                                        {{ $childSettings['subheadline'] ?? 'Storytelling and craft description narrative text.' }}
                                                    </p>
                                                @elseif ($childType === 'element_image' && !empty($childSettings['image_url']))
                                                    <div class="flex justify-{{ $childSettings['alignment'] ?? 'center' }}">
                                                        <img src="{{ $childSettings['image_url'] }}" class="max-w-md h-auto rounded-xl shadow-sm">
                                                    </div>
                                                @elseif ($childType === 'element_video' && !empty($childSettings['video_url']))
                                                    <div class="max-w-2xl mx-auto aspect-video rounded-xl overflow-hidden bg-black">
                                                        <iframe src="{{ $childSettings['video_url'] }}" class="w-full h-full"></iframe>
                                                    </div>
                                                @elseif ($childType === 'element_button')
                                                    <div class="flex justify-{{ $childSettings['alignment'] ?? 'center' }}">
                                                        <span class="px-6 py-2.5 rounded-full bg-[#214E34] text-white text-xs font-semibold">
                                                            {{ $childSettings['primary_cta_text'] ?? 'Explore Now' }}
                                                        </span>
                                                    </div>
                                                @elseif ($childType === 'element_artisan_quote')
                                                    <div class="p-4 rounded-xl bg-[#F8F6F2] border-l-4 border-[#214E34]">
                                                        <p class="italic font-editorial-heading text-base text-gray-800">"{{ $childSettings['subheadline'] ?? '' }}"</p>
                                                        <span class="text-xs font-bold text-gray-900 mt-1 block">— {{ $childSettings['headline'] ?? '' }}</span>
                                                    </div>
                                                @elseif ($childType === 'element_divider')
                                                    <div class="my-2 border-t border-stone-200"></div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Section Inner Dropzone for Elements -->
                                <div x-data="{ isInnerDrop: false }"
                                     @dragover.prevent="isInnerDrop = true; event.dataTransfer.dropEffect = 'copy'"
                                     @dragleave.prevent="isInnerDrop = false"
                                     @drop.prevent="
                                         isInnerDrop = false;
                                         const itemType = event.dataTransfer.getData('text/plain');
                                         if (itemType) {
                                             $wire.addElementToSection('{{ $secId }}', itemType);
                                         }
                                     "
                                     class="w-full mt-4 py-2 px-3 rounded-xl border border-dashed transition-all text-center flex items-center justify-center space-x-1.5 text-xs pointer-events-auto cursor-pointer"
                                     :class="isInnerDrop ? 'border-[#214E34] bg-[#214E34]/20 text-[#214E34] font-bold py-3.5 shadow-md scale-[1.01]' : 'border-stone-300 text-stone-400 hover:border-[#214E34] hover:text-[#214E34] hover:bg-[#214E34]/5'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Drag & drop elements from left to fit inside this section</span>
                                </div>

                            </div>
                        </section>

                        <!-- Elementor Inter-Section Dropzone (Between Sections at Index $index + 1) -->
                        <div x-data="{ isOver: false }"
                             @dragover.prevent="isOver = true; event.dataTransfer.dropEffect = 'copy'"
                             @dragleave.prevent="isOver = false"
                             @drop.prevent="
                                 isOver = false;
                                 const reorderFrom = event.dataTransfer.getData('sorolata/reorder');
                                 const itemType = event.dataTransfer.getData('text/plain');
                                 if (reorderFrom !== '') {
                                     $wire.reorderSections(parseInt(reorderFrom), {{ $index + 1 }});
                                 } else if (itemType) {
                                     $wire.insertElementAt(itemType, {{ $index + 1 }});
                                 }
                             "
                             class="w-full transition-all duration-200 flex items-center justify-center cursor-pointer group my-1"
                             :class="isOver ? 'bg-[#214E34]/25 border-2 border-dashed border-[#214E34] py-6 rounded-xl shadow-inner scale-[1.01]' : 'h-3 hover:h-8 hover:bg-[#214E34]/15 rounded-lg border border-transparent hover:border-dashed hover:border-[#214E34]/40'">
                            <div class="hidden group-hover:flex items-center space-x-1.5 text-[11px] font-bold text-[#214E34]"
                                 :class="isOver ? '!flex text-white bg-[#214E34] px-4 py-1.5 rounded-full shadow-lg' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Drop Element / Block Here</span>
                            </div>
                        </div>
                    @empty
                        <!-- Empty Canvas Dropzone -->
                        <div x-data="{ isOver: false }"
                             @dragover.prevent="isOver = true; event.dataTransfer.dropEffect = 'copy'"
                             @dragleave.prevent="isOver = false"
                             @drop.prevent="
                                 isOver = false;
                                 const itemType = event.dataTransfer.getData('text/plain');
                                 if (itemType) {
                                     $wire.insertElementAt(itemType, 0);
                                 }
                             "
                             class="py-24 text-center px-4 transition-all duration-200 rounded-2xl"
                             :class="isOver ? 'bg-[#214E34]/20 border-2 border-dashed border-[#214E34]' : ''">
                            <div class="w-16 h-16 mx-auto rounded-full bg-[#214E34]/10 text-[#214E34] flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="font-editorial-heading text-2xl text-gray-800 mb-2">Canvas is Empty</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">
                                Drag & drop any element or block from the left panel, or click below to start with a hero showcase.
                            </p>
                            <button type="button" 
                                    wire:click="addSection('hero_cinematic')"
                                    class="px-5 py-2.5 rounded-full bg-[#214E34] text-white text-xs font-semibold">
                                + Add Cinematic Hero Block
                            </button>
                        </div>
                    @endforelse

                </div>

            </div>

            <!-- Canvas Bottom Add Block Strip (Also Dropzone for End of Page) -->
            <div x-data="{ isOver: false }"
                 @dragover.prevent="isOver = true; event.dataTransfer.dropEffect = 'copy'"
                 @dragleave.prevent="isOver = false"
                 @drop.prevent="
                     isOver = false;
                     const reorderFrom = event.dataTransfer.getData('sorolata/reorder');
                     const itemType = event.dataTransfer.getData('text/plain');
                     if (reorderFrom !== '') {
                         $wire.reorderSections(parseInt(reorderFrom), {{ count($sections) }});
                     } else if (itemType) {
                         $wire.insertElementAt(itemType, {{ count($sections) }});
                     }
                 "
                 class="w-full max-w-[1240px] my-6 text-center transition-all p-3 rounded-xl"
                 :class="isOver ? 'bg-[#214E34]/25 border-2 border-dashed border-[#214E34]' : ''">
                <button type="button" 
                        wire:click="addSection('hero_cinematic')"
                        class="px-6 py-2.5 rounded-full border border-dashed border-gray-600 hover:border-[#214E34] text-gray-400 hover:text-white bg-[#1a1d24]/60 hover:bg-[#1a1d24] text-xs font-medium transition-all inline-flex items-center space-x-2">
                    <svg class="w-4 h-4 text-[#A3D9B1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Append New Section to Canvas (Or Drop Any Element Here)</span>
                </button>
            </div>

        </main>

        <!-- 3. RIGHT INSPECTOR PANEL (ELEMENTOR STYLE: CONTENT, STYLE, ADVANCED) -->
        <aside class="w-80 bg-[#1a1d24] border-l border-[#282d37] flex flex-col shrink-0 z-20">
            
            <div class="p-3 border-b border-[#282d37] bg-[#14161d] flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs font-bold uppercase tracking-wider text-white">
                        @if ($selectedSection)
                            {{ ucwords(str_replace('_', ' ', $selectedSection['type'])) }}
                        @else
                            Inspector
                        @endif
                    </span>
                </div>
                @if ($selectedSection)
                    <span class="text-[10px] px-2 py-0.5 rounded bg-[#282d37] text-emerald-400 font-mono">
                        Active Section
                    </span>
                @endif
            </div>

            <!-- 3 Elementor Tab Switchers -->
            <div class="grid grid-cols-3 border-b border-[#282d37] bg-[#12141a]">
                <button type="button" 
                        wire:click="setInspectorTab('content')"
                        class="py-2.5 text-xs font-semibold tracking-wide flex items-center justify-center space-x-1 border-b-2 transition-all {{ $inspectorTab === 'content' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Content</span>
                </button>
                <button type="button" 
                        wire:click="setInspectorTab('style')"
                        class="py-2.5 text-xs font-semibold tracking-wide flex items-center justify-center space-x-1 border-b-2 transition-all {{ $inspectorTab === 'style' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <span>Style</span>
                </button>
                <button type="button" 
                        wire:click="setInspectorTab('advanced')"
                        class="py-2.5 text-xs font-semibold tracking-wide flex items-center justify-center space-x-1 border-b-2 transition-all {{ $inspectorTab === 'advanced' ? 'border-[#214E34] text-white bg-[#1a1d24]' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Advanced</span>
                </button>
            </div>

            @if ($selectedSection && $selectedIndex !== null)
                @php
                    $sIndex = $selectedIndex;
                @endphp
                <div class="flex-1 overflow-y-auto builder-scroll p-4 space-y-4">
                    
                    <!-- TAB 1: CONTENT (কনটেন্ট) -->
                    @if ($inspectorTab === 'content')
                        <!-- Dynamic Heritage Tags Helper -->
                        @php
                            $dynamicTagList = [
                                ['label' => 'Title', 'code' => '{{ product.title }}'],
                                ['label' => 'Price', 'code' => '{{ product.price }}'],
                                ['label' => 'Artisan', 'code' => '{{ artisan.name }}'],
                                ['label' => 'District', 'code' => '{{ district.name }}'],
                                ['label' => 'Atelier', 'code' => '{{ artisan.atelier }}'],
                                ['label' => 'GI Specialty', 'code' => '{{ district.gi_specialty }}'],
                            ];
                        @endphp
                        <div class="p-2.5 rounded-lg bg-[#14161d] border border-[#282d37]">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">Dynamic Tags (Click to copy)</span>
                                <span class="text-[9px] text-gray-500">Auto-fills from Product Data</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5" x-data="{ copied: '' }">
                                @foreach ($dynamicTagList as $dTag)
                                    <button type="button" 
                                            @click="navigator.clipboard.writeText('{{ $dTag['code'] }}'); copied = '{{ $dTag['code'] }}'; setTimeout(() => copied = '', 1500)"
                                            class="px-2 py-1 rounded text-[10px] font-mono bg-[#222731] hover:bg-[#214E34] text-gray-200 hover:text-white border border-[#282d37] transition-all flex items-center space-x-1"
                                            title="Click to copy {{ $dTag['code'] }}">
                                        <span>@php echo htmlspecialchars($dTag['code']); @endphp</span>
                                    </button>
                                @endforeach
                                <div x-show="copied" x-cloak class="text-[10px] text-emerald-400 w-full mt-1 flex items-center space-x-1">
                                    <span>✓ Copied:</span>
                                    <span class="font-mono" x-text="copied"></span>
                                </div>
                            </div>
                        </div>

                        @php
                            $secType = $sections[$sIndex]['type'] ?? '';
                        @endphp

                        {{-- 1. TESTIMONIAL EDITORIAL --}}
                        @if ($secType === 'testimonial_editorial')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Testimonial / Collector Impression
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text (লেবেল)</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="e.g. Collector Impressions"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Collector Review / Quote (প্রশংসা বা মতামত)</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="4"
                                              placeholder="Unboxing this piece in London was a transcendent experience..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Collector / Reviewer Name (নাম)</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="e.g. Dr. Eleanor Vance"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Designation & Location (পদবী ও অবস্থান)</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.collector_location" 
                                           placeholder="e.g. Textile Historian & Collector • Kensington, London"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>
                            </div>

                        {{-- 2. SPECS ANATOMY --}}
                        @elseif ($secType === 'specs_anatomy')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Pure Formulation & Metrics Table
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="e.g. Material Formulation"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Table Title (শিরোনাম)</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="e.g. Pure Formulation & Metrics"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Subtitle / Description</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="2"
                                              placeholder="Transparent composition details..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <!-- 6 Specific formulation items -->
                                <div class="p-3 rounded-xl bg-[#14161d] border border-[#282d37] space-y-3">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">
                                        Specification Details (উপাদান টেবিল এডিট করুন)
                                    </span>
                                    
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Fiber / Material (আঁশ / উপাদান)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.spec_fiber" 
                                               placeholder="100% Hand-Ginned Indigenous Cotton"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Weave Density (বুনন ঘনত্ব)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.spec_density" 
                                               placeholder="120 × 120 thread count pit-loom configuration"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Dye Formulation (রং প্রক্রিয়া)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.spec_dye" 
                                               placeholder="Botanical Madder Root, Pomegranate Rind..."
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Crafting Duration (সময়কাল)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.spec_duration" 
                                               placeholder="180 hours of handloom dedication"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Dimensions (পরিমাপ)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.spec_dimensions" 
                                               placeholder="220 cm × 75 cm"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Care Instructions (যত্ন নির্দেশিকা)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.spec_care" 
                                               placeholder="Delicate cold hand rinse with natural soapnut, shade dry"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>
                                </div>
                            </div>

                        {{-- 3. ARTISAN SPOTLIGHT --}}
                        @elseif ($secType === 'artisan_spotlight')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Master Artisan Spotlight
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Label</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="The Master Artisan Behind This Piece"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Artisan Name (কারিগরের নাম)</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="Rabeya Begum"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Artisan Pull Quote (উক্তি)</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="3"
                                              placeholder="Our hands carry the memory of three centuries of loom heritage..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Atelier / Loom Name</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.atelier_name" 
                                               placeholder="Dewan Royal Loom"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Years of Mastery</label>
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.experience" 
                                               placeholder="38"
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>
                                </div>

                                <!-- Photo Upload -->
                                <div class="p-3 rounded-xl bg-[#14161d] border border-[#282d37] space-y-2">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">Artisan Portrait Photo (ছবি)</span>
                                    <input type="file" wire:model="uploadedImage" accept="image/*" class="text-xs text-gray-400">
                                    @if (!empty($sections[$sIndex]['settings']['image_url']))
                                        <div class="flex items-center space-x-2 pt-2">
                                            <img src="{{ $sections[$sIndex]['settings']['image_url'] }}" class="w-12 h-12 rounded-lg object-cover">
                                            <button type="button" wire:click="removeImage" class="text-[10px] text-red-400">Remove Photo</button>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Biography (জীবনকথা)</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.biography" 
                                              rows="3"
                                              placeholder="Carrying forward an uninterrupted lineage of artisan mastery..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>
                            </div>

                        {{-- 4. HERO CINEMATIC --}}
                        @elseif ($secType === 'hero_cinematic')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Cinematic Hero Showcase
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="GI Certified Heritage Artifact"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Product Headline</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="@{{ product.title }}"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Subtitle / Narrative</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="3"
                                              placeholder="Write storytelling or subtitle..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">CTA Button Text & Price</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.primary_cta_text" 
                                           placeholder="Acquire Piece • @{{ product.price }}"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <!-- Hero Photography Upload -->
                                <div class="p-3.5 rounded-xl bg-[#14161d] border border-[#282d37] space-y-3">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">Hero Photo / Video</span>
                                    <input type="file" wire:model="uploadedImage" accept="image/*" class="text-xs text-gray-400">
                                    @if (!empty($sections[$sIndex]['settings']['image_url']))
                                        <div class="flex items-center space-x-2 pt-1">
                                            <img src="{{ $sections[$sIndex]['settings']['image_url'] }}" class="w-12 h-12 rounded-lg object-cover">
                                            <button type="button" wire:click="removeImage" class="text-[10px] text-red-400">Remove Image</button>
                                        </div>
                                    @endif
                                    <div class="pt-2 border-t border-[#282d37]">
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Video URL (Optional)</label>
                                        <input type="text" 
                                               wire:model.live.debounce.400ms="sections.{{ $sIndex }}.settings.video_url" 
                                               placeholder="https://www.youtube.com/watch?v=..."
                                               class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                    </div>
                                </div>
                            </div>

                        {{-- 5. STICKY PURCHASE BOX --}}
                        @elseif ($secType === 'sticky_purchase_box')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Purchase Box & Guarantee
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="Immediate Acquisition"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Title</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="@{{ product.title }}"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Subtitle</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                           placeholder="Single Batch Archive Edition..."
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Checkout Button Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.primary_cta_text" 
                                           placeholder="Add to Archival Box • Instant Checkout"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div class="p-3 rounded-xl bg-[#14161d] border border-[#282d37] space-y-2.5">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">3 Guarantee Perks (সুবিধাসমূহ)</span>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Perk 1: Insured Courier</label>
                                        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.perk_1" placeholder="Delivered in tamper-evident box." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Perk 2: Artisan Royalty</label>
                                        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.perk_2" placeholder="Fair remuneration deposited to guild." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Perk 3: Certificate</label>
                                        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.perk_3" placeholder="Numbered certificate signed by craftsman." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    </div>
                                </div>
                            </div>

                        {{-- 6. INTERACTIVE GALLERY --}}
                        @elseif ($secType === 'interactive_gallery')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Macro Visual Gallery
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="Macro Visual Study"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Gallery Title (শিরোনাম)</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="Tactile Intricacies"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Subtitle</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="2"
                                              placeholder="Notice the deliberate irregularities that prove human hand execution..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <div class="p-3 rounded-xl bg-[#14161d] border border-[#282d37] space-y-2.5">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">Gallery Image URLs</span>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Image 1 URL</label>
                                        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.gallery_img_1" placeholder="https://..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Image 2 URL</label>
                                        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.gallery_img_2" placeholder="https://..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Image 3 URL</label>
                                        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.gallery_img_3" placeholder="https://..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                    </div>
                                </div>
                            </div>

                        {{-- 7. HERITAGE PROVENANCE --}}
                        @elseif ($secType === 'heritage_provenance')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Regional Provenance & GI
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="Geographical Indication & Regional Provenance"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Heading</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="Rooted in @{{ district.name }}"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Narrative Subtitle</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="2"
                                              placeholder="Soil, microclimate, and river humidity shape the personality..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <div class="p-3 rounded-xl bg-[#14161d] border border-[#282d37] space-y-2.5">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">3 Provenance Cards</span>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Card 1: GI Specialty Text</label>
                                        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.gi_specialty" placeholder="World-renowned handloom textile..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Card 2: River Basin & Climate</label>
                                        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.river_basin" placeholder="Shitalakshya river air provides morning humidity..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 mb-0.5">Card 3: Historical Chronicle</label>
                                        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.historical_narrative" placeholder="Documented in traveler chronicles since 14th century..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none"></textarea>
                                    </div>
                                </div>
                            </div>

                        {{-- 8. ATOMIC ELEMENTS --}}
                        @elseif ($secType === 'element_heading')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Heading Widget
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Heading Text</label>
                                    <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" placeholder="Heading text..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>
                            </div>

                        @elseif ($secType === 'element_paragraph')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Paragraph Widget
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Paragraph Text</label>
                                    <textarea rows="4" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" placeholder="Paragraph text..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>
                            </div>

                        @elseif ($secType === 'element_image')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Image Widget
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Upload Image</label>
                                    <input type="file" wire:model="uploadedImage" accept="image/*" class="text-xs text-gray-400">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Image URL (or paste external link)</label>
                                    <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.image_url" placeholder="https://..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                </div>
                            </div>

                        @elseif ($secType === 'element_video')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Video Player Widget
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Video URL (YouTube, Vimeo, MP4)</label>
                                    <input type="text" wire:model.live.debounce.400ms="sections.{{ $sIndex }}.settings.video_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                </div>
                            </div>

                        @elseif ($secType === 'element_button')
                            <div class="space-y-4">
                                <div class="p-2 rounded-lg bg-[#214E34]/20 border border-[#214E34]/40 text-[11px] text-[#A3D9B1]">
                                    Editing Action Button
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Button Text</label>
                                    <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.primary_cta_text" placeholder="Click Here" class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Button Link URL</label>
                                    <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.primary_cta_url" placeholder="/shop" class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                                </div>
                            </div>

                        {{-- 9. DEFAULT / FALLBACK FOR OTHER SECTIONS --}}
                        @else
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Headline Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" 
                                           placeholder="e.g. Section Headline"
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Subheadline / Narrative</label>
                                    <textarea wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" 
                                              rows="3"
                                              placeholder="Description..."
                                              class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Badge Text</label>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.badge_text" 
                                           placeholder="Label..."
                                           class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                                </div>

                                <div class="p-3 rounded-xl bg-[#14161d] border border-[#282d37] space-y-2">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#A3D9B1] block">Media & Uploads</span>
                                    <input type="file" wire:model="uploadedImage" accept="image/*" class="text-xs text-gray-400">
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- TAB 2: STYLE (স্টাইল) -->
                    @if ($inspectorTab === 'style')
                        <!-- Alignment Selector (Left, Center, Right) -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1.5">Text & Content Alignment</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" 
                                        wire:click="$set('sections.{{ $sIndex }}.settings.alignment', 'left')"
                                        class="py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-1 transition-all {{ ($sections[$sIndex]['settings']['alignment'] ?? 'center') === 'left' ? 'bg-[#214E34] border-[#214E34] text-white' : 'bg-[#12141a] border-[#282d37] text-gray-400 hover:text-white' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14" />
                                    </svg>
                                    <span>Left</span>
                                </button>
                                <button type="button" 
                                        wire:click="$set('sections.{{ $sIndex }}.settings.alignment', 'center')"
                                        class="py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-1 transition-all {{ ($sections[$sIndex]['settings']['alignment'] ?? 'center') === 'center' ? 'bg-[#214E34] border-[#214E34] text-white' : 'bg-[#12141a] border-[#282d37] text-gray-400 hover:text-white' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14" />
                                    </svg>
                                    <span>Center</span>
                                </button>
                                <button type="button" 
                                        wire:click="$set('sections.{{ $sIndex }}.settings.alignment', 'right')"
                                        class="py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-1 transition-all {{ ($sections[$sIndex]['settings']['alignment'] ?? 'center') === 'right' ? 'bg-[#214E34] border-[#214E34] text-white' : 'bg-[#12141a] border-[#282d37] text-gray-400 hover:text-white' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14" />
                                    </svg>
                                    <span>Right</span>
                                </button>
                            </div>
                        </div>

                        <!-- Headline Size -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1">Headline Font Size</label>
                            <select wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline_size" 
                                    class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                <option value="text-2xl sm:text-3xl">Medium (2xl - 3xl)</option>
                                <option value="text-3xl sm:text-4xl">Large (3xl - 4xl)</option>
                                <option value="text-4xl sm:text-5xl lg:text-6xl">Extra Large (4xl - 6xl)</option>
                                <option value="text-5xl sm:text-6xl lg:text-7xl">Cinematic Monumental (5xl - 7xl)</option>
                            </select>
                        </div>

                        <!-- Text Color -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1.5">Headline & Text Color</label>
                            <div class="grid grid-cols-5 gap-2 mb-2">
                                @foreach (['#1D1D1D', '#FFFFFF', '#214E34', '#C96A3D', '#D4AF37'] as $tColor)
                                    <button type="button" 
                                            wire:click="$set('sections.{{ $sIndex }}.settings.text_color', '{{ $tColor }}')"
                                            class="h-7 rounded border border-gray-600 transition-transform hover:scale-110 flex items-center justify-center {{ ($sections[$sIndex]['settings']['text_color'] ?? '#1D1D1D') === $tColor ? 'ring-2 ring-emerald-400' : '' }}"
                                            style="background-color: {{ $tColor }};"
                                            title="{{ $tColor }}"></button>
                                @endforeach
                            </div>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.text_color" 
                                   placeholder="#1D1D1D"
                                   class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                        </div>

                        <!-- Background Color -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1.5">Section Background</label>
                            <div class="grid grid-cols-5 gap-2 mb-2">
                                @foreach (['#FFFFFF', '#F8F6F2', '#214E34', '#C96A3D', '#1D1D1D'] as $color)
                                    <button type="button" 
                                            wire:click="$set('sections.{{ $sIndex }}.settings.bg_color', '{{ $color }}')"
                                            class="h-7 rounded border border-gray-600 transition-transform hover:scale-110 flex items-center justify-center {{ ($sections[$sIndex]['settings']['bg_color'] ?? '#FFFFFF') === $color ? 'ring-2 ring-emerald-400' : '' }}"
                                            style="background-color: {{ $color }};"
                                            title="{{ $color }}"></button>
                                @endforeach
                            </div>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.bg_color" 
                                   placeholder="#FFFFFF"
                                   class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                        </div>

                        <!-- Padding Top / Bottom -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Padding Top</label>
                                <select wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.padding_top" 
                                        class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    <option value="32px">32px (Compact)</option>
                                    <option value="48px">48px (Default)</option>
                                    <option value="64px">64px (Roomy)</option>
                                    <option value="96px">96px (Spacious)</option>
                                    <option value="128px">128px (Hero)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Padding Bottom</label>
                                <select wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.padding_bottom" 
                                        class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                    <option value="32px">32px (Compact)</option>
                                    <option value="48px">48px (Default)</option>
                                    <option value="64px">64px (Roomy)</option>
                                    <option value="96px">96px (Spacious)</option>
                                    <option value="128px">128px (Hero)</option>
                                </select>
                            </div>
                        </div>
                    @endif

                    <!-- TAB 3: ADVANCED (অ্যাডভান্সড) -->
                    @if ($inspectorTab === 'advanced')
                        <!-- Container Width -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1">Container Width</label>
                            <select wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.container_width" 
                                    class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-1.5 focus:border-[#214E34] focus:outline-none">
                                <option value="boxed">Boxed (Max-Width 1200px)</option>
                                <option value="full">Full Width (100% Screen)</option>
                            </select>
                        </div>

                        <!-- Custom CSS Class -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1">Custom CSS Class</label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.custom_class" 
                                   placeholder="e.g. custom-banner py-4"
                                   class="w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-3 py-1.5 focus:border-[#214E34] focus:outline-none font-mono">
                        </div>

                        <!-- Active Visibility Toggle -->
                        <div class="pt-3 border-t border-[#282d37] flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-white">Section Visibility</div>
                                <div class="text-[11px] text-gray-400">Display this section on storefront</div>
                            </div>
                            <input type="checkbox" 
                                   wire:model.live="sections.{{ $sIndex }}.is_active" 
                                   class="w-4 h-4 rounded text-[#214E34] focus:ring-0 bg-[#12141a] border-[#282d37]">
                        </div>
                    @endif

                </div>
            @else
                <!-- No Section Selected State -->
                <div class="flex-1 flex flex-col items-center justify-center p-6 text-center text-gray-500">
                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                    <p class="text-xs font-medium text-gray-400">No Block Selected</p>
                    <p class="text-[11px] text-gray-500 mt-1 max-w-[200px]">
                        Click any section on the canvas to inspect and customize its settings.
                    </p>
                </div>
            @endif

        </aside>

    </div>

</div>
