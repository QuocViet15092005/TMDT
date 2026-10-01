<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ TRANG CHECKOUT
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Giỏ hàng đang trống.'
                );
        }

        $total = 0;

        foreach ($cart as $item) {
            $total +=
                ($item['price'] ?? 0)
                * ($item['quantity'] ?? 0);
        }

        return view(
            'checkout.index',
            compact('cart', 'total')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TẠO ĐƠN HÀNG
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' =>
                'required|string|max:255',

            'customer_phone' =>
                'required|string|max:20',

            'customer_email' =>
                'nullable|email|max:255',

            'shipping_address' =>
                'required|string|max:500',

            'payment_method' =>
                'required|in:cod,qr',

            'discount_code' =>
                'nullable|string|max:50',
        ]);

        if (!auth()->check() && $validated['payment_method'] === 'qr') {
            throw ValidationException::withMessages([
                'payment_method' => 'Phương thức thanh toán QR Code yêu cầu đăng nhập tài khoản. Vui lòng đăng nhập hoặc chọn thanh toán khi nhận hàng (COD).'
            ]);
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Giỏ hàng đang trống.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        |
        | Nếu bất kỳ bước nào lỗi:
        | - không tạo Order
        | - không tạo OrderDetail
        | - không trừ tồn kho
        |
        */

        $order = DB::transaction(
            function () use ($validated, $cart) {

                $total = 0;

                $orderItems = [];


                /*
                |--------------------------------------------------------------------------
                | KIỂM TRA LẠI GIỎ HÀNG TỪ DATABASE
                |--------------------------------------------------------------------------
                */

                foreach ($cart as $item) {

                    if (!isset($item['variant_id'])) {
                        throw ValidationException::withMessages([
                            'cart' =>
                                'Giỏ hàng chứa sản phẩm không hợp lệ.'
                        ]);
                    }

                    /*
                     * lockForUpdate()
                     * khóa variant trong lúc đặt hàng
                     * để hạn chế bán vượt tồn kho.
                     */
                    $variant = ProductVariant::with('product')
                        ->whereKey($item['variant_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$variant || !$variant->product) {
                        throw ValidationException::withMessages([
                            'cart' =>
                                'Một sản phẩm trong giỏ không còn tồn tại.'
                        ]);
                    }

                    $product = $variant->product;

                    if (!$product->is_active) {
                        throw ValidationException::withMessages([
                            'cart' =>
                                $product->name .
                                ' hiện không còn kinh doanh.'
                        ]);
                    }

                    $quantity =
                        (int) ($item['quantity'] ?? 0);

                    if ($quantity <= 0) {
                        throw ValidationException::withMessages([
                            'cart' =>
                                'Số lượng sản phẩm không hợp lệ.'
                        ]);
                    }

                    /*
                     * Kiểm tra tồn kho thực tế.
                     */
                    if ($quantity > $variant->quantity) {
                        throw ValidationException::withMessages([
                            'cart' =>
                                'Sản phẩm ' .
                                $product->name .
                                ' - Size ' .
                                ($variant->size ?? 'Không có') .
                                ' - Màu ' .
                                ($variant->color ?? 'Không có') .
                                ' không đủ số lượng trong kho.'
                        ]);
                    }


                    /*
                     * KHÔNG lấy giá do trình duyệt gửi lên.
                     * Lấy giá mới nhất từ Database.
                     */
                    $price = (float) $product->price;

                    $subtotal =
                        $price * $quantity;

                    $total += $subtotal;


                    /*
                     * Chuẩn bị dữ liệu để tạo OrderDetail.
                     */
                    $orderItems[] = [
                        'variant' => $variant,
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }


                /*
                |--------------------------------------------------------------------------
                | KIỂM TRA DISCOUNT
                |--------------------------------------------------------------------------
                */

                $discount = null;
                $discountAmount = 0;

                if (!empty($validated['discount_code'])) {
                    $discount = \App\Models\Discount::where('code', strtoupper($validated['discount_code']))
                        ->first();

                    if (!$discount || !$discount->isValid()) {
                        throw ValidationException::withMessages([
                            'discount_code' =>
                                'Mã giảm giá không hợp lệ hoặc đã hết hiệu lực.'
                        ]);
                    }

                    if ($total < $discount->min_purchase_amount) {
                        throw ValidationException::withMessages([
                            'discount_code' =>
                                'Đơn hàng phải từ ' . number_format($discount->min_purchase_amount, 0, ',', '.') . ' đ trở lên.'
                        ]);
                    }

                    $discountAmount = $discount->calculateDiscount($total);
                }

                $finalTotal = $total - $discountAmount;


                /*
                |--------------------------------------------------------------------------
                | TẠO ORDER
                |--------------------------------------------------------------------------
                */

                $order = Order::create([
                    'user_id' =>
                        auth()->id(),

                    'customer_name' =>
                        $validated['customer_name'],

                    'customer_phone' =>
                        $validated['customer_phone'],

                    'customer_email' =>
                        $validated['customer_email'] ?? null,

                    'shipping_address' =>
                        $validated['shipping_address'],

                    'total_amount' =>
                        $finalTotal,

                    'payment_method' =>
                        $validated['payment_method'],

                    'discount_id' =>
                        $discount?->id,

                    'discount_amount' =>
                        $discountAmount,

                    /*
                     * COD và QR đều bắt đầu là chưa thanh toán.
                     * QR sau khi xác nhận mới chuyển thành paid.
                     */
                    'payment_status' =>
                        'unpaid',

                    'order_status' =>
                        'pending',
                ]);


                /*
                |--------------------------------------------------------------------------
                | TẠO ORDER DETAILS + TRỪ TỒN KHO
                |--------------------------------------------------------------------------
                */

                foreach ($orderItems as $item) {

                    $variant =
                        $item['variant'];

                    $product =
                        $item['product'];

                    $quantity =
                        $item['quantity'];


                    OrderDetail::create([
                        'order_id' =>
                            $order->id,

                        'product_id' =>
                            $product->id,

                        'product_variant_id' =>
                            $variant->id,

                        /*
                         * Snapshot thông tin sản phẩm.
                         */
                        'product_name' =>
                            $product->name,

                        'size' =>
                            $variant->size,

                        'color' =>
                            $variant->color,

                        'price' =>
                            $item['price'],

                        'quantity' =>
                            $quantity,

                        'subtotal' =>
                            $item['subtotal'],
                    ]);


                    /*
                     * Trừ tồn kho của đúng variant.
                     */
                    $variant->decrement(
                        'quantity',
                        $quantity
                    );
                }

                // Tăng used count của discount
                if ($discount) {
                    $discount->increment('used_count');
                }

                return $order;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | XÓA GIỎ HÀNG & LƯU PHIÊN ĐẶT HÀNG
        |--------------------------------------------------------------------------
        */

        session()->forget('cart');
        session()->put('last_order_id', $order->id);


        /*
        |--------------------------------------------------------------------------
        | CHUYỂN ĐẾN THANH TOÁN
        |--------------------------------------------------------------------------
        */

        if (
            $validated['payment_method']
            === 'qr'
        ) {
            return redirect()
                ->route(
                    'payment.qr',
                    $order
                );
        }


        /*
         * COD -> đặt hàng thành công luôn.
         */
        return redirect()
            ->route(
                'checkout.success',
                $order
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ĐẶT HÀNG THÀNH CÔNG (BẢO VỆ ĐƠN HÀNG)
    |--------------------------------------------------------------------------
    */
    public function success(Order $order)
    {
        // Kiểm tra quyền xem thông tin đơn hàng
        $isOwner = auth()->check() && $order->user_id === auth()->id();
        $isSessionOwner = session('last_order_id') == $order->id;
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        abort_unless(
            $isOwner || $isSessionOwner || $isAdmin,
            403,
            'Bạn không có quyền truy cập thông tin đơn hàng này.'
        );

        $order->load([
            'details.product',
            'details.variant'
        ]);

        return view(
            'checkout.success',
            compact('order')
        );
    }
}