@extends('layouts.app')

@section('title', 'Chi tiết Voucher')

@section('content')
<div style="background-color: #f4f7fe; padding: 28px 0; font-family: system-ui, -apple-system, sans-serif; color: #2b3674; min-height: 100vh;">
    <div class="container">
        <!-- Header & Nút thao tác -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 1.6rem; font-weight: 800; margin: 0 0 4px 0; color: #1b2559;">Chi tiết Voucher</h1>
                <div style="font-size: 0.92rem; color: #707ebe;">
                    Thông tin voucher: <strong>{{ $discount->code }}</strong>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.discounts.edit', $discount) }}" style="background: #2563eb; border: none; padding: 10px 18px; border-radius: 10px; font-size: 0.9rem; font-weight: 700; cursor: pointer; color: #ffffff; text-decoration: none; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                     Chỉnh sửa
                </a>
                <a href="{{ route('admin.discounts.index') }}" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 10px 18px; border-radius: 10px; font-size: 0.9rem; font-weight: 600; cursor: pointer; color: #334155; text-decoration: none;">
                     Quay lại
                </a>
            </div>
        </div>

        <!-- Layout 2 cột (Flexbox Inline) -->
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            
            <!-- CỘT TRÁI -->
            <div style="flex: 1 1 600px;">
                
                <!-- Card 1: Thông tin Voucher -->
                <div style="background: #ffffff; border-radius: 16px; padding: 28px; margin-bottom: 20px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12); border: 1px solid #e2e8f0;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: #2563eb; margin-bottom: 22px; display: flex; align-items: center; gap: 8px;">
                         Thông tin Voucher
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; row-gap: 22px; column-gap: 20px;">
                        <div>
                            <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 4px; font-weight: 600;">Mã Voucher</div>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #1b2559;">
                                {{ $discount->code }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Loại giảm giá</div>
                            <span style="background: #dcfce7; color: #15803d; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: inline-block;">
                                {{ $discount->discount_type === 'percentage' ? '% Phần trăm' : 'Cố định (VNĐ)' }}
                            </span>
                        </div>

                        <div>
                            <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 4px; font-weight: 600;">Tên Voucher</div>
                            <div style="font-size: 0.98rem; font-weight: 700; color: #1b2559;">
                                {{ $discount->name ?? 'Tri ân khách hàng mới' }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 4px; font-weight: 600;">Giá trị giảm giá</div>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #16a34a;">
                                {{ $discount->discount_type === 'percentage' ? number_format($discount->discount_value, 2) . '%' : number_format($discount->discount_value, 0, ',', '.') . 'đ' }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 4px; font-weight: 600;">Mô tả</div>
                            <div style="font-size: 0.92rem; color: #334155; font-weight: 500;">
                                {{ $discount->description ?? 'Giảm giá 20%' }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 4px; font-weight: 600;">Đơn hàng tối thiểu</div>
                            <div style="font-size: 0.98rem; font-weight: 700; color: #1b2559;">
                                {{ number_format($discount->min_purchase_amount ?? 500000, 0, ',', '.') }}đ
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Thống kê sử dụng -->
                <div style="background: #ffffff; border-radius: 16px; padding: 28px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12); border: 1px solid #e2e8f0;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: #2563eb; margin-bottom: 22px; display: flex; align-items: center; gap: 8px;">
                        Thống kê sử dụng
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); text-align: center; margin-bottom: 24px;">
                        <div>
                            <div style="font-size: 1.4rem; font-weight: 800; color: #1b2559;">{{ $usedCount ?? 0 }}</div>
                            <div style="font-size: 0.85rem; color: #707ebe; font-weight: 600;">Lần sử dụng</div>
                        </div>
                        <div>
                            <div style="font-size: 1.4rem; font-weight: 800; color: #16a34a;">{{ number_format($totalSavings ?? 0, 0, ',', '.') }}đ</div>
                            <div style="font-size: 0.85rem; color: #707ebe; font-weight: 600;">Tổng tiết kiệm</div>
                        </div>
                        <div>
                            <div style="font-size: 1.4rem; font-weight: 800; color: #1b2559;">{{ $uniqueUsersCount ?? 0 }}</div>
                            <div style="font-size: 0.85rem; color: #707ebe; font-weight: 600;">Người dùng</div>
                        </div>
                        <div>
                            <div style="font-size: 1.4rem; font-weight: 800; color: #2563eb;">
                                {{ $discount->max_uses ? ($discount->max_uses - ($usedCount ?? 0)) : 100 }}
                            </div>
                            <div style="font-size: 0.85rem; color: #707ebe; font-weight: 600;">Còn lại</div>
                        </div>
                    </div>

                    <!-- Thanh tiến độ -->
                    @php
                        $max = $discount->max_uses ?? 100;
                        $used = $usedCount ?? 0;
                        $percent = min(100, round(($used / $max) * 100));
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: #707ebe; margin-bottom: 8px; font-weight: 600;">
                            <span>Tiến độ sử dụng</span>
                            <span>{{ $used }}/{{ $max }}</span>
                        </div>
                        <div style="width: 100%; height: 10px; background-color: #f1f5f9; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $percent }}%; height: 100%; background-color: #2563eb; border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CỘT PHẢI -->
            <div style="flex: 1 1 300px;">
                
                <!-- Card 3: Trạng thái & Cài đặt -->
                <div style="background: #ffffff; border-radius: 16px; padding: 28px; margin-bottom: 20px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12); border: 1px solid #e2e8f0;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: #2563eb; margin-bottom: 22px; display: flex; align-items: center; gap: 8px;">
                         Trạng thái & Cài đặt
                    </div>

                    <div style="margin-bottom: 18px;">
                        <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Trạng thái</div>
                        <span style="background: #dcfce7; color: #15803d; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-block;">
                            {{ $discount->is_active ? 'Hoạt động' : 'Đã tắt' }}
                        </span>
                    </div>

                    <div style="margin-bottom: 18px;">
                        <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Hiển thị</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #1b2559; display: flex; align-items: center; gap: 6px;">
                            <span>🔵</span> {{ $discount->is_public ? 'Công khai' : 'Riêng tư' }}
                        </div>
                    </div>

                    <div style="margin-bottom: 18px;">
                        <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Giới hạn sử dụng</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #1b2559;">
                            {{ $discount->max_uses ? $discount->max_uses . ' lần' : '100 lần' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Giới hạn mỗi người</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #1b2559;">
                            {{ $discount->user_max_uses ?? 1 }} lần
                        </div>
                    </div>
                </div>

                <!-- Card 4: Thời gian hiệu lực -->
                <div style="background: #ffffff; border-radius: 16px; padding: 28px; box-shadow: 0px 18px 40px rgba(112, 144, 176, 0.12); border: 1px solid #e2e8f0;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: #2563eb; margin-bottom: 22px; display: flex; align-items: center; gap: 8px;">
                        Thời gian hiệu lực
                    </div>

                    <div style="margin-bottom: 18px;">
                        <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Bắt đầu</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #1b2559;">
                            {{ $discount->starts_at ? $discount->starts_at->format('d/m/Y H:i') : 'Ngay lập tức' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.85rem; color: #707ebe; margin-bottom: 6px; font-weight: 600;">Kết thúc</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #1b2559;">
                            {{ $discount->expires_at ? $discount->expires_at->format('d/m/Y H:i') : '30/09/2030 20:48' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection