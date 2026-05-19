<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    /**
     * Handles payment gateway redirect callback logic.
     */
    public function handleCallback(Request $request)
    {
        $orderId = $request->query('order_id');
        $gateway = $request->query('gateway');
        $status = $request->query('status');

        $order = Order::findOrFail($orderId);

        if ($status === 'success') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);
            
            $order->payments()->update([
                'status' => 'completed',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed successfully',
                'order' => $order,
            ]);
        }

        $order->update([
            'payment_status' => 'failed',
        ]);
        
        $order->payments()->update([
            'status' => 'failed',
        ]);

        return response()->json([
            'status' => 'failed',
            'message' => 'Payment process failed',
            'order' => $order,
        ]);
    }
}
