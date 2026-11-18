<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation #{{ $order->order_number }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #0b0d12; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e2e8f0; }
        .wrapper { max-width: 600px; margin: 40px auto; background-color: #12151c; border-radius: 16px; border: 1px solid #232936; overflow: hidden; }
        .header { background: linear-gradient(135deg, #1b3827 0%, #0d1a12 100%); padding: 36px 32px; text-align: center; border-bottom: 1px solid #232936; }
        .logo { font-size: 24px; font-weight: 700; letter-spacing: 0.15em; color: #ffffff; text-transform: uppercase; margin: 0; }
        .badge { display: inline-block; margin-top: 12px; padding: 4px 14px; background: rgba(212, 175, 55, 0.15); color: #d4af37; border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 9999px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .content { padding: 32px; }
        .greeting { font-size: 18px; color: #ffffff; margin-bottom: 8px; font-weight: 600; }
        .subtitle { font-size: 13px; color: #94a3b8; line-height: 1.6; margin-bottom: 24px; }
        .order-meta { background-color: #181d27; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; border: 1px solid #28303f; display: flex; justify-content: space-between; }
        .meta-col { flex: 1; }
        .meta-label { font-size: 10px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.05em; }
        .meta-val { font-size: 13px; color: #f1f5f9; font-weight: 600; margin-top: 4px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .items-table th { text-align: left; font-size: 11px; text-transform: uppercase; color: #64748b; padding-bottom: 10px; border-bottom: 1px solid #232936; }
        .items-table td { padding: 12px 0; border-bottom: 1px solid #1c222e; font-size: 13px; }
        .totals-table { width: 100%; margin-bottom: 28px; }
        .totals-table td { padding: 6px 0; font-size: 13px; color: #94a3b8; }
        .totals-table .grand { font-size: 16px; color: #ffffff; font-weight: 700; border-top: 1px solid #2a3344; padding-top: 12px; }
        .btn { display: block; text-align: center; background-color: #214E34; color: #ffffff; padding: 14px 24px; border-radius: 10px; text-decoration: none; font-size: 13px; font-weight: 600; letter-spacing: 0.05em; margin: 28px 0; }
        .btn:hover { background-color: #2b6845; }
        .footer { padding: 24px 32px; background-color: #0c0e13; text-align: center; border-top: 1px solid #1c222e; font-size: 11px; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1 class="logo">SOROLATA</h1>
            <div class="badge">Artisan Heirloom Provenance</div>
        </div>

        <div class="content">
            <div class="greeting">ধন্যবাদ, {{ $order->customer_name }}!</div>
            <p class="subtitle">
                আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে। আমাদের কারিগর ও কিউরেটর দল আপনার ঐতিহ্যবাহী পণ্যটি যত্নের সাথে প্রস্তুত করছে।
            </p>

            <div class="order-meta">
                <div class="meta-col">
                    <div class="meta-label">Order Number</div>
                    <div class="meta-val">#{{ $order->order_number }}</div>
                </div>
                <div class="meta-col">
                    <div class="meta-label">Payment Method</div>
                    <div class="meta-val">{{ strtoupper($order->payment_gateway?->value ?? 'MANUAL') }}</div>
                </div>
                <div class="meta-col">
                    <div class="meta-label">Status</div>
                    <div class="meta-val">{{ $order->status->label() }}</div>
                </div>
            </div>

            <!-- Items -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item & Artisan</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #ffffff;">{{ $item->product_title }}</div>
                            @if($item->artisan_name)
                                <div style="font-size: 11px; color: #d4af37;">By {{ $item->artisan_name }}</div>
                            @endif
                        </td>
                        <td style="text-align: center; color: #94a3b8;">{{ $item->quantity }}</td>
                        <td style="text-align: right; color: #ffffff; font-weight: 600;">৳ {{ number_format($item->subtotal, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Financials -->
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td style="text-align: right; color: #e2e8f0;">৳ {{ number_format($order->subtotal_amount, 0) }}</td>
                </tr>
                <tr>
                    <td>Delivery Charge ({{ $order->shipping_address['district'] ?? 'Standard' }})</td>
                    <td style="text-align: right; color: #e2e8f0;">৳ {{ number_format($order->shipping_fee, 0) }}</td>
                </tr>
                @if($order->advance_delivery_charge > 0)
                <tr>
                    <td style="color: #38bdf8;">Advance Delivery Charge Paid</td>
                    <td style="text-align: right; color: #38bdf8;">- ৳ {{ number_format($order->advance_delivery_charge, 0) }}</td>
                </tr>
                @endif
                <tr class="grand">
                    <td>Total Payable / Due</td>
                    <td style="text-align: right; color: #ffffff;">৳ {{ number_format($order->due_amount ?? $order->total_amount, 0) }}</td>
                </tr>
            </table>

            <!-- Delivery Address -->
            <div style="background-color: #151922; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; border: 1px solid #222938;">
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 4px;">Delivery Address</div>
                <div style="font-size: 13px; color: #cbd5e1;">{{ $order->shipping_address['address'] ?? ($order->shipping_address['line_1'] ?? 'Delivery Address') }}, {{ $order->shipping_address['district'] ?? ($order->shipping_address['city'] ?? 'Dhaka') }}</div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Phone: {{ $order->customer_phone }}</div>
            </div>

            <!-- CTA -->
            <a href="{{ url('/track-order?search=' . $order->order_number) }}" class="btn">
                🔍 Track Your Heirloom Live (লাইভ অর্ডার ট্র্যাক করুন)
            </a>
        </div>

        <div class="footer">
            Sorolata Heritage Crafts & Atelier • Support Hotline: +880 1700 000 000<br>
            Preserving indigenous Bangladeshi craftsmanship with pride.
        </div>
    </div>
</body>
</html>
