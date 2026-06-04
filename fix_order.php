<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = App\Models\Order::find(1);
if ($order) {
    foreach($order->items as $item) {
        $product = App\Models\Product::find($item->product_id);
        if ($product) {
            $item->price = $product->effective_price;
            $item->total = $product->effective_price * $item->quantity;
            $item->save();
        }
    }
    $order->subtotal = $order->items->sum('total');
    $order->total = $order->subtotal + $order->shipping_cost;
    $order->save();
    echo "Order updated. New total: " . $order->total . "\n";
} else {
    echo "Order 1 not found.\n";
}
