@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ modalOpen: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">Promotional Privileges & Coupons</h1>
            <p class="text-xs text-gray-400 mt-1">Configure percentage promotions, fixed discounts, and complimentary shipping vouchers.</p>
        </div>

        <button type="button" @click="modalOpen = true" 
                class="px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create Promotional Code</span>
        </button>
    </div>

    <!-- Coupons Table -->
    <div class="rounded-2xl bg-[#161922] border border-[#252a36] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-[#141720]">
                        <th class="py-3 px-4">Coupon Code</th>
                        <th class="py-3 px-4">Promotion Name</th>
                        <th class="py-3 px-4">Discount Value</th>
                        <th class="py-3 px-4">Min. Order</th>
                        <th class="py-3 px-4">Usage Limit</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]">
                    @forelse ($discounts as $d)
                        <tr class="hover:bg-[#1a1e28] transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-white text-sm">
                                <span class="px-2 py-0.5 rounded bg-[#222733] border border-[#2e3646]">{{ $d->code }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-200 font-medium">{{ $d->name }}</td>
                            <td class="py-3.5 px-4 font-bold text-[#A3D9B1]">
                                @if ($d->type === \App\Enums\DiscountType::PERCENTAGE)
                                    {{ $d->value }}% Off
                                @elseif ($d->type === \App\Enums\DiscountType::FREE_SHIPPING)
                                    Free Insured Courier
                                @else
                                    ৳ {{ number_format($d->value, 0) }} Off
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 font-mono">
                                ৳ {{ number_format($d->min_subtotal, 0) }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 font-mono">
                                {{ $d->used_count }} / {{ $d->usage_limit ?? '∞' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $d->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                                    {{ $d->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form method="POST" action="{{ route('admin.discounts.destroy', $d->id) }}" onsubmit="return confirm('Delete coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-300">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">No promotional coupons configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Coupon Modal -->
    <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="modalOpen = false" class="w-full max-w-lg bg-[#161922] border border-[#2d3444] rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Draft New Promotional Privilege</h3>
                <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.discounts.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Coupon Code (Uppercase)</label>
                    <input type="text" name="code" required placeholder="e.g. SONARGAON25" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 font-mono uppercase focus:border-[#214E34] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Privilege Campaign Name</label>
                    <input type="text" name="name" required placeholder="e.g. Winter Handloom Festival 15% Off" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Discount Type</label>
                        <select name="type" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-300 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed_amount">Fixed Amount (৳)</option>
                            <option value="free_shipping">Complimentary Courier</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Value (% or ৳)</label>
                        <input type="number" step="0.01" name="value" required placeholder="10" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Min. Subtotal Requirement (৳)</label>
                        <input type="number" step="0.01" name="min_subtotal" placeholder="5000" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Usage Limit</label>
                        <input type="number" name="usage_limit" placeholder="200" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-[#252a36]">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl border border-[#2d3444] text-xs text-gray-400">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold">Publish Coupon</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
