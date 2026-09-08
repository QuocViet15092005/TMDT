<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Discount;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);

        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Tính tiền giảm giá từ session nếu có
        $discountData = session()->get('discount', null);
        $discountAmount = 0;

        if ($discountData) {
            $discountAmount = $discountData['amount'];
        }

        $total = max(0, $subtotal - $discountAmount);

        return view('cart.index', compact('cart', 'subtotal', 'discountAmount', 'total'));
    }

    // Áp dụng mã giảm giá
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $discount = Discount::where('code', $code)->first();

        // 1. Kiểm tra tồn tại
        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại!'
            ]);
        }

        // 2. Kiểm tra hiệu lực (ngày bắt đầu, hết hạn, kích hoạt)
        if (!$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết hạn hoặc không có hiệu lực!'
            ]);
        }

        // 3. Tính tổng tiền giỏ hàng
        $cart = session()->get('cart', []);
        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        if ($cartTotal <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng của bạn đang trống!'
            ]);
        }

        // 4. Kiểm tra đơn hàng tối thiểu
        if ($discount->min_purchase_amount && $cartTotal < $discount->min_purchase_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu phải từ ' . number_format($discount->min_purchase_amount, 0, ',', '.') . 'đ để áp dụng mã này!'
            ]);
        }

        // 5. Tính giá trị giảm
        $discountAmount = $discount->calculateDiscount($cartTotal);

        // 6. Lưu thông tin vào Session
        session()->put('discount', [
            'id' => $discount->id,
            'code' => $discount->code,
            'amount' => $discountAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount_amount' => $discountAmount,
            'new_total' => max(0, $cartTotal - $discountAmount)
        ]);
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

        if (!$product->is_active) {
            return back()->with('error', 'Sản phẩm hiện không còn kinh doanh.');
        }

        if ($variant->quantity <= 0) {
            return back()->with('error', 'Biến thể sản phẩm đã hết hàng.');
        }

        $quantity = $validated['quantity'];

        if ($quantity > $variant->quantity) {
            return back()->with('error', 'Số lượng yêu cầu vượt quá tồn kho.');
        }

        $cart = session()->get('cart', []);
        $key = $variant->id;

        if (isset($cart[$key])) {
            $newQuantity = $cart[$key]['quantity'] + $quantity;

            if ($newQuantity > $variant->quantity) {
                return back()->with('error', 'Tổng số lượng trong giỏ vượt quá tồn kho.');
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
                ->with('success', 'Đã chọn sản phẩm! Vui lòng hoàn tất thông tin thanh toán.');
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    // Cập nhật số lượng
    public function update(Request $request, $variantId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($variantId);

        if ($validated['quantity'] > $variant->quantity) {
            return back()->with('error', 'Số lượng vượt quá tồn kho.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);

            // Cập nhật lại số tiền giảm giá nếu giỏ hàng thay đổi
            $this->recalculateDiscount();
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    // Xóa một sản phẩm
    public function remove($variantId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);

            // Cập nhật lại số tiền giảm giá
            $this->recalculateDiscount();
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // Xóa toàn bộ giỏ
    public function clear()
    {
        session()->forget('cart');
        session()->forget('discount');

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }

    // Hỗ trợ tính lại tiền giảm giá khi sửa/xóa giỏ hàng
    private function recalculateDiscount()
    {
        if (session()->has('discount')) {
            $discountData = session()->get('discount');
            $discount = Discount::find($discountData['id']);

            $cart = session()->get('cart', []);
            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }

            if ($discount && $discount->isValid() && $cartTotal >= ($discount->min_purchase_amount ?? 0)) {
                $discountAmount = $discount->calculateDiscount($cartTotal);
                session()->put('discount.amount', $discountAmount);
            } else {
                session()->forget('discount');
            }
        }
    }
}