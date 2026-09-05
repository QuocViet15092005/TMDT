@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="container section-space">
    <!-- Header -->
    <div style="margin-bottom: 28px;">
        <h1 class="section-title" style="margin-bottom: 6px;">🛒 Giỏ hàng của bạn</h1>
        <p class="text-muted" style="margin: 0; font-size: 0.95rem;">Kiểm tra và quản lý các sản phẩm thể thao bạn đã chọn</p>
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

    {{-- GIỎ HÀNG TRỐNG --}}
    @if(empty($cart))
        <div style="text-align: center; padding: 72px 24px; background: var(--white); border-radius: var(--radius-xl); border: 1px dashed var(--border-color); box-shadow: var(--shadow-sm); max-width: 680px; margin: 0 auto;">
            <div style="font-size: 4rem; margin-bottom: 16px;">🛍️</div>
            <h2 style="color: var(--dark-color); margin-bottom: 10px; font-size: 1.5rem; font-weight: 800;">Giỏ hàng của bạn đang trống</h2>
            <p class="text-muted" style="margin-bottom: 28px; font-size: 0.98rem;">Hãy khám phá thêm hàng trăm sản phẩm thể thao chất lượng cao tại Sport Shop.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                👟 Khám phá sản phẩm ngay
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start;">
            
            <!-- Main Cart Items List -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($cart as $variantId => $item)
                    <div class="card" style="padding: 20px; display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                        
                        <!-- Product Image -->
                        <div style="width: 100px; height: 100px; border-radius: var(--radius-md); overflow: hidden; background: #f1f5f9; flex-shrink: 0; border: 1px solid var(--border-color);">
                            <img 
                                src="{{ $item['image'] ? asset('images/products/' . $item['image']) : asset('images/no-image.jpg') }}" 
                                alt="{{ $item['name'] }}" 
                                style="width: 100%; height: 100%; object-fit: cover;"
                            >
                        </div>
                        
                        <!-- Product Info -->
                        <div style="flex: 1; min-width: 220px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 6px;">
                                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--dark-color); margin: 0;">
                                    {{ $item['name'] }}
                                </h3>
                                <form action="{{ route('cart.remove', $variantId) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')" 
                                        style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--slate-400); padding: 4px; line-height: 1; transition: var(--transition-fast);"
                                        onmouseover="this.style.color='#ef4444'"
                                        onmouseout="this.style.color='var(--slate-400)'"
                                        title="Xóa khỏi giỏ"
                                    >
                                        ✕
                                    </button>
                                </form>
                            </div>

                            <!-- Variants Info -->
                            <div style="display: flex; gap: 10px; margin-bottom: 12px; font-size: 0.85rem;">
                                @if(!empty($item['size']))
                                    <span class="badge badge-primary">Size: {{ $item['size'] }}</span>
                                @endif
                                @if(!empty($item['color']))
                                    <span class="badge badge-info">Màu: {{ $item['color'] }}</span>
                                @endif
                            </div>

                            <!-- Price & Quantity Control -->
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; padding-top: 12px; border-top: 1px dashed var(--border-color);">
                                <div>
                                    <div style="font-size: 0.8rem; color: var(--slate-500);">Đơn giá</div>
                                    <div style="font-weight: 700; color: var(--secondary-color); font-size: 1.05rem;">
                                        {{ number_format($item['price'], 0, ',', '.') }} VNĐ
                                    </div>
                                </div>

                                <!-- Quantity Control -->
                                <form action="{{ route('cart.update', $variantId) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                    @csrf
                                    @method('PATCH')
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        value="{{ $item['quantity'] }}" 
                                        min="1" 
                                        max="999"
                                        style="width: 70px; padding: 6px 8px; text-align: center; font-weight: 700; font-size: 0.95rem; border-radius: var(--radius-sm);"
                                    >
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.82rem;">
                                        Cập nhật
                                    </button>
                                </form>

                                <div style="text-align: right;">
                                    <div style="font-size: 0.8rem; color: var(--slate-500);">Tổng tiền</div>
                                    <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary-color);">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VNĐ
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        ← Tiếp tục chọn thêm sản phẩm
                    </a>

                    <form action="{{ route('cart.clear') }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            onclick="return confirm('Bạn có chắc muốn xóa sạch toàn bộ giỏ hàng?')"
                            class="link-button"
                        >
                            🗑️ Xóa toàn bộ giỏ
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div style="position: sticky; top: 90px;">
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--dark-color); margin: 0 0 18px; padding-bottom: 14px; border-bottom: 1.5px solid var(--border-color);">
                        🧾 Tóm tắt đơn hàng
                    </h3>

                    @php
                        $itemCount = count($cart);
                        $totalUnits = array_sum(array_column($cart, 'quantity'));
                    @endphp

                    <div style="margin-bottom: 20px; display: grid; gap: 12px; font-size: 0.92rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span class="text-muted">Số loại sản phẩm:</span>
                            <strong>{{ $itemCount }} loại ({{ $totalUnits }} món)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span class="text-muted">Tạm tính tiền hàng:</span>
                            <strong>{{ number_format($total, 0, ',', '.') }} VNĐ</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="badge badge-success">Miễn phí giao hàng</span>
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

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 12px;">
                        💳 Tiến hành đặt hàng ngay
                    </a>

                    <div style="text-align: center; font-size: 0.8rem; color: var(--slate-400); margin-top: 12px;">
                        🔒 Đảm bảo thanh toán an toàn 100%
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>

<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns: 1fr 380px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
