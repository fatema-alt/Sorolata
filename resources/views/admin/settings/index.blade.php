@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="{ tab: '{{ $activeTab ?? 'general' }}' }">
    
    <div>
        <h1 class="font-editorial-heading text-3xl text-white font-normal tracking-tight">System & Storefront Configuration</h1>
        <p class="text-xs text-gray-400 mt-1">Configure commerce parameters, payment gateway credentials, courier rules, and database backups.</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-icon name="check" class="w-4 h-4 text-emerald-400 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-400 text-sm">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-icon name="x" class="w-4 h-4 text-rose-400 shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-rose-400/60 hover:text-rose-400 text-sm">&times;</button>
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex border-b border-[#252a36] space-x-2 bg-[#141720] p-1.5 rounded-2xl text-xs font-medium text-gray-400 overflow-x-auto">
        <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
            General & Brand
        </button>
        <button type="button" @click="tab = 'payments'" :class="tab === 'payments' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
            Payment Gateways
        </button>
        <button type="button" @click="tab = 'shipping'" :class="tab === 'shipping' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
            Logistics & Couriers
        </button>
        <button type="button" @click="tab = 'sms'" :class="tab === 'sms' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
            SMS Gateway
        </button>
        <button type="button" @click="tab = 'backup'" :class="tab === 'backup' ? 'bg-[#222733] text-white shadow' : 'hover:text-gray-200'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5">
            <x-icon name="database" class="w-3.5 h-3.5 text-emerald-400" />
            <span>Database Backup</span>
            @if(!empty($backups))
                <span class="px-1.5 py-0.2 text-[10px] bg-emerald-500/20 text-emerald-300 rounded-full font-mono font-semibold">{{ count($backups) }}</span>
            @endif
        </button>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Tab -->
        <div x-show="tab === 'general'" class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-2">Commerce Identity</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Store Name</label>
                    <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'Sorolata' }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Support Email</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'curator@sorolata.com' }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Support Hotline / Concierge</label>
                    <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '+880 1700 000 000' }}" 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Base Currency</label>
                    <input type="text" name="currency" value="{{ $settings['currency'] ?? 'BDT' }}" readonly 
                           class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-gray-400 px-3 py-2.5 cursor-not-allowed">
                </div>
            </div>

            <!-- Store Brand Logo Upload -->
            <div class="pt-3 border-t border-[#252a36]">
                <label class="block text-xs font-semibold text-gray-300 mb-2">Store Brand Logo (স্টোর লোগো)</label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    @if(!empty($settings['store_logo']))
                        <div class="h-12 px-3 py-1 rounded-xl bg-[#0f1115] border border-[#282d37] flex items-center justify-center shrink-0">
                            <img src="{{ $settings['store_logo'] }}" alt="Store Logo" class="h-8 max-w-[140px] object-contain">
                        </div>
                    @endif
                    <div class="flex-1 space-y-2 w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-gray-400 mb-1">Upload Logo from Device</label>
                                <input type="file" name="store_logo_file" accept="image/*" 
                                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-2 text-xs text-gray-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-400 mb-1">Or Logo URL</label>
                                <input type="text" name="store_logo" value="{{ $settings['store_logo'] ?? '' }}" 
                                       placeholder="e.g. /images/logo.png" 
                                       class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Tab -->
        <div x-show="tab === 'payments'" class="space-y-6">
            
            <!-- 1. Mobile Banking Manual (bKash & Nagad) -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white">1. Mobile Banking (বিকাশ ও নগদ ম্যানুয়াল একাউন্ট)</h3>
                        <p class="text-[11px] text-gray-400">গ্রাহক এই নাম্বারে টাকা পাঠিয়ে ট্রানজেকশন আইডি (TrxID) প্রদান করবে।</p>
                    </div>
                </div>

                <!-- bKash Configuration -->
                <div class="p-4 rounded-xl bg-[#12141a] border border-[#282d37] space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-[#E2136E] text-white flex items-center justify-center font-bold text-xs">b</span>
                            <span class="text-xs font-bold text-white">bKash Manual Channel</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="manual_bkash_enabled" value="1" 
                                   {{ ($settings['manual_bkash_enabled'] ?? '1') === '1' ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#E2136E]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">bKash Mobile Number</label>
                            <input type="text" name="manual_bkash_number" value="{{ $settings['manual_bkash_number'] ?? '01711002233' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-[#E2136E] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Account Type</label>
                            <input type="text" name="manual_bkash_type" value="{{ $settings['manual_bkash_type'] ?? 'Personal (Send Money)' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#E2136E] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">bKash Logo URL (Optional)</label>
                            <input type="text" name="manual_bkash_logo" value="{{ $settings['manual_bkash_logo'] ?? '' }}" 
                                   placeholder="Default: /images/payments/bkash.svg"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#E2136E] focus:outline-none font-mono">
                        </div>
                    </div>
                    <!-- bKash Logo Upload from Device -->
                    <div class="pt-2 border-t border-[#1b202c] flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <div class="w-12 h-8 rounded-lg bg-[#0f1115] border border-[#282d37] flex items-center justify-center shrink-0">
                            <img src="{{ !empty($settings['manual_bkash_logo']) ? $settings['manual_bkash_logo'] : asset('images/payments/bkash.svg') }}" alt="bKash" class="h-5 w-auto object-contain">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-[11px] text-gray-400 mb-1">Upload Custom bKash Logo (device থেকে ছবি সিলেক্ট করুন):</label>
                            <input type="file" name="manual_bkash_logo_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-1.5 text-xs text-gray-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Instructions for Customer (বাংলায় নির্দেশাবলী)</label>
                        <textarea name="manual_bkash_instructions" rows="2" 
                                  class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#E2136E] focus:outline-none">{{ $settings['manual_bkash_instructions'] ?? 'বিকাশ অ্যাপ থেকে Send Money করুন। রেফারেন্সে অর্ডার নাম্বার লিখুন।' }}</textarea>
                    </div>
                </div>

                <!-- Nagad Configuration -->
                <div class="p-4 rounded-xl bg-[#12141a] border border-[#282d37] space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-[#F7941D] text-white flex items-center justify-center font-bold text-xs">N</span>
                            <span class="text-xs font-bold text-white">Nagad Manual Channel</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="manual_nagad_enabled" value="1" 
                                   {{ ($settings['manual_nagad_enabled'] ?? '1') === '1' ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F7941D]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Nagad Mobile Number</label>
                            <input type="text" name="manual_nagad_number" value="{{ $settings['manual_nagad_number'] ?? '01811002233' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-[#F7941D] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Account Type</label>
                            <input type="text" name="manual_nagad_type" value="{{ $settings['manual_nagad_type'] ?? 'Personal (Send Money)' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#F7941D] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Nagad Logo URL (Optional)</label>
                            <input type="text" name="manual_nagad_logo" value="{{ $settings['manual_nagad_logo'] ?? '' }}" 
                                   placeholder="Default: /images/payments/nagad.svg"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#F7941D] focus:outline-none font-mono">
                        </div>
                    </div>
                    <!-- Nagad Logo Upload from Device -->
                    <div class="pt-2 border-t border-[#1b202c] flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <div class="w-12 h-8 rounded-lg bg-[#0f1115] border border-[#282d37] flex items-center justify-center shrink-0">
                            <img src="{{ !empty($settings['manual_nagad_logo']) ? $settings['manual_nagad_logo'] : asset('images/payments/nagad.svg') }}" alt="Nagad" class="h-5 w-auto object-contain">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-[11px] text-gray-400 mb-1">Upload Custom Nagad Logo (device থেকে ছবি সিলেক্ট করুন):</label>
                            <input type="file" name="manual_nagad_logo_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-1.5 text-xs text-gray-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Instructions for Customer</label>
                        <textarea name="manual_nagad_instructions" rows="2" 
                                  class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#F7941D] focus:outline-none">{{ $settings['manual_nagad_instructions'] ?? 'নগদ অ্যাপ থেকে Send Money করুন এবং রেফারেন্সে অর্ডার নাম্বার দিন।' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. Bank Wire Accounts (BRAC Bank & DBBL) -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white">2. Bank Accounts (ব্র্যাক ব্যাংক ও ডাচ-বাংলা ব্যাংক)</h3>
                        <p class="text-[11px] text-gray-400">অনলাইন ট্রান্সফার, অ্যাপস (Astha, NexusPay) অথবা ব্রাঞ্চ ডিপোজিটের বিস্তারিত।</p>
                    </div>
                </div>

                <!-- BRAC Bank Configuration -->
                <div class="p-4 rounded-xl bg-[#12141a] border border-[#282d37] space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">B</span>
                            <span class="text-xs font-bold text-white">BRAC Bank Transfer</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="manual_brac_enabled" value="1" 
                                   {{ ($settings['manual_brac_enabled'] ?? '1') === '1' ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Account Name</label>
                            <input type="text" name="manual_brac_account_name" value="{{ $settings['manual_brac_account_name'] ?? 'Sorolata Heritage Ltd' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Account Number</label>
                            <input type="text" name="manual_brac_account_number" value="{{ $settings['manual_brac_account_number'] ?? '1501204859632001' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Branch Name</label>
                            <input type="text" name="manual_brac_branch" value="{{ $settings['manual_brac_branch'] ?? 'Gulshan Branch, Dhaka' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Routing Number</label>
                            <input type="text" name="manual_brac_routing_number" value="{{ $settings['manual_brac_routing_number'] ?? '060261726' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-blue-500 focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-300 mb-1">BRAC Bank Logo URL (Optional)</label>
                            <input type="text" name="manual_brac_logo" value="{{ $settings['manual_brac_logo'] ?? '' }}" 
                                   placeholder="Default: /images/payments/brac_bank.svg"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none font-mono">
                        </div>
                    </div>
                    <!-- BRAC Logo Upload from Device -->
                    <div class="pt-2 border-t border-[#1b202c] flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <div class="w-12 h-8 rounded-lg bg-[#0f1115] border border-[#282d37] flex items-center justify-center shrink-0">
                            <img src="{{ !empty($settings['manual_brac_logo']) ? $settings['manual_brac_logo'] : asset('images/payments/brac_bank.svg') }}" alt="BRAC Bank" class="h-5 w-auto object-contain">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-[11px] text-gray-400 mb-1">Upload Custom BRAC Bank Logo (device থেকে ছবি সিলেক্ট করুন):</label>
                            <input type="file" name="manual_brac_logo_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-1.5 text-xs text-gray-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Instructions for Customer</label>
                        <textarea name="manual_brac_instructions" rows="2" 
                                  class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-blue-500 focus:outline-none">{{ $settings['manual_brac_instructions'] ?? 'ব্র্যাক ব্যাংক (Astha App / ব্রাঞ্চ ডিপোজিট) করে ডিপোজিট স্লিপ বা ট্রানজেকশন রেফারেন্স প্রদান করুন।' }}</textarea>
                    </div>
                </div>

                <!-- Dutch-Bangla Bank Configuration -->
                <div class="p-4 rounded-xl bg-[#12141a] border border-[#282d37] space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-orange-600 text-white flex items-center justify-center font-bold text-xs">D</span>
                            <span class="text-xs font-bold text-white">Dutch-Bangla Bank (DBBL)</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="manual_dbbl_enabled" value="1" 
                                   {{ ($settings['manual_dbbl_enabled'] ?? '1') === '1' ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-600"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Account Name</label>
                            <input type="text" name="manual_dbbl_account_name" value="{{ $settings['manual_dbbl_account_name'] ?? 'Sorolata Artisan Commerce' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-orange-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Account Number</label>
                            <input type="text" name="manual_dbbl_account_number" value="{{ $settings['manual_dbbl_account_number'] ?? '1151200034567' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-orange-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Branch Name</label>
                            <input type="text" name="manual_dbbl_branch" value="{{ $settings['manual_dbbl_branch'] ?? 'Dhanmondi Branch, Dhaka' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-orange-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Routing Number</label>
                            <input type="text" name="manual_dbbl_routing_number" value="{{ $settings['manual_dbbl_routing_number'] ?? '090261345' }}" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-orange-500 focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Dutch-Bangla / Rocket Logo URL (Optional)</label>
                            <input type="text" name="manual_dbbl_logo" value="{{ $settings['manual_dbbl_logo'] ?? '' }}" 
                                   placeholder="Default: /images/payments/rocket.svg"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-orange-500 focus:outline-none font-mono">
                        </div>
                    </div>
                    <!-- DBBL Logo Upload from Device -->
                    <div class="pt-2 border-t border-[#1b202c] flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <div class="w-12 h-8 rounded-lg bg-[#0f1115] border border-[#282d37] flex items-center justify-center shrink-0">
                            <img src="{{ !empty($settings['manual_dbbl_logo']) ? $settings['manual_dbbl_logo'] : asset('images/payments/rocket.svg') }}" alt="Rocket" class="h-5 w-auto object-contain">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-[11px] text-gray-400 mb-1">Upload Custom DBBL / Rocket Logo (device থেকে ছবি সিলেক্ট করুন):</label>
                            <input type="file" name="manual_dbbl_logo_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-1.5 text-xs text-gray-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Instructions for Customer</label>
                        <textarea name="manual_dbbl_instructions" rows="2" 
                                  class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-orange-500 focus:outline-none">{{ $settings['manual_dbbl_instructions'] ?? 'ডাচ-বাংলা ব্যাংক (NexusPay App / রকেট / ব্যাংক ট্রান্সফার) করে ট্রানজেকশন রেফারেন্স প্রদান করুন।' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 3. COD & Advance Delivery Charge -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white">3. Cash on Delivery (ক্যাশ অন ডেলিভারি - অগ্রিম ডিসি)</h3>
                        <p class="text-[11px] text-gray-400">ফেক অর্ডার প্রতিরোধে অগ্রিম ডেলিভারি চার্জ নির্ধারণ করুন।</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_cod_enabled" value="1" 
                               {{ ($settings['payment_cod_enabled'] ?? '1') === '1' ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#214E34]"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">ঢাকা সিটি অগ্রিম চার্জ (৳)</label>
                        <input type="number" name="cod_advance_charge_dhaka" value="{{ $settings['cod_advance_charge_dhaka'] ?? '80' }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">ঢাকার বাইরে অগ্রিম চার্জ (৳)</label>
                        <input type="number" name="cod_advance_charge_outside" value="{{ $settings['cod_advance_charge_outside'] ?? '150' }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">COD নীতি ও নির্দেশনা (Customer Instructions)</label>
                    <textarea name="cod_instructions" rows="2" 
                              class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#214E34] focus:outline-none">{{ $settings['cod_instructions'] ?? 'ফেক অর্ডার ও পার্সেল রিটার্ন রোধে শুধুমাত্র কুরিয়ার চার্জ অগ্রিম বিকাশ, নগদ বা ব্যাংকে পরিশোধ করে TrxID সাবমিট করুন। অবশিষ্ট মূল্য পণ্য হাতে পাওয়ার পর প্রদান করবেন।' }}</textarea>
                </div>
            </div>

            <!-- 4. SSLCommerz Automated Gateway (ভবিষ্যতে ব্যবহারের জন্য সরাসরি কনফিগারেশন) -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-[#0A3B5C] text-white flex items-center justify-center font-bold text-xs">SSL</span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-white">4. SSLCommerz Gateway (কার্ড ও নেট ব্যাংকিং গেটওয়ে)</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">ভবিষ্যতে SSLCommerz অ্যাকাউন্ট অ্যাক্টিভ হলে এখানে Store ID ও Password দিলেই সরাসরি সক্রিয় হয়ে যাবে।</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_sslcommerz_enabled" value="1" 
                               {{ ($settings['payment_sslcommerz_enabled'] ?? '0') === '1' ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0A3B5C]"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Gateway Environment (মোড)</label>
                        <select name="sslcommerz_mode" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#0A3B5C] focus:outline-none">
                            <option value="sandbox" {{ ($settings['sslcommerz_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Test Environment)</option>
                            <option value="live" {{ ($settings['sslcommerz_mode'] ?? '') === 'live' ? 'selected' : '' }}>Live (Production Gateway)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Store ID</label>
                        <input type="text" name="sslcommerz_store_id" value="{{ $settings['sslcommerz_store_id'] ?? '' }}" 
                               placeholder="e.g. sorolatalive001"
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-[#0A3B5C] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Store Password / Secret Key</label>
                        <input type="password" name="sslcommerz_store_password" value="{{ $settings['sslcommerz_store_password'] ?? '' }}" 
                               placeholder="••••••••••••"
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-[#0A3B5C] focus:outline-none">
                    </div>
                </div>

                <!-- SSLCommerz Logo Upload -->
                <div class="pt-2 border-t border-[#1b202c] flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <div class="w-16 h-8 rounded-lg bg-[#0f1115] border border-[#282d37] flex items-center justify-center shrink-0">
                        <img src="{{ !empty($settings['sslcommerz_logo']) ? $settings['sslcommerz_logo'] : asset('images/payments/sslcommerz.svg') }}" alt="SSLCommerz" class="h-5 w-auto object-contain">
                    </div>
                    <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] text-gray-400 mb-1">Upload Gateway Logo (device থেকে ছবি দিন):</label>
                            <input type="file" name="sslcommerz_logo_file" accept="image/*" 
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl px-3 py-1.5 text-xs text-gray-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-[#222733] file:text-white cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-400 mb-1">Or Logo URL:</label>
                            <input type="text" name="sslcommerz_logo" value="{{ $settings['sslcommerz_logo'] ?? '' }}" 
                                   placeholder="Default: /images/payments/sslcommerz.svg"
                                   class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2 focus:border-[#0A3B5C] focus:outline-none font-mono">
                        </div>
                    </div>
                </div>

                <!-- Webhook & IPN Endpoints info -->
                <div class="p-3 rounded-xl bg-[#0b0d12] border border-[#232834] text-[10px] text-gray-400 space-y-1 font-mono">
                    <div class="text-gray-300 font-bold uppercase tracking-wider mb-1">Integrated Webhook &amp; IPN Endpoints:</div>
                    <div class="flex justify-between"><span>Success URL:</span> <span class="text-emerald-400">{{ url('/payment/sslcommerz/success') }}</span></div>
                    <div class="flex justify-between"><span>Fail URL:</span> <span class="text-rose-400">{{ url('/payment/sslcommerz/fail') }}</span></div>
                    <div class="flex justify-between"><span>Cancel URL:</span> <span class="text-amber-400">{{ url('/payment/sslcommerz/cancel') }}</span></div>
                    <div class="flex justify-between"><span>IPN Notification URL:</span> <span class="text-cyan-400">{{ url('/payment/sslcommerz/ipn') }}</span></div>
                </div>
            </div>

        </div>

        <!-- Shipping Tab -->
        <div x-show="tab === 'shipping'" class="space-y-6">
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-2">Fulfillment Rules</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Domestic Free Shipping Minimum (৳)</label>
                        <input type="number" name="shipping_free_threshold" value="{{ $settings['shipping_free_threshold'] ?? 5000 }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Default Standard Shipping Fee (৳)</label>
                        <input type="number" name="shipping_default_fee" value="{{ $settings['shipping_default_fee'] ?? 80 }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-[#214E34] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Steadfast Courier API Integration -->
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-600/30 text-emerald-400 flex items-center justify-center font-bold text-xs">📦</span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-white">Steadfast Courier API (স্ট্যাডফাস্ট কুরিয়ার অটো বুকিং)</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">অর্ডার ডিটেইল পেজ থেকে ১-ক্লিকে সরাসরি স্ট্যাডফাস্ট পোর্টালে পার্সেল এন্ট্রি ও ট্র্যাকিং কোড জেনারেট হবে।</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Steadfast API Key</label>
                        <input type="password" name="steadfast_api_key" value="{{ $settings['steadfast_api_key'] ?? '' }}" 
                               placeholder="আপনার Steadfast API Key পেস্ট করুন"
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Steadfast Secret Key</label>
                        <input type="password" name="steadfast_secret_key" value="{{ $settings['steadfast_secret_key'] ?? '' }}" 
                               placeholder="আপনার Steadfast Secret Key পেস্ট করুন"
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="p-3 bg-emerald-950/20 border border-emerald-900/40 rounded-xl text-[11px] text-emerald-300 flex items-start space-x-2">
                    <span class="text-emerald-400 mt-0.5">ℹ️</span>
                    <div>
                        <strong>অটোমেটিক মোড:</strong> API Key ও Secret Key ফাঁকা থাকলে সিস্টেম স্বয়ংক্রিয়ভাবে নির্ভুল <strong>Sandbox Mock Mode</strong>-এ ট্র্যাকিং কোড প্রস্তুত করে টেস্ট করতে দেবে। লাইভ করতে শুধুমাত্র আপনার আসল Steadfast প্যানেলের কী (Keys) বসিয়ে দিন।
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Gateway Tab -->
        <div x-show="tab === 'sms'" class="space-y-6">
            <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600/30 text-blue-400 flex items-center justify-center font-bold text-xs">💬</span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-white">Bangladeshi SMS Gateway Configuration</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">অর্ডার গ্রহণ, পেমেন্ট অনুমোদন এবং কুরিয়ারে বুকিংয়ের সাথে সাথে গ্রাহককে স্বয়ংক্রিয় SMS পাঠানো হবে।</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_enabled" value="1" 
                               {{ ($settings['sms_enabled'] ?? '1') === '1' ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">SMS Gateway Provider</label>
                        <select name="sms_provider" class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                            <option value="log" {{ ($settings['sms_provider'] ?? 'log') === 'log' ? 'selected' : '' }}>Simulator / Log Mode (Free Testing)</option>
                            <option value="greenweb" {{ ($settings['sms_provider'] ?? '') === 'greenweb' ? 'selected' : '' }}>Greenweb BD</option>
                            <option value="elitbuzz" {{ ($settings['sms_provider'] ?? '') === 'elitbuzz' ? 'selected' : '' }}>Elitbuzz BD</option>
                            <option value="mim_sms" {{ ($settings['sms_provider'] ?? '') === 'mim_sms' ? 'selected' : '' }}>MimSMS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">API Key / Token</label>
                        <input type="password" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" 
                               placeholder="Gateway API Token"
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Sender ID / Masking</label>
                        <input type="text" name="sms_sender_id" value="{{ $settings['sms_sender_id'] ?? 'Sorolata' }}" 
                               placeholder="e.g. Sorolata or 88096..."
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Order Placed Template (অর্ডার গ্রহণের পর SMS)</label>
                        <input type="text" name="sms_order_placed_template" value="{{ $settings['sms_order_placed_template'] ?? 'Dear {name}, your Sorolata order #{order_number} (৳{total}) is placed successfully! Track: {tracking_url}' }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Payment Confirmed Template (পেমেন্ট ভেরিফাই হওয়ার পর SMS)</label>
                        <input type="text" name="sms_order_confirmed_template" value="{{ $settings['sms_order_confirmed_template'] ?? 'Dear {name}, payment for order #{order_number} is verified! Atelier preparation started. Track: {tracking_url}' }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Order Shipped Template (কুরিয়ারে ডিসপ্যাচের পর SMS)</label>
                        <input type="text" name="sms_order_shipped_template" value="{{ $settings['sms_order_shipped_template'] ?? 'Dear {name}, order #{order_number} dispatched via {courier}. Tracking: {tracking_number}. Track live: {tracking_url}' }}" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2" x-show="tab !== 'backup'">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#214E34] hover:bg-[#2c6846] text-white text-xs font-semibold shadow-sm transition-all">
                Save All Settings
            </button>
        </div>

    </form>

    <!-- Database Backup Tab (Independent of settings form) -->
    <div x-show="tab === 'backup'" class="space-y-6">
        
        <!-- 1. Stats Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Database Engine</span>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-white font-mono truncate max-w-[140px]">{{ $backupStats['database'] ?? 'sorolata' }}</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase font-mono">
                        {{ $backupStats['driver'] ?? 'MYSQL' }}
                    </span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Tables & Records</span>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-white">{{ $backupStats['tables_count'] ?? 0 }} Tables</span>
                    <span class="text-xs text-gray-400 font-mono">~{{ number_format($backupStats['total_records'] ?? 0) }} Rows</span>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Backup Storage</span>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-white">{{ $backupStats['backups_count'] ?? 0 }} Files</span>
                    <span class="text-xs text-emerald-400 font-mono font-semibold">{{ $backupStats['total_backup_size'] ?? '0 B' }}</span>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="p-4 rounded-2xl bg-[#161922] border border-[#252a36] space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Latest Backup</span>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-white truncate max-w-[150px]">
                        {{ $backupStats['last_backup_at'] ?? 'No backups yet' }}
                    </span>
                    <x-icon name="shield-check" class="w-4 h-4 text-emerald-400 shrink-0" />
                </div>
            </div>
        </div>

        <!-- 2. Create Backup Action Panel -->
        <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#252a36]">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <x-icon name="database" class="w-4 h-4 text-emerald-400" />
                        <span>Create Database Backup (নতুন ডাটাবেজ ব্যাকআপ তৈরি করুন)</span>
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">
                        সম্পূর্ণ ডাটাবেজ (সকল প্রোডাক্ট, ক্যাটাগরি, অর্ডার, কাস্টমার রেকর্ড, পেমেন্ট ট্রানজেকশন ও সেটিংস) এর একটি সম্পূর্ণ SQL ডাম্প ব্যাকআপ তৈরি হবে।
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.backup.create') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-300 mb-1">
                            Backup Label / Note (ঐচ্ছিক নাম)
                        </label>
                        <input type="text" name="custom_name" placeholder="e.g. before-product-import, monthly-archive" 
                               class="w-full bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white px-3 py-2.5 font-mono focus:border-emerald-500 focus:outline-none">
                        <span class="text-[10px] text-gray-500 mt-1 block">ফাইলের নামের সাথে স্বয়ংক্রিয়ভাবে টাইমস্ট্যাম্প (তারিখ ও সময়) যুক্ত হবে।</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Direct Instant Download Button -->
                        <button type="submit" name="download_now" value="1" 
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-sm transition-all whitespace-nowrap cursor-pointer">
                            <x-icon name="download" class="w-4 h-4" />
                            <span>Download .SQL Now</span>
                        </button>

                        <!-- Save to Server Button -->
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#222733] hover:bg-[#2c3344] text-gray-200 hover:text-white text-xs font-semibold border border-[#333b4e] transition-all whitespace-nowrap cursor-pointer">
                            <x-icon name="database" class="w-4 h-4 text-emerald-400" />
                            <span>Save to Server</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 3. Existing Backups Archive Table -->
        <div class="p-6 rounded-2xl bg-[#161922] border border-[#252a36] space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#252a36]">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Stored Backup Archives (সংরক্ষিত ব্যাকআপ তালিকা)</h3>
                    <p class="text-[11px] text-gray-400">সার্ভার স্টোরেজে সংরক্ষিত ব্যাকআপ ফাইলসমূহ এখান থেকে সরাসরি ডাউনলোড অথবা মুছে ফেলা যাবে।</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-[#111318] border border-[#252a36] text-[11px] font-mono text-gray-400">
                    Directory: storage/app/backups
                </span>
            </div>

            @if(empty($backups))
                <div class="py-10 text-center space-y-2">
                    <x-icon name="database" class="w-8 h-8 text-gray-600 mx-auto" />
                    <p class="text-xs text-gray-400 font-medium">কোনো ব্যাকআপ ফাইল পাওয়া যায়নি।</p>
                    <p class="text-[11px] text-gray-500">উপরের বাটনটিতে ক্লিক করে আপনার প্রথম ডাটাবেজ ব্যাকআপ তৈরি করুন।</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-[#252a36] text-gray-400 font-semibold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="pb-3">File Name</th>
                                <th class="pb-3">Size</th>
                                <th class="pb-3">Created Date</th>
                                <th class="pb-3">Age</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#252a36]/50">
                            @foreach($backups as $b)
                                <tr class="hover:bg-[#1a1e28]/50 transition-colors">
                                    <td class="py-3 font-mono text-emerald-400 font-medium flex items-center gap-2">
                                        <x-icon name="database" class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
                                        <span class="truncate max-w-xs sm:max-w-md">{{ $b['filename'] }}</span>
                                    </td>
                                    <td class="py-3 font-mono text-gray-300 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded bg-[#101217] border border-[#252a36] text-[11px]">
                                            {{ $b['size'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-gray-300 font-mono text-[11px] whitespace-nowrap">
                                        {{ $b['created_at'] }}
                                    </td>
                                    <td class="py-3 text-gray-400 text-[11px] whitespace-nowrap">
                                        {{ $b['relative_time'] }}
                                    </td>
                                    <td class="py-3 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <!-- Download Button -->
                                            <a href="{{ route('admin.settings.backup.download', $b['filename']) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 text-[11px] font-medium border border-emerald-500/30 transition-all">
                                                <x-icon name="download" class="w-3 h-3" />
                                                <span>Download</span>
                                            </a>

                                            <!-- Delete Button Form -->
                                            <form method="POST" action="{{ route('admin.settings.backup.delete', $b['filename']) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete this backup file? (এই ব্যাকআপ ফাইলটি মুছে ফেলতে চান?)')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-600/10 hover:bg-rose-600/20 text-rose-400 text-[11px] font-medium border border-rose-500/20 transition-all cursor-pointer" 
                                                        title="Delete backup">
                                                    <x-icon name="trash" class="w-3 h-3" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 4. Quick Recovery & Developer Guidelines -->
        <div class="p-6 rounded-2xl bg-[#141720] border border-[#252a36] space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-300 flex items-center gap-2">
                <x-icon name="shield-check" class="w-4 h-4 text-blue-400" />
                <span>Database Restoration Guide (ব্যাকআপ রিস্টোর করার নিয়ম)</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-400">
                <div class="space-y-1.5 p-3.5 rounded-xl bg-[#0d0f14] border border-[#212530]">
                    <span class="font-bold text-white block">১. phpMyAdmin বা GUI দিয়ে রিস্টোর:</span>
                    <p class="text-[11px] leading-relaxed">
                        phpMyAdmin-এ গিয়ে <code class="text-emerald-400 font-mono">sorolata</code> ডাটাবেজ সিলেক্ট করুন। এরপর <strong>Import</strong> ট্যাবে ক্লিক করে আপনার ডাউনলোড করা <code class="text-emerald-400 font-mono">.sql</code> ফাইলটি সিলেক্ট করে <strong>Import / Go</strong> বাটনে চাপুন।
                    </p>
                </div>
                <div class="space-y-1.5 p-3.5 rounded-xl bg-[#0d0f14] border border-[#212530]">
                    <span class="font-bold text-white block">২. Terminal বা Command Line দিয়ে:</span>
                    <p class="text-[11px] leading-relaxed">
                        কমান্ড লাইনে নিচের কমান্ডটি রান করলেই দ্রুত রিস্টোর হয়ে যাবে:
                    </p>
                    <code class="block text-[11px] font-mono text-emerald-400 bg-black/40 p-2 rounded-lg overflow-x-auto">
                        mysql -u root -p sorolata &lt; backup_file.sql
                    </code>
                </div>
            </div>
            <div class="pt-2 text-[11px] text-gray-500 flex items-center justify-between border-t border-[#252a36]">
                <span>Artisan CLI Backup Command: <code class="text-emerald-400 font-mono">php artisan db:backup</code></span>
                <span>Storage Path: <code class="text-gray-400 font-mono">storage/app/backups/</code></span>
            </div>
        </div>

    </div>

</div>
@endsection
