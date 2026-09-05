<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('discount_id')
                ->nullable()
                ->constrained('discounts')
                ->nullOnDelete()
                ->after('payment_method');
            
            $table->decimal('discount_amount', 15, 2)
                ->default(0)
                ->after('discount_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignIdFor('Discount');
            $table->dropColumn('discount_id');
            $table->dropColumn('discount_amount');
        });
    }
};
