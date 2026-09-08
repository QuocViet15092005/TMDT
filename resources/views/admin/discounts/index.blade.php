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
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 15%; padding: 12px 16px;">Mã Voucher</th>
                            <th style="width: 25%; padding: 12px 16px;">Mô tả</th>
                            <th style="width: 15%; padding: 12px 16px; text-align: center;">Loại giảm</th>
                            <th style="width: 12%; padding: 12px 16px; text-align: center;">Giá trị</th>
                            <th style="width: 13%; padding: 12px 16px; text-align: center;">Số lượng</th>
                            <th style="width: 10%; padding: 12px 16px; text-align: center;">Trạng thái</th>
                            <th style="width: 10%; padding: 12px 16px; text-align: center;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $discount)
                            <tr>
                                <td style="padding: 12px 16px;">
                                    <strong style="color: var(--primary-color);">{{ $discount->code }}</strong>
                                    @if($discount->name)
                                        <div style="font-size: 0.85rem; color: #6c757d;">{{ $discount->name }}</div>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; color: #6c757d;">{{ $discount->description ?? 'Không có' }}</td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    {{ $discount->discount_type === 'percentage' ? 'Phần trăm (%)' : 'Cố định (VNĐ)' }}
                                </td>
                                <td class="font-bold" style="padding: 12px 16px; text-align: center;">
                                    {{ $discount->discount_type === 'percentage' ? $discount->discount_value . '%' : number_format($discount->discount_value, 0, ',', '.') . ' VNĐ' }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    {{ $discount->used_count ?? 0 }} / {{ $discount->max_uses ?? '∞' }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    @if($discount->is_active)
                                        <span class="badge badge-success">Đang bật</span>
                                    @else
                                        <span class="badge badge-danger">Tắt</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        <!-- Nút Xem chi tiết mới thêm -->
                                        <a href="{{ route('admin.discounts.show', $discount) }}" class="btn btn-info text-white" style="padding: 6px 12px; font-size: 0.85rem; background-color: #17a2b8; border-color: #17a2b8;">Xem</a>
                                        
                                        <!-- Nút Chỉnh sửa -->
                                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">Sửa</a>
                                        
                                        <!-- Nút Xóa -->
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