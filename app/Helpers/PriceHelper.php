<?php

namespace App\Helpers;

class PriceHelper
{
    /**
     * Định dạng giá thành chuỗi VND
     */
    public static function format(float $price): string
    {
        return number_format($price, 0, ',', '.') . ' đ';
    }

    /**
     * Chuyển đổi giá từ chuỗi sang float
     */
    public static function parse(string $price): float
    {
        return (float) str_replace(['.', ',', ' đ'], '', $price);
    }

    /**
     * Tính giá sau giảm giá (phần trăm)
     */
    public static function applyPercentageDiscount(float $price, float $discountPercent): float
    {
        return $price * (1 - ($discountPercent / 100));
    }

    /**
     * Tính giá sau giảm giá (cố định)
     */
    public static function applyFixedDiscount(float $price, float $discountAmount): float
    {
        return max(0, $price - $discountAmount);
    }

    /**
     * Tính lãi (markup)
     */
    public static function applyMarkup(float $price, float $markupPercent): float
    {
        return $price * (1 + ($markupPercent / 100));
    }

    /**
     * Lấy tiền giảm giá
     */
    public static function calculateDiscount(float $originalPrice, float $discountedPrice): float
    {
        return $originalPrice - $discountedPrice;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public static function calculateDiscountPercent(float $originalPrice, float $discountedPrice): float
    {
        if ($originalPrice == 0) {
            return 0;
        }
        return (($originalPrice - $discountedPrice) / $originalPrice) * 100;
    }
}
