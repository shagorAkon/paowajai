<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful checkout with COD.
     */
    public function test_checkout_successful_with_cash_on_delivery(): void
    {
        // 1. Create Product
        $product = Product::factory()->create([
            'name' => 'Premium Football Jersey',
            'price' => 1000.00,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        // 2. Perform Checkout
        $response = $this->postJson('/api/v1/storefront/checkout', [
            'customer_name' => 'Sagor Akon',
            'customer_phone' => '01712345678',
            'shipping_address' => 'Mirpur, Dhaka',
            'shipping_division' => 'Dhaka',
            'shipping_city' => 'Dhaka',
            'payment_method' => 'cod',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        // 3. Assertions
        $response->assertStatus(201);
        $response->assertJsonPath('order.customer_name', 'Sagor Akon');
        
        // Assert Subtotal (1000 * 2) = 2000
        $response->assertJsonPath('order.subtotal', '2000.00');
        
        // Assert Shipping (Dhaka) = 60
        $response->assertJsonPath('order.shipping_cost', '60.00');
        
        // Assert Total = 2060
        $response->assertJsonPath('order.total', '2060.00');

        // Assert Stock Decremented
        $this->assertEquals(8, $product->fresh()->stock_quantity);
    }

    /**
     * Test checkout applying a percentage coupon discount.
     */
    public function test_checkout_applying_valid_coupon(): void
    {
        // 1. Create Product & Coupon
        $product = Product::factory()->create([
            'name' => 'Mini Projector',
            'price' => 5000.00,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $coupon = Coupon::create([
            'code' => 'PROMO10',
            'type' => 'percentage',
            'value' => 10.00, // 10%
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
            'is_active' => true,
            'usage_limit' => 100,
        ]);

        // 2. Perform Checkout with Coupon
        $response = $this->postJson('/api/v1/storefront/checkout', [
            'customer_name' => 'Test Customer',
            'customer_phone' => '01812345678',
            'shipping_address' => 'Halishahar, Chittogram',
            'shipping_division' => 'Chattogram',
            'shipping_city' => 'Chittogram',
            'payment_method' => 'cod',
            'coupon_code' => 'PROMO10',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ]
        ]);

        // 3. Assertions
        $response->assertStatus(201);
        
        // Assert Subtotal = 5000
        $response->assertJsonPath('order.subtotal', '5000.00');
        
        // Assert 10% discount = 500
        $response->assertJsonPath('order.discount', '500.00');
        
        // Assert Shipping (Outside Dhaka) = 120
        $response->assertJsonPath('order.shipping_cost', '120.00');
        
        // Assert Total = 5000 + 120 - 500 = 4620
        $response->assertJsonPath('order.total', '4620.00');
    }
}
