<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     * Asignar automáticamente el empresa_id del usuario autenticado.
     */
    public function creating(Product $product): void
    {
        if (Auth::check() && !$product->empresa_id) {
            $product->empresa_id = Auth::user()->empresa_id;
        }
    }

    /**
     * Handle the Product "created" event.
     * Registrar entrada inicial de inventario si tiene stock.
     */
    public function created(Product $product): void
    {
        // Si el producto se creó con stock inicial, registrar como entrada
        if ($product->stock > 0 && Auth::check()) {
            InventoryMovement::create([
                'empresa_id' => $product->empresa_id,
                'product_id' => $product->id,
                'type' => 'entrada',
                'quantity' => $product->stock,
                'reason' => 'Stock inicial al crear producto',
                'user_id' => Auth::id(),
            ]);
        }
    }
}
