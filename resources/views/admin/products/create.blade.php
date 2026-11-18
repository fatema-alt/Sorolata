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

    <form method="POST" action="{{ route('admin.products.store') }}" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
        @csrf

        <div class="space-y-4">
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
