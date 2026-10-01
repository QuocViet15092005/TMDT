<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistApiController extends Controller
{
    // Danh sách yêu thích
    public function index()
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with('product.category')
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $wishlists,
        ]);
    }

    // Thêm vào wishlist
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích.',
            'data' => $wishlist,
        ]);
    }

    // Xóa khỏi wishlist
    public function remove(Product $product)
    {
        auth()->user()
            ->wishlists()
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích.',
        ]);
    }

    // Kiểm tra sản phẩm trong wishlist
    public function check(Product $product)
    {
        $inWishlist = Wishlist::where([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ])->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
        ]);
    }
}
