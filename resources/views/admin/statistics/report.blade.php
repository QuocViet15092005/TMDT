@extends('layouts.app')

@section('title', 'Báo cáo chi tiết')

@section('content')
<style>
    .report-page-wrapper {
        padding: 24px 0;
    }

    .orders-page-header {
        margin-bottom: 24px;
    }

    /* Form Bộ Lọc Ngày Hiện Đại */
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        display: flex;
        gap: 16px;
        align-items: flex-end;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
    }

    .filter-group input[type="date"] {
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .filter-group input[type="date"]:focus {
        border-color: #2563eb;
    }

    .btn-filter {
        background-color: #2563eb;
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-filter:hover {
        background-color: #1d4ed8;
    }

    .btn-export-csv {
        background-color: #16a34a;
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-export-csv:hover {
        background-color: #15803d;
    }

    /* Grid Thống Kê Tổng Quan */
    .report-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .report-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .report-stat-info h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .report-stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }

    .report-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Panel & Table Layout */
    .report-panel {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .report-panel-header {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        background-color: #ffffff;
    }

    .report-panel-header h3 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table-custom {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .data-table-custom th, .data-table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .data-table-custom th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
    }

    .badge-method {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #e2e8f0;
        color: #334155;
    }
</style>

<div class="container report-page-wrapper">
    <div class="orders-page-header">
        <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;">Báo cáo doanh thu chi tiết</h1>
        <p class="section-subtitle" style="color: #64748b; margin: 0;">Dữ liệu đơn hàng đã thanh toán từ <strong>{{ $startDate }}</strong> đến <strong>{{ $endDate }}</strong>.</p>
    </div>

    {{-- BỘ LỌC NGÀY --}}
    <form action="{{ route('admin.statistics.report') }}" method="GET" class="filter-card">
        <div class="filter-group">
            <label>Từ ngày:</label>
            <input type="date" name="start_date" value="{{ $startDate }}" required>
        </div>
        <div class="filter-group">
            <label>Đến ngày:</label>
            <input type="date" name="end_date" value="{{ $endDate }}" required>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-filter">Lọc báo cáo</button>
            <a href="{{ route('admin.statistics.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-export-csv">📥 Xuất CSV</a>
        </div>
    </form>

    {{-- TỔNG KẾT --}}
    <div class="report-stats-grid">
        <div class="report-stat-card" style="border-left: 4px solid #16a34a;">
            <div class="report-stat-info">
                <h3>Tổng doanh thu kỳ này</h3>
                <p class="report-stat-value" style="color: #16a34a;">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="report-stat-icon" style="background-color: #dcfce7; color: #16a34a;">
                💰
            </div>
        </div>

        <div class="report-stat-card" style="border-left: 4px solid #0284c7;">
            <div class="report-stat-info">
                <h3>Tổng số đơn thành công</h3>
                <p class="report-stat-value" style="color: #0284c7;">{{ $totalOrders }}</p>
            </div>
            <div class="report-stat-icon" style="background-color: #e0f2fe; color: #0284c7;">
                📦
            </div>
        </div>
    </div>

    {{-- BẢNG ĐƠN HÀNG --}}
    <div class="report-panel">
        <div class="report-panel-header">
            <h3>Danh sách đơn hàng trong kỳ</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table-custom">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Phương thức</th>
                        <th style="text-align: right;">Tổng tiền</th>
                        <th style="text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td><strong>{{ $order->customer_name }}</strong></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge-method">{{ strtoupper($order->payment_method) }}</span>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #16a34a;">
                                {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary" style="padding: 4px 12px; font-size: 0.85rem; text-decoration: none;">Xem chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px; color: #64748b;">
                                Không có đơn hàng nào trong khoảng thời gian này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection