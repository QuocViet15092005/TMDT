<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cấu hình thông tin thanh toán VietQR ngân hàng
    |--------------------------------------------------------------------------
    |
    | Hỗ trợ tạo mã QR thanh toán chuẩn VietQR (Napas 247 / EMVCo).
    | Khi quét mã bằng ứng dụng ngân hàng hoặc ví điện tử (MB, Vietcombank, Techcombank, Momo...),
    | ứng dụng sẽ tự động điền ĐÚNG SỐ TIỀN thanh toán và NỘI DUNG chuyển khoản.
    |
    */

    // Mã ngân hàng theo chuẩn VietQR (MB, VCB, ICB, TCB, ACB, VPB, TPB, BIDV, ...)
    'bank_id' => env('VIETQR_BANK_ID', 'TPB'),

    // Tên hiển thị của ngân hàng
    'bank_name' => env('VIETQR_BANK_NAME', 'TPBank (Ngân hàng TMCP Tiên Phong)'),

    // Số tài khoản ngân hàng nhận tiền
    'account_no' => env('VIETQR_ACCOUNT_NO', '15092005205'),

    // Tên chủ tài khoản ngân hàng (viết hoa không dấu hoặc có dấu)
    'account_name' => env('VIETQR_ACCOUNT_NAME', 'DO QUOC VIET'),

    // Mẫu hiển thị VietQR (compact2, compact, qr_only, print)
    'template' => env('VIETQR_TEMPLATE', 'compact2'),

    // API Token của SePay (https://sepay.vn) để tự động kiểm tra giao dịch thời gian thực
    'sepay_api_token' => env('SEPAY_API_TOKEN', ''),

    // API Key của Casso (https://casso.vn)
    'casso_api_key' => env('CASSO_API_KEY', ''),
];
