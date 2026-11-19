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
        <button type="button" @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5">
            <span>Media & Gallery</span>
            <span class="px-1.5 py-0.2 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px]">{{ $product->media->count() }}</span>
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
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: GENERAL -->
        <div x-show="activeTab === 'general'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Artifact Title (EN)</label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Artifact Title (বাংলা / BN)</label>
                    <input type="text" name="title_bn" value="{{ old('title_bn', $product->title_bn) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Subtitle / Archival Hook (EN)</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Subtitle / Archival Hook (বাংলা / BN)</label>
                    <input type="text" name="subtitle_bn" value="{{ old('subtitle_bn', $product->subtitle_bn) }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Short Description (EN)</label>
                    <textarea name="short_description" rows="3" 
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Short Description (বাংলা / BN)</label>
                    <textarea name="short_description_bn" rows="3" 
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('short_description_bn', $product->short_description_bn) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Long Description / Editorial (EN)</label>
                    <textarea name="long_description" rows="4" 
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('long_description', $product->long_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Long Description / Editorial (বাংলা / BN)</label>
                    <textarea name="long_description_bn" rows="4" 
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('long_description_bn', $product->long_description_bn) }}</textarea>
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

        <!-- TAB: MEDIA & GALLERY -->
        <div x-show="activeTab === 'media'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-6">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-white flex items-center gap-2">
                    <span>📸</span> Artifact Photography & Media Assets
                </h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Manage existing imagery or upload new high-resolution product photos.</p>
            </div>

            <!-- Existing Photos Gallery -->
            <div class="space-y-3">
                <div class="text-xs font-semibold text-gray-300 flex items-center justify-between">
                    <span>Current Gallery Images</span>
                    <span class="text-[11px] text-gray-500 font-mono">{{ $product->media->count() }} photo(s) attached</span>
                </div>

                @if($product->media->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        @foreach($product->media as $mediaItem)
                            <div class="relative group aspect-square rounded-2xl overflow-hidden border border-[#282d37] bg-[#0f1115] shadow-xs">
                                <img src="{{ $mediaItem->file_path }}" alt="{{ $mediaItem->alt_text }}" class="w-full h-full object-cover">
                                @if($loop->first)
                                    <span class="absolute top-1.5 left-1.5 bg-[#214E34] text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                        Primary Hero
                                    </span>
                                @endif
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2">
                                    <button 
                                        type="submit" 
                                        formaction="{{ route('admin.products.media.destroy', ['id' => $product->id, 'mediaId' => $mediaItem->id]) }}"
                                        formmethod="POST"
                                        onclick="return confirm('Remove this image from artifact?')"
                                        class="px-2.5 py-1.5 rounded-lg bg-red-600/90 hover:bg-red-600 text-white text-[11px] font-semibold flex items-center gap-1 shadow-xs transition-transform transform hover:scale-105"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 rounded-xl border border-[#282d37] bg-[#0f1115] text-center text-gray-500 text-xs">
                        No photos currently attached to this artifact. Use the uploader below to add images.
                    </div>
                @endif
            </div>

            <!-- Upload New Photos Section -->
            <div class="pt-5 border-t border-[#252a36] space-y-4" x-data="{
                newPreviews: [],
                handleNewFiles(event) {
                    const files = event.target.files;
                    this.newPreviews = [];
                    for (let i = 0; i < files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.newPreviews.push({ name: files[i].name, src: e.target.result });
                        };
                        reader.readAsDataURL(files[i]);
                    }
                }
            }">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-semibold text-gray-200">Upload Additional Photos</label>
                    <span class="text-[10px] text-gray-500 font-mono">JPG, PNG, WEBP • Max 10MB</span>
                </div>

                <!-- Dropzone -->
                <div class="relative border-2 border-dashed border-[#282d37] hover:border-[#214E34] transition-colors rounded-xl p-6 text-center bg-[#0f1115] group cursor-pointer">
                    <input 
                        type="file" 
                        name="images[]" 
                        id="edit_product_images" 
                        multiple 
                        accept="image/*"
                        @change="handleNewFiles($event)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    >
                    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                        <div class="w-10 h-10 rounded-full bg-[#1b202c] border border-[#2b3344] flex items-center justify-center text-gray-300 group-hover:text-emerald-400 group-hover:scale-110 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-xs text-gray-300">
                            <span class="text-[#34D399] font-bold">Choose Photos</span> or drag & drop files to add
                        </div>
                    </div>
                </div>

                <!-- New Previews -->
                <template x-if="newPreviews.length > 0">
                    <div class="space-y-2">
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <span>Ready to Upload on Save:</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold" x-text="newPreviews.length"></span>
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                            <template x-for="(p, index) in newPreviews" :key="index">
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-[#282d37] bg-[#161922]">
                                    <img :src="p.src" class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Direct Image URL Field -->
                <div class="pt-2">
                    <label class="block text-[11px] text-gray-400 mb-1">Or add an external Image URL (Optional):</label>
                    <input 
                        type="url" 
                        name="image_url" 
                        placeholder="https://images.unsplash.com/photo-..."
                        class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2 focus:border-[#214E34] focus:outline-none font-mono"
                    >
                </div>
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
