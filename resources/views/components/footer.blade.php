<footer class="site-footer">
    <div class="container">
        {{-- KHỐI ĐĂNG KÝ NHẬN TIN KHUYẾN MÃI --}}
        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); padding: 24px 28px; border-radius: 16px; margin-bottom: 40px;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; margin: 0 0 6px 0; color: #fff; display: flex; align-items: center; gap: 8px;">
                        ✉️ Đăng ký nhận tin khuyến mãi
                    </h3>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">
                        Nhận thông tin sản phẩm mới và ưu đãi đặc biệt qua email
                    </p>
                </div>

                <form action="{{ route('newsletter.subscribe') }}" method="POST" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    @csrf
                    <input type="email" name="email" placeholder="Nhập email của bạn..." required 
                           style="padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.08); color: #fff; outline: none; min-width: 220px; font-size: 0.88rem;">
                    
                    <input type="text" name="name" placeholder="Tên của bạn (tùy chọn)" 
                           style="padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.08); color: #fff; outline: none; min-width: 180px; font-size: 0.88rem;">

                    <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 10px 22px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 0.88rem; transition: background 0.2s;">
                        Đăng ký
                    </button>
                </form>
            </div>

            {{-- Thông báo gửi Mail --}}
            @if(session('newsletter_success'))
                <div style="margin-top: 14px; background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 8px 12px; border-radius: 6px; font-size: 0.85rem;">
                    ✓ {{ session('newsletter_success') }}
                </div>
            @endif

            @if(session('newsletter_error'))
                <div style="margin-top: 14px; background: rgba(239, 68, 68, 0.2); color: #f87171; padding: 8px 12px; border-radius: 6px; font-size: 0.85rem;">
                    ✕ {{ session('newsletter_error') }}
                </div>
            @endif
        </div>
    </div>

    {{-- CÁC CỘT NỘI DUNG FOOTER --}}
    <div class="container footer-grid">
        <div class="footer-column">
            <h3 style="font-size: 1.3rem; display: flex; align-items: center; gap: 8px;">
                <span>⚡</span> SPORT SHOP
            </h3>
            <p>
                Hệ thống bán lẻ trang phục, giày dép & phụ kiện thể thao chính hãng hàng đầu. Đem lại trải nghiệm mua sắm tiện lợi, nhanh chóng với chất lượng tốt nhất.
            </p>
            <div style="margin-top: 16px; display: flex; gap: 10px;">
                <span style="background: rgba(255,255,255,0.1); padding: 6px 12px; border-radius: 8px; font-size: 0.82rem; color: #cbd5e1;">🛡️ 100% Chính Hãng</span>
                <span style="background: rgba(255,255,255,0.1); padding: 6px 12px; border-radius: 8px; font-size: 0.82rem; color: #cbd5e1;">🚚 Giao Toàn Quốc</span>
            </div>
        </div>

        <div class="footer-column">
            <h3>Chính sách & Hỗ trợ</h3>
            <a href="#">📋 Hướng dẫn chọn size</a>
            <a href="#">🚚 Chính sách giao hàng</a>
            <a href="#">🔄 Quy định đổi trả & bảo hành</a>
            <a href="#">🛡️ Chính sách bảo mật</a>
            <a href="#">❓ Câu hỏi thường gặp</a>
        </div>

        <div class="footer-column">
            <h3>Thanh toán an toàn</h3>
            <p style="margin-bottom: 12px;">Chấp nhận nhiều hình thức thanh toán linh hoạt:</p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <div style="background: rgba(255,255,255,0.06); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px; color: #e2e8f0; font-size: 0.88rem;">
                    <span>📱</span> Chuyển khoản VietQR Napas 247
                </div>
                <div style="background: rgba(255,255,255,0.06); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px; color: #e2e8f0; font-size: 0.88rem;">
                    <span>💵</span> Thanh toán khi nhận hàng (COD)
                </div>
            </div>
        </div>

        <div class="footer-column">
            <h3>Tổng đài hỗ trợ</h3>
            <p><strong>Hotline:</strong> 0123 456 789 (8h00 - 21h00)</p>
            <p><strong>Email:</strong> support@sportshop.vn</p>
            <p><strong>Địa chỉ:</strong> Quận Cầu Giấy, Hà Nội</p>
            <p style="margin-top: 12px; color: #22c55e; font-weight: 600;">● Hệ thống phục vụ 24/7</p>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <span>© {{ date('Y') }} SPORT SHOP - Bản quyền thuộc về Sport Shop.</span>
            <span style="color: #64748b;">Được thiết kế cho trải nghiệm mua sắm thể thao tốt nhất.</span>
        </div>
    </div>
</footer>