<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'price',
        'image',
        'description',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Lấy sản phẩm bán chạy nhất
    public static function getTopSelling($limit = 8)
    {
        return self::with(['category', 'variants'])
            ->where('is_active', true)
            ->withSum(['orderDetails as total_sold' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('order_status', 'completed')
                      ->orWhere('payment_status', 'paid');
                });
            }], 'quantity')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    // Lấy sản phẩm theo danh mục
    public static function getByCategory($categoryId, $limit = 12)
    {
        return self::where('category_id', $categoryId)
            ->where('is_active', true)
            ->with(['category', 'variants', 'reviews'])
            ->paginate($limit);
    }

    // Tính trung bình đánh giá
    public function getAverageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Lấy tổng số lượt bán
    public function getTotalSold()
    {
        return (int) $this->orderDetails()
            ->whereHas('order', function ($q) {
                $q->where('order_status', 'completed')
                  ->orWhere('payment_status', 'paid');
            })
            ->sum('quantity');
    }
}
