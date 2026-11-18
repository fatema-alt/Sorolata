@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    
    <div class="flex items-center space-x-2 text-xs text-gray-400 pb-2">
        <a href="{{ route('admin.customers.index') }}" class="hover:text-white">Collectors</a>
        <span>/</span>
        <span class="text-white">{{ $customer->name }}</span>
    </div>

    <!-- Collector Hero Card -->
    <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-[#214E34] text-white flex items-center justify-center font-bold text-lg ring-4 ring-[#222735]">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="font-editorial-heading text-2xl text-white font-normal">{{ $customer->name }}</h1>
                <div class="text-xs text-gray-400 font-mono">{{ $customer->email }} • Joined {{ $customer->created_at->format('M Y') }}</div>
            </div>
        </div>

        <div class="flex items-center space-x-6 text-right">
            <div>
                <div class="text-[10px] text-gray-400 uppercase font-semibold">Total Acquisitions</div>
                <div class="text-lg font-bold text-white">{{ $customer->orders->count() }} Orders</div>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 uppercase font-semibold">Lifetime Spend</div>
                <div class="text-lg font-bold text-[#A3D9B1]">৳ {{ number_format($lifetimeValue, 0) }}</div>
            </div>
        </div>
    </div>

    <!-- Orders History -->
    <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36]">
        <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Acquisition History</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3">Order Number</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Artifacts</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Inspect</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]">
                    @forelse ($customer->orders as $order)
                        <tr>
                            <td class="py-3 font-mono font-semibold text-white">#{{ $order->order_number }}</td>
                            <td class="py-3 text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3 text-gray-300">{{ $order->items->count() }} piece(s)</td>
                            <td class="py-3 font-bold text-white">৳ {{ number_format($order->total_amount, 0) }}</td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $order->status->badgeClass() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#A3D9B1] hover:underline">
                                    View →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">No orders placed by this collector yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
