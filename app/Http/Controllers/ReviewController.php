<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(
        Request $request,
        Product $product
    ) {
        if (!auth()->check()) {
            return back()->with('error', 'Vui lòng đăng nhập để đánh giá sản phẩm.');
        }

        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA ĐIỀU KIỆN ĐÁNH GIÁ:
        | Người dùng phải có ít nhất 1 đơn hàng trạng thái 'completed'
        | có chứa sản phẩm này.
        |--------------------------------------------------------------------------
        */
        $hasPurchased = Order::where('user_id', auth()->id())
            ->where('order_status', 'completed')
            ->whereHas('details', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with(
                'error',
                'Bạn chỉ có thể đánh giá sản phẩm này sau khi đã mua và đơn hàng đã hoàn thành (giao thành công).'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'rating' =>
                'required|integer|min:1|max:5',

            'comment' =>
                'nullable|string|max:1000',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TẠO HOẶC CẬP NHẬT ĐÁNH GIÁ
        |--------------------------------------------------------------------------
        | Một User chỉ có một Review cho một Product.
        */
        Review::updateOrCreate(
            [
                'user_id' =>
                    auth()->id(),

                'product_id' =>
                    $product->id,
            ],
            [
                'rating' =>
                    $validated['rating'],

                'comment' =>
                    $validated['comment'] ?? null,
            ]
        );

        return back()->with(
            'success',
            'Cảm ơn bạn! Đánh giá sản phẩm đã được lưu thành công.'
        );
    }
}