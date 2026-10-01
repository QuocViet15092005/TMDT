<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. ADMIN
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            [
                'email' => 'admin@sportshop.com',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 2. CATEGORY
        |--------------------------------------------------------------------------
        */

        $shoes = Category::updateOrCreate(
            [
                'name' => 'Giày bóng đá',
            ],
            [
                'description' =>
                    'Các loại giày đá bóng Nike, Adidas, Puma...',
            ]
        );

        $clothes = Category::updateOrCreate(
            [
                'name' => 'Quần áo bóng đá',
            ],
            [
                'description' =>
                    'Áo đấu câu lạc bộ, đội tuyển và quần áo tập luyện.',
            ]
        );

        $accessories = Category::updateOrCreate(
            [
                'name' => 'Phụ kiện bóng đá',
            ],
            [
                'description' =>
                    'Bóng, tất, găng tay và các phụ kiện bóng đá.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 3. PRODUCT - GIÀY
        |--------------------------------------------------------------------------
        */

        $mercurial = Product::updateOrCreate(
            [
                'name' => 'Nike Mercurial Vapor 16',
            ],
            [
                'category_id' => $shoes->id,
                'brand' => 'Nike',
                'price' => 2500000,
                'image' => 'nike-mercurial-vapor-16.jpg',
                'description' =>
                    'Giày bóng đá Nike Mercurial Vapor 16 dành cho sân cỏ nhân tạo.',
                'is_active' => true,
            ]
        );

        $predator = Product::updateOrCreate(
            [
                'name' => 'Adidas Predator Elite',
            ],
            [
                'category_id' => $shoes->id,
                'brand' => 'Adidas',
                'price' => 2800000,
                'image' => 'adidas-predator-elite.jpg',
                'description' =>
                    'Giày bóng đá Adidas Predator Elite với khả năng kiểm soát bóng tốt.',
                'is_active' => true,
            ]
        );

        $future = Product::updateOrCreate(
            [
                'name' => 'Puma Future 7',
            ],
            [
                'category_id' => $shoes->id,
                'brand' => 'Puma',
                'price' => 1900000,
                'image' => 'puma-future-7.jpg',
                'description' =>
                    'Giày bóng đá Puma Future 7 thiết kế ôm chân.',
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 4. PRODUCT - QUẦN ÁO
        |--------------------------------------------------------------------------
        */

        $realMadrid = Product::updateOrCreate(
            [
                'name' => 'Áo Real Madrid Home',
            ],
            [
                'category_id' => $clothes->id,
                'brand' => 'Adidas',
                'price' => 850000,
                'image' => 'real-madrid-home.jpg',
                'description' =>
                    'Áo đấu sân nhà Real Madrid.',
                'is_active' => true,
            ]
        );

        $barcelona = Product::updateOrCreate(
            [
                'name' => 'Áo Barcelona Home',
            ],
            [
                'category_id' => $clothes->id,
                'brand' => 'Nike',
                'price' => 850000,
                'image' => 'barcelona-home.jpg',
                'description' =>
                    'Áo đấu sân nhà Barcelona.',
                'is_active' => true,
            ]
        );

        $argentina = Product::updateOrCreate(
            [
                'name' => 'Áo Argentina Home',
            ],
            [
                'category_id' => $clothes->id,
                'brand' => 'Adidas',
                'price' => 750000,
                'image' => 'argentina-home.jpg',
                'description' =>
                    'Áo đội tuyển Argentina.',
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 5. PRODUCT - PHỤ KIỆN
        |--------------------------------------------------------------------------
        */

        $ball = Product::updateOrCreate(
            [
                'name' => 'Bóng Adidas League',
            ],
            [
                'category_id' => $accessories->id,
                'brand' => 'Adidas',
                'price' => 650000,
                'image' => 'adidas-league-ball.jpg',
                'description' =>
                    'Bóng đá Adidas League tiêu chuẩn thi đấu.',
                'is_active' => true,
            ]
        );

        $socks = Product::updateOrCreate(
            [
                'name' => 'Tất bóng đá Nike Grip',
            ],
            [
                'category_id' => $accessories->id,
                'brand' => 'Nike',
                'price' => 180000,
                'image' => 'nike-grip-socks.jpg',
                'description' =>
                    'Tất chống trượt dành cho bóng đá.',
                'is_active' => true,
            ]
        );

        $gloves = Product::updateOrCreate(
            [
                'name' => 'Găng tay thủ môn Adidas',
            ],
            [
                'category_id' => $accessories->id,
                'brand' => 'Adidas',
                'price' => 550000,
                'image' => 'adidas-goalkeeper-gloves.jpg',
                'description' =>
                    'Găng tay thủ môn Adidas có độ bám tốt.',
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 6. PRODUCT VARIANT - GIÀY
        |--------------------------------------------------------------------------
        */

        // Nike Mercurial Vapor 16: Màu Xanh dương cho tất cả các size
        $this->createVariant(
            $mercurial->id,
            '40',
            'Xanh dương',
            5
        );

        $this->createVariant(
            $mercurial->id,
            '41',
            'Xanh dương',
            8
        );

        $this->createVariant(
            $mercurial->id,
            '42',
            'Xanh dương',
            6
        );

        $this->createVariant(
            $mercurial->id,
            '43',
            'Xanh dương',
            4
        );


        // Adidas Predator Elite: Màu Đen cho tất cả các size
        $this->createVariant(
            $predator->id,
            '40',
            'Đen',
            5
        );

        $this->createVariant(
            $predator->id,
            '41',
            'Đen',
            7
        );

        $this->createVariant(
            $predator->id,
            '42',
            'Đen',
            6
        );

        $this->createVariant(
            $predator->id,
            '43',
            'Đen',
            3
        );


        // Puma Future 7: Màu Xanh cho tất cả các size
        $this->createVariant(
            $future->id,
            '40',
            'Xanh',
            7
        );

        $this->createVariant(
            $future->id,
            '41',
            'Xanh',
            8
        );

        $this->createVariant(
            $future->id,
            '42',
            'Xanh',
            5
        );


        /*
        |--------------------------------------------------------------------------
        | 7. PRODUCT VARIANT - ÁO
        |--------------------------------------------------------------------------
        */

        $this->createVariant(
            $realMadrid->id,
            'M',
            'Trắng',
            15
        );

        $this->createVariant(
            $realMadrid->id,
            'L',
            'Trắng',
            12
        );

        $this->createVariant(
            $realMadrid->id,
            'XL',
            'Trắng',
            8
        );


        $this->createVariant(
            $barcelona->id,
            'M',
            'Xanh đỏ',
            10
        );

        $this->createVariant(
            $barcelona->id,
            'L',
            'Xanh đỏ',
            12
        );

        $this->createVariant(
            $barcelona->id,
            'XL',
            'Xanh đỏ',
            7
        );


        $this->createVariant(
            $argentina->id,
            'M',
            'Trắng xanh',
            10
        );

        $this->createVariant(
            $argentina->id,
            'L',
            'Trắng xanh',
            10
        );

        $this->createVariant(
            $argentina->id,
            'XL',
            'Trắng xanh',
            5
        );


        /*
        |--------------------------------------------------------------------------
        | 8. PRODUCT VARIANT - PHỤ KIỆN
        |--------------------------------------------------------------------------
        */

        $this->createVariant(
            $ball->id,
            null,
            'Trắng',
            20
        );

        $this->createVariant(
            $socks->id,
            'Freesize',
            'Đen',
            30
        );

        $this->createVariant(
            $gloves->id,
            'M',
            'Đen',
            8
        );

        $this->createVariant(
            $gloves->id,
            'L',
            'Đen',
            8
        );

        /*
        |--------------------------------------------------------------------------
        | 9. SAMPLE CUSTOMER & DISCOUNT
        |--------------------------------------------------------------------------
        */

        $customer = User::updateOrCreate(
            [
                'email' => 'customer@sportshop.com',
            ],
            [
                'name' => 'Nguyễn Văn A',
                'password' => Hash::make('123456'),
                'role' => 'customer',
            ]
        );

        \App\Models\Discount::updateOrCreate(
            ['code' => 'SPORTSHOP10'],
            [
                'name' => 'Giảm giá 10% đơn từ 500k',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase_amount' => 500000,
                'max_discount_amount' => 200000,
                'usage_limit' => 100,
                'used_count' => 1,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 10. SAMPLE COMPLETED ORDER & REVIEW
        |--------------------------------------------------------------------------
        */

        $sampleOrder = \App\Models\Order::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => $customer->id,
                'customer_name' => 'Nguyễn Văn A',
                'customer_phone' => '0987654321',
                'customer_email' => 'customer@sportshop.com',
                'shipping_address' => 'Số 123 Đường Cầu Giấy, Hà Nội',
                'total_amount' => 3350000,
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'order_status' => 'completed',
            ]
        );

        $mercurialVariant = ProductVariant::where('product_id', $mercurial->id)->first();
        if ($mercurialVariant) {
            \App\Models\OrderDetail::updateOrCreate(
                [
                    'order_id' => $sampleOrder->id,
                    'product_id' => $mercurial->id,
                    'product_variant_id' => $mercurialVariant->id,
                ],
                [
                    'product_name' => $mercurial->name,
                    'size' => $mercurialVariant->size,
                    'color' => $mercurialVariant->color,
                    'price' => $mercurial->price,
                    'quantity' => 2,
                    'subtotal' => $mercurial->price * 2,
                ]
            );
        }

        $realMadridVariant = ProductVariant::where('product_id', $realMadrid->id)->first();
        if ($realMadridVariant) {
            \App\Models\OrderDetail::updateOrCreate(
                [
                    'order_id' => $sampleOrder->id,
                    'product_id' => $realMadrid->id,
                    'product_variant_id' => $realMadridVariant->id,
                ],
                [
                    'product_name' => $realMadrid->name,
                    'size' => $realMadridVariant->size,
                    'color' => $realMadridVariant->color,
                    'price' => $realMadrid->price,
                    'quantity' => 1,
                    'subtotal' => $realMadrid->price,
                ]
            );
        }

        \App\Models\Review::updateOrCreate(
            [
                'user_id' => $customer->id,
                'product_id' => $mercurial->id,
            ],
            [
                'rating' => 5,
                'comment' => 'Giày đi rất ôm chân, bám sân nhân tạo tốt và chất lượng da mềm mại!',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HÀM TẠO VARIANT
    |--------------------------------------------------------------------------
    */

    private function createVariant(
        int $productId,
        ?string $size,
        ?string $color,
        int $quantity
    ): void
    {
        ProductVariant::updateOrCreate(
            [
                'product_id' => $productId,
                'size' => $size,
                'color' => $color,
            ],
            [
                'quantity' => $quantity,
            ]
        );
    }
}