@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <div>
        <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Administrative Activity Ledger & Audit Trail</h1>
        <p class="text-xs text-gray-400 mt-1">Immutable security ledger tracking every product revision, order status update, and setting change.</p>
    </div>

    <div class="rounded-2xl bg-[#161922] border border-[#252a36] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-[#141720]">
                        <th class="py-3 px-4">Action</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4">Actor</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-[#1a1e28] transition-colors">
                            <td class="py-3.5 px-4 font-mono font-semibold text-white">
                                <span class="px-2 py-0.5 rounded bg-[#222733] text-[10px]">{{ $log->action }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-200">
                                {{ $log->description }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-xs text-white font-medium">{{ $log->user?->name ?? 'System Automated' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-400">
                                {{ $log->ip_address }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-gray-400 font-mono text-[11px]">
                                {{ $log->created_at->format('M d, Y • H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">
                                No activity logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="p-4 border-t border-[#252a36]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
