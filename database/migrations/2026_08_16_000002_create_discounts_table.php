<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            
            $table->string('name'); // Tên voucher (Mới bổ sung)
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount_amount', 15, 2)->nullable(); // Giảm tối đa (Mới bổ sung)
            
            $table->integer('max_uses')->nullable();
            $table->integer('user_max_uses')->default(1); // Giới hạn/user (Mới bổ sung)
            $table->integer('used_count')->default(0);
            
            $table->decimal('min_purchase_amount', 15, 2)->default(0);
            $table->string('category_type')->default('all'); // Phạm vi áp dụng (Mới bổ sung)
            
            $table->datetime('starts_at')->nullable(); // Đổi tên đồng bộ với form
            $table->datetime('expires_at')->nullable(); // Đổi tên đồng bộ với form
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true); // Hiển thị công khai (Mới bổ sung)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};