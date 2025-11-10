<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryManager extends Component
{
    use WithPagination;

    // Public properties
    public $name;
    public $description;
    public $editingId = null;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . ($this->editingId ?? 'NULL'),
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom validation messages
     */
    protected $messages = [
        'name.required' => 'El nombre de la categoría es obligatorio',
        'name.max' => 'El nombre no puede tener más de 100 caracteres',
        'name.unique' => 'Ya existe una categoría con este nombre',
        'description.max' => 'La descripción no puede tener más de 500 caracteres',
    ];

    /**
     * Reset form fields
     */
    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    /**
     * Create or update category
     */
    public function save()
    {
        // Check permission
        if (!Auth::user()->can('manage-settings')) {
            $this->dispatch('category-error', message: 'No tienes permiso para gestionar categorías');
            return;
        }

        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'empresa_id' => Auth::user()->empresa_id,
            ];

            if ($this->editingId) {
                // Update existing category
                $category = Category::find($this->editingId);
                
                if (!$category) {
                    $this->dispatch('category-error', message: 'Categoría no encontrada');
                    return;
                }

                $category->update($data);
                $this->dispatch('category-updated', message: 'Categoría actualizada exitosamente');
            } else {
                // Create new category
                Category::create($data);
                $this->dispatch('category-created', message: 'Categoría creada exitosamente');
            }

            $this->resetForm();
            
        } catch (\Exception $e) {
            Log::error('Error saving category: ' . $e->getMessage());
            $this->dispatch('category-error', message: 'Error al guardar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Edit category
     */
    public function edit($id)
    {
        $category = Category::find($id);
        
        if ($category) {
            $this->editingId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
        }
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        // Check permission
        if (!Auth::user()->can('manage-settings')) {
            $this->dispatch('category-error', message: 'No tienes permiso para eliminar categorías');
            return;
        }

        try {
            $category = Category::find($id);
            
            if ($category) {
                // Check if category has products
                if ($category->products()->count() > 0) {
                    $this->dispatch('category-error', message: 'No se puede eliminar una categoría que tiene productos asociados');
                    return;
                }

                $category->delete();
                $this->dispatch('category-deleted', message: 'Categoría eliminada exitosamente');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            $this->dispatch('category-error', message: 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Update search and reset pagination
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Render component
     */
    public function render()
    {
        $categories = Category::withCount('products')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.category-manager', [
            'categories' => $categories
        ]);
    }
}
