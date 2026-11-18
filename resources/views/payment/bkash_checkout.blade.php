<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Payment Gateway - {{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bkash-pink { background-color: #E2136E; }
        .bkash-pink-hover:hover { background-color: #C00F5D; }
        .bkash-text-pink { color: #E2136E; }
        .bkash-border { border-color: #E2136E; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans text-gray-800">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200">
        <!-- bKash Header -->
        <div class="bkash-pink px-6 py-5 text-white flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md p-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-current text-[#E2136E]">
                        <path d="M50 0 L100 50 L50 100 L0 50 Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight">{{ __('bKash Payment') }}</h2>
                    <p class="text-xs text-pink-100">{{ __('Sorolata Heritage Merchant Gateway') }}</p>
                </div>
            </div>
            <span class="text-xs bg-white/20 px-2.5 py-1 rounded-full text-white font-medium">{{ __('Secured 256-bit') }}</span>
        </div>

        <!-- Order & Amount Summary -->
        <div class="bg-pink-50/70 p-5 border-b border-pink-100">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('Invoice / Order') }}</span>
                <span class="text-sm font-mono font-bold text-gray-900">#{{ $order->order_number }}</span>
            </div>

            @if($isAdvanceFee)
                <div class="bg-amber-100/80 border border-amber-300 rounded-lg p-3 my-2 text-xs text-amber-900 flex items-start gap-2">
                    <span class="text-base">🛡️</span>
                    <div>
                        <strong class="font-bold">{{ __('Advance Delivery Charge (COD Protection):') }}</strong>
                        <p class="text-amber-800 mt-0.5">{{ __('Pay delivery fee to confirm order and prevent spam. Remaining :balance will be payable in cash upon parcel delivery.', ['balance' => '৳' . number_format($order->total_amount - $payableAmount, 2)]) }}</p>
                    </div>
                </div>
            @endif

            <div class="flex justify-between items-baseline mt-3 pt-3 border-t border-pink-200/50">
                <span class="text-sm font-medium text-gray-700">{{ __('Total Payable Amount:') }}</span>
                <span class="text-2xl font-black text-[#E2136E]">৳{{ number_format($payableAmount, 2) }}</span>
            </div>
        </div>

        <!-- Simulation Form -->
        <form action="{{ route('payment.bkash.callback', ['order' => $order->order_number]) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="paymentID" value="BKS_SIM_{{ strtoupper(uniqid()) }}">
            <input type="hidden" name="trxID" value="TRX{{ rand(1000000000, 9999999999) }}">
            <input type="hidden" name="amount" value="{{ $payableAmount }}">
            <input type="hidden" name="status" value="success">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">{{ __('Your bKash Account Number') }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-medium text-sm">+88</span>
                    <input type="text" name="customer_phone" value="{{ $order->customer_phone }}" class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-[#E2136E] focus:outline-none" required placeholder="017XXXXXXXX">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">{{ __('4-Digit OTP / PIN (Sandbox Simulator)') }}</label>
                <input type="password" value="12345" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm font-mono tracking-widest focus:ring-2 focus:ring-[#E2136E] focus:outline-none" placeholder="•••••">
                <p class="text-[11px] text-gray-500 mt-1">{{ __('In test mode, enter any PIN to simulate payment confirmation directly.') }}</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bkash-pink bkash-pink-hover text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-pink-500/25 transition-all flex items-center justify-center space-x-2">
                    <span>{{ __('Confirm & Complete Payment (:amount)', ['amount' => '৳' . number_format($payableAmount, 2)]) }}</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('checkout.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">{{ __('Cancel Payment and Return') }}</a>
            </div>
        </form>

        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
            <span>{{ __('Official bKash PGW API v1.2') }}</span>
            <span>{{ __('24/7 Helpline: 16247') }}</span>
        </div>
    </div>
</body>
</html>
