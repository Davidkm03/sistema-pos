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
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_name', 255);
            $table->string('business_logo')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_phone', 50)->nullable();
            $table->string('business_email', 255)->nullable();
            $table->string('business_tax_id', 100)->nullable();
            $table->text('receipt_footer')->nullable();
            $table->string('primary_color', 7)->default('#3B82F6');
            $table->string('secondary_color', 7)->default('#10B981');
            $table->string('timezone', 50)->default('America/Mexico_City');
            $table->string('currency', 3)->default('MXN');
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
