<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- Auth Controllers ---
use App\Http\Controllers\Api\V1\AuthController;

// --- Storefront Controllers ---
use App\Http\Controllers\Api\V1\Storefront\HomeController;
use App\Http\Controllers\Api\V1\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\Api\V1\Storefront\CategoryController as StorefrontCategoryController;
use App\Http\Controllers\Api\V1\Storefront\CheckoutController;

// --- Admin Controllers ---
use App\Http\Controllers\Api\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\V1\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\BannerController;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ========================================
    // Authentication
    // ========================================
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // ========================================
    // Public Storefront Routes
    // ========================================
    Route::prefix('storefront')->group(function () {
        Route::get('/home', [HomeController::class, 'index']);
        Route::get('/categories', [StorefrontCategoryController::class, 'index']);
        Route::get('/categories/{slug}', [StorefrontCategoryController::class, 'show']);
        Route::get('/products', [StorefrontProductController::class, 'index']);
        Route::get('/products/featured', [StorefrontProductController::class, 'featured']);
        Route::get('/products/flash-sale', [StorefrontProductController::class, 'flashSale']);
        Route::get('/products/{slug}', [StorefrontProductController::class, 'show']);
        // Orders & Tracking
        Route::post('/checkout', [\App\Http\Controllers\Api\V1\Storefront\CheckoutController::class, 'store']);
        Route::get('/track-order/{order_number}', [\App\Http\Controllers\Api\V1\Storefront\CheckoutController::class, 'track']);
        Route::get('/tracking/search', [\App\Http\Controllers\Api\V1\Storefront\TrackingController::class, 'search']);
        Route::get('/tracking/{order_number}/invoice', [\App\Http\Controllers\Api\V1\Storefront\TrackingController::class, 'downloadInvoice']);
        Route::get('/payment/callback', [\App\Http\Controllers\Api\V1\Storefront\PaymentCallbackController::class, 'handleCallback']);
    });

    // ========================================
    // Admin Routes (Authenticated + Admin Role)
    // ========================================
    Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {

        // Dashboard
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/revenue-chart', [DashboardController::class, 'revenueChart']);
        Route::get('/dashboard/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/dashboard/recent-orders', [DashboardController::class, 'recentOrders']);
        Route::get('/dashboard/order-status-breakdown', [DashboardController::class, 'orderStatusBreakdown']);
        Route::get('/dashboard/notifications', [DashboardController::class, 'notifications']);
        Route::post('/dashboard/notifications/{id}/read', [DashboardController::class, 'markNotificationAsRead']);
        Route::get('/dashboard/reports/export', [\App\Http\Controllers\Api\V1\Admin\ReportController::class, 'export']);

        // Categories
        Route::apiResource('/categories', AdminCategoryController::class);

        // Products
        Route::apiResource('/products', AdminProductController::class);

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);
        Route::patch('/orders/{order}/items/{item}/status', [AdminOrderController::class, 'updateItemStatus']);
        Route::patch('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking']);
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy']);
        Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Api\V1\Admin\InvoiceController::class, 'download']);

        // Settings
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index']);
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update']);

        // Banners (Customize Home Page)
        Route::get('/banners', [BannerController::class, 'index']);
        Route::post('/banners', [BannerController::class, 'store']);
        Route::post('/banners/{id}', [BannerController::class, 'update']); // Using POST for file upload updates
        Route::delete('/banners/{id}', [BannerController::class, 'destroy']);
    });
});
