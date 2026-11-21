@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-white transition">← Back to Orders</a>
            </div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight mt-1">Abandoned Cart & Checkout Recovery</h1>
            <p class="text-xs text-gray-400 mt-1">Track drop-offs during checkout and re-engage collectors with 1-click WhatsApp concierge reminders.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl bg-[#222733] hover:bg-[#2c3343] border border-[#333a4d] text-xs font-semibold text-gray-200 transition-colors">
                Active Orders Queue
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-xs flex items-center gap-2">
            <span>✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Toolbar -->
    <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.abandoned-checkouts.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative w-full sm:w-80">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search patron name, mobile or email..." 
                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white pl-9 pr-3 py-2 focus:border-[#214E34] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select name="status" class="bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                <option value="">All Checkout States</option>
                <option value="abandoned" {{ request('status') === 'abandoned' ? 'selected' : '' }}>Pending Recovery</option>
                <option value="recovered" {{ request('status') === 'recovered' ? 'selected' : '' }}>Successfully Recovered</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#222733] hover:bg-[#282f3e] text-xs font-medium text-gray-200 transition-colors">
                Filter
            </button>

            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.abandoned-checkouts.index') }}" class="text-xs text-gray-400 hover:text-white underline">
                    Clear
                </a>
            @endif
        </form>

        <div class="text-xs text-gray-400 font-mono">
            Total {{ $checkouts->total() }} recorded drop(s)
        </div>
    </div>

    <!-- Abandoned Checkouts Table -->
    <div class="rounded-2xl bg-[#161922] border border-[#252a36] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] bg-[#12141c] text-gray-400 uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4 font-semibold">Collector / Phone</th>
                        <th class="py-3 px-4 font-semibold">District & Address</th>
                        <th class="py-3 px-4 font-semibold">Heirlooms in Bag</th>
                        <th class="py-3 px-4 font-semibold">Total Value</th>
                        <th class="py-3 px-4 font-semibold">Last Active</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Recovery Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1e2330] text-gray-300">
                    @forelse($checkouts as $checkout)
                        @php
                            $rawPhone = preg_replace('/[^0-9]/', '', (string)$checkout->customer_phone);
                            if (str_starts_with($rawPhone, '01')) {
                                $waPhone = '88' . $rawPhone;
                            } elseif (str_starts_with($rawPhone, '8801')) {
                                $waPhone = $rawPhone;
                            } else {
                                $waPhone = $rawPhone;
                            }

                            $firstItem = !empty($checkout->items_list) ? ($checkout->items_list[0]['title'] ?? 'হস্তশিল্প পণ্য') : 'আপনার পছন্দের পণ্য';
                            $waText = rawurlencode("আসসালামু আলাইকুম " . ($checkout->customer_name ?: 'সম্মানিত সংগ্রাহক') . "! আপনি Sorolata (সরোলতা)-তে '{$firstItem}' এর অর্ডার প্রক্রিয়া শুরু করেছিলেন কিন্তু সম্পন্ন হয়নি। আমরা কি আপনার অর্ডারটি কনফার্ম করতে সহায়তা করতে পারি? ধন্যবাদ, সরোলতা টিম।");
                        @endphp
                        <tr class="hover:bg-[#1a1e29] transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-medium text-white">{{ $checkout->customer_name ?: 'Guest Collector' }}</div>
                                @if($checkout->customer_phone)
                                    <div class="text-[11px] font-mono text-emerald-400 mt-0.5">{{ $checkout->customer_phone }}</div>
                                @endif
                                @if($checkout->customer_email)
                                    <div class="text-[10px] text-gray-500">{{ $checkout->customer_email }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                @if($checkout->district)
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#214E34]/30 text-emerald-300 mb-1">
                                        {{ $checkout->district }}
                                    </span>
                                @endif
                                <div class="text-[11px] text-gray-400 truncate">{{ $checkout->shipping_address ?: '—' }}</div>
                            </td>
                            <td class="py-3 px-4 max-w-sm">
                                @if(!empty($checkout->items_list))
                                    <div class="space-y-1">
                                        @foreach($checkout->items_list as $it)
                                            <div class="text-[11px] text-gray-300">
                                                <span class="font-medium text-white">• {{ $it['title'] ?? 'Item' }}</span>
                                                <span class="text-gray-500 text-[10px]">(x{{ $it['quantity'] ?? 1 }} • ৳{{ number_format((float)($it['price'] ?? 0)) }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-amber-300">
                                ৳{{ number_format((float) $checkout->total_amount, 2) }}
                            </td>
                            <td class="py-3 px-4 text-[11px] text-gray-400">
                                <div>{{ $checkout->updated_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-gray-600">{{ $checkout->updated_at->format('d M, h:i A') }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($checkout->is_recovered)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-800">
                                        <span>✓</span> Recovered
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-950 text-amber-400 border border-amber-800">
                                        <span>⏳</span> Abandoned
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($checkout->customer_phone && !$checkout->is_recovered)
                                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" 
                                           target="_blank"
                                           class="px-3 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-gray-950 font-bold text-[11px] flex items-center gap-1.5 shadow-sm transition-all"
                                           title="Send WhatsApp recovery message">
                                            <span>💬</span>
                                            <span>WhatsApp</span>
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.abandoned-checkouts.destroy', $checkout->id) }}" method="POST" onsubmit="return confirm('Delete this abandoned record?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-400 rounded-lg hover:bg-[#222733] transition" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <span class="text-2xl block mb-2">🎉</span>
                                No abandoned checkouts found. All collectors are completing their acquisitions smoothly!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($checkouts->hasPages())
            <div class="p-4 border-t border-[#252a36]">
                {{ $checkouts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
