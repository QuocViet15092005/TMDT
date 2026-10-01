@extends('layouts.app')

@section('title', 'Báo cáo chi tiết')

@section('content')
<div class="container section-space">
    <div class="orders-page-header">
        <h1 class="section-title">📊 Báo cáo doanh thu chi tiết</h1>
        <p class="section-subtitle">Dữ liệu đơn hàng đã thanh toán từ {{ $startDate }} đến {{ $endDate }}.</p>
    </div>

    {{-- BỘ LỌC NGÀY --}}
    <form action="{{ route('admin.statistics.report') }}" method="GET" class="filter-form mb-4">
        <div>
            <label class="font-bold">Từ ngày:</label>
            <input type="date" name="start_date" value="{{ $startDate }}" required>
        </div>
        <div>
            <label class="font-bold">Đến ngày:</label>
            <input type="date" name="end_date" value="{{ $endDate }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Lọc báo cáo</button>
        <a href="{{ route('admin.statistics.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">📥 Xuất CSV</a>
    </form>

    {{-- TỔNG KẾT --}}
    <div class="stats-grid mb-4">
        <div class="stat-box">
            <h3>Tổng doanh thu kỳ này</h3>
            <p>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
        </div>
        <div class="stat-box">
            <h3>Tổng số đơn hàng thành công</h3>
            <p>{{ $totalOrders }}</p>
        </div>
    </div>

    {{-- BẢNG ĐƠN HÀNG --}}
    <div class="card">
        <div class="card-header">
            <h3>Danh sách đơn hàng trong kỳ</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Phương thức</th>
                            <th class="text-end">Tổng tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ strtoupper($order->payment_method) }}</td>
                                <td class="text-end font-bold price-highlight">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">Xem chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 24px;">Không có đơn hàng nào trong khoảng thời gian này.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrap mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
