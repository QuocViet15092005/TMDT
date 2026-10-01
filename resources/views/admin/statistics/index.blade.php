@extends('layouts.app')

@section('title', 'Thống kê & Doanh thu')

@section('content')
<div class="container section-space">
    <div class="orders-page-header">
        <h1 class="section-title">📈 Thống kê & Báo cáo Doanh thu</h1>
        <p class="section-subtitle">Tổng hợp số liệu kinh doanh, doanh thu và sản phẩm bán chạy.</p>
    </div>

    {{-- THỐNG KÊ TỔNG QUAN --}}
    <div class="stats-grid">
        <div class="stat-box" style="border-left: 4px solid var(--secondary-color);">
            <h3>💰 Tổng doanh thu</h3>
            <p>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
        </div>

        <div class="stat-box" style="border-left: 4px solid var(--primary-color);">
            <h3>📦 Tổng đơn hàng</h3>
            <p>{{ $totalOrders }}</p>
        </div>

        <div class="stat-box" style="border-left: 4px solid #10b981;">
            <h3>👟 Số lượng sản phẩm đã bán</h3>
            <p style="color: #10b981;">{{ $totalItemsSold }}</p>
        </div>
    </div>

    <div class="order-detail-grid mt-4">
        {{-- DOANH THU THEO THÁNG --}}
        <div class="card">
            <div class="card-header">
                <h3>Doanh thu 6 tháng gần nhất</h3>
            </div>
            <div class="card-body">
                @if($revenueByMonth->isEmpty())
                    <p class="text-muted">Chưa có dữ liệu doanh thu.</p>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th class="text-end">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenueByMonth as $item)
                                <tr>
                                    <td>Tháng {{ $item->month }}/{{ $item->year }}</td>
                                    <td class="text-end font-bold price-highlight">
                                        {{ number_format($item->revenue, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- TOP SẢN PHẨM BÁN CHẠY --}}
        <div class="card">
            <div class="card-header">
                <h3>Top sản phẩm bán chạy</h3>
            </div>
            <div class="card-body">
                @if($topProducts->isEmpty())
                    <p class="text-muted">Chưa có dữ liệu bán hàng.</p>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-end">Đã bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $prod)
                                <tr>
                                    <td>
                                        <strong>{{ $prod->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ number_format($prod->price, 0, ',', '.') }} VNĐ</small>
                                    </td>
                                    <td class="text-end font-bold text-success">{{ $prod->total_sold ?? 0 }} cái</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    {{-- BÁO CÁO CHI TIẾT & XUẤT CSV --}}
    <div class="card mt-4">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Xuất báo cáo doanh thu</h3>
            <a href="{{ route('admin.statistics.report') }}" class="btn btn-secondary">Xem báo cáo chi tiết</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.statistics.export') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label class="font-bold">Từ ngày:</label>
                    <input type="date" name="start_date" value="{{ now()->subMonth()->format('Y-m-d') }}" required>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label class="font-bold">Đến ngày:</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <button type="submit" class="btn btn-success">📥 Xuất file CSV</button>
            </form>
        </div>
    </div>
</div>
@endsection
