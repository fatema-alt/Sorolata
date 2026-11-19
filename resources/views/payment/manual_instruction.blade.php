@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ __('Complete Your Payment') }} — #{{ $order->order_number }} | Sorolata</title>
@endsection

@section('content')
@php
    $shippingFee = (float) $order->shipping_fee;
    $totalAmount = (float) $order->total_amount;
    $subtotalAmount = (float) $order->subtotal_amount;
    $defaultAdvance = $shippingFee > 0 ? $shippingFee : 80.00;
@endphp

<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-12" x-data="{ 
    paymentType: 'cod', // 'cod' or 'full'
    activeChannel: 'bkash', // 'bkash', 'nagad', 'brac', 'dbbl'
    shippingFee: {{ $defaultAdvance }},
    totalAmount: {{ $totalAmount }},
    subtotal: {{ $subtotalAmount }},
    copiedText: '',
    copy(text) {
        if (!text) return;
        navigator.clipboard.writeText(text);
        this.copiedText = text;
        setTimeout(() => { this.copiedText = ''; }, 2000);
    },
    get payableNow() {
        return this.paymentType === 'cod' ? this.shippingFee : this.totalAmount;
    },
    get dueOnDelivery() {
        return this.paymentType === 'cod' ? (this.totalAmount - this.shippingFee) : 0;
    }
}">

    <!-- Top Status Badge -->
    <div class="mb-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            {{ __('Order placed, payment pending') }}
        </span>
    </div>

    <!-- Page Title -->
    <div class="space-y-1 mb-6">
        <h1 class="font-editorial-heading text-3xl sm:text-4xl text-[#1D1D1D] font-light">
            {{ __('Complete your payment') }}
        </h1>
        <span class="sr-only">{{ __('Payment Instructions & TrxID Submission') }}</span>
        <p class="text-xs text-[#777777]">
            {{ __('Order') }} <strong class="font-mono text-gray-900">#{{ $order->order_number }}</strong>, {{ __('placed on') }} {{ $order->created_at->format('d M, Y') }}
        </p>
    </div>

    <!-- Payment Choice / Summary Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-[#E8E8E8] shadow-soft-xs space-y-5 mb-8">
        
        <!-- Payment Mode Toggle: COD vs Full Online Payment -->
        <div class="p-1 rounded-2xl bg-[#F4F1EA] grid grid-cols-2 gap-1 text-xs font-semibold">
            <button 
                type="button" 
                @click="paymentType = 'cod'" 
                :class="paymentType === 'cod' ? 'bg-white text-[#214E34] shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                class="py-2.5 px-3 rounded-xl transition-all text-center flex items-center justify-center gap-1.5"
            >
                <span>📦</span>
                <span>{{ __('Cash on Delivery (COD)') }}</span>
            </button>
            <button 
                type="button" 
                @click="paymentType = 'full'" 
                :class="paymentType === 'full' ? 'bg-white text-[#214E34] shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                class="py-2.5 px-3 rounded-xl transition-all text-center flex items-center justify-center gap-1.5"
            >
                <span>💳</span>
                <span>{{ __('Full Order Payment') }}</span>
            </button>
        </div>

        <!-- Highlighted Payable Amount -->
        <div class="pt-2 border-t border-gray-100">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold" x-text="paymentType === 'cod' ? @json(__('Advance delivery charge to pay now'), JSON_UNESCAPED_UNICODE) : @json(__('Total order amount to pay now'), JSON_UNESCAPED_UNICODE)">
                {{ __('Advance delivery charge to pay now') }}
            </div>
            <div class="text-3xl sm:text-4xl font-black text-[#214E34] mt-0.5">
                ৳<span x-text="payableNow.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
            </div>
        </div>

        <!-- Customer Identity -->
        <div class="text-xs text-gray-700 space-y-0.5 pt-2 border-t border-gray-100">
            <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
            <div class="text-gray-500 font-mono">{{ $order->customer_phone }}</div>
        </div>

        <!-- Financial Breakdown Box -->
        <div class="bg-[#F8F6F2] rounded-2xl p-4 space-y-2 text-xs">
            <div class="flex justify-between items-center text-gray-700">
                <span class="font-semibold" x-text="paymentType === 'cod' ? @json(__('Pay courier advance now'), JSON_UNESCAPED_UNICODE) : @json(__('Pay full amount now'), JSON_UNESCAPED_UNICODE)">{{ __('Pay now') }}</span>
                <span class="font-bold text-[#214E34]">৳<span x-text="payableNow.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
            </div>
            <div class="flex justify-between items-center text-gray-700">
                <span>{{ __('Pay the courier on delivery') }}</span>
                <span class="font-semibold text-amber-800">৳<span x-text="dueOnDelivery.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
            </div>
            <div class="pt-2 border-t border-[#E8E8E8] flex justify-between items-center font-bold text-gray-900">
                <span>{{ __('Order total') }}</span>
                <span>৳{{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>

        <!-- COD Advance Notice -->
        <template x-if="paymentType === 'cod'">
            <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200 text-xs text-amber-900 flex items-start gap-2.5">
                <span class="text-base">🛡️</span>
                <p class="leading-relaxed">
                    {{ __('To stop fake orders and returned parcels, only the courier charge is paid in advance. Pay the rest in cash when the parcel arrives.') }}
                </p>
            </div>
        </template>

        <!-- Trust Note -->
        <div class="flex items-center gap-2 text-[11px] text-gray-500">
            <span class="text-emerald-700">✓</span>
            <span>{{ __('Only send money to the accounts shown on this page. We never ask for your PIN or OTP.') }}</span>
        </div>
    </div>

    <!-- Step 1: Select Channel & View Instructions -->
    <div class="space-y-4 mb-8">
        <div class="flex items-center gap-3">
            <span class="w-7 h-7 rounded-full bg-[#214E34] text-white flex items-center justify-center font-bold text-xs shrink-0">1</span>
            <div>
                <h2 class="text-base font-bold text-gray-900">
                    {{ __('Send') }} ৳<span x-text="payableNow.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> {{ __('to one of these accounts') }}
                </h2>
                <p class="text-xs text-gray-500">{{ __('Put your order number in the reference field when you send.') }}</p>
            </div>
        </div>

        <!-- Channel Selection Cards with Logos / Images -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            
            <!-- 1. bKash Card -->
            <button 
                type="button" 
                @click="activeChannel = 'bkash'"
                :class="activeChannel === 'bkash' ? 'border-[#E2136E] bg-pink-50/60 ring-2 ring-[#E2136E]/40 shadow-xs' : 'border-gray-200 hover:border-gray-300 bg-white'"
                class="p-3.5 rounded-2xl border text-left transition-all relative flex flex-col justify-between min-h-[92px] cursor-pointer"
            >
                @if(!empty($settings['manual_bkash_logo']))
                    <img src="{{ $settings['manual_bkash_logo'] }}" alt="bKash" class="h-6 object-contain self-start">
                @else
                    <img src="{{ asset('images/payments/bkash.svg') }}" alt="bKash" class="h-6 w-auto object-contain self-start">
                @endif
                <div class="mt-2">
                    <div class="text-xs font-bold text-gray-900 flex items-center justify-between">
                        <span>bKash</span>
                        <template x-if="activeChannel === 'bkash'">
                            <span class="text-[#E2136E] font-bold text-xs">✓</span>
                        </template>
                    </div>
                    <span class="text-[10px] text-gray-500">{{ __('Mobile banking') }}</span>
                </div>
            </button>

            <!-- 2. Nagad Card -->
            <button 
                type="button" 
                @click="activeChannel = 'nagad'"
                :class="activeChannel === 'nagad' ? 'border-[#F7941D] bg-amber-50/60 ring-2 ring-[#F7941D]/40 shadow-xs' : 'border-gray-200 hover:border-gray-300 bg-white'"
                class="p-3.5 rounded-2xl border text-left transition-all relative flex flex-col justify-between min-h-[92px] cursor-pointer"
            >
                @if(!empty($settings['manual_nagad_logo']))
                    <img src="{{ $settings['manual_nagad_logo'] }}" alt="Nagad" class="h-6 object-contain self-start">
                @else
                    <img src="{{ asset('images/payments/nagad.svg') }}" alt="Nagad" class="h-6 w-auto object-contain self-start">
                @endif
                <div class="mt-2">
                    <div class="text-xs font-bold text-gray-900 flex items-center justify-between">
                        <span>Nagad</span>
                        <template x-if="activeChannel === 'nagad'">
                            <span class="text-[#F7941D] font-bold text-xs">✓</span>
                        </template>
                    </div>
                    <span class="text-[10px] text-gray-500">{{ __('Mobile banking') }}</span>
                </div>
            </button>

            <!-- 3. BRAC Bank Card -->
            <button 
                type="button" 
                @click="activeChannel = 'brac'"
                :class="activeChannel === 'brac' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-600/40 shadow-xs' : 'border-gray-200 hover:border-gray-300 bg-white'"
                class="p-3.5 rounded-2xl border text-left transition-all relative flex flex-col justify-between min-h-[92px] cursor-pointer"
            >
                @if(!empty($settings['manual_brac_logo']))
                    <img src="{{ $settings['manual_brac_logo'] }}" alt="BRAC Bank" class="h-6 object-contain self-start">
                @else
                    <img src="{{ asset('images/payments/brac_bank.svg') }}" alt="BRAC Bank" class="h-6 w-auto object-contain self-start">
                @endif
                <div class="mt-2">
                    <div class="text-xs font-bold text-gray-900 flex items-center justify-between">
                        <span>BRAC Bank</span>
                        <template x-if="activeChannel === 'brac'">
                            <span class="text-blue-600 font-bold text-xs">✓</span>
                        </template>
                    </div>
                    <span class="text-[10px] text-gray-500">{{ __('Bank transfer') }}</span>
                </div>
            </button>

            <!-- 4. DBBL / Rocket Card -->
            <button 
                type="button" 
                @click="activeChannel = 'dbbl'"
                :class="activeChannel === 'dbbl' ? 'border-purple-600 bg-purple-50/60 ring-2 ring-purple-600/40 shadow-xs' : 'border-gray-200 hover:border-gray-300 bg-white'"
                class="p-3.5 rounded-2xl border text-left transition-all relative flex flex-col justify-between min-h-[92px] cursor-pointer"
            >
                @if(!empty($settings['manual_dbbl_logo']))
                    <img src="{{ $settings['manual_dbbl_logo'] }}" alt="Rocket" class="h-6 object-contain self-start">
                @else
                    <img src="{{ asset('images/payments/rocket.svg') }}" alt="Rocket" class="h-6 w-auto object-contain self-start">
                @endif
                <div class="mt-2">
                    <div class="text-xs font-bold text-gray-900 flex items-center justify-between">
                        <span>Dutch-Bangla</span>
                        <template x-if="activeChannel === 'dbbl'">
                            <span class="text-purple-600 font-bold text-xs">✓</span>
                        </template>
                    </div>
                    <span class="text-[10px] text-gray-500">{{ __('Nexus / Rocket') }}</span>
                </div>
            </button>
        </div>

        <!-- Selected Channel Account Box -->

        <!-- bKash Box -->
        <div x-show="activeChannel === 'bkash'" class="bg-white rounded-3xl p-6 border border-[#E8E8E8] shadow-soft-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <img src="{{ !empty($settings['manual_bkash_logo']) ? $settings['manual_bkash_logo'] : asset('images/payments/bkash.svg') }}" alt="bKash" class="h-7 w-auto object-contain">
                <h3 class="text-sm font-bold text-gray-900">{{ __('Pay with bKash') }}</h3>
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">{{ __('bKash number') }}</span>
                <div class="text-2xl font-mono font-black text-[#1D1D1D] tracking-wide">
                    {{ $settings['manual_bkash_number'] ?? '01782989118' }}
                </div>
                <div class="pt-1 flex flex-wrap items-center gap-2">
                    <button 
                        type="button" 
                        @click="copy('{{ $settings['manual_bkash_number'] ?? '01782989118' }}')"
                        class="px-3 py-1.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-xs font-semibold text-gray-800 inline-flex items-center gap-1.5 transition cursor-pointer"
                    >
                        <span>📋</span>
                        <span x-text="copiedText === '{{ $settings['manual_bkash_number'] ?? '01782989118' }}' ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy number'), JSON_UNESCAPED_UNICODE)">{{ __('Copy number') }}</span>
                    </button>
                    <span class="text-[11px] px-2.5 py-1 rounded-full bg-pink-100/70 text-[#E2136E] font-semibold">
                        {{ $settings['manual_bkash_type'] ?? __('Personal (Send Money)') }}
                    </span>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 space-y-1 text-xs">
                <div class="text-gray-500">{{ __('Reference to enter:') }} <strong class="font-mono text-gray-900">#{{ $order->order_number }}</strong></div>
                <button type="button" @click="copy('{{ $order->order_number }}')" class="text-[11px] text-emerald-800 hover:underline font-semibold cursor-pointer">
                    <span x-text="copiedText === '{{ $order->order_number }}' ? @json(__('Reference Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy reference'), JSON_UNESCAPED_UNICODE)">{{ __('Copy reference') }}</span>
                </button>
            </div>

            <div class="p-3.5 rounded-2xl bg-[#F8F6F2] text-xs text-gray-700 leading-relaxed space-y-1">
                <strong class="text-gray-900">{{ __('How to pay:') }}</strong>
                <p>{{ $settings['manual_bkash_instructions'] ?? __('Open the bKash app, choose Send Money and send the amount shown. Enter your order number in the reference field.') }}</p>
            </div>
        </div>

        <!-- Nagad Box -->
        <div x-show="activeChannel === 'nagad'" class="bg-white rounded-3xl p-6 border border-[#E8E8E8] shadow-soft-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <img src="{{ !empty($settings['manual_nagad_logo']) ? $settings['manual_nagad_logo'] : asset('images/payments/nagad.svg') }}" alt="Nagad" class="h-7 w-auto object-contain">
                <h3 class="text-sm font-bold text-gray-900">{{ __('Pay with Nagad') }}</h3>
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">{{ __('Nagad number') }}</span>
                <div class="text-2xl font-mono font-black text-[#1D1D1D] tracking-wide">
                    {{ $settings['manual_nagad_number'] ?? '01811002233' }}
                </div>
                <div class="pt-1 flex flex-wrap items-center gap-2">
                    <button 
                        type="button" 
                        @click="copy('{{ $settings['manual_nagad_number'] ?? '01811002233' }}')"
                        class="px-3 py-1.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-xs font-semibold text-gray-800 inline-flex items-center gap-1.5 transition cursor-pointer"
                    >
                        <span>📋</span>
                        <span x-text="copiedText === '{{ $settings['manual_nagad_number'] ?? '01811002233' }}' ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy number'), JSON_UNESCAPED_UNICODE)">{{ __('Copy number') }}</span>
                    </button>
                    <span class="text-[11px] px-2.5 py-1 rounded-full bg-amber-100/70 text-[#F7941D] font-semibold">
                        {{ $settings['manual_nagad_type'] ?? __('Personal (Send Money)') }}
                    </span>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 space-y-1 text-xs">
                <div class="text-gray-500">{{ __('Reference to enter:') }} <strong class="font-mono text-gray-900">#{{ $order->order_number }}</strong></div>
                <button type="button" @click="copy('{{ $order->order_number }}')" class="text-[11px] text-emerald-800 hover:underline font-semibold cursor-pointer">
                    <span x-text="copiedText === '{{ $order->order_number }}' ? @json(__('Reference Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy reference'), JSON_UNESCAPED_UNICODE)">{{ __('Copy reference') }}</span>
                </button>
            </div>

            <div class="p-3.5 rounded-2xl bg-[#F8F6F2] text-xs text-gray-700 leading-relaxed space-y-1">
                <strong class="text-gray-900">{{ __('How to pay:') }}</strong>
                <p>{{ $settings['manual_nagad_instructions'] ?? __('Open the Nagad app, choose Send Money and send the amount shown. Enter your order number in the reference field.') }}</p>
            </div>
        </div>

        <!-- BRAC Bank Box -->
        <div x-show="activeChannel === 'brac'" class="bg-white rounded-3xl p-6 border border-[#E8E8E8] shadow-soft-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <img src="{{ !empty($settings['manual_brac_logo']) ? $settings['manual_brac_logo'] : asset('images/payments/brac_bank.svg') }}" alt="BRAC Bank" class="h-7 w-auto object-contain">
                <h3 class="text-sm font-bold text-gray-900">{{ __('Pay via BRAC Bank Transfer') }}</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-500">{{ __('Account Name:') }}</span>
                    <div class="font-bold text-gray-900">{{ $settings['manual_brac_account_name'] ?? 'Sorolata Heritage Ltd' }}</div>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Account Number:') }}</span>
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-bold text-base text-[#0054A6]">{{ $settings['manual_brac_account_number'] ?? '1501204859632001' }}</span>
                        <button type="button" @click="copy('{{ $settings['manual_brac_account_number'] ?? '1501204859632001' }}')" class="text-[11px] text-blue-600 underline font-semibold cursor-pointer">{{ __('Copy') }}</button>
                    </div>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Branch:') }}</span>
                    <div class="font-semibold text-gray-900">{{ $settings['manual_brac_branch'] ?? 'Gulshan Branch, Dhaka' }}</div>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Routing Number:') }}</span>
                    <div class="font-mono font-bold text-gray-900">{{ $settings['manual_brac_routing_number'] ?? '060261726' }}</div>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-[#F8F6F2] text-xs text-gray-700 leading-relaxed">
                <strong class="text-gray-900">{{ __('Instructions:') }}</strong>
                <p>{{ $settings['manual_brac_instructions'] ?? __('Transfer via BRAC Bank (Astha App or bank deposit) and enter your order number in the narration.') }}</p>
            </div>
        </div>

        <!-- DBBL Box -->
        <div x-show="activeChannel === 'dbbl'" class="bg-white rounded-3xl p-6 border border-[#E8E8E8] shadow-soft-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <img src="{{ !empty($settings['manual_dbbl_logo']) ? $settings['manual_dbbl_logo'] : asset('images/payments/rocket.svg') }}" alt="Rocket" class="h-7 w-auto object-contain">
                <h3 class="text-sm font-bold text-gray-900">{{ __('Pay via Dutch-Bangla / Rocket') }}</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-500">{{ __('Account Name:') }}</span>
                    <div class="font-bold text-gray-900">{{ $settings['manual_dbbl_account_name'] ?? 'Sorolata Artisan Commerce' }}</div>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Account Number:') }}</span>
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-bold text-base text-purple-900">{{ $settings['manual_dbbl_account_number'] ?? '1151200034567' }}</span>
                        <button type="button" @click="copy('{{ $settings['manual_dbbl_account_number'] ?? '1151200034567' }}')" class="text-[11px] text-purple-600 underline font-semibold cursor-pointer">{{ __('Copy') }}</button>
                    </div>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Branch:') }}</span>
                    <div class="font-semibold text-gray-900">{{ $settings['manual_dbbl_branch'] ?? 'Dhanmondi Branch, Dhaka' }}</div>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Routing Number:') }}</span>
                    <div class="font-mono font-bold text-gray-900">{{ $settings['manual_dbbl_routing_number'] ?? '090261345' }}</div>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-[#F8F6F2] text-xs text-gray-700 leading-relaxed">
                <strong class="text-gray-900">{{ __('Instructions:') }}</strong>
                <p>{{ $settings['manual_dbbl_instructions'] ?? __('Transfer via NexusPay app or Rocket and enter your order number in the reference.') }}</p>
            </div>
        </div>
    </div>

    <!-- Step 2: Submit Payment Details Form -->
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <span class="w-7 h-7 rounded-full bg-[#214E34] text-white flex items-center justify-center font-bold text-xs shrink-0">2</span>
            <div>
                <h2 class="text-base font-bold text-gray-900">
                    {{ __('Submit your payment details') }}
                </h2>
                <p class="text-xs text-gray-500">{{ __('Our accounts team will check your TrxID and confirm the order.') }}</p>
            </div>
        </div>

        <form action="{{ route('payment.manual.submit', ['order' => $order->order_number]) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-7 border border-[#E8E8E8] shadow-soft-xs space-y-4">
            @csrf

            <!-- Hidden Fields -->
            <input type="hidden" name="payment_method" :value="activeChannel">
            <input type="hidden" name="payment_type" :value="paymentType">

            <!-- Sender Mobile / Account Number -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-gray-900 uppercase tracking-wider">
                    {{ __('Account you sent from *') }}
                </label>
                <input 
                    type="text" 
                    name="sender_number" 
                    value="{{ old('sender_number', $order->customer_phone) }}" 
                    required 
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-xs font-mono text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                    placeholder="{{ __('+8801700000001') }}"
                >
                <p class="text-[11px] text-gray-500">{{ __('Your bKash or Nagad number, or your bank account number.') }}</p>
            </div>

            <!-- Transaction ID (TrxID) -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-gray-900 uppercase tracking-wider">
                    {{ __('Transaction ID (TrxID) *') }}
                </label>
                <input 
                    type="text" 
                    name="transaction_id" 
                    value="{{ old('transaction_id') }}" 
                    required 
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-xs font-mono font-bold uppercase text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                    placeholder="{{ __('e.g. BL7A8X9K or slip reference') }}"
                >
                <p class="text-[11px] text-gray-500">{{ __('Copy the TrxID from the confirmation SMS you received after sending the money.') }}</p>
            </div>

            <!-- Notes -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-gray-900 uppercase tracking-wider">
                    {{ __('Notes (optional)') }}
                </label>
                <textarea 
                    name="customer_notes" 
                    rows="2" 
                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-xs text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                    placeholder="{{ __('Anything we should know about this payment...') }}"
                ></textarea>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-4 bg-[#214E34] hover:bg-[#163824] text-white font-bold text-xs uppercase tracking-widest rounded-2xl shadow-md transition-all flex items-center justify-center space-x-2 cursor-pointer"
            >
                <span>{{ __('Submit payment details') }}</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Footer Links -->
        <div class="pt-3 text-center space-y-1 text-xs text-gray-500">
            <div>{{ __('Need help? Call') }} <strong class="text-gray-900 font-mono">+880 1700 000 000</strong></div>
            <div>
                <a href="{{ route('track.order', ['order_number' => $order->order_number]) }}" class="text-[#214E34] font-semibold hover:underline">
                    {{ __('Submit later from the order tracking page') }}
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
