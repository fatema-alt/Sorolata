@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <div>
        <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Artisan Guild Masters & Ateliers</h1>
        <p class="text-xs text-gray-400 mt-1">Directory of living cultural treasures, generational looms, and master craftspeople.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($artisans as $artisan)
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] hover:border-[#214E34] transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-[#222733] overflow-hidden shrink-0">
                            @if ($artisan->portrait_image)
                                <img src="{{ $artisan->portrait_image }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-bold text-white">{{ substr($artisan->full_name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">{{ $artisan->full_name }}</h3>
                            <div class="text-[11px] text-[#A3D9B1]">{{ $artisan->atelier_name }}</div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-300 italic line-clamp-2">"{{ $artisan->quote }}"</p>
                    <div class="text-[11px] text-gray-400 font-mono">
                        {{ $artisan->years_of_mastery }} Years Mastery • {{ $artisan->district?->name_en }}
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#252a36] flex items-center justify-between text-xs">
                    <span class="text-gray-400">{{ $artisan->products_count }} Artifacts Woven</span>
                    <a href="{{ route('artisan.show', $artisan->slug ?? $artisan->uuid) }}" target="_blank" class="text-[#A3D9B1] hover:underline font-semibold">
                        Public Profile ↗
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 text-xs">No artisans registered yet.</div>
        @endforelse
    </div>

    @if ($artisans->hasPages())
        <div>{{ $artisans->links() }}</div>
    @endif

</div>
@endsection
