@extends('layouts.app')

@section('title', 'Thống kê & Doanh thu')

@section('content')
<style>
    /* Reset & Base Scoping */
    .stats-page-wrapper {
        padding: 24px 0;
    }

    .orders-page-header {
        margin-bottom: 24px;
    }

    /* Grid Thống Kê Tổng Quan */
    .stats-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card-item {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .stat-card-info h3 {
        font-size: 0.95rem;
        color: #64748b;
        margin: 0 0 6px 0;
        font-weight: 600;
    }

    .stat-card-info .stat-number {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
    }

    .stat-card-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Layout 2 Cột Dữ Liệu */
    .stats-data-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 28px;
    }

    @media (max-width: 992px) {
        .stats-data-grid {
            grid-template-columns: 1fr;
        }
    }

    .stats-panel {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .stats-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .stats-panel-header h3 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* Bảng dữ liệu */
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
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .data-table-custom th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
    }

    /* Form Xuất Báo Cáo */
    .export-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 200px;
    }

    .export-form-group label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
    }

    .export-form-group input[type="date"] {
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .export-form-group input[type="date"]:focus {
        border-color: #2563eb;
    }

    .btn-export {
        background-color: #16a34a;
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-export:hover {
        background-color: #15803d;
    }
</style>

<div class="container stats-page-wrapper">
    <div class="orders-page-header">
        <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;">📈 Thống kê & Báo cáo Doanh thu</h1>
        <p class="section-subtitle" style="color: #64748b; margin: 0;">Tổng hợp số liệu kinh doanh, doanh thu và sản phẩm bán chạy.</p>
    </div>

    {{-- THỐNG KÊ TỔNG QUAN --}}
    <div class="stats-card-grid">
        {{-- TỔNG DOANH THU --}}
        <div class="stat-card-item" style="border-left: 5px solid #16a34a;">
            <div class="stat-card-info">
                <h3> Tổng doanh thu</h3>
                <p class="stat-number" style="color: #16a34a;">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="stat-card-icon" style="background-color: #dcfce7; color: #16a34a;">
                💵
            </div>
        </div>

        {{-- TỔNG ĐƠN HÀNG --}}
        <div class="stat-card-item" style="border-left: 5px solid #2563eb;">
            <div class="stat-card-info">
                <h3> Tổng đơn hàng</h3>
                <p class="stat-number" style="color: #2563eb;">{{ $totalOrders }}</p>
            </div>
            <div class="stat-card-icon" style="background-color: #dbeafe; color: #2563eb;">
                📦
            </div>
        </div>

        {{-- SẢN PHẨM ĐÃ BÁN --}}
        <div class="stat-card-item" style="border-left: 5px solid #10b981;">
            <div class="stat-card-info">
                <h3> Sản phẩm đã bán</h3>
                <p class="stat-number" style="color: #10b981;">{{ $totalItemsSold }}</p>
            </div>
            <div class="stat-card-icon" style="background-color: #e6f4ea; color: #10b981;">
                👟
            </div>
        </div>
    </div>

    {{-- BẢNG DỮ LIỆU CỘT KÉP --}}
    <div class="stats-data-grid">
        {{-- DOANH THU THEO THÁNG --}}
        <div class="stats-panel">
            <div class="stats-panel-header">
                <h3> Doanh thu 6 tháng gần nhất</h3>
            </div>
            <div class="panel-body">
                @if($revenueByMonth->isEmpty())
                    <p style="color: #64748b; margin: 0; padding: 12px 0;">Chưa có dữ liệu doanh thu.</p>
                @else
                    <div class="table-responsive">
                        <table class="data-table-custom">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th style="text-align: right;">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueByMonth as $item)
                                    <tr>
                                        <td><strong>Tháng {{ $item->month }}/{{ $item->year }}</strong></td>
                                        <td style="text-align: right; font-weight: 700; color: #16a34a;">
                                            {{ number_format($item->revenue, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- TOP SẢN PHẨM BÁN CHẠY --}}
        <div class="stats-panel">
            <div class="stats-panel-header">
                <h3> Top sản phẩm bán chạy</h3>
            </div>
            <div class="panel-body">
                @if($topProducts->isEmpty())
                    <p style="color: #64748b; margin: 0; padding: 12px 0;">Chưa có dữ liệu bán hàng.</p>
                @else
                    <div class="table-responsive">
                        <table class="data-table-custom">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th style="text-align: right;">Số lượng bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $prod)
                                    <tr>
                                        <td>
                                            <strong style="color: #0f172a; font-size: 0.92rem;">{{ $prod->name }}</strong>
                                            <br>
                                            <small style="color: #64748b;">{{ number_format($prod->price, 0, ',', '.') }} VNĐ</small>
                                        </td>
                                        <td style="text-align: right;">
                                            <span style="background-color: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;">
                                                {{ $prod->total_sold ?? 0 }} cái
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BÁO CÁO CHI TIẾT & XUẤT CSV --}}
    <div class="stats-panel">
        <div class="stats-panel-header">
            <h3> Xuất báo cáo doanh thu chi tiết</h3>
            <a href="{{ route('admin.statistics.report') }}" class="btn btn-secondary" style="font-size: 0.88rem; padding: 6px 14px; text-decoration: none;">
                Xem báo cáo chi tiết &rarr;
            </a>
        </div>
        <div class="panel-body" style="padding-top: 8px;">
            <form action="{{ route('admin.statistics.export') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                <div class="export-form-group">
                    <label>Từ ngày:</label>
                    <input type="date" name="start_date" value="{{ now()->subMonth()->format('Y-m-d') }}" required>
                </div>
                <div class="export-form-group">
                    <label>Đến ngày:</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div>
                    <button type="submit" class="btn-export">
                         Xuất file CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection