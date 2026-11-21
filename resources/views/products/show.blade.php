@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ $metaTags['title'] }}</title>
    <meta name="description" content="{{ $metaTags['description'] }}">
    <link rel="canonical" href="{{ $metaTags['canonical'] }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $metaTags['og:title'] }}">
    <meta property="og:description" content="{{ $metaTags['og:description'] }}">
    <meta property="og:image" content="{{ $metaTags['og:image'] }}">
    <meta property="og:url" content="{{ $metaTags['og:url'] }}">
    <meta property="og:type" content="product">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTags['twitter:title'] }}">
    <meta name="twitter:description" content="{{ $metaTags['twitter:description'] }}">
    <meta name="twitter:image" content="{{ $metaTags['twitter:image'] }}">

    <!-- Schema.org JSON-LD Rich Snippet -->
    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    <article class="product-landing-page {{ $product->getThemeMode()->cssClass() }}">
        @foreach($renderedBlocks as $blockHtml)
            {!! $blockHtml !!}
        @endforeach

        <!-- Behind the Loom (Origin Story Feature - Shajgoj Reverse Linking) -->
        @if(isset($relatedStories) && $relatedStories->isNotEmpty())
            @php
                $locale = app()->getLocale();
                $story = $relatedStories->first();
            @endphp
            <section class="py-16 bg-[#F5F2EB] border-t border-[#E5DFD3]">
                <div class="max-w-5xl mx-auto px-6 lg:px-12">
                    <div class="rounded-3xl border border-[#DFD8CA] bg-white p-8 sm:p-12 shadow-sm flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-full md:w-1/2 aspect-[16/10] rounded-2xl overflow-hidden bg-[#ECE8DF] shrink-0">
                            <img src="{{ $story->hero_image }}" alt="{{ $story->getLocalized('title') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="w-full md:w-1/2 space-y-4 text-left">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] tracking-widest uppercase font-bold text-[#C96A3D] bg-[#C96A3D]/10 px-2.5 py-0.5 rounded-full">
                                    {{ __('Behind the Loom') }}
                                </span>
                                <span class="text-xs text-[#888888]">• {{ $story->reading_time }} {{ __('min read') }}</span>
                            </div>
                            <h3 class="font-editorial-heading text-2xl sm:text-3xl text-[#1D1D1D] leading-snug">
                                {{ $story->getLocalized('title') }}
                            </h3>
                            <p class="font-editorial-body text-sm text-[#555555] line-clamp-3 leading-relaxed">
                                {{ $story->getLocalized('excerpt') }}
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('stories.show', $story->slug) }}" class="inline-flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-[#214E34] hover:text-[#1a3f2a] underline underline-offset-4">
                                    <span>{{ __('Read Full Story') }}</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- Collector Reviews & Impressions Section -->
        <section id="reviews" class="py-20 bg-[#FDFBF7] border-t border-[#E8E8E8]">
            <div class="max-w-5xl mx-auto px-6 lg:px-12 space-y-12">
                
                @if(session('review_success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                        <span class="text-xl">✨</span>
                        <span>{{ session('review_success') }}</span>
                    </div>
                @endif

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-[#E8E8E8] pb-8">
                    <div>
                        <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('Provenance & Feedback') }}</span>
                        <h2 class="font-editorial-heading text-3xl sm:text-4xl font-light text-[#1D1D1D] mt-1">
                            {{ __('Collector Reviews') }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl border border-[#E8E8E8] shadow-xs">
                        <div class="text-3xl font-black text-[#214E34]">{{ number_format($averageRating, 1) }}</div>
                        <div>
                            <div class="flex text-amber-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($averageRating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <div class="text-xs text-[#777777]">{{ __(':count Verified Reviews', ['count' => $reviews->count()]) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Grid / List -->
                @if($reviews->isNotEmpty())
                    <div class="space-y-6">
                        @foreach($reviews as $rev)
                            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E8E8E8] shadow-xs space-y-4">
                                <div class="flex items-start justify-between">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $rev->author_name }}</h4>
                                            @if($rev->is_verified_purchase)
                                                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200 flex items-center gap-1">
                                                    <span>✓</span> {{ __('Verified Buyer') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $rev->author_location }} • {{ $rev->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="flex text-amber-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                </div>

                                @if($rev->title)
                                    <h5 class="text-sm font-bold text-gray-900 font-editorial-heading">{{ $rev->title }}</h5>
                                @endif

                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed font-light">
                                    {{ $rev->content }}
                                </p>

                                @if($rev->admin_reply)
                                    <div class="mt-4 pt-4 border-t border-[#F0EFEA] bg-[#F9F8F5] p-4 rounded-2xl space-y-1">
                                        <div class="text-xs font-bold text-[#214E34] flex items-center gap-1.5">
                                            <span>🏛️</span> {{ __('Sorolata Curator Note:') }}
                                        </div>
                                        <p class="text-xs text-gray-600 leading-relaxed">
                                            {{ $rev->admin_reply }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center rounded-3xl bg-white border border-[#E8E8E8] p-8 space-y-2">
                        <div class="text-2xl text-amber-500">★</div>
                        <h4 class="text-sm font-semibold text-gray-900">{{ __('No public reviews yet') }}</h4>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto">{{ __('Be the first to acquire this unique masterpiece and share your review.') }}</p>
                    </div>
                @endif

                <!-- Write a Review Form -->
                <div class="bg-white rounded-[32px] p-8 sm:p-10 border border-[#E8E8E8] shadow-soft-sm space-y-6">
                    <div>
                        <span class="text-xs uppercase tracking-widest text-[#C96A3D] font-bold">{{ __('Share Your Experience') }}</span>
                        <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D] mt-1">
                            {{ __('Write a Review') }}
                        </h3>
                        <p class="text-xs text-[#777777] mt-1">{{ __('Your review honors our artisans and enriches the living heritage archive.') }}</p>
                    </div>

                    <form action="{{ route('products.review.submit', $product->slug) }}" method="POST" class="space-y-6" x-data="{ rating: 5 }">
                        @csrf
                        
                        <!-- Star Rating Selector -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Select Your Rating') }}</label>
                            <div class="flex items-center gap-2">
                                <template x-for="star in 5" :key="star">
                                    <button 
                                        type="button" 
                                        @click="rating = star" 
                                        class="text-2xl transition-transform hover:scale-125 focus:outline-none"
                                        :class="star <= rating ? 'text-amber-400' : 'text-gray-300'"
                                    >
                                        ★
                                    </button>
                                </template>
                                <span class="text-xs font-semibold text-gray-600 ml-2">
                                    (<span x-text="rating"></span> / 5 {{ __('Stars') }})
                                </span>
                            </div>
                            <input type="hidden" name="rating" :value="rating">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label for="author_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Your Name *') }}</label>
                                <input 
                                    type="text" 
                                    name="author_name" 
                                    id="author_name" 
                                    required 
                                    value="{{ old('author_name', auth()->user()?->name ?? '') }}"
                                    placeholder="{{ __('e.g. Farhana Rahman') }}"
                                    class="w-full px-4 py-3 text-xs border border-gray-300 rounded-2xl focus:ring-1 focus:ring-[#214E34] focus:outline-none"
                                >
                            </div>

                            <div class="space-y-1.5">
                                <label for="author_location" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Your District / City') }}</label>
                                <input 
                                    type="text" 
                                    name="author_location" 
                                    id="author_location" 
                                    value="{{ old('author_location') }}"
                                    placeholder="{{ __('e.g. Gulshan, Dhaka') }}"
                                    class="w-full px-4 py-3 text-xs border border-gray-300 rounded-2xl focus:ring-1 focus:ring-[#214E34] focus:outline-none"
                                >
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="title" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Review Title (Optional)') }}</label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                value="{{ old('title') }}"
                                placeholder="{{ __('e.g. Incomparable weave and heritage elegance') }}"
                                class="w-full px-4 py-3 text-xs border border-gray-300 rounded-2xl focus:ring-1 focus:ring-[#214E34] focus:outline-none"
                            >
                        </div>

                        <div class="space-y-1.5">
                            <label for="content" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Your Review & Detailed Impressions *') }}</label>
                            <textarea 
                                name="content" 
                                id="content" 
                                rows="4" 
                                required 
                                placeholder="{{ __('Share your thoughts on weave quality, motif intricacies, finishing, or unboxing...') }}"
                                class="w-full px-4 py-3 text-xs border border-gray-300 rounded-2xl focus:ring-1 focus:ring-[#214E34] focus:outline-none leading-relaxed"
                            >{{ old('content') }}</textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="px-8 py-3.5 bg-[#214E34] hover:bg-[#163824] text-white text-xs font-bold uppercase tracking-wider rounded-2xl transition-all shadow-md shadow-emerald-900/10 flex items-center gap-2"
                        >
                            <span>{{ __('Submit Review') }}</span>
                            <span>→</span>
                        </button>
                    </form>
                </div>

            </div>
        </section>
    </article>

    {{-- Trigger DataLayer view_item & Meta ViewContent (PDF Step 05) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.sorolataAnalytics) {
                window.sorolataAnalytics.pushViewItem({
                    id: '{{ $product->id }}',
                    name: @json($product->getLocalized('title')),
                    price: {{ (float) $product->price }},
                    category: @json($product->category?->name ?? 'Heritage Handcraft')
                });
            }
        });
    </script>
@endsection
