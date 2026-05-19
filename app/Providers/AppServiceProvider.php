<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $clearCache = function () {
            \Illuminate\Support\Facades\Cache::forget('storefront_home_data');
        };

        \App\Models\Product::saved($clearCache);
        \App\Models\Product::deleted($clearCache);

        \App\Models\Category::saved($clearCache);
        \App\Models\Category::deleted($clearCache);

        \App\Models\Banner::saved($clearCache);
        \App\Models\Banner::deleted($clearCache);
    }
}
