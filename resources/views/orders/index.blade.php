@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container section-space">
    <div class="orders-page-header">
        <h1 class="section-title">Đơn hàng của tôi</h1>
        <p class="section-subtitle">Theo dõi trạng thái và tiến trình các đơn hàng bạn đã đặt.</p>
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

    {{-- TABS LỌC TRẠNG THÁI --}}
    <div class="order-tabs">
        <a href="{{ route('orders.index') }}" class="order-tab {{ !request('status') ? 'active' : '' }}">
            Tất cả
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="order-tab {{ request('status') === 'pending' ? 'active' : '' }}">
            Chờ xác nhận
        </a>
        <a href="{{ route('orders.index', ['status' => 'confirmed']) }}" class="order-tab {{ request('status') === 'confirmed' ? 'active' : '' }}">
            Đã xác nhận
        </a>
        <a href="{{ route('orders.index', ['status' => 'shipping']) }}" class="order-tab {{ request('status') === 'shipping' ? 'active' : '' }}">
            Đang giao
        </a>
        <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="order-tab {{ request('status') === 'completed' ? 'active' : '' }}">
            Hoàn thành
        </a>
        <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="order-tab {{ request('status') === 'cancelled' ? 'active' : '' }}">
            Đã hủy
        </a>
    </div>

    {{-- DANH SÁCH ĐƠN HÀNG --}}
    <div class="orders-list">
        @forelse($orders as $order)
            <div class="order-card">
                {{-- HEADER ĐƠN HÀNG --}}
                <div class="order-card-header">
                    <div class="order-meta">
                        <span class="order-id">Đơn hàng #{{ $order->id }}</span>
                        <span class="order-date">• {{ $order->created_at?->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="order-badges">
                        {{-- TRẠNG THÁI THANH TOÁN --}}
                        @if($order->payment_status === 'paid')
                            <span class="badge badge-success">Đã thanh toán</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="badge badge-danger">Thanh toán thất bại</span>
                        @else
                            <span class="badge badge-warning">Chưa thanh toán ({{ strtoupper($order->payment_method) }})</span>
                        @endif

                        {{-- TRẠNG THÁI ĐƠN HÀNG --}}
                        @switch($order->order_status)
                            @case('pending')
                                <span class="badge badge-warning">🕒 Chờ xác nhận</span>
                                @break
                            @case('confirmed')
                                <span class="badge badge-primary">📦 Đã xác nhận</span>
                                @break
                            @case('shipping')
                                <span class="badge badge-info">🚚 Đang giao hàng</span>
                                @break
                            @case('completed')
                                <span class="badge badge-success">✓ Hoàn thành</span>
                                @break
                            @case('cancelled')
                                <span class="badge badge-danger">✕ Đã hủy</span>
                                @break
                            @default
                                <span class="badge">{{ $order->order_status }}</span>
                        @endswitch
                    </div>
                </div>

                {{-- DANH SÁCH SẢN PHẨM TRONG ĐƠN --}}
                <div class="order-card-body">
                    @foreach($order->details as $detail)
                        <div class="order-product-item">
                            <div class="order-product-image">
                                @if($detail->product && $detail->product->image)
                                    <img src="{{ asset('images/products/' . $detail->product->image) }}" alt="{{ $detail->product_name }}">
                                @else
                                    <div class="order-no-image">No Image</div>
                                @endif
                            </div>

                            <div class="order-product-info">
                                <h4 class="order-product-name">{{ $detail->product_name }}</h4>
                                <div class="order-product-variant">
                                    @if($detail->size)
                                        <span class="variant-tag">Size: {{ $detail->size }}</span>
                                    @endif
                                    @if($detail->color)
                                        <span class="variant-tag">Màu: {{ $detail->color }}</span>
                                    @endif
                                    <span class="variant-quantity">x{{ $detail->quantity }}</span>
                                </div>
                            </div>

                            <div class="order-product-price">
                                <span>{{ number_format($detail->price, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- FOOTER ĐƠN HÀNG --}}
                <div class="order-card-footer">
                    <div class="order-total-section">
                        <span class="total-label">Tổng tiền:</span>
                        <span class="total-price">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                    </div>

                    <div class="order-actions">
                        {{-- NÚT XEM CHI TIẾT & TIẾN TRÌNH --}}
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">
                            Xem tiến trình & chi tiết
                        </a>

                        {{-- NÚT THANH TOÁN QR NẾU CHƯA TRẢ --}}
                        @if($order->payment_method === 'qr' && $order->payment_status !== 'paid' && $order->order_status !== 'cancelled')
                            <a href="{{ route('payment.qr', $order) }}" class="btn btn-success">
                                Thanh toán QR
                            </a>
                        @endif

                        {{-- NÚT HỦY ĐƠN KHI ĐANG PENDING --}}
                        @if($order->order_status === 'pending')
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                @csrf
                                <button type="submit" class="btn btn-danger-outline">
                                    Hủy đơn
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="order-empty-state">
                <div class="empty-icon">📦</div>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa đặt đơn hàng nào ở trạng thái này.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        @endforelse
    </div>

    {{-- PHÂN TRANG --}}
    @if($orders->hasPages())
        <div class="pagination-wrap">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
