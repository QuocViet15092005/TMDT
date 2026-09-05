<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Size giày / quần áo: 40, 41, 42, S, M, L...
            $table->string('size', 50)->nullable();

            // Màu sắc: Đỏ, Trắng, Đen...
            $table->string('color', 100)->nullable();

            // Tồn kho của từng biến thể
            $table->unsignedInteger('quantity')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};