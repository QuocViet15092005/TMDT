@extends('layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="container section-space">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;">🏷️ Quản lý danh mục sản phẩm</h1>
            <p class="section-subtitle" style="margin: 0;">Thêm mới, sửa thông tin và xóa danh mục thể thao</p>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                👟 Quản lý sản phẩm
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="dashboard-grid-2">
        {{-- FORM THÊM MỚI DANH MỤC --}}
        <div class="panel">
            <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem;">➕ Thêm danh mục mới</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Tên danh mục *</label>
                    <input type="text" name="name" placeholder="Ví dụ: Giày đá bóng, Áo thi đấu..." required class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Mô tả danh mục</label>
                    <textarea name="description" rows="3" placeholder="Mô tả ngắn gọn về danh mục này..." class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Thêm danh mục</button>
            </form>
        </div>

        {{-- THỐNG KÊ NHANH --}}
        <div class="panel" style="display: flex; flex-direction: column; justify-content: center;">
            <h2 style="margin-top: 0; margin-bottom: 12px; font-size: 1.15rem;">💡 Hướng dẫn quản trị danh mục</h2>
            <ul style="color: #475569; font-size: 0.9rem; line-height: 1.7; padding-left: 20px; margin: 0;">
                <li>Danh mục giúp khách hàng dễ dàng tìm kiếm và lọc sản phẩm trên trang chủ và cửa hàng.</li>
                <li>Bạn có thể chỉnh sửa tên và mô tả trực tiếp ở bảng danh sách bên dưới.</li>
                <li>Chỉ có thể xóa danh mục khi danh mục đó <strong>không chứa sản phẩm nào</strong>.</li>
            </ul>
        </div>
    </div>

    {{-- DANH SÁCH DANH MỤC --}}
    <div class="panel" style="margin-top: 24px;">
        <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem;">📋 Danh sách danh mục ({{ $categories->total() }})</h2>

        @if($categories->isEmpty())
            <div class="alert alert-info">
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
                                <td>#{{ $category->id }}</td>
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                        <input type="text" name="name" value="{{ $category->name }}" required style="padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 6px; width: 100%; min-width: 150px; font-weight: 600;">
                                    </td>
                                    <td>
                                        <input type="text" name="description" value="{{ $category->description }}" placeholder="Mô tả..." style="padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 6px; width: 100%; min-width: 200px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="badge bg-primary" style="text-decoration: none;">
                                            {{ $category->products_count }} sản phẩm
                                        </a>
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 6px; align-items: center;">
                                            <button type="submit" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85rem;" title="Lưu thông tin chỉnh sửa">
                                                💾 Lưu
                                            </button>
                                </form>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục \'{{ $category->name }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.85rem;" title="Xóa danh mục">
                                                    🗑️ Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4" style="margin-top: 20px;">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
