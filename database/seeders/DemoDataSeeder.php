<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'nisharulnirob@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('nirob@9564'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Super Admin');

        // Create Categories
        $categories = [
            ['name' => 'Football Jerseys', 'slug' => 'football-jerseys', 'is_featured' => true, 'sort_order' => 1, 'icon' => '⚽'],
            ['name' => 'Electronics Gadgets', 'slug' => 'electronics-gadgets', 'is_featured' => true, 'sort_order' => 2, 'icon' => '📱'],
            ['name' => 'Mini Projectors', 'slug' => 'mini-projectors', 'is_featured' => true, 'sort_order' => 3, 'icon' => '📽️'],
            ['name' => 'Makeup Items', 'slug' => 'makeup-items', 'is_featured' => true, 'sort_order' => 4, 'icon' => '💄'],
            ['name' => 'Fashion Products', 'slug' => 'fashion-products', 'is_featured' => true, 'sort_order' => 5, 'icon' => '👗'],
            ['name' => 'Trending Products', 'slug' => 'trending-products', 'is_featured' => true, 'sort_order' => 6, 'icon' => '🔥'],
            ['name' => 'Seasonal Campaigns', 'slug' => 'seasonal-campaigns', 'is_featured' => false, 'sort_order' => 7, 'icon' => '🎉'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Create Sample Products
        $jerseys = Category::where('slug', 'football-jerseys')->first();
        $electronics = Category::where('slug', 'electronics-gadgets')->first();
        $projectors = Category::where('slug', 'mini-projectors')->first();
        $makeup = Category::where('slug', 'makeup-items')->first();

        $sampleProducts = [
            [
                'category_id' => $jerseys->id,
                'name' => 'Argentina World Cup Jersey 2026',
                'slug' => 'argentina-world-cup-jersey-2026',
                'short_description' => 'Official replica Argentina national team jersey for FIFA World Cup 2026.',
                'description' => '<p>Premium quality replica jersey made from breathable polyester fabric. Features the iconic sky blue and white stripes with AFA crest. Perfect for fans and collectors.</p>',
                'price' => 1200.00,
                'compare_price' => 1800.00,
                'cost_price' => 450.00,
                'stock_quantity' => 150,
                'is_active' => true,
                'is_featured' => true,
                'sku' => 'JER-ARG-2026',
            ],
            [
                'category_id' => $jerseys->id,
                'name' => 'Brazil Away Jersey 2026',
                'slug' => 'brazil-away-jersey-2026',
                'short_description' => 'Stylish away jersey of the Brazilian national team.',
                'price' => 1100.00,
                'compare_price' => 1600.00,
                'cost_price' => 400.00,
                'stock_quantity' => 120,
                'is_active' => true,
                'is_featured' => true,
                'sku' => 'JER-BRA-AWAY-2026',
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'Wireless Bluetooth Earbuds Pro',
                'slug' => 'wireless-bluetooth-earbuds-pro',
                'short_description' => 'Premium ANC earbuds with 40hr battery life and crystal-clear audio.',
                'price' => 2500.00,
                'compare_price' => 3500.00,
                'cost_price' => 800.00,
                'stock_quantity' => 200,
                'is_active' => true,
                'is_featured' => true,
                'sku' => 'ELEC-EAR-PRO-01',
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'Smart Watch Ultra Fitness Band',
                'slug' => 'smart-watch-ultra-fitness-band',
                'short_description' => 'Full HD AMOLED display smartwatch with heart rate, SpO2, and GPS tracking.',
                'price' => 3200.00,
                'compare_price' => 4500.00,
                'cost_price' => 1200.00,
                'stock_quantity' => 80,
                'is_active' => true,
                'is_featured' => true,
                'sku' => 'ELEC-SW-ULTRA-01',
            ],
            [
                'category_id' => $projectors->id,
                'name' => 'Mini Portable Projector 4K',
                'slug' => 'mini-portable-projector-4k',
                'short_description' => 'Compact 4K projector for home cinema, gaming, and presentations.',
                'price' => 8500.00,
                'compare_price' => 12000.00,
                'cost_price' => 3500.00,
                'stock_quantity' => 30,
                'is_active' => true,
                'is_featured' => true,
                'is_flash_sale' => true,
                'flash_sale_price' => 6999.00,
                'flash_sale_start' => now(),
                'flash_sale_end' => now()->addDays(7),
                'sku' => 'PROJ-MINI-4K-01',
            ],
            [
                'category_id' => $makeup->id,
                'name' => 'Luxury Matte Lipstick Set (6 Shades)',
                'slug' => 'luxury-matte-lipstick-set-6-shades',
                'short_description' => 'Long-lasting matte lipstick collection with 6 trendy shades.',
                'price' => 850.00,
                'compare_price' => 1200.00,
                'cost_price' => 300.00,
                'stock_quantity' => 300,
                'is_active' => true,
                'is_featured' => true,
                'sku' => 'MKP-LIP-SET-06',
            ],
        ];

        foreach ($sampleProducts as $prod) {
            $product = Product::firstOrCreate(['slug' => $prod['slug']], $prod);

            // Add sample variants for jerseys
            if ($prod['category_id'] === $jerseys->id) {
                $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                foreach ($sizes as $size) {
                    $product->variants()->firstOrCreate(
                        ['product_id' => $product->id, 'size' => $size],
                        ['size' => $size, 'stock_quantity' => rand(10, 50)]
                    );
                }
            }
        }

        // Create Banners
        Banner::firstOrCreate(
            ['title' => 'Grand Opening Sale'],
            [
                'title' => 'Grand Opening Sale',
                'subtitle' => 'Up to 50% off on all products!',
                'image' => 'banners/hero-1.jpg',
                'link' => '/products?featured=1',
                'button_text' => 'Shop Now',
                'position' => 'hero',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Banner::firstOrCreate(
            ['title' => 'World Cup Collection'],
            [
                'title' => 'World Cup Collection',
                'subtitle' => 'Get your team jersey before the match!',
                'image' => 'banners/hero-2.jpg',
                'link' => '/category/football-jerseys',
                'button_text' => 'Explore',
                'position' => 'hero',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );
    }
}
