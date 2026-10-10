@extends('layouts.app')

@section('title', 'Quản lý khách hàng')

@section('content')
<style>
    .users-page-wrapper {
        padding: 28px 0;
    }

    .orders-page-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
    }

    /* Form tìm kiếm chuẩn khối card */
    .search-filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input-group {
        flex: 1;
        min-width: 260px;
        position: relative;
    }

    .search-input-group input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input-group input:focus {
        border-color: #2563eb;
    }

    .btn-search {
        background-color: #2563eb;
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }

    .btn-search:hover {
        background-color: #1d4ed8;
    }

    /* Khung Panel Bảng Dữ Liệu */
    .users-panel {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-top: 20px;
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
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .data-table-custom th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
    }

    .user-badge-orders {
        background-color: #dbeafe;
        color: #2563eb;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.82rem;
        display: inline-block;
    }
</style>

<div class="container users-page-wrapper">
    <div class="orders-page-header">
        <div>
            <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;">Quản lý khách hàng</h1>
            <p class="section-subtitle" style="color: #64748b; margin: 0;">Danh sách thành viên và khách hàng mua sắm tại cửa hàng.</p>
        </div>

        {{-- FORM TÌM KIẾM KHÁCH HÀNG --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="search-filter-card" style="margin-bottom: 0;">
            <div class="search-input-group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, email...">
            </div>
            <button type="submit" class="btn-search">
                Tìm kiếm
            </button>
        </form>
    </div>

    {{-- BẢNG DANH SÁCH KHÁCH HÀNG --}}
    <div class="users-panel">
        <div class="table-responsive">
            <table class="data-table-custom">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số đơn hàng đã đặt</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                        <tr>
                            <td><strong>#{{ $user->id }}</strong></td>
                            <td><strong style="color: #0f172a;">{{ $user->name }}</strong></td>
                            <td style="color: #475569;">{{ $user->email }}</td>
                            <td>
                                <span class="user-badge-orders">
                                    {{ $user->orders_count ?? 0 }} đơn hàng
                                </span>
                            </td>
                            <td style="color: #64748b;">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 32px; color: #64748b;">
                                Không tìm thấy khách hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection