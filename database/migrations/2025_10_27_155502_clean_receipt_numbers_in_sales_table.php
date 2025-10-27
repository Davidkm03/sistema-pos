<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Clean up receipt_number field by extracting only the numeric part
     * from old formatted values like "VT-000008" -> "8"
     */
    public function up(): void
    {
        // Get all sales with receipt numbers
        $sales = DB::table('sales')->whereNotNull('receipt_number')->get();

        foreach ($sales as $sale) {
            $receiptNumber = $sale->receipt_number;

            // Extract only numbers from the receipt_number
            // Handles formats like "VT-000008", "RV-000001", etc.
            if (preg_match('/(\d+)/', $receiptNumber, $matches)) {
                $numericValue = (int) $matches[1];

                DB::table('sales')
                    ->where('id', $sale->id)
                    ->update(['receipt_number' => $numericValue]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No way to reverse this reliably as we don't know the original prefix
        // If needed, the getFormattedDocumentNumber() method will format it again
    }
};
