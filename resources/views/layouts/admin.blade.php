<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no" class="h-full antialiased bg-[#0f1115] notranslate">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Merchant Operations' }} — Sorolata Studio</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Hind+Siliguri:wght@400;600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .admin-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .admin-scroll::-webkit-scrollbar-track {
            background: #14171f;
        }
        .admin-scroll::-webkit-scrollbar-thumb {
            background: #282d37;
            border-radius: 999px;
        }
        .admin-scroll::-webkit-scrollbar-thumb:hover {
            background: #373e4d;
        }
        h1, h2, h3, h4, h5, h6, .font-editorial-heading {
            color: inherit !important;
        }
    </style>
</head>
<body 
    x-data="{ 
        commandOpen: false, 
        sidebarCollapsed: false, 
        searchQuery: '', 
        searchResults: null,
        notificationsOpen: false,
        search() {
            if (!this.searchQuery.trim()) {
                this.searchResults = null;
                return;
            }
            fetch('/admin/api/command-search?q=' + encodeURIComponent(this.searchQuery))
                .then(res => res.json())
                .then(data => { this.searchResults = data; });
        }
    }" 
    @keydown.window.ctrl.k.prevent="commandOpen = true"
    @keydown.window.meta.k.prevent="commandOpen = true"
    @keydown.escape.window="commandOpen = false; notificationsOpen = false"
    class="h-full text-[#E0E2EC] font-sans antialiased overflow-hidden flex flex-col select-none"
