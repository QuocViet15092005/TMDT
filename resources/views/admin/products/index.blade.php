@extends('layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
<style>
    /* Đồng bộ kiểu khối panel và form */
    .panel {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    .form-control, select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, select:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .data-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        border-top: 1px solid #e2e8f0;
    }

    .data-table td {
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 12px;
    }
</style>

<div class="container section-space" style="padding: 28px 0;">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <h1 class="section-title" style="font-weight: 800; margin-bottom: 4px;"> Quản lý sản phẩm</h1>
            <p class="section-subtitle" style="color: #64748b; margin: 0;">Danh sách toàn bộ sản phẩm, thêm mới, sửa giá, kho hàng và xóa sản phẩm.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 6px; font-weight: 700; border-radius: 10px; padding: 10px 18px;">
                 Thêm sản phẩm mới
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="border-radius: 10px; padding: 10px 18px; font-weight: 600;">
                 Quản lý danh mục
            </a>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
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

    {{-- BỘ LỌC VÀ TÌM KIẾM --}}
    <div class="panel" style="margin-bottom: 24px; padding: 16px 20px;">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Tìm theo tên sản phẩm, thương hiệu..." 
                class="form-control" 
                style="flex: 1; min-width: 220px; padding: 10px 14px; font-size: 0.9rem;"
            >

            <select name="category_id" style="padding: 10px 14px; font-size: 0.9rem; min-width: 180px; background-color: #ffffff;">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 10px 20px; border-radius: 10px; font-weight: 700;">
                Lọc sản phẩm
            </button>

            @if(request('search') || request('category_id'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding: 10px 16px; border-radius: 10px; font-weight: 600;">
                    ✕ Xóa lọc
                </a>
            @endif
        </form>
    </div>

    {{-- BẢNG SẢN PHẨM --}}
    <div class="panel" style="padding: 24px;">
        @if($products->isEmpty())
            <div class="alert alert-info" style="border-radius: 10px; margin: 0;">
                Không tìm thấy sản phẩm nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 80px;">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá bán</th>
                            <th>Biến thể / Kho</th>
                            <th style="text-align: right; min-width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            @php
                                $totalStock = $product->variants->sum('quantity');
                            @endphp
                            <tr>
                                <td><strong>#{{ $product->id }}</strong></td>
                                <td>
                                    @if($product->image)
                                        <img 
                                            src="{{ asset('images/products/' . $product->image) }}" 
                                            alt="{{ $product->name }}" 
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;"
                                            onerror="this.src='{{ asset('images/no-image.jpg') }}'"
                                        >
                                    @else
                                        <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                            👟
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong style="color: #0f172a; font-size: 0.92rem;">{{ $product->name }}</strong>
                                    @if($product->brand)
                                        <br><small style="color: #64748b;">Hãng: {{ $product->brand }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background: #dbeafe; color: #2563eb; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">
                                        {{ $product->category->name ?? 'Chưa phân loại' }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: #16a34a;">
                                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                    </strong>
                                </td>
                                <td>
                                    @if($product->variants->count() > 0)
                                        <span class="badge {{ $totalStock > 0 ? 'bg-success' : 'bg-danger' }}" style="padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">
                                            {{ $product->variants->count() }} biến thể (Tổng: {{ $totalStock }})
                                        </span>
                                    @else
                                        <span class="badge bg-warning" style="padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">Chưa tạo biến thể</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem; border-radius: 8px;" title="Chỉnh sửa thông tin">
                                             Sửa
                                        </a>
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem; background: #f0fdf4; color: #166534; border-color: #bbf7d0; border-radius: 8px;" title="Quản lý kích cỡ Size">
                                             Size
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm \'{{ $product->name }}\'? Hành động này không thể hoàn tác!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem; border-radius: 8px;" title="Xóa sản phẩm">
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

            <div class="mt-4" style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection