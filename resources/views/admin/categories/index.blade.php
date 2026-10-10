@extends('layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')
<style>
    .categories-page-wrapper {
        padding: 28px 0;
    }

    .orders-page-header {
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
    }

    .dashboard-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 992px) {
        .dashboard-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Đồng bộ giao diện Khối Panel / Card */
    .panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-primary {
        background-color: #2563eb;
        color: #ffffff;
        padding: 11px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
    }

    .btn-secondary {
        background-color: #f1f5f9;
        color: #334155;
        padding: 9px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.88rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-secondary:hover {
        background-color: #e2e8f0;
    }

    .btn-danger {
        background-color: #ef4444;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    /* Bảng dữ liệu */
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
</style>

<div class="container categories-page-wrapper">
    <div class="orders-page-header">
        <div>
            <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;"> Quản lý danh mục sản phẩm</h1>
            <p class="section-subtitle" style="color: #64748b; margin: 0;">Thêm mới, sửa thông tin và xóa danh mục thể thao</p>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                 Quản lý sản phẩm &rarr;
            </a>
        </div>
    </div>

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

    <div class="dashboard-grid-2">
        {{-- FORM THÊM MỚI DANH MỤC --}}
        <div class="panel">
            <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Thêm danh mục mới</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.88rem; color: #334155;">Tên danh mục *</label>
                    <input type="text" name="name" placeholder="Ví dụ: Giày đá bóng, Áo thi đấu..." required class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.88rem; color: #334155;">Mô tả danh mục</label>
                    <textarea name="description" rows="3" placeholder="Mô tả ngắn gọn về danh mục này..." class="form-control" style="resize: vertical;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Thêm danh mục</button>
            </form>
        </div>

        {{-- THỐNG KÊ NHANH / HƯỚNG DẪN --}}
        <div class="panel" style="display: flex; flex-direction: column; justify-content: center;">
            <h2 style="margin-top: 0; margin-bottom: 14px; font-size: 1.15rem; font-weight: 700; color: #0f172a;"> Hướng dẫn quản trị danh mục</h2>
            <ul style="color: #475569; font-size: 0.9rem; line-height: 1.7; padding-left: 20px; margin: 0;">
                <li>Danh mục giúp khách hàng dễ dàng tìm kiếm và lọc sản phẩm trên trang chủ và cửa hàng.</li>
                <li>Bạn có thể chỉnh sửa tên và mô tả trực tiếp ở bảng danh sách bên dưới.</li>
                <li>Chỉ có thể xóa danh mục khi danh mục đó <strong>không chứa sản phẩm nào</strong>.</li>
            </ul>
        </div>
    </div>

    {{-- DANH SÁCH DANH MỤC --}}
    <div class="panel">
        <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem; font-weight: 700; color: #0f172a;"> Danh sách danh mục ({{ $categories->total() }})</h2>

        @if($categories->isEmpty())
            <div class="alert alert-info" style="border-radius: 10px; margin: 0;">
                Chưa có danh mục nào. Hãy tạo danh mục đầu tiên ở trên.
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Số sản phẩm</th>
                            <th style="text-align: right; min-width: 160px;">Lưu sửa / Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td><strong>#{{ $category->id }}</strong></td>
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                        <input type="text" name="name" value="{{ $category->name }}" required class="form-control" style="padding: 7px 10px; font-weight: 600;">
                                    </td>
                                    <td>
                                        <input type="text" name="description" value="{{ $category->description }}" placeholder="Mô tả..." class="form-control" style="padding: 7px 10px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="badge" style="background-color: #dbeafe; color: #2563eb; padding: 4px 10px; border-radius: 20px; font-weight: 700; text-decoration: none; font-size: 0.82rem;">
                                            {{ $category->products_count }} sản phẩm
                                        </a>
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 6px; align-items: center;">
                                            <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;" title="Lưu thông tin chỉnh sửa">
                                                 Lưu
                                            </button>
                                </form>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục \'{{ $category->name }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;" title="Xóa danh mục">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection