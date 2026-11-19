@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ __('Complete Your Payment') }} — #{{ $order->order_number }} | Sorolata</title>
@endsection

@section('content')
@php
    $shippingFee = (float) $order->shipping_fee;
    $totalAmount = (float) $order->total_amount;
    $subtotalAmount = (float) $order->subtotal_amount;
    $advanceCharge = (float) ($order->advance_delivery_charge > 0 ? $order->advance_delivery_charge : ($shippingFee > 0 ? $shippingFee : 80.00));
    $isCod = in_array(strtolower($order->payment_gateway?->value ?? ''), ['cash_on_delivery', 'cod'], true);
    $isAdvanceFee = $order->advance_delivery_charge > 0 && $order->paid_amount == 0;
    $initialMode = ($isCod && $isAdvanceFee) ? 'cod' : 'full';
    $sslCommerzEnabled = ($settings['payment_sslcommerz_enabled'] ?? '0') === '1';
@endphp

<style>
    .payment-layout-container {
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
        padding: 2.5rem 1rem 4rem;
    }
    .payment-two-col {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        align-items: start;
    }
    @media (min-width: 1024px) {
        .payment-two-col {
            grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
            gap: 2.5rem;
        }
    }
    .payment-channels-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .payment-channels-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    .channel-card-selected {
        background-color: #F0FDF4 !important;
        border-color: #22C55E !important;
        box-shadow: 0 0 0 1px #22C55E;
    }
</style>

<div class="payment-layout-container" x-data="{ 
    paymentType: '{{ $initialMode }}', // 'cod' or 'full'
    activeChannel: 'bkash', // 'bkash', 'nagad', 'brac', 'dbbl', 'sslcommerz'
    advanceCharge: {{ $advanceCharge }},
    totalAmount: {{ $totalAmount }},
    copiedText: '',
    copy(text) {
        if (!text) return;
        navigator.clipboard.writeText(text);
        this.copiedText = text;
        setTimeout(() => { this.copiedText = ''; }, 2000);
    },
    get payableNow() {
        return this.paymentType === 'cod' ? this.advanceCharge : this.totalAmount;
    },
    get dueOnDelivery() {
        return this.paymentType === 'cod' ? Math.max(0, this.totalAmount - this.advanceCharge) : 0;
    }
}">

    <!-- Top Status Pill -->
    <div class="mb-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FEF3C7] border border-[#FDE68A] text-[#92400E] text-[11px] font-semibold">
            <span class="w-2 h-2 rounded-full bg-[#D97706] animate-pulse"></span>
            <span>{{ __('Order placed, payment pending') }}</span>
        </span>
    </div>

    <!-- Page Title & Subtitle -->
    <div class="space-y-1 mb-8">
        <h1 class="font-editorial-heading text-3xl sm:text-4xl lg:text-[40px] text-[#1D1D1D] font-light tracking-tight">
            {{ __('Complete your payment') }}
        </h1>
        <p class="text-xs text-[#777777]">
            {{ __('Order') }} <strong class="font-mono text-gray-900">#{{ $order->order_number }}</strong>, {{ __('placed on') }} {{ $order->created_at->format('d M Y') }}
        </p>
    </div>

    <!-- 2-Column Desktop Grid Layout -->
    <div class="payment-two-col">

        <!-- LEFT COLUMN: Steps 1 & 2 -->
        <div class="space-y-6">

            <!-- STEP 1 CARD: Select Channel & View Account -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-[#E8E8E8] shadow-sm space-y-5">
                
                <!-- Step 1 Header -->
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-[#214E34] text-white flex items-center justify-center font-bold text-xs shrink-0">1</span>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-gray-900">
                            {{ __('Send') }} ৳<span x-text="payableNow.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> {{ __('to one of these accounts') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Put your order number in the reference field when you send.') }}</p>
                    </div>
                </div>

                <!-- 2x2 Payment Channels Grid -->
                <div class="payment-channels-grid">

                    <!-- bKash Button -->
                    <button 
                        type="button" 
                        @click="activeChannel = 'bkash'"
                        :class="activeChannel === 'bkash' ? 'channel-card-selected' : 'bg-white border-gray-200 hover:border-gray-300'"
                        class="p-3.5 rounded-2xl border text-left transition-all relative flex items-center justify-between min-h-[76px] cursor-pointer"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-[#E2136E] text-white flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden shadow-xs">
                                @if(!empty($settings['manual_bkash_logo']))
                                    <img src="{{ $settings['manual_bkash_logo'] }}" alt="bKash" class="w-full h-full object-contain p-1">
                                @else
                                    <span>b</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-900">bKash</div>
                                <div class="text-[10px] text-gray-500">{{ __('Mobile banking') }}</div>
                            </div>
                        </div>
                        <template x-if="activeChannel === 'bkash'">
                            <div class="w-5 h-5 rounded-full bg-[#22C55E] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                ✓
                            </div>
                        </template>
                    </button>

                    <!-- Nagad Button -->
                    <button 
                        type="button" 
                        @click="activeChannel = 'nagad'"
                        :class="activeChannel === 'nagad' ? 'channel-card-selected' : 'bg-white border-gray-200 hover:border-gray-300'"
                        class="p-3.5 rounded-2xl border text-left transition-all relative flex items-center justify-between min-h-[76px] cursor-pointer"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-[#F7941D] text-white flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden shadow-xs">
                                @if(!empty($settings['manual_nagad_logo']))
                                    <img src="{{ $settings['manual_nagad_logo'] }}" alt="Nagad" class="w-full h-full object-contain p-1">
                                @else
                                    <span>N</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-900">Nagad</div>
                                <div class="text-[10px] text-gray-500">{{ __('Mobile banking') }}</div>
                            </div>
                        </div>
                        <template x-if="activeChannel === 'nagad'">
                            <div class="w-5 h-5 rounded-full bg-[#22C55E] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                ✓
                            </div>
                        </template>
                    </button>

                    <!-- BRAC Bank Button -->
                    <button 
                        type="button" 
                        @click="activeChannel = 'brac'"
                        :class="activeChannel === 'brac' ? 'channel-card-selected' : 'bg-white border-gray-200 hover:border-gray-300'"
                        class="p-3.5 rounded-2xl border text-left transition-all relative flex items-center justify-between min-h-[76px] cursor-pointer"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-[#0054A6] text-white flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden shadow-xs">
                                @if(!empty($settings['manual_brac_logo']))
                                    <img src="{{ $settings['manual_brac_logo'] }}" alt="BRAC" class="w-full h-full object-contain p-1">
                                @else
                                    <span>B</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-900">BRAC Bank</div>
                                <div class="text-[10px] text-gray-500">{{ __('Bank transfer') }}</div>
                            </div>
                        </div>
                        <template x-if="activeChannel === 'brac'">
                            <div class="w-5 h-5 rounded-full bg-[#22C55E] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                ✓
                            </div>
                        </template>
                    </button>

                    <!-- Dutch-Bangla Button -->
                    <button 
                        type="button" 
                        @click="activeChannel = 'dbbl'"
                        :class="activeChannel === 'dbbl' ? 'channel-card-selected' : 'bg-white border-gray-200 hover:border-gray-300'"
                        class="p-3.5 rounded-2xl border text-left transition-all relative flex items-center justify-between min-h-[76px] cursor-pointer"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-[#E04B26] text-white flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden shadow-xs">
                                @if(!empty($settings['manual_dbbl_logo']))
                                    <img src="{{ $settings['manual_dbbl_logo'] }}" alt="DBBL" class="w-full h-full object-contain p-1">
                                @else
                                    <span>D</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-900">Dutch-Bangla</div>
                                <div class="text-[10px] text-gray-500">{{ __('Bank transfer or Rocket') }}</div>
                            </div>
                        </div>
                        <template x-if="activeChannel === 'dbbl'">
                            <div class="w-5 h-5 rounded-full bg-[#22C55E] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                ✓
                            </div>
                        </template>
                    </button>

                    @if($sslCommerzEnabled)
                        <!-- Optional SSLCommerz Card (when enabled in admin settings) -->
                        <button 
                            type="button" 
                            @click="activeChannel = 'sslcommerz'"
                            :class="activeChannel === 'sslcommerz' ? 'channel-card-selected' : 'bg-white border-gray-200 hover:border-gray-300'"
                            class="sm:col-span-2 p-3.5 rounded-2xl border text-left transition-all relative flex items-center justify-between min-h-[76px] cursor-pointer"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-[#0A3B5C] text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden shadow-xs">
                                    <span>SSL</span>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">Cards &amp; NetBanking (SSLCommerz)</div>
                                    <div class="text-[10px] text-gray-500">Automated Instant Card / MFS Gateway</div>
                                </div>
                            </div>
                            <template x-if="activeChannel === 'sslcommerz'">
                                <div class="w-5 h-5 rounded-full bg-[#22C55E] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                    ✓
                                </div>
                            </template>
                        </button>
                    @endif

                </div>

                <!-- Selected Channel Details Box (Matches Image 2) -->
                
                <!-- 1. bKash Details Box -->
                <div x-show="activeChannel === 'bkash'" class="rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB] p-5 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-md bg-[#E2136E] text-white flex items-center justify-center font-bold text-xs">b</span>
                        <h3 class="text-xs sm:text-sm font-bold text-gray-900">{{ __('Pay with bKash') }}</h3>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-[11px] text-gray-500 font-medium">{{ __('bKash number') }}</span>
                        <div class="text-2xl sm:text-3xl font-mono font-bold text-gray-900 tracking-wide">
                            {{ $settings['manual_bkash_number'] ?? '01782989118' }}
                        </div>
                        <div class="pt-1 flex flex-wrap items-center gap-2">
                            <button 
                                type="button" 
                                @click="copy('{{ $settings['manual_bkash_number'] ?? '01782989118' }}')"
                                class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-800 inline-flex items-center gap-1.5 transition cursor-pointer shadow-2xs"
                            >
                                <span>📋</span>
                                <span x-text="copiedText === '{{ $settings['manual_bkash_number'] ?? '01782989118' }}' ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy number'), JSON_UNESCAPED_UNICODE)">{{ __('Copy number') }}</span>
                            </button>
                            <span class="text-[11px] px-2.5 py-1 rounded-full bg-gray-200/70 text-gray-700 font-medium">
                                {{ $settings['manual_bkash_type'] ?? __('Personal (Send Money)') }}
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-2"></div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <div>
                            {{ __('Reference to enter:') }} <strong class="font-mono text-gray-900 font-bold">#{{ $order->order_number }}</strong>
                            <button type="button" @click="copy('{{ $order->order_number }}')" class="ml-2 text-emerald-700 hover:underline font-semibold cursor-pointer">
                                <span x-text="copiedText === '{{ $order->order_number }}' ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy'), JSON_UNESCAPED_UNICODE)">{{ __('Copy') }}</span>
                            </button>
                        </div>
                        <p class="pt-1 leading-relaxed">
                            <strong class="text-gray-800">{{ __('How to pay:') }}</strong> {{ $settings['manual_bkash_instructions'] ?? __('Open the bKash app, choose Send Money and send the amount shown. Enter your order number in the reference field.') }}
                        </p>
                    </div>
                </div>

                <!-- 2. Nagad Details Box -->
                <div x-show="activeChannel === 'nagad'" class="rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB] p-5 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-md bg-[#F7941D] text-white flex items-center justify-center font-bold text-xs">N</span>
                        <h3 class="text-xs sm:text-sm font-bold text-gray-900">{{ __('Pay with Nagad') }}</h3>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-[11px] text-gray-500 font-medium">{{ __('Nagad number') }}</span>
                        <div class="text-2xl sm:text-3xl font-mono font-bold text-gray-900 tracking-wide">
                            {{ $settings['manual_nagad_number'] ?? '01811002233' }}
                        </div>
                        <div class="pt-1 flex flex-wrap items-center gap-2">
                            <button 
                                type="button" 
                                @click="copy('{{ $settings['manual_nagad_number'] ?? '01811002233' }}')"
                                class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-800 inline-flex items-center gap-1.5 transition cursor-pointer shadow-2xs"
                            >
                                <span>📋</span>
                                <span x-text="copiedText === '{{ $settings['manual_nagad_number'] ?? '01811002233' }}' ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy number'), JSON_UNESCAPED_UNICODE)">{{ __('Copy number') }}</span>
                            </button>
                            <span class="text-[11px] px-2.5 py-1 rounded-full bg-gray-200/70 text-gray-700 font-medium">
                                {{ $settings['manual_nagad_type'] ?? __('Personal (Send Money)') }}
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-2"></div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <div>
                            {{ __('Reference to enter:') }} <strong class="font-mono text-gray-900 font-bold">#{{ $order->order_number }}</strong>
                            <button type="button" @click="copy('{{ $order->order_number }}')" class="ml-2 text-emerald-700 hover:underline font-semibold cursor-pointer">
                                <span x-text="copiedText === '{{ $order->order_number }}' ? @json(__('Copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy'), JSON_UNESCAPED_UNICODE)">{{ __('Copy') }}</span>
                            </button>
                        </div>
                        <p class="pt-1 leading-relaxed">
                            <strong class="text-gray-800">{{ __('How to pay:') }}</strong> {{ $settings['manual_nagad_instructions'] ?? __('Open the Nagad app, choose Send Money and send the amount shown. Enter your order number in the reference field.') }}
                        </p>
                    </div>
                </div>

                <!-- 3. BRAC Bank Details Box -->
                <div x-show="activeChannel === 'brac'" class="rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB] p-5 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-md bg-[#0054A6] text-white flex items-center justify-center font-bold text-xs">B</span>
                        <h3 class="text-xs sm:text-sm font-bold text-gray-900">{{ __('Pay with BRAC Bank') }}</h3>
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

                    <div class="border-t border-dashed border-gray-300 my-2"></div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <div>
                            {{ __('Reference to enter:') }} <strong class="font-mono text-gray-900 font-bold">#{{ $order->order_number }}</strong>
                        </div>
                        <p class="pt-1 leading-relaxed">
                            <strong class="text-gray-800">{{ __('How to pay:') }}</strong> {{ $settings['manual_brac_instructions'] ?? __('Transfer via BRAC Bank (Astha App or bank deposit) and enter your order number in the narration.') }}
                        </p>
                    </div>
                </div>

                <!-- 4. Dutch-Bangla Bank Details Box -->
                <div x-show="activeChannel === 'dbbl'" class="rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB] p-5 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-md bg-[#E04B26] text-white flex items-center justify-center font-bold text-xs">D</span>
                        <h3 class="text-xs sm:text-sm font-bold text-gray-900">{{ __('Pay with Dutch-Bangla / Rocket') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-gray-500">{{ __('Account Name:') }}</span>
                            <div class="font-bold text-gray-900">{{ $settings['manual_dbbl_account_name'] ?? 'Sorolata Artisan Commerce' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('Account Number:') }}</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-base text-gray-900">{{ $settings['manual_dbbl_account_number'] ?? '1151200034567' }}</span>
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

                    <div class="border-t border-dashed border-gray-300 my-2"></div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <div>
                            {{ __('Reference to enter:') }} <strong class="font-mono text-gray-900 font-bold">#{{ $order->order_number }}</strong>
                        </div>
                        <p class="pt-1 leading-relaxed">
                            <strong class="text-gray-800">{{ __('How to pay:') }}</strong> {{ $settings['manual_dbbl_instructions'] ?? __('Transfer via NexusPay app or Rocket and enter your order number in the reference.') }}
                        </p>
                    </div>
                </div>

                @if($sslCommerzEnabled)
                    <!-- 5. SSLCommerz Details Box -->
                    <div x-show="activeChannel === 'sslcommerz'" class="rounded-2xl bg-[#F0FDF4] border border-[#22C55E] p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-md bg-[#0A3B5C] text-white flex items-center justify-center font-bold text-xs">SSL</span>
                                <h3 class="text-xs sm:text-sm font-bold text-gray-900">Instant Automated Online Payment</h3>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">Instant Verification</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Pay with Visa, Mastercard, American Express, bKash, Nagad, Rocket, or internet banking through SSLCommerz secure checkout.
                        </p>
                        <a 
                            href="{{ route('payment.sslcommerz.checkout', ['order' => $order->order_number]) }}" 
                            class="inline-flex items-center justify-center w-full py-3.5 bg-[#0A3B5C] hover:bg-[#072a42] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm"
                        >
                            <span>Proceed to SSLCommerz Checkout &rarr;</span>
                        </a>
                    </div>
                @endif

            </div>


            <!-- STEP 2 CARD: Submit Payment Details Form -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-[#E8E8E8] shadow-sm space-y-5">
                
                <!-- Step 2 Header -->
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-[#214E34] text-white flex items-center justify-center font-bold text-xs shrink-0">2</span>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-gray-900">
                            {{ __('Submit your payment details') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Our accounts team will check your TrxID and confirm the order.') }}</p>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('payment.manual.submit', ['order' => $order->order_number]) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="payment_method" :value="activeChannel">
                    <input type="hidden" name="payment_type" :value="paymentType">

                    <!-- Two-column Inputs: Account sent from & Transaction ID -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1">
                                {{ __('Account you sent from *') }}
                            </label>
                            <input 
                                type="text" 
                                name="sender_number" 
                                value="{{ old('sender_number', $order->customer_phone) }}" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-xs font-mono text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                                placeholder="+8801700000001"
                            >
                            <p class="text-[11px] text-gray-500 mt-1 leading-snug">{{ __('Your bKash or Nagad number, or your bank account number.') }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1">
                                {{ __('Transaction ID (TrxID) *') }}
                            </label>
                            <input 
                                type="text" 
                                name="transaction_id" 
                                value="{{ old('transaction_id') }}" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-xs font-mono font-bold uppercase text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                                placeholder="{{ __('e.g. BL7A8X9K or slip reference') }}"
                            >
                            <p class="text-[11px] text-gray-500 mt-1 leading-snug">{{ __('Copy the TrxID from the confirmation SMS you received after sending the money.') }}</p>
                        </div>
                    </div>

                    <!-- Notes Field -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-800 mb-1">
                            {{ __('Notes (optional)') }}
                        </label>
                        <textarea 
                            name="customer_notes" 
                            rows="2" 
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-xs text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                            placeholder="{{ __('Anything we should know about this payment') }}"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 bg-[#214E34] hover:bg-[#183926] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-xs cursor-pointer flex items-center justify-center"
                    >
                        <span>{{ __('Submit payment details') }}</span>
                    </button>
                </form>

                <!-- Help Hotline & Submit Later Row -->
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-2">
                    <div>
                        {{ __('Need help? Call') }} <strong class="text-gray-900 font-mono">+880 1700 000 000</strong>
                    </div>
                    <div>
                        <a href="{{ route('track.order', ['order_number' => $order->order_number]) }}" class="text-[#214E34] font-semibold underline underline-offset-2 hover:text-[#183926]">
                            {{ __('Submit later from the order tracking page') }}
                        </a>
                    </div>
                </div>

            </div>

        </div>


        <!-- RIGHT COLUMN: Order Summary Card (Sticky) -->
        <div class="lg:sticky lg:top-24 space-y-4">
            
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-[#E8E8E8] shadow-sm space-y-4">
                
                <!-- Advance Delivery Charge Heading -->
                <div>
                    <div class="text-xs text-gray-500 font-medium" x-text="paymentType === 'cod' ? @json(__('Advance delivery charge to pay now'), JSON_UNESCAPED_UNICODE) : @json(__('Total amount to pay now'), JSON_UNESCAPED_UNICODE)">
                        {{ __('Advance delivery charge to pay now') }}
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-[#214E34] mt-1 tracking-tight">
                        ৳<span x-text="payableNow.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200"></div>

                <!-- Customer Details -->
                <div class="text-xs space-y-0.5">
                    <div class="font-bold text-gray-900 text-sm">{{ $order->customer_name }}</div>
                    <div class="text-gray-500 font-mono">{{ $order->customer_phone }}</div>
                </div>

                <!-- Financial Breakdown Box -->
                <div class="rounded-2xl bg-[#F9FAFB] p-4 space-y-2.5 text-xs text-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="font-medium" x-text="paymentType === 'cod' ? @json(__('Pay now'), JSON_UNESCAPED_UNICODE) : @json(__('Pay full amount'), JSON_UNESCAPED_UNICODE)">{{ __('Pay now') }}</span>
                        <span class="font-bold text-[#214E34]">৳<span x-text="payableNow.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                    </div>

                    <template x-if="paymentType === 'cod'">
                        <div class="flex justify-between items-center">
                            <span>{{ __('Pay the courier on delivery') }}</span>
                            <span class="font-semibold text-gray-900">৳<span x-text="dueOnDelivery.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                        </div>
                    </template>

                    <div class="border-t border-dashed border-gray-200 pt-2 flex justify-between items-center font-bold text-gray-900">
                        <span>{{ __('Order total') }}</span>
                        <span>৳{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>

                <!-- Optional Payment Mode Switcher (COD vs Full) -->
                <div class="p-1 rounded-xl bg-gray-100 grid grid-cols-2 gap-1 text-[11px] font-semibold">
                    <button 
                        type="button" 
                        @click="paymentType = 'cod'" 
                        :class="paymentType === 'cod' ? 'bg-white text-[#214E34] shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                        class="py-1.5 px-2 rounded-lg transition-all text-center"
                    >
                        {{ __('COD (Advance Fee)') }}
                    </button>
                    <button 
                        type="button" 
                        @click="paymentType = 'full'" 
                        :class="paymentType === 'full' ? 'bg-white text-[#214E34] shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                        class="py-1.5 px-2 rounded-lg transition-all text-center"
                    >
                        {{ __('Pay Full Amount') }}
                    </button>
                </div>

                <!-- Warning Notice Box (Matches Image 2) -->
                <div class="p-4 rounded-2xl bg-[#FFFBEB] border border-[#FDE68A] flex items-start gap-3 text-xs text-[#92400E]">
                    <svg class="w-5 h-5 text-[#D97706] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="leading-relaxed text-[11.5px]">
                        {{ __('To stop fake orders and returned parcels, only the courier charge is paid in advance. Pay the rest in cash when the parcel arrives.') }}
                    </p>
                </div>

                <!-- Security Verification Notice -->
                <div class="flex items-center gap-2 text-[11px] text-gray-500 pt-1">
                    <span class="text-emerald-700 font-bold">✓</span>
                    <span>{{ __('Only send money to the accounts shown on this page. We never ask for your PIN or OTP.') }}</span>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
