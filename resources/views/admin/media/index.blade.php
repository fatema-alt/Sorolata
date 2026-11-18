@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ copySuccess: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Archival Media Library</h1>
            <p class="text-xs text-gray-400 mt-1">High-definition artisan portraits, macro weave photography, and craft certificates.</p>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse ($assets as $asset)
            <div class="group rounded-2xl bg-[#161922] border border-[#252a36] hover:border-[#214E34] overflow-hidden transition-all flex flex-col justify-between">
                <div class="aspect-square bg-[#0f1115] relative overflow-hidden flex items-center justify-center">
                    <img src="{{ $asset->file_path }}" alt="{{ $asset->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2">
                        <button type="button" 
                                @click="navigator.clipboard.writeText('{{ $asset->file_path }}'); copySuccess = true; setTimeout(() => copySuccess = false, 1500)"
                                class="px-2.5 py-1 rounded bg-[#214E34] text-[10px] font-semibold text-white">
                            Copy Path
                        </button>
                    </div>
                </div>
                <div class="p-2.5 text-[10px]">
                    <div class="font-medium text-gray-200 truncate">{{ basename($asset->file_path) }}</div>
                    <div class="text-gray-500 font-mono mt-0.5">{{ number_format($asset->file_size / 1024, 0) }} KB • {{ $asset->aspect_ratio }}</div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-gray-500 text-xs">
                No media assets indexed in storage yet.
            </div>
        @endforelse
    </div>

    @if ($assets->hasPages())
        <div>{{ $assets->links() }}</div>
    @endif

</div>
@endsection
