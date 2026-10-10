@extends('layouts.app')

@section('title', 'Sửa thông tin sản phẩm')

@section('content')
<style>
    .product-edit-wrapper {
        padding: 28px 0;
        max-width: 900px;
        margin: 0 auto;
    }

    .edit-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    /* Khung card trắng bọc toàn bộ form */
    .admin-form-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #0f172a;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .form-control, select.form-control {
        width: 100%;
        padding: 11px 16px;
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        font-size: 0.95rem;
        color: #0f172a;
        background-color: #ffffff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, select.form-control:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-row-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Ảnh sản phẩm hiện tại */
    .current-image-preview {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
    }

    .current-image-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
    }

    /* Nút bấm thao tác */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-submit {
        background-color: #2563eb;
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-submit:hover {
        background-color: #1d4ed8;
    }

    .btn-cancel {
        background-color: #f1f5f9;
        color: #334155;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        border: 1px solid #cbd5e1;
        transition: background-color 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    .btn-sizes {
        background-color: #f0fdf4;
        color: #16a34a;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        border: 1px solid #bbf7d0;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .btn-sizes:hover {
        background-color: #dcfce7;
    }

    .file-input-wrapper input[type="file"] {
        padding: 9px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        width: 100%;
        cursor: pointer;
    }
</style>

<div class="container product-edit-wrapper">
    <div class="edit-page-header">
        <div>
            <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;">Sửa thông tin sản phẩm</h1>
            <p class="section-subtitle" style="color: #64748b; margin: 0;">Cập nhật thông tin chi tiết cho <strong>{{ $product->name }}</strong></p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            {{-- Nút quản lý size/biến thể nếu có --}}
            @if(Route::has('admin.product-variants.index'))
                <a href="{{ route('admin.product-variants.index', $product) }}" class="btn-sizes">
                    Quản lý Size
                </a>
            @endif
            <a href="{{ route('admin.products.index') }}" class="btn-cancel" style="padding: 10px 16px; font-size: 0.88rem;">
                &larr; Quay lại danh sách
            </a>
        </div>
    </div>

    {{-- HIỂN THỊ LỖI VALIDATION (NẾU CÓ) --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM SỬA SẢN PHẨM --}}
    <div class="admin-form-panel">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- TÊN SẢN PHẨM --}}
            <div class="form-group">
                <label for="name" class="form-label">Tên sản phẩm *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $product->name) }}" 
                    placeholder="Ví dụ: Giày đá bóng Nike Mercurial..." 
                    class="form-control" 
                    required
                >
            </div>

            {{-- DANH MỤC VÀ THƯƠNG HIỆU --}}
            <div class="form-row-2">
                <div class="form-group">
                    <label for="category_id" class="form-label">Danh mục sản phẩm *</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="brand" class="form-label">Thương hiệu</label>
                    <input 
                        type="text" 
                        name="brand" 
                        id="brand" 
                        value="{{ old('brand', $product->brand ?? '') }}" 
                        placeholder="Ví dụ: Nike, Adidas, Puma..." 
                        class="form-control"
                    >
                </div>
            </div>

            {{-- GIÁ BÁN VÀ SỐ LƯỢNG KHO --}}
            <div class="form-row-2">
                <div class="form-group">
                    <label for="price" class="form-label">Giá bán (VNĐ) *</label>
                    <input 
                        type="number" 
                        name="price" 
                        id="price" 
                        value="{{ old('price', $product->price) }}" 
                        placeholder="Ví dụ: 1500000" 
                        class="form-control" 
                        min="0"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="stock" class="form-label">Số lượng tồn kho</label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        value="{{ old('stock', $product->stock ?? 0) }}" 
                        class="form-control" 
                        min="0"
                    >
                </div>
            </div>

            {{-- HÌNH ẢNH SẢN PHẨM --}}
            <div class="form-group">
                <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                
                @if($product->image)
                    <div class="current-image-preview">
                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/no-image.jpg') }}'">
                        <div>
                            <strong style="font-size: 0.88rem; color: #0f172a; display: block;">Ảnh hiện tại:</strong>
                            <span style="font-size: 0.8rem; color: #64748b; word-break: break-all;">{{ $product->image }}</span>
                        </div>
                    </div>
                @endif

                <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <small style="color: #64748b; display: block; margin-top: 6px;">Tải lên ảnh mới nếu bạn muốn thay thế ảnh cũ.</small>
            </div>

            {{-- NÚT LƯU THAY ĐỔI --}}
            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    Hủy bỏ
                </a>
                <button type="submit" class="btn-submit">
                    Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection