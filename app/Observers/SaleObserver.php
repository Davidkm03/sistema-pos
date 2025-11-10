<?php

namespace App\Observers;

use App\Models\Sale;
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
}
