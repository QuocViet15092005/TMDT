@extends('layouts.app')

@section('title', 'Thanh toán QR - Đơn hàng #' . $order->id)

@section('content')
<div class="container section-space" style="max-width: 820px; margin: 0 auto;">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; border-radius: 12px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px; border-radius: 12px; font-weight: 500;">
            ✕ {{ session('error') }}
        </div>
    @endif

    @if($order->payment_status === 'paid')
        {{-- ========================================================= --}}
        {{-- TRƯỜNG HỢP: ĐÃ XÁC THỰC THANH TOÁN THÀNH CÔNG --}}
        {{-- ========================================================= --}}
        <div class="card" style="text-align: center; padding: 48px 28px; border: 2px solid #10b981; background: #f0fdf4; border-radius: 20px; box-shadow: 0 12px 30px -5px rgba(16, 185, 129, 0.18);">
            <div style="width: 88px; height: 88px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; margin: 0 auto 24px; box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);">
                ✓
            </div>

            <h1 style="color: #065f46; font-size: 1.9rem; margin-bottom: 10px; font-weight: 800; letter-spacing: -0.5px;">
                🎉 ĐÃ XÁC THỰC THANH TOÁN QR THÀNH CÔNG!
            </h1>
            <p style="color: #047857; font-size: 1.05rem; max-width: 560px; margin: 0 auto 28px; line-height: 1.6;">
                Hệ thống Sport Shop đã ghi nhận thanh toán chuyển khoản thành công cho đơn hàng <strong>#{{ $order->id }}</strong>. Đơn hàng của bạn đã sẵn sàng để xử lý và giao đến bạn!
            </p>

            {{-- BẢNG TÓM TẮT ĐƠN HÀNG ĐÃ THANH TOÁN --}}
            <div style="background: #ffffff; border: 1px solid #d1fae5; border-radius: 16px; padding: 24px; text-align: left; max-width: 520px; margin: 0 auto 32px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px;">
                    <span style="color: #64748b; font-size: 0.95rem;">Mã đơn hàng:</span>
                    <strong style="color: #0f172a; font-size: 1rem;">#{{ $order->id }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px;">
                    <span style="color: #64748b; font-size: 0.95rem;">Khách hàng:</span>
                    <strong style="color: #0f172a;">{{ $order->customer_name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px;">
                    <span style="color: #64748b; font-size: 0.95rem;">Số tiền đã thanh toán:</span>
                    <strong style="color: #059669; font-size: 1.2rem;">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px;">
                    <span style="color: #64748b; font-size: 0.95rem;">Phương thức:</span>
                    <strong style="color: #4f46e5;">Chuyển khoản VietQR Code</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #64748b; font-size: 0.95rem;">Trạng thái thanh toán:</span>
                    <span class="badge badge-success" style="background: #10b981; color: white; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;">✓ ĐÃ THANH TOÁN (PAID)</span>
                </div>
            </div>

            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary" style="padding: 12px 26px; font-size: 1rem; border-radius: 10px;">
                    📦 Xem tiến trình đơn hàng &rarr;
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="padding: 12px 26px; font-size: 1rem; border-radius: 10px;">
                    🛍️ Tiếp tục mua sắm
                </a>
            </div>
        </div>

    @else
        {{-- ========================================================= --}}
        {{-- TRƯỜNG HỢP: CHỜ QUÉT MÃ VIETQR VÀ TỰ ĐỘNG XÁC THỰC --}}
        {{-- ========================================================= --}}
        <div style="text-align: center; margin-bottom: 24px;">
            <h1 class="section-title" style="margin-bottom: 8px; font-size: 1.85rem; font-weight: 800;">
                📱 Thanh toán đơn hàng qua mã QR
            </h1>
        </div>

        <div class="card" style="border-radius: 20px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.07); border: 1px solid #e2e8f0;">
            {{-- THÔNG TIN TỔNG QUAN --}}
            <div style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff; padding: 18px 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <span style="font-size: 0.85rem; color: #94a3b8; display: block;">Mã đơn hàng:</span>
                    <strong style="font-size: 1.15rem; color: #f8fafc; letter-spacing: 0.5px;">#{{ $order->id }}</strong>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 0.85rem; color: #94a3b8; display: block;">Số tiền cần thanh toán:</span>
                    <strong style="font-size: 1.4rem; color: #fbbf24; font-weight: 800;">{{ number_format($amount, 0, ',', '.') }} VNĐ</strong>
                </div>
            </div>

            <div class="card-body" style="padding: 32px 24px; text-align: center; background: #fafbfc;">
                
                {{-- KHUNG CHỨA MÃ QR VÀ CHI TIẾT CHUYỂN KHOẢN --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; align-items: start; max-width: 740px; margin: 0 auto 28px;">
                    
                    {{-- CỘT 1: KHUNG ẢNH QR VIETQR CHUẨN --}}
                    <div style="background: #ffffff; padding: 18px; border: 2px solid #e2e8f0; border-radius: 18px; box-shadow: 0 6px 18px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        
                        <div style="position: relative; width: 100%; max-width: 290px; margin-bottom: 12px;">
                            {{-- ẢNH MÃ QR VIETQR ĐỘNG ĐÃ NHÚNG ĐÚNG SỐ TIỀN VÀ NỘI DUNG --}}
                            <img
                                id="vietqr-image"
                                src="{{ $qrUrl }}"
                                alt="Mã VietQR thanh toán đơn hàng #{{ $order->id }}"
                                style="width: 100%; height: auto; display: block; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);"
                                onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode('2|99|'.$accountNo.'|'.$accountName.'||0|0|'.$amount.'|'.$transferContent.'|transfer_p2p') }}';"
                            >
                        </div>

                        <div style="font-size: 0.82rem; color: #64748b; margin-top: 4px; display: flex; align-items: center; gap: 6px;">
                            <span style="color: #10b981; font-weight: 700;">●</span> Mã QR đã chứa sẵn số tiền <strong>{{ number_format($amount, 0, ',', '.') }}đ</strong>
                        </div>

                        {{-- NÚT TẢI MÃ QR VỀ MÁY --}}
                        <a
                            href="{{ $qrUrl }}"
                            target="_blank"
                            download="vietqr-sportshop-{{ $order->id }}.png"
                            class="btn btn-secondary"
                            style="margin-top: 12px; font-size: 0.85rem; padding: 6px 14px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;"
                        >
                            📥 Tải ảnh mã QR
                        </a>
                    </div>

                    {{-- CỘT 2: THÔNG TIN TÀI KHOẢN & TIỆN ÍCH SAO CHÉP --}}
                    <div style="text-align: left; background: #ffffff; padding: 20px; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                        <div style="font-weight: 700; font-size: 1rem; color: #1e293b; margin-bottom: 14px; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>🏦</span> Thông tin tài khoản thụ hưởng
                        </div>

                        {{-- NGÂN HÀNG --}}
                        <div style="margin-bottom: 12px;">
                            <span style="font-size: 0.82rem; color: #64748b; display: block; margin-bottom: 2px;">Ngân hàng thụ hưởng</span>
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                {{ $bankName }}
                            </div>
                        </div>

                        {{-- CHỦ TÀI KHOẢN --}}
                        <div style="margin-bottom: 12px;">
                            <span style="font-size: 0.82rem; color: #64748b; display: block; margin-bottom: 2px;">Chủ tài khoản</span>
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem; text-transform: uppercase;">
                                {{ $accountName }}
                            </div>
                        </div>

                        {{-- SỐ TÀI KHOẢN + NÚT SAO CHÉP --}}
                        <div style="margin-bottom: 12px; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="font-size: 0.8rem; color: #64748b; display: block;">Số tài khoản</span>
                                    <strong style="font-size: 1.1rem; color: #0f172a; letter-spacing: 0.5px;">{{ $accountNo }}</strong>
                                </div>
                                <button
                                    type="button"
                                    class="btn-copy"
                                    onclick="copyToClipboard('{{ $accountNo }}', 'Số tài khoản')"
                                    style="background: #e2e8f0; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; color: #334155; transition: all 0.2s;"
                                >
                                    📋 Sao chép
                                </button>
                            </div>
                        </div>

                        {{-- SỐ TIỀN CHÍNH XÁC + NÚT SAO CHÉP --}}
                        <div style="margin-bottom: 12px; background: #fefce8; padding: 8px 12px; border-radius: 8px; border: 1px solid #fef08a;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="font-size: 0.8rem; color: #854d0e; display: block;">Số tiền chính xác</span>
                                    <strong style="font-size: 1.15rem; color: #ca8a04;">{{ number_format($amount, 0, ',', '.') }} VNĐ</strong>
                                </div>
                                <button
                                    type="button"
                                    class="btn-copy"
                                    onclick="copyToClipboard('{{ $amount }}', 'Số tiền')"
                                    style="background: #fde047; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; color: #713f12; transition: all 0.2s;"
                                >
                                    📋 Sao chép
                                </button>
                            </div>
                        </div>

                        {{-- NỘI DUNG CHUYỂN KHOẢN + NÚT SAO CHÉP --}}
                        <div style="background: #eff6ff; padding: 8px 12px; border-radius: 8px; border: 1px solid #bfdbfe;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="font-size: 0.8rem; color: #1e40af; display: block;">Nội dung chuyển khoản (Memo)</span>
                                    <strong style="font-size: 1.1rem; color: #2563eb; letter-spacing: 1px;">{{ $transferContent }}</strong>
                                </div>
                                <button
                                    type="button"
                                    class="btn-copy"
                                    onclick="copyToClipboard('{{ $transferContent }}', 'Nội dung chuyển khoản')"
                                    style="background: #93c5fd; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; color: #1e3a8a; transition: all 0.2s;"
                                >
                                    📋 Sao chép
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CÁC BƯỚC HƯỚNG DẪN --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; max-width: 740px; margin: 0 auto 24px; text-align: left;">
                    <div style="font-weight: 700; font-size: 0.95rem; color: #334155; margin-bottom: 12px;">
                        💡 Các bước thanh toán:
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                        <div style="background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                            <strong style="color: var(--primary-color);">1. Mở App</strong><br>
                            Mở ứng dụng Ngân hàng hoặc Ví điện tử (MB, VCB, MoMo...)
                        </div>
                        <div style="background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                            <strong style="color: var(--primary-color);">2. Quét mã QR</strong><br>
                            Chọn "Quét mã QR" và hướng camera vào mã ở trên
                        </div>
                        <div style="background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                            <strong style="color: var(--primary-color);">3. Xác nhận chuyển</strong><br>
                            Kiểm tra số tiền và hoàn tất chuyển khoản
                        </div>
                        <div style="background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                            <strong style="color: #10b981;">4. Tự động chuyển</strong><br>
                            Hệ thống tự nhận diện và chuyển sang trang đơn hàng
                        </div>
                    </div>
                </div>

                {{-- TRẠNG THÁI TỰ ĐỘNG KIỂM TRA THANH TOÁN --}}
                <div id="payment-status-card" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 14px; padding: 18px 24px; max-width: 740px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; gap: 14px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.08); transition: all 0.3s ease;">
                    <div id="status-spinner" style="width: 24px; height: 24px; border: 3px solid #bbf7d0; border-top-color: #16a34a; border-radius: 50%; animation: qrSpin 1s linear infinite; flex-shrink: 0;"></div>
                    <div style="text-align: left;">
                        <div id="status-title" style="font-weight: 700; color: #15803d; font-size: 1.02rem;">
                            Đang tự động kiểm tra giao dịch chuyển khoản...
                        </div>
                        <div id="status-desc" style="font-size: 0.88rem; color: #166534; margin-top: 2px;">
                            Sau khi chuyển tiền thành công, hệ thống sẽ tự động chuyển sang trang theo dõi đơn hàng ngay lập tức.
                        </div>
                    </div>
                </div>

                <p style="color: #94a3b8; font-size: 0.85rem; margin-top: 14px; margin-bottom: 0;">
                    🔒 Giao dịch an toàn được bảo vệ bởi hệ thống ngân hàng Napas 247.
                </p>
            </div>
        </div>

        <div style="margin-top: 24px; text-align: center;">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary" style="font-size: 0.95rem; border-radius: 8px;">
                ← Quay lại trang chi tiết đơn hàng #{{ $order->id }}
            </a>
        </div>
    @endif

</div>

{{-- TOAST THÔNG BÁO SAO CHÉP --}}
<div id="copy-toast" style="position: fixed; bottom: 30px; right: 30px; background: #0f172a; color: #ffffff; padding: 12px 20px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.25); display: none; align-items: center; gap: 8px; font-size: 0.95rem; z-index: 9999; animation: fadeIn 0.3s ease;">
    <span>✅</span> <span id="copy-toast-text">Đã sao chép thành công!</span>
</div>

<style>
@keyframes qrSpin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
let isRedirecting = false;

// Tự động kiểm tra trạng thái thanh toán định kỳ mỗi 1.5s
function checkPaymentStatus() {
    if (isRedirecting) return;

    fetch("{{ route('payment.qr.status', $order) }}", {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.is_paid && !isRedirecting) {
            handlePaidSuccess(data.redirect_url);
        }
    })
    .catch(function(error) {
        console.warn('Đang kiểm tra thanh toán...', error);
    });
}

