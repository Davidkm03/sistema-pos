<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Solo ejecutar en MySQL - SQLite no soporta MODIFY COLUMN y no usa ENUMs
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sales MODIFY COLUMN payment_method ENUM('efectivo', 'tarjeta', 'tarjeta_debito', 'tarjeta_credito', 'transferencia') NOT NULL DEFAULT 'efectivo'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al ENUM original solo en MySQL
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sales MODIFY COLUMN payment_method ENUM('efectivo', 'tarjeta') NOT NULL");
        }
    }
};
