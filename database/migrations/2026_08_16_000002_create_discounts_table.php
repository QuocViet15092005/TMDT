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
            
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            $table->enum('discount_type', ['percentage', 'fixed'])
                ->default('percentage');
            
            $table->decimal('discount_value', 10, 2);
            
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            
            $table->decimal('min_purchase_amount', 15, 2)
                ->default(0);
            
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
