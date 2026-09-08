<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type', // 'percentage' hoặc 'fixed'
        'discount_value',
        'max_discount_amount',
        'max_uses',
        'user_max_uses',
        'used_count',
        'min_purchase_amount',
        'category_type',
        'starts_at',
        'expires_at',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * Quan hệ với các Đơn hàng (Orders)
     * Thêm phương thức này để sửa lỗi Call to undefined method orders()
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_id');
    }

    // Kiểm tra voucher còn hiệu lực
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        $now = now();
        if ($this->starts_at && $now < $this->starts_at) {
            return false;
        }

        if ($this->expires_at && $now > $this->expires_at) {
            return false;
        }

        return true;
    }

    // Tính tiền giảm giá
    public function calculateDiscount($amount)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($amount < $this->min_purchase_amount) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = ($amount * $this->discount_value) / 100;
            
            // Nếu có thiết lập mức giảm tối đa
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                return $this->max_discount_amount;
            }
            
            return $discount;
        } else {
            return min($this->discount_value, $amount);
        }
    }
}