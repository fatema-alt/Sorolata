@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Artifacts & Heritage Catalog</h1>
            <p class="text-xs text-gray-400 mt-1">Manage Bengal artisanal masterpieces, provenance records, and live inventory.</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.create') }}" 
               class="px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#2a6342] text-white text-xs font-semibold shadow-sm transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Draft New Artifact</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            
            <!-- Search Bar -->
            <div class="relative w-full sm:w-72">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search title or SKU..." 
                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white pl-9 pr-3 py-2 focus:border-[#214E34] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Status Dropdown -->
            <select name="status" class="bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Live</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>

            <!-- District Dropdown -->
            <select name="district_id" class="bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                <option value="">All Craft Basins</option>
                @foreach ($districts as $d)
                    <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->name_en }} ({{ $d->gi_specialty ?? 'GI Craft' }})
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#222733] hover:bg-[#282f3e] text-xs font-medium text-gray-200 transition-colors">
                Filter
            </button>

            @if (request()->hasAny(['search', 'status', 'district_id']))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-gray-400 hover:text-white underline">
                    Clear Filters
                </a>
            @endif
        </form>

        <div class="text-xs text-gray-400 font-mono">
            Showing {{ $products->total() }} artifact(s)
        </div>
    </div>

    <!-- Product Grid Table -->
    <div class="rounded-2xl bg-[#161922] border border-[#252a36] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-[#141720]">
                        <th class="py-3 px-4">Artifact</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Provenance</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Inventory</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]">
                    @forelse ($products as $p)
                        <tr class="hover:bg-[#1a1e28] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#222733] border border-[#2d3444] overflow-hidden shrink-0 flex items-center justify-center text-gray-500 font-mono text-[10px]">
                                        @if ($p->media->first())
                                            <img src="{{ $p->media->first()->file_path }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            SRL
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', $p->id) }}" class="font-semibold text-white hover:text-[#A3D9B1] transition-colors line-clamp-1">
                                            {{ $p->title }}
                                        </a>
                                        <div class="text-[10px] text-gray-400">
                                            {{ $p->variants->count() }} Variant(s) • {{ $p->category?->name ?? 'General Guild' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-medium text-gray-300">
                                {{ $p->sku }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-gray-200">{{ $p->artisan?->full_name ?? 'Guild Atelier' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $p->district?->name_en ?? 'Bengal Basin' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-white">{{ $p->formatted_price }}</div>
                                @if ($p->has_discount)
                                    <div class="text-[10px] line-through text-gray-500">{{ $p->formatted_compare_price }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $p->available_stock <= 5 ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' }}">
                                    {{ $p->available_stock }} in stock
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $p->status === \App\Enums\ProductStatus::PUBLISHED ? 'bg-[#214E34]/20 text-[#A3D9B1]' : 'bg-gray-700/30 text-gray-300' }}">
                                    {{ $p->status->value }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('builder.product', $p->id) }}" 
                                       target="_blank"
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-[#222733] transition-colors"
                                       title="Open in Visual Page Builder Studio">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $p->id) }}" 
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-[#222733] transition-colors"
                                       title="Edit Artifact">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.duplicate', $p->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-[#222733] transition-colors"
                                                title="Duplicate Artifact">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                No artifacts matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="p-4 border-t border-[#252a36]">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
