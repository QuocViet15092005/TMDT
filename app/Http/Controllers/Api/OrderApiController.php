<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    // Danh sách đơn hàng của người dùng
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('details.product')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    // Chi tiết đơn hàng
    public function show(Order $order)
    {
        // Kiểm tra quyền
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xem đơn hàng này.',
            ], 403);
        }

        $order->load(['user', 'details.product', 'details.variant']);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    // Hủy đơn hàng
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy đơn hàng này.',
            ], 403);
        }

        if ($order->order_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể hủy đơn hàng ở trạng thái chờ xử lý.',
            ], 400);
        }

        // Hoàn lại tồn kho
        foreach ($order->details as $detail) {
            $detail->variant->increment('quantity', $detail->quantity);
        }

        $order->update(['order_status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã bị hủy.',
            'data' => $order,
        ]);
    }
}
