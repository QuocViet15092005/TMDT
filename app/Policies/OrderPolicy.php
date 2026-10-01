<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Kiểm tra người dùng có thể xem đơn hàng
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->role === 'admin';
    }

    /**
     * Kiểm tra người dùng có thể cập nhật đơn hàng
     */
    public function update(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Kiểm tra người dùng có thể hủy đơn hàng
     */
    public function cancel(User $user, Order $order): bool
    {
        // Chỉ chủ đơn hàng hoặc admin mới có thể hủy
        if ($user->id !== $order->user_id && $user->role !== 'admin') {
            return false;
        }

        // Chỉ hủy được đơn ở trạng thái pending
        return $order->order_status === 'pending';
    }

    /**
     * Kiểm tra người dùng có thể xóa đơn hàng
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}
