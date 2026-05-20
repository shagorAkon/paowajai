<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        $order->load(['items.product', 'user']);

        // Generate base64 QR code for PDF embedding
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($order->order_number));

        // Create the view data
        $data = [
            'order' => $order,
            'qrCode' => 'data:image/svg+xml;base64,' . $qrCode,
            'company_name' => 'Paowajai Ecommerce',
            'company_address' => 'Dhaka, Bangladesh',
            'company_phone' => '+880 123 456 7890',
            'company_email' => 'support@paowajai.com'
        ];

        // Normally we'd render a view here, but since this is an API, we can either
        // return a base64 encoded PDF string, or directly download the file.
        // We'll generate HTML inline for simplicity in this artifact, or use a blade file.
        
        $html = $this->getInvoiceHtml($data);
        $pdf = Pdf::loadHTML($html);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    private function getInvoiceHtml($data)
    {
        $order = $data['order'];
        $itemsHtml = '';
        foreach($order->items as $item) {
            $variant = $item->variant_label ? " ({$item->variant_label})" : '';
            $itemsHtml .= "
                <tr>
                    <td style='padding: 10px; border-bottom: 1px solid #ddd;'>{$item->product_name}{$variant}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: center;'>{$item->quantity}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>৳ {$item->price}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #ddd; text-align: right;'>৳ {$item->total}</td>
                </tr>
            ";
        }

        return "
        <html>
        <head>
            <style>
                body { font-family: 'Helvetica', sans-serif; color: #333; }
                .header { text-align: center; margin-bottom: 40px; }
                .details { width: 100%; margin-bottom: 30px; }
                .details td { vertical-align: top; }
                .items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                .items th { background: #f8f9fa; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
                .totals { width: 100%; margin-top: 20px; }
                .totals td { padding: 5px 10px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>INVOICE</h1>
                <p>{$data['company_name']}<br>{$data['company_address']}</p>
            </div>
            <table class='details'>
                <tr>
                    <td width='50%'>
                        <strong>Bill To:</strong><br>
                        {$order->customer_name}<br>
                        {$order->shipping_address}, {$order->shipping_city}<br>
                        Phone: {$order->customer_phone}
                    </td>
                    <td width='50%' style='text-align: right;'>
                        <strong>Invoice #:</strong> {$order->order_number}<br>
                        <strong>Date:</strong> {$order->created_at->format('d M Y')}<br>
                        <strong>Status:</strong> ".ucfirst($order->status)."<br>
                        <strong>Payment:</strong> ".strtoupper($order->payment_method)."
                        <br><br>
                        <img src='{$data['qrCode']}' width='80'>
                    </td>
                </tr>
            </table>
            <table class='items'>
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th style='text-align: center;'>Qty</th>
                        <th style='text-align: right;'>Rate</th>
                        <th style='text-align: right;'>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>
            <table class='totals' align='right' style='width: 40%;'>
                <tr>
                    <td style='text-align: right;'><strong>Subtotal:</strong></td>
                    <td style='text-align: right;'>৳ {$order->subtotal}</td>
                </tr>
                <tr>
                    <td style='text-align: right;'><strong>Shipping:</strong></td>
                    <td style='text-align: right;'>৳ {$order->shipping_cost}</td>
                </tr>
                <tr>
                    <td style='text-align: right; border-top: 2px solid #333; font-size: 1.2em;'><strong>Total:</strong></td>
                    <td style='text-align: right; border-top: 2px solid #333; font-size: 1.2em;'><strong>৳ {$order->total}</strong></td>
                </tr>
            </table>
        </body>
        </html>
        ";
    }
}
