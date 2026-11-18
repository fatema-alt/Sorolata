<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Archival Certificate & Invoice #{{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; background: #fff; color: #1d1d1d; margin: 0; padding: 40px; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #e8e8e8; padding: 48px; border-radius: 12px; }
        .heading { font-family: 'Cormorant Garamond', serif; font-size: 32px; margin: 0 0 6px 0; font-weight: normal; }
        .subheading { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #214e34; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #777; border-bottom: 1px solid #e8e8e8; padding: 12px 8px; }
        td { padding: 14px 8px; border-bottom: 1px solid #f2f2f2; font-size: 13px; }
        .text-right { text-align: right; }
        .seal { border: 2px solid #214e34; padding: 12px 24px; border-radius: 999px; display: inline-block; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #214e34; font-weight: bold; }
        @media print {
            body { padding: 0; }
            .container { border: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 800px; margin: 0 auto 20px auto; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #214e34; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            Print Archival Document
        </button>
    </div>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e8e8e8; padding-bottom: 30px;">
            <div>
                <h1 class="heading">Sorolata</h1>
                <div class="subheading">Master Artisan Guild Provenance Ledger</div>
                <div style="font-size: 12px; color: #666; margin-top: 6px;">
                    Imperial Bengal Commerce House • Sonargaon, Narayanganj, Bangladesh
                </div>
            </div>
            <div style="text-align: right;">
                <div class="seal">Archival Certified</div>
                <div style="font-size: 12px; color: #555; margin-top: 8px;">Order #{{ $order->order_number }}</div>
                <div style="font-size: 11px; color: #888;">{{ $order->created_at->format('F d, Y') }}</div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 30px; font-size: 12px; line-height: 1.6;">
            <div>
                <strong style="text-transform: uppercase; letter-spacing: 1px; font-size: 10px; color: #888;">Collector / Consignee:</strong>
                <div>{{ $order->customer_name }}</div>
                <div>{{ $order->customer_phone }}</div>
                <div>{{ $order->customer_email }}</div>
            </div>
            <div style="text-align: right;">
                <strong style="text-transform: uppercase; letter-spacing: 1px; font-size: 10px; color: #888;">Destination:</strong>
                <div>{{ $order->shipping_address['line_1'] ?? ($order->shipping_address['address'] ?? 'Gulshan-2') }}</div>
                <div>{{ $order->shipping_address['city'] ?? 'Dhaka' }}, Bangladesh</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Heirloom Artifact & Provenance</th>
                    <th>SKU</th>
                    <th class="text-right">Price</th>
                    <th style="text-align: center;">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_title }}</strong>
                            <div style="font-size: 11px; color: #666;">Guild: {{ $item->artisan_name ?? 'Bengal Atelier' }}</div>
                        </td>
                        <td style="font-family: monospace;">{{ $item->sku }}</td>
                        <td class="text-right">৳ {{ number_format($item->unit_price, 0) }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td class="text-right"><strong>৳ {{ number_format($item->subtotal, 0) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
            <div style="width: 250px; font-size: 13px; line-height: 2;">
                <div style="display: flex; justify-content: space-between; color: #666;">
                    <span>Subtotal:</span>
                    <span>৳ {{ number_format($order->subtotal_amount, 0) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; color: #666;">
                    <span>Insured Courier:</span>
                    <span>৳ {{ number_format($order->shipping_fee, 0) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; border-top: 2px solid #1d1d1d; padding-top: 6px; margin-top: 6px;">
                    <span>Total Acquisition:</span>
                    <span>৳ {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 60px; padding-top: 20px; border-top: 1px dashed #ccc; text-align: center; font-size: 11px; color: #777;">
            This document certifies that the artifacts above were handspun and chiseled by recognized master artisans using verified geographical indication materials.
        </div>
    </div>
</body>
</html>
