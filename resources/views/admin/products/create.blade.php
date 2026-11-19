@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    
    <div class="pb-4 border-b border-[#252a36]">
        <div class="flex items-center space-x-2 text-xs text-gray-400 mb-1">
            <a href="{{ route('admin.products.index') }}" class="hover:text-white">Artifacts</a>
            <span>/</span>
            <span class="text-white">New Piece</span>
        </div>
        <h1 class="font-editorial-heading text-3xl text-white font-normal">Draft New Heritage Artifact</h1>
        <p class="text-xs text-gray-400 mt-1">Initiate a new catalog record for a master artisan's work.</p>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-6">
        @csrf

        <div class="space-y-5">
            <!-- 1. Titles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Artifact Title (EN)</label>
                    <input type="text" name="title" value="{{ old('title') }}" required 
                           placeholder="e.g. Royal Sonargaon Khadi Shawl"
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Artifact Title (বাংলা / BN)</label>
                    <input type="text" name="title_bn" value="{{ old('title_bn') }}" 
                           placeholder="যেমনঃ রাজকীয় সোনারগাঁও খাদি শাল"
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>

            <!-- 2. Pricing & Stock -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Unique SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', 'SRL-' . strtoupper(\Illuminate\Support\Str::random(6))) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Collector Price (৳ BDT)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required 
                           placeholder="18500"
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Initial Stock Intake</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 5) }}" required 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>

            <!-- 3. Master Artisan & District -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Master Artisan Guild</label>
                    <select name="artisan_id" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                        <option value="">Select Master Artisan</option>
                        @foreach ($artisans as $a)
                            <option value="{{ $a->id }}">{{ $a->full_name }} ({{ $a->atelier_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Craft Basin / District</label>
                    <select name="district_id" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">
                        <option value="">Select Geographical Basin</option>
                        @foreach ($districts as $d)
                            <option value="{{ $d->id }}">{{ $d->name_en }} ({{ $d->gi_specialty ?? 'GI Certified' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 4. Image Upload & Gallery Showcase -->
            <div class="p-5 rounded-2xl bg-[#0f1115] border border-[#282d37] space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold text-white flex items-center gap-1.5">
                            <span class="text-emerald-400">📸</span> Artifact Photography & Imagery
                        </label>
                        <p class="text-[11px] text-gray-400 mt-0.5">Upload product photos. The first image will be used as the primary showcase artifact.</p>
                    </div>
                    <span class="text-[10px] text-gray-500 font-mono">JPG, PNG, WEBP • Max 10MB</span>
                </div>

                <div x-data="{
                    previews: [],
                    handleFiles(event) {
                        const files = event.target.files;
                        this.previews = [];
                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push({ name: files[i].name, src: e.target.result });
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                }" class="space-y-4">
                    
                    <!-- Upload Dropzone -->
                    <div class="relative border-2 border-dashed border-[#282d37] hover:border-[#214E34] transition-colors rounded-xl p-6 text-center bg-[#141720]/70 group cursor-pointer">
                        <input 
                            type="file" 
                            name="images[]" 
                            id="product_images" 
                            multiple 
                            accept="image/*"
                            @change="handleFiles($event)"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        >
                        <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                            <div class="w-10 h-10 rounded-full bg-[#1b202c] border border-[#2b3344] flex items-center justify-center text-gray-300 group-hover:text-emerald-400 group-hover:scale-110 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-xs text-gray-300">
                                <span class="text-[#34D399] font-bold">Choose Photos</span> or drag & drop files here
                            </div>
                            <div class="text-[11px] text-gray-500">
                                Select one or multiple images
                            </div>
                        </div>
                    </div>

                    <!-- Live Image Previews -->
                    <template x-if="previews.length > 0">
                        <div class="space-y-2">
                            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                <span>Selected Images:</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold" x-text="previews.length"></span>
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                                <template x-for="(p, index) in previews" :key="index">
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-[#282d37] bg-[#161922]">
                                        <img :src="p.src" class="w-full h-full object-cover">
                                        <span x-show="index === 0" class="absolute top-1 left-1 bg-[#214E34] text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            Primary
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Direct Image URL Alternative -->
                    <div class="pt-2 border-t border-[#252a36]">
                        <label class="block text-[11px] text-gray-400 mb-1">Or paste external Image URL (Optional):</label>
                        <input 
                            type="url" 
                            name="image_url" 
                            value="{{ old('image_url') }}" 
                            placeholder="https://images.unsplash.com/photo-..."
                            class="w-full bg-[#141720] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2 focus:border-[#214E34] focus:outline-none font-mono"
                        >
                    </div>
                </div>
            </div>

            <!-- 5. Editorial & Provenance Short Story -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Short Story / Provenance Introduction (EN)</label>
                    <textarea name="short_description" rows="3" 
                              placeholder="Brief editorial introducing the craft traditions and materials..."
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('short_description') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Short Story / Provenance Introduction (বাংলা / BN)</label>
                    <textarea name="short_description_bn" rows="3" 
                              placeholder="ঐতিহ্যবাহী কারুশিল্প ও উপাদানের সংক্ষিপ্ত পরিচিতি..."
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none">{{ old('short_description_bn') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#252a36]">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl border border-[#2d3444] text-xs font-medium text-gray-300 hover:bg-[#222733] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all">
                Create Artifact Record
            </button>
        </div>

    </form>

</div>
@endsection
