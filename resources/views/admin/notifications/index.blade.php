@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ selectedType: '{{ $filterType }}', selectedStatus: '{{ $filterStatus }}' }">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Notification & Dispatch Center</h1>
            <p class="text-xs text-gray-400 mt-1">Real-time alerts for customer orders, verified payment receipts, cancellations, inventory thresholds, and customer reviews (রিয়েল-টাইম নোটিফিকেশন হাব)।</p>
        </div>
        <div class="flex items-center gap-3">
            @if($unreadCount > 0)
                <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Mark All as Read (সবগুলো পঠিত করুন)</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Metric Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36]">
            <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Alerts</div>
            <div class="text-2xl font-bold text-white mt-1 font-mono">{{ $totalCount }}</div>
            <div class="text-[10px] text-gray-500 mt-0.5">System-wide dispatches</div>
        </div>
        <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36]">
            <div class="text-[10px] font-bold uppercase tracking-wider text-rose-400">Unread Alerts</div>
            <div class="text-2xl font-bold text-rose-400 mt-1 font-mono">{{ $unreadCount }}</div>
            <div class="text-[10px] text-gray-500 mt-0.5">Requiring attention</div>
        </div>
        <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36]">
            <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Orders & Payments</div>
            <div class="text-2xl font-bold text-emerald-400 mt-1 font-mono">{{ ($typeCounts['order'] ?? 0) + ($typeCounts['payment'] ?? 0) }}</div>
            <div class="text-[10px] text-gray-500 mt-0.5">{{ $typeCounts['order'] ?? 0 }} orders, {{ $typeCounts['payment'] ?? 0 }} trx</div>
        </div>
        <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36]">
            <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Low Stock Alerts</div>
            <div class="text-2xl font-bold text-amber-400 mt-1 font-mono">{{ $typeCounts['stock'] ?? 0 }}</div>
            <div class="text-[10px] text-gray-500 mt-0.5">Inventory reorders needed</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Type Tabs -->
        <div class="flex items-center gap-1.5 flex-wrap w-full md:w-auto">
            @php
                $types = [
                    'all' => ['label' => 'All Alerts', 'count' => $totalCount],
                    'order' => ['label' => 'Orders', 'count' => $typeCounts['order'] ?? 0],
                    'payment' => ['label' => 'Payments', 'count' => $typeCounts['payment'] ?? 0],
                    'stock' => ['label' => 'Low Stock', 'count' => $typeCounts['stock'] ?? 0],
                    'cancellation' => ['label' => 'Cancellations', 'count' => $typeCounts['cancellation'] ?? 0],
                    'review' => ['label' => 'Reviews', 'count' => $typeCounts['review'] ?? 0],
                ];
            @endphp
            @foreach($types as $key => $meta)
                <a href="{{ route('admin.notifications.index', ['type' => $key, 'status' => $filterStatus]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all flex items-center gap-1.5 {{ $filterType === $key ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-400 hover:text-white hover:bg-[#222733]' }}">
                    <span>{{ $meta['label'] }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $filterType === $key ? 'bg-black/30 text-white' : 'bg-[#222733] text-gray-400' }} font-mono">
                        {{ $meta['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Status Filter -->
        <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
            <span class="text-xs text-gray-400">Status:</span>
            <div class="inline-flex rounded-xl bg-[#0f1115] p-1 border border-[#252a36]">
                <a href="{{ route('admin.notifications.index', ['type' => $filterType, 'status' => 'all']) }}" 
                   class="px-2.5 py-1 text-xs rounded-lg transition-colors {{ $filterStatus === 'all' ? 'bg-[#214E34] text-white' : 'text-gray-400 hover:text-white' }}">
                    All
                </a>
                <a href="{{ route('admin.notifications.index', ['type' => $filterType, 'status' => 'unread']) }}" 
                   class="px-2.5 py-1 text-xs rounded-lg transition-colors {{ $filterStatus === 'unread' ? 'bg-[#214E34] text-white' : 'text-gray-400 hover:text-white' }}">
                    Unread
                </a>
                <a href="{{ route('admin.notifications.index', ['type' => $filterType, 'status' => 'read']) }}" 
                   class="px-2.5 py-1 text-xs rounded-lg transition-colors {{ $filterStatus === 'read' ? 'bg-[#214E34] text-white' : 'text-gray-400 hover:text-white' }}">
                    Read
                </a>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $n)
            @php
                $color = $n->getTypeColor();
                $badgeBg = match($color) {
                    'emerald' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                    'blue' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                    'rose' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                    'amber' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                    'purple' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                    default => 'bg-gray-500/20 text-gray-400 border-gray-500/30'
                };
                $iconSvg = match($n->type) {
                    'order' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />',
                    'payment' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'cancellation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                    'stock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
                    'review' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />',
                    default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />',
                };
            @endphp
            <div class="p-4 rounded-2xl bg-[#161922] border {{ !$n->is_read ? 'border-[#214E34]/80 shadow-md bg-gradient-to-r from-[#161922] to-[#1a2325]' : 'border-[#252a36]' }} transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                
                <!-- Left Details -->
                <div class="flex items-start gap-3.5 flex-1 min-w-0">
                    <div class="p-2.5 rounded-xl border {{ $badgeBg }} shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $iconSvg !!}
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border {{ $badgeBg }}">
                                {{ $n->getTypeLabel() }}
                            </span>
                            @if(!$n->is_read)
                                <span class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-semibold animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unread
                                </span>
                            @endif
                            <span class="text-xs text-gray-500 font-mono">
                                {{ $n->created_at?->diffForHumans() }} ({{ $n->created_at?->format('d M Y, h:i A') }})
                            </span>
                        </div>

                        <h3 class="text-sm font-semibold text-white">
                            {{ $n->title }}
                        </h3>
                        <p class="text-xs text-gray-300 mt-0.5 line-clamp-2">
                            {{ $n->message }}
                        </p>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                    @if(!empty($n->action_url))
                        <form action="{{ route('admin.notifications.read', $n->id) }}?redirect=1" method="POST">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Inspect</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    @endif

                    @if(!$n->is_read)
                        <form action="{{ route('admin.notifications.read', $n->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-[#222733] transition-colors" title="Mark as Read">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.notifications.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl text-gray-500 hover:text-rose-400 hover:bg-rose-950/30 transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 rounded-2xl bg-[#161922] border border-[#252a36] text-center">
                <div class="w-12 h-12 rounded-full bg-[#222733] text-gray-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-white">No notifications found</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">There are no notifications matching the selected filter. As new events occur in the storefront, dispatches will appear here.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pt-4">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection
