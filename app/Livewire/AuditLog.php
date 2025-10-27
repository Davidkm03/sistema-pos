<?php

namespace App\Livewire;

use App\Models\SaleAuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class AuditLog extends Component
{
    use WithPagination;

    // Filtros
    public $searchTerm = '';
    public $actionFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $userFilter = null;

    // Modal de detalles
    public $showDetailsModal = false;
    public $selectedLog = null;

    public function mount()
    {
        $this->dateFrom = today()->subDays(30)->format('Y-m-d');
        $this->dateTo = today()->format('Y-m-d');
    }

    public function openDetailsModal($logId)
    {
        $this->selectedLog = SaleAuditLog::with(['sale', 'user'])->findOrFail($logId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedLog = null;
    }

    public function getLogsProperty()
    {
        $query = SaleAuditLog::with(['sale', 'user'])
            ->when($this->searchTerm, function ($q) {
                $q->where('sale_id', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            })
            ->when($this->actionFilter !== 'all', function ($q) {
                $q->where('action', $this->actionFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->userFilter, function ($q) {
                $q->where('user_id', $this->userFilter);
            })
            ->latest();

        return $query->paginate(20);
    }

    public function render()
    {
        return view('livewire.audit-log', [
            'logs' => $this->logs,
        ])->layout('layouts.app');
    }
}
