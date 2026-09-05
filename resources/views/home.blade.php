@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
{{-- HERO SECTION --}}
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-badge">
            <span>🔥</span> BỘ SƯU TẬP THỂ THAO MÙA HÈ 2026 MỚI NHẤT
        </div>
        <h1>Khơi Nguồn Đam Mê & Bứt Phá Giới Hạn Cùng SPORT SHOP</h1>
        <p>Khám phá hơn 1.000+ mẫu giày bóng đá, quần áo thể thao, phụ kiện tập luyện chính hãng từ các thương hiệu hàng đầu thế giới với ưu đãi hấp dẫn nhất.</p>
        <div class="hero-actions">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                ⚡ Mua sắm ngay
            </a>
            <a href="{{ route('products.index', ['sort' => 'price_asc']) }}" class="btn btn-secondary btn-lg" style="background: rgba(255,255,255,0.15); color: #fff !important; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);">
                🏷️ Xem khuyến mãi
            </a>
        </div>
    </div>
</section>

{{-- TRUST PERKS BANNER --}}
<div class="container">
    <div class="perks-grid">
        <div class="perk-card">
            <div class="perk-icon">🚚</div>
            <div class="perk-info">
                <h4>Giao hàng hỏa tốc</h4>
                <p>Nhận hàng nhanh trong 24h</p>
            </div>
        </div>
        <div class="perk-card">
            <div class="perk-icon">🛡️</div>
            <div class="perk-info">
                <h4>100% Chính hãng</h4>
                <p>Cam kết chất lượng chuẩn hãng</p>
            </div>
        </div>
        <div class="perk-card">
            <div class="perk-icon">🔄</div>
            <div class="perk-info">
                <h4>Đổi trả 7 ngày</h4>
                <p>Đổi size miễn phí dễ dàng</p>
            </div>
        </div>
        <div class="perk-card">
            <div class="perk-icon">💬</div>
            <div class="perk-info">
                <h4>Hỗ trợ 24/7</h4>
                <p>Tư vấn chuyên nghiệp nhiệt tình</p>
            </div>
        </div>
    </div>
</div>

{{-- DANH MỤC NỔI BẬT --}}
<div class="container section-space">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <h2 class="section-title" style="margin-bottom: 0;">🏷️ Danh mục sản phẩm</h2>
        <a href="{{ route('products.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 14px;">Xem tất cả &rarr;</a>
    </div>
    <div class="category-list">
        <a href="{{ route('products.index') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
            🌟 Tất cả danh mục
        </a>
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                👟 {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>

{{-- SẢN PHẨM BÁN CHẠY --}}
@if(isset($topSellingProducts) && $topSellingProducts->count() > 0)
    <div class="container section-space" style="padding-top: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h2 class="section-title" style="margin-bottom: 4px;">🔥 Sản phẩm bán chạy nhất</h2>
                <p class="text-muted" style="font-size: 0.92rem; margin: 0;">Những mẫu giày và trang phục được yêu thích và lựa chọn nhiều nhất</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary" style="font-size: 0.88rem; padding: 8px 16px;">Xem tất cả &rarr;</a>
        </div>
        
        <div class="product-grid">
            @foreach($topSellingProducts as $product)
                <article class="product-card">
                    <div class="product-card-media">
                        <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                        
                        @if(($product->total_sold ?? 0) > 0)
                            <span class="product-card-badge">
                                🔥 Đã bán {{ $product->total_sold }}
                            </span>
                        @else
                            <span class="product-card-badge" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                                ⭐ Bán chạy
                            </span>
                        @endif

                        {{-- NÚT YÊU THÍCH --}}
                        @auth
                            @php
                                $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                                    ->where('product_id', $product->id)
                                    ->exists();
                            @endphp
                            @if($isWishlisted)
                                <form action="{{ route('wishlists.remove', $product) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Bỏ yêu thích" class="product-wishlist-btn" style="border-color: #fecaca; color: #ef4444;">
                                        ❤️
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('wishlists.add') }}" method="POST" class="inline-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" title="Thêm vào yêu thích" class="product-wishlist-btn">
                                        🤍
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login.form') }}" title="Đăng nhập để thêm vào yêu thích" class="product-wishlist-btn">
                                🤍
                            </a>
                        @endauth
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
                            $inStock = $product->variants->where('quantity', '>', 0)->count() > 0;
                        @endphp
                        <div class="product-actions">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
                                Chi tiết
                            </a>
                            @if($inStock)
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary">
                                    Chọn size
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    Hết hàng
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif

{{-- SẢN PHẨM MỚI NHẤT --}}
<div class="container section-space" style="padding-top: 10px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2 class="section-title" style="margin-bottom: 4px;">✨ Hàng mới về</h2>
            <p class="text-muted" style="font-size: 0.92rem; margin: 0;">Cập nhật những mẫu sản phẩm mới nhất vừa cập bến Sport Shop</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary" style="font-size: 0.88rem; padding: 8px 16px;">Xem tất cả &rarr;</a>
    </div>

    <div class="product-grid">
        @foreach($products as $product)
            <article class="product-card">
                <div class="product-card-media">
                    <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                    
                    {{-- NÚT YÊU THÍCH --}}
                    @auth
                        @php
                            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                                ->where('product_id', $product->id)
                                ->exists();
                        @endphp
                        @if($isWishlisted)
                            <form action="{{ route('wishlists.remove', $product) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Bỏ yêu thích" class="product-wishlist-btn" style="border-color: #fecaca; color: #ef4444;">
                                    ❤️
                                </button>
                            </form>
                        @else
                            <form action="{{ route('wishlists.add') }}" method="POST" class="inline-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" title="Thêm vào yêu thích" class="product-wishlist-btn">
                                    🤍
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.form') }}" title="Đăng nhập để thêm vào yêu thích" class="product-wishlist-btn">
                            🤍
                        </a>
                    @endauth
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
                        $inStock = $product->variants->where('quantity', '>', 0)->count() > 0;
                    @endphp
                    <div class="product-actions">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
                            Chi tiết
                        </a>
                        @if($inStock)
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary">
                                Chọn size
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary" disabled>
                                Hết hàng
                            </button>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection

