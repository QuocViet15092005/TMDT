<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    // Thêm biến thể mới
    public function store(
        Request $request,
        Product $product
    ) {
        $validated = $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
        ]);

        // Kiểm tra tránh trùng Size + Màu
        $exists = $product->variants()
            ->where('size', $validated['size'] ?? null)
            ->where('color', $validated['color'] ?? null)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Biến thể Size và Màu này đã tồn tại.'
            );
        }

        $product->variants()->create([
            'size' => $validated['size'] ?? null,
            'color' => $validated['color'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

        return back()->with(
            'success',
            'Thêm biến thể thành công.'
        );
    }


    // Cập nhật biến thể
    public function update(
        Request $request,
        ProductVariant $variant
    ) {
        $validated = $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
        ]);

        // Kiểm tra trùng biến thể khác
        $exists = ProductVariant::where(
                'product_id',
                $variant->product_id
            )
            ->where('size', $validated['size'] ?? null)
            ->where('color', $validated['color'] ?? null)
            ->where('id', '!=', $variant->id)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Size và Màu này đã tồn tại.'
            );
        }

        $variant->update([
            'size' => $validated['size'] ?? null,
            'color' => $validated['color'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

        return back()->with(
            'success',
            'Cập nhật biến thể thành công.'
        );
    }


    // Xóa biến thể
    public function destroy(
        ProductVariant $variant
    ) {
        $variant->delete();

        return back()->with(
            'success',
            'Xóa biến thể thành công.'
        );
    }
}