<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
            'category',
            'variants'
        ])->where('is_active', true);

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where(
                'category_id',
                $request->category
            );
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand')) {
            $query->where(
                'brand',
                $request->brand
            );
        }

        // Sắp xếp
        if ($request->sort === 'price_asc') {

            $query->orderBy('price', 'asc');

        } elseif ($request->sort === 'price_desc') {

            $query->orderBy('price', 'desc');

        } else {

            $query->latest();
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();

        return view(
            'products.index',
            compact(
                'products',
                'categories'
            )
        );
    }


    public function show(Product $product)
    {
        $product->load([
            'category',
            'variants',
            'reviews.user'
        ]);

        abort_unless(
            $product->is_active,
            404
        );

        return view(
            'products.show',
            compact('product')
        );
    }
}