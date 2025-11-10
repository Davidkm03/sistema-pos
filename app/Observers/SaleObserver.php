<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Auth;

class SaleObserver
{
    /**
     * Handle the Sale "creating" event.
     * Asignar automáticamente el empresa_id del usuario autenticado.
     */
    public function creating(Sale $sale): void
    {
        if (Auth::check() && !$sale->empresa_id) {
            $sale->empresa_id = Auth::user()->empresa_id;
        }
    }

    /**
     * Handle the Sale "created" event.
     * Registrar salidas de inventario automáticamente para cada producto vendido.
     */
    public function created(Sale $sale): void
    {
        // Solo registrar movimientos para ventas completadas
        if ($sale->status === 'completada' && Auth::check()) {
            // Cargar los items de la venta
            $sale->load('saleItems');

            foreach ($sale->saleItems as $item) {
                InventoryMovement::create([
                    'empresa_id' => $sale->empresa_id,
                    'product_id' => $item->product_id,
                    'type' => 'salida',
                    'quantity' => $item->quantity,
                    'reason' => 'Venta #' . $sale->id . ' - ' . ($sale->document_type === 'receipt' ? 'Recibo #' . $sale->receipt_number : 'Factura #' . $sale->invoice_number),
                    'user_id' => $sale->user_id,
                ]);
            }
        }
    }
}