>

    <!-- 1. TOP GLOBAL NAVIGATION -->
    <header class="h-16 bg-[#161922] border-b border-[#252a36] px-6 flex items-center justify-between shrink-0 z-30">
        
        <!-- Left: Wordmark & Sidebar Toggle -->
        <div class="flex items-center space-x-4">
            <button type="button" 
                    @click="sidebarCollapsed = !sidebarCollapsed" 
                    class="p-2 text-gray-400 hover:text-white hover:bg-[#222733] rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                <span class="font-editorial-heading text-2xl text-white font-normal tracking-tight">Sorolata</span>
                <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 rounded-full bg-[#214E34]/30 text-[#A3D9B1] border border-[#214E34]/50 font-semibold">
                    Studio
                </span>
            </a>
        </div>

        <!-- Center: Global Command Palette Trigger (Stripe / Linear / Notion style) -->
        <div class="flex-1 max-w-xl mx-8 hidden sm:block">
            <button type="button" 
                    @click="commandOpen = true" 
                    class="w-full bg-[#0f1115] hover:bg-[#12141a] border border-[#282d37] hover:border-[#384050] text-gray-400 hover:text-gray-200 text-xs px-3.5 py-2 rounded-xl flex items-center justify-between transition-all shadow-inner">
                <div class="flex items-center space-x-2.5">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Jump to orders, products, artisans, settings...</span>
                </div>
                <div class="flex items-center space-x-1 font-mono text-[10px] bg-[#1a1e28] px-2 py-0.5 rounded border border-[#2d3342] text-gray-400">
                    <span>⌘</span>
                    <span>K</span>
                </div>
            </button>
        </div>

        <!-- Right: Actions, Notifications & Profile -->
        <div class="flex items-center space-x-3.5">
            
            <!-- Visit Storefront Link -->
            <a href="{{ url('/') }}" target="_blank" 
               class="hidden md:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-[#282d37] hover:border-gray-500 bg-[#1c202b] hover:bg-[#232836] text-xs font-medium text-gray-300 transition-colors">
                <span>Storefront</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            <!-- Notification Bell -->
            @php
                $notifService = app(\App\Services\Admin\AdminNotificationService::class);
                $unreadNotifsCount = $notifService->getUnreadCount();
                $headerNotifs = $notifService->getLatest(5);
            @endphp
            <div class="relative" x-data="{ 
                unreadCount: {{ $unreadNotifsCount }},
                fetchUnread() {
                    fetch('{{ route('admin.notifications.unread-count') }}')
                        .then(r => r.json())
                        .then(d => { if(d && typeof d.count !== 'undefined') this.unreadCount = d.count; })
                        .catch(() => {});
                }
            }" x-init="setInterval(() => fetchUnread(), 30000)">
                <button type="button" 
                        @click="notificationsOpen = !notificationsOpen" 
                        class="p-2 text-gray-400 hover:text-white hover:bg-[#222733] rounded-lg transition-colors relative"
                        title="Alerts & Notifications">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span x-show="unreadCount > 0" 
                          x-text="unreadCount > 99 ? '99+' : unreadCount" 
                          class="absolute -top-1 -right-1 px-1.5 py-0.2 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold rounded-full bg-rose-500 text-white ring-2 ring-[#161922]">
                        {{ $unreadNotifsCount > 99 ? '99+' : $unreadNotifsCount }}
                    </span>
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="notificationsOpen" 
                     @click.away="notificationsOpen = false" 
                     x-transition 
                     class="absolute right-0 mt-2 w-96 bg-[#1c202b] border border-[#2d3342] rounded-2xl shadow-2xl p-4 z-50">
                    <div class="flex items-center justify-between pb-3 border-b border-[#2d3342]">
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-white">Central Dispatch</h4>
                            @if($unreadNotifsCount > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-semibold">{{ $unreadNotifsCount }} unread</span>
                            @else
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold">All Caught Up</span>
                            @endif
                        </div>
                        @if($unreadNotifsCount > 0)
                            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[11px] text-[#A3D9B1] hover:underline font-medium">Mark all read</button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="py-2 divide-y divide-[#252a36] max-h-80 overflow-y-auto admin-scroll">
                        @forelse($headerNotifs as $notif)
                            <div class="py-2.5 first:pt-1 last:pb-1 flex items-start gap-3 hover:bg-[#222733]/50 p-2 rounded-xl transition-colors {{ !$notif->is_read ? 'bg-[#214E34]/10' : '' }}">
                                @php
                                    $color = $notif->getTypeColor();
                                    $dotClass = match($color) {
                                        'emerald' => 'bg-emerald-400',
                                        'blue' => 'bg-blue-400',
                                        'rose' => 'bg-rose-400',
                                        'amber' => 'bg-amber-400',
                                        'purple' => 'bg-purple-400',
                                        default => 'bg-gray-400'
                                    };
                                    $badgeClass = match($color) {
                                        'emerald' => 'bg-emerald-500/20 text-emerald-400',
                                        'blue' => 'bg-blue-500/20 text-blue-400',
                                        'rose' => 'bg-rose-500/20 text-rose-400',
                                        'amber' => 'bg-amber-500/20 text-amber-400',
                                        'purple' => 'bg-purple-500/20 text-purple-400',
                                        default => 'bg-gray-500/20 text-gray-400'
                                    };
                                @endphp
                                <span class="w-2 h-2 rounded-full {{ $dotClass }} mt-1.5 shrink-0"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <span class="text-[9px] uppercase tracking-wider font-bold px-1.5 py-0.2 rounded {{ $badgeClass }}">
                                            {{ $notif->getTypeLabel() }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 font-mono">{{ $notif->created_at?->diffForHumans() }}</span>
                                    </div>
                                    <form action="{{ route('admin.notifications.read', $notif->id) }}?redirect=1" method="POST">
                                        @csrf
                                        <button type="submit" class="text-left group w-full">
                                            <p class="text-xs font-semibold text-white group-hover:text-[#A3D9B1] truncate transition-colors">{{ $notif->title }}</p>
                                            <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ $notif->message }}</p>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-500 text-xs">
                                <svg class="w-8 h-8 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                No notifications yet.
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('admin.notifications.index') }}" class="block text-center text-xs font-semibold text-[#A3D9B1] hover:underline pt-2.5 border-t border-[#2d3342]">
                        Open Notification Center →
                    </a>
                </div>
            </div>

            <!-- Dynamic Profile & Logout Menu -->
            @php
                $currentUser = auth()->user();
                $initials = $currentUser ? strtoupper(substr($currentUser->name, 0, 2)) : 'AD';
                $roleLabel = $currentUser?->role?->label() ?? 'Master Curator';
            @endphp
            <div class="relative pl-2 border-l border-[#282d37]" x-data="{ profileMenuOpen: false }">
                <button type="button" 
                        @click="profileMenuOpen = !profileMenuOpen" 
                        class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-[#222733] transition-colors focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-[#214E34] text-[#A3D9B1] flex items-center justify-center text-xs font-bold ring-2 ring-[#282d37]">
                        {{ $initials }}
                    </div>
                    <div class="hidden lg:block text-left">
                        <div class="text-xs font-semibold text-white leading-tight">{{ $currentUser->name ?? 'Administrator' }}</div>
                        <div class="text-[10px] text-gray-400 leading-tight">{{ $roleLabel }}</div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="profileMenuOpen" 
                     @click.away="profileMenuOpen = false" 
                     x-transition 
                     class="absolute right-0 mt-2 w-56 bg-[#1c202b] border border-[#2d3342] rounded-2xl shadow-2xl p-2 z-50 divide-y divide-[#2d3342]">
                    <div class="px-3 py-2">
                        <p class="text-xs font-bold text-white truncate">{{ $currentUser->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ $currentUser->email ?? 'admin@sorolata.com' }}</p>
                        <span class="inline-block mt-1 text-[9px] uppercase tracking-wider font-semibold px-2 py-0.5 rounded-full bg-emerald-950 text-emerald-300 border border-emerald-800">
                            {{ $roleLabel }}
                        </span>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('admin.profile') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-xs text-gray-300 hover:text-white hover:bg-[#252b3a] transition-colors">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile & Credentials</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-xs text-gray-300 hover:text-white hover:bg-[#252b3a] transition-colors">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Storefront Settings</span>
                        </a>
                    </div>
                    <div class="py-1">
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 rounded-lg text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-950/40 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sign Out (লগআউট)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- 2. BODY WORKSPACE (COLLAPSIBLE SIDEBAR + MAIN CONTENT) -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" 
               class="bg-[#161922] border-r border-[#252a36] flex flex-col shrink-0 transition-all duration-200 z-20">
            
            <div class="flex-1 overflow-y-auto admin-scroll py-4 px-3 space-y-6">
                
                <!-- Category: CORE COMMERCE -->
                <div>
                    <h5 x-show="!sidebarCollapsed" class="text-[10px] font-bold tracking-widest uppercase text-gray-500 px-3 mb-2">
                        Commerce Core
                    </h5>
                    <nav class="space-y-1">
                        
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}"
                           title="Dashboard Overview">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                        </a>

                        <!-- Orders -->
                        <a href="{{ route('admin.orders.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}"
                           title="Orders & Deliveries">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Orders</span>
                            </div>
                            <span x-show="!sidebarCollapsed" class="text-[10px] px-1.5 py-0.5 rounded-full bg-[#2a303f] text-gray-300 font-mono">Live</span>
                        </a>

                        <!-- Products -->
                        <a href="{{ route('admin.products.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}"
                           title="Artifacts Catalog">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Products</span>
                        </a>

                        <!-- Collections -->
                        <a href="{{ route('admin.collections.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.collections.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}"
                           title="Curated Collections">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Collections</span>
                        </a>
                    </nav>
                </div>

                <!-- Category: HERITAGE GUILDS -->
                <div>
                    <h5 x-show="!sidebarCollapsed" class="text-[10px] font-bold tracking-widest uppercase text-gray-500 px-3 mb-2">
                        Heritage Provenance
                    </h5>
                    <nav class="space-y-1">
                        
                        <!-- Artisans -->
                        <a href="{{ route('admin.artisans.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.artisans.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Master Artisans</span>
                        </a>

                        <!-- Districts -->
                        <a href="{{ route('admin.districts.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.districts.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Districts & Basins</span>
                        </a>

                        <!-- Customers -->
                        <a href="{{ route('admin.customers.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Collectors</span>
                        </a>

                        <!-- Reviews -->
                        <a href="{{ route('admin.reviews.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Reviews</span>
                        </a>
                    </nav>
                </div>

                <!-- Category: CMS & MARKETING -->
                <div>
                    <h5 x-show="!sidebarCollapsed" class="text-[10px] font-bold tracking-widest uppercase text-gray-500 px-3 mb-2">
                        Studio CMS
                    </h5>
                    <nav class="space-y-1">
                        
                        <!-- Visual Pages & Page Builder Studio -->
                        <a href="{{ route('admin.pages.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.pages.*') || request()->routeIs('builder.page*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Pages & Builder</span>
                        </a>

                        <!-- Heritage Stories (Shoppable Content CMS) -->
                        <a href="{{ route('admin.stories.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.stories.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Heritage Stories</span>
                        </a>

                        <!-- Media Library -->
                        <a href="{{ route('admin.media.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.media.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Media Library</span>
                        </a>

                        <!-- Discounts & Coupons -->
                        <a href="{{ route('admin.discounts.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.discounts.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Coupons</span>
                        </a>
                    </nav>
                </div>

                <!-- Category: SYSTEM OPERATIONS -->
                <div>
                    <h5 x-show="!sidebarCollapsed" class="text-[10px] font-bold tracking-widest uppercase text-gray-500 px-3 mb-2">
                        Operations
                    </h5>
                    <nav class="space-y-1">
                        
                        <!-- Notifications -->
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}"
                           title="Notification Dispatch">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Notifications</span>
                            </div>
                            @if(isset($unreadNotifsCount) && $unreadNotifsCount > 0)
                                <span x-show="!sidebarCollapsed" class="text-[10px] px-1.5 py-0.2 rounded-full bg-rose-500/20 text-rose-400 font-mono font-bold">{{ $unreadNotifsCount }}</span>
                            @endif
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Store Settings</span>
                        </a>

                        <!-- Audit Logs -->
                        <a href="{{ route('admin.logs.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-medium transition-all {{ request()->routeIs('admin.logs.*') ? 'bg-[#214E34] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222733] hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Audit Trail</span>
                        </a>
                    </nav>
                </div>

            </div>

            <!-- Footer indicator -->
            <div class="p-3 border-t border-[#252a36] bg-[#12141a] text-[10px] text-gray-500 text-center">
                <span x-show="!sidebarCollapsed">Sorolata Engine 2.4.0 • Enterprise Monolith</span>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 overflow-y-auto admin-scroll bg-[#0f1115] p-6 lg:p-8">
            
            <!-- Flash Message Banner -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-xs flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-200 text-xs flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <!-- 3. NOTION / LINEAR STYLE COMMAND PALETTE MODAL (CTRL+K) -->
    <div x-show="commandOpen" 
         x-transition.opacity 
         class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-start justify-center pt-20 px-4"
         style="display: none;">
        
        <div @click.away="commandOpen = false" 
             class="w-full max-w-2xl bg-[#1a1e28] border border-[#2e3545] rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            
            <!-- Command Input Bar -->
            <div class="p-4 border-b border-[#2e3545] flex items-center space-x-3 bg-[#151821]">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       x-model="searchQuery" 
                       @input.debounce.200ms="search()"
                       placeholder="Type a command or search orders, products, artisans..." 
                       class="w-full bg-transparent text-sm text-white placeholder-gray-500 focus:outline-none">
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-[#222735] text-gray-400 border border-[#2d3444]">ESC</span>
            </div>

            <!-- Command Results List -->
            <div class="max-h-96 overflow-y-auto admin-scroll p-3 space-y-4">
                
                <!-- Quick Navigation Shortcuts (Default State) -->
                <div x-show="!searchResults">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 px-2 block mb-2">
                        Quick Shortcuts
                    </span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.products.create') }}" class="flex items-center space-x-3 p-2.5 rounded-xl hover:bg-[#222736] text-xs text-gray-200 transition-colors">
                            <span class="p-1.5 rounded-lg bg-[#214E34]/20 text-[#A3D9B1]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </span>
                            <div class="flex-1">
                                <div class="font-semibold text-white">Create New Heritage Artifact</div>
                                <div class="text-[10px] text-gray-400">Draft a new catalog piece</div>
                            </div>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 p-2.5 rounded-xl hover:bg-[#222736] text-xs text-gray-200 transition-colors">
                            <span class="p-1.5 rounded-lg bg-indigo-500/20 text-indigo-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </span>
                            <div class="flex-1">
                                <div class="font-semibold text-white">Open Logistics & Orders</div>
                                <div class="text-[10px] text-gray-400">View live fulfillment timeline</div>
                            </div>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 p-2.5 rounded-xl hover:bg-[#222736] text-xs text-gray-200 transition-colors">
                            <span class="p-1.5 rounded-lg bg-amber-500/20 text-amber-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                            </span>
                            <div class="flex-1">
                                <div class="font-semibold text-white">Storefront Configuration</div>
                                <div class="text-[10px] text-gray-400">Gateways, shipping zones and tax rates</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Live Query Results -->
                <template x-if="searchResults">
                    <div class="space-y-4">
                        <!-- Products Results -->
                        <template x-if="searchResults.products && searchResults.products.length > 0">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 px-2 block mb-1">Products</span>
                                <div class="space-y-1">
                                    <template x-for="p in searchResults.products" :key="p.url">
                                        <a :href="p.url" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-[#222736] text-xs text-gray-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            <div>
                                                <div class="font-semibold text-white" x-text="p.title"></div>
                                                <div class="text-[10px] text-gray-400" x-text="p.subtitle"></div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Orders Results -->
                        <template x-if="searchResults.orders && searchResults.orders.length > 0">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 px-2 block mb-1">Orders</span>
                                <div class="space-y-1">
                                    <template x-for="o in searchResults.orders" :key="o.url">
                                        <a :href="o.url" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-[#222736] text-xs text-gray-200">
                                            <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                            <div>
                                                <div class="font-semibold text-white" x-text="o.title"></div>
                                                <div class="text-[10px] text-gray-400" x-text="o.subtitle"></div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

            </div>

            <!-- Modal Footer -->
            <div class="p-3 border-t border-[#2e3545] bg-[#151821] flex items-center justify-between text-[11px] text-gray-500">
                <div class="flex items-center space-x-3">
                    <span>Press <kbd class="px-1.5 py-0.5 rounded bg-[#222735] text-gray-300">↵</kbd> to select</span>
                    <span><kbd class="px-1.5 py-0.5 rounded bg-[#222735] text-gray-300">ESC</kbd> to close</span>
                </div>
                <span>Sorolata Navigation Engine</span>
            </div>

        </div>

    </div>

    @livewireScripts
</body>
</html>
