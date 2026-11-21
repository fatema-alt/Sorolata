@extends('layouts.storefront')

@section('seo_meta')
    <title>{{ __('Track your order') }} — #{{ $order?->order_number ?? 'Search' }} | Sorolata</title>
    <meta name="description" content="Follow the handcrafting, payment verification, and courier transit of your acquired Bangladeshi heirloom.">
@endsection

@section('content')
<style>
    .track-order-wrapper {
        max-width: 1040px;
        margin: 0 auto;
        padding: 2.5rem 1.25rem;
    }
    .order-stepper-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
    }
    .order-stepper-line {
        position: absolute;
        top: 1rem;
        left: 10%;
        right: 10%;
        height: 2px;
        border-top: 2px dashed #E5E7EB;
        z-index: 1;
    }
    .order-step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 0.25rem;
    }
    .order-step-circle {
        width: 2rem;
        height: 2rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .order-step-active {
        border: 2px solid #214E34;
        color: #214E34;
    }
    .order-step-inactive {
        border: 2px solid #E5E7EB;
        color: #9CA3AF;
    }
    .order-step-pending {
        border: 2px solid #2563EB;
        color: #2563EB;
        background: #EFF6FF;
    }
    .track-lower-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        border-top: 1px solid #F3F4F6;
        padding-top: 1.5rem;
    }
    @media (max-width: 768px) {
        .order-stepper-container {
            grid-template-columns: 1fr;
            gap: 1.25rem;
            text-align: left;
        }
        .order-stepper-line {
            display: none;
        }
        .order-step-item {
            flex-direction: row;
            gap: 0.75rem;
        }
        .track-lower-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>

<div class="track-order-wrapper" x-data="{
    copiedTrx: false,
    showCancelModal: false,
    copy(text) {
        if (!text) return;
        navigator.clipboard.writeText(text);
        this.copiedTrx = true;
        setTimeout(() => { this.copiedTrx = false; }, 2000);
    }
}">
    
    <!-- 1. Search Box Header (Row Layout with Fixed Dimensions) -->
    <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1.5rem; margin-bottom: 1.5rem;">
        <div style="flex: 1 1 300px;">
            <h1 class="font-editorial-heading" style="font-size: 2.5rem; line-height: 1.1; color: #1D1D1D; font-weight: 300; margin: 0; white-space: nowrap;">
                {{ __('Track your order') }}
            </h1>
            <span class="sr-only">{{ __('Order Tracking & Delivery Status') }} - {{ __('Track Your Heirloom') }}</span>
            <p style="font-size: 0.75rem; color: #777; margin-top: 0.35rem; margin-bottom: 0;">
                {{ __('Enter your order number (SRL-XXXXXXXX) or the mobile number you ordered with.') }}
            </p>
        </div>

        <form action="{{ route('track.order') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center; flex-shrink: 0;">
            <input 
                type="text" 
                name="order_number" 
                value="{{ $queriedNumber ?? '' }}"
                placeholder="{{ __('SRL-XXXXXXXX') }}" 
                style="width: 260px; font-size: 0.75rem; padding: 0.7rem 1rem; border-radius: 1rem; border: 1px solid #D1D5DB; background: #fff; color: #111; font-family: monospace; outline: none;"
                required
            >
            <button type="submit" style="padding: 0.7rem 1.5rem; background-color: #214E34; color: #fff; font-size: 0.75rem; font-weight: bold; border-radius: 1rem; border: none; cursor: pointer; white-space: nowrap;">
                {{ __('Track order') }}
            </button>
        </form>
    </div>

    <!-- Success Message Banner (e.g. after TrxID submission) -->
    @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 1rem; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
            <span style="width: 1.25rem; height: 1.25rem; border-radius: 9999px; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem; flex-shrink: 0;">✓</span>
            <div style="font-size: 0.75rem; color: #065F46;">
                <strong style="color: #064E3B; margin-right: 0.25rem;">{{ __('Done') }}</strong>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($order)
        @php
            $currentStep = $order->status->stepNumber();
            $isPaid = $order->payment_status === \App\Enums\PaymentStatus::PAID;
            $isPartial = $order->payment_status === \App\Enums\PaymentStatus::PARTIALLY_PAID;
            $dueAmount = $order->due_amount ?: max(0.0, (float) $order->total_amount - (float) $order->paid_amount);
        @endphp

        <!-- 2. Main Order Card -->
        <div style="background: #fff; border-radius: 1.5rem; border: 1px solid #E8E8E8; padding: 2rem 2.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            
            <!-- Order Header: Number, Date, Badges -->
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <div style="font-size: 1.5rem; color: #1D1D1D; font-weight: 300;">
                        {{ __('Order') }} <strong style="font-family: monospace; font-weight: 700; color: #111;">#{{ $order->order_number }}</strong>
                    </div>
                    <div style="font-size: 0.75rem; color: #6B7280; margin-top: 0.25rem;">
                        {{ __('Placed on') }} {{ $order->created_at->format('d M Y, h:i A') }} • 
                        <button type="button" @click="copy('{{ $order->order_number }}')" style="color: #214E34; font-weight: 600; text-decoration: underline; background: none; border: none; cursor: pointer; font-size: 0.75rem;">
                            <span x-text="copiedTrx ? @json(__('Order number copied ✓'), JSON_UNESCAPED_UNICODE) : @json(__('Copy order number'), JSON_UNESCAPED_UNICODE)">{{ __('Copy order number') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Badges & Invoice -->
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                    <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.35rem 0.95rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: #214E34; color: #fff; text-decoration: none; box-shadow: 0 1px 3px rgba(33,78,52,0.18);">
                        <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('Download Invoice (চালান)') }}
                    </a>
                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.3rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #FBF6ED; border: 1px solid #F2E5CE; color: #8C6424;">
                        <span style="width: 6px; height: 6px; border-radius: 9999px; background: #D4A359;"></span>
                        {{ $order->status->label() }}
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.3rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: {{ $isPaid ? '#ECFDF5' : '#FBF6ED' }}; border: 1px solid {{ $isPaid ? '#A7F3D0' : '#F2E5CE' }}; color: {{ $isPaid ? '#065F46' : '#8C6424' }};">
                        <span style="width: 6px; height: 6px; border-radius: 9999px; background: {{ $isPaid ? '#10B981' : '#D4A359' }};"></span>
                        {{ __('Payment:') }} {{ $order->payment_status->label() }}
                    </span>
                </div>
            </div>

            <!-- Customer & Delivery Snapshot Grid -->
            <div style="border-top: 1px solid #F3F4F6; padding-top: 1.25rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; font-size: 0.75rem;">
                <div>
                    <span style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9CA3AF; font-weight: 600; display: block;">{{ __('Customer') }}</span>
                    <div style="font-weight: 700; color: #111; font-size: 0.875rem; margin-top: 0.25rem;">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <span style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9CA3AF; font-weight: 600; display: block;">{{ __('Mobile') }}</span>
                    <div style="font-weight: 700; color: #111; font-size: 0.875rem; margin-top: 0.25rem; font-family: monospace;">{{ $order->customer_phone }}</div>
                </div>
                <div>
                    <span style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9CA3AF; font-weight: 600; display: block;">{{ __('Payment method') }}</span>
                    <div style="font-weight: 700; color: #111; font-size: 0.875rem; margin-top: 0.25rem;">{{ $order->payment_gateway?->displayName() ?? ucfirst($order->payment_gateway?->value ?? 'Cash on Delivery (COD)') }}</div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <span style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9CA3AF; font-weight: 600; display: block;">{{ __('Delivery to') }}</span>
                    <div style="font-weight: 500; color: #1F2937; margin-top: 0.25rem;">
                        {{ $order->shipping_address['address'] ?? ($order->shipping_address['line_1'] ?? '') }}, {{ $order->shipping_address['district'] ?? ($order->shipping_address['city'] ?? __('Dhaka')) }}
                    </div>
                </div>
            </div>

            <!-- Payment Check In Progress Banner -->
            @if(!empty($order->transaction_id) && !$isPaid)
                <div style="background: #EEF4FB; border: 1px solid #D5E3F5; border-radius: 1rem; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <div style="width: 2rem; height: 2rem; border-radius: 9999px; background: #DBEAFE; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #1E3A8A; font-size: 0.8rem;">{{ __('Payment check in progress') }}</div>
                        <div style="color: #2563EB; font-size: 0.75rem; margin-top: 0.15rem;">
                            {{ __('Our accounts team is matching your TrxID :trx with the statement. We will confirm your parcel as soon as it matches.', ['trx' => $order->transaction_id]) }}
                        </div>
                    </div>
                </div>
            @elseif(!$isPaid && empty($order->transaction_id))
                <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 1rem; padding: 1rem 1.25rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <div style="font-weight: 700; color: #92400E; font-size: 0.8rem;">{{ __('Payment required to confirm dispatch') }}</div>
                        <div style="color: #B45309; font-size: 0.75rem; margin-top: 0.15rem;">{{ __('Please send payment and submit your TrxID to start parcel handcrafting and delivery.') }}</div>
                    </div>
                    <a href="{{ route('payment.manual.instruction', ['order' => $order->order_number]) }}" 
                       style="padding: 0.5rem 1rem; background-color: #214E34; color: #fff; font-size: 0.75rem; font-weight: bold; border-radius: 0.75rem; text-decoration: none;">
                        {{ __('Pay Now →') }}
                    </a>
                </div>
            @endif

            <!-- Courier Tracking Code -->
            @if(!empty($order->tracking_number))
                <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 1rem; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <span style="font-size: 0.65rem; font-weight: 700; color: #4338CA; text-transform: uppercase; letter-spacing: 0.05em; display: block;">{{ __('Courier Tracking') }}</span>
                        <div style="font-family: monospace; font-weight: 700; color: #312E81; font-size: 0.875rem; margin-top: 0.2rem;">{{ $order->tracking_number }} ({{ $order->courier_name ?? 'Courier' }})</div>
                    </div>
                    <button type="button" @click="copy('{{ $order->tracking_number }}')" style="font-size: 0.75rem; color: #4338CA; text-decoration: underline; font-weight: 600; background: none; border: none; cursor: pointer;">
                        {{ __('Copy') }}
                    </button>
                </div>
            @endif

            <!-- 3. Order Progress Stepper (Always Visible 5 Steps) -->
            <div style="border-top: 1px solid #F3F4F6; padding-top: 1.25rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #111; margin-bottom: 1rem;">
                    {{ __('Order progress') }}
                </h3>

                <div class="order-stepper-container">
                    <div class="order-stepper-line"></div>

                    <!-- Step 1: Order placed -->
                    <div class="order-step-item">
                        <span class="order-step-circle {{ $currentStep >= 1 ? 'order-step-active' : 'order-step-inactive' }}">
                            {{ $currentStep > 1 ? '✓' : '1' }}
                        </span>
                        <div style="font-weight: 700; color: #111; font-size: 0.75rem; margin-top: 0.5rem;">{{ __('Order placed') }}</div>
                        <div style="font-size: 0.65rem; color: #6B7280; margin-top: 0.15rem;">{{ __('Registered in our system') }}</div>
                    </div>

                    <!-- Step 2: Payment check -->
                    <div class="order-step-item">
                        <span class="order-step-circle {{ $currentStep >= 2 ? 'order-step-active' : ($currentStep == 1 && !empty($order->transaction_id) ? 'order-step-pending' : 'order-step-inactive') }}">
                            {{ $currentStep > 2 ? '✓' : '2' }}
                        </span>
                        <div style="font-weight: 700; color: #111; font-size: 0.75rem; margin-top: 0.5rem;">{{ __('Payment check') }}</div>
                        <div style="font-size: 0.65rem; color: #6B7280; margin-top: 0.15rem;">
                            {{ !empty($order->transaction_id) ? __('Confirming your TrxID') : __('Awaiting payment submission') }}
                        </div>
                    </div>

                    <!-- Step 3: Packed and sealed -->
                    <div class="order-step-item">
                        <span class="order-step-circle {{ $currentStep >= 3 ? 'order-step-active' : 'order-step-inactive' }}">
                            {{ $currentStep > 3 ? '✓' : '3' }}
                        </span>
                        <div style="font-weight: 700; color: #111; font-size: 0.75rem; margin-top: 0.5rem;">{{ __('Packed and sealed') }}</div>
                        <div style="font-size: 0.65rem; color: #6B7280; margin-top: 0.15rem;">{{ __('Quality seal from the loom') }}</div>
                    </div>

                    <!-- Step 4: With the courier -->
                    <div class="order-step-item">
                        <span class="order-step-circle {{ $currentStep >= 4 ? 'order-step-active' : 'order-step-inactive' }}">
                            {{ $currentStep > 4 ? '✓' : '4' }}
                        </span>
                        <div style="font-weight: 700; color: #111; font-size: 0.75rem; margin-top: 0.5rem;">{{ __('With the courier') }}</div>
                        <div style="font-size: 0.65rem; color: #6B7280; margin-top: 0.15rem;">{{ __('On its way to you') }}</div>
                    </div>

                    <!-- Step 5: Delivered -->
                    <div class="order-step-item">
                        <span class="order-step-circle {{ $currentStep >= 5 ? 'order-step-active' : 'order-step-inactive' }}">
                            {{ $currentStep >= 5 ? '✓' : '5' }}
                        </span>
                        <div style="font-weight: 700; color: #111; font-size: 0.75rem; margin-top: 0.5rem;">{{ __('Delivered') }}</div>
                        <div style="font-size: 0.65rem; color: #6B7280; margin-top: 0.15rem;">{{ __('In your hands') }}</div>
                    </div>
                </div>
            </div>

            <!-- 4. Two-Column Lower Grid: Left = Order History, Right = Items & Ledger -->
            <div class="track-lower-grid">
                
                <!-- Left Column: Order History -->
                <div>
                    <h3 style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #111; margin-bottom: 1rem;">
                        {{ __('Order history') }}
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        @forelse($order->timelines as $timeline)
                            <div style="display: flex; gap: 0.875rem; align-items: flex-start;">
                                <div style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $loop->first ? '#214E34' : '#D1D5DB' }}; margin-top: 0.25rem; flex-shrink: 0; box-shadow: 0 0 0 3px #F9FAFB;"></div>
                                <div style="flex-grow: 1;">
                                    <div style="font-weight: 700; color: #111; font-size: 0.8rem; line-height: 1.3;">{{ $timeline->localizedStatus() }}</div>
                                    <div style="color: #4B5563; font-size: 0.725rem; margin-top: 0.2rem; line-height: 1.4;">{{ $timeline->localizedNote() }}</div>
                                    <div style="color: #9CA3AF; font-size: 0.675rem; margin-top: 0.2rem;">
                                        {{ $timeline->created_at->format('M d, Y, h:i A') }} • {{ __('By :actor', ['actor' => __($timeline->actor_type)]) }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="font-size: 0.75rem; color: #6B7280;">{{ __('Order registered.') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Right Column: Items in this order & Financial Ledger -->
                <div>
                    <h3 style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #111; margin-bottom: 1rem;">
                        {{ __('Items in this order') }}
                    </h3>

                    <!-- Items List -->
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem;">
                        @foreach($order->items as $item)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                                    @if($item->product && $item->product->media->first())
                                        <img src="{{ $item->product->media->first()->file_path }}" alt="{{ $item->product_title }}" style="width: 44px; height: 44px; border-radius: 0.75rem; object-fit: cover; border: 1px solid #E5E7EB; flex-shrink: 0;">
                                    @else
                                        <div style="width: 44px; height: 44px; border-radius: 0.75rem; background: #F3F4F6; border: 1px solid #E5E7EB; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">🏺</div>
                                    @endif
                                    <div style="min-width: 0;">
                                        <div style="font-weight: 700; color: #111; font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $item->product_title }}</div>
                                        <div style="font-size: 0.7rem; color: #6B7280; margin-top: 0.15rem;">{{ __('Qty :qty', ['qty' => $item->quantity]) }} • SKU: {{ $item->sku }}</div>
                                    </div>
                                </div>
                                <div style="font-weight: 700; color: #111; font-family: monospace; font-size: 0.8rem; flex-shrink: 0;">
                                    ৳{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Financial Ledger Box -->
                    <div style="background: #FBF9F5; border-radius: 1rem; padding: 1.25rem; border: 1px solid #EFECE6; font-size: 0.75rem; display: flex; flex-direction: column; gap: 0.625rem;">
                        <div style="display: flex; justify-content: space-between; color: #4B5563;">
                            <span>{{ __('Subtotal') }}</span>
                            <span style="font-family: monospace; font-weight: 600; color: #111;">৳{{ number_format($order->subtotal_amount, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: #4B5563;">
                            <span>{{ __('Delivery') }}</span>
                            <span style="font-family: monospace; font-weight: 600; color: #111;">৳{{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div style="display: flex; justify-content: space-between; color: #059669; font-weight: 600;">
                                <span>{{ __('Discount') }}</span>
                                <span style="font-family: monospace;">-৳{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div style="border-top: 1px solid #E5E7EB; padding-top: 0.5rem; display: flex; justify-content: space-between; font-weight: 700; font-size: 0.875rem; color: #111;">
                            <span>{{ __('Total') }}</span>
                            <span style="font-family: monospace; color: #214E34;">৳{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div style="border-top: 1px solid #E5E7EB; padding-top: 0.5rem; display: flex; justify-content: space-between; color: #047857; font-weight: 600;">
                            <span>{{ __('Paid') }}</span>
                            <span style="font-family: monospace;">৳{{ number_format($order->paid_amount, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: 700; color: #111;">
                            <span>{{ __('Due on delivery') }}</span>
                            <span style="font-family: monospace; color: #B45309;">৳{{ number_format($dueAmount, 2) }}</span>
                        </div>
                        @if(!empty($order->transaction_id))
                            <div style="border-top: 1px solid #E5E7EB; padding-top: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #4B5563;">{{ __('Transaction ID') }}</span>
                                <span style="background: #fff; border: 1px solid #D1D5DB; padding: 0.15rem 0.6rem; border-radius: 0.375rem; font-family: monospace; font-weight: 700; font-size: 0.75rem; color: #111;">
                                    {{ $order->transaction_id }}
                                </span>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

            <!-- 5. Footer Actions: Help, Cancel Request, Print & Shop Buttons -->
            <div style="border-top: 1px solid #F3F4F6; padding-top: 1.25rem; margin-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; font-size: 0.75rem;">
                <div style="color: #6B7280;">
                    {{ __('Need help? Call') }} <strong style="color: #111; font-family: monospace;">+880 1700 000 000</strong>
                </div>

                @if(!in_array($order->status, [\App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::DELIVERED, \App\Enums\OrderStatus::CANCELLED], true))
                    <div>
                        <button type="button" @click="showCancelModal = true" style="color: #BE123C; text-decoration: underline; font-weight: 600; background: none; border: none; cursor: pointer; font-size: 0.75rem;">
                            {{ __('Request cancellation or change') }}
                        </button>
                    </div>
                @endif

                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <button type="button" onclick="window.print()" style="padding: 0.6rem 1.25rem; border-radius: 0.75rem; border: 1px solid #D1D5DB; background: #fff; color: #111; font-weight: 700; cursor: pointer; font-size: 0.75rem;">
                        {{ __('Print receipt') }}
                    </button>
                    <a href="{{ route('shop.index') }}" style="padding: 0.6rem 1.25rem; border-radius: 0.75rem; background: #214E34; color: #fff; font-weight: 700; text-decoration: none; display: inline-block; font-size: 0.75rem;">
                        {{ __('Continue shopping') }}
                    </a>
                </div>
            </div>

        </div>

        <!-- Cancellation Modal -->
        <div x-show="showCancelModal" x-cloak style="position: fixed; inset: 0; z-index: 50; overflow-y: auto; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 1rem;">
            <div @click.away="showCancelModal = false" style="background: #fff; border-radius: 1.5rem; max-width: 440px; width: 100%; padding: 1.5rem; border: 1px solid #E5E7EB; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #F3F4F6;">
                    <h3 style="font-weight: 700; color: #111; font-size: 0.875rem; margin: 0;">{{ __('Request Cancellation or Change') }}</h3>
                    <button type="button" @click="showCancelModal = false" style="color: #9CA3AF; font-size: 1.25rem; font-weight: bold; background: none; border: none; cursor: pointer;">✕</button>
                </div>

                <form method="POST" action="{{ route('track.order.cancel-request', $order->order_number) }}" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem; font-size: 0.75rem;">
                    @csrf
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem;">{{ __('Select Reason *') }}</label>
                        <select name="reason" required style="width: 100%; border-radius: 0.75rem; border: 1px solid #D1D5DB; padding: 0.6rem 0.85rem; color: #1F2937; outline: none;">
                            <option value="ভুল প্রোডাক্ট বা কোয়ান্টিটি অর্ডার করেছি">{{ __('Ordered wrong item or quantity') }}</option>
                            <option value="ডেলিভারি ঠিকানা বা মোবাইল নম্বর পরিবর্তন করতে চাই">{{ __('Need to change delivery address or phone number') }}</option>
                            <option value="অন্য পেমেন্ট পদ্ধতিতে অর্ডার করতে চাই">{{ __('Want to switch payment method') }}</option>
                            <option value="দেরি হবে মনে হচ্ছে / পরবর্তীতে অর্ডার করব">{{ __('Delivery will take too long / will order later') }}</option>
                            <option value="অন্যান্য কারণ">{{ __('Other reason') }}</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem;">{{ __('Additional Notes (Optional)') }}</label>
                        <textarea name="notes" rows="3" placeholder="{{ __('Describe your request...') }}" 
                                  style="width: 100%; border-radius: 0.75rem; border: 1px solid #D1D5DB; padding: 0.6rem 0.85rem; color: #1F2937; outline: none;"></textarea>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; padding-top: 0.5rem;">
                        <button type="button" @click="showCancelModal = false" style="padding: 0.5rem 1rem; border-radius: 0.75rem; color: #4B5563; background: #F3F4F6; font-weight: 600; border: none; cursor: pointer;">
                            {{ __('Go Back') }}
                        </button>
                        <button type="submit" style="padding: 0.5rem 1.25rem; border-radius: 0.75rem; background: #BE123C; color: #fff; font-weight: 700; border: none; cursor: pointer;">
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @else
        @if(!empty($queriedNumber))
            <div style="padding: 2.5rem; text-align: center; background: #fff; border-radius: 1.5rem; border: 1px solid #E5E7EB;">
                <span style="font-size: 2rem;">🔍</span>
                <h3 style="font-size: 1rem; font-weight: 700; color: #1F2937; margin-top: 0.5rem;">{{ __('No Order Found') }}</h3>
                <p style="font-size: 0.75rem; color: #6B7280; max-width: 380px; margin: 0.5rem auto 0;">
                    {{ __('No matching order was found for ":number". Please verify the order number or phone number.', ['number' => $queriedNumber]) }}
                </p>
            </div>
        @else
            <div style="padding: 2.5rem; text-align: center; background: #fff; border-radius: 1.5rem; border: 1px solid #E5E7EB;">
                <span style="font-size: 2rem;">📦</span>
                <h3 style="font-size: 1rem; font-weight: 700; color: #214E34; margin-top: 0.5rem;">{{ __('Trace Your Order') }}</h3>
                <p style="font-size: 0.75rem; color: #6B7280; max-width: 420px; margin: 0.5rem auto 0;">
                    {{ __('Enter your order reference above (e.g. SRL-XXXXXXXX) or phone number to check live status.') }}
                </p>
            </div>
        @endif
    @endif

</div>
@endsection
