@extends('layouts.app')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="container section-space">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;">🎟️ Quản lý mã giảm giá</h1>
            <p class="section-subtitle" style="margin: 0;">Danh sách các voucher khuyến mãi của cửa hàng.</p>
        </div>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">+ Tạo mã giảm giá mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Voucher</th>
                            <th>Mô tả</th>
                            <th>Loại giảm</th>
                            <th>Giá trị</th>
                            <th>Số lượng</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $discount)
                            <tr>
                                <td><strong style="color: var(--primary-color);">{{ $discount->code }}</strong></td>
                                <td>{{ $discount->description ?? 'Không có' }}</td>
                                <td>{{ $discount->discount_type === 'percentage' ? 'Phần trăm (%)' : 'Cố định (VNĐ)' }}</td>
                                <td class="font-bold">
                                    {{ $discount->discount_type === 'percentage' ? $discount->discount_value . '%' : number_format($discount->discount_value, 0, ',', '.') . ' VNĐ' }}
                                </td>
                                <td>{{ $discount->used_count ?? 0 }} / {{ $discount->max_uses ?? '∞' }}</td>
                                <td>
                                    @if($discount->is_active)
                                        <span class="badge badge-success">Đang bật</span>
                                    @else
                                        <span class="badge badge-danger">Tắt</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">Sửa</a>
                                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 24px;">Chưa có mã giảm giá nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($discounts->hasPages())
        <div class="pagination-wrap mt-4">
            {{ $discounts->links() }}
        </div>
    @endif
</div>
@endsection
