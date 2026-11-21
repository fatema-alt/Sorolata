<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }} #{{ $order->order_number }} — Sorolata</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #214E34;
            --primary-dark: #153322;
            --gold: #C5A059;
            --charcoal: #1D1D1D;
            --muted: #6B7280;
            --border: #E5E7EB;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F9FAFB;
            color: var(--charcoal);
            padding: 30px 15px;
            font-size: 13px;
            line-height: 1.5;
        }
        .invoice-card {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 40px 48px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #F3F4F6;
            padding-bottom: 24px;
            gap: 20px;
        }
        .brand-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 34px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .brand-tagline {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
            font-weight: 600;
            margin-top: 5px;
        }
        .brand-meta {
            font-size: 11px;
            color: var(--muted);
            margin-top: 6px;
            line-height: 1.4;
        }
        .invoice-status-box {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .badge-paid { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-cod { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
        .badge-partial { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
        
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin: 28px 0;
            padding: 20px 24px;
            background: #FAFAFA;
            border-radius: 12px;
            border: 1px solid #F0F0F0;
        }
        .meta-col h4 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--muted);
            margin-bottom: 8px;
            font-weight: 700;
        }
        .meta-col p {
            font-size: 13px;
            color: #111827;
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            text-align: left;
            padding: 12px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4B5563;
            border-bottom: 2px solid #E5E7EB;
            background: #F9FAFB;
        }
        td {
            padding: 14px;
            border-bottom: 1px solid #F3F4F6;
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace, Courier, monospace; }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }
        .totals-table {
            width: 320px;
            font-size: 13px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            color: #4B5563;
        }
        .totals-row.grand-total {
            border-top: 2px solid var(--charcoal);
            margin-top: 8px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
        }
        .totals-row.due {
            color: #DC2626;
            font-weight: 700;
        }
        
        .footer-note {
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px dashed var(--border);
            text-align: center;
            font-size: 11px;
            color: var(--muted);
            line-height: 1.6;
        }

        .action-bar {
            max-width: 820px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: var(--primary);
            color: #fff;
            padding: 10px 22px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(33, 78, 52, 0.25);
            transition: all 0.2s;
        }
        .btn-print:hover {
            background: var(--primary-dark);
        }
        .btn-back {
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .btn-back:hover {
            color: var(--charcoal);
        }

        @media print {
            body { background: #fff; padding: 0; }
            .action-bar { display: none !important; }
            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('track.order', ['order_number' => $order->order_number]) }}" class="btn-back">
            ← {{ __('Back to Order Tracking') }}
        </a>
        <button onclick="window.print()" class="btn-print">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print / Save PDF (চালান সংরক্ষণ)') }}
        </button>
    </div>

    <div class="invoice-card">
        <!-- Header -->
        <div class="header">
            <div>
                <h1 class="brand-title">Sorolata</h1>
                <div class="brand-tagline">Bengal Living Heritage & Handcrafted Luxury</div>
                <div class="brand-meta">
                    House of Certified Geographical Indication & Handloom Artisans<br>
                    Website: sorolata.com • Support: +880 1700-000000 • Sonargaon, Bangladesh
                </div>
            </div>
            <div class="invoice-status-box">
                <div style="font-size: 18px; font-weight: 800; color: #111827; font-family: monospace;">
                    #{{ $order->order_number }}
                </div>
                <div style="font-size: 11px; color: var(--muted); margin-top: 3px;">
                    {{ __('Date:') }} {{ $order->created_at->format('d M, Y • h:i A') }}
                </div>
                <div style="margin-top: 10px;">
                    @php
                        $isPaid = $order->payment_status === \App\Enums\PaymentStatus::PAID;
                        $isPartial = $order->payment_status === \App\Enums\PaymentStatus::PARTIALLY_PAID;
                    @endphp
                    @if($isPaid)
                        <span class="badge badge-paid">✓ {{ __('Payment Complete / পরিশোধিত') }}</span>
                    @elseif($isPartial)
                        <span class="badge badge-partial">⚡ {{ __('Partially Paid / আংশিক পরিশোধিত') }}</span>
                    @else
                        <span class="badge badge-cod">⏳ {{ __('Cash On Delivery / ক্যাশ অন ডেলিভারি') }}</span>
                    @endif
                </div>
                @if($order->steadfast_consignment_id)
                    <div style="margin-top: 8px; font-size: 11px; color: var(--muted);">
                        Courier ID: <strong class="font-mono">{{ $order->steadfast_tracking_code ?? $order->steadfast_consignment_id }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer & Delivery Meta Grid -->
        <div class="meta-grid">
            <div class="meta-col">
                <h4>{{ __('Billed & Delivered To (গ্রাহকের তথ্য)') }}</h4>
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>{{ $order->customer_phone }}</p>
                @if($order->customer_email)
                    <p style="color: var(--muted); font-size: 12px;">{{ $order->customer_email }}</p>
                @endif
                <p style="margin-top: 6px; color: #374151;">
                    {{ is_array($order->shipping_address) ? ($order->shipping_address['line_1'] ?? ($order->shipping_address['address'] ?? json_encode($order->shipping_address))) : $order->shipping_address }}
                </p>
                @if(!empty($order->district))
                    <p style="color: var(--primary); font-weight: 600; font-size: 12px;">
                        {{ $order->district }}
                    </p>
                @endif
            </div>
            <div class="meta-col" style="text-align: right;">
                <h4>{{ __('Order & Payment Specifications') }}</h4>
                <p>{{ __('Payment Method:') }} <strong>{{ strtoupper(str_replace('_', ' ', (string)($order->payment_method?->value ?? $order->payment_method ?? 'COD'))) }}</strong></p>
                <p>{{ __('Fulfillment Status:') }} <strong>{{ $order->status->label() }}</strong></p>
                @if($order->paid_at)
                    <p style="color: #059669; font-size: 12px;">{{ __('Paid At:') }} {{ $order->paid_at->format('d M, Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Line Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">{{ __('Item & Artisan Provenance') }}</th>
                    <th class="text-center" style="width: 15%;">{{ __('Qty') }}</th>
                    <th class="text-right" style="width: 15%;">{{ __('Unit Price') }}</th>
                    <th class="text-right" style="width: 20%;">{{ __('Subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong style="font-size: 14px; color: #111827;">{{ $item->product_title }}</strong>
                            @if($item->sku)
                                <div style="font-size: 11px; color: var(--muted); font-family: monospace;">SKU: {{ $item->sku }}</div>
                            @endif
                            @if($item->artisan_name)
                                <div style="font-size: 11px; color: var(--gold); font-weight: 600;">Artisan: {{ $item->artisan_name }}</div>
                            @endif
                        </td>
                        <td class="text-center" style="font-weight: 600; font-size: 14px;">
                            {{ $item->quantity }}
                        </td>
                        <td class="text-right font-mono" style="font-size: 13px;">
                            ৳{{ number_format((float) $item->unit_price, 2) }}
                        </td>
                        <td class="text-right font-mono" style="font-size: 14px; font-weight: 700; color: #111827;">
                            ৳{{ number_format((float) $item->subtotal, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Calculation -->
        <div class="totals-section">
            <div class="totals-table">
                <div class="totals-row">
                    <span>{{ __('Subtotal (মোট মূল্য):') }}</span>
                    <span class="font-mono">৳{{ number_format((float) $order->subtotal_amount, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>{{ __('Delivery Charge (ডেলিভারি ফি):') }}</span>
                    <span class="font-mono">৳{{ number_format((float) $order->shipping_fee, 2) }}</span>
                </div>
                @if((float) $order->discount_amount > 0)
                    <div class="totals-row" style="color: #059669;">
                        <span>{{ __('Discount (ডিসকাউন্ট):') }}</span>
                        <span class="font-mono">-৳{{ number_format((float) $order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="totals-row grand-total">
                    <span>{{ __('Total (সর্বমোট):') }}</span>
                    <span class="font-mono">৳{{ number_format((float) $order->total_amount, 2) }}</span>
                </div>
                @if((float) $order->paid_amount > 0)
                    <div class="totals-row" style="color: #059669; font-weight: 600;">
                        <span>{{ __('Paid / Advance Received:') }}</span>
                        <span class="font-mono">-৳{{ number_format((float) $order->paid_amount, 2) }}</span>
                    </div>
                @endif
                @php
                    $due = $order->due_amount ?: max(0.0, (float) $order->total_amount - (float) $order->paid_amount);
                @endphp
                @if($due > 0)
                    <div class="totals-row due">
                        <span>{{ __('Due on Delivery (বাকি COD):') }}</span>
                        <span class="font-mono">৳{{ number_format((float) $due, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            <p><strong>{{ __('Thank you for preserving Bengal’s Living Heritage.') }}</strong></p>
            <p style="margin-top: 4px;">
                {{ __('Every Sorolata piece is handcrafted by master artisans. For inquiries, exchanges, or provenance certificates, contact support@sorolata.com.') }}
            </p>
        </div>
    </div>

</body>
</html>
