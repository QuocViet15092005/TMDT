@extends('layouts.app')

@section('title', 'Chi tiết Voucher')

@section('content')
<div style="background-color: #f4f7fe; padding: 20px; font-family: system-ui, -apple-system, sans-serif; color: #2b3674; min-height: 100vh;">

    <!-- Header & Nút thao tác -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; margin: 0 0 4px 0; color: #1b2559;">Chi tiết Voucher</h1>
            <div style="font-size: 13px; color: #707ebe;">
                Thông tin voucher: <strong>{{ $discount->code }}</strong>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.discounts.edit', $discount) }}" style="background: #fff; border: 1px solid #e0e5f2; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; color: #4318ff; text-decoration: none;">
                 Chỉnh sửa
            </a>
            <a href="{{ route('admin.discounts.index') }}" style="background: #fff; border: 1px solid #e0e5f2; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; color: #2b3674; text-decoration: none;">
                 Quay lại
            </a>
        </div>
    </div>

    <!-- Layout 2 cột (Flexbox Inline) -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <!-- CỘT TRÁI (Rộng 65%) -->
        <div style="flex: 1 1 600px;">
            
            <!-- Card 1: Thông tin Voucher -->
            <div style="background: #ffffff; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12);">
                <div style="font-size: 15px; font-weight: 600; color: #4318ff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                     Thông tin Voucher
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; row-gap: 20px; column-gap: 16px;">
                    <div>
                        <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Mã Voucher</div>
                        <div style="font-size: 16px; font-weight: 700; color: #1b2559; display: flex; align-items: center; gap: 6px;">
                            {{ $discount->code }}
                            <span style="cursor: pointer; font-size: 12px;" onclick="navigator.clipboard.writeText('{{ $discount->code }}')"></span>
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Loại giảm giá</div>
                        <span style="background: #e6f4ea; color: #137333; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block;">
                            {{ $discount->discount_type === 'percentage' ? '% Phần trăm' : 'Cố định (VNĐ)' }}
                        </span>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Tên Voucher</div>
                        <div style="font-size: 14px; font-weight: 600; color: #1b2559;">
                            {{ $discount->name ?? 'Tri ân khách hàng mới' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Giá trị giảm giá</div>
                        <div style="font-size: 16px; font-weight: 700; color: #1b2559;">
                            {{ $discount->discount_type === 'percentage' ? number_format($discount->discount_value, 2) . '%' : number_format($discount->discount_value, 0, ',', '.') . 'đ' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Mô tả</div>
                        <div style="font-size: 13px; color: #2b3674;">
                            {{ $discount->description ?? 'Giảm giá 20%' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Đơn hàng tối thiểu</div>
                        <div style="font-size: 14px; font-weight: 700; color: #1b2559;">
                            {{ number_format($discount->min_purchase_amount ?? 500000, 0, ',', '.') }}đ
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Thống kê sử dụng -->
            <div style="background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12);">
                <div style="font-size: 15px; font-weight: 600; color: #4318ff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    Thống kê sử dụng
                </div>

                <div style="display: grid; grid-template-columns: repeat(4, 1fr); text-align: center; margin-bottom: 24px;">
                    <div>
                        <div style="font-size: 22px; font-weight: 700; color: #1b2559;">{{ $usedCount ?? 0 }}</div>
                        <div style="font-size: 12px; color: #707ebe;">Lần sử dụng</div>
                    </div>
                    <div>
                        <div style="font-size: 22px; font-weight: 700; color: #1b2559;">{{ number_format($totalSavings ?? 0, 0, ',', '.') }}đ</div>
                        <div style="font-size: 12px; color: #707ebe;">Tổng tiết kiệm</div>
                    </div>
                    <div>
                        <div style="font-size: 22px; font-weight: 700; color: #1b2559;">{{ $uniqueUsersCount ?? 0 }}</div>
                        <div style="font-size: 12px; color: #707ebe;">Người dùng</div>
                    </div>
                    <div>
                        <div style="font-size: 22px; font-weight: 700; color: #1b2559;">
                            {{ $discount->max_uses ? ($discount->max_uses - ($usedCount ?? 0)) : 100 }}
                        </div>
                        <div style="font-size: 12px; color: #707ebe;">Còn lại</div>
                    </div>
                </div>

                <!-- Thanh tiến độ -->
                @php
                    $max = $discount->max_uses ?? 100;
                    $used = $usedCount ?? 0;
                    $percent = min(100, round(($used / $max) * 100));
                @endphp
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #707ebe; margin-bottom: 6px;">
                        <span>Tiến độ sử dụng</span>
                        <span>{{ $used }}/{{ $max }}</span>
                    </div>
                    <div style="width: 100%; height: 8px; background-color: #eff3f6; border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $percent }}%; height: 100%; background-color: #4318ff; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- CỘT PHẢI (Rộng 30%) -->
        <div style="flex: 1 1 280px;">
            
            <!-- Card 3: Trạng thái & Cài đặt -->
            <div style="background: #ffffff; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12);">
                <div style="font-size: 15px; font-weight: 600; color: #4318ff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                     Trạng thái & Cài đặt
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 12px; color: #707ebe; margin-bottom: 6px;">Trạng thái</div>
                    <span style="background: #01b574; color: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                        {{ $discount->is_active ? 'Hoạt động' : 'Đã tắt' }}
                    </span>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Hiển thị</div>
                    <div style="font-size: 13px; font-weight: 600; color: #4318ff; display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 8px;">🔵</span> {{ $discount->is_public ? 'Công khai' : 'Riêng tư' }}
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Giới hạn sử dụng</div>
                    <div style="font-size: 13px; font-weight: 600; color: #1b2559;">
                        {{ $discount->max_uses ? $discount->max_uses . ' lần' : '100 lần' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Giới hạn mỗi người</div>
                    <div style="font-size: 13px; font-weight: 600; color: #1b2559;">
                        {{ $discount->user_max_uses ?? 1 }} lần
                    </div>
                </div>
            </div>

            <!-- Card 4: Thời gian hiệu lực -->
            <div style="background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12);">
                <div style="font-size: 15px; font-weight: 600; color: #4318ff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    Thời gian hiệu lực
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Bắt đầu</div>
                    <div style="font-size: 13px; font-weight: 600; color: #1b2559;">
                        {{ $discount->starts_at ? $discount->starts_at->format('d/m/Y H:i') : 'Ngay lập tức' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 12px; color: #707ebe; margin-bottom: 4px;">Kết thúc</div>
                    <div style="font-size: 13px; font-weight: 600; color: #1b2559;">
                        {{ $discount->expires_at ? $discount->expires_at->format('d/m/Y H:i') : '30/09/2030 20:48' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection