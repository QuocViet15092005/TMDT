@extends('layouts.app')

@section('title', 'Quản lý Size & Tồn kho - ' . $product->name)

@section('content')
<div class="container section-space">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;"> Quản lý Kích cỡ (Size) & Tồn kho</h1>
            <p class="section-subtitle" style="margin: 0;">Sản phẩm: <strong>{{ $product->name }}</strong></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                 Sửa thông tin sản phẩm
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
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

    @php
        $defaultColor = $product->variants->firstWhere('color', '!=', null)?->color ?? '';
    @endphp

    <div class="dashboard-grid-2">
        {{-- THÔNG TIN CƠ BẢN CỦA SẢN PHẨM --}}
        <div class="panel">
            <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem;"> Thông tin sản phẩm</h2>
            <div style="display: flex; gap: 20px; align-items: flex-start;">
                @if($product->image)
                    <img 
                        src="{{ asset('images/products/' . $product->image) }}" 
                        alt="{{ $product->name }}" 
                        style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border-color);"
                        onerror="this.src='{{ asset('images/no-image.jpg') }}'"
                    >
                @endif
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 8px; font-size: 1.1rem;">{{ $product->name }}</h3>
                    <p style="margin: 0 0 6px; color: #64748b;"><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
                    <p style="margin: 0 0 6px; color: #64748b;"><strong>Thương hiệu:</strong> {{ $product->brand ?? 'N/A' }}</p>
                    @if($defaultColor)
                        <p style="margin: 0 0 6px; color: #64748b;"><strong>Màu sắc chung:</strong> <span style="background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 4px; font-weight: 600;">🎨 {{ $defaultColor }}</span></p>
                    @endif
                    <p style="margin: 0 0 6px; font-size: 1.2rem; font-weight: 700; color: var(--secondary-color);">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">
                        Tổng tồn kho: <strong>{{ $product->variants->sum('quantity') }}</strong> chiếc
                    </p>
                </div>
            </div>
        </div>

        {{-- FORM THÊM BIẾN THỂ MỚI --}}
        <div class="panel">
            <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem;">➕ Thêm kích cỡ mới cho sản phẩm</h2>
            <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Kích cỡ (Size) *</label>
                        <input type="text" name="size" placeholder="Ví dụ: 40, 41, M, L..." required class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Màu sắc (Color)</label>
                        <input type="text" name="color" value="{{ old('color', $defaultColor) }}" placeholder="Ví dụ: Đen, Trắng..." class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Số lượng kho *</label>
                        <input type="number" name="quantity" min="0" value="10" required class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Thêm kích cỡ vào kho</button>
            </form>
        </div>
    </div>

    {{-- DANH SÁCH CÁC BIẾN THỂ HIỆN CÓ --}}
    <div class="panel" style="margin-top: 24px;">
        <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 1.15rem;">📋 Danh sách biến thể hiện có ({{ $product->variants->count() }})</h2>

        @if($product->variants->isEmpty())
            <div class="alert alert-info" style="margin: 0;">
                Sản phẩm này chưa có biến thể nào. Hãy thêm Size và Màu sắc ở form bên trên để khách hàng có thể đặt mua.
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Size (Kích thước)</th>
                            <th>Màu sắc</th>
                            <th>Số lượng trong kho</th>
                            <th style="text-align: right;">Cập nhật / Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                            <tr>
                                <td>#{{ $variant->id }}</td>
                                <form action="{{ route('admin.variants.update', $variant) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                        <input type="text" name="size" value="{{ $variant->size }}" style="padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 6px; width: 120px;">
                                    </td>
                                    <td>
                                        <input type="text" name="color" value="{{ $variant->color }}" style="padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 6px; width: 140px;">
                                    </td>
                                    <td>
                                        <input type="number" name="quantity" min="0" value="{{ $variant->quantity }}" style="padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 6px; width: 100px; font-weight: 600;">
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 8px; align-items: center;">
                                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem;">
                                                 Lưu thay đổi
                                            </button>
                                </form>
                                            <form action="{{ route('admin.variants.destroy', $variant) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;">
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