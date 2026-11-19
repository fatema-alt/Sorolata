@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ __('Track your order') }} — #{{ $order?->order_number ?? 'Search' }} | Sorolata</title>
    <meta name="description" content="Follow the handcrafting, payment verification, and courier transit of your acquired Bangladeshi heirloom.">
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6" x-data="{
    copiedTrx: false,
    showCancelModal: false,
    copy(text) {
        if (!text) return;
        navigator.clipboard.writeText(text);
        this.copiedTrx = true;
        setTimeout(() => { this.copiedTrx = false; }, 2000);
    }
}">
    
    <!-- 1. Search Box Header (Responsive Row matching Desktop Mockup) -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-2">
        <div class="space-y-1.5">
            <h1 class="font-editorial-heading text-4xl sm:text-5xl text-[#1D1D1D] font-light">
                {{ __('Track your order') }}
            </h1>
            <span class="sr-only">{{ __('Order Tracking & Delivery Status') }} - {{ __('Track Your Heirloom') }}</span>
            <p class="text-xs text-[#777777]">
                {{ __('Enter your order number (SRL-XXXXXXXX) or the mobile number you ordered with.') }}
            </p>
        </div>

        <form action="{{ route('track.order') }}" method="GET" class="flex gap-2 w-full md:w-auto shrink-0">
            <div class="relative flex-grow md:w-72">
                <input 
                    type="text" 
                    name="order_number" 
                    value="{{ $queriedNumber ?? '' }}"
                    placeholder="{{ __('SRL-XXXXXXXX') }}" 
                    class="w-full text-xs px-4 py-3 rounded-2xl border border-gray-300 bg-white text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34] font-mono shadow-xs"
                    required
                >
            </div>
            <button type="submit" class="px-6 py-3 bg-[#214E34] hover:bg-[#163824] text-white text-xs font-bold rounded-2xl shadow-xs transition-all shrink-0 cursor-pointer">
                <span>{{ __('Track order') }}</span>
            </button>
        </form>
    </div>

    <!-- Success Message Banner (e.g. after TrxID submission) -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-3 shadow-xs">
            <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">✓</span>
            <div>
                <strong class="font-bold text-xs text-emerald-950 inline-block mr-1">{{ __('Done') }}</strong>
                <span class="text-emerald-800 leading-relaxed">{{ session('success') }}</span>
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

        <!-- 2. Main Order Card (Wide Desktop Layout) -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-[#E8E8E8] shadow-soft-xs space-y-8">
            
            <!-- Order Header: Number, Date, Badges -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="space-y-1">
                    <div class="text-2xl sm:text-3xl text-[#1D1D1D] flex items-center gap-2">
                        <span class="font-normal">{{ __('Order') }}</span> 
                        <strong class="font-bold font-mono tracking-tight text-gray-900">{{ $order->order_number }}</strong>
                    </div>
                    <div class="text-xs text-gray-500 flex flex-wrap items-center gap-2">
                        <span>{{ __('Placed on') }} {{ $order->created_at->format('d M Y, h:i A') }}</span>
                        <span>•</span>
                        <button type="button" @click="copy('{{ $order->order_number }}')" class="text-xs text-[#214E34] hover:underline font-semibold cursor-pointer">
                            <span x-text="copiedTrx ? @json(__('Order number copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy order number'), JSON_UNESCAPED_UNICODE)">{{ __('Copy order number') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FBF6ED] border border-[#F2E5CE] text-[#8C6424]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4A359]"></span>
                        {{ $order->status->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $isPaid ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-[#FBF6ED] border border-[#F2E5CE] text-[#8C6424]' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isPaid ? 'bg-emerald-500' : 'bg-[#D4A359]' }}"></span>
                        {{ __('Payment:') }} {{ $order->payment_status->label() }}
                    </span>
                </div>
            </div>

            <!-- Customer & Delivery Grid (3 Columns on Desktop) -->
            <div class="pt-6 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                <div>
                    <span class="text-gray-400 text-[11px] uppercase tracking-wider block font-semibold">{{ __('Customer') }}</span>
                    <div class="font-bold text-gray-900 mt-1 text-sm">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <span class="text-gray-400 text-[11px] uppercase tracking-wider block font-semibold">{{ __('Mobile') }}</span>
                    <div class="font-bold text-gray-900 font-mono mt-1 text-sm">{{ $order->customer_phone }}</div>
                </div>
                <div>
                    <span class="text-gray-400 text-[11px] uppercase tracking-wider block font-semibold">{{ __('Payment method') }}</span>
                    <div class="font-bold text-gray-900 mt-1 text-sm">{{ $order->payment_gateway?->displayName() ?? ucfirst($order->payment_gateway?->value ?? 'Cash on Delivery (COD)') }}</div>
                </div>
                <div class="sm:col-span-3">
                    <span class="text-gray-400 text-[11px] uppercase tracking-wider block font-semibold">{{ __('Delivery to') }}</span>
                    <div class="font-medium text-gray-900 mt-1 leading-relaxed">
                        {{ $order->shipping_address['address'] ?? ($order->shipping_address['line_1'] ?? '') }}, {{ $order->shipping_address['district'] ?? ($order->shipping_address['city'] ?? __('Dhaka')) }}
                    </div>
                </div>
            </div>

            <!-- Payment Check In Progress or Call To Action -->
            @if(!empty($order->transaction_id) && !$isPaid)
                <div class="p-4 rounded-2xl bg-[#EEF4FB] border border-[#D5E3F5] text-[#1E3A8A] text-xs flex items-center gap-3.5">
                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="space-y-0.5">
                        <strong class="font-bold text-[#1E3A8A] text-xs block">{{ __('Payment check in progress') }}</strong>
                        <p class="text-blue-700 leading-relaxed text-[11px]">
                            {{ __('Our accounts team is matching your TrxID :trx with the statement. We will confirm your parcel as soon as it matches.', ['trx' => $order->transaction_id]) }}
                        </p>
                    </div>
                </div>
            @elseif(!$isPaid && empty($order->transaction_id))
                <div class="p-4 rounded-2xl bg-amber-50/90 border border-amber-200 text-amber-950 text-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <strong class="font-bold text-amber-900 block">{{ __('Payment required to confirm dispatch') }}</strong>
                        <p class="text-amber-800">{{ __('Please send payment and submit your TrxID to start parcel handcrafting and delivery.') }}</p>
                    </div>
                    <a href="{{ route('payment.manual.instruction', ['order' => $order->order_number]) }}" 
                       class="px-4 py-2 bg-[#214E34] hover:bg-[#163824] text-white font-bold text-xs rounded-xl shadow-xs shrink-0 transition">
                        {{ __('Pay Now →') }}
                    </a>
                </div>
            @endif

            <!-- Courier Tracking Code (If Dispatched) -->
            @if(!empty($order->tracking_number))
                <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-950 text-xs flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider block">{{ __('Courier Tracking') }}</span>
                        <div class="font-mono font-bold text-indigo-900 text-sm mt-0.5">{{ $order->tracking_number }} ({{ $order->courier_name ?? 'Courier' }})</div>
                    </div>
                    <button type="button" @click="copy('{{ $order->tracking_number }}')" class="text-xs text-indigo-700 underline font-semibold cursor-pointer">
                        {{ __('Copy') }}
                    </button>
                </div>
            @endif

            <!-- 3. Order Progress Stepper (Horizontal on Desktop, Vertical on Mobile) -->
            <div class="pt-6 border-t border-gray-100 space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">{{ __('Order progress') }}</h3>

                <!-- Desktop Stepper (Grid of 5 Steps with Connecting Line) -->
                <div class="hidden md:block">
                    <div class="grid grid-cols-5 relative">
                        <!-- Horizontal connector line behind circles -->
                        <div class="absolute top-4 left-[10%] right-[10%] h-0.5 border-t-2 border-dashed border-gray-200 -z-0"></div>

                        <!-- Step 1: Order placed -->
                        <div class="relative z-10 flex flex-col items-center text-center px-1">
                            <span class="w-8 h-8 rounded-full {{ $currentStep >= 1 ? 'bg-white text-[#214E34] border-2 border-[#214E34]' : 'bg-white text-gray-300 border-2 border-gray-200' }} flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ $currentStep > 1 ? '✓' : '1' }}
                            </span>
                            <div class="font-bold text-gray-900 text-xs mt-3">{{ __('Order placed') }}</div>
                            <p class="text-[10px] text-gray-500 mt-0.5 leading-snug">{{ __('Registered in our system') }}</p>
                        </div>

                        <!-- Step 2: Payment check -->
                        <div class="relative z-10 flex flex-col items-center text-center px-1">
                            <span class="w-8 h-8 rounded-full {{ $currentStep >= 2 ? 'bg-white text-[#214E34] border-2 border-[#214E34]' : ($currentStep == 1 && !empty($order->transaction_id) ? 'bg-white text-blue-600 border-2 border-blue-600' : 'bg-white text-gray-300 border-2 border-gray-200') }} flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ $currentStep > 2 ? '✓' : '2' }}
                            </span>
                            <div class="font-bold text-gray-900 text-xs mt-3">{{ __('Payment check') }}</div>
                            <p class="text-[10px] text-gray-500 mt-0.5 leading-snug">{{ !empty($order->transaction_id) ? __('Confirming your TrxID') : __('Awaiting payment submission') }}</p>
                        </div>

                        <!-- Step 3: Packed and sealed -->
                        <div class="relative z-10 flex flex-col items-center text-center px-1">
                            <span class="w-8 h-8 rounded-full {{ $currentStep >= 3 ? 'bg-white text-[#214E34] border-2 border-[#214E34]' : 'bg-white text-gray-300 border-2 border-gray-200' }} flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ $currentStep > 3 ? '✓' : '3' }}
                            </span>
                            <div class="font-bold text-gray-900 text-xs mt-3">{{ __('Packed and sealed') }}</div>
                            <p class="text-[10px] text-gray-500 mt-0.5 leading-snug">{{ __('Quality seal from the loom') }}</p>
                        </div>

                        <!-- Step 4: With the courier -->
                        <div class="relative z-10 flex flex-col items-center text-center px-1">
                            <span class="w-8 h-8 rounded-full {{ $currentStep >= 4 ? 'bg-white text-[#214E34] border-2 border-[#214E34]' : 'bg-white text-gray-300 border-2 border-gray-200' }} flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ $currentStep > 4 ? '✓' : '4' }}
                            </span>
                            <div class="font-bold text-gray-900 text-xs mt-3">{{ __('With the courier') }}</div>
                            <p class="text-[10px] text-gray-500 mt-0.5 leading-snug">{{ __('On its way to you') }}</p>
                        </div>

                        <!-- Step 5: Delivered -->
                        <div class="relative z-10 flex flex-col items-center text-center px-1">
                            <span class="w-8 h-8 rounded-full {{ $currentStep >= 5 ? 'bg-white text-[#214E34] border-2 border-[#214E34]' : 'bg-white text-gray-300 border-2 border-gray-200' }} flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ $currentStep >= 5 ? '✓' : '5' }}
                            </span>
                            <div class="font-bold text-gray-900 text-xs mt-3">{{ __('Delivered') }}</div>
                            <p class="text-[10px] text-gray-500 mt-0.5 leading-snug">{{ __('In your hands') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Stepper (Vertical Clean Stepper) -->
                <div class="md:hidden relative pl-6 space-y-5 before:absolute before:left-3 before:top-3 before:bottom-3 before:w-0.5 before:bg-gray-200">
                    <!-- Step 1 -->
                    <div class="relative text-xs">
                        <span class="absolute -left-6 top-0 w-6 h-6 rounded-full {{ $currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-[11px] ring-4 ring-white">
                            {{ $currentStep > 1 ? '✓' : '1' }}
                        </span>
                        <div class="font-bold text-gray-900">{{ __('Order placed') }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Registered in our system') }}</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-xs">
                        <span class="absolute -left-6 top-0 w-6 h-6 rounded-full {{ $currentStep >= 2 ? 'bg-emerald-600 text-white' : ($currentStep == 1 && !empty($order->transaction_id) ? 'bg-blue-600 text-white animate-pulse' : 'bg-gray-200 text-gray-600') }} flex items-center justify-center font-bold text-[11px] ring-4 ring-white">
                            {{ $currentStep > 2 ? '✓' : '2' }}
                        </span>
                        <div class="font-bold text-gray-900">{{ __('Payment check') }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ !empty($order->transaction_id) ? __('Confirming your TrxID') : __('Awaiting payment submission') }}</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-xs">
                        <span class="absolute -left-6 top-0 w-6 h-6 rounded-full {{ $currentStep >= 3 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-[11px] ring-4 ring-white">
                            {{ $currentStep > 3 ? '✓' : '3' }}
                        </span>
                        <div class="font-bold text-gray-900">{{ __('Packed and sealed') }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Quality seal from the loom') }}</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative text-xs">
                        <span class="absolute -left-6 top-0 w-6 h-6 rounded-full {{ $currentStep >= 4 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-[11px] ring-4 ring-white">
                            {{ $currentStep > 4 ? '✓' : '4' }}
                        </span>
                        <div class="font-bold text-gray-900">{{ __('With the courier') }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('On its way to you') }}</p>
                    </div>

                    <!-- Step 5 -->
                    <div class="relative text-xs">
                        <span class="absolute -left-6 top-0 w-6 h-6 rounded-full {{ $currentStep >= 5 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-[11px] ring-4 ring-white">
                            {{ $currentStep >= 5 ? '✓' : '5' }}
                        </span>
                        <div class="font-bold text-gray-900">{{ __('Delivered') }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('In your hands') }}</p>
                    </div>
                </div>
            </div>

            <!-- 4. Two-Column Desktop Section: Left = Order History, Right = Items & Financial Ledger -->
            <div class="pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-10">
                
                <!-- Col A: Order History Timeline -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">{{ __('Order history') }}</h3>

                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                        @forelse($order->timelines as $timeline)
                            <div class="relative text-xs">
                                <span class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full {{ $loop->first ? 'bg-[#214E34]' : 'bg-gray-300' }} ring-4 ring-white"></span>
                                <div class="font-bold text-gray-900 leading-snug">{{ $timeline->localizedStatus() }}</div>
                                <p class="text-gray-600 mt-1 leading-relaxed text-[11px]">{{ $timeline->localizedNote() }}</p>
                                <div class="text-[10px] text-gray-400 mt-1">
                                    {{ $timeline->created_at->format('M d, Y, h:i A') }} • {{ __('By :actor', ['actor' => __($timeline->actor_type)]) }}
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500">{{ __('Order registered.') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Col B: Items in this order & Financial Ledger -->
                <div class="space-y-6">
                    
                    <!-- Items List -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">{{ __('Items in this order') }}</h3>

                        <div class="space-y-3 divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <div class="pt-3 first:pt-0 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if($item->product && $item->product->media->first())
                                            <img src="{{ $item->product->media->first()->file_path }}" alt="{{ $item->product_title }}" class="w-12 h-12 rounded-xl object-cover bg-gray-50 border border-gray-200 shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-lg shrink-0">
                                                🏺
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-bold text-gray-900 leading-snug truncate">{{ $item->product_title }}</h4>
                                            <div class="text-[10px] text-gray-500 mt-0.5">
                                                {{ __('Qty :qty', ['qty' => $item->quantity]) }} • SKU: {{ $item->sku }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-bold text-gray-900 font-mono shrink-0">
                                        ৳{{ number_format($item->subtotal, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Financial Summary Ledger Box -->
                    <div class="bg-[#F8F6F2] rounded-2xl p-5 space-y-2.5 text-xs border border-gray-100">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>{{ __('Subtotal') }}</span>
                            <span class="font-mono text-gray-900 font-semibold">৳{{ number_format($order->subtotal_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>{{ __('Delivery') }}</span>
                            <span class="font-mono text-gray-900 font-semibold">৳{{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between items-center text-emerald-700">
                                <span>{{ __('Discount') }}</span>
                                <span class="font-mono font-semibold">-৳{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="pt-2 border-t border-gray-200/80 flex justify-between items-center font-bold text-gray-900 text-sm">
                            <span>{{ __('Total') }}</span>
                            <span class="font-mono text-[#214E34]">৳{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="pt-2 border-t border-gray-200/80 flex justify-between items-center text-emerald-800 font-semibold">
                            <span>{{ __('Paid') }}</span>
                            <span class="font-mono">৳{{ number_format($order->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center font-bold text-gray-900">
                            <span>{{ __('Due on delivery') }}</span>
                            <span class="font-mono text-gray-900">৳{{ number_format($dueAmount, 2) }}</span>
                        </div>
                        @if(!empty($order->transaction_id))
                            <div class="pt-2 border-t border-gray-200/80 flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Transaction ID') }}</span>
                                <span class="bg-white border border-gray-300 px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold text-gray-800 shadow-2xs">
                                    {{ $order->transaction_id }}
                                </span>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

            <!-- 5. Footer Actions: Help, Cancel Request, Print & Shop Buttons -->
            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <div class="text-gray-500">
                    {{ __('Need help? Call') }} <strong class="text-gray-900 font-mono">+880 1700 000 000</strong>
                </div>

                @if(!in_array($order->status, [\App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::DELIVERED, \App\Enums\OrderStatus::CANCELLED], true))
                    <div>
                        <button type="button" @click="showCancelModal = true" class="text-rose-700 hover:underline font-semibold cursor-pointer">
                            {{ __('Request cancellation or change') }}
                        </button>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-800 font-bold transition cursor-pointer">
                        {{ __('Print receipt') }}
                    </button>
                    <a href="{{ route('shop.index') }}" class="px-5 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#163824] text-white font-bold transition shadow-xs">
                        {{ __('Continue shopping') }}
                    </a>
                </div>
            </div>

        </div>

        <!-- Cancellation Modal -->
        <div x-show="showCancelModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showCancelModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-gray-200">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">{{ __('Request Cancellation or Change') }}</h3>
                    <button type="button" @click="showCancelModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold cursor-pointer">✕</button>
                </div>

                <form method="POST" action="{{ route('track.order.cancel-request', $order->order_number) }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">{{ __('Select Reason *') }}</label>
                        <select name="reason" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none text-gray-800">
                            <option value="ভুল প্রোডাক্ট বা কোয়ান্টিটি অর্ডার করেছি">{{ __('Ordered wrong item or quantity') }}</option>
                            <option value="ডেলিভারি ঠিকানা বা মোবাইল নম্বর পরিবর্তন করতে চাই">{{ __('Need to change delivery address or phone number') }}</option>
                            <option value="অন্য পেমেন্ট পদ্ধতিতে অর্ডার করতে চাই">{{ __('Want to switch payment method') }}</option>
                            <option value="দেরি হবে মনে হচ্ছে / পরবর্তীতে অর্ডার করব">{{ __('Delivery will take too long / will order later') }}</option>
                            <option value="অন্যান্য কারণ">{{ __('Other reason') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">{{ __('Additional Notes (Optional)') }}</label>
                        <textarea name="notes" rows="3" placeholder="{{ __('Describe your request...') }}" 
                                  class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 focus:border-[#214E34] focus:outline-none text-gray-800"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showCancelModal = false" class="px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 font-semibold cursor-pointer">
                            {{ __('Go Back') }}
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold transition shadow-xs cursor-pointer">
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @else
        @if(!empty($queriedNumber))
            <div class="p-8 text-center bg-white rounded-3xl border border-gray-200 space-y-3 shadow-soft-xs">
                <span class="text-3xl">🔍</span>
                <h3 class="text-base font-bold text-gray-800">{{ __('No Order Found') }}</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">
                    {{ __('No matching order was found for ":number". Please verify the order number or phone number.', ['number' => $queriedNumber]) }}
                </p>
            </div>
        @else
            <div class="p-8 text-center bg-white rounded-3xl border border-gray-200 space-y-3 shadow-soft-xs">
                <span class="text-3xl">📦</span>
                <h3 class="text-base font-bold text-[#214E34]">{{ __('Trace Your Order') }}</h3>
                <p class="text-xs text-gray-500 max-w-md mx-auto">
                    {{ __('Enter your order reference above (e.g. SRL-XXXXXXXX) or phone number to check live status.') }}
                </p>
            </div>
        @endif
    @endif

</div>
@endsection
