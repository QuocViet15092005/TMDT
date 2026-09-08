<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type', // 'percentage' hoặc 'fixed'
        'discount_value',
        'max_uses',
        'used_count',
        'min_purchase_amount',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

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
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }

        if ($this->end_date && $now > $this->end_date) {
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
            return ($amount * $this->discount_value) / 100;
        } else {
            return min($this->discount_value, $amount);
        }
    }
}
