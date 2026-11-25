@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto"
     x-data="{
         modalOpen: false,
         isEdit: false,
         formAction: '{{ route('admin.artisans.store') }}',
         form: {
             id: '',
             full_name: '',
             full_name_bn: '',
             atelier_name: '',
             atelier_name_bn: '',
             district_id: '',
             craft_type: 'jamdani_weaving',
             years_of_mastery: 25,
             quote: '',
             quote_bn: '',
             biography: '',
             biography_bn: '',
             portrait_url: '',
             awards_text: '',
             is_verified: true
         },
         openCreate() {
             this.isEdit = false;
             this.formAction = '{{ route('admin.artisans.store') }}';
             this.form = {
                 id: '',
                 full_name: '',
                 full_name_bn: '',
                 atelier_name: '',
                 atelier_name_bn: '',
                 district_id: '{{ $districts->first()?->id ?? '' }}',
                 craft_type: '{{ $craftTypes[0]->value ?? 'jamdani_weaving' }}',
                 years_of_mastery: 25,
                 quote: '',
                 quote_bn: '',
                 biography: '',
                 biography_bn: '',
                 portrait_url: '',
                 awards_text: '',
                 is_verified: true
             };
             this.modalOpen = true;
         },
         openEdit(a) {
             this.isEdit = true;
             this.formAction = '{{ url('/admin/artisans') }}/' + a.id;
             this.form = Object.assign({}, a);
             this.modalOpen = true;
         }
     }">

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Artisan Guild Masters & Ateliers</h1>
            <p class="text-xs text-gray-400 mt-1">Directory of living cultural treasures, generational looms, and individual artisan page editor.</p>
        </div>
        <button type="button" @click="openCreate()" class="px-4 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#193b27] text-white text-xs font-semibold transition-all cursor-pointer">
            + Add Master Artisan
        </button>
    </div>

    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3.5 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-xs font-medium">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-3.5 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-xs space-y-1">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($artisans as $artisan)
            @php
                $artisanPayload = [
                    'id' => $artisan->id,
                    'full_name' => $artisan->full_name ?? '',
                    'full_name_bn' => $artisan->full_name_bn ?? '',
                    'atelier_name' => $artisan->atelier_name ?? '',
                    'atelier_name_bn' => $artisan->atelier_name_bn ?? '',
                    'district_id' => $artisan->district_id ?? '',
                    'craft_type' => is_object($artisan->craft_type) ? $artisan->craft_type->value : ($artisan->craft_type ?? ''),
                    'years_of_mastery' => $artisan->years_of_mastery ?? 0,
                    'quote' => $artisan->quote ?? '',
                    'quote_bn' => $artisan->quote_bn ?? '',
                    'biography' => $artisan->biography ?? '',
                    'biography_bn' => $artisan->biography_bn ?? '',
                    'portrait_url' => $artisan->portrait_url ?? '',
                    'awards_text' => is_array($artisan->awards) ? implode("\n", $artisan->awards) : '',
                    'is_verified' => (bool) $artisan->is_verified,
                ];
            @endphp
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] hover:border-[#214E34] transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-[#222733] overflow-hidden shrink-0">
                            @if ($artisan->portrait_url)
                                <img src="{{ $artisan->portrait_url }}" alt="{{ $artisan->full_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-bold text-white">{{ substr($artisan->full_name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-white truncate">{{ $artisan->full_name }}</h3>
                            <div class="text-[11px] text-[#A3D9B1] truncate">{{ $artisan->atelier_name }}</div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-300 italic line-clamp-2">"{{ $artisan->quote }}"</p>
                    <div class="text-[11px] text-gray-400 font-mono">
                        {{ $artisan->years_of_mastery }} Years Mastery • {{ $artisan->district?->name_en }}
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#252a36] flex items-center justify-between gap-2 text-xs">
                    <span class="text-gray-400">{{ $artisan->products_count }} Artifacts Woven</span>
                    <div class="flex items-center gap-3">
                        <button type="button"
                                @click='openEdit(@json($artisanPayload))'
                                class="px-3 py-1.5 rounded-lg bg-[#222733] hover:bg-[#214E34] text-white font-semibold transition-colors cursor-pointer">
                            Edit Page
                        </button>
                        <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" target="_blank" class="text-[#A3D9B1] hover:underline font-semibold">
                            Public Profile ↗
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 text-xs">No artisans registered yet.</div>
        @endforelse
    </div>

    @if ($artisans->hasPages())
        <div>{{ $artisans->links() }}</div>
    @endif

    <!-- Individual Artisan Page Editor Modal -->
    <div x-show="modalOpen" x-cloak @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 overflow-y-auto">
        <div @click.away="modalOpen = false" class="w-full max-w-2xl bg-[#161922] border border-[#282d37] rounded-2xl shadow-2xl overflow-hidden my-8">
            <div class="px-6 py-4 border-b border-[#282d37] flex items-center justify-between bg-[#12141c]">
                <h2 class="text-base font-bold text-white" x-text="isEdit ? 'Edit Artisan Page: ' + form.full_name : 'Add New Master Artisan'"></h2>
                <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-white text-lg">✕</button>
            </div>

            <form :action="formAction" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[78vh] overflow-y-auto">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Full Name (EN) *</label>
                        <input type="text" name="full_name" required x-model="form.full_name" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Full Name (BN)</label>
                        <input type="text" name="full_name_bn" x-model="form.full_name_bn" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Atelier / Guild Name (EN)</label>
                        <input type="text" name="atelier_name" x-model="form.atelier_name" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Atelier / Guild Name (BN)</label>
                        <input type="text" name="atelier_name_bn" x-model="form.atelier_name_bn" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">District</label>
                        <select name="district_id" x-model="form.district_id" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                            <option value="">-- Select District --</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Craft Type</label>
                        <select name="craft_type" x-model="form.craft_type" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                            @foreach($craftTypes as $ct)
                                <option value="{{ $ct->value }}">{{ $ct->title() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Years of Mastery</label>
                        <input type="number" name="years_of_mastery" min="0" max="100" x-model="form.years_of_mastery" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-[#0f1117] border border-[#282d37] space-y-2">
                    <label class="block text-[11px] font-semibold text-gray-300">Portrait Photo (Upload new or paste URL)</label>
                    <div class="flex items-center gap-3">
                        <template x-if="form.portrait_url">
                            <img :src="form.portrait_url" alt="" class="w-12 h-12 rounded-xl object-cover border border-[#282d37] shrink-0">
                        </template>
                        <div class="flex-1 space-y-2">
                            <input type="file" name="portrait_file" accept="image/*" class="block w-full text-xs text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-[#222733] file:text-white">
                            <input type="text" name="portrait_url" placeholder="Or Portrait Image URL" x-model="form.portrait_url" class="w-full rounded-lg bg-[#161922] border border-[#282d37] px-3 py-1.5 text-xs text-white">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Quote (EN)</label>
                        <textarea name="quote" rows="2" x-model="form.quote" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Quote (BN)</label>
                        <textarea name="quote_bn" rows="2" x-model="form.quote_bn" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Biography (EN)</label>
                        <textarea name="biography" rows="4" x-model="form.biography" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Biography (BN)</label>
                        <textarea name="biography_bn" rows="4" x-model="form.biography_bn" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Honors & Awards (one per line)</label>
                    <textarea name="awards_text" rows="2" x-model="form.awards_text" class="w-full rounded-lg bg-[#0f1117] border border-[#282d37] px-3 py-2 text-xs text-white"></textarea>
                </div>

                <label class="inline-flex items-center gap-2 text-xs text-gray-300 cursor-pointer">
                    <input type="checkbox" name="is_verified" value="1" x-model="form.is_verified" class="rounded bg-[#0f1117] border-[#282d37] text-[#214E34]">
                    <span>Verified Master Artisan (shown on storefront)</span>
                </label>

                <div class="pt-4 border-t border-[#282d37] flex items-center justify-between">
                    <div>
                        <template x-if="isEdit">
                            <button type="button"
                                    @click="if(confirm('Delete this artisan?')) { document.getElementById('delete-artisan-form').action = formAction; document.getElementById('delete-artisan-form').submit(); }"
                                    class="px-3.5 py-2 rounded-lg bg-red-500/15 hover:bg-red-500/25 text-red-300 text-xs font-semibold cursor-pointer">
                                Delete Artisan
                            </button>
                        </template>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-lg bg-[#222733] text-gray-300 text-xs font-semibold cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#214E34] hover:bg-[#193b27] text-white text-xs font-semibold cursor-pointer">Save Changes</button>
                    </div>
                </div>
            </form>
            <form id="delete-artisan-form" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

</div>
@endsection
