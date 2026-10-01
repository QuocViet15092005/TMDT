<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    // Lấy danh sách sản phẩm
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'variants', 'reviews']);

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Sắp xếp
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    // Lấy chi tiết sản phẩm
    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không còn kinh doanh.',
            ], 404);
        }

        $product->load(['category', 'variants', 'reviews.user']);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    // Lấy sản phẩm bán chạy
    public function topSelling(Request $request)
    {
        $limit = $request->get('limit', 5);
        $products = Product::getTopSelling($limit);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    // Lấy danh mục sản phẩm
    public function categories()
    {
        $categories = \App\Models\Category::with('products')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
