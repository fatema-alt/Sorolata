@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" x-data="{ activeTab: 'general' }">
    
    <!-- Header with Breadcrumb & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#252a36]">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-white">Artifacts</a>
                <span>/</span>
                <span class="text-white">{{ $product->sku }}</span>
            </div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal">{{ $product->title }}</h1>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('builder.product', $product->id) }}" 
               target="_blank"
               class="px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span>Launch Visual Page Builder</span>
            </a>

            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Archive this artifact?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2 rounded-xl border border-red-900/60 hover:bg-red-950/40 text-xs font-medium text-red-400 transition-colors">
                    Archive
                </button>
            </form>
        </div>
    </div>

    <!-- Progressive Disclosure Tabs -->
    <div class="flex border-b border-[#252a36] space-x-2 bg-[#141720] p-1.5 rounded-2xl text-xs font-medium text-gray-400 overflow-x-auto">
        <button type="button" @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all">
            General
        </button>
        <button type="button" @click="activeTab = 'pricing'" :class="activeTab === 'pricing' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all">
            Pricing
        </button>
        <button type="button" @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all">
            Inventory & Stock
        </button>
        <button type="button" @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all">
            Shipping & Logistics
        </button>
        <button type="button" @click="activeTab = 'heritage'" :class="activeTab === 'heritage' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all">
            Provenance & Artisan
        </button>
        <button type="button" @click="activeTab = 'variants'" :class="activeTab === 'variants' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all">
            Variants ({{ $product->variants->count() }})
        </button>
    </div>

    <!-- Tabbed Edit Form -->
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: GENERAL -->
        <div x-show="activeTab === 'general'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Artifact Title</label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Subtitle / Archival Hook</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Guild Category</label>
                    <select name="category_id" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                        <option value="">Uncategorized</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Publication Status</label>
                    <select name="status" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                        <option value="published" {{ $product->status->value === 'published' ? 'selected' : '' }}>Published Live</option>
                        <option value="draft" {{ $product->status->value === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- TAB 2: PRICING -->
        <div x-show="activeTab === 'pricing'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Collector Price (৳ BDT)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Promotional Sale Price (৳)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Compare-at Price (৳)</label>
                    <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- TAB 3: INVENTORY -->
        <div x-show="activeTab === 'inventory'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Standardized SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Available Units in Atelier</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- TAB 4: SHIPPING & LOGISTICS -->
        <div x-show="activeTab === 'shipping'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Physical Weight (kg)</label>
                    <input type="number" step="0.001" name="weight" value="{{ old('weight', $product->weight ?? 0.5) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Shipping Class</label>
                    <input type="text" name="shipping_class" value="{{ old('shipping_class', $product->shipping_class ?? 'standard') }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Estimated Dispatch</label>
                    <input type="text" name="delivery_estimate" value="{{ old('delivery_estimate', $product->delivery_estimate ?? '2-4 Days') }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- TAB 5: HERITAGE & PROVENANCE -->
        <div x-show="activeTab === 'heritage'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Master Artisan Guild</label>
                    <select name="artisan_id" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                        <option value="">None Assigned</option>
                        @foreach ($artisans as $a)
                            <option value="{{ $a->id }}" {{ $product->artisan_id == $a->id ? 'selected' : '' }}>
                                {{ $a->full_name }} ({{ $a->atelier_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Geographical Origin / Basin</label>
                    <select name="district_id" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                        <option value="">None Assigned</option>
                        @foreach ($districts as $d)
                            <option value="{{ $d->id }}" {{ $product->district_id == $d->id ? 'selected' : '' }}>
                                {{ $d->name_en }} ({{ $d->gi_specialty ?? 'GI Certified' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Handmade Duration</label>
                <input type="text" name="handmade_duration" value="{{ old('handmade_duration', $product->handmade_duration) }}" 
                       placeholder="e.g. 28 Days On Handloom"
                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Care & Preservation Instructions</label>
                <textarea name="care_instructions" rows="3" 
                          class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('care_instructions', $product->care_instructions) }}</textarea>
            </div>
        </div>

        <!-- TAB 6: VARIANTS -->
        <div x-show="activeTab === 'variants'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Configured Product Variants</h3>
                    <p class="text-[11px] text-gray-400">Independent SKUs with custom pricing, color, and size options.</p>
                </div>
            </div>

            <div class="space-y-2">
                @forelse ($product->variants as $variant)
                    <div class="p-3 rounded-xl bg-[#1c202b] border border-[#252a36] flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-white">{{ $variant->title }}</div>
                            <div class="text-[10px] text-gray-400 font-mono">SKU: {{ $variant->sku }} • Weight: {{ $variant->weight ?? 0.5 }}kg</div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-xs font-bold text-white">{{ $variant->formatted_price }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 font-mono">
                                {{ $variant->stock_quantity }} in stock
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500 text-xs">
                        No sub-variants configured for this artifact. The primary SKU is used.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Save Button Bar -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#252a36]">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl border border-[#2d3444] text-xs font-medium text-gray-300 hover:bg-[#222733] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all">
                Save Changes
            </button>
        </div>

    </form>

</div>
@endsection
