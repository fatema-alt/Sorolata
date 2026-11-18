<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSLCommerz Payment Gateway - {{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800">
    <div class="max-w-xl w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
        <!-- SSLCommerz Header -->
        <div class="bg-gradient-to-r from-blue-900 to-indigo-900 px-6 py-5 text-white flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center font-bold text-blue-900 text-lg shadow">
                    SSL
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight">{{ __('SSLCommerz Secured Pay') }}</h2>
                    <p class="text-xs text-blue-200">{{ __('Bangladesh Premier Multi-Channel Payment Gateway') }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2 py-0.5 rounded-full font-mono">PCI-DSS V3.2</span>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-slate-50 p-5 border-b border-slate-200">
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500 font-medium">{{ __('Merchant:') }}</span>
                <span class="font-bold text-slate-900">Sorolata Artisan Handloom Ltd.</span>
            </div>
            <div class="flex justify-between items-center text-sm mt-1">
                <span class="text-slate-500 font-medium">{{ __('Invoice Number:') }}</span>
                <span class="font-mono font-bold text-indigo-700">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between items-baseline mt-3 pt-3 border-t border-slate-200">
                <span class="text-sm font-semibold text-slate-700">{{ __('Total Payable (BDT):') }}</span>
                <span class="text-2xl font-black text-indigo-900">৳{{ number_format($payableAmount, 2) }}</span>
            </div>
        </div>

        <!-- Channel Select Form -->
        <form action="{{ route('payment.sslcommerz.success') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="tran_id" value="{{ $tranId }}">
            <input type="hidden" name="val_id" value="VAL_{{ strtoupper(uniqid()) }}">
            <input type="hidden" name="amount" value="{{ $payableAmount }}">
            <input type="hidden" name="status" value="VALID">
            <input type="hidden" name="value_a" value="{{ $order->order_number }}">

            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ __('Choose Payment Method') }}</p>
                <div class="grid grid-cols-3 gap-3">
                    <label class="border-2 border-indigo-600 bg-indigo-50/50 rounded-xl p-3 flex flex-col items-center cursor-pointer transition hover:shadow-md">
                        <input type="radio" name="card_type" value="Cards (Visa / Master)" checked class="hidden">
                        <span class="text-2xl mb-1">💳</span>
                        <span class="text-xs font-bold text-slate-800">{{ __('Cards') }}</span>
                        <span class="text-[10px] text-slate-500">{{ __('Visa / Master / Amex') }}</span>
                    </label>

                    <label class="border-2 border-slate-200 hover:border-indigo-400 bg-white rounded-xl p-3 flex flex-col items-center cursor-pointer transition hover:shadow-md">
                        <input type="radio" name="card_type" value="Nagad / Rocket / Upay" class="hidden">
                        <span class="text-2xl mb-1">📱</span>
                        <span class="text-xs font-bold text-slate-800">{{ __('Mobile Banking') }}</span>
                        <span class="text-[10px] text-slate-500">{{ __('Nagad / Rocket') }}</span>
                    </label>

                    <label class="border-2 border-slate-200 hover:border-indigo-400 bg-white rounded-xl p-3 flex flex-col items-center cursor-pointer transition hover:shadow-md">
                        <input type="radio" name="card_type" value="Net Banking (CityTouch / EBL)" class="hidden">
                        <span class="text-2xl mb-1">🏦</span>
                        <span class="text-xs font-bold text-slate-800">{{ __('Internet Bank') }}</span>
                        <span class="text-[10px] text-slate-500">{{ __('City / EBL / Brac') }}</span>
                    </label>
                </div>
            </div>

            <!-- Card / Bank Info Mock Input -->
            <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-sm">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">{{ __('Card / Account Holder') }}</label>
                    <input type="text" value="{{ $order->customer_name }}" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm" readonly>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">{{ __('Simulated Card No.') }}</label>
                        <input type="text" value="4111 •••• •••• 1111" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-mono" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">{{ __('CVV / Security PIN') }}</label>
                        <input type="password" value="123" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-mono" readonly>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-600/20 text-sm transition">
                    {{ __('Pay :amount BDT', ['amount' => '৳' . number_format($payableAmount, 2)]) }}
                </button>
                <a href="{{ route('payment.sslcommerz.cancel') }}" class="px-5 py-3 border border-slate-300 hover:bg-slate-100 rounded-xl text-xs font-semibold text-slate-600 flex items-center justify-center">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>

        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-400">
            <span>{{ __('SSLCommerz Hosted v4.0') }}</span>
            <span>{{ __('Verified by VISA & Mastercard ID Check') }}</span>
        </div>
    </div>
</body>
</html>
