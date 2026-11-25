@extends('layouts.storefront')

@php
    $locale = app()->getLocale();
@endphp

@section('seo_meta')
    <title>{{ __('The Heritage Gazette') }} — {{ __('Chronicles of Bengal Craft') }} | Sorolata</title>
    <meta name="description" content="Read in-depth editorial chronicles exploring lost textile lineages, botanical dyeing, and master artisans of Bengal.">
    <link rel="canonical" href="{{ route('stories.index') }}">
@endsection

@section('content')
    <x-heritage-header
        :eyebrow="__('Stories')"
        :title="__('Read about the crafts')"
        :subtitle="__('Slow journalism dedicated to the philosophy, material science, and living custodians of traditional South Asian handcraft.')"
        :breadcrumb="__('Stories')"
    />

    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            @foreach($stories as $story)
                @php
                    $storyTitle = $story->getLocalized('title');
                    $storyExcerpt = $story->getLocalized('excerpt');
                    $storyCategory = $story->getLocalized('category');
                @endphp
                <article class="group space-y-4">
                    <a href="{{ route('stories.show', $story->slug) }}" class="block relative rounded-[32px] overflow-hidden aspect-[16/10] bg-[#F8F6F2] shadow-sm hover:shadow-md transition-all">
                        <img src="{{ $story->hero_image }}" alt="{{ $storyTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                    </a>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-xs text-[#888888]">
                            <span class="uppercase tracking-widest text-[#C96A3D] font-bold">{{ $storyCategory }}</span>
                            <span>•</span>
                            <span>{{ $story->reading_time }} {{ __('min read') }}</span>
                            @if($story->products_count ?? $story->products->count())
                                <span>•</span>
                                <span class="text-[#214E34] font-medium bg-[#214E34]/10 px-2 py-0.5 rounded-full">
                                    🛍️ {{ __('Shoppable') }}
                                </span>
                            @endif
                        </div>
                        <h2 class="font-editorial-heading text-2xl sm:text-3xl font-light text-[#1D1D1D] group-hover:text-[#214E34] transition-colors">
                            <a href="{{ route('stories.show', $story->slug) }}">
                                {{ $storyTitle }}
                            </a>
                        </h2>
                        <p class="font-editorial-body text-sm text-[#555555] line-clamp-2 leading-relaxed">
                            {{ $storyExcerpt }}
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('stories.show', $story->slug) }}" class="text-xs font-semibold uppercase tracking-wider text-[#214E34] hover:underline">
                                {{ __('Read Chronicle') }} →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection
