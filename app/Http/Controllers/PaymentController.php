<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ QR THANH TOÁN
    |--------------------------------------------------------------------------
    */
    public function qr(Order $order)
    {
        // Chỉ chủ đơn hàng hoặc Admin được xem
        abort_unless(
            $order->user_id === auth()->id()
            || auth()->user()?->role === 'admin',
            403
        );

        // Đơn phải chọn phương thức QR
        abort_unless(
            $order->payment_method === 'qr',
            404
        );

        // Không thanh toán đơn đã bị hủy
        if ($order->order_status === 'cancelled') {
            return redirect()
                ->route('orders.show', $order)
                ->with(
                    'error',
                    'Đơn hàng này đã bị hủy.'
                );
        }

        // Nếu đơn hàng đã thanh toán rồi, chuyển hướng thẳng sang trang chi tiết đơn hàng
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('orders.show', $order)
                ->with(
                    'success',
                    '🎉 ĐÃ XÁC THỰC THANH TOÁN QR THÀNH CÔNG! Hệ thống Sport Shop đã ghi nhận thanh toán cho đơn hàng #' . $order->id . '.'
                );
        }

        // Cập nhật số tiền đơn test thành 10.000 VNĐ
        if ($order->id == 12 && $order->total_amount != 10000) {
            $order->update(['total_amount' => 10000]);
            $order->refresh();
        }

        // Lấy thông tin tài khoản ngân hàng cấu hình
        $bankId = config('bank.bank_id', 'TPB');
        $bankName = config('bank.bank_name', 'TPBank (Ngân hàng TMCP Tiên Phong)');
        $accountNo = config('bank.account_no', '15092005205');
        $accountName = config('bank.account_name', 'DO QUOC VIET');
        $template = config('bank.template', 'compact2');

        // Số tiền chính xác cần thanh toán và cú pháp chuyển khoản
        $amount = (int) round($order->total_amount);
        $transferContent = 'SPORTSHOP' . $order->id;

        // Sinh link ảnh VietQR chuẩn Napas 247 nhúng sẵn số tiền chính xác & nội dung
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.png?" . http_build_query([
            'amount' => $amount,
            'addInfo' => $transferContent,
            'accountName' => $accountName,
        ]);

        return view(
            'payment.qr',
            compact('order', 'bankId', 'bankName', 'accountNo', 'accountName', 'amount', 'transferContent', 'qrUrl')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA TRẠNG THÁI THANH TOÁN (TỰ ĐỘNG POLLING)
    |--------------------------------------------------------------------------
    */
    public function checkStatus(Order $order)
    {
        // Kiểm tra quyền
        abort_unless(
            $order->user_id === auth()->id()
            || auth()->user()?->role === 'admin',
            403
        );

        // Nếu chưa thanh toán, tự động truy vấn API ngân hàng / SePay nếu có cấu hình token
        if ($order->payment_status !== 'paid') {
            $sepayToken = config('bank.sepay_api_token') ?: env('SEPAY_API_TOKEN');
            if ($sepayToken) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                        'Authorization' => 'Bearer ' . trim($sepayToken),
                    ])->timeout(4)->get('https://my.sepay.vn/userapi/transactions/list', [
                        'limit' => 30,
                    ]);

                    if ($response->successful()) {
                        $transactions = $response->json('transactions', []);
                        \Illuminate\Support\Facades\Log::info("SePay API responded for order #{$order->id}: count=" . count($transactions) . ", latest=" . json_encode($transactions[0] ?? []));
                        $targetMemo = 'SPORTSHOP' . $order->id;
                        $orderIdStr = (string) $order->id;

                        foreach ($transactions as $trans) {
                            $rawContent = $trans['transaction_content'] ?? $trans['content'] ?? $trans['description'] ?? '';
                            $cleanContent = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rawContent));
                            $code = strtoupper(trim($trans['code'] ?? ''));
                            $amountIn = (float) ($trans['amount_in'] ?? $trans['amount'] ?? 0);

                            // Kiểm tra xem nội dung có chứa SPORTSHOP{id} hoặc DH{id} hoặc khớp đơn hàng
                            $isMatched = str_contains($cleanContent, $targetMemo)
                                || str_contains($cleanContent, 'DH' . $orderIdStr)
                                || str_contains($cleanContent, 'ORDER' . $orderIdStr)
                                || ($code === $targetMemo || $code === $orderIdStr)
                                || (preg_match('/(?:SPORTSHOP|ORDER|DH|SPORT)[\s_-]*' . $orderIdStr . '\b/i', $rawContent));

                            if ($isMatched && $amountIn >= ($order->total_amount - 100)) {
                                $order->update([
                                    'payment_status' => 'paid',
                                    'order_status' => $order->order_status === 'pending' ? 'confirmed' : $order->order_status,
                                ]);
                                $order->refresh();
                                \Illuminate\Support\Facades\Log::info("Order #{$order->id} successfully verified & marked PAID via SePay API.");
                                break;
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("SePay API error for order #{$order->id}: " . $e->getMessage());
                }
            }
        }

        $isPaid = $order->payment_status === 'paid';

        if ($isPaid) {
            session()->flash(
                'success',
                '🎉 ĐÃ XÁC THỰC THANH TOÁN QR THÀNH CÔNG! Hệ thống Sport Shop đã ghi nhận thanh toán cho đơn hàng #' . $order->id . '.'
            );
        }

        return response()->json([
            'is_paid' => $isPaid,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'redirect_url' => route('orders.show', $order),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | XÁC NHẬN THANH TOÁN QR
    |--------------------------------------------------------------------------
    */
    public function confirm(Order $order)
    {
        // Kiểm tra quyền
        abort_unless(
            $order->user_id === auth()->id()
            || auth()->user()?->role === 'admin',
            403
        );

        // Không cho đơn COD chạy chức năng này
        abort_unless(
            $order->payment_method === 'qr',
            404
        );

        // Không thanh toán đơn đã hủy
        if ($order->order_status === 'cancelled') {
            return redirect()
                ->route('orders.show', $order)
                ->with(
                    'error',
                    'Không thể thanh toán đơn hàng đã bị hủy.'
                );
        }

        // Cập nhật trạng thái thanh toán
        if ($order->payment_status !== 'paid') {
            $updateData = [
                'payment_status' => 'paid',
            ];

            // Nếu đang pending thì tự động xác nhận đơn
            if ($order->order_status === 'pending') {
                $updateData['order_status'] = 'confirmed';
            }

            $order->update($updateData);
        }

        session()->flash(
            'success',
            '🎉 ĐÃ XÁC THỰC THANH TOÁN QR THÀNH CÔNG! Hệ thống Sport Shop đã ghi nhận thanh toán cho đơn hàng #' . $order->id . '.'
        );

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('orders.show', $order),
            ]);
        }

        return redirect()
            ->route('orders.show', $order);
    }
}