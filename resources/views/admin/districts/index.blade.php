@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <div>
        <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Geographical Basins & GI Districts</h1>
        <p class="text-xs text-gray-400 mt-1">Geographical origin mappings, river basins, and certified craft specialties.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($districts as $d)
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] hover:border-[#214E34] transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-white">{{ $d->name_en }}</h3>
                        <span class="text-xs text-gray-400 font-serif">{{ $d->name_bn }}</span>
                    </div>
                    <div class="text-[11px] text-[#A3D9B1] font-semibold">{{ $d->gi_specialty ?? 'GI Certified Guild' }}</div>
                    <div class="text-[11px] text-gray-400 font-mono mt-1">River Basin: {{ $d->river_basin ?? 'Shitalakshya' }}</div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#252a36] flex items-center justify-between text-xs">
                    <span class="text-gray-400">{{ $d->products_count }} Products • {{ $d->artisans_count }} Artisans</span>
                    <a href="{{ route('district.show', $d->slug ?? $d->name_en) }}" target="_blank" class="text-[#A3D9B1] hover:underline font-semibold">
                        Basin Hub ↗
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 text-xs">No craft basins recorded yet.</div>
        @endforelse
    </div>

    @if ($districts->hasPages())
        <div>{{ $districts->links() }}</div>
    @endif

</div>
@endsection
