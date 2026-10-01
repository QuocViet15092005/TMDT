@extends('layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container section-space">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;">👟 Quản lý sản phẩm</h1>
            <p class="section-subtitle" style="margin: 0;">Danh sách toàn bộ sản phẩm, thêm mới, sửa giá, kho hàng và xóa sản phẩm.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                ➕ Thêm sản phẩm mới
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                🏷️ Quản lý danh mục
            </a>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
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

    {{-- BỘ LỌC VÀ TÌM KIẾM --}}
    <div class="panel" style="margin-bottom: 20px; padding: 14px 20px;">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Tìm theo tên sản phẩm, thương hiệu..." 
                class="form-control" 
                style="flex: 1; min-width: 200px; padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem;"
            >

            <select name="category_id" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; min-width: 160px;">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">
                🔍 Lọc sản phẩm
            </button>

            @if(request('search') || request('category_id'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding: 8px 14px;">
                    ✕ Xóa lọc
                </a>
            @endif
        </form>
    </div>

    {{-- BẢNG SẢN PHẨM --}}
    @if($products->isEmpty())
        <div class="alert alert-info">
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
                            <td>#{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                    <img 
                                        src="{{ asset('images/products/' . $product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;"
                                        onerror="this.src='{{ asset('images/no-image.jpg') }}'"
                                    >
                                @else
                                    <div style="width: 55px; height: 55px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                                        👟
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->brand)
                                    <br><small style="color: #64748b;">Hãng: {{ $product->brand }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--secondary-color);">
                                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                </strong>
                            </td>
                            <td>
                                @if($product->variants->count() > 0)
                                    <span class="badge {{ $totalStock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->variants->count() }} biến thể (Tổng: {{ $totalStock }})
                                    </span>
                                @else
                                    <span class="badge bg-warning">Chưa tạo biến thể</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85rem;" title="Chỉnh sửa thông tin">
                                        ✏️ Sửa
                                    </a>
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85rem; background: #f0fdf4; color: #166534; border-color: #bbf7d0;" title="Quản lý kích cỡ Size">
                                        🎨 Size
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm \'{{ $product->name }}\'? Hành động này không thể hoàn tác!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.85rem;" title="Xóa sản phẩm">
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
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
