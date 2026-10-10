@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    /* Reset & Base Scoping */
    .dashboard-wrapper {
        padding: 28px 0;
    }

    .orders-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }

    /* Grid Thống kê 5 Thẻ - Chuẩn Card Trắng Hiện Đại */
    .dashboard-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-box-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        border-top: 4px solid #2563eb; /* Viền trên xanh biển đồng bộ */
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
        min-height: 140px;
    }

    .stat-box-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.08);
    }

    .stat-box-card h3 {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0 0 8px 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 4px;
        line-height: 1.2;
        color: #0f172a;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #2563eb;
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
    }

    .stat-link-arrow {
        transition: transform 0.2s;
    }

    .stat-box-card:hover .stat-link-arrow {
        transform: translateX(4px);
    }

    /* Panel & Table Layout */
    .dashboard-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .data-table th, .data-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .data-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-block;
    }
    .bg-success { background: #dcfce7; color: #15803d; }
    .bg-danger { background: #fee2e2; color: #b91c1c; }
    .bg-warning { background: #fef3c7; color: #b45309; }
    .bg-primary { background: #dbeafe; color: #1d4ed8; }
    .bg-info { background: #e0f2fe; color: #0369a1; }
    .bg-secondary { background: #f1f5f9; color: #475569; }

    /* Layout 2 Cột Phía Dưới */
    .dashboard-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 24px;
    }

    @media (max-width: 992px) {
        .dashboard-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Danh sách sản phẩm thu nhỏ */
    .mini-product-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .mini-product-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        text-decoration: none !important;
        transition: all 0.2s;
    }

    .mini-product-item:hover {
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    .mini-product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
        flex-shrink: 0;
    }

    /* Quick Cards */
    .admin-quick-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        text-decoration: none !important;
        color: inherit;
        transition: all 0.2s;
    }

    .admin-quick-card:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .admin-quick-info h4 {
        color: #0f172a;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .admin-quick-info p {
        margin: 0;
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.4;
    }
</style>

<div class="container dashboard-wrapper">
    <div class="orders-page-header">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px; font-weight: 800;">Bảng điều khiển Quản trị (Admin Dashboard)</h1>
            <p class="section-subtitle" style="margin: 0; color: #64748b;">Xin chào <strong>{{ auth()->user()->name }}</strong>, chào mừng bạn đến với trang quản trị Sport Shop.</p>
        </div>
        <div>
            <a href="{{ route('home') }}" class="btn btn-secondary" style="font-size: 0.9rem; text-decoration: none; border-radius: 10px; padding: 10px 18px; font-weight: 600;">
                 Xem trang khách hàng
            </a>
        </div>
    </div>

    {{-- THỐNG KÊ TỔNG QUAN & DOANH THU --}}
    <div class="dashboard-stats-grid">
        {{-- DOANH THU --}}
        <a href="{{ route('admin.statistics.index') }}" class="stat-box-card" title="Bấm để xem Báo cáo doanh số chi tiết">
            <div>
                <h3>Tổng doanh thu</h3>
                <div class="stat-value" style="color: #16a34a;">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                <small style="color: #64748b;">Từ các đơn đã thanh toán</small>
            </div>
            <div class="stat-footer">
                <span>Xem báo cáo</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>

        {{-- TỔNG ĐƠN HÀNG --}}
        <a href="{{ route('admin.orders.index') }}" class="stat-box-card" title="Bấm để xem Danh sách tất cả đơn hàng">
            <div>
                <h3>Tổng đơn hàng</h3>
                <div class="stat-value" style="color: #2563eb;">{{ $totalOrders }}</div>
                <small style="color: #64748b;">Tất cả đơn hàng</small>
            </div>
            <div class="stat-footer">
                <span>Xem danh sách đơn</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>

        {{-- ĐƠN CHỜ XỬ LÝ --}}
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="stat-box-card" style="border-top-color: #1d4ed8; background: {{ $pendingOrders > 0 ? '#fffdf7' : '#ffffff' }};" title="Bấm để xem danh sách các đơn hàng chờ xác nhận">
            <div>
                <h3>Đơn chờ xử lý</h3>
                <div class="stat-value" style="color: #1d4ed8;">{{ $pendingOrders }}</div>
                <small style="color: {{ $pendingOrders > 0 ? '#1d4ed8' : '#64748b' }}; font-weight: {{ $pendingOrders > 0 ? '600' : 'normal' }};">
                    {{ $pendingOrders > 0 ? '! Cần xác nhận ngay' : 'Không có đơn chờ' }}
                </small>
            </div>
            <div class="stat-footer" style="color: #1d4ed8;">
                <span>Xử lý ngay</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>

        {{-- TỔNG SẢN PHẨM --}}
        <a href="{{ route('admin.products.index') }}" class="stat-box-card" style="border-top-color: #1d4ed8;" title="Bấm để xem Quản lý danh sách sản phẩm">
            <div>
                <h3>Tổng sản phẩm</h3>
                <div class="stat-value" style="color: #1d4ed8;">{{ $totalProducts }}</div>
                <small style="color: #64748b;">Mặt hàng đang quản lý</small>
            </div>
            <div class="stat-footer" style="color: #1d4ed8;">
                <span>Quản lý kho</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>

        {{-- KHÁCH HÀNG --}}
        <a href="{{ route('admin.users.index') }}" class="stat-box-card" style="border-top-color: #1d4ed8;" title="Bấm để xem Danh sách khách hàng">
            <div>
                <h3>Khách hàng</h3>
                <div class="stat-value" style="color: #1d4ed8;">{{ $totalUsers }}</div>
                <small style="color: #64748b;">Tài khoản thành viên</small>
            </div>
            <div class="stat-footer" style="color: #1d4ed8;">
                <span>Xem khách hàng</span>
                <span class="stat-link-arrow">→</span>
            </div>
        </a>
    </div>

    {{-- XEM TRỰC TIẾP: ĐƠN HÀNG GẦN ĐÂY CẦN THEO DÕI --}}
    <div class="dashboard-panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a;">
                Đơn hàng mới nhất cần theo dõi
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 8px 16px; text-decoration: none; border-radius: 10px; font-weight: 600;">
                Xem tất cả đơn hàng &rarr;
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="alert alert-info" style="margin: 0; padding: 14px; background: #e0f2fe; color: #0369a1; border-radius: 10px;">
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
                            <th style="text-align: right;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <strong style="color: #0f172a;">{{ $order->customer_name }}</strong>
                                    <br>
                                    <small style="color: #64748b;">{{ $order->customer_phone }}</small>
                                </td>
                                <td>
                                    <strong style="color: #16a34a;">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                    </strong>
                                </td>
                                <td>
                                    @if($order->payment_method === 'cod')
                                        <span class="badge" style="background: #f1f5f9; color: #334155;">COD</span>
                                    @elseif($order->payment_method === 'qr')
                                        <span class="badge" style="background: #e0e7ff; color: #4338ca;">QR Code</span>
                                    @else
                                        <span class="badge" style="background: #f1f5f9; color: #334155;">{{ $order->payment_method }}</span>
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
                                            <span class="badge bg-warning">Chờ xác nhận</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                            @break
                                        @case('shipping')
                                            <span class="badge bg-info">Đang giao</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Hoàn thành</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <small style="color: #64748b;">{{ $order->created_at?->format('d/m/Y H:i') }}</small>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary" style="padding: 6px 14px; font-size: 0.85rem; text-decoration: none; border-radius: 8px; font-weight: 600;">
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
        {{-- SẢN PHẨM TRONG KHO --}}
        <div class="dashboard-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Sản phẩm trong kho</h2>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 14px; text-decoration: none; border-radius: 8px; font-weight: 600;">
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
                                <div class="mini-product-img" style="display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">👟</div>
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; font-size: 0.92rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #0f172a; margin-bottom: 2px;">
                                    {{ $prod->name }}
                                </div>
                                <div style="font-size: 0.8rem; color: #64748b;">
                                    {{ $prod->category->name ?? 'Không có danh mục' }} &bull; Còn {{ $prod->variants->sum('quantity') }} chiếc
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: #16a34a; font-size: 0.92rem;">
                                    {{ number_format($prod->price, 0, ',', '.') }} đ
                                </div>
                                <span style="font-size: 0.75rem; color: #2563eb; font-weight: 600;">Sửa &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- LỐI TẮT QUẢN LÝ HỆ THỐNG --}}
        <div class="dashboard-panel">
            <h2 style="margin: 0 0 16px; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Lối tắt quản lý hệ thống</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.orders.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-info">
                        <h4 style="font-size: 0.95rem; margin: 0 0 2px 0;">Quản lý Đơn hàng</h4>
                        <p>Xem, cập nhật trạng thái đơn (Chờ xác nhận, Giao hàng, Hoàn thành), kiểm tra thanh toán.</p>
                    </div>
                </a>

                <a href="{{ route('admin.products.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-info">
                        <h4 style="font-size: 0.95rem; margin: 0 0 2px 0;">Quản lý Sản phẩm</h4>
                        <p>Thêm sản phẩm mới, cập nhật giá, hình ảnh, quản lý các biến thể Size & Màu sắc.</p>
                    </div>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-info">
                        <h4 style="font-size: 0.95rem; margin: 0 0 2px 0;">Quản lý Danh mục</h4>
                        <p>Thêm mới, sửa, xóa các danh mục thể thao (Giày đá bóng, Áo đấu, Phụ kiện...).</p>
                    </div>
                </a>

                <a href="{{ route('admin.discounts.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-info">
                        <h4 style="font-size: 0.95rem; margin: 0 0 2px 0;">Quản lý Mã giảm giá</h4>
                        <p>Tạo và quản lý voucher khuyến mãi cho khách hàng khi mua sắm.</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-info">
                        <h4 style="font-size: 0.95rem; margin: 0 0 2px 0;">Quản lý Khách hàng</h4>
                        <p>Xem danh sách thành viên, số lượng đơn hàng đã đặt của từng khách hàng.</p>
                    </div>
                </a>

                <a href="{{ route('admin.statistics.index') }}" class="admin-quick-card" style="padding: 12px 16px;">
                    <div class="admin-quick-info">
                        <h4 style="font-size: 0.95rem; margin: 0 0 2px 0;">Báo cáo Thống kê & Doanh số</h4>
                        <p>Xem biểu đồ doanh thu theo tháng, sản phẩm bán chạy nhất và xuất file báo cáo CSV.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection