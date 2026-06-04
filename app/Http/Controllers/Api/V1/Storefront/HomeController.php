<?php

namespace App\Http\Controllers\Api\V1\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $homeData = \Illuminate\Support\Facades\Cache::remember('storefront_home_data', 600, function () {
            $heroBanners = Banner::active()
                ->position('hero')
                ->orderBy('sort_order')
                ->get();

            $popupBanner = Banner::active()
                ->position('popup')
                ->first();

            $featuredProducts = \App\Models\Product::with('images')
                ->active()
                ->featured()
                ->latest()
                ->limit(8)
                ->get();

            $flashSaleProducts = \App\Models\Product::with('images')
                ->active()
                ->flashSale()
                ->limit(8)
                ->get();

            $categories = \App\Models\Category::active()
                ->orderBy('sort_order')
                ->limit(8)
                ->get();

            $newArrivals = \App\Models\Product::with('images')
                ->active()
                ->latest()
                ->limit(8)
                ->get();

            return [
                'banners' => $heroBanners,
                'popup' => $popupBanner,
                'featured_products' => $featuredProducts,
                'flash_sale_products' => $flashSaleProducts,
                'categories' => $categories,
                'new_arrivals' => $newArrivals,
            ];
        });

        return response()->json($homeData);
    }
}
