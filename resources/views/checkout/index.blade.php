@extends('layouts.storefront')

@section('seo_meta')
    <title>Express Checkout — Insured Collector Concierge | Sorolata</title>
    <meta name="description" content="Secure one-page checkout for your artisanal heirloom. Instant bKash, SSLCommerz, Nagad, and Cash on Delivery with advance courier protection.">
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16" x-data="{
        subtotal: {{ $subtotal }},
        dhakaFee: {{ $dhakaFee }},
        outsideFee: {{ $outsideFee ?? 150.00 }},
        discountAmount: {{ $discountAmount ?? 0.0 }},
        selectedDistrict: '{{ old('city_district', 'Dhaka') }}',
        paymentMethod: 'bkash',
        get isDhaka() {
            return this.selectedDistrict.toLowerCase().includes('dhaka') || this.selectedDistrict.includes('ঢাকা');
        },
        get shippingFee() {
            return this.isDhaka ? this.dhakaFee : this.outsideFee;
        },
        get grandTotal() {
            return Math.max(0, this.subtotal - this.discountAmount) + this.shippingFee;
        },
        get advanceDeliveryCharge() {
            return this.paymentMethod === 'cash_on_delivery' ? this.shippingFee : 0;
        },
        get dueOnDelivery() {
            return this.paymentMethod === 'cash_on_delivery' ? Math.max(0, this.subtotal - this.discountAmount) : 0;
        }
    }">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">Frictionless Concierge</span>
                <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-1">
                    One-Page Checkout (এক ক্লিকে অর্ডার)
                </h1>
            </div>
            <div class="flex items-center gap-2 text-xs text-[#214E34] bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>অফিশিয়াল গেটওয়ে ও সরাসরি মার্চেন্ট একাউন্ট ট্রাস্টেড</span>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-3">
                <span class="text-xl">ℹ️</span>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            @csrf

            <!-- Left: Checkout Forms -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- 1. Collector Information -->
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                            1. কাস্টমার তথ্য (Collector Contact)
                        </h2>
                        <span class="text-xs text-[#888888]">Guest Checkout Active</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">আপনার নাম (Full Name) *</label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                value="{{ old('customer_name', auth()->user()?->name ?? '') }}" 
                                placeholder="যেমন: ফারহান আহমেদ"
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                                required
                            >
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">ইমেইল (Email Address) *</label>
                            <input 
                                type="email" 
                                name="customer_email" 
                                value="{{ old('customer_email', auth()->user()?->email ?? '') }}" 
                                placeholder="name@example.com"
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                                required
                            >
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-[#1D1D1D] uppercase">মোবাইল ফোন (Active Phone Number) *</label>
                        <input 
                            type="tel" 
                            name="customer_phone" 
                            value="{{ old('customer_phone', auth()->user()?->phone ?? '') }}" 
                            placeholder="01XXXXXXXXX"
                            class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                            required
                        >
                        <p class="text-[11px] text-[#777777]">ডেলিভারি আপডেট ও এসএমএস নোটিফিকেশনের জন্য ব্যবহৃত হবে।</p>
                    </div>
                </div>

                <!-- 2. Insured Shipping Address -->
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                    <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                        2. ডেলিভারি ঠিকানা (Delivery Address)
                    </h2>

                    <!-- District Selection Buttons -->
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-[#1D1D1D] uppercase">ডেলিভারি এরিয়া নির্বাচন করুন</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                @click="selectedDistrict = 'Dhaka'" 
                                :class="isDhaka ? 'border-[#214E34] bg-emerald-50/70 text-[#214E34] font-bold shadow-sm' : 'border-gray-200 bg-white text-gray-700'"
                                class="p-3.5 rounded-2xl border-2 text-left flex items-center justify-between transition-all"
                            >
                                <div>
                                    <div class="text-sm font-semibold">ঢাকা সিটির ভেতর</div>
                                    <div class="text-[11px] opacity-75">হোম ডেলিভারি চার্জ ৳{{ (int)$dhakaFee }}</div>
                                </div>
                                <span class="text-base" x-show="isDhaka">✓</span>
                            </button>

                            <button 
                                type="button" 
                                @click="selectedDistrict = (selectedDistrict === 'Dhaka' ? 'Chattogram' : selectedDistrict)" 
                                :class="!isDhaka ? 'border-[#214E34] bg-emerald-50/70 text-[#214E34] font-bold shadow-sm' : 'border-gray-200 bg-white text-gray-700'"
                                class="p-3.5 rounded-2xl border-2 text-left flex items-center justify-between transition-all"
                            >
                                <div>
                                    <div class="text-sm font-semibold">ঢাকার বাইরে (সারাদেশ)</div>
                                    <div class="text-[11px] opacity-75">কুরিয়ার ডেলিভারি চার্জ ৳{{ (int)$outsideFee }}</div>
                                </div>
                                <span class="text-base" x-show="!isDhaka">✓</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-[#1D1D1D] uppercase">পূর্ণাঙ্গ ঠিকানা (House / Road / Area) *</label>
                        <input 
                            type="text" 
                            name="delivery_address" 
                            value="{{ old('delivery_address', '') }}" 
                            placeholder="বাড়ি নম্বর, ফ্ল্যাট, রোড, এলাকা..."
                            class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">জেলা (District) *</label>
                            <select 
                                name="city_district" 
                                x-model="selectedDistrict" 
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                                required
                            >
                                <option value="Dhaka">Dhaka (ঢাকা)</option>
                                <option value="Chattogram">Chattogram (চট্টগ্রাম)</option>
                                <option value="Sylhet">Sylhet (সিলেট)</option>
                                <option value="Rajshahi">Rajshahi (রাজশাহী)</option>
                                <option value="Khulna">Khulna (খুলনা)</option>
                                <option value="Barishal">Barishal (বরিশাল)</option>
                                <option value="Rangpur">Rangpur (রংপুর)</option>
                                <option value="Mymensingh">Mymensingh (ময়মনসিংহ)</option>
                                <option value="Bogura">Bogura (বগুড়া)</option>
                                <option value="Tangail">Tangail (টাঙ্গাইল)</option>
                                <option value="Narayanganj">Narayanganj (নারায়ণগঞ্জ)</option>
                                <option value="Gazipur">Gazipur (গাজীপুর)</option>
                                <option value="Cumilla">Cumilla (কুমিল্লা)</option>
                                <option value="Cox's Bazar">Cox's Bazar (কক্সবাজার)</option>
                                <option value="Feni">Feni (ফেনী)</option>
                                <option value="Noakhali">Noakhali (নোয়াখালী)</option>
                                <option value="Brahmanbaria">Brahmanbaria (ব্রাহ্মণবাড়িয়া)</option>
                                <option value="Chandpur">Chandpur (চাঁদপুর)</option>
                                <option value="Lakshmipur">Lakshmipur (লক্ষ্মীপুর)</option>
                                <option value="Faridpur">Faridpur (ফরিদপুর)</option>
                                <option value="Gopalganj">Gopalganj (গোপালগঞ্জ)</option>
                                <option value="Madaripur">Madaripur (মাদারীপুর)</option>
                                <option value="Manikganj">Manikganj (মানিকগঞ্জ)</option>
                                <option value="Munshiganj">Munshiganj (মুন্সীগঞ্জ)</option>
                                <option value="Narsingdi">Narsingdi (নরসিংদী)</option>
                                <option value="Rajbari">Rajbari (রাজবাড়ী)</option>
                                <option value="Shariatpur">Shariatpur (শরীয়তপুর)</option>
                                <option value="Kishoreganj">Kishoreganj (কিশোরগঞ্জ)</option>
                                <option value="Jamalpur">Jamalpur (জামালপুর)</option>
                                <option value="Netrokona">Netrokona (নেত্রকোণা)</option>
                                <option value="Sherpur">Sherpur (শেরপুর)</option>
                                <option value="Jashore">Jashore (যশোর)</option>
                                <option value="Bagerhat">Bagerhat (বাগেরহাট)</option>
                                <option value="Chuadanga">Chuadanga (চুয়াডাঙ্গা)</option>
                                <option value="Kushtia">Kushtia (কুষ্টিয়া)</option>
                                <option value="Magura">Magura (মাগুরা)</option>
                                <option value="Meherpur">Meherpur (মেহেরপুর)</option>
                                <option value="Narail">Narail (নড়াইল)</option>
                                <option value="Satkhira">Satkhira (সাতক্ষীরা)</option>
                                <option value="Jhenaidah">Jhenaidah (ঝিনাইদহ)</option>
                                <option value="Sirajganj">Sirajganj (সিরাজগঞ্জ)</option>
                                <option value="Pabna">Pabna (পাবনা)</option>
                                <option value="Natore">Natore (নাটোর)</option>
                                <option value="Naogaon">Naogaon (নওগাঁ)</option>
                                <option value="Chapai Nawabganj">Chapai Nawabganj (চাঁপাইনবাবগঞ্জ)</option>
                                <option value="Joypurhat">Joypurhat (জয়পুরহাট)</option>
                                <option value="Dinajpur">Dinajpur (দিনাজপুর)</option>
                                <option value="Gaibandha">Gaibandha (গাইবান্ধা)</option>
                                <option value="Kurigram">Kurigram (কুড়িগ্রাম)</option>
                                <option value="Lalmonirhat">Lalmonirhat (লালমনিরহাট)</option>
                                <option value="Nilphamari">Nilphamari (নীলফামারী)</option>
                                <option value="Panchagarh">Panchagarh (পঞ্চগড়)</option>
                                <option value="Thakurgaon">Thakurgaon (ঠাকুরগাঁও)</option>
                                <option value="Habiganj">Habiganj (হবিগঞ্জ)</option>
                                <option value="Moulvibazar">Moulvibazar (মৌলভীবাজার)</option>
                                <option value="Sunamganj">Sunamganj (সুনামগঞ্জ)</option>
                                <option value="Barguna">Barguna (বরগুনা)</option>
                                <option value="Bhola">Bhola (ভোলা)</option>
                                <option value="Jhalokati">Jhalokati (ঝালকাঠি)</option>
                                <option value="Patuakhali">Patuakhali (পটুয়াখালী)</option>
                                <option value="Pirojpur">Pirojpur (পিরোজপুর)</option>
                                <option value="Bandarban">Bandarban (বান্দরবান)</option>
                                <option value="Khagrachhari">Khagrachhari (খাগড়াছড়ি)</option>
                                <option value="Rangamati">Rangamati (রাঙ্গামাটি)</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">ডেলিভারি নোট (ঐচ্ছিক)</label>
                            <input 
                                type="text" 
                                name="gift_note" 
                                placeholder="স্পেশাল কোনো নির্দেশনা থাকলে লিখুন..." 
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                            >
                        </div>
                    </div>
                </div>

                <!-- 3. Payment Method Selection -->
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                            3. পেমেন্ট মাধ্যম নির্বাচন (Payment Selection & Gateway)
                        </h2>
                        <span class="text-xs text-emerald-800 bg-emerald-100/80 px-2.5 py-1 rounded-full font-medium">SSL / 256-bit Encrypted</span>
                    </div>

                    <div class="space-y-3">
                        <!-- Option 1: Mobile Banking (bKash & Nagad) -->
                        <label 
                            :class="paymentMethod === 'bkash' ? 'border-[#E2136E] bg-pink-50/40 ring-1 ring-[#E2136E]' : 'border-[#E8E8E8] hover:border-pink-300'"
                            class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_gateway" value="bkash" x-model="paymentMethod" class="w-4 h-4 text-[#E2136E] focus:ring-[#E2136E]">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span>মোবাইল ব্যাংকিং (bKash বিকাশ ও Nagad নগদ)</span>
                                        <span class="bg-[#E2136E] text-white text-[10px] px-2 py-0.5 rounded-full font-bold">জনপ্রিয়</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">বিকাশ বা নগদ একাউন্টে সেন্ড মানি করে TrxID দিয়ে অর্ডার কনফার্ম করুন।</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 font-bold text-xs">
                                <span class="text-[#E2136E]">bKash</span>
                                <span class="text-gray-300">/</span>
                                <span class="text-[#F7941D]">Nagad</span>
                            </div>
                        </label>

                        <!-- Option 2: BRAC Bank Transfer -->
                        <label 
                            :class="paymentMethod === 'brac_bank' ? 'border-blue-600 bg-blue-50/40 ring-1 ring-blue-600' : 'border-[#E8E8E8] hover:border-blue-300'"
                            class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_gateway" value="brac_bank" x-model="paymentMethod" class="w-4 h-4 text-blue-600 focus:ring-blue-600">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span>ব্র্যাক ব্যাংক ট্রান্সফার (BRAC Bank Transfer)</span>
                                        <span class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">ব্যাংক ওয়্যার</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">ব্র্যাক ব্যাংক একাউন্টে (Astha App বা ব্রাঞ্চ ডিপোজিট) ট্রান্সফার করে রেফারেন্স দিন।</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-blue-700">BRAC Bank</span>
                        </label>

                        <!-- Option 3: Dutch-Bangla Bank (DBBL) -->
                        <label 
                            :class="paymentMethod === 'dbbl' ? 'border-orange-600 bg-orange-50/40 ring-1 ring-orange-600' : 'border-[#E8E8E8] hover:border-orange-300'"
                            class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
                        >
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_gateway" value="dbbl" x-model="paymentMethod" class="w-4 h-4 text-orange-600 focus:ring-orange-600">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span>ডাচ-বাংলা ব্যাংক (Dutch-Bangla Bank / DBBL / Rocket)</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">NexusPay অ্যাপ, রকেট বা যেকোনো ব্যাংক একাউন্ট থেকে DBBL-এ ট্রান্সফার করুন।</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-orange-700">DBBL</span>
                        </label>

                        <!-- Option 3: Cash On Delivery with Advance Delivery Charge -->
                        <label 
                            :class="paymentMethod === 'cash_on_delivery' ? 'border-amber-600 bg-amber-50/40 ring-1 ring-amber-600' : 'border-[#E8E8E8] hover:border-amber-300'"
                            class="flex flex-col p-4 rounded-2xl border transition-all cursor-pointer"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="cash_on_delivery" x-model="paymentMethod" class="w-4 h-4 text-amber-600 focus:ring-amber-600">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                            <span>ক্যাশ অন ডেলিভারি (COD - অগ্রিম ডেলিভারি চার্জ সহ)</span>
                                            <span class="bg-amber-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">সুরক্ষিত</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-0.5">পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন।</p>
                                    </div>
                                </div>
                                <span class="text-xl">📦</span>
                            </div>

                            <!-- COD Anti-Fraud Policy Box -->
                            <div x-show="paymentMethod === 'cash_on_delivery'" class="mt-3 pt-3 border-t border-amber-200/60 text-xs text-amber-900 space-y-1">
                                <div class="flex items-start gap-1.5">
                                    <span class="text-amber-700 font-bold">🛡️ নীতি:</span>
                                    <span>ফেক অর্ডার ও পার্সেল রিটার্ন রোধে শুধুমাত্র ডেলিভারি চার্জ (<span class="font-bold">৳<span x-text="shippingFee"></span></span>) অগ্রিম বিকাশ/অনলাইনে প্রদান করতে হবে।</span>
                                </div>
                                <div class="flex items-center justify-between font-semibold text-[11px] pt-1 text-gray-700">
                                    <span>এখন পরিশোধযোগ্য (অগ্রিম চার্জ): <span class="text-[#E2136E] font-bold">৳<span x-text="shippingFee"></span></span></span>
                                    <span>ডেলিভারির সময় প্রদেয়: <span class="text-amber-800 font-bold">৳<span x-text="subtotal.toLocaleString()"></span></span></span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary Sticky Box -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-lg space-y-6 sticky top-28">
                    <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D] pb-4 border-b border-[#E8E8E8]">
                        অর্ডার সারসংক্ষেপ (Order Summary)
                    </h2>

                    <div class="space-y-4 max-h-72 overflow-y-auto divide-y divide-[#E8E8E8]">
                        @foreach($items as $item)
                            <div class="pt-3 first:pt-0 flex items-center gap-4">
                                <img 
                                    src="{{ $item['product']->media->first()?->file_path ?? 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=200' }}" 
                                    alt="{{ $item['product']->title }}" 
                                    class="w-14 h-14 rounded-xl object-cover bg-[#F8F6F2]"
                                >
                                <div class="flex-grow">
                                    <h4 class="font-editorial-heading text-sm text-[#1D1D1D] line-clamp-1 font-semibold">{{ $item['product']->title }}</h4>
                                    <span class="text-xs text-[#888888]">Qty: {{ $item['quantity'] }}</span>
                                </div>
                                <div class="text-xs font-bold text-gray-900">
                                    ৳{{ number_format($item['subtotal'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-[#E8E8E8] space-y-2 text-xs text-[#555555]">
                        <div class="flex justify-between">
                            <span>পণ্যের সাবটোটাল</span>
                            <span class="font-semibold text-gray-900">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discountAmount > 0)
                            <div class="flex justify-between text-emerald-700 font-semibold">
                                <span>কুপন ডিসকাউন্ট ({{ $coupon['code'] ?? '' }})</span>
                                <span>-৳{{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>ডেলিভারি চার্জ (<span x-text="isDhaka ? 'ঢাকা' : 'ঢাকার বাইরে'"></span>)</span>
                            <span class="text-[#2D6A4F] font-semibold">৳<span x-text="shippingFee"></span>.00</span>
                        </div>
                        <template x-if="paymentMethod === 'cash_on_delivery'">
                            <div class="pt-2 border-t border-dashed border-amber-300 space-y-1">
                                <div class="flex justify-between text-pink-700 font-bold">
                                    <span>এখন পরিশোধ (অগ্রিম কুরিয়ার ফি):</span>
                                    <span>৳<span x-text="shippingFee"></span>.00</span>
                                </div>
                                <div class="flex justify-between text-amber-800 font-medium">
                                    <span>ডেলিভারির সময় প্রদেয় বাকি:</span>
                                    <span>৳<span x-text="dueOnDelivery.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Coupon / Promo Section -->
                    <div class="pt-4 border-t border-[#E8E8E8]">
                        @if($coupon)
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-emerald-700 text-sm">🎟️</span>
                                    <div>
                                        <div class="text-xs font-bold text-emerald-900 tracking-wider uppercase">{{ $coupon['code'] }}</div>
                                        <div class="text-[11px] text-emerald-700">কুপন ডিসকাউন্ট সক্রিয় (-৳{{ number_format($discountAmount, 2) }})</div>
                                    </div>
                                </div>
                                <button type="submit" formaction="{{ route('checkout.coupon.remove') }}" formmethod="POST" formnovalidate class="text-xs text-rose-600 hover:text-rose-800 underline font-semibold">
                                    বাতিল
                                </button>
                            </div>
                        @else
                            <div class="space-y-1.5">
                                <label for="coupon_code" class="text-xs font-semibold text-gray-700">প্রোমো কোড বা ভাউচার আছে?</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        name="coupon_code" 
                                        id="coupon_code" 
                                        placeholder="যেমন: SOROLATA10" 
                                        value="{{ old('coupon_code') }}"
                                        class="flex-1 px-3 py-2 text-xs border border-gray-300 rounded-xl focus:ring-1 focus:ring-[#214E34] uppercase font-mono tracking-wider"
                                    >
                                    <button 
                                        type="submit" 
                                        formaction="{{ route('checkout.coupon.apply') }}" 
                                        formmethod="POST" 
                                        formnovalidate
                                        class="px-4 py-2 bg-[#214E34] hover:bg-[#163824] text-white text-xs font-semibold rounded-xl transition-all"
                                    >
                                        প্রয়োগ
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-[#E8E8E8] flex justify-between items-baseline">
                        <span class="font-editorial-heading text-xl text-[#1D1D1D]">সর্বমোট (Total Amount)</span>
                        <span class="text-2xl font-black text-[#A05A42]">৳<span x-text="grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                    </div>

                    <button type="submit" class="w-full bg-[#214E34] hover:bg-[#163824] text-white py-4 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-900/20 transition-all flex items-center justify-center space-x-2">
                        <span x-text="paymentMethod === 'cash_on_delivery' ? 'অগ্রিম চার্জ পরিশোধ করে অর্ডার কনফার্ম করুন' : 'পেমেন্ট সম্পন্ন করে অর্ডার কনফার্ম করুন'"></span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>

                    <div class="text-[11px] text-center text-[#888888] space-y-1 pt-1">
                        <p class="flex items-center justify-center gap-1"><span>🛡️</span> ১০০% আসল তাঁতের পণ্য এবং নিরাপদ ডেলিভারি গ্যারান্টি</p>
                        <p>✓ ইনভয়েস ও ট্র্যাকিং নম্বর তাৎক্ষণিকভাবে প্রদান করা হবে</p>
                    </div>
                </div>
            </div>

        </form>

    </div>
@endsection
