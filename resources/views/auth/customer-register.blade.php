@extends('layouts.storefront')

@section('seo_meta')
    <title>Create Customer Account — Collector Concierge | Sorolata</title>
    <meta name="description" content="Register your personal collector account at Sorolata to enjoy express checkout, order provenance tracking, and curated heirloom recommendations.">
@endsection

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 px-6 lg:px-12 bg-[#F8F6F2]">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E8E8] shadow-soft-lg space-y-8">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <span class="text-[10px] uppercase tracking-widest text-[#214E34] font-bold">{{ __('New Collector Registration') }}</span>
            <h1 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                {{ __('Create New Account') }}
            </h1>
            <p class="text-xs text-gray-500 font-editorial-body">
                {{ __('Register to unlock express checkout and track heirloom provenance history.') }}
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Your Full Name *') }}</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="{{ __('e.g. Asif Iqbal') }}"
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required 
                    autofocus
                >
            </div>

            <!-- Email Address -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Email Address *') }}</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="name@example.com"
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required
                >
            </div>

            <!-- Mobile Phone -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Active Phone Number *') }}</label>
                <input 
                    type="tel" 
                    name="phone" 
                    value="{{ old('phone') }}" 
                    placeholder="01XXXXXXXXX"
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required
                >
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Password (min 6 characters) *') }}</label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="••••••••" 
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required
                >
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-[#1D1D1D] uppercase">{{ __('Confirm Password *') }}</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    placeholder="••••••••" 
                    class="w-full text-xs p-3.5 rounded-xl border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34] focus:bg-white transition-all font-sans"
                    required
                >
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-[#214E34] hover:bg-[#183a27] text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                    {{ __('Create Account →') }}
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-[#E8E8E8] text-center text-xs text-gray-600">
            <p>
                {{ __('Already have an account?') }} 
                <a href="{{ route('login') }}" class="text-[#214E34] font-bold hover:underline">
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>

    </div>
</div>
@endsection
