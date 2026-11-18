@extends('layouts.admin')

@section('title', $isEdit ? 'Edit Story: ' . $story->title : 'Compose New Story')

@section('content')
<div x-data="{ 
        activeTab: 'en',
        insertProduct(slug) {
            const shortcode = `\n\n[product:${slug}]\n\n`;
            const textarea = document.getElementById(this.activeTab === 'en' ? 'content_en' : 'content_bn');
            if (textarea) {
                const start = textarea.selectionStart || textarea.value.length;
                const end = textarea.selectionEnd || textarea.value.length;
                textarea.value = textarea.value.substring(0, start) + shortcode + textarea.value.substring(end);
                textarea.focus();
                alert(`Inserted [product:${slug}] into ${this.activeTab === 'en' ? 'English' : 'Bangla'} story!`);
            }
        }
     }" 
     class="space-y-6 max-w-5xl">

    <!-- Header -->
    <div class="flex items-center justify-between pb-6 border-b border-[#282d37]">
        <div>
            <a href="{{ route('admin.stories.index') }}" class="text-xs text-[#A3D9B1] hover:underline flex items-center gap-1 mb-1">
                ← Back to Stories
            </a>
            <h1 class="text-2xl font-semibold text-white tracking-tight">
                {{ $isEdit ? 'Edit Story: ' . $story->title : 'Compose New Heritage Dispatch' }}
            </h1>
        </div>

        @if($isEdit)
            <a href="{{ route('stories.show', $story->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg border border-[#282d37] bg-[#222731] hover:bg-[#282d37] text-xs font-medium text-gray-300">
                View Live Preview ↗
            </a>
        @endif
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.stories.update', $story->id) : route('admin.stories.store') }}" class="space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- Language Tabs Selector -->
        <div class="flex items-center justify-between bg-[#1a1d24] p-2 rounded-2xl border border-[#282d37]">
            <div class="flex items-center space-x-1">
                <button type="button" 
                        @click="activeTab = 'en'"
                        :class="activeTab === 'en' ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-400 hover:text-white'"
                        class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all">
                    🇬🇧 English Content
                </button>
                <button type="button" 
                        @click="activeTab = 'bn'"
                        :class="activeTab === 'bn' ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-400 hover:text-white'"
                        class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all">
                    🇧🇩 বাংলা কন্টেন্ট (Bangla)
                </button>
            </div>
            <span class="text-xs text-gray-500 pr-3">
                Switch tabs to enter bilingual translations
            </span>
        </div>

        <!-- ENGLISH FIELDS TAB -->
        <div x-show="activeTab === 'en'" class="space-y-6 bg-[#1a1d24] p-6 rounded-2xl border border-[#282d37]">
            <div class="border-b border-[#282d37] pb-3 mb-4">
                <h3 class="text-sm font-semibold text-white">English Dispatch Metadata & Body</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Story Title (English) *</label>
                    <input type="text" name="title" value="{{ old('title', $story->title) }}" required 
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Category *</label>
                    <input type="text" name="category" value="{{ old('category', $story->category ?? 'Heritage & Craft') }}" required 
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Author Name *</label>
                    <input type="text" name="author_name" value="{{ old('author_name', $story->author_name ?? 'Curator Desk') }}" required 
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Excerpt / Summary (English)</label>
                    <textarea name="excerpt" rows="2" 
                              class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">{{ old('excerpt', $story->excerpt) }}</textarea>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-medium text-gray-300">Story Body (English) *</label>
                    <span class="text-[11px] text-[#A3D9B1]">Tip: Click any product below to inject into text</span>
                </div>
                <textarea id="content_en" name="content" rows="12" required 
                          class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-3 font-mono text-xs text-gray-200 focus:outline-none focus:border-[#214E34] leading-relaxed">{{ old('content', $story->content) }}</textarea>
            </div>
        </div>

        <!-- BANGLA FIELDS TAB -->
        <div x-show="activeTab === 'bn'" class="space-y-6 bg-[#1a1d24] p-6 rounded-2xl border border-[#282d37]" style="display: none;">
            <div class="border-b border-[#282d37] pb-3 mb-4">
                <h3 class="text-sm font-semibold text-white">বাংলা সংস্করণ (Bangla Dispatch Content)</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">গল্পের শিরোনাম (বাংলা)</label>
                    <input type="text" name="title_bn" value="{{ old('title_bn', $story->title_bn) }}" 
                           placeholder="যেমন: সোনারগাঁয়ের লুপ্ত মসলিন ও জামদানির পুনর্জন্ম"
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">ক্যাটাগরি (বাংলা)</label>
                    <input type="text" name="category_bn" value="{{ old('category_bn', $story->category_bn) }}" 
                           placeholder="যেমন: বস্ত্রশিল্প ও তাঁত ঐতিহ্য"
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">লেখকের নাম (বাংলা)</label>
                    <input type="text" name="author_name_bn" value="{{ old('author_name_bn', $story->author_name_bn) }}" 
                           placeholder="যেমন: কাজী ফারহান"
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">সারসংক্ষেপ (বাংলা)</label>
                    <textarea name="excerpt_bn" rows="2" 
                              class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">{{ old('excerpt_bn', $story->excerpt_bn) }}</textarea>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-medium text-gray-300">মূল গল্প ও বিবরণ (বাংলা)</label>
                    <span class="text-[11px] text-[#A3D9B1]">টিপস: প্যারাগ্রাফের মাঝে [product:slug] কোড বসান</span>
                </div>
                <textarea id="content_bn" name="content_bn" rows="12" 
                          placeholder="বাংলায় সম্পূর্ণ গল্পটি লিখুন..."
                          class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-3 font-mono text-xs text-gray-200 focus:outline-none focus:border-[#214E34] leading-relaxed">{{ old('content_bn', $story->content_bn) }}</textarea>
            </div>
        </div>

        <!-- SHOPOPPABLE PRODUCT INSERTER TOOLBOX (The Shajgoj Feature) -->
        <div class="bg-[#1a1d24] p-6 rounded-2xl border border-[#282d37] space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                        <span>🛍️ Shoppable Products Inserter (In-Article & Carousel)</span>
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Click "Insert Into Story" to drop a shoppable card into your text cursor, and check the box to link it to the story carousel.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-64 overflow-y-auto pr-2">
                @foreach($products as $product)
                    <div class="p-3 bg-[#12141a] rounded-xl border border-[#282d37] flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <input type="checkbox" 
                                   name="product_ids[]" 
                                   value="{{ $product->id }}" 
                                   id="prod_{{ $product->id }}"
                                   {{ in_array($product->id, old('product_ids', $linkedProductIds)) ? 'checked' : '' }}
                                   class="rounded bg-[#1a1d24] border-gray-600 text-[#214E34] focus:ring-0">
                            <label for="prod_{{ $product->id }}" class="truncate cursor-pointer">
                                <span class="text-xs text-white block truncate">{{ $product->title }}</span>
                                <span class="text-[10px] text-[#A3D9B1]">৳ {{ number_format((float) $product->price) }}</span>
                            </label>
                        </div>
                        <button type="button" 
                                @click="insertProduct('{{ $product->slug }}')"
                                class="shrink-0 px-2 py-1 bg-[#222731] hover:bg-[#282d37] border border-[#282d37] rounded text-[10px] text-gray-300 font-mono hover:text-white transition-colors"
                                title="Insert shortcode [product:{{ $product->slug }}]">
                            + Insert
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- GLOBAL METADATA & PUBLISHING -->
        <div class="bg-[#1a1d24] p-6 rounded-2xl border border-[#282d37] space-y-4">
            <h3 class="text-sm font-semibold text-white">Global Settings & Publication</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Custom Slug (URL) *</label>
                    <input type="text" name="slug" value="{{ old('slug', $story->slug) }}" 
                           placeholder="revival-of-sonargaon-muslin"
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Hero Image URL</label>
                    <input type="url" name="hero_image" value="{{ old('hero_image', $story->hero_image) }}" 
                           placeholder="https://images.unsplash.com/..."
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Reading Time (Minutes) *</label>
                    <input type="number" name="reading_time" value="{{ old('reading_time', $story->reading_time ?? 4) }}" min="1" required 
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>
            </div>

            <div class="pt-2 flex items-center space-x-2">
                <input type="checkbox" name="is_published" id="is_published" value="1" 
                       {{ old('is_published', $story->is_published ?? true) ? 'checked' : '' }}
                       class="rounded bg-[#12141a] border-gray-600 text-[#214E34] focus:ring-0">
                <label for="is_published" class="text-xs text-gray-300 cursor-pointer">
                    Publish immediately to The Heritage Gazette
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-4">
            <a href="{{ route('admin.stories.index') }}" class="px-5 py-2.5 rounded-xl border border-[#282d37] text-xs font-medium text-gray-400 hover:text-white">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#1a3f2a] text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-all">
                {{ $isEdit ? 'Save Changes' : 'Publish Dispatch' }}
            </button>
        </div>
    </form>
</div>
@endsection
