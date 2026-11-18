<!DOCTYPE html>
<html lang="bn" class="h-full antialiased bg-[#0a0c10]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Studio Login — Sorolata Merchant Operations</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .font-editorial { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full font-sans text-gray-200 antialiased flex items-center justify-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#18231c] via-[#0f1218] to-[#08090c]" 
      x-data="{ 
          showPassword: false,
          fillDemo(email, pass) {
              document.getElementById('email').value = email;
              document.getElementById('password').value = pass;
          }
      }">

    <div class="max-w-md w-full space-y-6">
        
        <!-- Header Wordmark -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#163824] to-[#214E34] border border-[#2e6d49]/40 shadow-xl shadow-emerald-950/50 mb-2">
                <span class="font-editorial text-2xl font-bold text-white tracking-widest">S</span>
            </div>
            <h1 class="font-editorial text-3xl sm:text-4xl font-normal text-white tracking-tight">
                Sorolata Studio
            </h1>
            <p class="text-xs uppercase tracking-widest text-[#78a98a] font-semibold">
                Executive Management & Merchant Operations
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-[#141720]/90 backdrop-blur-xl border border-[#252b38] rounded-3xl p-8 shadow-2xl shadow-black/80 space-y-6">
            
            <!-- Alert / Flash Messages -->
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-950/70 border border-emerald-800 text-emerald-300 text-xs flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-950/70 border border-rose-800 text-rose-300 text-xs flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/70 border border-rose-800 text-rose-300 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <span>•</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-400">
                        অ্যাডমিন ইমেইল (Staff Email)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', 'admin@sorolata.com') }}" 
                            required 
                            autofocus
                            class="w-full pl-10 pr-4 py-3 bg-[#0d1016] border border-[#2b3240] rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34] transition-all font-sans"
                            placeholder="admin@sorolata.com"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-400">
                            পাসওয়ার্ড (Password)
                        </label>
                        <button type="button" @click="showPassword = !showPassword" class="text-[11px] text-[#A3D9B1] hover:underline focus:outline-none">
                            <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                        </button>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            id="password" 
                            value="password"
                            required 
                            class="w-full pl-10 pr-10 py-3 bg-[#0d1016] border border-[#2b3240] rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-[#214E34] focus:ring-1 focus:ring-[#214E34] transition-all font-mono"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Remember Me & Protection -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-gray-300">
                        <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded bg-[#0d1016] border-[#2b3240] text-[#214E34] focus:ring-[#214E34]">
                        <span>Remember session (স্মরণ রাখুন)</span>
                    </label>
                    <span class="text-[11px] text-gray-500">256-Bit SSL Guard</span>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-[#1b432c] to-[#214E34] hover:from-[#214E34] hover:to-[#2c6946] text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-950/60 border border-[#2e6d49]/50 transition-all flex items-center justify-center gap-2"
                >
                    <span>স্টুডিওতে প্রবেশ করুন (Sign In to Studio)</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Quick Dev / Demo Autofill Helper -->
            <div class="pt-4 border-t border-[#252b38]/80 text-[11px] text-gray-400 space-y-2">
                <div class="flex items-center justify-between text-gray-500 uppercase tracking-wider font-semibold">
                    <span>Quick Autofill (টেস্টিং লগইন):</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="fillDemo('admin@sorolata.com', 'password')" class="p-2 rounded-lg bg-[#0d1016] hover:bg-[#1a202c] border border-[#282f3d] text-left transition-colors">
                        <div class="font-bold text-gray-200">Super Admin</div>
                        <div class="text-[10px] text-gray-500 truncate">admin@sorolata.com</div>
                    </button>
                    <button type="button" @click="fillDemo('manager@sorolata.com', 'password')" class="p-2 rounded-lg bg-[#0d1016] hover:bg-[#1a202c] border border-[#282f3d] text-left transition-colors">
                        <div class="font-bold text-gray-200">Store Manager</div>
                        <div class="text-[10px] text-gray-500 truncate">manager@sorolata.com</div>
                    </button>
                </div>
            </div>

        </div>

        <!-- Footer Note -->
        <div class="text-center text-[11px] text-gray-600 flex items-center justify-center gap-4">
            <a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition-colors">← মূল ওয়েবসাইটে ফিরে যান</a>
            <span>•</span>
            <span>Sorolata Monolith v2.4.0</span>
        </div>

    </div>

</body>
</html>
