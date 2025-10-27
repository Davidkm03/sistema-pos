<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Public properties
    public $name;
    public $category_id;
    public $sku;
    public $price;
    public $cost;
    public $stock;
    public $image;
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
            'sku' => 'required|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:1024',
            'tax_type' => 'nullable|in:standard,exempt,excluded,custom',
            'custom_tax_rate' => 'nullable|numeric|min:0|max:100',
        ];

        // If editing, exclude current product ID from SKU uniqueness validation
        if ($this->editingId) {
            $rules['sku'] = 'required|unique:products,sku,' . $this->editingId;
        }

        return $rules;
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

        // Handle image upload
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        if ($this->editingId) {
            // Update existing product
            $product = Product::find($this->editingId);
            
            // Remove image from validated data if no new image was uploaded
            if (!$this->image) {
                unset($validatedData['image']);
            }
            
            $product->update($validatedData);
            
            // Dispatch event for SweetAlert2
            $this->dispatch('product-saved', message: 'Producto actualizado exitosamente', isEdit: true);
        } else {
            // Create new product
            Product::create($validatedData);
            
            // Dispatch event for SweetAlert2
            $this->dispatch('product-saved', message: 'Producto creado exitosamente', isEdit: false);
        }

        $this->resetForm();
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
            $this->sku = $product->sku;
            $this->price = $product->price;
            $this->cost = $product->cost;
            $this->stock = $product->stock;
            $this->tax_type = $product->tax_type ?? 'standard';
            $this->custom_tax_rate = $product->tax_rate;
            // Note: We don't load the existing image into the file input
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
            // Delete image file if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
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
            'sku',
            'price',
            'cost',
            'stock',
            'image',
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
        // Build query for products with eager loading of category
        $query = Product::with('category');

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

        return view('livewire.product-manager', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
