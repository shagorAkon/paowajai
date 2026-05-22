<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #111; font-size: 32px; }
        .details-container { width: 100%; margin-bottom: 30px; }
        .details-container td { vertical-align: top; }
        .text-right { text-align: right; }
        .items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-weight: bold; }
        .items td { padding: 12px; border-bottom: 1px solid #eee; }
        .totals { width: 40%; margin-top: 20px; float: right; border-collapse: collapse; }
        .totals td { padding: 8px 12px; }
        .totals-total { border-top: 2px solid #333; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table style="width: 100%; margin-bottom: 30px;">
            <tr>
                <td style="vertical-align: top;">
                    <h1 style="margin: 0; color: #111; font-size: 32px;">INVOICE</h1>
                    <p style="margin: 5px 0 0 0; color: #555;">{{ $company_name }}<br>{{ $company_address }}</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <img src="{{ $qrCode }}" width="90" alt="QR Code">
                </td>
            </tr>
        </table>

        <table class="details-container">
            <tr>
                <td width="50%">
                    <strong style="color: #555; text-transform: uppercase; font-size: 12px;">Bill To:</strong><br>
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_zip ?? '' }}<br>
                    Phone: {{ $order->customer_phone }}
                </td>
                <td width="50%" class="text-right">
                    <strong style="color: #555; text-transform: uppercase; font-size: 12px;">Order Details:</strong><br>
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
                    <strong>Status:</strong> <span style="text-transform: uppercase;">{{ $order->status }}</span><br>
                    <strong>Payment:</strong> <span style="text-transform: uppercase;">{{ $order->payment_method }}</span>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Rate</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_label)
                        <br><span style="color: #777; font-size: 12px;">Variant: {{ $item->variant_label }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">৳ {{ number_format($item->price, 2) }}</td>
                    <td style="text-align: right;">৳ {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="clear: both;"></div>

        <table class="totals">
            <tr>
                <td class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right">৳ {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Shipping:</strong></td>
                <td class="text-right">৳ {{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right totals-total">Total:</td>
                <td class="text-right totals-total">৳ {{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <div class="footer">
            <p>Thank you for your business!<br>
            If you have any questions about this invoice, please contact us at {{ $company_email }} or {{ $company_phone }}.</p>
        </div>
    </div>
</body>
</html>
