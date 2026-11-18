@extends('layouts.storefront')

@section('seo_meta')
    <title>Curated Heritage Catalog — All Handcrafted Artifacts | Sorolata</title>
    <meta name="description" content="Browse our complete collection of GI-certified Bengali handloom, bell-metal, and folk artifacts. Filter by district, artisan, and craft.">
    <link rel="canonical" href="{{ route('shop.index') }}">
@endsection

@section('content')
    <!-- Shop Hero Header -->
    <section class="py-12 bg-white border-b border-[#E8E8E8]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="max-w-3xl space-y-3">
                <div class="flex items-center gap-2 text-xs text-[#888888] uppercase tracking-wider">
                    <a href="{{ url('/') }}" class="hover:text-[#214E34]">{{ __('Home') }}</a>
                    <span>/</span>
                    <span class="text-[#1D1D1D] font-semibold">{{ __('Curated Catalog') }}</span>
                </div>
                <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D]">
                    {{ __('The Living Archive') }}
                </h1>
                <p class="font-editorial-body text-base text-[#555555]">
                    {{ __('Explore authentic Bengali heirlooms. Filter through generational weaving clusters, lost-wax bell-metal foundries, and master craftsman ateliers.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Main Shop Catalog Body -->
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Sidebar Filter Panel -->
            <aside class="lg:col-span-3 space-y-8">
                <form action="{{ route('shop.index') }}" method="GET" class="space-y-6">
                    
                    <!-- Search within catalog -->
                    <div class="space-y-2">
                        <label class="text-xs font-semibold uppercase tracking-widest text-[#1D1D1D]">{{ __('Search Collection') }}</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ request('q') }}"
                                placeholder="{{ __('Search keyword...') }}" 
                                class="w-full text-xs px-3.5 py-2.5 rounded-full border border-[#E8E8E8] bg-white text-[#1D1D1D] focus:outline-none focus:border-[#214E34]"
                            >
                        </div>
                    </div>

                    <!-- Category Facets -->
                    <div class="space-y-3 pt-4 border-t border-[#E8E8E8]">
                        <h4 class="text-xs font-semibold uppercase tracking-widest text-[#1D1D1D]">{{ __('Craft Categories') }}</h4>
                        <div class="space-y-1.5 text-xs text-[#555555]">
                            <a href="{{ route('shop.index') }}" class="block py-1 hover:text-[#214E34] {{ empty($activeCategory) ? 'text-[#214E34] font-bold' : '' }}">
                                {{ __('All Guild Crafts') }}
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="flex items-center justify-between py-1 hover:text-[#214E34] {{ $activeCategory === $cat->slug ? 'text-[#214E34] font-bold' : '' }}">
                                    <span>{{ $cat->getLocalized('name') }}</span>
                                    <span class="text-[#888888]">({{ $cat->products_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- District Origin Facets -->
                    <div class="space-y-3 pt-4 border-t border-[#E8E8E8]">
                        <h4 class="text-xs font-semibold uppercase tracking-widest text-[#1D1D1D]">{{ __('Heritage District') }}</h4>
                        <div class="space-y-1.5 text-xs text-[#555555]">
                            @foreach($districts as $dist)
                                <a href="{{ route('shop.index', ['district' => $dist->id]) }}" class="flex items-center justify-between py-1 hover:text-[#214E34] {{ $activeDistrict == $dist->id ? 'text-[#214E34] font-bold' : '' }}">
                                    <span>{{ $dist->getLocalized('name') }}</span>
                                    <span class="text-[#888888]">({{ $dist->products_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="pt-4 border-t border-[#E8E8E8] flex gap-2">
                        <x-button type="submit" variant="primary" size="sm" class="w-full">
                            {{ __('Apply Filters') }}
                        </x-button>
                        <x-button href="{{ route('shop.index') }}" variant="ghost" size="sm">
                            {{ __('Reset') }}
                        </x-button>
                    </div>

                </form>
            </aside>

            <!-- Product Grid & Sorting Stage -->
            <main class="lg:col-span-9 space-y-6">
                
                <!-- Top Utility Bar: Count & Sorting -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E8E8E8] text-xs text-[#555555]">
                    <div>
                        <span>{{ __('Showing :count archival pieces', ['count' => $products->count()]) }}</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-[#888888]">{{ __('Sort by:') }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="px-3 py-1 rounded-full border text-xs {{ $activeSort === 'newest' ? 'bg-[#214E34] text-white border-[#214E34]' : 'bg-white border-[#E8E8E8] text-[#555555]' }}">
                                {{ __('Newest Drops') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="px-3 py-1 rounded-full border text-xs {{ $activeSort === 'price_asc' ? 'bg-[#214E34] text-white border-[#214E34]' : 'bg-white border-[#E8E8E8] text-[#555555]' }}">
                                {{ __('Price: Low to High') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" class="px-3 py-1 rounded-full border text-xs {{ $activeSort === 'popular' ? 'bg-[#214E34] text-white border-[#214E34]' : 'bg-white border-[#E8E8E8] text-[#555555]' }}">
                                {{ __('Curator Curated') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div class="col-span-3 text-center py-20 space-y-4">
                            <x-icon name="sparkles" class="w-10 h-10 text-[#C96A3D] mx-auto" />
                            <h3 class="font-editorial-heading text-2xl text-[#1D1D1D]">{{ __('No pieces match your selected filters') }}</h3>
                            <p class="text-sm text-[#888888]">{{ __('Try resetting your filter facets or exploring our flagship collections.') }}</p>
                            <x-button href="{{ route('shop.index') }}" variant="primary" size="sm">
                                {{ __('View All Pieces') }}
                            </x-button>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Links -->
                <div class="pt-8">
                    {{ $products->links() }}
                </div>

            </main>

        </div>
    </div>
@endsection
