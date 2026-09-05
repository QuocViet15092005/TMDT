<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrowsingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Lấy lịch sử duyệt gần nhất
    public static function getRecentHistory($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->with('product.category')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
