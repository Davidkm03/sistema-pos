<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\ProductImageAnalyzer;

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
        $empresaId = auth()->user()->empresa_id;
        
        $rules = [
            'name' => 'required|min:3|max:200',
            'category_id' => 'required|exists:categories,id',
            'sku' => [
                'required',
                function ($attribute, $value, $fail) use ($empresaId) {
                    $query = Product::where('sku', $value)
                        ->where('empresa_id', $empresaId);
                    
                    if ($this->editingId) {
                        $query->where('id', '!=', $this->editingId);
                    }
                    
                    if ($query->exists()) {
                        $fail('El SKU ya existe en esta empresa.');
                    }
                },
            ],
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB máximo
            'tax_type' => 'nullable|in:standard,exempt,excluded,custom',
            'custom_tax_rate' => 'nullable|numeric|min:0|max:100',
        ];

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
            $imagePath = process_and_save_image($this->image, 'products', 800, 85);
            $validatedData['image'] = $imagePath;
        }

        if ($this->editingId) {
            // Update existing product
            $product = Product::find($this->editingId);
            
            // Delete old image if uploading a new one
            if ($this->image && $product->image) {
                delete_image($product->image);
            }
            
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
     * Analizar imagen de producto con IA
     */
    public function analyzeImage()
    {
        // Validar que haya una imagen
        $this->validate([
            'image' => 'required|image|max:5120', // Max 5MB
        ]);

        try {
            // Convertir imagen a base64
            $imageBase64 = base64_encode(file_get_contents($this->image->getRealPath()));

            // Analizar con IA
            $analyzer = new ProductImageAnalyzer();
            
            if (!$analyzer->isAvailable()) {
                session()->flash('error', 'Servicio de IA no configurado. Agrega tu API key de OpenAI en el archivo .env');
                return;
            }

            $result = $analyzer->analyzeProductImage($imageBase64);

            if ($result) {
                // Auto-completar campos
                $this->name = $result['nombre'] ?? '';
                
                // Buscar o sugerir categoría
                if (!empty($result['categoria_sugerida'])) {
                    $category = Category::where('name', 'like', '%' . $result['categoria_sugerida'] . '%')->first();
                    if ($category) {
                        $this->category_id = $category->id;
                    }
                }
                
                // Sugerir precio si viene
                if (!empty($result['precio_estimado'])) {
                    $this->price = $result['precio_estimado'];
                }
                
                // Si viene SKU/código de barras
                if (!empty($result['codigo_barras'])) {
                    $this->sku = $result['codigo_barras'];
                }

                // Mensaje de éxito con información de confianza
                $confianza = $result['confianza'] ?? 'media';
                
                session()->flash('success', "Producto identificado con confianza {$confianza}. Revisa y ajusta la información si es necesario.");
                
                // Información adicional en descripción o nota
                if (!empty($result['descripcion'])) {
                    session()->flash('ai_description', $result['descripcion']);
                }
            } else {
                session()->flash('error', 'No se pudo analizar la imagen. Intenta con otra foto más clara.');
            }

        } catch (\Exception $e) {
            \Log::error('Error analyzing image in ProductManager', [
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Error al procesar la imagen: ' . $e->getMessage());
        }
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
