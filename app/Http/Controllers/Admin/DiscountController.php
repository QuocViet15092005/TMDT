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
            'name'                => 'required|string|max:255',
            'code'                => 'required|string|max:50|unique:discounts,code',
            'description'         => 'nullable|string|max:500',
            'discount_type'       => 'required|in:percentage,fixed',
            'discount_value'      => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses'            => 'nullable|integer|min:1',
            'user_max_uses'       => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'category_type'       => 'nullable|string',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after_or_equal:starts_at',
            'is_active'           => 'required|boolean',
            'is_public'           => 'required|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_purchase_amount'] = $validated['min_purchase_amount'] ?? 0;

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
            'name'                => 'required|string|max:255',
            'code'                => 'required|string|max:50|unique:discounts,code,' . $discount->id,
            'description'         => 'nullable|string|max:500',
            'discount_type'       => 'required|in:percentage,fixed',
            'discount_value'      => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses'            => 'nullable|integer|min:1',
            'user_max_uses'       => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'category_type'       => 'nullable|string',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after_or_equal:starts_at',
            'is_active'           => 'required|boolean',
            'is_public'           => 'required|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_purchase_amount'] = $validated['min_purchase_amount'] ?? 0;

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
    /**
 * Xem chi tiết thông tin voucher
 */
public function show(Discount $discount)
{
    // Lấy số lượt đã sử dụng
    $usedCount = $discount->used_count ?? 0;
    
    // Tính tổng tiền tiết kiệm và số người dùng (nếu có quan hệ orders)
    $totalSavings = method_exists($discount, 'orders') ? ($discount->orders()->sum('discount_amount') ?? 0) : 0;
    $uniqueUsersCount = method_exists($discount, 'orders') ? $discount->orders()->distinct('user_id')->count('user_id') : 0;

    return view('admin.discounts.show', compact('discount', 'usedCount', 'totalSavings', 'uniqueUsersCount'));
}
}