<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterSubscribed;
use Exception;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email!',
            'email.email' => 'Địa chỉ email không hợp lệ!',
        ]);

        try {
            // Gửi mail xác nhận tới email người dùng vừa nhập
            Mail::to($request->email)->send(new NewsletterSubscribed($request->name));

            return back()->with('newsletter_success', 'Đăng ký nhận tin thành công! Vui lòng kiểm tra hộp thư email của bạn.');
        } catch (Exception $e) {
            // Hiển thị lỗi nếu sai mật khẩu ứng dụng Google hoặc chưa bật TLS
            return back()->with('newsletter_error', 'Gửi email thất bại. Vui lòng kiểm tra lại cấu hình SMTP hoặc App Password.');
        }
    }
}