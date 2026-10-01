<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Có thể null vì khách COD không bắt buộc đăng nhập
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('customer_name');

            $table->string(
                'customer_phone',
                20
            );

            $table->string('customer_email')
                ->nullable();

            $table->text('shipping_address');

            $table->decimal(
                'total_amount',
                15,
                2
            )->default(0);

            /*
            |--------------------------------------------------------------------------
            | PHƯƠNG THỨC THANH TOÁN
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'payment_method',
                [
                    'cod',
                    'qr'
                ]
            )->default('cod');


            /*
            |--------------------------------------------------------------------------
            | TRẠNG THÁI THANH TOÁN
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'payment_status',
                [
                    'unpaid',
                    'paid',
                    'failed'
                ]
            )->default('unpaid');


            /*
            |--------------------------------------------------------------------------
            | TRẠNG THÁI ĐƠN HÀNG
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'order_status',
                [
                    'pending',
                    'confirmed',
                    'shipping',
                    'completed',
                    'cancelled'
                ]
            )->default('pending');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};