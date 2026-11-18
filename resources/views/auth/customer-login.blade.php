@extends('layouts.storefront')

@section('seo_meta')
    <title>Customer Login — Collector Concierge | Sorolata</title>
    <meta name="description" content="Sign in to your Sorolata collector account to track past acquisitions, manage delivery addresses, and view provenance certificates.">
@endsection

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 px-6 lg:px-12 bg-[#F8F6F2]">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E8E8] shadow-soft-lg space-y-8">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">Collector Portal</span>
            <h1 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                অ্যাকাউন্টে লগইন করুন
            </h1>
            <p class="text-xs text-gray-500 font-editorial-body">
                আপনার অর্ডার হিস্টোরি, সেভ করা আইটেম ও ডেলিভারি তথ্য দেখতে লগইন করুন।
            </p>
        </div>

        @if(session('info'))
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->has('login'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                {{ $errors->first('login') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf

            <!-- Email or Phone -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">ইমেইল অথবা মোবাইল নম্বর *</label>
                <input 
                    type="text" 
                    name="login" 
                    value="{{ old('login') }}" 
                    placeholder="example@email.com বা 01XXXXXXXXX"
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required 
                    autofocus
                >
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">পাসওয়ার্ড *</label>
                </div>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="••••••••" 
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs text-gray-600">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="rounded text-[#214E34] focus:ring-[#214E34]">
                    <span>মনে রাখুন</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-[#214E34] hover:bg-[#183a27] text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                লগইন করুন →
            </button>
        </form>

        <div class="pt-4 border-t border-[#E8E8E8] text-center text-xs text-gray-600 space-y-2">
            <p>
                নতুন কাস্টমার? 
                <a href="{{ route('register') }}" class="text-[#214E34] font-bold hover:underline">
                    অ্যাকাউন্ট তৈরি করুন
                </a>
            </p>
            <p class="text-[11px] text-gray-400">
                অ্যাডমিন বা স্টাফ লগইন করতে চান? 
                <a href="{{ route('admin.login') }}" class="text-gray-600 hover:underline">অ্যাডমিন স্টুডিও →</a>
            </p>
        </div>

    </div>
</div>
@endsection
