<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }


    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'buy_now' => 'nullable|in:0,1',
        ]);

        $variant = ProductVariant::with('product')
            ->findOrFail($validated['product_variant_id']);

        $product = $variant->product;

        // Kiểm tra sản phẩm còn bán không
        if (!$product->is_active) {
            return back()->with(
                'error',
                'Sản phẩm hiện không còn kinh doanh.'
            );
        }

        // Kiểm tra tồn kho
        if ($variant->quantity <= 0) {
            return back()->with(
                'error',
                'Biến thể sản phẩm đã hết hàng.'
            );
        }

        $quantity = $validated['quantity'];

        if ($quantity > $variant->quantity) {
            return back()->with(
                'error',
                'Số lượng yêu cầu vượt quá tồn kho.'
            );
        }

        $cart = session()->get('cart', []);

        // Dùng variant id để phân biệt size + màu
        $key = $variant->id;

        if (isset($cart[$key])) {

            $newQuantity =
                $cart[$key]['quantity'] + $quantity;

            if ($newQuantity > $variant->quantity) {
                return back()->with(
                    'error',
                    'Tổng số lượng trong giỏ vượt quá tồn kho.'
                );
            }

            $cart[$key]['quantity'] = $newQuantity;

        } else {

            $cart[$key] = [
                'variant_id' => $variant->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => $product->price,
                'image' => $product->image,
                'size' => $variant->size,
                'color' => $variant->color,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        if ($request->input('buy_now') == '1') {
            return redirect()
                ->route('checkout.index')
                ->with(
                    'success',
                    'Đã chọn sản phẩm! Vui lòng hoàn tất thông tin thanh toán.'
                );
        }

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Đã thêm sản phẩm vào giỏ hàng.'
            );
    }


    // Cập nhật số lượng
    public function update(
        Request $request,
        $variantId
    ) {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($variantId);

        if ($validated['quantity'] > $variant->quantity) {
            return back()->with(
                'error',
                'Số lượng vượt quá tồn kho.'
            );
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {

            $cart[$variantId]['quantity'] =
                $validated['quantity'];

            session()->put('cart', $cart);
        }

        return back()->with(
            'success',
            'Đã cập nhật giỏ hàng.'
        );
    }


    // Xóa một sản phẩm
    public function remove($variantId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {

            unset($cart[$variantId]);

            session()->put('cart', $cart);
        }

        return back()->with(
            'success',
            'Đã xóa sản phẩm khỏi giỏ hàng.'
        );
    }


    // Xóa toàn bộ giỏ
    public function clear()
    {
        session()->forget('cart');

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Đã xóa toàn bộ giỏ hàng.'
            );
    }
}