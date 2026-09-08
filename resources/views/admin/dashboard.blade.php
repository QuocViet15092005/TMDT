@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container section-space">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;">📊 Bảng điều khiển Quản trị (Admin Dashboard)</h1>
            <p class="section-subtitle" style="margin: 0;">Xin chào <strong>{{ auth()->user()->name }}</strong>, chào mừng bạn đến với trang quản trị Sport Shop.</p>
        </div>
        <div>
            <a href="{{ route('home') }}" class="btn btn-secondary" style="font-size: 0.9rem;">
                🌐 Xem trang khách hàng
            </a>
        </div>
    </div>

    {{-- THỐNG KÊ TỔNG QUAN & DOANH THU (CLICK ĐỂ XEM TRỰC TIẾP CHI TIẾT) --}}
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        {{-- DOANH THU --}}
        <a href="{{ route('admin.statistics.index') }}" class="stat-box-card" style="border-left: 4px solid var(--secondary-color);" title="Bấm để xem Báo cáo doanh số chi tiết">
            <div>
                <h3>💰 Tổng doanh thu</h3>
                <div class="stat-value" style="color: var(--secondary-color);">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                <small style="color: #64748b;">Từ các đơn đã thanh toán</small>
            </div>
            <div class="stat-footer">
                <span>Xem báo cáo</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>

        {{-- TỔNG ĐƠN HÀNG --}}
        <a href="{{ route('admin.orders.index') }}" class="stat-box-card" style="border-left: 4px solid var(--primary-color);" title="Bấm để xem Danh sách tất cả đơn hàng">
            <div>
                <h3>📦 Tổng đơn hàng</h3>
                <div class="stat-value" style="color: var(--primary-color);">{{ $totalOrders }}</div>
                <small style="color: #64748b;">Tất cả đơn hàng</small>
            </div>
            <div class="stat-footer">
                <span>Xem danh sách đơn</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>

        {{-- ĐƠN CHỜ XỬ LÝ --}}
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="stat-box-card" style="border-left: 4px solid #f59e0b; background: {{ $pendingOrders > 0 ? '#fffdf7' : '#ffffff' }};" title="Bấm để xem danh sách các đơn hàng chờ xác nhận">
            <div>
                <h3>🕒 Đơn chờ xử lý</h3>
                <div class="stat-value" style="color: #d97706;">{{ $pendingOrders }}</div>
                <small style="color: {{ $pendingOrders > 0 ? '#d97706' : '#64748b' }}; font-weight: {{ $pendingOrders > 0 ? '600' : 'normal' }};">
                    {{ $pendingOrders > 0 ? '⚠️ Cần xác nhận ngay' : 'Không có đơn chờ' }}
                </small>
            </div>
            <div class="stat-footer">
                <span style="color: #d97706; font-weight: 600;">Xử lý ngay</span>
                <span class="stat-link-arrow" style="color: #d97706;">→</span>
            </div>
        </a>

        {{-- TỔNG SẢN PHẨM --}}
        <a href="{{ route('admin.products.index') }}" class="stat-box-card" style="border-left: 4px solid #10b981;" title="Bấm để xem Quản lý danh sách sản phẩm">
            <div>
                <h3>👟 Tổng sản phẩm</h3>
                <div class="stat-value" style="color: #10b981;">{{ $totalProducts }}</div>
                <small style="color: #64748b;">Mặt hàng đang quản lý</small>
            </div>
            <div class="stat-footer">
                <span>Quản lý kho</span>
                <span class="stat-link-arrow" style="color: #10b981;">→</span>
            </div>
        </a>

        {{-- KHÁCH HÀNG --}}
        <a href="{{ route('admin.users.index') }}" class="stat-box-card" style="border-left: 4px solid #6366f1;" title="Bấm để xem Danh sách khách hàng">
            <div>
                <h3>👥 Khách hàng</h3>
                <div class="stat-value" style="color: #6366f1;">{{ $totalUsers }}</div>
                <small style="color: #64748b;">Tài khoản thành viên</small>
            </div>
            <div class="stat-footer">
                <span>Xem khách hàng</span>
                <span class="stat-link-arrow" style="color: #6366f1;">→</span>
            </div>
        </a>
    </div>

    {{-- XEM TRỰC TIẾP: ĐƠN HÀNG GẦN ĐÂY CẦN THEO DÕI --}}
    <div class="panel" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <h2 style="margin: 0; font-size: 1.25rem; display: flex; align-items: center; gap: 8px;">
                📋 Đơn hàng mới nhất cần theo dõi
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 14px;">
                Xem tất cả đơn hàng &rarr;
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="alert alert-info" style="margin: 0;">
                Hiện tại chưa có đơn hàng nào được tạo.
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái đơn</th>
                            <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <strong>{{ $order->customer_name }}</strong>
                                    <br>
                                    <small style="color: #64748b;">{{ $order->customer_phone }}</small>
                                </td>
                                <td>
                                    <strong style="color: var(--secondary-color);">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                    </strong>
                                </td>
                                <td>
                                    @if($order->payment_method === 'cod')
                                        <span class="badge" style="background: #e2e8f0; color: #334155;">COD</span>
                                    @elseif($order->payment_method === 'qr')
                                        <span class="badge" style="background: #e0e7ff; color: #4338ca;">QR Code</span>
                                    @else
                                        <span class="badge" style="background: #e2e8f0; color: #334155;">{{ $order->payment_method }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="badge bg-danger">Thất bại</span>
                                    @else
                                        <span class="badge bg-warning">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($order->order_status)
                                        @case('pending')
                                            <span class="badge bg-warning">🕒 Chờ xác nhận</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-primary">✔️ Đã xác nhận</span>
                                            @break
                                        @case('shipping')
                                            <span class="badge bg-info">🚚 Đang giao</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">✅ Hoàn thành</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">❌ Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge">{{ $order->order_status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <small>{{ $order->created_at?->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.85rem;">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- PHẦN DƯỚI: SẢN PHẨM & CÁC CHỨC NĂNG QUẢN TRỊ --}}
    <div class="dashboard-grid-2">
        {{-- XEM TRỰC TIẾP: SẢN PHẨM MỚI / NỔI BẬT --}}
        <div class="panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="margin: 0; font-size: 1.15rem;">👟 Sản phẩm trong kho</h2>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 4px 10px;">
                    Xem tất cả &rarr;
                </a>
            </div>

            @if($topProducts->isEmpty())
                <p style="color: #64748b; margin: 0;">Chưa có sản phẩm nào.</p>
            @else
                <div class="mini-product-list">
                    @foreach($topProducts as $prod)
                        <a href="{{ route('admin.products.edit', $prod) }}" class="mini-product-item" title="Bấm để chỉnh sửa sản phẩm">
                            @if($prod->image)
                                <img src="{{ asset('images/products/' . $prod->image) }}" alt="{{ $prod->name }}" class="mini-product-img" onerror="this.src='{{ asset('images/no-image.jpg') }}'">
                            @else
                                <div class="mini-product-img" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">👟</div>
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--dark-color);">
                                    {{ $prod->name }}
                                </div>
                                <div style="font-size: 0.8rem; color: #64748b;">
                                    {{ $prod->category->name ?? 'Không có danh mục' }} &bull; Còn {{ $prod->variants->sum('quantity') }} chiếc
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: var(--primary-color); font-size: 0.95rem;">
                                    {{ number_format($prod->price, 0, ',', '.') }} đ
                                </div>
                                <span style="font-size: 0.75rem; color: #64748b;">Sửa &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- LỐI TẮT QUẢN LÝ HỆ THỐNG --}}
        <div class="panel">
            <h2 style="margin: 0 0 16px; font-size: 1.15rem;">🛠️ Lối tắt quản lý hệ thống</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.orders.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-icon" style="font-size: 1.5rem;">📦</div>
                    <div class="admin-quick-info">
                        <h4 style="font-size: 1rem; margin-bottom: 2px;">Quản lý Đơn hàng</h4>
                        <p>Xem, cập nhật trạng thái đơn (Chờ xác nhận, Giao hàng, Hoàn thành), kiểm tra thanh toán.</p>
                    </div>
                </a>

                <a href="{{ route('admin.products.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-icon" style="font-size: 1.5rem;">👟</div>
                    <div class="admin-quick-info">
                        <h4 style="font-size: 1rem; margin-bottom: 2px;">Quản lý Sản phẩm</h4>
                        <p>Thêm sản phẩm mới, cập nhật giá, hình ảnh, quản lý các biến thể Size & Màu sắc.</p>
                    </div>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-icon" style="font-size: 1.5rem;">🏷️</div>
                    <div class="admin-quick-info">
                        <h4 style="font-size: 1rem; margin-bottom: 2px;">Quản lý Danh mục</h4>
                        <p>Thêm mới, sửa, xóa các danh mục thể thao (Giày đá bóng, Áo đấu, Phụ kiện...).</p>
                    </div>
                </a>

                <a href="{{ route('admin.discounts.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-icon" style="font-size: 1.5rem;">🎟️</div>
                    <div class="admin-quick-info">
                        <h4 style="font-size: 1rem; margin-bottom: 2px;">Quản lý Mã giảm giá</h4>
                        <p>Tạo và quản lý voucher khuyến mãi cho khách hàng khi mua sắm.</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-icon" style="font-size: 1.5rem;">👥</div>
                    <div class="admin-quick-info">
                        <h4 style="font-size: 1rem; margin-bottom: 2px;">Quản lý Khách hàng</h4>
                        <p>Xem danh sách thành viên, số lượng đơn hàng đã đặt của từng khách hàng.</p>
                    </div>
                </a>

                <a href="{{ route('admin.statistics.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-icon" style="font-size: 1.5rem;">📈</div>
                    <div class="admin-quick-info">
                        <h4 style="font-size: 1rem; margin-bottom: 2px;">Báo cáo Thống kê & Doanh số</h4>
                        <p>Xem biểu đồ doanh thu theo tháng, sản phẩm bán chạy nhất và xuất file báo cáo CSV.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection