@extends('layouts.admin')

@section('content')
<div class="space-y-6 sm:space-y-7 max-w-7xl mx-auto pb-12" x-data="{
    timeRange: 'Last 30 Days',
    chartRange: '30D',
    basinFilter: 'Top Craft Basins',
    trajectories: @js($metrics['trajectories']),
    get currentTrajectory() {
        return this.trajectories[this.chartRange] || this.trajectories['30D'];
    }
}">
    
    <!-- Top Greeting & Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <!-- Green Glowing Leaf Emblem -->
                <span class="w-7 h-7 rounded-lg bg-[#214E34]/30 border border-[#214E34]/50 flex items-center justify-center text-emerald-400 shadow-[0_0_12px_rgba(33,78,52,0.3)]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 20A7 7 0 0 1 4 13C4 8.5 7.5 5 12 3a9 9 0 0 1 8 13.5A7.5 7.5 0 0 1 11 20z" />
                        <path d="M12 3v17" />
                    </svg>
                </span>
                <h1 class="font-editorial-heading text-2xl sm:text-3xl text-white font-normal tracking-tight">
                    Executive Dashboard
                </h1>
            </div>
            <p class="text-xs text-gray-400 mt-1">Real-time pulse of Bengali artisan guild commerce and collector orders.</p>
        </div>

        <!-- Header Actions: Quick Actions + Date Range Filter -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Action 1: Add Artifact -->
            <a 
                href="{{ route('admin.products.create') }}" 
                class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#22C55E] to-[#16A34A] hover:from-[#16A34A] hover:to-[#15803D] text-black font-bold text-xs shadow-md shadow-emerald-950/40 flex items-center gap-1.5 transition-all"
            >
                <span class="text-sm font-bold leading-none">+</span>
                <span>Add Artifact</span>
            </a>

            <!-- Action 2: Collections -->
            <a 
                href="{{ route('admin.collections.index') }}" 
                class="px-3 py-2 rounded-xl bg-[#141720] hover:bg-[#1e2330] text-gray-200 hover:text-white border border-[#252a36] hover:border-gray-500 text-xs font-semibold flex items-center gap-1.5 transition-all"
            >
                <x-icon name="layers" class="w-3.5 h-3.5 text-gray-400" />
                <span>Collections</span>
            </a>

            <!-- Action 3: Orders -->
            <a 
                href="{{ route('admin.orders.index') }}" 
                class="px-3 py-2 rounded-xl bg-[#141720] hover:bg-[#1e2330] text-gray-200 hover:text-white border border-[#252a36] hover:border-gray-500 text-xs font-semibold flex items-center gap-1.5 transition-all"
            >
                <x-icon name="shopping-bag" class="w-3.5 h-3.5 text-gray-400" />
                <span>Orders</span>
            </a>

            <!-- Date Range Filter Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    type="button" 
                    @click="open = !open" 
                    class="px-3.5 py-2 rounded-xl bg-[#141720] border border-[#252a36] hover:border-gray-500 text-xs font-medium text-gray-300 hover:text-white transition-all flex items-center gap-2 shadow-sm"
                >
                    <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span x-text="timeRange">Last 30 Days</span>
                    <x-icon name="chevron-down" class="w-3 h-3 text-gray-400 ml-1" />
                </button>

                <div 
                    x-show="open" 
                    @click.outside="open = false" 
                    x-transition 
                    class="absolute right-0 mt-2 w-44 rounded-xl bg-[#161922] border border-[#2b3345] shadow-xl p-1 z-30 text-xs text-gray-300 space-y-0.5" 
                    style="display: none;"
                >
                    <button type="button" @click="timeRange = 'Today'; open = false" class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-[#202534] hover:text-white">Today</button>
                    <button type="button" @click="timeRange = 'Last 7 Days'; open = false" class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-[#202534] hover:text-white">Last 7 Days</button>
                    <button type="button" @click="timeRange = 'Last 30 Days'; open = false" class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-[#202534] hover:text-white">Last 30 Days</button>
                    <button type="button" @click="timeRange = 'Last 90 Days'; open = false" class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-[#202534] hover:text-white">Last 90 Days</button>
                    <button type="button" @click="timeRange = 'This Year'; open = false" class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-[#202534] hover:text-white">This Year</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- ROW 1: 4 High-Impact Compact KPI Metric Cards                     -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        
        <!-- 1. Today's Sales (Active Emerald Glowing Card) -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-gradient-to-b from-[#18281e] to-[#12151d] border border-[#214E34] shadow-[0_0_16px_rgba(33,78,52,0.2)] flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1 mb-1">
                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400">
                    <x-icon name="coins" class="w-3.5 h-3.5" />
                    <span>Today's Sales</span>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-white tracking-tight font-mono">
                ৳{{ number_format($metrics['today_sales'], 0) }}
            </div>
            <div class="text-[10px] text-emerald-400 mt-1 font-medium flex items-center gap-1">
                <span>▲ {{ $metrics['today_sales_trend'] }}</span>
                <span class="text-gray-500 text-[9px]">vs yesterday</span>
            </div>
        </div>

        <!-- 2. Total Revenue -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-[#141720] border border-[#252a36] hover:border-[#384052] transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1 mb-1">
                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    <x-icon name="chart-bar" class="w-3.5 h-3.5 text-cyan-400" />
                    <span>Total Revenue</span>
                </div>
                <span class="text-[9px] font-mono text-gray-500">30D</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-white tracking-tight font-mono">
                ৳{{ number_format($metrics['total_revenue'], 0) }}
            </div>
            <div class="text-[10px] text-emerald-400 mt-1 font-medium flex items-center gap-1">
                <span>▲ {{ $metrics['total_revenue_trend'] }}</span>
                <span class="text-gray-500 text-[9px]">vs last 30 days</span>
            </div>
        </div>

        <!-- 3. Orders Count -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-[#141720] border border-[#252a36] hover:border-[#384052] transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1 mb-1">
                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    <x-icon name="shopping-bag" class="w-3.5 h-3.5 text-indigo-400" />
                    <span>Orders Count</span>
                </div>
                <span class="text-[9px] font-mono text-gray-500">Live</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-white tracking-tight font-mono">
                {{ $metrics['total_orders'] }}
            </div>
            <div class="text-[10px] text-emerald-400 mt-1 font-medium flex items-center gap-1">
                <span>▲ {{ $metrics['total_orders_trend'] }}</span>
                <span class="text-gray-500 text-[9px]">vs last 7 days</span>
            </div>
        </div>

        <!-- 4. Average Order Value -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-[#141720] border border-[#252a36] hover:border-[#384052] transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1 mb-1">
                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    <x-icon name="layers" class="w-3.5 h-3.5 text-blue-400" />
                    <span>Average Order Value</span>
                </div>
                <span class="text-[9px] font-mono text-gray-500">AOV</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-white tracking-tight font-mono">
                ৳{{ number_format($metrics['aov'], 0) }}
            </div>
            <div class="text-[10px] text-emerald-400 mt-1 font-medium flex items-center gap-1">
                <span>▲ {{ $metrics['aov_trend'] }}</span>
                <span class="text-gray-500 text-[9px]">vs last 30 days</span>
            </div>
        </div>

    </div>

    <!-- ================================================================= -->
    <!-- ROW 2: Multi-Range Revenue Trajectory + Dynamic Collector Mix     -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
        
        <!-- Col 1: Dynamic Revenue Trajectory (7 cols) -->
        <div class="lg:col-span-7 p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-3">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <x-icon name="trending-up" class="w-4 h-4 text-emerald-400" />
                        <span x-text="chartRange + ' Revenue Trajectory'">30-Day Revenue Trajectory</span>
                    </h3>
                    <p class="text-[11px] text-gray-400">Daily gross acquisitions from domestic and global collectors.</p>
                </div>
                <!-- 7D / 30D / 90D Interactive Toggle -->
                <div class="flex items-center bg-[#0d0f14] p-1 rounded-xl border border-[#252a36] text-[10px] font-semibold text-gray-400 shrink-0">
                    <button type="button" @click="chartRange = '7D'" :class="chartRange === '7D' ? 'bg-[#22C55E] text-black font-bold shadow' : 'hover:text-gray-200'" class="px-2.5 py-1 rounded-lg transition-all">7D</button>
                    <button type="button" @click="chartRange = '30D'" :class="chartRange === '30D' ? 'bg-[#22C55E] text-black font-bold shadow' : 'hover:text-gray-200'" class="px-2.5 py-1 rounded-lg transition-all">30D</button>
                    <button type="button" @click="chartRange = '90D'" :class="chartRange === '90D' ? 'bg-[#22C55E] text-black font-bold shadow' : 'hover:text-gray-200'" class="px-2.5 py-1 rounded-lg transition-all">90D</button>
                </div>
            </div>

            <!-- Stylized Responsive Curved Area Graph -->
            <div class="relative w-full h-52 pt-2">
                <!-- Y-axis markers -->
                <div class="absolute left-0 top-0 bottom-6 w-12 flex flex-col justify-between text-[9px] font-mono text-gray-500 pointer-events-none">
                    <template x-for="yt in currentTrajectory.y_ticks" :key="yt">
                        <span x-text="yt"></span>
                    </template>
                </div>

                <!-- SVG Plot -->
                <div class="ml-12 h-44 relative">
                    <!-- Background Grid Lines -->
                    <div class="absolute inset-0 flex flex-col justify-between border-b border-[#252a36] pointer-events-none">
                        <div class="w-full border-t border-[#252a36]/40"></div>
                        <div class="w-full border-t border-[#252a36]/40"></div>
                        <div class="w-full border-t border-[#252a36]/40"></div>
                        <div class="w-full border-t border-[#252a36]/40"></div>
                        <div class="w-full border-t border-[#252a36]/40"></div>
                    </div>

                    <!-- Responsive SVG Area and Line -->
                    <svg viewBox="0 0 1000 280" preserveAspectRatio="none" class="w-full h-full overflow-visible">
                        <defs>
                            <linearGradient id="revenueTrajectoryGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#22C55E" stop-opacity="0.35"/>
                                <stop offset="50%" stop-color="#10B981" stop-opacity="0.12"/>
                                <stop offset="100%" stop-color="#22C55E" stop-opacity="0.0"/>
                            </linearGradient>
                            <filter id="neonGlow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="4" result="blur" />
                                <feComposite in="SourceGraphic" in2="blur" operator="over"/>
                            </filter>
                        </defs>

                        <!-- Gradient Fill (Reactive) -->
                        <path :d="currentTrajectory.area_path" fill="url(#revenueTrajectoryGrad)" />

                        <!-- Glowing Line (Reactive) -->
                        <path :d="currentTrajectory.line_path" fill="none" stroke="#22C55E" stroke-width="2.5" filter="url(#neonGlow)" />

                        <!-- Peak Line & Marker -->
                        <line :x1="currentTrajectory.peak.x" :y1="currentTrajectory.peak.y" :x2="currentTrajectory.peak.x" y2="260" stroke="#22C55E" stroke-width="1.5" stroke-dasharray="3,3" opacity="0.6"/>
                        <circle :cx="currentTrajectory.peak.x" :cy="currentTrajectory.peak.y" r="5" fill="#22C55E" stroke="#0f1115" stroke-width="2.5" />
                    </svg>

                    <!-- Floating Tooltip Card on Peak -->
                    <div 
                        class="absolute -translate-x-1/2 -translate-y-full px-2 py-0.5 rounded-lg bg-[#0d0f14] border border-[#22C55E]/40 text-center shadow-lg pointer-events-none transition-all duration-300"
                        :style="'left: ' + currentTrajectory.peak.percent_x + '%; top: ' + (currentTrajectory.peak.y > 90 ? currentTrajectory.peak.y - 12 : currentTrajectory.peak.y + 40) + 'px;'"
                    >
                        <div class="text-[8px] text-gray-400 font-mono leading-none" x-text="currentTrajectory.peak.date"></div>
                        <div class="text-xs font-bold text-white font-mono leading-tight mt-0.5" x-text="currentTrajectory.peak.amount"></div>
                    </div>
                </div>

                <!-- Dynamic X-Axis Labels -->
                <div class="ml-12 flex justify-between text-[9px] font-mono text-gray-500 pt-2 border-t border-[#252a36]">
                    <template x-for="(p, idx) in currentTrajectory.points" :key="idx">
                        <span :class="p.day === currentTrajectory.peak.day ? 'text-emerald-400 font-semibold' : ''" x-text="p.day"></span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Col 2: Collector Mix (5 cols - Fully Contained & Dynamic) -->
        <div class="lg:col-span-5 p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-bold text-white">Collector Mix</h3>
                    <p class="text-[11px] text-gray-400">Orders by collector type.</p>
                </div>
                <span class="text-[10px] text-emerald-400 font-medium px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20" x-text="chartRange">30D</span>
            </div>

            <!-- Donut Chart & Legend -->
            <div class="flex items-center justify-around gap-4 py-3 my-auto">
                <!-- SVG Donut (Dynamic Segments) -->
                <div class="relative w-24 h-24 sm:w-28 sm:h-28 shrink-0">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#1d2230" stroke-width="12" />
                        <!-- New Segment -->
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="{{ $metrics['collector_mix']['segments']['new']['color'] }}" stroke-width="12" stroke-dasharray="{{ $metrics['collector_mix']['segments']['new']['dash'] }}" stroke-dashoffset="{{ $metrics['collector_mix']['segments']['new']['offset'] }}" />
                        <!-- Returning Segment -->
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="{{ $metrics['collector_mix']['segments']['returning']['color'] }}" stroke-width="12" stroke-dasharray="{{ $metrics['collector_mix']['segments']['returning']['dash'] }}" stroke-dashoffset="{{ $metrics['collector_mix']['segments']['returning']['offset'] }}" />
                        <!-- International Segment -->
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="{{ $metrics['collector_mix']['segments']['international']['color'] }}" stroke-width="12" stroke-dasharray="{{ $metrics['collector_mix']['segments']['international']['dash'] }}" stroke-dashoffset="{{ $metrics['collector_mix']['segments']['international']['offset'] }}" />
                    </svg>
                    <!-- Center Metric -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-base font-bold text-white font-mono leading-none">{{ number_format($metrics['collector_mix']['total']) }}</span>
                        <span class="text-[9px] text-gray-400 leading-tight mt-0.5">Collectors</span>
                    </div>
                </div>

                <!-- Legend -->
                <div class="space-y-2 text-xs flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-1.5 text-gray-300 truncate">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#84CC16] shrink-0"></span>
                            <span class="truncate">Returning</span>
                        </span>
                        <span class="font-mono text-gray-300 font-bold shrink-0">{{ $metrics['collector_mix']['returning_percent'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-1.5 text-gray-300 truncate">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#22C55E] shrink-0"></span>
                            <span class="truncate">New</span>
                        </span>
                        <span class="font-mono text-gray-300 font-bold shrink-0">{{ $metrics['collector_mix']['new_percent'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-1.5 text-gray-300 truncate">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6] shrink-0"></span>
                            <span class="truncate">International</span>
                        </span>
                        <span class="font-mono text-gray-300 font-bold shrink-0">{{ $metrics['collector_mix']['international_percent'] }}%</span>
                    </div>
                </div>
            </div>
            
            <div class="text-[10px] text-gray-500 pt-2.5 border-t border-[#252a36] flex items-center justify-between">
                <span>Domestic & Global Reach</span>
                <a href="{{ route('admin.orders.index') }}" class="text-emerald-400 hover:underline flex items-center gap-1">
                    <span>Manage Orders</span>
                    <x-icon name="chevron-right" class="w-3 h-3" />
                </a>
            </div>
        </div>

    </div>

    <!-- ================================================================= -->
    <!-- ROW 3: Deep Analytics Breakdown (4 Responsive Columns)             -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 sm:gap-6">
        
        <!-- 1. District-wise Performance (Vertical Bars) -->
        <div class="p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-white">District-wise Performance</h3>
                <span class="text-[10px] text-gray-400 font-mono">Top Craft Basins ▾</span>
            </div>

            <div class="h-44 flex items-end justify-around gap-2 pt-3 pb-2 border-b border-[#252a36]">
                @foreach($metrics['top_districts'] as $dist)
                    <div class="flex-1 flex flex-col items-center justify-end h-full group min-w-0">
                        <span class="text-[10px] font-mono font-medium text-gray-300 group-hover:text-emerald-400 transition-colors whitespace-nowrap mb-1.5">
                            {{ $dist['label'] }}
                        </span>
                        <div class="w-full flex items-end justify-center h-24 mb-1.5">
                            <div 
                                class="w-6 sm:w-7 bg-gradient-to-t from-[#059669] to-[#34D399] rounded-t-lg transition-all duration-300 shadow-[0_0_14px_rgba(52,211,153,0.35)] group-hover:brightness-125 shrink-0"
                                style="height: {{ max(14, $dist['height_percent']) }}%;"
                            ></div>
                        </div>
                        <span class="text-[10px] text-gray-400 truncate w-full text-center group-hover:text-white transition-colors" title="{{ $dist['name'] }}">
                            {{ $dist['name'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between text-[10px] text-gray-400 pt-3">
                <a href="{{ route('admin.districts.index') }}" class="text-emerald-400 hover:underline">All 64 Basins →</a>
                <span>Based on revenue</span>
            </div>
        </div>

        <!-- 2. Conversion Funnel -->
        <div class="p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-white">Conversion Funnel</h3>
                <p class="text-[11px] text-gray-400">Collector journey (last 30 days)</p>
            </div>

            <div class="space-y-1.5 my-auto pt-2">
                @foreach($metrics['conversion_funnel'] as $idx => $step)
                    @php
                        $isLast = $loop->last;
                    @endphp
                    <div class="flex items-center justify-between p-1.5 rounded-lg {{ $isLast ? 'bg-[#84CC16] text-black font-bold shadow-[0_0_12px_rgba(132,204,22,0.3)]' : 'bg-[#181c26] text-gray-300' }} text-xs transition-all">
                        <span class="font-medium text-[10px] sm:text-[11px] truncate">{{ $step['stage'] }}</span>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-mono text-[10px] sm:text-[11px] {{ $isLast ? 'text-black' : 'text-white' }} font-bold">{{ number_format($step['count']) }}</span>
                            <span class="font-mono text-[9px] sm:text-[10px] {{ $isLast ? 'text-black/80' : 'text-gray-400' }} w-9 text-right">{{ $step['percent'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-[10px] text-gray-500 pt-3 border-t border-[#252a36] flex items-center justify-between">
                <span>Reach: {{ number_format($metrics['conversion_funnel'][0]['count']) }}</span>
                <span class="text-emerald-400 font-semibold">{{ $metrics['conversion_rate'] }}% Overall CVR</span>
            </div>
        </div>

        <!-- 3. Order Status Distribution (Donut Chart - Dynamic Segments) -->
        <div class="p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-white">Order Status Distribution</h3>
                <p class="text-[11px] text-gray-400">Live order state composition.</p>
            </div>

            <div class="flex items-center justify-between gap-3 py-2 my-auto">
                <!-- SVG Donut (Dynamic from Order Distribution Segments) -->
                <div class="relative w-22 h-22 sm:w-24 sm:h-24 shrink-0">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#1d2230" stroke-width="12" />
                        @foreach($metrics['order_distribution']['segments'] as $seg)
                            <circle cx="50" cy="50" r="38" fill="transparent" stroke="{{ $seg['color'] }}" stroke-width="12" stroke-dasharray="{{ $seg['dash'] }}" stroke-dashoffset="{{ $seg['offset'] }}" />
                        @endforeach
                    </svg>
                    <!-- Center Value -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-base font-bold text-white font-mono leading-none">{{ $metrics['total_orders'] }}</span>
                        <span class="text-[9px] text-gray-400 leading-tight mt-0.5">Orders</span>
                    </div>
                </div>

                <!-- Legend (Dynamic) -->
                <div class="space-y-1.5 text-[11px] min-w-0 flex-1">
                    @foreach($metrics['order_distribution']['segments'] as $seg)
                        <div class="flex items-center justify-between gap-1">
                            <span class="flex items-center gap-1.5 text-gray-300 truncate">
                                <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $seg['color'] }}"></span>
                                <span class="truncate text-[10px]">{{ $seg['label'] }}</span>
                            </span>
                            <span class="font-mono text-gray-300 shrink-0 text-[10px] font-semibold">{{ $seg['percent'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-[10px] text-gray-500 pt-3 border-t border-[#252a36]">
                Fulfillment queue distribution
            </div>
        </div>

        <!-- 4. Recent Collector Activity (Live Feed) -->
        <div class="p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-white">Recent Collector Activity</h3>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold border border-emerald-500/30 flex items-center gap-1 animate-pulse">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Live</span>
                </span>
            </div>

            <div class="space-y-3 my-auto py-1">
                @foreach($metrics['recent_activities'] as $act)
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-lg {{ $act['color'] }} flex items-center justify-center shrink-0 mt-0.5">
                            <x-icon :name="$act['icon']" class="w-3.5 h-3.5" />
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-white leading-tight truncate">{{ $act['title'] }}</div>
                            <div class="text-[10px] text-gray-400 truncate">{{ $act['subtitle'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-[10px] text-gray-500 pt-3 border-t border-[#252a36] flex items-center justify-between">
                <span>Real-time websocket pulse</span>
                <a href="{{ route('admin.logs.index') }}" class="text-emerald-400 hover:underline">Full Log →</a>
            </div>
        </div>

    </div>

    <!-- ================================================================= -->
    <!-- ROW 4: Weekly Growth & Low Stock Summary                          -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
        
        <!-- 1. Weekly Growth Highlights (7 cols) -->
        <div class="lg:col-span-7 p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <x-icon name="trending-up" class="w-4 h-4 text-emerald-400" />
                    <span>Weekly Growth Highlights</span>
                </h3>
                <p class="text-[11px] text-gray-400">Key commerce metrics vs previous 7 days.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 my-auto py-2.5">
                <!-- Revenue Metric -->
                <div class="p-2.5 rounded-xl bg-[#181c26] border border-[#252a36]">
                    <div class="flex items-center gap-1 text-[10px] text-gray-400 mb-1">
                        <x-icon name="shopping-bag" class="w-3 h-3 text-emerald-400" />
                        <span>Revenue</span>
                    </div>
                    <div class="text-sm font-bold text-emerald-400 font-mono flex items-center justify-between">
                        <span>{{ $metrics['weekly_growth']['revenue'] }}</span>
                        <!-- Mini SVG Sparkline -->
                        <svg class="w-7 h-3.5" viewBox="0 0 32 16" fill="none">
                            <path d="M1 14 L8 9 L16 11 L24 4 L31 2" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- Orders Metric -->
                <div class="p-2.5 rounded-xl bg-[#181c26] border border-[#252a36]">
                    <div class="flex items-center gap-1 text-[10px] text-gray-400 mb-1">
                        <x-icon name="package" class="w-3 h-3 text-emerald-400" />
                        <span>Orders</span>
                    </div>
                    <div class="text-sm font-bold text-emerald-400 font-mono flex items-center justify-between">
                        <span>{{ $metrics['weekly_growth']['orders'] }}</span>
                        <svg class="w-7 h-3.5" viewBox="0 0 32 16" fill="none">
                            <path d="M1 12 L8 14 L16 8 L24 6 L31 2" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- Collectors Metric -->
                <div class="p-2.5 rounded-xl bg-[#181c26] border border-[#252a36]">
                    <div class="flex items-center gap-1 text-[10px] text-gray-400 mb-1">
                        <x-icon name="users" class="w-3 h-3 text-emerald-400" />
                        <span>Collectors</span>
                    </div>
                    <div class="text-sm font-bold text-emerald-400 font-mono flex items-center justify-between">
                        <span>{{ $metrics['weekly_growth']['collectors'] }}</span>
                        <svg class="w-7 h-3.5" viewBox="0 0 32 16" fill="none">
                            <path d="M1 15 L8 10 L16 8 L24 5 L31 2" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- Active Artisans Metric -->
                <div class="p-2.5 rounded-xl bg-[#181c26] border border-[#252a36]">
                    <div class="flex items-center gap-1 text-[10px] text-gray-400 mb-1">
                        <x-icon name="user" class="w-3 h-3 text-emerald-400" />
                        <span>Artisans</span>
                    </div>
                    <div class="text-sm font-bold text-emerald-400 font-mono flex items-center justify-between">
                        <span>{{ $metrics['weekly_growth']['artisans'] }}</span>
                        <svg class="w-7 h-3.5" viewBox="0 0 32 16" fill="none">
                            <path d="M1 10 L8 8 L16 9 L24 4 L31 2" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="text-[10px] text-gray-500 pt-2 border-t border-[#252a36]">
                Normalized against previous 7-day run rate
            </div>
        </div>

        <!-- 2. Low Stock Summary (5 cols) -->
        <div class="lg:col-span-5 p-4 sm:p-5 rounded-2xl bg-[#141720] border border-[#252a36] flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-white flex items-center gap-1.5">
                    <x-icon name="alert-triangle" class="w-4 h-4 text-amber-400" />
                    <span>Low Stock Summary</span>
                </h3>
                <a href="{{ route('admin.products.index') }}" class="text-[11px] text-emerald-400 hover:underline">View All →</a>
            </div>

            <div class="space-y-2.5 my-auto py-1">
                @forelse($metrics['low_stock_products'] as $prod)
                    <div class="p-2.5 rounded-xl bg-[#181c26] border border-[#252a36] flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <!-- Thumbnail / Fallback -->
                            <div class="w-8 h-8 rounded-lg bg-[#222736] border border-[#2f364a] overflow-hidden shrink-0 flex items-center justify-center">
                                @if($prod->primary_image_url)
                                    <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->title }}" class="w-full h-full object-cover">
                                @else
                                    <x-icon name="package" class="w-4 h-4 text-gray-400" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-white truncate">{{ $prod->title }}</div>
                                <div class="text-[10px] text-gray-400">Only {{ $prod->stock_quantity ?? 3 }} pieces left</div>
                            </div>
                        </div>
                        <!-- Amber Badge -->
                        <span class="w-6 h-6 rounded-lg bg-amber-500/20 border border-amber-500/40 text-amber-400 text-xs font-mono font-bold flex items-center justify-center shrink-0">
                            {{ $prod->stock_quantity ?? 3 }}
                        </span>
                    </div>
                @empty
                    <div class="py-4 text-center text-xs text-gray-500">All products adequately stocked.</div>
                @endforelse
            </div>

            <div class="text-[10px] text-gray-500 pt-2.5 border-t border-[#252a36]">
                Automated re-order threshold: 5 units
            </div>
        </div>

    </div>

    <!-- ================================================================= -->
    <!-- ROW 5: Recent Orders Awaiting Dispatch (Full-width Data Table)     -->
    <!-- ================================================================= -->
    <div class="p-4 sm:p-6 rounded-2xl bg-[#141720] border border-[#252a36]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-2.5 border-b border-[#252a36]">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <x-icon name="shopping-bag" class="w-4 h-4 text-emerald-400" />
                    <span>Recent Orders Awaiting Dispatch</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">White-glove courier and concierge fulfillment queue.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-emerald-400 hover:underline flex items-center gap-1">
                <span>View Full Queue</span>
                <span>→</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#252a36] text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="py-3 px-3">Order Number</th>
                        <th class="py-3 px-3">Collector</th>
                        <th class="py-3 px-3">Items Count</th>
                        <th class="py-3 px-3">Amount</th>
                        <th class="py-3 px-3">Fulfillment Status</th>
                        <th class="py-3 px-3">Placed At</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#252a36]/60">
                    @forelse ($metrics['recent_orders'] as $order)
                        <tr class="hover:bg-[#1a1e28]/70 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-semibold text-white">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-emerald-400 transition-colors">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="font-medium text-gray-200">{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="py-3.5 px-3 font-mono text-gray-300">
                                {{ $order->items ? $order->items->count() : 1 }} piece(s)
                            </td>
                            <td class="py-3.5 px-3 font-bold text-white font-mono">
                                ৳{{ number_format($order->total_amount, 0) }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $order->status->badgeClass() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-gray-400 text-[11px]">
                                {{ $order->created_at ? $order->created_at->diffForHumans() : 'Just now' }}
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-[#222733] hover:bg-[#214E34] text-[11px] font-semibold text-gray-200 hover:text-white transition-colors">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                No recent orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
