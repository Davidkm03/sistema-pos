<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\InventoryMovement;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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

    /**
     * Handle the Product "updated" event.
     * Verificar stock bajo y crear notificaciones.
     */
    public function updated(Product $product): void
    {
        // Verificar si el stock cambió y está por debajo del mínimo
        if ($product->wasChanged('stock') && $product->stock <= $product->min_stock) {
            try {
                $this->notificationService->notifyLowStock($product);
            } catch (\Exception $e) {
                // Log error but don't break the update
                Log::error('Error creating low stock notification: ' . $e->getMessage());
            }
        }
    }
}
