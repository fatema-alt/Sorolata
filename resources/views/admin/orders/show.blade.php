@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#252a36]">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="hover:text-white">Orders</a>
                <span>/</span>
                <span class="text-white font-mono">#{{ $order->order_number }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <h1 class="font-editorial-heading text-3xl text-white font-normal">Order #{{ $order->order_number }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $order->status->badgeClass() }}">
                    {{ $order->status->label() }}
                </span>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.shipping-label', $order->id) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-[#214E34] hover:bg-[#1b3f2a] text-xs font-semibold text-white transition-all flex items-center space-x-2 shadow-sm">
                <span>🏷️</span>
                <span>Print 4x6 Label (স্টিকার)</span>
            </a>
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl border border-[#2d3444] hover:border-gray-500 bg-[#161922] text-xs font-medium text-gray-300 hover:text-white transition-all flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Certificate / Invoice</span>
            </a>
        </div>
    </div>

    <!-- Customer Cancellation Alert Banner (if requested) -->
    @php
        $cancellationReq = $order->timelines->first(fn($t) => str_contains($t->note, '[CANCELLATION_REQUEST]'));
    @endphp
    @if($cancellationReq && $order->status !== \App\Enums\OrderStatus::CANCELLED)
        <div class="p-4 rounded-2xl bg-rose-950/40 border border-rose-800 text-rose-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 font-bold text-rose-300">
                    <span class="text-base">⚠️</span>
                    <span>Customer Cancellation Request (গ্রাহক অর্ডার বাতিল অনুরোধ করেছেন)</span>
                </div>
                <p class="text-xs text-rose-200/90 mt-1 leading-relaxed">{{ str_replace('[CANCELLATION_REQUEST]', '', $cancellationReq->note) }}</p>
                <div class="text-[10px] text-rose-400/80 mt-1">অনুরোধের সময়: {{ $cancellationReq->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <form method="POST" action="{{ route('admin.orders.cancellation-approve', $order->id) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('অর্ডার বাতিল অনুমোদন করবেন? পণ্যের স্টক স্বয়ংক্রিয়ভাবে ইনভেনটরিতে ফেরত যোগ হয়ে যাবে।')" 
                            class="px-4 py-2 rounded-xl bg-rose-700 hover:bg-rose-600 text-white text-xs font-semibold shadow transition-all">
                        ✓ Approve & Restock
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.orders.cancellation-decline', $order->id) }}">
                    @csrf
                    <input type="hidden" name="reason" value="পণ্যটি ইতোমধ্যে তাঁতে তৈরি/প্যাকেজিং সম্পন্ন হয়েছে।">
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#222733] hover:bg-gray-700 text-gray-300 text-xs font-semibold transition-all">
                        ✕ Decline
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT 2 COLUMNS: ITEMS, TOTALS & TIMELINE -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Items Table -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36]">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Acquired Heirloom Artifacts</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#252a36] text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="pb-3">Artifact</th>
                                <th class="pb-3">SKU</th>
                                <th class="pb-3 text-right">Price</th>
                                <th class="pb-3 text-center">Qty</th>
                                <th class="pb-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#252a36]">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="py-3.5">
                                        <div class="font-semibold text-white">{{ $item->product_title }}</div>
                                        @if ($item->variant_title)
                                            <div class="text-[10px] text-[#A3D9B1]">{{ $item->variant_title }}</div>
                                        @endif
                                        <div class="text-[10px] text-gray-400">{{ $item->artisan_name ?? 'Master Artisan Guild' }}</div>
                                    </td>
                                    <td class="py-3.5 font-mono text-gray-300">{{ $item->sku }}</td>
                                    <td class="py-3.5 text-right font-mono text-gray-300">৳ {{ number_format($item->unit_price, 0) }}</td>
                                    <td class="py-3.5 text-center font-mono text-white">{{ $item->quantity }}</td>
                                    <td class="py-3.5 text-right font-bold text-white">৳ {{ number_format($item->subtotal, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Breakdown -->
                <div class="mt-6 pt-4 border-t border-[#252a36] flex justify-end">
                    <div class="w-72 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal</span>
                            <span class="font-mono text-white">৳ {{ number_format($order->subtotal_amount, 0) }}</span>
                        </div>
                        @if ($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-400">
                                <span>Privilege Discount</span>
                                <span class="font-mono">- ৳ {{ number_format($order->discount_amount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-400">
                            <span>White-Glove Shipping</span>
                            <span class="font-mono text-white">৳ {{ number_format($order->shipping_fee, 0) }}</span>
                        </div>
                        @if ($order->gift_wrap)
                            <div class="flex justify-between text-[#E79770]">
                                <span>Silk Gift Presentation</span>
                                <span class="font-mono">+ ৳ {{ number_format($order->gift_wrap_fee, 0) }}</span>
                            </div>
                        @endif
                        @if ($order->tax_amount > 0)
                            <div class="flex justify-between text-gray-400">
                                <span>Handicraft VAT</span>
                                <span class="font-mono text-white">৳ {{ number_format($order->tax_amount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm font-bold text-white pt-2 border-t border-[#252a36]">
                            <span>Grand Total</span>
                            <span class="font-mono text-[#A3D9B1]">৳ {{ number_format($order->total_amount, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Immutable Order Audit Timeline -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36]">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Immutable Order Audit Timeline</h3>
                <div class="space-y-4">
                    @forelse ($order->timelines as $timeline)
                        <div class="flex items-start space-x-3 text-xs">
                            <div class="w-6 h-6 rounded-full bg-[#222735] border border-[#2e3646] flex items-center justify-center shrink-0 mt-0.5 text-gray-400 font-mono text-[10px]">
                                ●
                            </div>
                            <div class="flex-1 bg-[#1c202b] p-3 rounded-xl border border-[#252a36]">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-white uppercase text-[10px] tracking-wider">{{ $timeline->status }}</span>
                                    <span class="text-[10px] text-gray-500 font-mono">{{ $timeline->created_at->format('M d, Y • H:i:s') }}</span>
                                </div>
                                <p class="text-gray-300 text-xs">{{ $timeline->note }}</p>
                                <span class="text-[9px] text-gray-500 block mt-1">Actor: {{ ucfirst($timeline->actor_type) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">No timeline entries recorded.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COLUMN: STATUS CONTROLS, DISPATCH & COLLECTOR -->
        <div class="space-y-6">
            
            <!-- Payment Verification & Audit Card -->
            <div class="p-6 rounded-2xl bg-[#161922] border {{ $order->payment_status->isSuccessful() ? 'border-emerald-800/60' : 'border-amber-600/40' }} space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">💳</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white">Payment Audit & Verification</h3>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $order->payment_status->badgeClass() }}">
                        {{ $order->payment_status->label() }}
                    </span>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center text-gray-400">
                        <span>Payment Method:</span>
                        <span class="font-semibold text-white text-right">{{ $order->payment_gateway?->displayName() ?? ucfirst($order->payment_gateway?->value ?? 'Direct') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-400">
                        <span>Grand Total:</span>
                        <span class="font-mono text-white font-bold">৳ {{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    @if ($order->advance_delivery_charge > 0)
                        <div class="flex justify-between items-center text-amber-400 bg-amber-950/30 px-2 py-1 rounded-lg border border-amber-800/40">
                            <span>Advance Courier DC:</span>
                            <span class="font-mono font-bold">৳ {{ number_format($order->advance_delivery_charge, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center text-emerald-400">
                        <span>Paid / Cleared Amount:</span>
                        <span class="font-mono font-bold">৳ {{ number_format($order->paid_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-rose-400">
                        <span>Due to Courier on Delivery:</span>
                        <span class="font-mono font-bold">৳ {{ number_format($order->due_amount ?: ($order->total_amount - $order->paid_amount), 2) }}</span>
                    </div>

                    <div class="pt-2 border-t border-[#252a36] space-y-1.5">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-[11px]">Submitted TrxID:</span>
                            <span class="font-mono text-indigo-300 font-bold bg-[#1c202b] px-2 py-0.5 rounded border border-[#2d3444]">
                                {{ $order->transaction_id ?: 'Not provided yet' }}
                            </span>
                        </div>

                        @if (!empty($order->payment_details['sender_number']))
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-[11px]">Sender Mobile / Account:</span>
                                <span class="font-mono text-gray-200 font-bold">{{ $order->payment_details['sender_number'] }}</span>
                            </div>
                        @endif

                        @if (!empty($order->payment_details['customer_notes']))
                            <div class="text-[11px] text-gray-400 bg-[#0f1115] p-2 rounded-lg border border-[#282d37]">
                                <span class="text-gray-500 font-semibold block text-[10px]">Customer Note:</span>
                                {{ $order->payment_details['customer_notes'] }}
                            </div>
                        @endif

                        @if (!empty($order->payment_details['verified_by']))
                            <div class="text-[10px] text-emerald-400 bg-emerald-950/30 p-2 rounded-lg border border-emerald-800/40">
                                ✓ Verified by: {{ $order->payment_details['verified_by'] }} ({{ \Carbon\Carbon::parse($order->payment_details['verified_at'] ?? now())->format('d M Y, h:i A') }})
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Admin Verification Action Form -->
                @if ($order->payment_status !== \App\Enums\PaymentStatus::PAID)
                    <form method="POST" action="{{ route('admin.orders.verify-payment', $order->id) }}" class="pt-2 space-y-3 border-t border-[#252a36]">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-300 mb-1">Confirm TrxID / Reference</label>
                            <input type="text" name="transaction_id" value="{{ $order->transaction_id }}" placeholder="e.g. TRX987654321" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 font-mono focus:border-emerald-500 focus:outline-none">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-emerald-950/50 transition-all flex items-center justify-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Verify & Confirm Payment (টাকা গ্রহণ ও নিশ্চিত)</span>
                        </button>
                    </form>
                @else
                    <div class="p-2.5 rounded-xl bg-emerald-950/40 border border-emerald-800 text-center text-emerald-300 text-xs font-semibold">
                        ✓ Full Payment Verified & Cleared
                    </div>
                @endif
            </div>

            <!-- Update Status Card -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white">Transition Status</h3>
                <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Next Fulfillment State</label>
                        <select name="status" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-200 px-3 py-2 focus:border-[#214E34] focus:outline-none">
                            @foreach (\App\Enums\OrderStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ $order->status === $s ? 'selected' : '' }}>
                                    {{ $s->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Curator Log Note</label>
                        <textarea name="note" rows="2" placeholder="e.g. Inspected and hand-sealed at Sonargaon guild..." 
                                  class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all">
                        Update Fulfillment State
                    </button>
                </form>
            </div>

            <!-- Courier Dispatch Card -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-[#252a36]">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Insured Courier Dispatch</h3>
                    <span class="text-[10px] text-gray-400 font-mono">Automated / Manual</span>
                </div>

                <!-- 1-Click Steadfast Automated Booking -->
                <form method="POST" action="{{ route('admin.orders.dispatch-steadfast', $order->id) }}" class="p-3.5 bg-gradient-to-br from-emerald-950/40 to-teal-950/20 border border-emerald-800/50 rounded-xl space-y-2">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold text-white flex items-center space-x-1.5">
                            <span>📦</span>
                            <span>Steadfast Courier Integration</span>
                        </div>
                        <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-semibold px-2 py-0.5 rounded">Ready</span>
                    </div>
                    <p class="text-[11px] text-emerald-200/80 leading-snug">
                        ক্লিক করলেই সরাসরি স্ট্যাডফাস্ট পোর্টালে পার্সেল বুকিং হবে, কনসাইনমেন্ট ট্র্যাকিং কোড সেভ হবে এবং গ্রাহককে SMS যাবে।
                    </p>
                    <button type="submit" onclick="return confirm('স্ট্যাডফাস্ট কুরিয়ারে এই পার্সেলটি বুক করবেন?')"
                            class="w-full py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow-md shadow-emerald-950/50 flex items-center justify-center space-x-1.5 transition-all">
                        <span>⚡ ১-ক্লিকে স্ট্যাডফাস্টে বুক করুন (Book Steadfast)</span>
                    </button>
                </form>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-[#252a36]"></div>
                    <span class="flex-shrink mx-2 text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Or Manual Tracking</span>
                    <div class="flex-grow border-t border-[#252a36]"></div>
                </div>

                <!-- Manual Courier Entry -->
                <form method="POST" action="{{ route('admin.orders.update-shipping', $order->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Courier Partner</label>
                        <input type="text" name="courier_name" value="{{ old('courier_name', $order->courier_name ?? 'Steadfast / Pathao / RedX') }}" required 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-300 mb-1">Tracking Number / Consignment ID</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number ?? '') }}" placeholder="e.g. STDF87654321" required 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none font-mono">
                    </div>

                    <button type="submit" class="w-full py-2 rounded-xl border border-[#2d3444] hover:bg-[#222733] text-gray-200 text-xs font-medium transition-colors">
                        Save Tracking & Disptach SMS
                    </button>
                </form>
            </div>

            <!-- Collector Information Card -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-3 text-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-2">Collector Contact</h3>
                <div>
                    <div class="text-gray-400">Name</div>
                    <div class="font-semibold text-white mt-0.5">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <div class="text-gray-400">Email</div>
                    <div class="font-mono text-gray-200 mt-0.5">{{ $order->customer_email }}</div>
                </div>
                <div>
                    <div class="text-gray-400">Phone</div>
                    <div class="font-mono text-gray-200 mt-0.5">{{ $order->customer_phone }}</div>
                </div>
                <div>
                    <div class="text-gray-400">Delivery Destination</div>
                    <div class="text-gray-200 mt-0.5 leading-relaxed">
                        {{ $order->shipping_address['line_1'] ?? ($order->shipping_address['address'] ?? 'Gulshan-2') }}<br>
                        {{ $order->shipping_address['city'] ?? 'Dhaka' }}, Bangladesh
                    </div>
                </div>
            </div>

            <!-- Gift Presentation (if enabled) -->
            @if ($order->gift_wrap)
                <div class="p-6 rounded-2xl bg-[#161922] border border-[#C96A3D]/40 space-y-3 text-xs">
                    <div class="flex items-center space-x-2 text-[#E79770]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        <span class="font-bold uppercase tracking-wider">Royal Gift Packaging</span>
                    </div>
                    <div>
                        <div class="text-gray-400 text-[11px]">Recipient</div>
                        <div class="font-semibold text-white">{{ $order->gift_recipient_name ?? 'Honored Recipient' }}</div>
                    </div>
                    @if ($order->gift_message)
                        <div>
                            <div class="text-gray-400 text-[11px]">Calligraphy Card Message</div>
                            <div class="italic text-gray-200 bg-[#12141a] p-3 rounded-xl border border-[#282d37] mt-1">
                                "{{ $order->gift_message }}"
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
