@extends('layouts.admin')

@section('title', 'Page Builder Studio & CMS Pages')

@section('content')
<div x-data="{ showCreateModal: false }" class="space-y-6">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#282d37]">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-2xl font-semibold text-white tracking-tight">Visual Page Builder & Pages Studio</h1>
                <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 rounded-full bg-[#214E34]/30 text-[#A3D9B1] border border-[#214E34]/50 font-semibold">
                    Dynamic Engine
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Design, rearrange sections, and visually customize the Homepage, About Us, Contact, and marketing landing pages.</p>
        </div>

        <button type="button" 
                @click="showCreateModal = true"
                class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#1a3f2a] text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Create New Page</span>
        </button>
    </div>

    <!-- Product Builder Quick Banner -->
    <div class="bg-gradient-to-r from-[#1c221e] to-[#1a1d24] border border-[#214E34]/40 p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#214E34]/20 border border-[#214E34]/40 flex items-center justify-center text-[#A3D9B1] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-semibold text-white">Visual Product Landing Page Builder</h4>
                <p class="text-[11px] text-gray-400">Every individual handcrafted product in the store has its own dedicated visual page builder.</p>
            </div>
        </div>
        <a href="{{ route('admin.products.index') }}" class="shrink-0 px-3.5 py-1.5 rounded-lg border border-[#214E34] hover:bg-[#214E34]/20 text-xs font-medium text-[#A3D9B1] transition-colors">
            Browse Products & Launch Builder →
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#1a1d24] p-4 rounded-2xl border border-[#282d37]">
        <form method="GET" action="{{ route('admin.pages.index') }}" class="w-full sm:max-w-md flex items-center space-x-2">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search pages by title or slug..." 
                   class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#214E34]">
            <button type="submit" class="px-3.5 py-2 bg-[#222731] hover:bg-[#282d37] border border-[#282d37] rounded-xl text-xs text-gray-300">
                Filter
            </button>
            @if(request('search'))
                <a href="{{ route('admin.pages.index') }}" class="text-xs text-gray-400 hover:text-white">Clear</a>
            @endif
        </form>

        <div class="flex items-center space-x-2 text-xs text-gray-400">
            <span>Total Pages: <strong class="text-white">{{ $pages->total() }}</strong></span>
        </div>
    </div>

    <!-- Pages List Table -->
    <div class="bg-[#1a1d24] border border-[#282d37] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-[#12141a] border-b border-[#282d37] text-[10px] uppercase font-bold tracking-widest text-gray-400">
                    <tr>
                        <th class="py-3.5 px-4">Page Title & Path</th>
                        <th class="py-3.5 px-4">Page Type</th>
                        <th class="py-3.5 px-4">Builder Sections</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Last Modified</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#282d37]">
                    @forelse($pages as $page)
                        @php
                            $sectionsCount = count($page->layout_json['sections'] ?? []);
                            $isCore = in_array($page->slug, ['home', 'about', 'contact'], true);
                            $liveUrl = match($page->slug) {
                                'home' => url('/'),
                                'about' => route('pages.about'),
                                'contact' => route('pages.contact'),
                                default => route('builder.page.show', $page->slug),
                            };
                        @endphp
                        <tr class="hover:bg-[#222731]/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-lg bg-[#12141a] border border-[#282d37] flex items-center justify-center text-gray-400 shrink-0">
                                        @if($page->slug === 'home')
                                            🏠
                                        @elseif($page->slug === 'about')
                                            📖
                                        @elseif($page->slug === 'contact')
                                            ✉️
                                        @else
                                            📄
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('builder.page', $page->id) }}" class="font-medium text-white hover:text-[#A3D9B1] text-sm">
                                                {{ $page->title }}
                                            </a>
                                            @if($isCore)
                                                <span class="text-[9px] uppercase tracking-wider px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 font-bold">
                                                    System Core
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-gray-500 font-mono mt-0.5 block">
                                            {{ $page->slug === 'home' ? '/' : '/' . $page->slug }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-[#222731] border border-[#282d37] text-gray-300">
                                    {{ $page->page_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#214E34]/20 text-[#A3D9B1] border border-[#214E34]/40">
                                    <span>🧩 {{ $sectionsCount }} Blocks</span>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($page->status === 'published')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-400 text-xs">
                                {{ $page->updated_at->diffForHumans() }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- OPEN IN PAGE BUILDER -->
                                    <a href="{{ route('builder.page', $page->id) }}" 
                                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-[#214E34] hover:bg-[#1a3f2a] text-white text-xs font-semibold shadow-sm transition-all"
                                       title="Launch Visual Page Builder">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Page Builder</span>
                                    </a>

                                    <!-- VIEW LIVE -->
                                    <a href="{{ $liveUrl }}" target="_blank" 
                                       class="p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-[#282d37] transition-colors" 
                                       title="View Live Page">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>

                                    <!-- DELETE (Non-core only) -->
                                    @if(! $isCore)
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page->id) }}" onsubmit="return confirm('Delete this page permanently?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-400 hover:text-red-300 rounded-lg hover:bg-[#282d37]" title="Delete Page">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                No pages found. Click "Create New Page" to compose one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pages->hasPages())
            <div class="p-4 border-t border-[#282d37]">
                {{ $pages->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE PAGE MODAL -->
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="showCreateModal = false" class="bg-[#1a1d24] border border-[#282d37] rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-[#282d37]">
                <h3 class="text-base font-semibold text-white">Create New Page for Builder</h3>
                <button type="button" @click="showCreateModal = false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.pages.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Page Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Boishakh Heritage Festival 2026" 
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Custom Slug (Optional)</label>
                    <input type="text" name="slug" placeholder="e.g. boishakh-heritage-festival" 
                           class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Page Type *</label>
                        <select name="page_type" class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                            <option value="landing">Marketing Landing</option>
                            <option value="campaign">Seasonal Campaign</option>
                            <option value="standard">Standard Editorial</option>
                            <option value="story">Artisan Chronicle</option>
                            <option value="festival">Festival Drop</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Initial Status *</label>
                        <select name="status" class="w-full bg-[#12141a] border border-[#282d37] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#214E34]">
                            <option value="draft">Draft (Work in Progress)</option>
                            <option value="published">Published Immediately</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-[#282d37]">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-xs text-gray-400 hover:text-white">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#214E34] hover:bg-[#1a3f2a] text-xs font-semibold text-white">
                        Create & Launch Builder →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
