@extends('layouts.app')

@section('title', 'Tạo mã giảm giá mới')

@section('content')
<div class="container my-4" style="max-width: 1200px !important;">
    <h1 class="h4 font-weight-bold mb-4 text-dark">Tạo mã giảm giá mới</h1>

    @if($errors->any())
        <div class="alert alert-danger mb-4 style-small">
            @foreach($errors->all() as $error)
                <p class="m-0">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.discounts.store') }}" method="POST">
        @csrf
        
        <div class="row" style="display: flex; flex-wrap: wrap;">
            
            <!-- CỘT TRÁI (CHIẾM 66.6% CHIỀU RỘNG) -->
            <div class="col-md-8 col-12 mb-4" style="flex: 0 0 66.666667%; max-width: 66.666667%;">
                
                <!-- Khối 1: Thông tin cơ bản -->
                <div class="card border mb-3 shadow-sm" style="border-radius: 6px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-uppercase text-secondary mb-3" style="font-size: 13px; letter-spacing: 0.5px;">Thông tin voucher</h6>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Tên Voucher (Chương trình) *</label>
                            <input type="text" name="name" id="input_name" class="form-control" value="{{ old('name') }}" required placeholder="VD: Giảm giá mùa hè, Tri ân khách hàng" style="font-size: 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Mã Voucher (Code) *</label>
                            <input type="text" name="code" id="input_code" class="form-control" value="{{ old('code') }}" required placeholder="VD: SALE20, FREE100K" style="font-size: 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Mô tả</label>
                            <textarea name="description" id="input_desc" class="form-control" rows="2" placeholder="Mô tả chương trình..." style="font-size: 14px;">{{ old('description') }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Loại giảm giá *</label>
                                <select name="discount_type" id="input_type" class="form-control" required style="font-size: 14px;">
                                    <option value="percentage" @selected(old('discount_type') === 'percentage')>Phần trăm (%)</option>
                                    <option value="fixed" @selected(old('discount_type') === 'fixed')>Số tiền cố định (VNĐ)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Giá trị giảm *</label>
                                <input type="number" name="discount_value" id="input_value" class="form-control" value="{{ old('discount_value') }}" required step="0.01" min="0" placeholder="VD: 10 hoặc 50000" style="font-size: 14px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Đơn hàng tối thiểu (VNĐ)</label>
                                <input type="number" name="min_purchase_amount" id="input_min" class="form-control" value="{{ old('min_purchase_amount', 0) }}" min="0" placeholder="0" style="font-size: 14px;">
                                <small class="text-muted d-block mt-1" style="font-size: 12px;">Để trống nếu không có yêu cầu</small>
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Giảm tối đa (VNĐ)</label>
                                <input type="number" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount') }}" min="0" placeholder="VD: 50000" style="font-size: 14px;">
                                <small class="text-muted d-block mt-1" style="font-size: 12px;">Áp dụng cho voucher phần trăm</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Khối 2: Giới hạn sử dụng -->
                <div class="card border mb-3 shadow-sm" style="border-radius: 6px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-uppercase text-secondary mb-3" style="font-size: 13px; letter-spacing: 0.5px;">Giới hạn sử dụng</h6>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Tổng số lần sử dụng tối đa</label>
                                <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses') }}" min="1" placeholder="Không giới hạn" style="font-size: 14px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Giới hạn mỗi người dùng</label>
                                <input type="number" name="user_max_uses" class="form-control" value="{{ old('user_max_uses', 1) }}" min="1" placeholder="1" style="font-size: 14px;">
                                <small class="text-muted d-block mt-1" style="font-size: 12px;">Số lần tối đa 1 tài khoản được áp dụng</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Khối 3: Danh mục -->
                <div class="card border shadow-sm" style="border-radius: 6px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-uppercase text-secondary mb-3" style="font-size: 13px; letter-spacing: 0.5px;">Phạm vi áp dụng</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category_type" id="cat_all" value="all" @checked(old('category_type', 'all') === 'all')>
                            <label class="form-check-label text-dark" for="cat_all" style="font-size: 14px;">Áp dụng cho tất cả danh mục</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category_type" id="cat_specific" value="specific" @checked(old('category_type') === 'specific')>
                            <label class="form-check-label text-secondary" for="cat_specific" style="font-size: 14px;">Chỉ áp dụng cho các danh mục được chọn</label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CỘT PHẢI (CHIẾM 33.3% CHIỀU RỘNG) -->
            <div class="col-md-4 col-12 mb-4" style="flex: 0 0 33.333333%; max-width: 33.333333%;">
                
                <!-- Khối 1: Thời gian hiệu lực -->
                <div class="card border mb-3 shadow-sm" style="border-radius: 6px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-uppercase text-secondary mb-3" style="font-size: 13px; letter-spacing: 0.5px;">Thời gian hiệu lực</h6>
                        
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Ngày bắt đầu</label>
                            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}" style="font-size: 14px;">
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">Để trống nếu có hiệu lực ngay</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Ngày kết thúc</label>
                            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}" style="font-size: 14px;">
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">Để trống nếu không hết hạn</small>
                        </div>
                    </div>
                </div>

                <!-- Khối 2: Trạng thái & Thao tác -->
                <div class="card border mb-3 shadow-sm" style="border-radius: 6px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-uppercase text-secondary mb-3" style="font-size: 13px; letter-spacing: 0.5px;">Cấu hình & Trạng thái</h6>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 14px;">Trạng thái hoạt động</label>
                            <select name="is_active" class="form-control" style="font-size: 14px;">
                                <option value="1" @selected(old('is_active', '1') == '1')>Bật (Hoạt động)</option>
                                <option value="0" @selected(old('is_active') == '0')>Tắt</option>
                            </select>
                        </div>

                        <div class="form-check mb-4">
                            <input type="hidden" name="is_public" value="0">
                            <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1" @checked(old('is_public', 1))>
                            <label class="form-check-label font-weight-bold text-dark" for="is_public" style="font-size: 14px;">
                                Hiển thị công khai
                            </label>
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">Hiển thị ở danh sách voucher của khách hàng</small>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary px-3" style="font-size: 14px;">Hủy</a>
                            <button type="submit" class="btn btn-primary px-3" style="font-size: 14px;">Tạo voucher</button>
                        </div>
                    </div>
                </div>

                <!-- Khối 3: Xem trước -->
                <div class="card border shadow-sm" style="border-radius: 6px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-uppercase text-secondary mb-3" style="font-size: 13px; letter-spacing: 0.5px;">Xem trước</h6>
                        
                        <div class="p-3 text-center border rounded" style="background-color: #f8f9fa;">
                            <span class="badge badge-primary bg-primary text-white mb-2 px-3 py-2" id="pv_code" style="font-size: 13px; font-weight: 600;">CODE_VOUCHER</span>
                            <p class="font-weight-bold text-dark mb-1" id="pv_desc" style="font-size: 13px;">Mô tả chương trình...</p>
                            <p class="text-primary font-weight-bold mb-1" id="pv_value" style="font-size: 14px;">Mức giảm</p>
                            <small class="text-muted d-block" id="pv_min" style="font-size: 12px;">Đơn hàng từ 0đ</small>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- SCRIPT LIVE PREVIEW -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputCode = document.getElementById('input_code');
        const inputDesc = document.getElementById('input_desc');
        const inputType = document.getElementById('input_type');
        const inputValue = document.getElementById('input_value');
        const inputMin = document.getElementById('input_min');

        const pvCode = document.getElementById('pv_code');
        const pvDesc = document.getElementById('pv_desc');
        const pvValue = document.getElementById('pv_value');
        const pvMin = document.getElementById('pv_min');

        function updatePreview() {
            pvCode.innerText = inputCode.value.trim() !== '' ? inputCode.value.toUpperCase() : 'CODE_VOUCHER';
            pvDesc.innerText = inputDesc.value.trim() !== '' ? inputDesc.value : 'Mô tả chương trình...';

            let val = inputValue.value ? parseFloat(inputValue.value) : 0;
            if (inputType.value === 'percentage') {
                pvValue.innerText = 'Giảm ' + val + '%';
            } else {
                pvValue.innerText = 'Giảm ' + val.toLocaleString('vi-VN') + 'đ';
            }

            let min = inputMin.value ? parseFloat(inputMin.value) : 0;
            pvMin.innerText = 'Đơn hàng từ ' + min.toLocaleString('vi-VN') + 'đ';
        }

        inputCode.addEventListener('input', updatePreview);
        inputDesc.addEventListener('input', updatePreview);
        inputType.addEventListener('change', updatePreview);
        inputValue.addEventListener('input', updatePreview);
        inputMin.addEventListener('input', updatePreview);

        updatePreview();
    });
</script>
@endsection