<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label #{{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Hind+Siliguri:wght@500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 4in 6in;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', -apple-system, sans-serif;
            background: #e5e7eb;
            color: #000;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .label-page {
            width: 4in;
            height: 6in;
            background: #fff;
            border: 2px solid #000;
            padding: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .brand-name {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .courier-badge {
            font-size: 11px;
            font-weight: 800;
            border: 2px solid #000;
            padding: 2px 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .barcode-section {
            text-align: center;
            padding: 6px 0;
            border-bottom: 2px solid #000;
        }
        .barcode-bars {
            display: inline-flex;
            align-items: flex-end;
            height: 42px;
            gap: 2px;
        }
        .barcode-bars span {
            background: #000;
            display: inline-block;
            height: 100%;
        }
        .barcode-text {
            font-family: monospace;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-top: 3px;
        }
        .consignee-box {
            border-bottom: 2px solid #000;
            padding: 8px 0;
            flex-grow: 1;
        }
        .section-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
        }
        .recipient-name {
            font-size: 16px;
            font-weight: 900;
            margin-top: 2px;
            line-height: 1.2;
        }
        .recipient-phone {
            font-size: 15px;
            font-weight: 900;
            font-family: monospace;
            margin: 3px 0;
        }
        .recipient-address {
            font-size: 12px;
            line-height: 1.35;
            font-weight: 600;
            color: #111;
        }
        .district-pill {
            display: inline-block;
            margin-top: 5px;
            font-size: 13px;
            font-weight: 900;
            background: #000;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .cod-row {
            display: flex;
            border-bottom: 2px solid #000;
            background: #f4f4f4;
        }
        .cod-box {
            flex: 1;
            padding: 6px 8px;
            border-right: 2px solid #000;
        }
        .cod-box:last-child {
            border-right: none;
        }
        .cod-amount {
            font-size: 20px;
            font-weight: 900;
            color: #000;
            font-family: monospace;
            line-height: 1.1;
        }
        .items-box {
            padding: 6px 0;
            font-size: 10px;
            border-bottom: 2px solid #000;
            max-height: 52px;
            overflow: hidden;
        }
        .items-box ul {
            list-style: none;
        }
        .items-box li {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sender-box {
            padding-top: 6px;
            font-size: 9px;
            line-height: 1.3;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
        }
        .btn-print {
            background: #214E34;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        @media print {
            body {
                background: transparent;
                padding: 0;
            }
            .label-page {
                border: none;
                width: 100%;
                height: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Print 4x6 Thermal Label
        </button>
    </div>

    @php
        $due = $order->due_amount ?: max(0.0, (float) $order->total_amount - (float) $order->paid_amount);
        $isPaid = $order->payment_status === \App\Enums\PaymentStatus::PAID;
        $trackingCode = $order->steadfast_tracking_code ?: ($order->steadfast_consignment_id ?: $order->order_number);
        $addr = is_array($order->shipping_address) ? ($order->shipping_address['address'] ?? ($order->shipping_address['line_1'] ?? json_encode($order->shipping_address))) : (string) $order->shipping_address;
    @endphp

    <div class="label-page">
        <!-- Header -->
        <div class="header">
            <div>
                <div class="brand-name">SOROLATA</div>
                <div style="font-size: 9px; font-weight: 700; letter-spacing: 0.5px;">BENGAL LIVING HERITAGE</div>
            </div>
            <div style="text-align: right;">
                <div class="courier-badge">STEADFAST COURIER</div>
                <div style="font-size: 9px; font-weight: 700; margin-top: 2px;">STANDARD HOME DELIVERY</div>
            </div>
        </div>

        <!-- Barcode -->
        <div class="barcode-section">
            <div class="barcode-bars">
                @php
                    $hash = crc32($trackingCode);
                    $widths = [2, 4, 1, 3, 2, 5, 2, 3, 1, 4, 2, 3, 5, 2, 1, 3, 4, 2, 5, 1, 3, 2, 4, 1, 3, 2, 4, 2, 1, 3, 5, 2, 3, 1, 4];
                @endphp
                @foreach($widths as $idx => $w)
                    <span style="width: {{ $w }}px; height: {{ 32 + (($idx + $hash) % 10) }}px;"></span>
                @endforeach
            </div>
            <div class="barcode-text">{{ $trackingCode }}</div>
        </div>

        <!-- Consignee / Delivery Address -->
        <div class="consignee-box">
            <div class="section-label">DELIVER TO / প্রাপক:</div>
            <div class="recipient-name">{{ $order->customer_name }}</div>
            <div class="recipient-phone">📞 {{ $order->customer_phone }}</div>
            <div class="recipient-address">{{ $addr }}</div>
            @if($order->district)
                <div class="district-pill">📍 {{ strtoupper($order->district) }}</div>
            @endif
        </div>

        <!-- COD & Payment Summary -->
        <div class="cod-row">
            <div class="cod-box">
                <div class="section-label">PAYMENT STATUS:</div>
                <div style="font-size: 13px; font-weight: 900; margin-top: 2px;">
                    @if($isPaid)
                        ✓ PREPAID / PAID
                    @elseif($due > 0)
                        ⏳ CASH ON DELIVERY
                    @else
                        PAID
                    @endif
                </div>
            </div>
            <div class="cod-box" style="text-align: right; background: #000; color: #fff;">
                <div class="section-label" style="color: #bbb;">COD TO COLLECT:</div>
                <div class="cod-amount" style="color: #fff;">
                    @if($due > 0)
                        ৳ {{ number_format((float)$due, 0) }}
                    @else
                        ৳ 0 (PAID)
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Summary for Packaging Verification -->
        <div class="items-box">
            <div class="section-label">CONTENTS ({{ $order->items->sum('quantity') }} items):</div>
            <ul>
                @foreach($order->items->take(3) as $item)
                    <li>• {{ $item->product_title }} (Qty: {{ $item->quantity }})</li>
                @endforeach
                @if($order->items->count() > 3)
                    <li>... and {{ $order->items->count() - 3 }} more item(s)</li>
                @endif
            </ul>
        </div>

        <!-- Sender / Return Details -->
        <div class="sender-box">
            <div>
                <strong>RETURN IF UNDELIVERED:</strong><br>
                Sorolata Heritage Guild, Sonargaon, Narayanganj.<br>
                Hotline: +880 1700-000000 • Web: sorolata.com
            </div>
            <div style="text-align: right; font-family: monospace;">
                #{{ $order->order_number }}<br>
                {{ $order->created_at->format('d/m/Y') }}
            </div>
        </div>
    </div>

</body>
</html>
