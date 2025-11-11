<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerManager extends Component
{
    use WithPagination;

    public $name;
    public $phone;
    public $email;
    public $tax_id_type = 'CC';
    public $tax_id;
    public $tax_regime = 'simplified';
    public $is_retention_agent = false;

    public $editingId = null;
    public $search = '';
    public $showHistoryModal = false;
    public $selectedCustomer = null;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:150',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'tax_id_type' => 'required|in:CC,NIT,CE,Pasaporte',
        'tax_id' => 'nullable|string|max:50',
        'tax_regime' => 'required|in:simplified,common',
        'is_retention_agent' => 'nullable|boolean',
    ];

    protected $messages = [
        'name.required' => 'El nombre del cliente es obligatorio',
        'name.max' => 'El nombre no puede exceder 150 caracteres',
        'phone.max' => 'El teléfono no puede exceder 20 caracteres',
        'email.email' => 'El email debe ser válido',
        'email.max' => 'El email no puede exceder 255 caracteres',
        'tax_id_type.required' => 'El tipo de documento es obligatorio',
        'tax_id_type.in' => 'El tipo de documento no es válido',
        'tax_id.max' => 'El documento no puede exceder 50 caracteres',
        'tax_regime.required' => 'El régimen tributario es obligatorio',
        'tax_regime.in' => 'El régimen tributario no es válido',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                $customer = Customer::findOrFail($this->editingId);
                $customer->update([
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'tax_id_type' => $this->tax_id_type,
                    'tax_id' => $this->tax_id,
                    'tax_regime' => $this->tax_regime,
                    'is_retention_agent' => $this->is_retention_agent,
                ]);

                $this->dispatch('customer-updated');
            } else {
                Customer::create([
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'tax_id_type' => $this->tax_id_type,
                    'tax_id' => $this->tax_id,
                    'tax_regime' => $this->tax_regime,
                    'is_retention_agent' => $this->is_retention_agent,
                ]);

                $this->dispatch('customer-created');
            }

            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('customer-error', message: $e->getMessage());
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        
        $this->editingId = $customer->id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->tax_id_type = $customer->tax_id_type;
        $this->tax_id = $customer->tax_id;
        $this->tax_regime = $customer->tax_regime;
        $this->is_retention_agent = $customer->is_retention_agent;
    }

    public function delete($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            // Check if customer has sales
            if ($customer->sales()->count() > 0) {
                $this->dispatch('customer-error', message: 'No se puede eliminar un cliente con ventas asociadas');
                return;
            }

            $customer->delete();
            $this->dispatch('customer-deleted');
        } catch (\Exception $e) {
            $this->dispatch('customer-error', message: $e->getMessage());
        }
    }

    public function viewHistory($id)
    {
        $this->selectedCustomer = Customer::with(['sales' => function($query) {
            $query->orderBy('created_at', 'desc')->take(10);
        }])->findOrFail($id);
        
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedCustomer = null;
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'phone',
            'email',
            'tax_id_type',
            'tax_id',
            'tax_regime',
            'is_retention_agent',
            'editingId',
        ]);

        $this->tax_id_type = 'CC';
        $this->tax_regime = 'simplified';
        $this->is_retention_agent = false;
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('tax_id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.customer-manager', [
            'customers' => $customers,
        ])->layout('layouts.app');
    }
}
