<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_zip' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,bkash,nagad,sslcommerz',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $product->effective_price;
                $variantLabel = null;

                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::where('id', $item['variant_id'])->where('product_id', $product->id)->firstOrFail();
                    if ($variant->price) {
                        $price = $variant->price;
                    }
                    $variantLabel = trim(($variant->color ?? '') . ' ' . ($variant->size ?? ''));
                    
                    // Reduce variant stock
                    if ($variant->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name} - {$variantLabel}");
                    }
                    $variant->decrement('stock_quantity', $item['quantity']);
                    $product->decrement('stock_quantity', $item['quantity']);
                } else {
                    // Reduce product base stock
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }
                    $product->decrement('stock_quantity', $item['quantity']);
                }

                $itemTotal = $price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $product->name,
                    'variant_label' => $variantLabel,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ];
            }

            // Mock Shipping Cost (In a real app, query from Settings table)
            $shippingCost = strtolower($validated['shipping_city']) === 'dhaka' ? 60 : 120;
            $total = $subtotal + $shippingCost;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'tracking_number' => 'TRK-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => auth('sanctum')->id(), // Null for guests
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_zip' => $validated['shipping_zip'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $order->items()->createMany($orderItemsData);

            // Add Initial History
            $order->histories()->create([
                'status' => 'pending',
                'note' => 'Order has been placed successfully.'
            ]);

            DB::commit();

            // Notify Admins
            $admins = \App\Models\User::role(['Admin', 'Super Admin'])->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewOrderNotification($order));

            // Fire Mailables here if configured
            // Mail::to($order->customer_email)->send(new OrderPlaced($order));

            return response()->json([
                'message' => 'Order placed successfully',
                'order_number' => $order->order_number,
                'tracking_number' => $order->tracking_number,
                'total' => $order->total
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function track($order_number)
    {
        $order = Order::with('items')->where('order_number', $order_number)->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }
}
