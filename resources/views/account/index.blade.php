@extends('layouts.storefront')

@section('seo_meta')
    <title>Collector Portal — {{ $user->name }} | Sorolata</title>
    <meta name="description" content="View your past acquisitions, provenance certificates, saved heirlooms, and delivery addresses.">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16 space-y-12" x-data="{ showEditModal: false }">
    
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Header Profile Summary -->
    <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
            <div class="w-20 h-20 rounded-full bg-[#214E34]/10 text-[#214E34] flex items-center justify-center font-editorial-heading text-3xl font-light shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="space-y-1">
                <span class="inline-block text-[10px] uppercase tracking-widest text-[#C96A3D] font-bold">
                    {{ $user->role->label() }}
                </span>
                <h1 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">{{ $user->name }}</h1>
                <p class="text-xs text-[#666666]">
                    {{ $user->email }} @if($user->phone) • {{ $user->phone }} @endif
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="button" @click="showEditModal = true" class="px-5 py-2.5 rounded-full border border-[#D5D5D5] hover:border-[#214E34] text-xs font-semibold text-gray-700 hover:text-[#214E34] transition">
                {{ __('Edit Profile') }}
            </button>
            <form method="POST" action="{{ route('customer.logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition border border-rose-200">
                    {{ __('Sign Out') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl space-y-6" @click.away="showEditModal = false">
            <div class="flex items-center justify-between">
                <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">{{ __('Update Profile') }}</h3>
                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-700 uppercase">{{ __('Your Name *') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full text-xs p-3.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-[#214E34] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-700 uppercase">{{ __('Active Phone Number') }}</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="01XXXXXXXXX"
                           class="w-full text-xs p-3.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-[#214E34] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-700 uppercase">{{ __('Email Address (Unchangeable)') }}</label>
                    <input type="email" value="{{ $user->email }}" disabled 
                           class="w-full text-xs p-3.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#214E34] hover:bg-[#183a27] text-white text-xs font-bold rounded-xl shadow transition">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Left: Orders History -->
        <div class="lg:col-span-8 space-y-6">
            <h2 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                {{ __('Order History & Provenance Status') }}
            </h2>

            <div class="space-y-4">
                @forelse($recentOrders as $ord)
                    <div class="bg-white rounded-[32px] p-6 border border-[#E8E8E8] shadow-soft-xs space-y-4 hover:border-emerald-200 transition">
                        <div class="flex flex-wrap items-center justify-between pb-3 border-b border-[#E8E8E8] text-xs gap-2">
                            <div>
                                <span class="text-[#888888]">{{ __('Order Number:') }}</span>
                                <strong class="text-[#1D1D1D] font-mono">#{{ $ord->order_number }}</strong>
                                <span class="text-[#888888] ml-2">({{ $ord->created_at->format('d M, Y') }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $ord->status->badgeClass() }}">
                                    {{ $ord->status->label() }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $ord->payment_status->badgeClass() }}">
                                    {{ $ord->payment_status->label() }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($ord->items as $item)
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product?->media->first()?->file_path ?? 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=100' }}" 
                                             alt="{{ $item->product_title }}" class="w-12 h-12 rounded-xl object-cover bg-gray-50 border">
                                        <div>
                                            <h4 class="font-editorial-heading text-base text-[#1D1D1D] font-semibold">{{ $item->product_title }}</h4>
                                            <span class="text-xs text-gray-500">{{ __('Qty: :qty • Unit Price: ৳:price', ['qty' => $item->quantity, 'price' => number_format((float)$item->unit_price, 2)]) }}</span>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">৳{{ number_format((float)$item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-3 border-t border-[#E8E8E8] flex flex-wrap items-center justify-between text-xs gap-2">
                            <span class="text-[#888888]">{{ __('Total Price:') }} <strong class="text-[#1D1D1D] font-bold">৳{{ number_format((float)$ord->total_amount, 2) }}</strong></span>
                            <a href="{{ route('track.order', ['order_number' => $ord->order_number]) }}" class="text-[#214E34] font-bold hover:underline flex items-center gap-1">
                                <span>{{ __('View Live Tracking Timeline') }}</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[32px] p-12 text-center text-[#888888] border border-[#E8E8E8] space-y-3">
                        <span class="text-3xl">🛍️</span>
                        <h4 class="text-base font-bold text-gray-700">{{ __('No orders placed yet') }}</h4>
                        <p class="text-xs text-gray-500">{{ __('Explore authentic Dhakai Jamdani, Muslin, and living heritage crafts.') }}</p>
                        <a href="{{ route('shop.index') }}" class="inline-block mt-2 px-6 py-2.5 rounded-full bg-[#214E34] text-white text-xs font-bold hover:bg-[#183a27] transition">
                            {{ __('Explore Archive Collection') }}
                        </a>
                    </div>
                @endforelse
            </div>

            @if($recentOrders->hasPages())
                <div class="pt-4">
                    {{ $recentOrders->links() }}
                </div>
            @endif
        </div>

        <!-- Right: Saved Wishlist Pieces -->
        <div class="lg:col-span-4 space-y-6">
            <h2 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                {{ __('Saved Heirlooms (Wishlist)') }}
            </h2>

            <div class="space-y-4">
                @foreach($savedArtifacts as $saved)
                    <div class="bg-white rounded-[24px] p-4 border border-[#E8E8E8] shadow-soft-xs flex items-center gap-4 hover:border-emerald-200 transition">
                        <img src="{{ $saved->media->first()?->file_path }}" alt="{{ $saved->getLocalized('title') }}" class="w-16 h-16 rounded-[14px] object-cover bg-[#F8F6F2]">
                        <div class="flex-grow">
                            <h4 class="font-editorial-heading text-sm text-[#1D1D1D] line-clamp-1 font-semibold">{{ $saved->getLocalized('title') }}</h4>
                            <p class="text-xs text-gray-600 font-bold mt-0.5">৳{{ number_format((float)$saved->price, 2) }}</p>
                        </div>
                        <a href="{{ route('products.show', $saved->slug) }}" class="text-xs text-[#214E34] font-bold hover:underline shrink-0">{{ __('View →') }}</a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
