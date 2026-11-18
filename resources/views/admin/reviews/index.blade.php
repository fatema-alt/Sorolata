@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <div>
        <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Collector Appraisals & Reviews</h1>
        <p class="text-xs text-gray-400 mt-1">Review collector impressions, verify artisan provenance notes, and publish curator responses.</p>
    </div>

    <!-- Reviews Grid -->
    <div class="space-y-4">
        @forelse ($reviews as $rev)
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-[#252a36]">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-white">{{ $rev->author_name }}</span>
                            @if ($rev->is_verified_purchase)
                                <span class="text-[9px] px-2 py-0.5 rounded-full bg-[#214E34]/20 text-[#A3D9B1] border border-[#214E34]/30 font-semibold">
                                    Verified Collector
                                </span>
                            @endif
                            <span class="text-xs text-amber-400 font-mono">
                                {{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-400 mt-0.5">
                            Artifact: <a href="{{ route('admin.products.edit', $rev->product_id) }}" class="text-[#A3D9B1] hover:underline">{{ $rev->product?->title }}</a>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-500 font-mono">{{ $rev->created_at->diffForHumans() }}</span>
                </div>

                <p class="text-xs text-gray-200 leading-relaxed italic">
                    "{{ $rev->content }}"
                </p>

                <!-- Curator Reply Section -->
                @if ($rev->admin_reply)
                    <div class="p-3 rounded-xl bg-[#12141a] border border-[#252a36] text-xs">
                        <div class="flex items-center space-x-2 text-[#A3D9B1] font-semibold text-[10px] uppercase tracking-wider mb-1">
                            <span>Curator Response</span>
                            <span class="text-gray-500">• {{ $rev->admin_replied_at?->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-300">{{ $rev->admin_reply }}</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.reviews.reply', $rev->id) }}" class="flex items-center space-x-2 pt-2">
                        @csrf
                        <input type="text" name="admin_reply" required placeholder="Write official guild curator response..." 
                               class="flex-1 bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-medium transition-colors">
                            Publish Reply
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="p-12 text-center text-gray-500 rounded-2xl bg-[#161922] border border-[#252a36] text-xs">
                No collector reviews submitted yet.
            </div>
        @endforelse
    </div>

    @if ($reviews->hasPages())
        <div>{{ $reviews->links() }}</div>
    @endif

</div>
@endsection
