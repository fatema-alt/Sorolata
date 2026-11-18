@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Orders & Collector Logistics</h1>
            <p class="text-xs text-gray-400 mt-1">Review heirloom purchases, verify payment authenticity, and dispatch insured couriers.</p>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            
            <div class="relative w-full sm:w-80">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search order #, customer or phone..." 
                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white pl-9 pr-3 py-2 focus:border-[#214E34] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select name="status" class="bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                <option value="">All Fulfillment States</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="packed" {{ request('status') === 'packed' ? 'selected' : '' }}>Packed with Seal</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Dispatched</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>

            <select name="payment_status" class="bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                <option value="">All Payment States</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid / Verification Pending</option>
                <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid (Advance DC)</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid in Full</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed / Rejected</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#222733] hover:bg-[#282f3e] text-xs font-medium text-gray-200 transition-colors">
                Filter Queue
            </button>

            @if (request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-white underline">
                    Clear
                </a>
            @endif
        </form>

        <div class="text-xs text-gray-400 font-mono">
            Showing {{ $orders->total() }} order(s)
        </div>
    </div>

    <!-- Orders Table -->
    <div class="rounded-2xl bg-[#161922] border border-[#252a36] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-[#141720]">
                        <th class="py-3 px-4">Order Number</th>
                        <th class="py-3 px-4">Collector</th>
                        <th class="py-3 px-4">Destination</th>
                        <th class="py-3 px-4">Pieces</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Payment</th>
                        <th class="py-3 px-4">Fulfillment</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-[#1a1e28] transition-colors">
                            <td class="py-3.5 px-4 font-mono font-semibold text-white">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-[#A3D9B1]">
                                    #{{ $order->order_number }}
                                </a>
                                <div class="text-[10px] text-gray-500 font-sans">{{ $order->created_at->format('M d, Y • H:i') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-gray-200">{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-gray-300">
                                {{ $order->shipping_address['city'] ?? 'Dhaka' }}, Bangladesh
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-300">
                                {{ $order->items->count() }} item(s)
                            </td>
                            <td class="py-3.5 px-4 font-bold text-white">
                                ৳ {{ number_format($order->total_amount, 0) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-mono text-[11px] text-gray-300 uppercase font-semibold">{{ $order->payment_gateway->value }}</div>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded text-[10px] font-semibold border {{ $order->payment_status->badgeClass() }}">
                                    {{ $order->payment_status->label() }}
                                </span>
                                @if($order->transaction_id)
                                    <div class="font-mono text-[10px] text-amber-300/80 mt-1">Trx: {{ $order->transaction_id }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $order->status->badgeClass() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-[#222733] hover:bg-[#214E34] text-[11px] font-semibold text-gray-200 hover:text-white transition-colors">
                                        Inspect
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-500">
                                No orders matching current filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="p-4 border-t border-[#252a36]">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
