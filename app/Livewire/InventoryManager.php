<?php

namespace App\Livewire;

use App\Models\InventoryMovement;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryManager extends Component
{
    use WithPagination;

    // Public properties for form
    public $product_id;
    public $type = 'entrada';
    public $quantity;
    public $reason;

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules
     */
    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'type' => 'required|in:entrada,salida,ajuste',
        'quantity' => 'required|integer|min:1',
        'reason' => 'nullable|string|max:500',
    ];

    /**
     * Save inventory movement
     */
    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create the inventory movement
            InventoryMovement::create([
                'product_id' => $this->product_id,
                'type' => $this->type,
                'quantity' => $this->quantity,
                'reason' => $this->reason,
                'user_id' => Auth::id(),
            ]);

            // Update product stock based on movement type
            $product = Product::findOrFail($this->product_id);
            
            switch ($this->type) {
                case 'entrada':
                    $product->increment('stock', $this->quantity);
                    break;
                case 'salida':
                    if ($product->stock < $this->quantity) {
                        throw new \Exception('Stock insuficiente para realizar esta salida');
                    }
                    $product->decrement('stock', $this->quantity);
                    break;
                case 'ajuste':
                    // For adjustments, set the stock to the new quantity
                    $product->update(['stock' => $this->quantity]);
                    break;
            }

            DB::commit();

            // Dispatch success event
            $this->dispatch('movement-saved', message: 'Movimiento de inventario registrado exitosamente');
            
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Dispatch error event
            $this->dispatch('movement-error', message: $e->getMessage());
        }
    }

    /**
     * Reset form - clear all properties and validations
     */
    public function resetForm()
    {
        $this->reset([
            'product_id',
            'type',
            'quantity',
            'reason'
        ]);
        
        $this->type = 'entrada'; // Reset to default
        $this->resetValidation();
    }

    /**
     * Render the component
     */
    public function render()
    {
        // Get all products for the dropdown
        $products = Product::orderBy('name')->get();

        // Get inventory movements with related data, paginated
        $movements = InventoryMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.inventory-manager', [
            'products' => $products,
            'movements' => $movements,
        ]);
    }
}
