<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-badge">⚡</span>
            <span>SPORT SHOP</span>
        </a>

        <nav class="main-nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                🏠 Trang chủ
            </a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                👟 Sản phẩm
            </a>
            
            @php
                $cartCount = count(session('cart', []));
            @endphp
            <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                🛒 Giỏ hàng
                @if($cartCount > 0)
                    <span class="nav-badge">{{ $cartCount }}</span>
                @endif
            </a>

            @auth
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

                <form action="{{ route('logout') }}" method="POST" class="inline-form">
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
        </nav>
    </div>

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

