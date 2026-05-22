<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->get('type', 'sales'); // sales, revenue, customers, inventory
        $startDate = $request->get('start_date', now()->subDays(30)->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());

        $query = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc');

        if ($type === 'revenue') {
            $query->where('payment_status', 'paid');
        }

        $orders = $query->get();

        $csvHeader = ['Order ID', 'Date', 'Customer', 'Status', 'Payment', 'Total'];
        
        $callback = function () use ($orders, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->customer_name,
                    $order->status,
                    $order->payment_status,
                    $order->total
                ]);
            }
            fclose($file);
        };

        $filename = "{$type}_report_" . date('Ymd_His') . ".csv";

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
