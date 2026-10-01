@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="container section-space">
    <div class="orders-page-header" style="margin-bottom: 24px;">
        <h1 class="section-title">❤️ Sản phẩm yêu thích</h1>
        <p class="section-subtitle">Các sản phẩm bạn đã lưu để xem lại hoặc mua sau.</p>
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

    @if($wishlists->isEmpty())
        <div class="order-empty-state">
            <div class="empty-icon">💔</div>
            <h3 style="font-size: 1.35rem; margin-bottom: 8px; font-weight: 800;">Danh sách yêu thích đang trống</h3>
            <p class="text-muted" style="margin-bottom: 24px;">Hãy duyệt qua các mẫu giày đá bóng, áo đấu và phụ kiện yêu thích để lưu vào đây nhé.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                👟 Khám phá sản phẩm ngay
            </a>
        </div>
    @else
        <div class="product-grid">
            @foreach($wishlists as $wishlist)
                @php
                    $product = $wishlist->product;
                @endphp
                @if($product)
                    <article class="product-card">
                        <div class="product-card-media">
                            <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                            
                            <form action="{{ route('wishlists.remove', $product) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="product-wishlist-btn" style="color: #ef4444; border-color: #fecaca;" title="Bỏ yêu thích">
                                    ❤️
                                </button>
                            </form>
                        </div>
                        
                        <div class="product-info">
                            <span class="product-category-tag">
                                {{ $product->category->name ?? 'Thể thao' }}
                            </span>
                            <h3>
                                <a href="{{ route('products.show', $product) }}" style="color: inherit;">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="product-price">
                                {{ number_format($product->price, 0, ',', '.') }} <span style="font-size: 0.85rem; font-weight: 600;">VNĐ</span>
                            </div>
                            
                            @php
                                $inStock = $product->variants && $product->variants->where('quantity', '>', 0)->count() > 0;
                            @endphp

                            <div class="product-actions" style="grid-template-columns: 1fr;">
                                @if($inStock)
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary">
                                        ⚡ Xem & Chọn size
                                    </a>
                                @else
                                    <button type="button" class="btn btn-secondary" disabled>
                                        Tạm hết hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endif
            @endforeach
        </div>

        @if($wishlists->hasPages())
            <div class="pagination-wrap">
                {{ $wishlists->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

