@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<style>
    .orders-management-wrapper {
        padding: 28px 0;
    }

    /* Khung chứa bảng dữ liệu (Card trắng bo góc) */
    .admin-card-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    /* Tabs lọc trạng thái gọn gàng, hiện đại */
    .filter-tabs-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 24px;
        background: #ffffff;
        padding: 14px 18px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .filter-tab-btn {
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .filter-tab-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .filter-tab-btn.active {
        background-color: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }

    /* SVG Icon trắng đen */
    .tab-icon {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
        opacity: 0.85;
    }

    /* Tùy chỉnh bảng dữ liệu */
    .data-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .data-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    /* Badges trạng thái chuẩn đẹp */
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-block;
    }
    .bg-success { background: #dcfce7 !important; color: #15803d !important; }
    .bg-danger { background: #fee2e2 !important; color: #b91c1c !important; }
    .bg-warning { background: #fef3c7 !important; color: #b45309 !important; }
    .bg-primary { background: #dbeafe !important; color: #1d4ed8 !important; }
    .bg-info { background: #e0f2fe !important; color: #0369a1 !important; }
    .bg-secondary { background: #f1f5f9 !important; color: #475569 !important; }
</style>

<div class="container section-space orders-management-wrapper">
    <div style="margin-bottom: 24px;">
        <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;">
            Quản lý đơn hàng
        </h1>
        <p class="section-subtitle" style="color: #64748b; margin: 0;">Danh sách và theo dõi toàn bộ đơn hàng của khách hàng trên hệ thống.</p>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 10px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="border-radius: 10px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER TABS VỚI ICON TRẮNG ĐEN TỐI GIẢN --}}
    <div class="filter-tabs-group">
        {{-- Tất cả --}}
        <a href="{{ route('admin.orders.index') }}" class="filter-tab-btn {{ !request('status') ? 'active' : '' }}">
            <svg class="tab-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Tất cả ({{ \App\Models\Order::count() }})
        </a>

        {{-- Chờ xác nhận --}}
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-tab-btn {{ request('status') === 'pending' ? 'active' : '' }}">
            <svg class="tab-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Chờ xác nhận ({{ \App\Models\Order::where('order_status', 'pending')->count() }})
        </a>

        {{-- Đã xác nhận --}}
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="filter-tab-btn {{ request('status') === 'confirmed' ? 'active' : '' }}">
            <svg class="tab-icon" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            Đã xác nhận ({{ \App\Models\Order::where('order_status', 'confirmed')->count() }})
        </a>

        {{-- Đang giao --}}
        <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="filter-tab-btn {{ request('status') === 'shipping' ? 'active' : '' }}">
            <svg class="tab-icon" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            Đang giao ({{ \App\Models\Order::where('order_status', 'shipping')->count() }})
        </a>

        {{-- Hoàn thành --}}
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="filter-tab-btn {{ request('status') === 'completed' ? 'active' : '' }}">
            <svg class="tab-icon" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            Hoàn thành ({{ \App\Models\Order::where('order_status', 'completed')->count() }})
        </a>

        {{-- Đã hủy --}}
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="filter-tab-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">
            <svg class="tab-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            Đã hủy ({{ \App\Models\Order::where('order_status', 'cancelled')->count() }})
        </a>
    </div>

    {{-- KHUNG CHỨA BẢNG ĐƠN HÀNG --}}
    <div class="admin-card-panel">
        @if($orders->isEmpty())
            <div class="alert alert-info" style="border-radius: 10px; margin: 0;">
                Hiện chưa có đơn hàng nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái đơn</th>
                            <th>Ngày đặt</th>
                            <th style="text-align: right; width: 110px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            {{-- ID --}}
                            <td>
                                <strong>#{{ $order->id }}</strong>
                            </td>

                            {{-- CUSTOMER --}}
                            <td>
                                <strong style="color: #0f172a;">{{ $order->customer_name }}</strong>
                                <br>
                                <small style="color: #64748b;">{{ $order->customer_phone }}</small>
                            </td>

                            {{-- TOTAL --}}
                            <td>
                                <strong style="color: #16a34a; white-space: nowrap;">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </strong>
                            </td>

                            {{-- PAYMENT METHOD --}}
                            <td>
                                <span class="badge bg-secondary" style="text-transform: uppercase;">
                                    @if($order->payment_method === 'cod')
                                        COD
                                    @elseif($order->payment_method === 'qr')
                                        QR
                                    @else
                                        {{ $order->payment_method }}
                                    @endif
                                </span>
                            </td>

                            {{-- PAYMENT STATUS --}}
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="badge bg-danger">Thất bại</span>
                                @else
                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                @endif
                            </td>

                            {{-- ORDER STATUS --}}
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

                            {{-- CREATED --}}
                            <td style="color: #64748b; white-space: nowrap;">
                                {{ $order->created_at?->format('d/m/Y H:i') }}
                            </td>

                            {{-- ACTION --}}
                            <td style="text-align: right;">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary" style="padding: 6px 14px; font-size: 0.85rem; border-radius: 8px; font-weight: 600; text-decoration: none;">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                <div class="mt-4" style="margin-top: 24px; display: flex; justify-content: center;">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection