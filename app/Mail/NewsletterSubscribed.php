<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscribed extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('🎉 Cảm ơn bạn đã đăng ký nhận tin khuyến mãi!')
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f8fafc;'>
                            <div style='max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0;'>
                                <h2 style='color: #2563eb; margin-top: 0;'>Chào ".e($this->name ?? 'bạn')."!</h2>
                                <p style='color: #475569; font-size: 16px; line-height: 1.6;'>
                                    Cảm ơn bạn đã đăng ký nhận thông tin ưu đãi tại <strong>Sport Shop</strong>.
                                </p>
                                <p style='color: #475569; font-size: 16px; line-height: 1.6;'>
                                    Chúng tôi sẽ gửi cho bạn những thông tin sản phẩm mới nhất và các mã ưu đãi đặc biệt qua email này.
                                </p>
                                <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                                <p style='font-size: 0.85rem; color: #64748b;'>Trân trọng,<br>Đội ngũ Sport Shop</p>
                            </div>
                        </div>
                    ");
    }
}