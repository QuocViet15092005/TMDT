<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view(
            'admin.products.index',
            compact('products', 'categories')
        );
    }

    public function create()
    {
        $categories = Category::all();

        return view(
            'admin.products.create',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' =>
                'required|exists:categories,id',

            'name' =>
                'required|string|max:255',

            'brand' =>
                'nullable|string|max:100',

            'price' =>
                'required|numeric|min:0',

            'image' =>
                'nullable|string|max:255',

            'image_file' =>
                'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

            'description' =>
                'nullable|string',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $validated['image'] = $filename;
        }

        unset($validated['image_file']);
        $validated['is_active'] = true;

        $product = Product::create($validated);

        // Tạo sẵn một biến thể mặc định nếu có số lượng
        if ($request->filled('initial_quantity') && $request->initial_quantity > 0) {
            $product->variants()->create([
                'size' => $request->input('initial_size', 'Tiêu chuẩn'),
                'color' => $request->input('initial_color', 'Mặc định'),
                'quantity' => $request->initial_quantity,
            ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Thêm sản phẩm thành công.'
            );
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'variants'
        ]);

        return view(
            'admin.products.show',
            compact('product')
        );
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }

    public function update(
        Request $request,
        Product $product
    ) {
        $validated = $request->validate([
            'category_id' =>
                'required|exists:categories,id',

            'name' =>
                'required|string|max:255',

            'brand' =>
                'nullable|string|max:100',

            'price' =>
                'required|numeric|min:0',

            'image' =>
                'nullable|string|max:255',

            'image_file' =>
                'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

            'description' =>
                'nullable|string',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $validated['image'] = $filename;
        }

        unset($validated['image_file']);

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Cập nhật sản phẩm thành công.'
            );
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Xóa sản phẩm thành công.'
            );
    }
}