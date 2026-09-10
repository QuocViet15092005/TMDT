@extends('layouts.app')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container section-space" style="max-width: 800px;">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;"> Thêm sản phẩm mới</h1>
            <p class="section-subtitle" style="margin: 0;">Điền thông tin chi tiết để thêm sản phẩm vào cửa hàng</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            ← Quay lại danh sách
        </a>
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Tên sản phẩm *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Giày đá bóng Nike Mercurial..." class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Danh mục sản phẩm *</label>
                    <select name="category_id" required class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Thương hiệu</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Ví dụ: Nike, Adidas, Puma..." class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Giá bán (VNĐ) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="1000" required placeholder="Ví dụ: 1500000" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Số lượng nhập kho ban đầu</label>
                    <input type="number" name="initial_quantity" value="{{ old('initial_quantity', 10) }}" min="0" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Tải lên hình ảnh sản phẩm</label>
                <input type="file" name="image_file" accept="image/*" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                <small style="color: #64748b; display: block; margin-top: 4px;">Hoặc nhập tên file nếu đã có trong thư mục hình ảnh:</small>
                <input type="text" name="image" value="{{ old('image') }}" placeholder="Ví dụ: nike-mercurial-vapor-16.jpg" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; margin-top: 6px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Mô tả sản phẩm</label>
                <textarea name="description" rows="5" placeholder="Chi tiết về chất liệu, tính năng, công nghệ..." class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;">{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding: 10px 20px;">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 600;">
                     Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
