<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variants']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }
        if ($request->filled('low_stock')) {
            $query->lowStock();
        }

        $products = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku|max:100',
            'barcode' => 'nullable|string|unique:products,barcode|max:100',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:10240',
            'video_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
            'flash_sale_start' => 'nullable|date',
            'flash_sale_end' => 'nullable|date|after:flash_sale_start',
            'flash_sale_price' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'gallery.*' => 'nullable|image|max:10240',
            'variants' => 'nullable|array',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.material' => 'nullable|string|max:50',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $product = Product::create($validated);

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $image->store('products/gallery', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        // Handle variants
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variant) {
                $product->variants()->create($variant);
            }
        }

        \Illuminate\Support\Facades\Cache::forget('storefront_home_data');

        return response()->json($product->load(['images', 'variants', 'category']), 201);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants']);
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:10240',
            'video_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
            'flash_sale_start' => 'nullable|date',
            'flash_sale_end' => 'nullable|date|after:flash_sale_start',
            'flash_sale_price' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.material' => 'nullable|string|max:50',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed during product update: ', $e->errors());
            throw $e;
        }

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        }

        // Handle booleans correctly from FormData string
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_flash_sale'] = $request->boolean('is_flash_sale');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Exclude variants from base product update
        $productData = collect($validated)->except(['variants'])->toArray();
        $product->update($productData);

        // Handle variants
        if ($request->has('variants')) {
            // Get existing variant IDs
            $existingVariantIds = $product->variants()->pluck('id')->toArray();
            $updatedVariantIds = [];

            if (!empty($validated['variants'])) {
                foreach ($validated['variants'] as $variantData) {
                    if (isset($variantData['id']) && in_array($variantData['id'], $existingVariantIds)) {
                        $product->variants()->where('id', $variantData['id'])->update($variantData);
                        $updatedVariantIds[] = $variantData['id'];
                    } else {
                        $newVariant = $product->variants()->create($variantData);
                        $updatedVariantIds[] = $newVariant->id;
                    }
                }
            }

            // Delete variants that were removed
            $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
            if (!empty($variantsToDelete)) {
                $product->variants()->whereIn('id', $variantsToDelete)->delete();
            }
        }

        \Illuminate\Support\Facades\Cache::forget('storefront_home_data');

        return response()->json($product->load(['images', 'variants', 'category']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        \Illuminate\Support\Facades\Cache::forget('storefront_home_data');

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
