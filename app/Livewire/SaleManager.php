<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\SaleCancellationReason;
use App\Models\SaleAuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
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
    public $cancellationReason = '';
    public $detailedReason = '';

    // Info
    public $previousReasons = [];

    protected $rules = [
        'cancellationReason' => 'required|min:5',
        'detailedReason' => 'required|min:20',
    ];

    public function mount()
    {
        $this->dateFrom = today()->subDays(7)->format('Y-m-d');
        $this->dateTo = today()->format('Y-m-d');
        $this->loadPreviousReasons();
    }

    public function loadPreviousReasons()
    {
        // Obtener razones únicas de ventas canceladas anteriormente
        $this->previousReasons = Sale::whereNotNull('cancellation_reason')
            ->where('status', 'cancelada')
            ->select('cancellation_reason')
            ->distinct()
            ->orderBy('cancellation_reason')
            ->limit(20)
            ->pluck('cancellation_reason')
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
        $this->reset(['cancellationReason', 'detailedReason']);
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
            // Anular la venta con la razón escrita por el usuario
            $this->saleToCancel->cancel($this->cancellationReason, $this->detailedReason);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Venta #' . $this->saleToCancel->id . ' anulada exitosamente'
            ]);

            $this->closeCancelModal();
            
            // Recargar razones para incluir la nueva
            $this->loadPreviousReasons();

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
        ]);
    }
}
