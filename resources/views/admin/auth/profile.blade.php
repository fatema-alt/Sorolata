@extends('layouts.admin', ['title' => 'Curator Profile & Security'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between pb-6 border-b border-[#252a36]">
        <div>
            <h1 class="font-editorial-heading text-3xl font-light text-white">
                Curator Credentials & Security
            </h1>
            <p class="text-xs text-gray-400 mt-1">
                Manage your administrative credentials, active session parameters, and password.
            </p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->role?->badgeClass() ?? 'bg-emerald-950 text-emerald-400' }} border">
            {{ $user->role?->label() ?? 'Administrator' }}
        </span>
    </div>

    <!-- Edit Profile Card -->
    <div class="bg-[#161922] border border-[#252a36] rounded-2xl p-6 lg:p-8 space-y-6">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300">
                1. Personnel Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        class="w-full px-4 py-2.5 bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white focus:outline-none focus:border-[#214E34]"
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400">Email Address (Immutable)</label>
                    <input 
                        type="email" 
                        value="{{ $user->email }}" 
                        disabled 
                        class="w-full px-4 py-2.5 bg-[#0a0c10] border border-[#1f232b] rounded-xl text-xs text-gray-500 cursor-not-allowed"
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400">Phone Number (SMS Alert Dispatch)</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', $user->phone) }}" 
                        class="w-full px-4 py-2.5 bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white focus:outline-none focus:border-[#214E34]"
                    >
                </div>
            </div>

            <hr class="border-[#252a36] my-6">

            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300">
                2. Change Administrative Password
            </h3>
            <p class="text-xs text-gray-500">
                Leave these fields empty if you do not wish to modify your administrative password.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-xs font-semibold text-gray-400">Current Password</label>
                    <input 
                        type="password" 
                        name="current_password" 
                        class="w-full max-w-md px-4 py-2.5 bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white focus:outline-none focus:border-[#214E34]"
                        placeholder="Current password..."
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400">New Password</label>
                    <input 
                        type="password" 
                        name="new_password" 
                        class="w-full px-4 py-2.5 bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white focus:outline-none focus:border-[#214E34]"
                        placeholder="Minimum 6 characters"
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400">Confirm New Password</label>
                    <input 
                        type="password" 
                        name="new_password_confirmation" 
                        class="w-full px-4 py-2.5 bg-[#0f1115] border border-[#282d37] rounded-xl text-xs text-white focus:outline-none focus:border-[#214E34]"
                        placeholder="Re-type new password"
                    >
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-[#214E34] hover:bg-[#2c6946] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md"
                >
                    Update Credentials
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
