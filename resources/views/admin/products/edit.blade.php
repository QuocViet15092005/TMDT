@extends('layouts.app')

@section('title', 'Sửa sản phẩm - ' . $product->name)

@section('content')
<div class="container section-space" style="max-width: 800px;">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;"> Sửa thông tin sản phẩm</h1>
            <p class="section-subtitle" style="margin: 0;">Cập nhật thông tin chi tiết cho <strong>{{ $product->name }}</strong></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary" style="background: #f0fdf4; color: #166534; border-color: #bbf7d0;">
                 Quản lý Size
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Tên sản phẩm *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Danh mục sản phẩm *</label>
                    <select name="category_id" required class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Thương hiệu</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Giá bán (VNĐ) *</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Hình ảnh sản phẩm</label>
                @if($product->image)
                    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 14px;">
                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.85rem; color: #64748b;">Ảnh hiện tại: <strong>{{ $product->image }}</strong></span>
                    </div>
                @endif
                <label style="display: block; font-size: 0.85rem; color: #475569; margin-bottom: 4px;">Chọn ảnh mới để thay đổi:</label>
                <input type="file" name="image_file" accept="image/*" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                <input type="text" name="image" value="{{ old('image', $product->image) }}" placeholder="Tên file ảnh" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; margin-top: 6px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Mô tả sản phẩm</label>
                <textarea name="description" rows="5" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">{{ old('description', $product->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding: 10px 20px;">Hủy</a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 600;">
                     Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
