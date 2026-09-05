<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return back()->with(
            'success',
            'Thêm danh mục thành công.'
        );
    }

    public function update(
        Request $request,
        Category $category
    ) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return back()->with(
            'success',
            'Cập nhật danh mục thành công.'
        );
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Danh mục đang có sản phẩm, không thể xóa.'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'Xóa danh mục thành công.'
        );
    }
}