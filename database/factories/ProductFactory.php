<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . rand(100, 999),
            'sku' => strtoupper(Str::random(8)),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'stock_quantity' => $this->faker->numberBetween(5, 50),
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
