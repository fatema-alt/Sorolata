@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ 
    showCreateModal: false, 
    showEditModal: false, 
    editDistrict: { id: null, name_en: '', name_bn: '', division: '', gi_specialty: '', gi_specialty_bn: '', river_basin: '', river_basin_bn: '', historical_narrative: '', historical_narrative_bn: '' } 
}">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Geographical Basins & GI Districts</h1>
            <p class="text-xs text-gray-400 mt-1">Geographical origin mappings, river basins, and certified craft specialties (ভৌগোলিক নির্দেশক ও কারুশিল্প হাব)।</p>
        </div>
        <div>
            <button type="button" @click="showCreateModal = true" class="px-4 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                <span class="text-base leading-none">+</span>
                <span>Add New District</span>
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-400">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button @click="$el.parentElement.remove()" class="text-rose-400/60 hover:text-rose-400">&times;</button>
        </div>
    @endif

    <!-- Districts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($districts as $d)
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] hover:border-[#214E34] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-base font-bold text-white group-hover:text-[#A3D9B1] transition-colors">{{ $d->name_en }}</h3>
                        <span class="text-xs text-[#A3D9B1] font-serif bg-[#214E34]/30 px-2 py-0.5 rounded-md">{{ $d->name_bn }}</span>
                    </div>

                    <div class="space-y-1.5 mt-3">
                        <div class="text-xs text-[#A3D9B1] font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#A3D9B1]"></span>
                            <span>{{ $d->gi_specialty ?? 'GI Certified Heritage Craft' }}</span>
                        </div>
                        @if(!empty($d->gi_specialty_bn))
                            <div class="text-[11px] text-gray-400 pl-3">{{ $d->gi_specialty_bn }}</div>
                        @endif

                        <div class="text-[11px] text-gray-400 font-mono mt-2 flex items-center gap-1">
                            <span class="text-gray-500">River Basin:</span>
                            <span class="text-gray-300">{{ $d->river_basin ?? 'Shitalakshya & Meghna' }}</span>
                        </div>

                        @if(!empty($d->division))
                            <div class="text-[10px] text-gray-500 font-mono">
                                Division: <span class="text-gray-400">{{ $d->division }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 pt-3 border-t border-[#252a36] flex items-center justify-between text-xs">
                    <span class="text-gray-400 text-[11px]">{{ $d->products_count }} Products • {{ $d->artisans_count }} Artisans</span>
                    
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="editDistrict = {
                                    id: {{ $d->id }},
                                    name_en: '{{ addslashes($d->name_en) }}',
                                    name_bn: '{{ addslashes($d->name_bn ?? '') }}',
                                    division: '{{ addslashes($d->division ?? '') }}',
                                    gi_specialty: '{{ addslashes($d->gi_specialty ?? '') }}',
                                    gi_specialty_bn: '{{ addslashes($d->gi_specialty_bn ?? '') }}',
                                    river_basin: '{{ addslashes($d->river_basin ?? '') }}',
                                    river_basin_bn: '{{ addslashes($d->river_basin_bn ?? '') }}',
                                    historical_narrative: '{{ addslashes(str_replace(["\r", "\n"], ' ', $d->historical_narrative ?? '')) }}',
                                    historical_narrative_bn: '{{ addslashes(str_replace(["\r", "\n"], ' ', $d->historical_narrative_bn ?? '')) }}'
                                }; showEditModal = true" 
                                class="text-gray-400 hover:text-white text-[11px] font-medium transition-colors cursor-pointer">
                            Edit
                        </button>

                        <a href="{{ route('district.show', $d->slug ?? $d->name_en) }}" target="_blank" class="text-[#A3D9B1] hover:underline font-semibold text-[11px]">
                            Basin Hub ↗
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 text-xs">No craft basins recorded yet. Click "Add New District" to add one.</div>
        @endforelse
    </div>

    @if ($districts->hasPages())
        <div>{{ $districts->links() }}</div>
    @endif

    <!-- Create District Modal -->
    <div x-show="showCreateModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showCreateModal = false" class="w-full max-w-xl bg-[#161922] border border-[#252a36] rounded-2xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Add New Geographical Basin / District</h3>
                <button type="button" @click="showCreateModal = false" class="text-gray-400 hover:text-white text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.districts.store') }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">District Name (English) *</label>
                        <input type="text" name="name_en" required placeholder="e.g. Rajshahi" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">District Name (বাংলা)</label>
                        <input type="text" name="name_bn" placeholder="যেমন: রাজশাহী" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">GI Craft Specialty (English)</label>
                        <input type="text" name="gi_specialty" placeholder="e.g. Pure Silk & Mulberry Sericulture" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">GI Craft Specialty (বাংলা)</label>
                        <input type="text" name="gi_specialty_bn" placeholder="যেমন: রাজশাহী রেশম ও খাঁটি সিল্ক বয়ন" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">River Basin & Climate (English)</label>
                        <input type="text" name="river_basin" placeholder="e.g. Padma River Basin" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Division (বিভাগ)</label>
                        <input type="text" name="division" placeholder="e.g. Rajshahi" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Historical Narrative & Cultural Lineage (English)</label>
                    <textarea rows="2" name="historical_narrative" placeholder="Write historical significance, soil minerals or craft heritage..." class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white p-3 focus:border-[#214E34] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-300 mb-1">ঐতিহাসিক পটভূমি ও কারুকাজ পরিচয় (বাংলা)</label>
                    <textarea rows="2" name="historical_narrative_bn" placeholder="এই জেলার কারুশিল্পের ঐতিহ্য ও ইতিহাস..." class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white p-3 focus:border-[#214E34] focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#252a36]">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white font-semibold">Save District</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit District Modal -->
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showEditModal = false" class="w-full max-w-xl bg-[#161922] border border-[#252a36] rounded-2xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Edit District: <span x-text="editDistrict.name_en"></span></h3>
                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-white text-lg">&times;</button>
            </div>

            <form method="POST" :action="'/admin/districts/' + editDistrict.id" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">District Name (English) *</label>
                        <input type="text" name="name_en" x-model="editDistrict.name_en" required class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">District Name (বাংলা)</label>
                        <input type="text" name="name_bn" x-model="editDistrict.name_bn" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">GI Craft Specialty (English)</label>
                        <input type="text" name="gi_specialty" x-model="editDistrict.gi_specialty" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">GI Craft Specialty (বাংলা)</label>
                        <input type="text" name="gi_specialty_bn" x-model="editDistrict.gi_specialty_bn" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">River Basin & Climate (English)</label>
                        <input type="text" name="river_basin" x-model="editDistrict.river_basin" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Division (বিভাগ)</label>
                        <input type="text" name="division" x-model="editDistrict.division" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Historical Narrative & Cultural Lineage (English)</label>
                    <textarea rows="2" name="historical_narrative" x-model="editDistrict.historical_narrative" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white p-3 focus:border-[#214E34] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-300 mb-1">ঐতিহাসিক পটভূমি ও কারুকাজ পরিচয় (বাংলা)</label>
                    <textarea rows="2" name="historical_narrative_bn" x-model="editDistrict.historical_narrative_bn" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white p-3 focus:border-[#214E34] focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-[#252a36]">
                    <div>
                        <button type="submit" 
                                form="delete-district-form" 
                                onclick="return confirm('Are you sure you want to delete this district?');" 
                                class="text-rose-400 hover:text-rose-300 text-xs cursor-pointer">
                            Delete District
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white font-semibold cursor-pointer">Update District</button>
                    </div>
                </div>
            </form>

            <form id="delete-district-form" method="POST" :action="'/admin/districts/' + editDistrict.id" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

</div>
@endsection
