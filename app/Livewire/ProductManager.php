<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    // Public properties
    public $name;
    public $category_id;
    public $supplier_id;
    public $price;
    public $cost;
    public $stock;
    public $editingId = null;
    public $search = '';

    // Tax properties
    public $tax_type = 'standard';
    public $custom_tax_rate = null;

    protected $paginationTheme = 'bootstrap';

    /**
     * Mount component - check for edit parameter
     */
    public function mount()
    {
        // Check if there's an 'edit' parameter in the URL
        if (request()->has('edit')) {
            $productId = request()->get('edit');
            $this->edit($productId);
        }
    }

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:200',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'tax_type' => 'nullable|in:standard,exempt,excluded,custom',
            'custom_tax_rate' => 'nullable|numeric|min:0|max:100',
        ];

        return $rules;
    }

    /**
     * Generate automatic SKU for the current empresa
     */
    private function generateSku()
    {
        $empresaId = auth()->user()->empresa_id;
        
        // Get the last product SKU for this empresa
        $lastProduct = Product::where('empresa_id', $empresaId)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastProduct && preg_match('/EMP' . $empresaId . '-(\d+)/', $lastProduct->sku, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'EMP' . $empresaId . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Save product (create or update)
     */
    public function save()
    {
        // Check permissions
        if ($this->editingId) {
            if (!auth()->user()->can('edit-products')) {
                $this->dispatch('product-error', message: 'No tienes permiso para editar productos');
                return;
            }
        } else {
            if (!auth()->user()->can('create-products')) {
                $this->dispatch('product-error', message: 'No tienes permiso para crear productos');
                return;
            }
        }

        // Validate the data
        $validatedData = $this->validate();

        if ($this->editingId) {
            // Update existing product
            $product = Product::find($this->editingId);
            $product->update($validatedData);
            
            // Dispatch event for SweetAlert2
            $this->dispatch('product-saved', message: 'Producto actualizado exitosamente', isEdit: true);
        } else {
            // Generate automatic SKU for new product
            $validatedData['sku'] = $this->generateSku();
            
            // Create new product
            Product::create($validatedData);
            
            // Dispatch event for SweetAlert2
            $this->dispatch('product-saved', message: 'Producto creado exitosamente', isEdit: false);
        }

        $this->resetForm();
    }

    /**
     * Save and create another - for quick bulk entry
     */
    public function saveAndCreateAnother()
    {
        // Check permissions
        if (!auth()->user()->can('create-products')) {
            $this->dispatch('product-error', message: 'No tienes permiso para crear productos');
            return;
        }

        // Validate the data
        $validatedData = $this->validate();

        // Generate automatic SKU
        $validatedData['sku'] = $this->generateSku();
        
        // Create new product
        Product::create($validatedData);
        
        // Dispatch event for SweetAlert2 (quick notification)
        $this->dispatch('product-created-quick', message: 'Producto creado! Listo para el siguiente');

        // Reset form but keep category selected for faster entry
        $keepCategory = $this->category_id;
        $this->resetForm();
        $this->category_id = $keepCategory;
    }

    /**
     * Edit product - load data into form
     */
    public function edit($id)
    {
        // Check permission
        if (!auth()->user()->can('edit-products')) {
            $this->dispatch('product-error', message: 'No tienes permiso para editar productos');
            return;
        }

        $product = Product::find($id);

        if ($product) {
            $this->editingId = $product->id;
            $this->name = $product->name;
            $this->category_id = $product->category_id;
            $this->supplier_id = $product->supplier_id;
            $this->price = $product->price;
            $this->cost = $product->cost;
            $this->stock = $product->stock;
            $this->tax_type = $product->tax_type ?? 'standard';
            $this->custom_tax_rate = $product->tax_rate;
        }
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        // Check permission
        if (!auth()->user()->can('delete-products')) {
            $this->dispatch('product-error', message: 'No tienes permiso para eliminar productos');
            return;
        }

        $product = Product::find($id);
        
        if ($product) {
            $product->delete();
            
            // Dispatch event for SweetAlert2
            $this->dispatch('product-deleted');
        }
    }

    /**
     * Reset form - clear all properties and validations
     */
    public function resetForm()
    {
        $this->reset([
            'name',
            'category_id',
            'supplier_id',
            'price',
            'cost',
            'stock',
            'editingId',
            'tax_type',
            'custom_tax_rate'
        ]);

        $this->resetValidation();
    }

    /**
     * Reset pagination when search changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Render the component
     */
    public function render()
    {
        // Build query for products with eager loading of category and supplier
        $query = Product::with(['category', 'supplier']);

        // Apply search filter if search term exists
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Paginate products (10 per page)
        $products = $query->paginate(10);

        // Get all categories for the form dropdown
        $categories = Category::all();
        
        // Get all active suppliers for the form dropdown
        $suppliers = \App\Models\Supplier::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.product-manager', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }
}
