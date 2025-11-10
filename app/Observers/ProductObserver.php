<?php

namespace App\Observers;

use App\Models\Product;
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
}
