@extends('layouts.app')

@section('title', 'Quản lý Size & Tồn kho - ' . $product->name)

@section('content')
<style>
    .variants-wrapper {
        padding: 28px 0;
    }

    /* Khung card trắng bọc nội dung */
    .admin-card-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    .form-control {
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 9px 12px !important;
        font-size: 0.9rem;
        color: #0f172a;
        background-color: #ffffff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
</style>

<div class="container section-space variants-wrapper">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px; font-weight: 800;">Quản lý Kích cỡ (Size) & Tồn kho</h1>
            <p class="section-subtitle" style="margin: 0; color: #64748b;">Sản phẩm: <strong>{{ $product->name }}</strong></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary" style="border-radius: 10px; font-weight: 600; padding: 8px 16px; text-decoration: none;">
                 Sửa thông tin sản phẩm
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="border-radius: 10px; font-weight: 600; padding: 8px 16px; text-decoration: none; border: 1px solid #cbd5e1;">
                ← Quay lại danh sách
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

    @php
        $defaultColor = $product->variants->firstWhere('color', '!=', null)?->color ?? '';
    @endphp

    <div class="dashboard-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        {{-- THÔNG TIN CƠ BẢN CỦA SẢN PHẨM --}}
        <div class="admin-card-panel">
            <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Thông tin sản phẩm</h2>
            <div style="display: flex; gap: 20px; align-items: flex-start;">
                @if($product->image)
                    <img 
                        src="{{ asset('images/products/' . $product->image) }}" 
                        alt="{{ $product->name }}" 
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0;"
                        onerror="this.src='{{ asset('images/no-image.jpg') }}'"
                    >
                @endif
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 8px; font-size: 1.05rem; font-weight: 700; color: #0f172a;">{{ $product->name }}</h3>
                    <p style="margin: 0 0 6px; color: #64748b; font-size: 0.88rem;"><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
                    <p style="margin: 0 0 6px; color: #64748b; font-size: 0.88rem;"><strong>Thương hiệu:</strong> {{ $product->brand ?? 'N/A' }}</p>
                    @if($defaultColor)
                        <p style="margin: 0 0 6px; color: #64748b; font-size: 0.88rem;"><strong>Màu sắc chung:</strong> <span style="background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 6px; font-weight: 600;">{{ $defaultColor }}</span></p>
                    @endif
                    <p style="margin: 0 0 6px; font-size: 1.1rem; font-weight: 800; color: #16a34a;">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>
                    <p style="margin: 0; font-size: 0.88rem; color: #64748b;">
                        Tổng tồn kho: <strong>{{ $product->variants->sum('quantity') }}</strong> chiếc
                    </p>
                </div>
            </div>
        </div>

        {{-- FORM THÊM BIẾN THỂ MỚI --}}
        <div class="admin-card-panel">
            <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Thêm kích cỡ mới cho sản phẩm</h2>
            <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: #0f172a;">Kích cỡ (Size) *</label>
                        <input type="text" name="size" placeholder="Ví dụ: 40, 41, M, L..." required class="form-control" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: #0f172a;">Màu sắc (Color)</label>
                        <input type="text" name="color" value="{{ old('color', $defaultColor) }}" placeholder="Ví dụ: Đen, Trắng..." class="form-control" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: #0f172a;">Số lượng kho *</label>
                        <input type="number" name="quantity" min="0" value="10" required class="form-control" style="width: 100%;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 10px; font-weight: 700; padding: 10px;">Thêm kích cỡ vào kho</button>
            </form>
        </div>
    </div>

    {{-- DANH SÁCH CÁC BIẾN THỂ HIỆN CÓ --}}
    <div class="admin-card-panel" style="margin-top: 24px;">
        <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Danh sách biến thể hiện có ({{ $product->variants->count() }})</h2>

        @if($product->variants->isEmpty())
            <div class="alert alert-info" style="margin: 0; border-radius: 10px;">
                Sản phẩm này chưa có biến thể nào. Hãy thêm Size và Màu sắc ở form bên trên để khách hàng có thể đặt mua.
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Size (Kích thước)</th>
                            <th>Màu sắc</th>
                            <th>Số lượng trong kho</th>
                            <th style="text-align: right; width: 220px;">Cập nhật / Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                            <tr>
                                <td><strong>#{{ $variant->id }}</strong></td>
                                <form action="{{ route('admin.variants.update', $variant) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                        <input type="text" name="size" value="{{ $variant->size }}" class="form-control" style="width: 110px;">
                                    </td>
                                    <td>
                                        <input type="text" name="color" value="{{ $variant->color }}" class="form-control" style="width: 130px;">
                                    </td>
                                    <td>
                                        <input type="number" name="quantity" min="0" value="{{ $variant->quantity }}" class="form-control" style="width: 100px; font-weight: 600;">
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 8px; align-items: center;">
                                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem; border-radius: 8px; font-weight: 600;">
                                                Lưu
                                            </button>
                                </form>
                                            <form action="{{ route('admin.variants.destroy', $variant) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem; border-radius: 8px; font-weight: 600;">
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
        @endif
    </div>
</div>
@endsection