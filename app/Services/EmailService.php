<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Gửi email xác nhận đơn hàng
     */
    public function sendOrderConfirmation(Order $order): void
    {
        if (!$order->customer_email) {
            return;
        }

        // TODO: Triển khai gửi email thực tế
        // Hiện tại chỉ ghi log
        \Log::info('Order confirmation email would be sent to: ' . $order->customer_email, [
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
        ]);
    }

    /**
     * Gửi email cập nhật trạng thái đơn hàng
     */
    public function sendOrderStatusUpdate(Order $order, string $oldStatus, string $newStatus): void
    {
        if (!$order->customer_email) {
            return;
        }

        \Log::info('Order status update email would be sent to: ' . $order->customer_email, [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Gửi email thông báo khuyến mãi
     */
    public function sendPromotionEmail(string $email, string $promotionCode, string $discount): void
    {
        \Log::info('Promotion email would be sent to: ' . $email, [
            'promotion_code' => $promotionCode,
            'discount' => $discount,
        ]);
    }

    /**
     * Gửi email phản hồi về review
     */
    public function sendReviewThankYou(string $email, string $productName): void
    {
        \Log::info('Review thank you email would be sent to: ' . $email, [
            'product_name' => $productName,
        ]);
    }
}
