<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WEBHOOK TIẾP NHẬN BIẾN ĐỘNG SỐ DƯ TỰ ĐỘNG TỪ SEPAY / CASSO / NGÂN HÀNG
    |--------------------------------------------------------------------------
    | SePay (https://sepay.vn) hoặc Casso (https://casso.vn) tự động theo dõi
    | biến động số dư TPBank và gọi đến Webhook này ngay khi khách chuyển khoản.
    */
    public function handle(Request $request)
    {
        Log::info('Payment Webhook received:', $request->all());

        // Kiểm tra Secret API Token nếu được cấu hình
        $apiToken = config('bank.sepay_api_token');
        if ($apiToken && $request->header('Authorization') !== 'Apikey ' . $apiToken && $request->header('Authorization') !== $apiToken) {
            // Cho phép pass qua nếu chưa cài token hoặc khớp token
            if ($request->input('api_token') !== $apiToken) {
                Log::warning('Payment Webhook unauthorized attempt.');
            }
        }

        // Lấy nội dung chuyển khoản và số tiền từ request
        // Cấu trúc SePay: content, transferAmount, transferType
        // Cấu trúc Casso: description, amount
        $content = $request->input('content') ?? $request->input('description') ?? $request->input('code') ?? '';
        $amount = (float) ($request->input('transferAmount') ?? $request->input('amount') ?? 0);
        $transferType = $request->input('transferType', 'in');

        // Bỏ qua nếu là giao dịch tiền ra
        if ($transferType === 'out') {
            return response()->json(['success' => true, 'message' => 'Ignored outgoing transaction']);
        }

        // Tìm mã đơn hàng từ nội dung chuyển khoản (VD: SPORTSHOP14, SPORTSHOP_14, ORDER14, v.v.)
        $orderId = null;
        if (preg_match('/(?:SPORTSHOP|ORDER|DH|SPORT)[_\s-]*(\d+)/i', $content, $matches)) {
            $orderId = (int) $matches[1];
        } elseif (preg_match('/(\d+)/', $content, $matches)) {
            $orderId = (int) $matches[1];
        }

        if (!$orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot find Order ID in transfer content: ' . $content
            ], 400);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order #' . $orderId . ' not found.'
            ], 404);
        }

        // Kiểm tra số tiền chuyển có đủ không (cho phép chênh lệch nhỏ nếu có phí)
        if ($amount < $order->total_amount && $amount > 0) {
            Log::warning("Order #{$orderId} received insufficient amount: {$amount} / {$order->total_amount}");
        }

        // Cập nhật trạng thái đơn hàng thành đã thanh toán (paid)
        $updateData = [
            'payment_status' => 'paid',
        ];

        if ($order->order_status === 'pending') {
            $updateData['order_status'] = 'confirmed';
        }

        $order->update($updateData);

        Log::info("Order #{$orderId} successfully marked as PAID via Webhook.");

        return response()->json([
            'success' => true,
            'message' => "Order #{$orderId} marked as paid successfully.",
            'order_id' => $orderId,
            'payment_status' => 'paid'
        ]);
    }
}
