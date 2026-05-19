<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->rootCategories()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    public function show(string $slug)
    {
        $category = Category::with(['children', 'products' => function ($q) {
                $q->active()->latest()->limit(20);
            }])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($category);
    }
}
