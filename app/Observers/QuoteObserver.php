<?php

namespace App\Observers;

use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class QuoteObserver
{
    /**
     * Handle the Quote "creating" event.
     * Asignar automáticamente el empresa_id del usuario autenticado.
     */
    public function creating(Quote $quote): void
    {
        if (Auth::check() && !$quote->empresa_id) {
            $quote->empresa_id = Auth::user()->empresa_id;
        }
    }
}
