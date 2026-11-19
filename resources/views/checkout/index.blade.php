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
                <span class="text-xs uppercase tracking-widest text-[#214E34] font-bold">{{ __('Frictionless Concierge') }}</span>
                <h1 class="font-editorial-heading text-4xl sm:text-5xl font-light text-[#1D1D1D] mt-1">
                    {{ __('One-Page Checkout') }}
                </h1>
            </div>
            <div class="flex items-center gap-2 text-xs text-[#214E34] bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ __('Official gateway & direct verified merchant account') }}</span>
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

        @auth
            <form id="checkout-logout-form" method="POST" action="{{ route('customer.logout') }}" class="hidden">
                @csrf
            </form>
        @endauth

        <form id="checkout-order-form" action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            @csrf

            <!-- Left: Checkout Forms -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- 1. Collector Information -->
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                            {{ __('1. Collector Contact') }}
                        </h2>
                        @auth
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100/80 text-[#214E34]">{{ __('Account Verified') }}</span>
                        @else
                            <span class="text-xs text-[#888888]">{{ __('Guest Checkout') }}</span>
                        @endauth
                    </div>

                    @auth
                        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-[#214E34] text-xs flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <x-icon name="user" class="w-4 h-4 text-[#214E34]" />
                                <span>{{ __('Logged in as') }} <strong class="text-gray-900">{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</span>
                            </div>
                            <button type="submit" form="checkout-logout-form" class="text-xs text-[#C96A3D] font-bold hover:underline cursor-pointer">
                                {{ __('Log out / Guest Checkout') }}
                            </button>
                        </div>
                    @else
                        <div class="p-3.5 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8] text-xs text-[#555555] flex flex-wrap items-center justify-between gap-2">
                            <span>{{ __('Checking out as Guest. Fill in your details below.') }}</span>
                            <a href="{{ route('login') }}" class="text-[#214E34] font-bold hover:underline">
                                {{ __('Already registered? Log in →') }}
                            </a>
                        </div>
                    @endauth

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Your Full Name *') }}</label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                value="{{ old('customer_name', auth()->user()?->name ?? '') }}" 
                                placeholder="{{ __('Enter your full name') }}"
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                                required
                            >
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Email Address *') }}</label>
                            <input 
                                type="email" 
                                name="customer_email" 
                                value="{{ old('customer_email', auth()->user()?->email ?? '') }}" 
                                placeholder="{{ __('Enter email address') }}"
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                                required
                            >
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Active Phone Number *') }}</label>
                        <input 
                            type="tel" 
                            name="customer_phone" 
                            value="{{ old('customer_phone', auth()->user()?->phone ?? '') }}" 
                            placeholder="01XXXXXXXXX"
                            class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                            required
                        >
                        <p class="text-[11px] text-[#777777]">{{ __('Used for delivery tracking and SMS notifications.') }}</p>
                    </div>
                </div>

                <!-- 2. Insured Shipping Address -->
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                    <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                        {{ __('2. Delivery Address') }}
                    </h2>

                    <!-- District Selection Buttons -->
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Select Delivery Area') }}</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                @click="selectedDistrict = 'Dhaka'" 
                                :class="isDhaka ? 'border-[#214E34] bg-emerald-50/70 text-[#214E34] font-bold shadow-sm' : 'border-gray-200 bg-white text-gray-700'"
                                class="p-3.5 rounded-2xl border-2 text-left flex items-center justify-between transition-all"
                            >
                                <div>
                                    <div class="text-sm font-semibold">{{ __('Inside Dhaka City') }}</div>
                                    <div class="text-[11px] opacity-75">{{ __('Home Delivery Fee ৳:fee', ['fee' => (int)$dhakaFee]) }}</div>
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
                                    <div class="text-sm font-semibold">{{ __('Outside Dhaka (All BD)') }}</div>
                                    <div class="text-[11px] opacity-75">{{ __('Courier Delivery Fee ৳:fee', ['fee' => (int)$outsideFee]) }}</div>
                                </div>
                                <span class="text-base" x-show="!isDhaka">✓</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Full Address (House / Road / Area) *') }}</label>
                        <input 
                            type="text" 
                            name="delivery_address" 
                            value="{{ old('delivery_address', '') }}" 
                            placeholder="{{ __('House, flat, road, area...') }}"
                            class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('District *') }}</label>
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
                            <label class="text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Delivery Note (Optional)') }}</label>
                            <input 
                                type="text" 
                                name="gift_note" 
                                placeholder="{{ __('Write special delivery instructions if any...') }}" 
                                class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white font-sans"
                            >
                        </div>
                    </div>
                </div>

                <!-- 3. Payment Method (Selected on Next Page) -->
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                            {{ __('3. Payment Selection') }}
                        </h2>
                        <span class="text-xs text-emerald-800 bg-emerald-100/80 px-2.5 py-1 rounded-full font-medium">{{ __('SSL / 256-bit Encrypted') }}</span>
                    </div>

                    <div class="p-5 rounded-2xl bg-[#F8F6F2] border border-[#E8E8E8] space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-xl bg-white border border-[#E8E8E8] text-[#214E34] shrink-0">
                                <x-icon name="shield-check" class="w-5 h-5 text-[#214E34]" />
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                                    {{ __('Choose Payment Method on Next Step') }}
                                </h3>
                                <p class="text-xs text-gray-600 leading-relaxed font-sans">
                                    {{ __('You will choose your payment method (bKash, Nagad, BRAC Bank, DBBL/Rocket, or Cash on Delivery) on the payment page immediately after clicking proceed.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Available Methods Badges / Logos -->
                        <div class="pt-3 border-t border-[#E8E8E8] flex flex-wrap items-center gap-2">
                            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mr-1">{{ __('Available:') }}</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-[#E8E8E8] text-xs font-bold text-[#E2136E] shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-[#E2136E]"></span> bKash
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-[#E8E8E8] text-xs font-bold text-[#F7941D] shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-[#F7941D]"></span> Nagad
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-[#E8E8E8] text-xs font-bold text-[#0054A6] shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-[#0054A6]"></span> BRAC Bank
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-[#E8E8E8] text-xs font-bold text-[#8C3494] shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-[#8C3494]"></span> Rocket
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-[#E8E8E8] text-xs font-bold text-[#214E34] shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-[#214E34]"></span> Cash on Delivery
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary Sticky Box -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-lg space-y-6 sticky top-28">
                    <h2 class="font-editorial-heading text-2xl font-light text-[#1D1D1D] pb-4 border-b border-[#E8E8E8]">
                        {{ __('Order Summary') }}
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
                                    <span class="text-xs text-[#888888]">{{ __('Qty: :qty', ['qty' => $item['quantity']]) }}</span>
                                </div>
                                <div class="text-xs font-bold text-gray-900">
                                    ৳{{ number_format($item['subtotal'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-[#E8E8E8] space-y-2 text-xs text-[#555555]">
                        <div class="flex justify-between">
                            <span>{{ __('Bag Subtotal') }}</span>
                            <span class="font-semibold text-gray-900">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discountAmount > 0)
                            <div class="flex justify-between text-emerald-700 font-semibold">
                                <span>{{ __('Coupon Discount (:code)', ['code' => $coupon['code'] ?? '']) }}</span>
                                <span>-৳{{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>{{ __('Delivery Charge') }} (<span x-text="isDhaka ? 'ঢাকা' : 'ঢাকার বাইরে'"></span>)</span>
                            <span class="text-[#2D6A4F] font-semibold">৳<span x-text="shippingFee"></span>.00</span>
                        </div>
                        <div class="pt-2 border-t border-dashed border-[#E8E8E8] text-xs text-gray-500 flex items-center justify-between">
                            <span>{{ __('Payment Method:') }}</span>
                            <span class="text-[#214E34] font-medium">{{ __('Choose on next step') }}</span>
                        </div>
                    </div>

                    <!-- Coupon / Promo Section -->
                    <div class="pt-4 border-t border-[#E8E8E8]">
                        @if($coupon)
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-emerald-700 text-sm">🎟️</span>
                                    <div>
                                        <div class="text-xs font-bold text-emerald-900 tracking-wider uppercase">{{ $coupon['code'] }}</div>
                                        <div class="text-[11px] text-emerald-700">{{ __('Coupon discount active (-৳:amount)', ['amount' => number_format($discountAmount, 2)]) }}</div>
                                    </div>
                                </div>
                                <button type="submit" formaction="{{ route('checkout.coupon.remove') }}" formmethod="POST" formnovalidate class="text-xs text-rose-600 hover:text-rose-800 underline font-semibold">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        @else
                            <div class="space-y-1.5">
                                <label for="coupon_code" class="text-xs font-semibold text-gray-700">{{ __('Have a Promo Code or Voucher?') }}</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        name="coupon_code" 
                                        id="coupon_code" 
                                        placeholder="{{ __('e.g. SOROLATA10') }}" 
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
                                        {{ __('Apply') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-[#E8E8E8] flex justify-between items-baseline">
                        <span class="font-editorial-heading text-xl text-[#1D1D1D]">{{ __('Total Amount') }}</span>
                        <span class="text-2xl font-black text-[#A05A42]">৳<span x-text="grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                    </div>

                    <button type="submit" form="checkout-order-form" class="w-full bg-[#214E34] hover:bg-[#163824] text-white py-4 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-900/20 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                        <span>{{ __('Place Order & Proceed to Payment →') }}</span>
                    </button>

                    <div class="text-[11px] text-center text-[#888888] space-y-1 pt-1">
                        <p class="flex items-center justify-center gap-1"><span>🛡️</span> {{ __('100% authentic artisanal goods and safe delivery guarantee') }}</p>
                        <p>{{ __('Instant digital invoice and courier tracking ID provided') }}</p>
                    </div>
                </div>
            </div>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkout-order-form');
            if (checkoutForm) {
                checkoutForm.addEventListener('invalid', function(e) {
                    const firstInvalid = checkoutForm.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalid.focus();
                    }
                }, true);
            }
        });
    </script>
@endsection
