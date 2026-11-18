@extends('layouts.admin')

@section('title', 'Editorial Stories & Gazette')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#282d37]">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-2xl font-semibold text-white tracking-tight">Editorial Stories & Gazette</h1>
                <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 rounded-full bg-[#C96A3D]/20 text-[#DE7D4E] border border-[#C96A3D]/40 font-semibold">
                    Shoppable CMS
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Publish chronicles, embed shoppable heirloom products, and manage bilingual heritage dispatches.</p>
        </div>

        <a href="{{ route('admin.stories.create') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#1a3f2a] text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Compose New Story</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex items-center justify-between gap-4 bg-[#1a1d24] p-4 rounded-2xl border border-[#282d37]">
        <form method="GET" action="{{ route('admin.stories.index') }}" class="flex-1 max-w-md flex items-center space-x-2">
            <div class="relative w-full">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search stories by title (English/বাংলা) or category..." 
                       class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#214E34]">
            </div>
            <button type="submit" class="px-3.5 py-2 bg-[#222731] hover:bg-[#282d37] border border-[#282d37] rounded-xl text-xs text-gray-300">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.stories.index') }}" class="text-xs text-gray-400 hover:text-white">Clear</a>
            @endif
        </form>
    </div>

    <!-- Stories Table -->
    <div class="bg-[#1a1d24] border border-[#282d37] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-[#12141a] border-b border-[#282d37] text-[10px] uppercase font-bold tracking-widest text-gray-400">
                    <tr>
                        <th class="py-3.5 px-4">Story & Title</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Shoppable Pieces</th>
                        <th class="py-3.5 px-4">Reading Time</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#282d37]">
                    @forelse($stories as $story)
                        <tr class="hover:bg-[#222731]/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-lg bg-[#12141a] overflow-hidden shrink-0 border border-[#282d37]">
                                        <img src="{{ $story->hero_image ?? 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=200' }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.stories.edit', $story->id) }}" class="font-medium text-white hover:text-[#A3D9B1] text-sm block">
                                            {{ $story->title }}
                                        </a>
                                        @if($story->title_bn)
                                            <p class="text-xs text-[#DE7D4E] mt-0.5">{{ $story->title_bn }}</p>
                                        @endif
                                        <span class="text-[10px] text-gray-500 font-mono mt-0.5 block">/stories/{{ $story->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full bg-[#222731] border border-[#282d37] text-gray-300">
                                    {{ $story->category }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($story->products_count > 0)
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#214E34]/30 text-[#A3D9B1] border border-[#214E34]/50">
                                        <span>🛍️ {{ $story->products_count }} Products</span>
                                    </span>
                                @else
                                    <span class="text-gray-500 text-[11px]">No products</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-gray-400">{{ $story->reading_time }} min read</span>
                            </td>
                            <td class="py-3 px-4">
                                @if($story->is_published)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('stories.show', $story->slug) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-[#282d37]" title="Preview Live">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.stories.edit', $story->id) }}" class="p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-[#282d37]" title="Edit Story">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.stories.destroy', $story->id) }}" onsubmit="return confirm('Are you sure you want to delete this story?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-400 hover:text-red-300 rounded-lg hover:bg-[#282d37]" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                No stories found. Click "Compose New Story" to create your first dispatch!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stories->hasPages())
            <div class="p-4 border-t border-[#282d37]">
                {{ $stories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
