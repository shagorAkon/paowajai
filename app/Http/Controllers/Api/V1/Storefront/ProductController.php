<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->active();

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('featured')) {
            $query->featured();
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $allowedSorts = ['price', 'name', 'created_at', 'sold_count', 'view_count'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        }

        return response()->json(
            $query->paginate($request->get('per_page', 12))
        );
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'images', 'variants' => function ($q) {
                $q->where('is_active', true);
            }])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('view_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(8)
            ->get(['id', 'name', 'slug', 'price', 'compare_price', 'thumbnail']);

        return response()->json([
            'product' => $product,
            'related' => $relatedProducts,
        ]);
    }

    public function featured()
    {
        $products = Product::with('images')
            ->active()
            ->featured()
            ->latest()
            ->limit(12)
            ->get();

        return response()->json($products);
    }

    public function flashSale()
    {
        $products = Product::with('images')
            ->active()
            ->flashSale()
            ->get();

        return response()->json($products);
    }
}
