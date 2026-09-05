<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng của khách hàng
    public function index(Request $request)
    {
        $query = auth()->user()
            ->orders()
            ->with(['details.product', 'details.variant'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Chỉ chủ đơn hàng hoặc Admin được xem
        abort_unless(
            $order->user_id === auth()->id()
            || auth()->user()?->role === 'admin',
            403
        );

        // Load đầy đủ thông tin đơn hàng
        $order->load([
            'user',
            'details.product',
            'details.variant',
        ]);

        return view(
            'orders.show',
            compact('order')
        );
    }

    // Hủy đơn hàng
    public function cancel(Order $order)
    {
        // Chỉ chủ đơn hàng được hủy
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        // Chỉ hủy được đơn ở trạng thái pending
        abort_unless(
            $order->order_status === 'pending',
            403
        );

        // Hoàn lại tồn kho nếu có
        foreach ($order->details as $detail) {
            if ($detail->variant) {
                $detail->variant->increment('quantity', $detail->quantity);
            }
        }

        $order->update([
            'order_status' => 'cancelled',
        ]);

        return back()->with('success', 'Đơn hàng đã được hủy thành công.');
    }
}
