<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        if (!$query) {
            return response()->json(['message' => 'Query is required'], 400);
        }

        $trimmed = trim($query);

        // 1. Try exact match by order_number first
        $order = Order::with(['items.product', 'histories'])
            ->where('order_number', $trimmed)
            ->first();

        if ($order) {
            return response()->json(['type' => 'single', 'order' => $order]);
        }

        // 2. Try exact match by tracking_number
        $order = Order::with(['items.product', 'histories'])
            ->where('tracking_number', $trimmed)
            ->first();

        if ($order) {
            return response()->json(['type' => 'single', 'order' => $order]);
        }

        // 3. Try match by phone number — may return multiple orders
        $orders = Order::with(['items.product', 'histories'])
            ->where('customer_phone', $trimmed)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->count() === 1) {
            return response()->json(['type' => 'single', 'order' => $orders->first()]);
        }

        if ($orders->count() > 1) {
            // Return a list of order summaries for the customer to pick from
            $summaries = $orders->map(function ($o) {
                return [
                    'id' => $o->id,
                    'order_number' => $o->order_number,
                    'total' => $o->total,
                    'status' => $o->status,
                    'created_at' => $o->created_at,
                ];
            });
            return response()->json(['type' => 'multiple', 'orders' => $summaries]);
        }

        return response()->json(['message' => 'Order not found. Please check your order number, tracking ID, or phone number.'], 404);
    }

    public function downloadInvoice(Request $request, $order_number)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $order_number)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Generate base64 QR code for PDF embedding
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($order->order_number));

        // Create the view data
        $data = [
            'order' => $order,
            'qrCode' => 'data:image/svg+xml;base64,' . $qrCode,
            'company_name' => 'Paowajai Ecommerce',
            'company_address' => 'Dhaka, Bangladesh',
            'company_phone' => '+880 123 456 7890',
            'company_email' => 'support@paowajai.com'
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order', $data);
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
