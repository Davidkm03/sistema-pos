<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupplierManager extends Component
{
    use WithPagination;

    // Public properties
    public $name;
    public $contact_name;
    public $email;
    public $phone;
    public $address;
    public $notes;
    public $is_active = true;
    public $editingId = null;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules
     */
    protected function rules()
    {
        $empresaId = Auth::user()->empresa_id;
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($empresaId) {
                    $query = Supplier::where('name', $value)
                        ->where('empresa_id', $empresaId);
                    
                    if ($this->editingId) {
                        $query->where('id', '!=', $this->editingId);
                    }
                    
                    if ($query->exists()) {
                        $fail('Ya existe un proveedor con este nombre.');
                    }
                },
            ],
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Custom validation messages
     */
    protected $messages = [
        'name.required' => 'El nombre del proveedor es obligatorio',
        'name.max' => 'El nombre no puede tener mas de 255 caracteres',
        'email.email' => 'Ingresa un correo electronico valido',
        'phone.max' => 'El telefono no puede tener mas de 20 caracteres',
        'address.max' => 'La direccion no puede tener mas de 500 caracteres',
        'notes.max' => 'Las notas no pueden tener mas de 1000 caracteres',
    ];

    /**
     * Reset form fields
     */
    public function resetForm()
    {
        $this->name = '';
        $this->contact_name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->notes = '';
        $this->is_active = true;
        $this->editingId = null;
        $this->resetValidation();
    }

    /**
     * Create or update supplier
     */
    public function save()
    {
        // Check permission
        if (!Auth::user()->can('manage-settings')) {
            $this->dispatch('supplier-error', message: 'No tienes permiso para gestionar proveedores');
            return;
        }

        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'contact_name' => $this->contact_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'notes' => $this->notes,
                'is_active' => $this->is_active,
                'empresa_id' => Auth::user()->empresa_id,
            ];

            if ($this->editingId) {
                // Update existing supplier
                $supplier = Supplier::find($this->editingId);
                
                if (!$supplier) {
                    $this->dispatch('supplier-error', message: 'Proveedor no encontrado');
                    return;
                }

                $supplier->update($data);
                $this->dispatch('supplier-updated', message: 'Proveedor actualizado exitosamente');
            } else {
                // Create new supplier
                Supplier::create($data);
                $this->dispatch('supplier-created', message: 'Proveedor creado exitosamente');
            }

            $this->resetForm();
            
        } catch (\Exception $e) {
            Log::error('Error saving supplier: ' . $e->getMessage());
            $this->dispatch('supplier-error', message: 'Error al guardar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Edit supplier
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);
        
        if ($supplier) {
            $this->editingId = $supplier->id;
            $this->name = $supplier->name;
            $this->contact_name = $supplier->contact_name;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->address = $supplier->address;
            $this->notes = $supplier->notes;
            $this->is_active = $supplier->is_active;
        }
    }

    /**
     * Delete supplier
     */
    public function delete($id)
    {
        // Check permission
        if (!Auth::user()->can('manage-settings')) {
            $this->dispatch('supplier-error', message: 'No tienes permiso para eliminar proveedores');
            return;
        }

        try {
            $supplier = Supplier::find($id);
            
            if ($supplier) {
                $supplier->delete();
                $this->dispatch('supplier-deleted', message: 'Proveedor eliminado exitosamente');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting supplier: ' . $e->getMessage());
            $this->dispatch('supplier-error', message: 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Toggle supplier active status
     */
    public function toggleActive($id)
    {
        try {
            $supplier = Supplier::find($id);
            
            if ($supplier) {
                $supplier->is_active = !$supplier->is_active;
                $supplier->save();
                
                $status = $supplier->is_active ? 'activado' : 'desactivado';
                $this->dispatch('supplier-toggled', message: "Proveedor {$status} exitosamente");
            }
        } catch (\Exception $e) {
            Log::error('Error toggling supplier: ' . $e->getMessage());
            $this->dispatch('supplier-error', message: 'Error al cambiar estado del proveedor');
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
        $suppliers = Supplier::when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.supplier-manager', [
            'suppliers' => $suppliers
        ]);
    }
}
