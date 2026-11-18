@extends('layouts.storefront')

@section('seo_meta')
    <title>Instant Heritage Search — Find Artifacts by District, Artisan & Material | Sorolata</title>
    <meta name="description" content="Search authentic Bengali handcrafts across 64 districts and hundreds of generational master artisans.">
    <link rel="canonical" href="{{ route('search.index') }}">
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16 space-y-12">
        
        <!-- Search Stage Hero -->
        <div class="max-w-2xl mx-auto text-center space-y-6">
            <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('Semantic Heritage Discovery') }}</span>
            <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D]">
                {{ __('Search The Living Archive') }}
            </h1>

            <form action="{{ route('search.index') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $query }}"
                    placeholder="{{ __('Search by loom, master weaver, district (e.g. Tangail, Sonargaon)...') }}" 
                    class="w-full text-sm px-6 py-4 rounded-full border border-[#E8E8E8] bg-white shadow-soft-xs focus:outline-none focus:border-[#214E34] focus:ring-2 focus:ring-[#214E34]/10"
                    autofocus
                >
                <button type="submit" class="absolute right-2 top-2 bottom-2 px-6 bg-[#214E34] text-white rounded-full text-xs font-semibold hover:bg-[#173825] transition-colors cursor-pointer">
                    {{ __('Search') }}
                </button>
            </form>

            <!-- Trending Search Tags -->
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-[#888888]">
                <span>{{ __('Trending:') }}</span>
                <a href="{{ route('search.index', ['q' => 'Muslin']) }}" class="text-[#214E34] hover:underline">{{ __('Sonargaon Muslin') }}</a>
                <span>•</span>
                <a href="{{ route('search.index', ['q' => 'Jamdani']) }}" class="text-[#214E34] hover:underline">{{ __('Tangail Jamdani') }}</a>
                <span>•</span>
                <a href="{{ route('search.index', ['q' => 'Brass']) }}" class="text-[#214E34] hover:underline">{{ __('Bogura Kansa') }}</a>
                <span>•</span>
                <a href="{{ route('search.index', ['q' => 'Shatranji']) }}" class="text-[#214E34] hover:underline">{{ __('Rangpur Carpet') }}</a>
            </div>
        </div>

        <!-- Search Results Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-[#E8E8E8] text-xs text-[#888888]">
                <span>
                    @if(!empty($query))
                        {!! __('Search results for ":query" (:count pieces found)', ['query' => '<strong class="text-[#1D1D1D]">' . e($query) . '</strong>', 'count' => $results->count()]) !!}
                    @else
                        {{ __('Curator Recommended Artifacts') }}
                    @endif
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($results as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-4 text-center py-20 space-y-4">
                        <h3 class="font-editorial-heading text-2xl text-[#1D1D1D]">{!! __('No pieces found matching ":query"', ['query' => e($query)]) !!}</h3>
                        <p class="text-sm text-[#888888]">{{ __('Try searching by district name like Tangail, Narayanganj or Bogura.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
