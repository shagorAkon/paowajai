<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_district' => 'nullable|string|max:100',
            'shipping_division' => 'nullable|string|max:100',
            'shipping_zip' => 'nullable|string|max:10',
            'payment_method' => 'required|in:cod,bkash,nagad,sslcommerz',
            'coupon_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
            'source' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = (float) $product->effective_price;

                if (!empty($item['variant_id'])) {
                    $variant = $product->variants()->findOrFail($item['variant_id']);
                    if ($variant->price) {
                        $price = (float) $variant->price;
                    }
                }

                $itemTotal = $price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $product->name,
                    'variant_label' => isset($variant) ? $variant->label : null,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ];

                // Decrement stock
                $product->decrement('stock_quantity', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            $shippingCost = $this->calculateShipping($validated['shipping_division'] ?? '');
            $discount = 0;

            // Apply coupon if provided
            if (!empty($validated['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])->valid()->first();
                if ($coupon) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $coupon->increment('used_count');
                }
            }

            $total = $subtotal + $shippingCost - $discount;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $request->user()?->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'] ?? null,
                'shipping_district' => $validated['shipping_district'] ?? null,
                'shipping_division' => $validated['shipping_division'] ?? null,
                'shipping_zip' => $validated['shipping_zip'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $validated['coupon_code'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'notes' => $validated['notes'] ?? null,
                'source' => $validated['source'] ?? 'website',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Create initial payment record
            $payment = $order->payments()->create([
                'method' => $validated['payment_method'],
                'amount' => $total,
                'currency' => 'BDT',
                'status' => 'pending',
            ]);

            $paymentUrl = null;
            if ($validated['payment_method'] !== 'cod') {
                $gatewayService = app(\App\Services\Payment\PaymentGatewayService::class);
                $initiation = $gatewayService->initiatePayment($order, $validated['payment_method']);
                if ($initiation['success']) {
                    $paymentUrl = $initiation['redirect_url'];
                    $payment->update([
                        'transaction_id' => $initiation['payment_id'] ?? null,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items'),
                'payment_url' => $paymentUrl,
            ], 201);
        });
    }

    private function calculateShipping(?string $division): float
    {
        // Bangladesh shipping rates
        $insideDhaka = 60.00;
        $outsideDhaka = 120.00;

        if (strtolower($division ?? '') === 'dhaka') {
            return $insideDhaka;
        }
        return $outsideDhaka;
    }
}
