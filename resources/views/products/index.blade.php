@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container section-space">

    {{-- HEADER BAR --}}
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;">👟 Tất cả sản phẩm</h1>
            <p class="text-muted" style="margin: 0; font-size: 0.95rem;">
                Hiển thị <strong>{{ $products->total() }}</strong> sản phẩm chất lượng cao
            </p>
        </div>
        
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div style="display: flex; gap: 10px; align-items: center;">
                <span class="badge badge-primary">🛡️ Chế độ Quản trị</span>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="font-size: 0.88rem;">
                    ➕ Thêm mới
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="font-size: 0.88rem;">
                    ⚙️ Quản lý kho
                </a>
            </div>
        @endif
    </div>

    {{-- THÔNG BÁO FLASH --}}
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- BỘ LỌC & TÌM KIẾM --}}
    <form method="GET" action="{{ route('products.index') }}" class="filter-form">
        <div>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="🔍 Tìm kiếm tên giày, áo, phụ kiện..."
            >
        </div>

        <div>
            <select name="category">
                <option value="">🏷️ Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(request('category') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="sort">
                <option value="">✨ Mới nhất trước</option>
                <option value="price_asc" @selected(request('sort') == 'price_asc')>
                    💵 Giá: Thấp → Cao
                </option>
                <option value="price_desc" @selected(request('sort') == 'price_desc')>
                    💎 Giá: Cao → Thấp
                </option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                Lọc
            </button>
            @if(request('search') || request('category') || request('sort'))
                <a href="{{ route('products.index') }}" class="btn btn-secondary" title="Đặt lại bộ lọc">
                    ✕
                </a>
            @endif
        </div>
    </form>

    {{-- DANH SÁCH SẢN PHẨM --}}
    <div class="product-grid">
        @forelse($products as $product)
            @php
                $inStock = $product->variants->where('quantity', '>', 0)->count() > 0;
                $totalQuantity = $product->variants->sum('quantity');
            @endphp

            <article class="product-card">
                <div class="product-card-media">
                    @if($product->image)
                        <img
                            src="{{ asset('images/products/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="product-image"
                            loading="lazy"
                        >
                    @else
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8; font-weight: 600;">
                            👟 Chưa có ảnh
                        </div>
                    @endif

                    @if(!$inStock)
                        <span class="product-card-badge" style="background: #64748b;">
                            Hết hàng
                        </span>
                    @elseif(($product->total_sold ?? 0) > 0)
                        <span class="product-card-badge">
                            🔥 Đã bán {{ $product->total_sold }}
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
                        {{ $product->category->name ?? 'Chưa phân loại' }}
                    </span>

                    <h3>
                        <a href="{{ route('products.show', $product) }}" style="color: inherit;">
                            {{ $product->name }}
                        </a>
                    </h3>

                    @if($product->brand)
                        <div style="font-size: 0.82rem; color: var(--slate-500); margin-bottom: 8px;">
                            Thương hiệu: <strong>{{ $product->brand }}</strong>
                        </div>
                    @endif

                    <div class="product-price">
                        {{ number_format($product->price, 0, ',', '.') }} <span style="font-size: 0.85rem; font-weight: 600;">VNĐ</span>
                    </div>

                    <div style="font-size: 0.82rem; margin-bottom: 14px;">
                        @if($inStock)
                            <span style="color: #10b981; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                ● Còn hàng ({{ $totalQuantity }} cái)
                            </span>
                        @else
                            <span style="color: #ef4444; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                ● Tạm hết hàng
                            </span>
                        @endif
                    </div>

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

                    {{-- ADMIN QUICK ACTIONS --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <div style="display: flex; gap: 6px; margin-top: 12px; padding-top: 10px; border-top: 1px dashed var(--border-color);">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary" style="flex: 1; padding: 4px 6px; font-size: 0.78rem;">
                                ✏️ Sửa
                            </a>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary" style="flex: 1; padding: 4px 6px; font-size: 0.78rem; background: #ecfdf5; color: #065f46; border-color: #a7f3d0;">
                                🎨 Size
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="width: 100%; padding: 4px 6px; font-size: 0.78rem;">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed var(--border-color);">
                <div style="font-size: 3.5rem; margin-bottom: 14px;">🔍</div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--dark-color); margin-bottom: 8px;">Không tìm thấy sản phẩm phù hợp</h3>
                <p class="text-muted" style="margin-bottom: 20px;">Vui lòng thử tìm kiếm bằng từ khóa khác hoặc xóa bộ lọc hiện tại.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    🔄 Xem tất cả sản phẩm
                </a>
            </div>
        @endforelse
    </div>

    {{-- PHÂN TRANG --}}
    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>

</div>
@endsection