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
        $pdf = Pdf::loadView('invoices.order', $data);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
