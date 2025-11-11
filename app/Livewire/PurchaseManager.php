<?php

namespace App\Livewire;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class PurchaseManager extends Component
{
    use WithPagination;

    public $supplier_id;
    public $purchase_date;
    public $notes;
    public $status = 'pending';
    
    public $cart = [];
    public $selectedProduct;
    public $productQuantity = 1;
    public $productCost = 0;
    
    // Modal de creación de proveedor
    public $showSupplierModal = false;
    public $newSupplierName = '';
    public $newSupplierPhone = '';
    public $newSupplierEmail = '';
    public $newSupplierAddress = '';
    
    public $editingId = null;
    public $search = '';
    public $statusFilter = 'all';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'supplier_id' => 'required|exists:suppliers,id',
        'purchase_date' => 'required|date',
        'notes' => 'nullable|string|max:500',
        'cart' => 'required|array|min:1',
    ];

    protected $messages = [
        'supplier_id.required' => 'Debe seleccionar un proveedor',
        'supplier_id.exists' => 'El proveedor seleccionado no es válido',
        'purchase_date.required' => 'La fecha de compra es obligatoria',
        'purchase_date.date' => 'La fecha debe ser válida',
        'notes.max' => 'Las notas no pueden exceder 500 caracteres',
        'cart.required' => 'Debe agregar al menos un producto',
        'cart.min' => 'Debe agregar al menos un producto',
    ];

    public function mount()
    {
        $this->purchase_date = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addToCart()
    {
        $this->validate([
            'selectedProduct' => 'required|exists:products,id',
            'productQuantity' => 'required|integer|min:1',
            'productCost' => 'required|numeric|min:0',
        ], [
            'selectedProduct.required' => 'Debe seleccionar un producto',
            'selectedProduct.exists' => 'El producto no es válido',
            'productQuantity.required' => 'La cantidad es obligatoria',
            'productQuantity.integer' => 'La cantidad debe ser un número entero',
            'productQuantity.min' => 'La cantidad debe ser al menos 1',
            'productCost.required' => 'El costo unitario es obligatorio',
            'productCost.numeric' => 'El costo debe ser un valor numérico',
            'productCost.min' => 'El costo debe ser mayor o igual a 0',
        ]);

        $product = Product::find($this->selectedProduct);

        if (isset($this->cart[$this->selectedProduct])) {
            $this->cart[$this->selectedProduct]['quantity'] += $this->productQuantity;
            $this->cart[$this->selectedProduct]['subtotal'] = 
                $this->cart[$this->selectedProduct]['quantity'] * $this->cart[$this->selectedProduct]['unit_cost'];
        } else {
            $this->cart[$this->selectedProduct] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $this->productQuantity,
                'unit_cost' => $this->productCost,
                'subtotal' => $this->productQuantity * $this->productCost,
            ];
        }

        $this->reset(['selectedProduct', 'productQuantity', 'productCost']);
        $this->productQuantity = 1;
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function updateCartQuantity($productId, $quantity)
    {
        if (isset($this->cart[$productId]) && $quantity > 0) {
            $this->cart[$productId]['quantity'] = $quantity;
            $this->cart[$productId]['subtotal'] = $quantity * $this->cart[$productId]['unit_cost'];
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $subtotal = collect($this->cart)->sum('subtotal');
            $tax = 0; // You can calculate tax if needed
            $total = $subtotal + $tax;

            $purchase = Purchase::create([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'notes' => $this->notes,
            ]);

            foreach ($this->cart as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            $this->resetForm();
            $this->dispatch('purchase-created');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('purchase-error', message: $e->getMessage());
        }
    }

    public function receivePurchase($id)
    {
        try {
            DB::beginTransaction();

            $purchase = Purchase::findOrFail($id);
            
            if ($purchase->status !== 'pending') {
                throw new \Exception('Solo se pueden recibir compras pendientes');
            }

            // Update stock for each product
            foreach ($purchase->items as $item) {
                $product = Product::find($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            $purchase->update([
                'status' => 'received',
                'received_at' => now(),
            ]);

            DB::commit();

            $this->dispatch('purchase-received');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('purchase-error', message: $e->getMessage());
        }
    }

    public function cancelPurchase($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            
            if ($purchase->status === 'received') {
                throw new \Exception('No se puede cancelar una compra ya recibida');
            }

            $purchase->update(['status' => 'cancelled']);
            
            $this->dispatch('purchase-cancelled');
        } catch (\Exception $e) {
            $this->dispatch('purchase-error', message: $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            
            if ($purchase->status === 'received') {
                throw new \Exception('No se puede eliminar una compra ya recibida. Debe cancelarla primero.');
            }

            $purchase->delete();
            $this->dispatch('purchase-deleted');
        } catch (\Exception $e) {
            $this->dispatch('purchase-error', message: $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'supplier_id',
            'notes',
            'cart',
            'selectedProduct',
            'productQuantity',
            'productCost',
            'editingId',
        ]);

        $this->purchase_date = date('Y-m-d');
        $this->productQuantity = 1;
        $this->productCost = 0;
    }

    public function openSupplierModal()
    {
        $this->showSupplierModal = true;
        $this->reset(['newSupplierName', 'newSupplierPhone', 'newSupplierEmail', 'newSupplierAddress']);
    }

    public function closeSupplierModal()
    {
        $this->showSupplierModal = false;
        $this->reset(['newSupplierName', 'newSupplierPhone', 'newSupplierEmail', 'newSupplierAddress']);
    }

    public function saveSupplier()
    {
        $this->validate([
            'newSupplierName' => 'required|string|max:255',
            'newSupplierPhone' => 'nullable|string|max:20',
            'newSupplierEmail' => 'nullable|email|max:255',
            'newSupplierAddress' => 'nullable|string|max:500',
        ], [
            'newSupplierName.required' => 'El nombre del proveedor es obligatorio',
            'newSupplierName.max' => 'El nombre no puede exceder 255 caracteres',
            'newSupplierPhone.max' => 'El teléfono no puede exceder 20 caracteres',
            'newSupplierEmail.email' => 'El correo electrónico no es válido',
            'newSupplierEmail.max' => 'El correo no puede exceder 255 caracteres',
            'newSupplierAddress.max' => 'La dirección no puede exceder 500 caracteres',
        ]);

        try {
            $supplier = Supplier::create([
                'name' => $this->newSupplierName,
                'phone' => $this->newSupplierPhone,
                'email' => $this->newSupplierEmail,
                'address' => $this->newSupplierAddress,
                'is_active' => true,
            ]);

            $this->supplier_id = $supplier->id;
            $this->closeSupplierModal();
            $this->dispatch('supplier-created');
        } catch (\Exception $e) {
            $this->dispatch('supplier-error', message: $e->getMessage());
        }
    }

    public function render()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $purchases = Purchase::query()
            ->with(['supplier', 'items', 'user'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('purchase_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('supplier', function($sq) {
                          $sq->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter !== 'all', function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.purchase-manager', [
            'suppliers' => $suppliers,
            'products' => $products,
            'purchases' => $purchases,
        ]);
    }
}
