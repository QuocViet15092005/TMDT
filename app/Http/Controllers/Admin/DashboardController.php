<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        $totalUsers = User::where(
            'role',
            'customer'
        )->count();

        $totalOrders = Order::count();

        $totalRevenue = Order::where(
            'payment_status',
            'paid'
        )->sum('total_amount');

        $pendingOrders = Order::where(
            'order_status',
            'pending'
        )->count();

        // 5 đơn hàng mới nhất cần theo dõi
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Top sản phẩm nổi bật
        $topProducts = Product::with(['category', 'variants'])
            ->latest()
            ->limit(5)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'totalProducts',
                'totalUsers',
                'totalOrders',
                'totalRevenue',
                'pendingOrders',
                'recentOrders',
                'topProducts'
            )
        );
    }
}