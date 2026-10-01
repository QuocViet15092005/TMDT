<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountApiController extends Controller
{
    // Kiểm tra và lấy thông tin mã giảm giá
    public function validateDiscount(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $discount = Discount::where('code', strtoupper($validated['code']))
            ->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại.',
            ], 404);
        }

        if (!$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không còn hiệu lực.',
            ], 400);
        }

        if ($validated['amount'] < $discount->min_purchase_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải từ ' . number_format($discount->min_purchase_amount, 0, ',', '.') . ' đ trở lên.',
            ], 400);
        }

        $discountAmount = $discount->calculateDiscount($validated['amount']);

        return response()->json([
            'success' => true,
            'discount' => [
                'code' => $discount->code,
                'discount_type' => $discount->discount_type,
                'discount_value' => $discount->discount_value,
                'discount_amount' => $discountAmount,
                'final_amount' => $validated['amount'] - $discountAmount,
            ],
        ]);
    }
}
