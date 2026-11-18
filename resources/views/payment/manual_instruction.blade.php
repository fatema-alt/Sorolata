@extends('layouts.storefront')

@section('seo_meta')
    <title>Payment Instruction & TrxID Verification | Order #{{ $order->order_number }}</title>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12" x-data="{ 
    activeChannel: '{{ $order->payment_gateway->value === 'nagad' ? 'nagad' : ($order->payment_gateway->value === 'brac_bank' ? 'brac' : ($order->payment_gateway->value === 'dbbl' ? 'dbbl' : 'bkash')) }}',
    copiedText: '',
    copy(text) {
        navigator.clipboard.writeText(text);
        this.copiedText = text;
        setTimeout(() => { this.copiedText = ''; }, 2000);
    }
}">

    <!-- Page Header -->
    <div class="text-center space-y-2 mb-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-[#214E34]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>অর্ডার রেজিস্টার্ড • পেমেন্ট ও ভেরিফিকেশন পেন্ডিং</span>
        </div>
        <h1 class="font-editorial-heading text-3xl sm:text-4xl text-[#1D1D1D] font-light">
            পেমেন্ট নির্দেশনা ও ট্রানজেকশন আইডি সাবমিশন
        </h1>
        <p class="text-xs text-gray-500">
            অর্ডার নং: <span class="font-mono font-bold text-gray-900">#{{ $order->order_number }}</span> • তারিখ: {{ $order->created_at->format('d M, Y') }}
        </p>
    </div>

    <!-- Amount & Order Notice Banner -->
    <div class="bg-white rounded-3xl p-6 border border-[#E8E8E8] shadow-sm mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
            <div>
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">গ্রাহকের নাম</span>
                <div class="text-sm font-bold text-gray-900">{{ $order->customer_name }} ({{ $order->customer_phone }})</div>
            </div>
            <div class="sm:text-right">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    {{ $isAdvanceFee ? 'পরিশোধযোগ্য অগ্রিম চার্জ (COD Security):' : 'পরিশোধযোগ্য মোট টাকা:' }}
                </span>
                <div class="text-3xl font-black text-[#214E34]">
                    ৳{{ number_format($payableAmount, 2) }}
                </div>
            </div>
        </div>

        @if($isAdvanceFee)
            <div class="mt-4 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start gap-3">
                <span class="text-lg">🛡️</span>
                <div>
                    <strong class="font-bold">অগ্রিম ডেলিভারি চার্জ নীতি:</strong>
                    <p class="mt-0.5">ফেক অর্ডার ও পার্সেল রিটার্ন রোধে শুধুমাত্র কুরিয়ার চার্জ <strong>৳{{ number_format($payableAmount, 2) }}</strong> অগ্রিম প্রদান করতে হবে। পণ্য পৌঁছানোর পর অবশিষ্ট <strong>৳{{ number_format($order->total_amount - $payableAmount, 2) }}</strong> কুরিয়ারের কাছে ক্যাশে পরিশোধ করবেন।</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Payment Channels Tabs & Account Details -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E8E8E8] shadow-sm space-y-6 mb-8">
        <div>
            <h2 class="font-editorial-heading text-2xl text-gray-900 font-normal">
                ১. নিচের যেকোনো মাধ্যমে টাকা পাঠিয়ে ট্রানজেকশন আইডি সংগ্রহ করুন
            </h2>
            <p class="text-xs text-gray-500 mt-1">পেমেন্ট করার জন্য নিচের একাউন্ট নম্বরগুলো ব্যবহার করুন।</p>
        </div>

        <!-- Channels Grid Buttons -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <!-- bKash Button -->
            <button type="button" @click="activeChannel = 'bkash'"
                    :class="activeChannel === 'bkash' ? 'border-[#E2136E] bg-pink-50/50 text-[#E2136E] ring-1 ring-[#E2136E]' : 'border-gray-200 hover:border-gray-300 text-gray-700'"
                    class="p-3.5 rounded-2xl border-2 text-center transition-all flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-[#E2136E] text-white flex items-center justify-center font-bold text-sm mb-1.5 shadow-sm">b</div>
                <span class="text-xs font-bold">bKash বিকাশ</span>
                <span class="text-[10px] text-gray-500">মোবাইল ব্যাংকিং</span>
            </button>

            <!-- Nagad Button -->
            <button type="button" @click="activeChannel = 'nagad'"
                    :class="activeChannel === 'nagad' ? 'border-[#F7941D] bg-amber-50/50 text-[#F7941D] ring-1 ring-[#F7941D]' : 'border-gray-200 hover:border-gray-300 text-gray-700'"
                    class="p-3.5 rounded-2xl border-2 text-center transition-all flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-[#F7941D] text-white flex items-center justify-center font-bold text-sm mb-1.5 shadow-sm">N</div>
                <span class="text-xs font-bold">Nagad নগদ</span>
                <span class="text-[10px] text-gray-500">মোবাইল ব্যাংকিং</span>
            </button>

            <!-- BRAC Bank Button -->
            <button type="button" @click="activeChannel = 'brac'"
                    :class="activeChannel === 'brac' ? 'border-blue-600 bg-blue-50/50 text-blue-700 ring-1 ring-blue-600' : 'border-gray-200 hover:border-gray-300 text-gray-700'"
                    class="p-3.5 rounded-2xl border-2 text-center transition-all flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm mb-1.5 shadow-sm">B</div>
                <span class="text-xs font-bold">BRAC Bank</span>
                <span class="text-[10px] text-gray-500">ব্যাংক ট্রান্সফার</span>
            </button>

            <!-- DBBL Button -->
            <button type="button" @click="activeChannel = 'dbbl'"
                    :class="activeChannel === 'dbbl' ? 'border-orange-600 bg-orange-50/50 text-orange-700 ring-1 ring-orange-600' : 'border-gray-200 hover:border-gray-300 text-gray-700'"
                    class="p-3.5 rounded-2xl border-2 text-center transition-all flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-orange-600 text-white flex items-center justify-center font-bold text-sm mb-1.5 shadow-sm">D</div>
                <span class="text-xs font-bold">Dutch-Bangla</span>
                <span class="text-[10px] text-gray-500">Nexus / Rocket</span>
            </button>
        </div>

        <!-- Details Box for activeChannel -->

        <!-- bKash Details -->
        <div x-show="activeChannel === 'bkash'" class="p-5 rounded-2xl bg-pink-50/60 border border-pink-200 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">বিকাশ মোবাইল নম্বর:</span>
                    <div class="text-xl font-mono font-black text-[#E2136E] mt-0.5">
                        {{ $settings['manual_bkash_number'] ?? '01711002233' }}
                    </div>
                    <span class="inline-block text-[11px] font-semibold text-pink-700 bg-pink-100 px-2.5 py-0.5 rounded-full mt-1">
                        {{ $settings['manual_bkash_type'] ?? 'Personal (Send Money)' }}
                    </span>
                </div>
                <button type="button" @click="copy('{{ $settings['manual_bkash_number'] ?? '01711002233' }}')" 
                        class="px-4 py-2 rounded-xl bg-white border border-pink-300 hover:bg-pink-100 text-xs font-bold text-[#E2136E] shadow-sm transition">
                    <span x-text="copiedText === '{{ $settings['manual_bkash_number'] ?? '01711002233' }}' ? 'কপি হয়েছে ✓' : 'নম্বর কপি করুন'"></span>
                </button>
            </div>
            <p class="text-xs text-gray-700 leading-relaxed border-t border-pink-200/60 pt-3">
                <strong>নির্দেশনা:</strong> {{ $settings['manual_bkash_instructions'] ?? 'বিকাশ অ্যাপ থেকে Send Money করুন। রেফারেন্সে আপনার অর্ডার নাম্বারটি লিখুন।' }}
            </p>
        </div>

        <!-- Nagad Details -->
        <div x-show="activeChannel === 'nagad'" class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">নগদ মোবাইল নম্বর:</span>
                    <div class="text-xl font-mono font-black text-[#F7941D] mt-0.5">
                        {{ $settings['manual_nagad_number'] ?? '01811002233' }}
                    </div>
                    <span class="inline-block text-[11px] font-semibold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full mt-1">
                        {{ $settings['manual_nagad_type'] ?? 'Personal (Send Money)' }}
                    </span>
                </div>
                <button type="button" @click="copy('{{ $settings['manual_nagad_number'] ?? '01811002233' }}')" 
                        class="px-4 py-2 rounded-xl bg-white border border-amber-300 hover:bg-amber-100 text-xs font-bold text-[#F7941D] shadow-sm transition">
                    <span x-text="copiedText === '{{ $settings['manual_nagad_number'] ?? '01811002233' }}' ? 'কপি হয়েছে ✓' : 'নম্বর কপি করুন'"></span>
                </button>
            </div>
            <p class="text-xs text-gray-700 leading-relaxed border-t border-amber-200/60 pt-3">
                <strong>নির্দেশনা:</strong> {{ $settings['manual_nagad_instructions'] ?? 'নগদ অ্যাপ থেকে Send Money করুন এবং রেফারেন্সে আপনার অর্ডার নাম্বারটি লিখুন।' }}
            </p>
        </div>

        <!-- BRAC Bank Details -->
        <div x-show="activeChannel === 'brac'" class="p-5 rounded-2xl bg-blue-50/60 border border-blue-200 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-500 font-medium">Account Name:</span>
                    <div class="font-bold text-gray-900 mt-0.5">{{ $settings['manual_brac_account_name'] ?? 'Sorolata Heritage Ltd' }}</div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Account Number:</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-mono font-black text-blue-900 text-base">{{ $settings['manual_brac_account_number'] ?? '1501204859632001' }}</span>
                        <button type="button" @click="copy('{{ $settings['manual_brac_account_number'] ?? '1501204859632001' }}')" class="text-[11px] text-blue-600 underline font-semibold">কপি</button>
                    </div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Branch:</span>
                    <div class="font-semibold text-gray-900 mt-0.5">{{ $settings['manual_brac_branch'] ?? 'Gulshan Branch, Dhaka' }}</div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Routing Number:</span>
                    <div class="font-mono font-bold text-gray-900 mt-0.5">{{ $settings['manual_brac_routing_number'] ?? '060261726' }}</div>
                </div>
            </div>
            <p class="text-xs text-gray-700 border-t border-blue-200/60 pt-3">
                <strong>নির্দেশনা:</strong> {{ $settings['manual_brac_instructions'] ?? 'ব্র্যাক ব্যাংক (Astha App / ব্রাঞ্চ ডিপোজিট) করে ডিপোজিট স্লিপ বা ট্রানজেকশন রেফারেন্স প্রদান করুন।' }}
            </p>
        </div>

        <!-- DBBL Details -->
        <div x-show="activeChannel === 'dbbl'" class="p-5 rounded-2xl bg-orange-50/60 border border-orange-200 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-500 font-medium">Account Name:</span>
                    <div class="font-bold text-gray-900 mt-0.5">{{ $settings['manual_dbbl_account_name'] ?? 'Sorolata Artisan Commerce' }}</div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Account Number:</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-mono font-black text-orange-900 text-base">{{ $settings['manual_dbbl_account_number'] ?? '1151200034567' }}</span>
                        <button type="button" @click="copy('{{ $settings['manual_dbbl_account_number'] ?? '1151200034567' }}')" class="text-[11px] text-orange-600 underline font-semibold">কপি</button>
                    </div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Branch:</span>
                    <div class="font-semibold text-gray-900 mt-0.5">{{ $settings['manual_dbbl_branch'] ?? 'Dhanmondi Branch, Dhaka' }}</div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Routing Number:</span>
                    <div class="font-mono font-bold text-gray-900 mt-0.5">{{ $settings['manual_dbbl_routing_number'] ?? '090261345' }}</div>
                </div>
            </div>
            <p class="text-xs text-gray-700 border-t border-orange-200/60 pt-3">
                <strong>নির্দেশনা:</strong> {{ $settings['manual_dbbl_instructions'] ?? 'ডাচ-বাংলা ব্যাংক (NexusPay App / রকেট / ব্যাংক ট্রান্সফার) করে ট্রানজেকশন রেফারেন্স প্রদান করুন।' }}
            </p>
        </div>
    </div>

    <!-- Submission Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E8E8E8] shadow-sm space-y-6">
        <div>
            <h2 class="font-editorial-heading text-2xl text-gray-900 font-normal">
                ২. টাকা পাঠানো শেষে নিচের ফর্মটি পূরণ করে সাবমিট করুন
            </h2>
            <p class="text-xs text-gray-500 mt-1">আমাদের একাউন্টস টিম আপনার সাবমিট করা TrxID ভেরিফাই করে অর্ডারটি কনফার্ম করবে।</p>
        </div>

        <form action="{{ route('payment.manual.submit', ['order' => $order->order_number]) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Payment Method Field -->
            <input type="hidden" name="payment_method" :value="activeChannel">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Sender Mobile / Account -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        যে নম্বর/একাউন্ট থেকে টাকা পাঠিয়েছেন (Sender Account) *
                    </label>
                    <input type="text" name="sender_number" value="{{ old('sender_number', $order->customer_phone) }}" required 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-mono text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                           placeholder="যেমন: 017XXXXXXXX বা ব্যাংক একাউন্ট নং">
                    <p class="text-[11px] text-gray-500">আপনার বিকাশ/নগদ নম্বর অথবা ব্যাংক একাউন্ট নম্বর লিখুন।</p>
                </div>

                <!-- Transaction ID (TrxID) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        ট্রানজেকশন আইডি (Transaction ID / TrxID) *
                    </label>
                    <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" required 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-mono font-bold uppercase text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                           placeholder="যেমন: BL7A8X9K বা স্লিপ রেফারেন্স">
                    <p class="text-[11px] text-gray-500">টাকা পাঠানোর পর প্রাপ্ত ফিরতি এসএমএস থেকে TrxID কপি করে বসান।</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    মন্তব্য বা রেফারেন্স (ঐচ্ছিক)
                </label>
                <textarea name="customer_notes" rows="2" 
                          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34]" 
                          placeholder="পেমেন্ট সম্পর্কিত কোনো তথ্য থাকলে লিখতে পারেন..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-[#214E34] hover:bg-[#193c28] text-white font-bold text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-900/20 transition-all flex items-center justify-center space-x-2">
                <span>পেমেন্ট তথ্য সাবমিট করুন (Submit Payment Details)</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
            <span>হেল্পলাইন: +880 1700 000 000</span>
            <a href="{{ route('track.order', ['order_number' => $order->order_number]) }}" class="hover:underline">পরে সাবমিট করতে ট্র্যাকিং পেজে যান →</a>
        </div>
    </div>

</div>
@endsection
