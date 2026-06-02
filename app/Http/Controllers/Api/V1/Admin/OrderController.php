<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->recent()->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user', 'payments']);
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,returned,refunded',
            'note' => 'nullable|string'
        ]);

        if ($order->status !== $validated['status']) {
            $order->update(['status' => $validated['status']]);
            
            $order->histories()->create([
                'status' => $validated['status'],
                'note' => $validated['note'] ?? 'Status updated to ' . $validated['status'] . '.'
            ]);
        }

        return response()->json($order);
    }

    public function updateItemStatus(Request $request, Order $order, $itemId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,shipped,rejected',
        ]);

        $item = $order->items()->findOrFail($itemId);
        $oldStatus = $item->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== $newStatus) {
            // Handle stock and price changes when moving TO rejected
            if ($newStatus === 'rejected') {
                $order->subtotal -= $item->total;
                $order->total -= $item->total;
                $order->save();

                if ($item->product_variant_id) {
                    \App\Models\ProductVariant::where('id', $item->product_variant_id)->increment('stock_quantity', $item->quantity);
                }
                \App\Models\Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            } 
            // Handle stock and price changes when moving FROM rejected
            else if ($oldStatus === 'rejected') {
                $order->subtotal += $item->total;
                $order->total += $item->total;
                $order->save();

                if ($item->product_variant_id) {
                    \App\Models\ProductVariant::where('id', $item->product_variant_id)->decrement('stock_quantity', $item->quantity);
                }
                \App\Models\Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
            }

            $item->update(['status' => $newStatus]);
        }

        return response()->json($order->load(['items.product', 'items.variant', 'user', 'payments']));
    }

    public function updateTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:255',
            'courier' => 'required|string|in:pathao,redx,steadfast,other',
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
