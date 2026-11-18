@extends('layouts.storefront')

@section('seo_meta')
    <title>Order Tracking & Provenance Journey — Order #{{ $order?->order_number ?? 'Search' }} | Sorolata</title>
    <meta name="description" content="Follow the handcrafting, payment verification, and courier transit of your acquired Bangladeshi heirloom.">
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-6 lg:px-12 py-12 space-y-10" x-data="{
    copiedTrx: false,
    showCancelModal: false,
    copy(text) {
        navigator.clipboard.writeText(text);
        this.copiedTrx = true;
        setTimeout(() => { this.copiedTrx = false; }, 2000);
    }
}">
    
    <!-- 1. Search Bar -->
    <div class="text-center space-y-3">
        <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('Real-time Provenance & Dispatch Tracker') }}</span>
        <h1 class="font-editorial-heading text-3xl sm:text-5xl font-light text-[#1D1D1D]">
            {{ __('Order Tracking & Delivery Status') }}
        </h1>
        <p class="font-editorial-body text-sm text-[#555555] max-w-lg mx-auto">
            {!! __('Enter your order reference (:order_format) or customer phone number to trace real-time craftsmanship and transit provenance.', ['order_format' => '<span class="font-mono font-bold">SRL-XXXXXXXX</span>']) !!}
        </p>

        <form action="{{ route('track.order') }}" method="GET" class="max-w-md mx-auto flex gap-2 pt-2">
            <div class="relative flex-grow">
                <input 
                    type="text" 
                    name="order_number" 
                    value="{{ $queriedNumber ?? '' }}"
                    placeholder="{{ __('Order # (e.g. SRL-XXXX) or Phone Number...') }}" 
                    class="w-full text-xs px-4 py-3.5 rounded-2xl border border-[#D5D5D5] bg-white text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34] shadow-sm font-sans"
                    required
                >
            </div>
            <button type="submit" class="px-6 py-3.5 bg-[#214E34] hover:bg-[#183a27] text-white text-xs font-bold rounded-2xl shadow-md transition-all flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>{{ __('Track Order') }}</span>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-3 shadow-sm">
            <span class="text-xl">✓</span>
            <div>
                <strong class="font-bold">{{ __('Success:') }}</strong>
                <p class="mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($order)
        @php
            $currentStep = $order->status->stepNumber();
            $isPaid = $order->payment_status === \App\Enums\PaymentStatus::PAID;
            $isPartial = $order->payment_status === \App\Enums\PaymentStatus::PARTIALLY_PAID;
            $dueAmount = $order->due_amount ?: max(0.0, (float) $order->total_amount - (float) $order->paid_amount);
        @endphp

        <!-- 2. Main Order Status Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-[#E8E8E8] shadow-soft-md space-y-8">
            
            <!-- Header Summary Row -->
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-[#E8E8E8] gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-[#888888] uppercase tracking-wider font-semibold">{{ __('Order Reference') }}</span>
                        <span class="text-[11px] font-mono text-gray-500">• {{ $order->created_at->format('d M, Y — h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <h2 class="font-mono text-2xl font-bold text-gray-900">#{{ $order->order_number }}</h2>
                        <button type="button" @click="copy('{{ $order->order_number }}')" class="text-xs text-emerald-700 hover:underline flex items-center gap-1">
                            <span x-text="copiedTrx ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy'), JSON_UNESCAPED_UNICODE)">{{ __('Copy') }}</span>
                        </button>
                    </div>
                    <div class="text-xs text-gray-600 mt-1 flex flex-wrap gap-x-4 gap-y-1">
                        <span>{{ __('Customer:') }} <strong class="text-gray-900">{{ $order->customer_name }}</strong></span>
                        <span>{{ __('Phone:') }} <strong class="text-gray-900 font-mono">{{ $order->customer_phone }}</strong></span>
                        <span>{{ __('Destination:') }} <span class="text-gray-700">{{ $order->shipping_address['address'] ?? ($order->shipping_address['line_1'] ?? '') }}, {{ $order->shipping_address['district'] ?? ($order->shipping_address['city'] ?? __('Dhaka')) }}</span></span>
                    </div>
                </div>

                <div class="flex flex-col md:items-end gap-2">
                    <div class="flex items-center gap-2">
                        <!-- Order Status Badge -->
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $order->status->badgeClass() }}">
                            {{ $order->status->label() }}
                        </span>
                        <!-- Payment Status Badge -->
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $order->payment_status->badgeClass() }}">
                            {{ $order->payment_status->label() }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-500 font-mono">
                        {{ __('Method:') }} {{ $order->payment_gateway?->displayName() ?? ucfirst($order->payment_gateway?->value ?? 'Manual') }}
                    </span>
                </div>
            </div>

            <!-- Contextual Status Alert / Next Step Guidance -->
            @if(!$isPaid && !$isPartial && empty($order->transaction_id))
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div class="text-xs">
                            <strong class="font-bold text-sm block">{{ __('Payment Transaction ID (TrxID) Required') }}</strong>
                            <span>{{ __('To confirm your heirloom order, please complete your transfer via bKash, Nagad or bank transfer and provide the TrxID.') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('payment.manual.instruction', ['order' => $order->order_number]) }}" 
                       class="px-5 py-2.5 bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs rounded-xl shadow transition shrink-0">
                        {{ __('Complete Payment & Provide TrxID →') }}
                    </a>
                </div>
            @elseif(!$isPaid && !empty($order->transaction_id) && $order->status === \App\Enums\OrderStatus::PENDING)
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 flex items-start gap-3">
                    <span class="text-2xl">⏳</span>
                    <div class="text-xs space-y-1">
                        <strong class="font-bold text-sm block">{{ __('Payment Verification Underway') }}</strong>
                        <p>{!! __('Our accounts team is reconciling your submitted TrxID (:trx_id) against the bank/MFS statement. Your parcel will be confirmed shortly.', ['trx_id' => '<span class="font-mono font-bold">' . e($order->transaction_id) . '</span>']) !!}</p>
                    </div>
                </div>
            @elseif($isPartial)
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3">
                    <span class="text-2xl">🛡️</span>
                    <div class="text-xs space-y-1">
                        <strong class="font-bold text-sm block">{{ __('Advance Delivery Fee Confirmed (COD Active)') }}</strong>
                        <p>{!! __('Advance delivery fee of :fee has been received. Your heirloom order is confirmed! The remaining balance of :due is payable to the courier upon delivery.', ['fee' => '<strong>৳' . number_format($order->paid_amount, 2) . '</strong>', 'due' => '<strong>৳' . number_format($dueAmount, 2) . '</strong>']) !!}</p>
                    </div>
                </div>
            @endif

            <!-- Courier Dispatch Banner (If Shipped) -->
            @if(!empty($order->tracking_number))
                <div class="p-5 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-950 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🚚</span>
                        <div>
                            <span class="text-[11px] font-bold text-indigo-700 uppercase tracking-wider block">{{ __('Courier Tracking Details') }}</span>
                            <div class="text-sm font-bold mt-0.5">
                                {{ $order->courier_name ?? 'RedX / Steadfast Courier' }} • {{ __('Tracking #:') }} <span class="font-mono text-indigo-900 underline">{{ $order->tracking_number }}</span>
                            </div>
                            <p class="text-xs text-indigo-800 mt-0.5">{{ __('Your parcel has been handed over to the courier and is on its journey to your delivery address.') }}</p>
                        </div>
                    </div>
                    <button type="button" @click="copy('{{ $order->tracking_number }}')" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow transition shrink-0">
                        {{ __('Copy Tracking Code') }}
                    </button>
                </div>
            @endif

            <!-- 3. Progressive Visual Stepper (5 Stages) -->
            <div class="space-y-4 pt-2">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Live Progress Journey') }}</h4>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <!-- Step 1: Placed -->
                    <div class="p-3.5 rounded-2xl border {{ $currentStep >= 1 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200 bg-gray-50 opacity-60' }} space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="w-6 h-6 rounded-full {{ $currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center font-bold text-[10px]">
                                {{ $currentStep > 1 ? '✓' : '1' }}
                            </span>
                            <span class="text-[10px] font-mono text-gray-500">{{ __('Step :step', ['step' => 1]) }}</span>
                        </div>
                        <div class="font-bold text-xs text-gray-900 mt-2">{{ __('Order Placed') }}</div>
                        <p class="text-[10px] text-gray-500">{{ __('Registered in system') }}</p>
                    </div>

                    <!-- Step 2: Payment Verified -->
                    <div class="p-3.5 rounded-2xl border {{ $currentStep >= 2 ? 'border-emerald-600 bg-emerald-50/50' : ($currentStep == 1 ? 'border-amber-400 bg-amber-50/40 ring-1 ring-amber-400' : 'border-gray-200 bg-gray-50 opacity-60') }} space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="w-6 h-6 rounded-full {{ $currentStep >= 2 ? 'bg-emerald-600 text-white' : ($currentStep == 1 ? 'bg-amber-500 text-white animate-pulse' : 'bg-gray-300 text-gray-600') }} flex items-center justify-center font-bold text-[10px]">
                                {{ $currentStep > 2 ? '✓' : '2' }}
                            </span>
                            <span class="text-[10px] font-mono text-gray-500">{{ __('Step :step', ['step' => 2]) }}</span>
                        </div>
                        <div class="font-bold text-xs text-gray-900 mt-2">{{ __('Payment Verification') }}</div>
                        <p class="text-[10px] text-gray-500">{{ $currentStep >= 2 ? __('Order Confirmed') : __('Awaiting TrxID') }}</p>
                    </div>

                    <!-- Step 3: Packaging & Sealed -->
                    <div class="p-3.5 rounded-2xl border {{ $currentStep >= 3 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200 bg-gray-50 opacity-60' }} space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="w-6 h-6 rounded-full {{ $currentStep >= 3 ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center font-bold text-[10px]">
                                {{ $currentStep > 3 ? '✓' : '3' }}
                            </span>
                            <span class="text-[10px] font-mono text-gray-500">{{ __('Step :step', ['step' => 3]) }}</span>
                        </div>
                        <div class="font-bold text-xs text-gray-900 mt-2">{{ __('Packaging & Sealed') }}</div>
                        <p class="text-[10px] text-gray-500">{{ __('Artisan Quality Seal') }}</p>
                    </div>

                    <!-- Step 4: Shipped -->
                    <div class="p-3.5 rounded-2xl border {{ $currentStep >= 4 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200 bg-gray-50 opacity-60' }} space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="w-6 h-6 rounded-full {{ $currentStep >= 4 ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center font-bold text-[10px]">
                                {{ $currentStep > 4 ? '✓' : '4' }}
                            </span>
                            <span class="text-[10px] font-mono text-gray-500">{{ __('Step :step', ['step' => 4]) }}</span>
                        </div>
                        <div class="font-bold text-xs text-gray-900 mt-2">{{ __('In Transit') }}</div>
                        <p class="text-[10px] text-gray-500">{{ __('Dispatched via Courier') }}</p>
                    </div>

                    <!-- Step 5: Delivered -->
                    <div class="p-3.5 rounded-2xl border {{ $currentStep >= 5 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200 bg-gray-50 opacity-60' }} space-y-1 col-span-2 md:col-span-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="w-6 h-6 rounded-full {{ $currentStep >= 5 ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center font-bold text-[10px]">
                                {{ $currentStep >= 5 ? '✓' : '5' }}
                            </span>
                            <span class="text-[10px] font-mono text-gray-500">{{ __('Step :step', ['step' => 5]) }}</span>
                        </div>
                        <div class="font-bold text-xs text-gray-900 mt-2">{{ __('Delivered') }}</div>
                        <p class="text-[10px] text-gray-500">{{ __('Received by Customer') }}</p>
                    </div>
                </div>
            </div>

            <!-- 4. Real Database Audit Timeline -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Order Timeline & Activity Log') }}</h4>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-700/20">
                    @forelse($order->timelines as $timeline)
                        <div class="relative text-xs">
                            <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#214E34] ring-4 ring-white"></span>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-900 uppercase tracking-wider text-[11px]">{{ $timeline->status }}</span>
                                <span class="text-[10px] font-mono text-gray-500">{{ $timeline->created_at->format('d M, Y • h:i A') }}</span>
                            </div>
                            <p class="text-gray-700 mt-1 leading-relaxed">{{ $timeline->note }}</p>
                            <span class="text-[10px] text-gray-400 mt-0.5 block">{{ __('Actor:') }} {{ ucfirst($timeline->actor_type) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">{{ __('No timeline entries recorded yet.') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- 5. Ordered Artifacts & Financial Summary Grid -->
            <div class="pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Left: Purchased Products -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Ordered Heirlooms') }}</h4>
                    <div class="space-y-3 divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="pt-3 first:pt-0 flex items-center gap-3">
                                <img 
                                    src="{{ $item->product?->media->first()?->file_path ?? 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=200' }}" 
                                    alt="{{ $item->product_title }}" 
                                    class="w-12 h-12 rounded-xl object-cover bg-gray-50 border border-gray-200 shrink-0"
                                >
                                <div class="flex-grow min-w-0">
                                    <h5 class="text-xs font-semibold text-gray-900 truncate">{{ $item->product_title }}</h5>
                                    <div class="text-[10px] text-gray-500">{{ __('Qty:') }} {{ $item->quantity }} • SKU: {{ $item->sku }}</div>
                                </div>
                                <div class="text-xs font-bold text-gray-900 font-mono shrink-0">
                                    ৳{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Financial Breakdown & Balance -->
                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-3 text-xs">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700">{{ __('Financial Ledger') }}</h4>
                    
                    <div class="space-y-1.5 text-gray-600">
                        <div class="flex justify-between">
                            <span>{{ __('Items Subtotal:') }}</span>
                            <span class="font-mono text-gray-900">৳{{ number_format($order->subtotal_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Delivery Fee:') }}</span>
                            <span class="font-mono text-gray-900">৳{{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-700">
                                <span>{{ __('Discount:') }}</span>
                                <span class="font-mono">-৳{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-gray-300 flex justify-between items-baseline font-bold text-sm text-gray-900">
                        <span>{{ __('Total Amount:') }}</span>
                        <span class="font-mono text-base text-[#214E34]">৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    <div class="pt-2 border-t border-gray-200 space-y-1">
                        <div class="flex justify-between text-emerald-700 font-semibold">
                            <span>{{ __('Paid Amount:') }}</span>
                            <span class="font-mono">৳{{ number_format($order->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-rose-700 font-bold">
                            <span>{{ __('Balance Due on Delivery:') }}</span>
                            <span class="font-mono">৳{{ number_format($dueAmount, 2) }}</span>
                        </div>
                    </div>

                    @if(!empty($order->transaction_id))
                        <div class="pt-2 border-t border-gray-200 text-[11px] text-gray-600 flex justify-between items-center">
                            <span>{{ __('Transaction ID:') }}</span>
                            <span class="font-mono font-bold bg-white px-2 py-0.5 rounded border border-gray-300 text-gray-800">{{ $order->transaction_id }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="pt-6 border-t border-[#E8E8E8] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>{{ __('Customer Concierge Hotline:') }} <strong class="text-gray-900">+880 1700 000 000</strong></span>
                </div>

                <div class="flex items-center flex-wrap gap-2.5">
                    @if(!in_array($order->status, [\App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::DELIVERED, \App\Enums\OrderStatus::CANCELLED], true))
                        <button type="button" @click="showCancelModal = true" class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold transition">
                            {{ __('Request Cancellation or Change') }}
                        </button>
                    @endif

                    <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold transition">
                        {{ __('Print Receipt 🖨️') }}
                    </button>
                    <a href="{{ route('shop.index') }}" class="px-4 py-2 rounded-xl bg-[#214E34] hover:bg-[#1a3d29] text-white font-bold transition">
                        {{ __('Continue Shopping →') }}
                    </a>
                </div>
            </div>

            <!-- Cancellation Request Modal -->
            <div x-show="showCancelModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="showCancelModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-gray-200">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900 text-base">{{ __('Request Order Modification or Cancellation') }}</h3>
                        <button type="button" @click="showCancelModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                    </div>

                    <form method="POST" action="{{ route('track.order.cancel-request', $order->order_number) }}" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">{{ __('Select Cancellation Reason *') }}</label>
                            <select name="reason" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none text-gray-800">
                                <option value="ভুল প্রোডাক্ট বা কোয়ান্টিটি অর্ডার করেছি">{{ __('Ordered wrong item or quantity') }}</option>
                                <option value="ডেলিভারি ঠিকানা বা মোবাইল নম্বর পরিবর্তন করতে চাই">{{ __('Need to change delivery address or phone number') }}</option>
                                <option value="অন্য পেমেন্ট পদ্ধতিতে অর্ডার করতে চাই">{{ __('Want to switch payment method') }}</option>
                                <option value="দেরি হবে মনে হচ্ছে / পরবর্তীতে অর্ডার করব">{{ __('Delivery will take too long / will order later') }}</option>
                                <option value="অন্যান্য কারণ">{{ __('Other reason') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">{{ __('Additional Notes or Remarks (Optional)') }}</label>
                            <textarea name="notes" rows="3" placeholder="{{ __('Describe your request in detail...') }}" 
                                      class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none text-gray-800"></textarea>
                        </div>

                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800 leading-snug">
                            {{ __('⚠️ Submitting this request will temporarily pause order processing while our curator team reviews it and gets in touch with you.') }}
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="showCancelModal = false" class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 font-semibold">
                                {{ __('Go Back') }}
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold transition shadow">
                                {{ __('Submit Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    @else
        @if(!empty($queriedNumber))
            <div class="p-12 text-center bg-white rounded-3xl border border-rose-200 space-y-4 shadow-soft-xs">
                <span class="text-4xl">🔍</span>
                <h3 class="text-lg font-bold text-gray-800">{{ __('No Order Found') }}</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">
                    {!! __('No matching order was found for ":number". Please verify the reference number or phone number and try again.', ['number' => '<span class="font-mono font-bold text-gray-700">' . e($queriedNumber) . '</span>']) !!}
                </p>
            </div>
        @else
            <div class="p-12 text-center bg-white rounded-3xl border border-gray-200 space-y-4 shadow-soft-xs">
                <span class="text-4xl">📦</span>
                <h3 class="text-lg font-bold text-[#214E34]">{{ __('Track Your Heirloom') }}</h3>
                <p class="text-xs text-gray-500 max-w-md mx-auto">
                    {{ __('Enter your order ID or mobile phone number above to inspect real-time progress, payment verification, and courier dispatch logs.') }}
                </p>
            </div>
        @endif
    @endif

</div>
@endsection
