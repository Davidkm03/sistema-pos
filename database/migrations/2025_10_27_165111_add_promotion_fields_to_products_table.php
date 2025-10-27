<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('image');
            $table->boolean('is_on_sale')->default(false)->after('is_featured');
            $table->decimal('sale_price', 10, 2)->nullable()->after('is_on_sale');
            $table->integer('discount_percentage')->nullable()->after('sale_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_on_sale', 'sale_price', 'discount_percentage']);
        });
    }
};
