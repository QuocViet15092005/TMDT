@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container section-space">
    {{-- THANH ĐIỀU HƯỚNG TRỞ VỀ --}}
    <div class="order-back-nav">
        <a href="{{ route('orders.index') }}" class="back-link">
            ← Quay lại danh sách đơn hàng
        </a>
    </div>

    {{-- HEADER TRANG --}}
    <div class="order-detail-header">
        <div>
            <h1 class="order-detail-title">Đơn hàng #{{ $order->id }}</h1>
            <p class="order-detail-time">Đặt lúc: {{ $order->created_at?->format('H:i, d/m/Y') }}</p>
        </div>
        <div class="order-header-badges">
            @if($order->payment_status === 'paid')
                <span class="badge badge-success">✓ Đã thanh toán</span>
            @elseif($order->payment_status === 'failed')
                <span class="badge badge-danger">✕ Thanh toán thất bại</span>
            @else
                <span class="badge badge-warning">🕒 Chưa thanh toán</span>
            @endif
        </div>
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

    {{-- ========================================================= --}}
    {{-- TIẾN TRÌNH ĐƠN HÀNG (ORDER PROGRESS TRACKER) --}}
    {{-- ========================================================= --}}
    <div class="card order-tracker-card mb-4">
        <h3 class="tracker-heading">Tiến trình đơn hàng</h3>

        @if($order->order_status === 'cancelled')
            <div class="cancelled-tracker-banner">
                <div class="cancelled-icon">✕</div>
                <div class="cancelled-info">
                    <h4>Đơn hàng đã bị hủy</h4>
                    <p>Đơn hàng này đã bị hủy vào lúc {{ $order->updated_at?->format('H:i, d/m/Y') }}. Số lượng sản phẩm đã được hoàn trả lại kho.</p>
                </div>
            </div>
        @else
            @php
                $statusOrder = [
                    'pending' => 1,
                    'confirmed' => 2,
                    'shipping' => 3,
                    'completed' => 4,
                ];
                $currentStep = $statusOrder[$order->order_status] ?? 1;
            @endphp

            <div class="order-stepper">
                {{-- BƯỚC 1: ĐẶT HÀNG --}}
                <div class="step-item {{ $currentStep >= 1 ? 'completed active' : '' }}">
                    <div class="step-circle">
                        <span class="step-num">1</span>
                    </div>
                    <div class="step-content">
                        <div class="step-title">Đặt hàng thành công</div>
                        <div class="step-desc">{{ $order->created_at?->format('d/m H:i') }}</div>
                    </div>
                </div>

                <div class="step-connector {{ $currentStep >= 2 ? 'active' : '' }}"></div>

                {{-- BƯỚC 2: XÁC NHẬN --}}
                <div class="step-item {{ $currentStep >= 2 ? 'completed active' : '' }}">
                    <div class="step-circle">
                        <span class="step-num">2</span>
                    </div>
                    <div class="step-content">
                        <div class="step-title">Đã xác nhận</div>
                        <div class="step-desc">{{ $currentStep >= 2 ? 'Shop đang chuẩn bị hàng' : 'Chờ xác nhận' }}</div>
                    </div>
                </div>

                <div class="step-connector {{ $currentStep >= 3 ? 'active' : '' }}"></div>

                {{-- BƯỚC 3: VẬN CHUYỂN --}}
                <div class="step-item {{ $currentStep >= 3 ? 'completed active' : '' }}">
                    <div class="step-circle">
                        <span class="step-num">3</span>
                    </div>
                    <div class="step-content">
                        <div class="step-title">Đang giao hàng</div>
                        <div class="step-desc">{{ $currentStep >= 3 ? 'Đang trên đường giao' : 'Chờ vận chuyển' }}</div>
                    </div>
                </div>

                <div class="step-connector {{ $currentStep >= 4 ? 'active' : '' }}"></div>

                {{-- BƯỚC 4: HOÀN THÀNH --}}
                <div class="step-item {{ $currentStep >= 4 ? 'completed active' : '' }}">
                    <div class="step-circle">
                        <span class="step-num">4</span>
                    </div>
                    <div class="step-content">
                        <div class="step-title">Đã nhận hàng</div>
                        <div class="step-desc">{{ $currentStep >= 4 ? 'Giao hàng thành công' : 'Chờ nhận hàng' }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ========================================================= --}}
    {{-- NỘI DUNG CHI TIẾT ĐƠN HÀNG (2 CỘT) --}}
    {{-- ========================================================= --}}
    <div class="order-detail-grid">
        {{-- CỘT TRÁI: DANH SÁCH SẢN PHẨM & TỔNG TIỀN --}}
        <div class="order-detail-main">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Sản phẩm đã đặt ({{ $order->details->count() }})</h3>
                </div>

                <div class="card-body p-0">
                    <div class="order-items-table-wrap">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td>
                                            <div class="order-item-cell">
                                                @if($detail->product && $detail->product->image)
                                                    <img src="{{ asset('images/products/' . $detail->product->image) }}" alt="{{ $detail->product_name }}" class="order-item-thumb">
                                                @else
                                                    <div class="order-item-thumb no-img">No Image</div>
                                                @endif
                                                <div>
                                                    <div class="order-item-name">{{ $detail->product_name }}</div>
                                                    @if($detail->product)
                                                        <a href="{{ route('products.show', $detail->product) }}" class="order-item-link" target="_blank">Xem sản phẩm ↗</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="variant-tags-stack">
                                                @if($detail->size)
                                                    <span class="variant-tag">Size: {{ $detail->size }}</span>
                                                @endif
                                                @if($detail->color)
                                                    <span class="variant-tag">Màu: {{ $detail->color }}</span>
                                                @endif
                                                @if(!$detail->size && !$detail->color)
                                                    <span class="text-muted">Mặc định</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center font-bold">x{{ $detail->quantity }}</td>
                                        <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }} VNĐ</td>
                                        <td class="text-end font-bold price-highlight">
                                            {{ number_format($detail->subtotal, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TỔNG KẾT TIỀN --}}
                <div class="order-pricing-summary">
                    <div class="pricing-row">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->details->sum('subtotal'), 0, ',', '.') }} VNĐ</span>
                    </div>

                    @if($order->discount_amount && $order->discount_amount > 0)
                        <div class="pricing-row discount-row">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</span>
                        </div>
                    @endif

                    <div class="pricing-row">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success font-bold">Miễn phí</span>
                    </div>

                    <div class="pricing-divider"></div>

                    <div class="pricing-row total-row">
                        <span>Tổng thanh toán:</span>
                        <span class="grand-total">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: THÔNG TIN GIAO HÀNG & HÀNH ĐỘNG --}}
        <div class="order-detail-side">
            {{-- THÔNG TIN NHẬN HÀNG --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Thông tin nhận hàng</h3>
                </div>
                <div class="card-body">
                    <div class="info-block">
                        <div class="info-label">Người nhận:</div>
                        <div class="info-value font-bold">{{ $order->customer_name }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-label">Số điện thoại:</div>
                        <div class="info-value">{{ $order->customer_phone }}</div>
                    </div>
                    @if($order->customer_email)
                        <div class="info-block">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ $order->customer_email }}</div>
                        </div>
                    @endif
                    <div class="info-block">
                        <div class="info-label">Địa chỉ giao:</div>
                        <div class="info-value">{{ $order->shipping_address }}</div>
                    </div>
                </div>
            </div>

            {{-- PHƯƠNG THỨC THANH TOÁN --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Thanh toán</h3>
                </div>
                <div class="card-body">
                    <div class="info-block">
                        <div class="info-label">Hình thức:</div>
                        <div class="info-value font-bold">
                            @if($order->payment_method === 'cod')
                                💵 Thanh toán khi nhận hàng (COD)
                            @elseif($order->payment_method === 'qr')
                                📱 Chuyển khoản qua mã QR
                            @else
                                {{ strtoupper($order->payment_method) }}
                            @endif
                        </div>
                    </div>

                    <div class="info-block">
                        <div class="info-label">Trạng thái:</div>
                        <div class="info-value">
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">✓ Đã thanh toán</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="badge badge-danger">✕ Thanh toán thất bại</span>
                            @else
                                <span class="badge badge-warning">🕒 Chưa thanh toán</span>
                            @endif
                        </div>
                    </div>

                    @if($order->payment_method === 'qr' && $order->payment_status === 'paid')
                        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 14px; margin-top: 14px;">
                            <div style="display: flex; align-items: center; gap: 6px; color: #065f46; font-weight: 700; font-size: 0.95rem; margin-bottom: 4px;">
                                <span>🎉</span> ĐÃ XÁC THỰC THANH TOÁN QR
                            </div>
                            <p style="margin: 0; color: #047857; font-size: 0.85rem; line-height: 1.4;">
                                Giao dịch thanh toán chuyển khoản qua mã QR đã được hệ thống xác thực thành công. Shop đang chuẩn bị sản phẩm để giao đến bạn!
                            </p>
                        </div>
                    @endif

                    @if($order->payment_method === 'qr' && $order->payment_status !== 'paid' && $order->order_status !== 'cancelled')
                        <div class="qr-pay-box mt-3">
                            <p class="qr-tip">Đơn hàng thanh toán qua QR. Bạn có thể bấm nút dưới để xem mã QR và thanh toán:</p>
                            <a href="{{ route('payment.qr', $order) }}" class="btn btn-success btn-block">
                                📲 Mở mã QR thanh toán ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- HÀNH ĐỘNG ĐƠN HÀNG --}}
            <div class="card order-actions-card">
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-block">
                        🛍️ Tiếp tục mua sắm
                    </a>

                    @if($order->order_status === 'pending')
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng #{{ $order->id }} không?');">
                            @csrf
                            <button type="submit" class="btn btn-danger-outline btn-block">
                                ✕ Hủy đơn hàng này
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection