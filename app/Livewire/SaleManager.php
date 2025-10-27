<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\SaleCancellationReason;
use App\Models\SaleAuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SaleManager extends Component
{
    use WithPagination;

    // Filtros
    public $searchTerm = '';
    public $statusFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $userFilter = null;

    // Modal de anulación
    public $showCancelModal = false;
    public $saleToCancel = null;
    public $selectedReason = null;
    public $detailedReason = '';

    // Info
    public $cancellationReasons = [];

    protected $rules = [
        'selectedReason' => 'required',
        'detailedReason' => 'required|min:20',
    ];

    public function mount()
    {
        $this->dateFrom = today()->subDays(7)->format('Y-m-d');
        $this->dateTo = today()->format('Y-m-d');
        $this->loadCancellationReasons();
    }

    public function loadCancellationReasons()
    {
        $this->cancellationReasons = SaleCancellationReason::active()
            ->ordered()
            ->get()
            ->map(function ($reason) {
                return [
                    'id' => $reason->id,
                    'text' => $reason->reason,
                    'requires_approval' => $reason->requires_admin_approval,
                ];
            })
            ->toArray();
    }

    public function openCancelModal($saleId)
    {
        $sale = Sale::with(['saleItems.product', 'user', 'customer'])->findOrFail($saleId);

        // Verificar si se puede anular
        $validation = $sale->canBeCancelled();
        if (!$validation['can']) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $validation['reason']
            ]);
            return;
        }

        $this->saleToCancel = $sale;
        $this->showCancelModal = true;
        $this->reset(['selectedReason', 'detailedReason']);
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->saleToCancel = null;
        $this->resetValidation();
    }

    public function confirmCancellation()
    {
        $this->validate();

        try {
            $reason = SaleCancellationReason::find($this->selectedReason);

            // Verificar si requiere aprobación de admin
            if ($reason->requiresApproval() && !auth()->user()->hasRole('Admin')) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Esta razón requiere aprobación de un administrador'
                ]);
                return;
            }

            // Anular la venta
            $this->saleToCancel->cancel($reason->reason, $this->detailedReason);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => '✓ Venta #' . $this->saleToCancel->id . ' anulada exitosamente'
            ]);

            $this->closeCancelModal();

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error al anular: ' . $e->getMessage()
            ]);
        }
    }

    public function getSalesProperty()
    {
        $query = Sale::with(['user', 'customer', 'saleItems'])
            ->when($this->searchTerm, function ($q) {
                $q->where('id', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('receipt_number', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest();

        return $query->paginate(20);
    }

    public function render()
    {
        return view('livewire.sale-manager', [
            'sales' => $this->sales,
        ])->layout('layouts.app');
    }
}
