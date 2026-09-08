@extends('layouts.app')

@section('title', 'Lịch sử sử dụng Voucher')

@section('content')
<div class="container section-space" style="max-width: 800px; margin: 0 auto; padding: 30px 15px;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('vouchers.index') }}" style="color: #64748b; text-decoration: none; font-weight: 600;">← Quay lại danh sách voucher</a>
        <h2 style="font-size: 1.4rem; font-weight: 800; color: #1e293b; margin-top: 10px;">⏱ Lịch sử Voucher đã sử dụng</h2>
    </div>

    <div class="card" style="padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9; text-align: left;">
                    <th style="padding: 12px;">Mã đơn</th>
                    <th style="padding: 12px;">Mã Voucher</th>
                    <th style="padding: 12px;">Số tiền giảm</th>
                    <th style="padding: 12px;">Ngày dùng</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 12px;">
                            <a href="{{ route('orders.show', $order->id) }}" style="color: #2563eb; font-weight: 700;">#{{ $order->id }}</a>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #eff6ff; color: #2563eb; padding: 4px 8px; border-radius: 4px; font-weight: 700;">
                                {{ $order->discount_code ?? $order->voucher_code ?? $order->coupon_code ?? 'N/A' }}
                            </span>
                        </td>
                        <td style="padding: 12px; color: #16a34a; font-weight: 700;">
                            -{{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}đ
                        </td>
                        <td style="padding: 12px; color: #64748b;">
                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px; color: #94a3b8;">
                            Bạn chưa sử dụng mã giảm giá nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection