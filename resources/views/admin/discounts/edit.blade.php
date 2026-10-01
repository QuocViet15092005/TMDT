@extends('layouts.app')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="container section-space">
    <div class="auth-box" style="max-width: 600px;">
        <h1 class="section-title">Chỉnh sửa mã giảm giá</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="m-0">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="font-bold">Mã Voucher (Code) *</label>
                        <input type="text" name="code" value="{{ old('code', $discount->code) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Mô tả</label>
                        <textarea name="description">{{ old('description', $discount->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Loại giảm giá *</label>
                        <select name="discount_type" required>
                            <option value="percentage" @selected(old('discount_type', $discount->discount_type) === 'percentage')>Phần trăm (%)</option>
                            <option value="fixed" @selected(old('discount_type', $discount->discount_type) === 'fixed')>Số tiền cố định (VNĐ)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Giá trị giảm *</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $discount->discount_value) }}" required step="0.01" min="0">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Số lượt sử dụng tối đa</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses', $discount->max_uses) }}" min="1">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Đơn hàng tối thiểu (VNĐ)</label>
                        <input type="number" name="min_purchase_amount" value="{{ old('min_purchase_amount', $discount->min_purchase_amount) }}" min="0">
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-bold">Trạng thái</label>
                        <select name="is_active">
                            <option value="1" @selected(old('is_active', $discount->is_active) == 1)>Bật (Hoạt động)</option>
                            <option value="0" @selected(old('is_active', $discount->is_active) == 0)>Tắt</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
