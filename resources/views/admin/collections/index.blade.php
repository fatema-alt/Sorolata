@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{
    createModal: false,
    editModal: false,
    productSearch: '',
    editCollection: {
        id: null,
        title: '',
        slug: '',
        type: 'manual',
        description: '',
        cover_image: '',
        banner_url: '',
        is_featured: false,
        product_ids: []
    },
    openEdit(col, productIds) {
        this.editCollection = {
            id: col.id,
            title: col.title || '',
            slug: col.slug || '',
            type: col.type || 'manual',
            description: col.description || '',
            cover_image: col.cover_image || '',
            banner_url: col.banner_url || '',
            is_featured: !!col.is_featured,
            product_ids: productIds || []
        };
        this.editModal = true;
    },
    generateSlug(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
}">
    
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#252a36]">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Curated Heritage Collections</h1>
            <p class="text-xs text-gray-400 mt-1">Editorial anthologies, seasonal releases, and bespoke artisan groupings.</p>
        </div>

        <button type="button" @click="createModal = true"
                class="px-5 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2b6543] text-white text-xs font-semibold shadow-lg shadow-[#214E34]/20 flex items-center space-x-2 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Create Collection (নতুন কালেকশন)</span>
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.collections.index') }}" class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
            <div class="relative flex-grow sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search collections..." 
                       class="w-full bg-[#161922] border border-[#252a36] rounded-xl text-xs text-white pl-9 pr-4 py-2 focus:border-[#214E34] focus:outline-none">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="type" onchange="this.form.submit()" class="bg-[#161922] border border-[#252a36] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                <option value="">All Types</option>
                <option value="manual" {{ request('type') === 'manual' ? 'selected' : '' }}>Manual Curated</option>
                <option value="seasonal" {{ request('type') === 'seasonal' ? 'selected' : '' }}>Seasonal Release</option>
                <option value="featured" {{ request('type') === 'featured' ? 'selected' : '' }}>Featured Showcase</option>
                <option value="rule_based" {{ request('type') === 'rule_based' ? 'selected' : '' }}>Rule Based</option>
            </select>

            @if(request()->hasAny(['search', 'type']))
                <a href="{{ route('admin.collections.index') }}" class="text-xs text-gray-400 hover:text-white px-2 py-1">Reset</a>
            @endif
        </form>

        <div class="text-xs text-gray-400 self-end sm:self-auto font-mono">
            Total Collections: {{ $collections->total() }}
        </div>
    </div>

    <!-- Collections Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($collections as $c)
            @php
                $attachedProductIds = $c->products->pluck('id')->toArray();
            @endphp
            <div class="rounded-2xl bg-[#161922] border border-[#252a36] hover:border-[#214E34] transition-all flex flex-col justify-between overflow-hidden group shadow-sm">
                <!-- Cover Image / Banner -->
                @if($c->cover_image)
                    <div class="h-40 w-full overflow-hidden bg-[#0d0f14] relative">
                        <img src="{{ $c->cover_image }}" alt="{{ $c->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#161922] via-transparent to-transparent"></div>
                        @if($c->is_featured)
                            <span class="absolute top-3 right-3 bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">
                                ★ Featured
                            </span>
                        @endif
                    </div>
                @else
                    <div class="h-28 w-full bg-gradient-to-br from-[#1a2e23] to-[#12161f] p-4 flex items-start justify-between relative">
                        <span class="text-2xl">🏺</span>
                        @if($c->is_featured)
                            <span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                ★ Featured
                            </span>
                        @endif
                    </div>
                @endif

                <div class="p-6 space-y-3 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <h3 class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors">{{ $c->title }}</h3>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-[#222733] text-gray-300 uppercase font-mono border border-gray-700/50 shrink-0">
                                {{ $c->type }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">
                            {{ $c->description ?: 'No editorial description provided yet.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-[#252a36] flex items-center justify-between text-xs">
                        <span class="text-gray-400 font-mono text-[11px] flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>{{ $c->products_count }} Product(s)</span>
                        </span>

                        <div class="flex items-center space-x-2">
                            <button type="button" @click='openEdit(@json($c), @json($attachedProductIds))'
                                    class="px-2.5 py-1 rounded-lg bg-[#222733] hover:bg-[#2e3545] text-gray-200 hover:text-white text-[11px] font-semibold transition">
                                Edit
                            </button>
                            <a href="{{ route('collection.show', $c->slug) }}" target="_blank" 
                               class="px-2.5 py-1 rounded-lg border border-[#2d3444] hover:border-emerald-500 text-[#A3D9B1] text-[11px] font-semibold transition">
                                View ↗
                            </a>
                            <form method="POST" action="{{ route('admin.collections.destroy', $c->id) }}" onsubmit="return confirm('এই কালেকশনটি মুছে ফেলবেন?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-gray-500 hover:text-rose-400 transition" title="Delete Collection">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-[#161922] rounded-3xl border border-[#252a36] space-y-3">
                <span class="text-4xl">🏺</span>
                <h3 class="text-sm font-bold text-gray-300">কোনো কালেকশন পাওয়া যায়নি</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">
                    আপনার শপে এখনো কোনো কালেকশন তৈরি করা হয়নি। উপরের "+ Create Collection" বাটনে ক্লিক করে নতুন কালেকশন তৈরি করুন।
                </p>
                <button type="button" @click="createModal = true" class="mt-2 px-4 py-2 bg-[#214E34] text-white rounded-xl text-xs font-semibold">
                    নতুন কালেকশন যোগ করুন
                </button>
            </div>
        @endforelse
    </div>

    @if ($collections->hasPages())
        <div class="pt-4">{{ $collections->links() }}</div>
    @endif

    <!-- 1. CREATE COLLECTION MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm p-3 sm:p-6">
        <div class="min-h-full flex items-center justify-center py-6 sm:py-10">
            <div @click.away="createModal = false" class="relative bg-[#161922] border border-[#252a36] rounded-3xl max-w-2xl w-full max-h-[85vh] flex flex-col shadow-2xl overflow-hidden my-auto">
                <!-- Modal Header (Sticky) -->
                <div class="flex items-center justify-between px-6 sm:px-8 py-5 border-b border-[#252a36] bg-[#161922] shrink-0">
                    <div>
                        <h3 class="font-bold text-white text-base">Create Curated Collection (নতুন কালেকশন তৈরি করুন)</h3>
                        <p class="text-[11px] text-gray-400">ঐতিহ্যবাহী পণ্যের সমন্বয়ে নতুন একটি কালেকশন তৈরি করুন।</p>
                    </div>
                    <button type="button" @click="createModal = false" class="w-8 h-8 rounded-full bg-[#222733] hover:bg-gray-700 text-gray-300 hover:text-white flex items-center justify-center transition">✕</button>
                </div>

                <!-- Modal Form with Scrollable Body -->
                <form method="POST" action="{{ route('admin.collections.store') }}" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0 overflow-hidden text-xs"
                      x-data="{ autoSlug: '' }">
                    @csrf

                    <div class="overflow-y-auto p-6 sm:p-8 space-y-4 flex-1 min-h-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Collection Title (English) *</label>
                            <input type="text" name="title" required placeholder="e.g. Royal Jamdani Loom Heritage" 
                                   @input="autoSlug = generateSlug($event.target.value)"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white focus:border-[#214E34] focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Collection Title (Bangla / বাংলা)</label>
                            <input type="text" name="title_bn" placeholder="যেমন: রাজকীয় জামদানি তাঁত ঐতিহ্য"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white focus:border-[#214E34] focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Slug (URL কী)</label>
                        <input type="text" name="slug" :value="autoSlug" placeholder="e.g. royal-jamdani-loom"
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-gray-300 font-mono focus:border-[#214E34] focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Collection Type *</label>
                            <select name="type" required class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white focus:border-[#214E34] focus:outline-none">
                                <option value="manual">Manual Curated (হাতে বাছাইকৃত)</option>
                                <option value="seasonal">Seasonal Release (মৌসুমি উৎসব)</option>
                                <option value="featured">Featured Showcase (বিশেষ প্রদর্শনী)</option>
                                <option value="rule_based">Rule Based (স্বয়ংক্রিয় রুল)</option>
                            </select>
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="flex items-center space-x-2.5 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="rounded bg-[#0f1115] border-[#282d37] text-emerald-600 focus:ring-0">
                                <span class="text-gray-300 font-semibold">Mark as Featured on Homepage</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Description (English)</label>
                            <textarea name="description" rows="2" placeholder="Collection background and editorial thesis..." 
                                      class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2 text-white focus:border-[#214E34] focus:outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Description (Bangla / বাংলা)</label>
                            <textarea name="description_bn" rows="2" placeholder="কালেকশনের ইতিহাস ও প্রেক্ষাপট লিখুন..." 
                                      class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2 text-white focus:border-[#214E34] focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Cover Image URL</label>
                            <input type="url" name="cover_image" placeholder="https://.../cover.jpg" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white font-mono text-[11px] focus:border-[#214E34] focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Or Upload from Device</label>
                            <input type="file" name="cover_image_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-2 text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white">
                        </div>
                    </div>

                    <!-- Product Picker -->
                    <div class="space-y-2 pt-2 border-t border-[#252a36]" x-data="{ prodSearch: '' }">
                        <div class="flex items-center justify-between">
                            <label class="font-semibold text-gray-300">Assign Products to this Collection (পণ্য যুক্ত করুন)</label>
                            <input type="text" x-model="prodSearch" placeholder="Filter products..." 
                                   class="bg-[#0f1115] border border-[#282d37] rounded-lg px-2.5 py-1 text-[11px] text-white focus:outline-none w-44">
                        </div>
                        <div class="max-h-48 overflow-y-auto space-y-1.5 p-2 bg-[#0f1115] rounded-xl border border-[#282d37]">
                            @foreach($allProducts as $p)
                                <label x-show="!prodSearch || '{{ strtolower(addslashes($p->title)) }}'.includes(prodSearch.toLowerCase()) || '{{ strtolower($p->sku) }}'.includes(prodSearch.toLowerCase())"
                                       class="flex items-center justify-between p-2 rounded-lg hover:bg-[#1a1e28] cursor-pointer transition">
                                    <div class="flex items-center space-x-2.5">
                                        <input type="checkbox" name="product_ids[]" value="{{ $p->id }}" class="rounded bg-[#161922] border-[#282d37] text-emerald-600 focus:ring-0">
                                        <span class="text-white font-medium">{{ $p->title }}</span>
                                        <span class="text-[10px] font-mono text-gray-400">({{ $p->sku }})</span>
                                    </div>
                                    <span class="font-mono text-emerald-400 text-[11px]">৳{{ number_format($p->price, 0) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Footer (Sticky Bottom) -->
                <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-4 bg-[#12141a] border-t border-[#252a36] shrink-0">
                    <button type="button" @click="createModal = false" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white transition">
                        Cancel (বাতিল)
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2b6543] text-white font-bold transition shadow-lg shadow-[#214E34]/30">
                        Save Collection (সংরক্ষণ করুন)
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <!-- 2. EDIT COLLECTION MODAL -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm p-3 sm:p-6">
        <div class="min-h-full flex items-center justify-center py-6 sm:py-10">
            <div @click.away="editModal = false" class="relative bg-[#161922] border border-[#252a36] rounded-3xl max-w-2xl w-full max-h-[85vh] flex flex-col shadow-2xl overflow-hidden my-auto">
                <!-- Modal Header (Sticky) -->
                <div class="flex items-center justify-between px-6 sm:px-8 py-5 border-b border-[#252a36] bg-[#161922] shrink-0">
                    <div>
                        <h3 class="font-bold text-white text-base">Edit Collection (কালেকশন সম্পাদনা করুন)</h3>
                        <p class="text-[11px] text-gray-400">কালেকশনের নাম, ছবি ও সংযুক্ত পণ্যসমূহ হালনাগাদ করুন।</p>
                    </div>
                    <button type="button" @click="editModal = false" class="w-8 h-8 rounded-full bg-[#222733] hover:bg-gray-700 text-gray-300 hover:text-white flex items-center justify-center transition">✕</button>
                </div>

                <form :action="'{{ url('/admin/collections') }}/' + editCollection.id" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0 overflow-hidden text-xs">
                    @csrf
                    @method('PUT')

                    <div class="overflow-y-auto p-6 sm:p-8 space-y-4 flex-1 min-h-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Collection Title (English) *</label>
                            <input type="text" name="title" x-model="editCollection.title" required 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white focus:border-[#214E34] focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Collection Title (Bangla / বাংলা)</label>
                            <input type="text" name="title_bn" x-model="editCollection.title_bn" placeholder="যেমন: রাজকীয় জামদানি তাঁত ঐতিহ্য"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white focus:border-[#214E34] focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Slug</label>
                        <input type="text" name="slug" x-model="editCollection.slug" required
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-gray-300 font-mono focus:border-[#214E34] focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Collection Type *</label>
                            <select name="type" x-model="editCollection.type" required class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white focus:border-[#214E34] focus:outline-none">
                                <option value="manual">Manual Curated</option>
                                <option value="seasonal">Seasonal Release</option>
                                <option value="featured">Featured Showcase</option>
                                <option value="rule_based">Rule Based</option>
                            </select>
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="flex items-center space-x-2.5 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" :checked="editCollection.is_featured" class="rounded bg-[#0f1115] border-[#282d37] text-emerald-600 focus:ring-0">
                                <span class="text-gray-300 font-semibold">Mark as Featured on Homepage</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Description (English)</label>
                            <textarea name="description" x-model="editCollection.description" rows="2" 
                                      class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2 text-white focus:border-[#214E34] focus:outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Description (Bangla / বাংলা)</label>
                            <textarea name="description_bn" x-model="editCollection.description_bn" rows="2" 
                                      class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2 text-white focus:border-[#214E34] focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Cover Image URL</label>
                            <input type="url" name="cover_image" x-model="editCollection.cover_image" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3.5 py-2.5 text-white font-mono text-[11px] focus:border-[#214E34] focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-300 mb-1">Or Replace File</label>
                            <input type="file" name="cover_image_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-2 text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white">
                        </div>
                    </div>

                    <!-- Product Picker in Edit -->
                    <div class="space-y-2 pt-2 border-t border-[#252a36]" x-data="{ editProdSearch: '' }">
                        <div class="flex items-center justify-between">
                            <label class="font-semibold text-gray-300">Attached Products (সংযুক্ত পণ্য নির্বাচন করুন)</label>
                            <input type="text" x-model="editProdSearch" placeholder="Filter products..." 
                                   class="bg-[#0f1115] border border-[#282d37] rounded-lg px-2.5 py-1 text-[11px] text-white focus:outline-none w-44">
                        </div>
                        <div class="max-h-48 overflow-y-auto space-y-1.5 p-2 bg-[#0f1115] rounded-xl border border-[#282d37]">
                            @foreach($allProducts as $p)
                                <label x-show="!editProdSearch || '{{ strtolower(addslashes($p->title)) }}'.includes(editProdSearch.toLowerCase()) || '{{ strtolower($p->sku) }}'.includes(editProdSearch.toLowerCase())"
                                       class="flex items-center justify-between p-2 rounded-lg hover:bg-[#1a1e28] cursor-pointer transition">
                                    <div class="flex items-center space-x-2.5">
                                        <input type="checkbox" name="product_ids[]" value="{{ $p->id }}" 
                                               :checked="editCollection.product_ids.includes({{ $p->id }})"
                                               class="rounded bg-[#161922] border-[#282d37] text-emerald-600 focus:ring-0">
                                        <span class="text-white font-medium">{{ $p->title }}</span>
                                        <span class="text-[10px] font-mono text-gray-400">({{ $p->sku }})</span>
                                    </div>
                                    <span class="font-mono text-emerald-400 text-[11px]">৳{{ number_format($p->price, 0) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Footer (Sticky Bottom) -->
                <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-4 bg-[#12141a] border-t border-[#252a36] shrink-0">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white transition">
                        Cancel (বাতিল)
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2b6543] text-white font-bold transition shadow-lg shadow-[#214E34]/30">
                        Update Collection (হালনাগাদ করুন)
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

</div>
@endsection
