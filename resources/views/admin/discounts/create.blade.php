@extends('layouts.app')

@section('title', 'Tạo mã giảm giá mới')

@section('content')
<div class="container section-space">
    <div class="auth-box" style="max-width: 600px;">
        <h1 class="section-title">Tạo mã giảm giá mới</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="m-0">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.discounts.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="font-bold">Mã Voucher (Code) *</label>
                        <input type="text" name="code" value="{{ old('code') }}" required placeholder="VD: SALE20, FREE100K">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Mô tả</label>
                        <textarea name="description" placeholder="Mô tả chương trình...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Loại giảm giá *</label>
                        <select name="discount_type" required>
                            <option value="percentage" @selected(old('discount_type') === 'percentage')>Phần trăm (%)</option>
                            <option value="fixed" @selected(old('discount_type') === 'fixed')>Số tiền cố định (VNĐ)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Giá trị giảm *</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value') }}" required step="0.01" min="0" placeholder="VD: 10 (với %) hoặc 50000 (với VNĐ)">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Số lượt sử dụng tối đa</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" placeholder="Để trống nếu không giới hạn">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-bold">Đơn hàng tối thiểu (VNĐ)</label>
                        <input type="number" name="min_purchase_amount" value="{{ old('min_purchase_amount', 0) }}" min="0">
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-bold">Trạng thái</label>
                        <select name="is_active">
                            <option value="1">Bật (Hoạt động)</option>
                            <option value="0">Tắt</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn btn-primary">Tạo mã</button>
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
