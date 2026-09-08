@extends('layouts.app')

@section('title', 'Voucher Giảm Giá')

@section('content')
<div class="container section-space" style="max-width: 900px; margin: 0 auto; padding: 30px 15px;">

    {{-- BUTTON LỊCH SỬ VOUCHER --}}
    @auth
        <div style="display: flex; justify-content: center; margin-bottom: 24px;">
            <a href="{{ route('vouchers.history') }}" class="btn-history-tab">
                ⏱ Lịch sử voucher
            </a>
        </div>
    @endauth

    {{-- DANH SÁCH VOUCHER --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 20px;">
        @forelse($vouchers as $voucher)
            @php
                // Tự động quét toàn bộ dữ liệu voucher để tìm giá trị số thực sự (khác 0)
                $data = $voucher->toArray();
                
                // 1. TÌM LOẠI GIẢM GIÁ
                $type = $voucher->type ?? $voucher->discount_type ?? $voucher->type_discount ?? 'fixed';
                
                // 2. TÌM CON SỐ MỨC GIẢM TRONG CSDL (Bỏ qua id, min, max, count, timestamps)
                $rawValue = 0;
                $excludedKeys = ['id', 'min_purchase_amount', 'min_order_amount', 'min_purchase', 'min_order', 'used_count', 'usage_limit', 'limit', 'max_uses', 'is_active', 'user_id'];
                
                foreach ($data as $key => $val) {
                    if (!in_array($key, $excludedKeys) && is_numeric($val) && (float)$val > 0) {
                        $rawValue = (float)$val;
                        break;
                    }
                }

                // 3. XỬ LÝ ĐỊNH DẠNG HIỂN THỊ
                $isPercent = (strtolower($type) === 'percent' || strtolower($type) === 'percentage');
                
                if ($isPercent) {
                    $discountText = number_format($rawValue) . '%';
                } else {
                    $discountText = number_format($rawValue, 0, ',', '.') . 'đ';
                }

                // 4. LẤY ĐƠN TỐI THIỂU
                $minOrder = $voucher->min_purchase_amount 
                    ?? $voucher->min_order_amount 
                    ?? $voucher->min_purchase 
                    ?? $voucher->min_order 
                    ?? 0;

                // 5. LẤY SỐ LẦN DÙNG VÀ HẠN DÙNG
                $used = $voucher->used_count ?? $voucher->used ?? $voucher->times_used ?? 0;
                $limit = $voucher->usage_limit ?? $voucher->limit ?? $voucher->max_uses ?? '∞';
                $endDate = $voucher->end_date ?? $voucher->expires_at ?? $voucher->valid_until ?? null;
            @endphp

            <div class="voucher-card">
                
                {{-- HEADER CARD --}}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <span class="badge-status">Hoạt động</span>
                    <span style="font-size: 1.2rem; font-weight: 700; color: #2563eb;">
                        {{ $isPercent ? '%' : '₫' }}
                    </span>
                </div>

                {{-- TÊN & MÔ TẢ --}}
                <h3 class="voucher-name">{{ $voucher->name ?? $voucher->title ?? 'Mã giảm giá' }}</h3>
                <p class="voucher-desc">
                    Giảm {{ $discountText }} cho đơn hàng từ {{ number_format((float)$minOrder, 0, ',', '.') }}đ
                </p>

                {{-- BẢNG THÔNG TIN MÃ --}}
                <div class="voucher-details-grid">
                    <div>
                        <span class="detail-label">Mức giảm:</span>
                        <strong class="detail-val" style="color: #2563eb;">{{ $discountText }}</strong>
                    </div>
                    <div>
                        <span class="detail-label">Đơn tối thiểu:</span>
                        <strong class="detail-val">{{ number_format((float)$minOrder, 0, ',', '.') }}đ</strong>
                    </div>
                    <div>
                        <span class="detail-label">Mã voucher:</span>
                        <strong class="detail-val" style="color: #1d4ed8;">{{ $voucher->code }}</strong>
                    </div>
                    <div>
                        <span class="detail-label">Đã sử dụng:</span>
                        <strong class="detail-val">{{ $used }}/{{ $limit }}</strong>
                    </div>
                    <div style="grid-column: span 2;">
                        <span class="detail-label">Hết hạn:</span>
                        <strong class="detail-val">
                            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y H:i') : 'Không giới hạn' }}
                        </strong>
                    </div>
                </div>

                {{-- NÚT SAO CHÉP MÃ --}}
                <button type="button" class="btn-copy-code" onclick="copyCode('{{ $voucher->code }}', this)">
                    📋 Sao chép mã
                </button>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #fff; border-radius: 12px; color: #64748b;">
                Hiện tại chưa có mã giảm giá nào sẵn có.
            </div>
        @endforelse
    </div>

</div>

<style>
    .btn-history-tab {
        background: #2563eb;
        color: #fff;
        padding: 10px 28px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
    }
    .btn-history-tab:hover {
        background: #1d4ed8;
        color: #fff;
    }
    .voucher-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eff6ff;
    }
    .badge-status {
        background: #eff6ff;
        color: #2563eb;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 12px;
        font-weight: 600;
    }
    .voucher-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 6px 0;
    }
    .voucher-desc {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0 0 16px 0;
    }
    .voucher-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        background: #f8fafc;
        padding: 14px;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    .detail-label {
        display: block;
        color: #94a3b8;
        font-size: 0.75rem;
        margin-bottom: 2px;
    }
    .detail-val {
        color: #334155;
        font-size: 0.88rem;
        font-weight: 700;
    }
    .btn-copy-code {
        width: 100%;
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 11px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-copy-code:hover {
        background: #1d4ed8;
    }
</style>

<script>
function copyCode(code, element) {
    navigator.clipboard.writeText(code).then(() => {
        const oldText = element.innerHTML;
        element.innerHTML = '✓ Đã sao chép!';
        element.style.background = '#16a34a';
        
        setTimeout(() => {
            element.innerHTML = oldText;
            element.style.background = '#2563eb';
        }, 2000);
    });
}
</script>
@endsection