function handlePaidSuccess(redirectUrl) {
    isRedirecting = true;
    clearInterval(statusPoller);

    const statusCard = document.getElementById('payment-status-card');
    const statusTitle = document.getElementById('status-title');
    const statusDesc = document.getElementById('status-desc');
    const spinner = document.getElementById('status-spinner');
    
    if (statusCard) {
        statusCard.style.background = '#dcfce7';
        statusCard.style.borderColor = '#22c55e';
    }
    if (spinner) {
        spinner.style.display = 'none';
    }
    if (statusTitle) {
        statusTitle.innerHTML = '🎉 ĐÃ XÁC NHẬN THANH TOÁN THÀNH CÔNG!';
        statusTitle.style.color = '#15803d';
    }
    if (statusDesc) {
        statusDesc.innerHTML = 'Đang tự động chuyển sang trang theo dõi đơn hàng...';
    }

    setTimeout(function() {
        window.location.href = redirectUrl;
    }, 600);
}

// Chạy kiểm tra ngay lập tức khi mở trang và định kỳ mỗi 1.0 giây
checkPaymentStatus();
const statusPoller = setInterval(checkPaymentStatus, 1000);

// Tự động kiểm tra ngay khi người dùng chuyển lại tab trình duyệt
window.addEventListener('focus', checkPaymentStatus);
document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'visible') {
        checkPaymentStatus();
    }
});

function copyToClipboard(text, fieldName) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Đã sao chép ' + fieldName + ': ' + text);
        }).catch(function() {
            fallbackCopy(text, fieldName);
        });
    } else {
        fallbackCopy(text, fieldName);
    }
}

function fallbackCopy(text, fieldName) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showToast('Đã sao chép ' + fieldName + ': ' + text);
    } catch (err) {
        alert('Không thể sao chép tự động: ' + text);
    }
    document.body.removeChild(textArea);
}

function showToast(message) {
    var toast = document.getElementById('copy-toast');
    var toastText = document.getElementById('copy-toast-text');
    if (!toast || !toastText) return;
    
    toastText.textContent = message;
    toast.style.display = 'flex';
    
    clearTimeout(window.toastTimeout);
    window.toastTimeout = setTimeout(function() {
        toast.style.display = 'none';
    }, 3000);
}
</script>
@endsection