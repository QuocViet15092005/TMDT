@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
{{-- SWIPER CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

{{-- CUSTOM CSS CHO HERO BANNER SLIDER --}}
<style>
.hero-slider-section {
    position: relative;
    background: #e2f1f8; /* Màu nền xanh nhạt rực rỡ như mẫu */
    overflow: hidden;
}

.hero-swiper {
    width: 100%;
    padding-bottom: 40px;
}

.hero-slide-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 480px;
    padding: 40px 0;
}

.hero-slide-content {
    flex: 1;
    max-width: 480px;
    z-index: 2;
}

.hero-brand-tag {
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 2px;
    color: #0f172a;
    text-transform: uppercase;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.hero-slide-title {
    font-size: 3.5rem;
    font-weight: 900;
    line-height: 1.05;
    color: #000000;
    text-transform: uppercase;
    letter-spacing: -1px;
    margin-bottom: 16px;
}

.hero-slide-desc {
    font-size: 0.95rem;
    color: #475569;
    margin-bottom: 24px;
    line-height: 1.5;
}

.btn-hero-buy {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    color: #000000;
    border: 1.5px solid #000000;
    padding: 10px 24px;
    font-weight: 800;
    font-size: 0.9rem;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.25s ease;
}

.btn-hero-buy:hover {
    background: #000000;
    color: #ffffff;
}

.hero-slide-image {
    flex: 1.2;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.hero-slide-image img {
    max-width: 100%;
    max-height: 420px;
    object-fit: contain;
    filter: drop-shadow(0 15px 25px rgba(0,0,0,0.15));
}

/* Nút phân trang (Dots) & Nút qua bài */
.swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #94a3b8;
    opacity: 0.6;
}

.swiper-pagination-bullet-active {
    background: #0284c7;
    opacity: 1;
    width: 24px;
    border-radius: 5px;
}

@media (max-width: 768px) {
    .hero-slide-item {
        flex-direction: column;
        text-align: center;
    }
    .hero-slide-content {
        max-width: 100%;
        margin-bottom: 20px;
    }
    .hero-slide-title {
        font-size: 2.2rem;
    }
    .hero-slide-image {
        justify-content: center;
    }
}
</style>

{{-- HERO SECTION BANNER SLIDER --}}
<section class="hero-slider-section">
    <div class="container">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                
               {{-- SLIDE 1: QUẦN ÁO BÓNG ĐÁ --}}
                <div class="swiper-slide">
                    <div class="hero-slide-item">
                        <div class="hero-slide-content">
                            <div class="hero-brand-tag">
                                <span>MIZUNO</span> / <span>01.2026</span>
                            </div>
                            <h1 class="hero-slide-title">UNITY SKY<br>PACK</h1>
                            <p class="hero-slide-desc">Khám phá bộ sưu tập giày bóng đá sân cỏ nhân tạo và Futsal mới nhất với phối màu xanh bầu trời rực rỡ.</p>
                            <a href="{{ route('products.index') }}" class="btn-hero-buy">
                                MUA NGAY <span>▷</span>
                            </a>
                        </div>
                        <div class="hero-slide-image">
                            <img src="https://images.unsplash.com/photo-1518091043644-c1d4457512c6?auto=format&fit=crop&w=800&q=80" 
                                 alt="Quần áo bóng đá" 
                                 onerror="this.src='https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80'">
                        </div>
                    </div>
                </div>

              {{-- SLIDE 2: GIÀY BÓNG ĐÁ --}}
                <div class="swiper-slide">
                    <div class="hero-slide-item">
                        <div class="hero-slide-content">
                            <div class="hero-brand-tag">
                                <span>NIKE</span> / <span>MERCURIAL</span>
                            </div>
                            <h1 class="hero-slide-title">SPEED &<br>POWER</h1>
                            <p class="hero-slide-desc">Bứt phá tốc độ trên mọi mặt sân với dòng sản phẩm cao cấp vừa cập bến tại Sport Shop.</p>
                            <a href="{{ route('products.index') }}" class="btn-hero-buy">
                                MUA NGAY <span>▷</span>
                            </a>
                        </div>
                        <div class="hero-slide-image">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80" 
                                 alt="Giày bóng đá"
                                 onerror="this.src='https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80'">
                        </div>
                    </div>
                </div>

                {{-- SLIDE 3: PHỤ KIỆN BÓNG ĐÁ --}}
                <div class="swiper-slide">
                    <div class="hero-slide-item">
                        <div class="hero-slide-content">
                            <div class="hero-brand-tag">
                                <span>ADIDAS</span> / <span>PREDATOR</span>
                            </div>
                            <h1 class="hero-slide-title">ACCURACY<br>CONTROL</h1>
                            <p class="hero-slide-desc">Kiểm soát bóng tuyệt đối, kiến tạo đường chuyền hoàn hảo cùng ưu đãi giảm giá đến 20%.</p>
                            <a href="{{ route('vouchers.index') }}" class="btn-hero-buy">
                                XEM KHUYẾN MÃI <span>▷</span>
                            </a>
                        </div>
                        <div class="hero-slide-image">
                            <img src="https://images.unsplash.com/photo-1614632537190-23e4146777db?auto=format&fit=crop&w=800&q=80" alt="Phụ kiện bóng đá">
                        </div>
                    </div>
                </div>

            </div>
            
            {{-- Dấu chấm chuyển Slide --}}
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

{{-- TRUST PERKS BANNER --}}
<div class="container" style="margin-top: 20px;">
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
                                 Đã bán {{ $product->total_sold }}
                            </span>
                        @else
                            <span class="product-card-badge" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                                 Bán chạy
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
            <h2 class="section-title" style="margin-bottom: 4px;"> Hàng mới về</h2>
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

{{-- SWIPER JS INIT --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            }
        });
    });
</script>
@endsection