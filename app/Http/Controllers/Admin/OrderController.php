<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'details.product',
            'details.variant',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function updateStatus(
        Request $request,
        Order $order
    ) {
        $validated = $request->validate([
            'order_status' => [
                'required',
                'in:pending,confirmed,shipping,completed,cancelled'
            ]
        ]);

        $newStatus = $validated['order_status'];
        $oldStatus = $order->order_status;

        // Hoàn lại tồn kho nếu chuyển sang trạng thái đã hủy (cancelled)
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->details as $detail) {
                if ($detail->variant) {
                    $detail->variant->increment('quantity', $detail->quantity);
                }
            }
        }

        $order->update([
            'order_status' => $newStatus
        ]);

        return back()->with(
            'success',
            'Cập nhật trạng thái đơn hàng thành công.'
        );
    }

    public function updatePayment(
        Request $request,
        Order $order
    ) {
        $validated = $request->validate([
            'payment_status' =>
                'required|in:unpaid,paid,failed'
        ]);

        $order->update([
            'payment_status' =>
                $validated['payment_status']
        ]);

        return back()->with(
            'success',
            'Cập nhật thanh toán thành công.'
        );
    }
}