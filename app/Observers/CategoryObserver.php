<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     * Asignar automáticamente el empresa_id del usuario autenticado.
     */
    public function creating(Category $category): void
    {
        if (Auth::check() && !$category->empresa_id) {
            $category->empresa_id = Auth::user()->empresa_id;
        }
    }
}
