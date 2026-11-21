@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Collectors & Patrons</h1>
            <p class="text-xs text-gray-400 mt-1">Directory of registered art patrons, lifetime acquisition values, and delivery profiles.</p>
        </div>
        <div>
            <a href="{{ route('admin.customers.export-csv', request()->query()) }}" class="px-3.5 py-2 rounded-xl bg-[#214E34] hover:bg-[#1b3f2a] text-xs font-semibold text-white transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export Patrons (CSV)</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] flex items-center justify-between">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center space-x-3 flex-1 max-w-md">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name or email..." 
                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white pl-9 pr-3 py-2 focus:border-[#214E34] focus:outline-none">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#222733] hover:bg-[#282f3e] text-xs font-medium text-gray-200">
                Search
            </button>
        </form>
        <span class="text-xs text-gray-400 font-mono">{{ $customers->total() }} collector(s)</span>
    </div>

    <!-- Customers Table -->
    <div class="rounded-2xl bg-[#161922] border border-[#252a36] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-[#141720]">
                        <th class="py-3 px-4">Collector Profile</th>
                        <th class="py-3 px-4">Role / Tier</th>
                        <th class="py-3 px-4">Acquisitions</th>
                        <th class="py-3 px-4">Lifetime Spend (LTV)</th>
                        <th class="py-3 px-4">Joined Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]">
                    @forelse ($customers as $c)
                        <tr class="hover:bg-[#1a1e28] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white">{{ $c->name }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $c->email }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $c->role?->badgeClass() ?? 'bg-gray-800 text-gray-300' }}">
                                    {{ $c->role?->label() ?? 'Registered Collector' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-300">
                                {{ $c->orders_count }} order(s)
                            </td>
                            <td class="py-3.5 px-4 font-bold text-white">
                                ৳ {{ number_format((float) ($c->orders_sum_total_amount ?? 0), 0) }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 text-[11px]">
                                {{ $c->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.customers.show', $c->id) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-[#222733] hover:bg-[#214E34] text-[11px] font-semibold text-gray-200 hover:text-white transition-colors">
                                    Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                No registered collectors recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="p-4 border-t border-[#252a36]">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
