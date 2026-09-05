<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Hiển thị danh sách yêu thích của người dùng
    public function index()
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with(['product.category', 'product.variants'])
            ->latest()
            ->paginate(12);

        return view('wishlists.index', compact('wishlists'));
    }

    // Thêm sản phẩm vào danh sách yêu thích
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Đã thêm vào danh sách yêu thích.',
                'in_wishlist' => true,
            ]);
        }

        return back()->with('success', 'Đã thêm vào danh sách yêu thích.');
    }

    // Xóa sản phẩm khỏi danh sách yêu thích
    public function remove(Product $product)
    {
        auth()->user()
            ->wishlists()
            ->where('product_id', $product->id)
            ->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Đã xóa khỏi danh sách yêu thích.',
                'in_wishlist' => false,
            ]);
        }

        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }

    // Kiểm tra sản phẩm có trong wishlist không
    public function check(Product $product)
    {
        $inWishlist = Wishlist::where([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ])->exists();

        return response()->json([
            'in_wishlist' => $inWishlist,
        ]);
    }
}
