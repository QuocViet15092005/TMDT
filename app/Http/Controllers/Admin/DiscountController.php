<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // Danh sách các mã giảm giá
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);

        return view('admin.discounts.index', compact('discounts'));
    }

    // Form tạo mã giảm giá
    public function create()
    {
        return view('admin.discounts.create');
    }

    // Tạo mã giảm giá
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code|max:50',
            'description' => 'nullable|string|max:500',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        Discount::create($validated);

        return redirect()
            ->route('admin.discounts.index')
            ->with('success', 'Tạo mã giảm giá thành công.');
    }

    // Form chỉnh sửa mã giảm giá
    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    // Cập nhật mã giảm giá
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $discount->id . '|max:50',
            'description' => 'nullable|string|max:500',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $discount->update($validated);

        return redirect()
            ->route('admin.discounts.index')
            ->with('success', 'Cập nhật mã giảm giá thành công.');
    }

    // Xóa mã giảm giá
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()
            ->route('admin.discounts.index')
            ->with('success', 'Xóa mã giảm giá thành công.');
    }
}
