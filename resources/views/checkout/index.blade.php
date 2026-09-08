@extends('layouts.app')

@section('title', 'Thanh toán đơn hàng')

@section('content')
<div class="container section-space">

    <div style="margin-bottom: 28px;">
        <h1 class="section-title" style="margin-bottom: 6px;">💳 Thanh toán & Đặt hàng</h1>
        <p class="text-muted" style="margin: 0; font-size: 0.95rem;">Hoàn tất thông tin nhận hàng và lựa chọn phương thức thanh toán thuận tiện</p>
    </div>

    {{-- THÔNG BÁO FLASH --}}
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            ✕ {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf

        {{-- LƯU MÃ GIẢM GIÁ ĐÃ ÁP DỤNG TỪ SESSION --}}
        @if(!empty($discountCode))
            <input type="hidden" name="discount_code" value="{{ $discountCode }}">
        @endif

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 32px; align-items: start;">
            
            {{-- CỘT TRÁI: THÔNG TIN KHÁCH HÀNG & PHƯƠNG THỨC THANH TOÁN --}}
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                {{-- KHỐI 1: THÔNG TIN NHẬN HÀNG --}}
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--dark-color); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                        <span>📍</span> Thông tin giao hàng
                    </h3>

                    <div class="form-group">
                        <label>Họ và tên người nhận <span style="color: #ef4444;">*</span></label>
                        <input
                            type="text"
                            name="customer_name"
                            value="{{ old('customer_name', auth()->user()?->name ?? '') }}"
                            placeholder="Ví dụ: Nguyễn Văn A"
                            required
                        >
                        @error('customer_name')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label>Số điện thoại <span style="color: #ef4444;">*</span></label>
                            <input
                                type="text"
                                name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                placeholder="0912 345 678"
                                required
                            >
                            @error('customer_phone')
                                <small style="color: #ef4444;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email nhận thông báo</label>
                            <input
                                type="email"
                                name="customer_email"
                                value="{{ old('customer_email', auth()->user()?->email ?? '') }}"
                                placeholder="email@example.com"
                            >
                            @error('customer_email')
                                <small style="color: #ef4444;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Địa chỉ nhận hàng cụ thể <span style="color: #ef4444;">*</span></label>
                        <textarea
                            name="shipping_address"
                            rows="3"
                            placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                            required
                        >{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- KHỐI 2: PHƯƠNG THỨC THANH TOÁN --}}
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--dark-color); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                        <span>💳</span> Chọn phương thức thanh toán
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        {{-- OPTION 1: COD --}}
                        <label style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; border: 2px solid #e2e8f0; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition-fast); background: #fafbfc;" class="payment-option-card">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="cod" 
                                {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}
                                style="width: 20px; height: 20px; margin-top: 2px;"
                            >
                            <div>
                                <strong style="display: block; font-size: 0.98rem; color: var(--dark-color); margin-bottom: 2px;">
                                    💵 Thanh toán khi nhận hàng (COD)
                                </strong>
                                <span style="font-size: 0.85rem; color: var(--slate-500);">
                                    Kiểm tra hàng trước khi nhận, thanh toán tiền mặt cho nhân viên giao hàng.
                                </span>
                            </div>
                        </label>

                        {{-- OPTION 2: VIETQR --}}
                        @auth
                            <label style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; border: 2px solid #e2e8f0; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition-fast); background: #fafbfc;" class="payment-option-card">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="qr" 
                                    {{ old('payment_method') === 'qr' ? 'checked' : '' }}
                                    style="width: 20px; height: 20px; margin-top: 2px;"
                                >
                                <div>
                                    <strong style="display: block; font-size: 0.98rem; color: var(--primary-color); margin-bottom: 2px;">
                                        📱 Chuyển khoản VietQR Code (Tự động xác nhận)
                                    </strong>
                                    <span style="font-size: 0.85rem; color: var(--slate-500);">
                                        Quét mã VietQR bằng bất kỳ App ngân hàng nào (MB, VCB, Momo, Techcom...). Hệ thống tự động xác thực và duyệt đơn sau 1 giây.
                                    </span>
                                </div>
                            </label>
                        @else
                            <div style="padding: 14px 16px; background: #f8fafc; border: 1px dashed var(--border-color); border-radius: var(--radius-md); font-size: 0.85rem; color: var(--slate-500);">
                                💡 Bạn muốn thanh toán trực tuyến qua <strong>VietQR</strong>? Vui lòng <a href="{{ route('login.form') }}" style="color: var(--primary-color); font-weight: 700;">Đăng nhập</a> trước khi đặt hàng.
                            </div>
                        @endauth
                    </div>
                </div>

            </div>

            {{-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG --}}
            <div style="position: sticky; top: 90px;">
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--dark-color); margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1.5px solid var(--border-color);">
                        📦 Đơn hàng của bạn ({{ count($cart) }})
                    </h3>

                    {{-- DANH SÁCH SẢN PHẨM NHỎ --}}
                    <div style="display: flex; flex-direction: column; gap: 12px; max-height: 280px; overflow-y: auto; padding-right: 4px; margin-bottom: 18px;">
                        @foreach($cart as $item)
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; font-size: 0.9rem; padding-bottom: 10px; border-bottom: 1px dashed var(--border-color);">
                                <div style="flex: 1;">
                                    <strong style="color: var(--dark-color); display: block;">{{ $item['name'] }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--slate-500); margin-top: 2px;">
                                        @if(!empty($item['size'])) Size: <strong>{{ $item['size'] }}</strong> @endif
                                        @if(!empty($item['color'])) | Màu: <strong>{{ $item['color'] }}</strong> @endif
                                        | SL: <strong>x{{ $item['quantity'] }}</strong>
                                    </div>
                                </div>
                                <div style="font-weight: 700; color: var(--slate-700); font-size: 0.95rem;">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- TÍNH TOÁN CHI PHÍ --}}
                    <div style="display: grid; gap: 10px; font-size: 0.92rem; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span class="text-muted">Tạm tính:</span>
                            <strong>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</strong>
                        </div>

                        @if($discountAmount > 0)
                            <div style="display: flex; justify-content: space-between; color: #16a34a;">
                                <span>Giảm giá ({{ $discountCode }}):</span>
                                <strong>-{{ number_format($discountAmount, 0, ',', '.') }} VNĐ</strong>
                            </div>
                        @endif

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-muted">Phí giao hàng:</span>
                            <span class="badge badge-success">Miễn phí</span>
                        </div>
                    </div>

                    <div style="padding: 16px 0; border-top: 1.5px dashed var(--border-color); border-bottom: 1.5px dashed var(--border-color); margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <span style="font-weight: 800; color: var(--dark-color); font-size: 1.05rem;">Tổng thanh toán:</span>
                            <span style="color: var(--secondary-color); font-weight: 800; font-size: 1.55rem; font-family: var(--font-heading);">
                                {{ number_format($total, 0, ',', '.') }} VNĐ
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; font-size: 1.05rem; margin-bottom: 12px;">
                        🚀 Hoàn tất đặt hàng ngay
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-secondary" style="width: 100%; font-size: 0.9rem;">
                        ← Quay lại giỏ hàng
                    </a>
                </div>
            </div>

        </div>
    </form>

</div>

<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns: 1.2fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection