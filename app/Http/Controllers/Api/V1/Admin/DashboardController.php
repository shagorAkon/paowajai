<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::lowStock()->count();

        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        return response()->json([
            'total_revenue' => $totalRevenue,
            'today_revenue' => $todayRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'total_customers' => $totalCustomers,
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
        ]);
    }

    public function revenueChart(Request $request)
    {
        $days = $request->get('days', 30);

        $revenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($revenue);
    }

    public function topProducts()
    {
        $products = Product::orderBy('sold_count', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'thumbnail', 'price', 'sold_count', 'stock_quantity']);

        return response()->json($products);
    }

    public function recentOrders()
    {
        $orders = Order::with('user')
            ->recent()
            ->limit(10)
            ->get();

        return response()->json($orders);
    }

    public function orderStatusBreakdown()
    {
        $breakdown = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json($breakdown);
    }
}
