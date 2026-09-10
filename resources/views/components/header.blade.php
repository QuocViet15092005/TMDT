<header class="site-header">
    {{-- HÀNG TÊN: LOGO - Ö TÌM KIẾM - HÀNH ĐỘNG TÀI KHOẢN & GIỎ HÀNG --}}
    <div class="header-top">
        <div class="container header-top-inner">
            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-badge">⚡</span>
                <span>SPORT SHOP</span>
            </a>

            {{-- Ô TÌM KIẾM CHÍNH GIỮA --}}
            <div class="header-search-box">
                <form action="{{ route('products.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Bạn đang tìm kiếm ..." autocomplete="off">
                    <button type="submit" aria-label="Tìm kiếm">
                        🔍
                    </button>
                </form>
            </div>

            {{-- CÁC PHÍM TẮT TÀI KHOẢN & GIỎ HÀNG BÊN PHẢI --}}
            <div class="header-user-actions">
                @php
                    $cartCount = count(session('cart', []));
                @endphp
                <a href="{{ route('cart.index') }}" class="header-action-btn cart-btn" title="Giỏ hàng">
                    🛒
                    @if($cartCount > 0)
                        <span class="nav-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <a href="{{ route('profile.show') }}" class="header-action-btn" title="Hồ sơ tài khoản">
                        👤
                    </a>
                @else
                    <a href="{{ route('login.form') }}" class="header-action-btn" title="Đăng nhập">
                        🔑
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- HÀNG DƯỚI: MAIN NAV MENU --}}
    <nav class="main-nav-bar">
        <div class="container">
            <div class="main-nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    🏠 Trang chủ
                </a>
                
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    👟 Sản phẩm
                </a>
                
                <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                    🛒 Giỏ hàng
                    @if($cartCount > 0)
                        <span class="nav-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    {{-- NÚT VOUCHER (CHỈ HIỆN KHI ĐÃ ĐĂNG NHẬP) --}}
                    <a href="{{ route('vouchers.index') }}" class="nav-link {{ request()->routeIs('vouchers.*') ? 'active' : '' }}">
                        🎫 Voucher
                    </a>

                    @php
                        $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                    @endphp
                    <a href="{{ route('wishlists.index') }}" class="nav-link {{ request()->routeIs('wishlists.*') ? 'active' : '' }}">
                        ❤️ Yêu thích
                        @if($wishlistCount > 0)
                            <span class="nav-badge" style="background: #ec4899;">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        📦 Đơn mua
                    </a>

                    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        👤 {{ Str::limit(auth()->user()->name, 12) }}
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="admin-badge-nav">
                            🛡️ Quản trị Admin
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline-form" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="link-button" title="Đăng xuất khỏi tài khoản">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}" class="nav-link {{ request()->routeIs('login.form') ? 'active' : '' }}">
                        🔑 Đăng nhập
                    </a>
                    <a href="{{ route('register.form') }}" class="btn btn-primary" style="padding: 6px 16px; font-size: 0.88rem;">
                        ✨ Đăng ký
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- MENU QUẢN TRỊ ADMIN (NẾU ĐANG LÀ ADMIN VÀ TRONG TRANG ADMIN) --}}
    @if(auth()->check() && auth()->user()->role === 'admin' && request()->is('admin*'))
        <div class="admin-subbar">
            <div class="container admin-subbar-inner">
                <span class="admin-subbar-title">⚙️ MENU QUẢN TRỊ:</span>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Tổng quan</a>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">📦 Đơn hàng</a>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">👟 Sản phẩm</a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">🏷️ Danh mục</a>
                <a href="{{ route('admin.discounts.index') }}" class="{{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">🎟️ Mã giảm giá</a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👥 Khách hàng</a>
                <a href="{{ route('admin.statistics.index') }}" class="{{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">📈 Báo cáo</a>
            </div>
        </div>
    @endif
</header>

<style>
.site-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

/* Hàng trên: Logo - Tìm kiếm - Icons */
.header-top {
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.header-top-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

/* Logo */
.logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    text-decoration: none;
    white-space: nowrap;
}

/* Thanh tìm kiếm */
.header-search-box {
    flex: 1;
    max-width: 600px;
    margin: 0 10px;
}

.search-form {
    position: relative;
    width: 100%;
}

.search-form input {
    width: 100%;
    height: 40px;
    padding: 0 40px 0 16px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s;
}

.search-form input:focus {
    border-color: #0f172a;
}

.search-form button {
    position: absolute;
    right: 0;
    top: 0;
    height: 40px;
    width: 40px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Icons góc phải hàng trên */
.header-user-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-action-btn {
    position: relative;
    font-size: 1.3rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Hàng dưới: Thanh Nav Menu */
.main-nav-bar {
    padding: 8px 0;
}

.main-nav {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
    text-decoration: none;
    white-space: nowrap;
    text-transform: uppercase;
}

.nav-link:hover,
.nav-link.active {
    color: #ef4444;
}

.nav-badge {
    background: #ef4444;
    color: #fff;
    font-size: 0.72rem;
    padding: 2px 6px;
    border-radius: 10px;
    line-height: 1;
}

/* Admin Badge - ĐÃ CẬP NHẬT NỀN XANH ĐẬM & CHỮ TRẮNG */
.main-nav a.admin-badge-nav,
.admin-badge-nav {
    background-color: #2563eb !important; /* Nền xanh đậm nổi bật */
    color: #ffffff !important;            /* Chữ màu trắng */
    border: 1px solid #1d4ed8 !important;
    padding: 6px 14px !important;
    border-radius: 6px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    transition: all 0.2s ease !important;
}

.main-nav a.admin-badge-nav:hover,
.admin-badge-nav:hover {
    background-color: #1d4ed8 !important; /* Xanh đậm hơn khi rê chuột vào */
    color: #ffffff !important;
}

.link-button {
    background: none;
    border: none;
    color: #64748b;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    text-transform: uppercase;
    padding: 0;
}

.link-button:hover {
    color: #ef4444;
}

/* Admin Subbar */
.admin-subbar {
    background: #0f172a;
    color: #ffffff;
    padding: 8px 0;
    font-size: 0.82rem;
}

.admin-subbar-inner {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.admin-subbar-title {
    font-weight: 700;
    color: #f59e0b;
}

.admin-subbar a {
    color: #cbd5e1;
    text-decoration: none;
}

.admin-subbar a:hover,
.admin-subbar a.active {
    color: #ffffff;
    font-weight: 700;
}
</style>