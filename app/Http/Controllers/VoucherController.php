<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VoucherController extends Controller
{
    /**
     * Hiển thị danh sách các voucher đang active
     */
    public function index()
    {
        $query = Discount::where('is_active', true);

        // Kiểm tra cột start_date
        if (Schema::hasColumn('discounts', 'start_date')) {
            $query->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            });
        }

        // Kiểm tra cột end_date
        if (Schema::hasColumn('discounts', 'end_date')) {
            $query->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
        }

        $vouchers = $query->latest()->get();

        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Xem lịch sử sử dụng voucher của khách hàng
     */
    public function history()
    {
        // Xác định tên cột lưu mã giảm giá trong bảng orders
        $discountColumn = null;
        if (Schema::hasColumn('orders', 'discount_code')) {
            $discountColumn = 'discount_code';
        } elseif (Schema::hasColumn('orders', 'voucher_code')) {
            $discountColumn = 'voucher_code';
        } elseif (Schema::hasColumn('orders', 'coupon_code')) {
            $discountColumn = 'coupon_code';
        }

        // Nếu có cột tương ứng thì mới query, ngược lại trả về danh sách trống
        if ($discountColumn) {
            $orders = Order::where('user_id', auth()->id())
                ->whereNotNull($discountColumn)
                ->latest()
                ->get();
        } else {
            $orders = collect(); // Mảng rỗng an toàn
        }

        return view('vouchers.history', compact('orders'));
    }
}