<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    // Thống kê doanh số
    public function index()
    {
        // Doanh thu theo tháng (6 tháng gần nhất)
        $revenueByMonth = Order::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as revenue')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top 10 sản phẩm bán chạy nhất
        $topProducts = Product::getTopSelling(10);

        // Tổng doanh thu
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        // Tổng đơn hàng
        $totalOrders = Order::count();

        // Tổng sản phẩm bán ra
        $totalItemsSold = Order::with('details')
            ->where('payment_status', 'paid')
            ->get()
            ->sum(function($order) {
                return $order->details->sum('quantity');
            });

        // Doanh thu theo phương thức thanh toán
        $revenueByPaymentMethod = Order::where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as revenue')
            ->groupBy('payment_method')
            ->get();

        // Trạng thái đơn hàng
        $ordersByStatus = Order::selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->get();

        return view('admin.statistics.index', compact(
            'revenueByMonth',
            'topProducts',
            'totalRevenue',
            'totalOrders',
            'totalItemsSold',
            'revenueByPaymentMethod',
            'ordersByStatus'
        ));
    }

    // Báo cáo chi tiết
    public function report()
    {
        $startDate = request()->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = request()->get('end_date', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->with('details.product')
            ->paginate(20);

        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->count();

        return view('admin.statistics.report', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'startDate',
            'endDate'
        ));
    }

    // Xuất báo cáo CSV
    public function export()
    {
        $startDate = request()->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = request()->get('end_date', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->with('details.product')
            ->get();

        $filename = 'report_' . $startDate . '_to_' . $endDate . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, ['Order ID', 'Date', 'Customer', 'Total', 'Status']);
            
            // Data
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->total_amount,
                    $order->order_status,
                ]);
            }
            
            fclose($handle);
        }, $filename);
    }
}
