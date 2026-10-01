<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\BrowsingHistory;
use Illuminate\Database\Eloquent\Collection;

class ProductRecommendationService
{
    /**
     * Gợi ý sản phẩm dựa trên lịch sử duyệt và mua hàng
     */
    public function getRecommendations(User $user, $limit = 8): Collection
    {
        // Lấy danh mục từ lịch sử duyệt
        $browsingCategories = BrowsingHistory::where('user_id', $user->id)
            ->with('product.category')
            ->latest()
            ->take(20)
            ->get()
            ->pluck('product.category_id')
            ->unique()
            ->toArray();

        // Lấy danh mục từ đơn hàng
        $purchasedCategories = $user->orders()
            ->with('details.product.category')
            ->get()
            ->pluck('details.*.product.category_id')
            ->flatten()
            ->unique()
            ->toArray();

        $categories = array_merge($browsingCategories, $purchasedCategories);

        if (empty($categories)) {
            // Nếu không có lịch sử, lấy sản phẩm phổ biến nhất
            return Product::getTopSelling($limit);
        }

        // Lấy sản phẩm từ các danh mục liên quan
        return Product::whereIn('category_id', $categories)
            ->where('is_active', true)
            ->whereNotIn('id', $user->orders()
                ->with('details')
                ->get()
                ->pluck('details.*.product_id')
                ->flatten()
                ->toArray()
            )
            ->with(['category', 'variants', 'reviews'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Gợi ý sản phẩm tương tự
     */
    public function getSimilarProducts(Product $product, $limit = 5): Collection
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['category', 'variants', 'reviews'])
            ->limit($limit)
            ->get();
    }

    /**
     * Ghi lại lịch sử duyệt
     */
    public function recordBrowse(User $user, Product $product): void
    {
        BrowsingHistory::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
