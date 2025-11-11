<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ExpenseManager extends Component
{
    use WithPagination, WithFileUploads;

    public $description;
    public $amount;
    public $expense_date;
    public $expense_category_id;
    public $receipt_number;
    public $notes;
    public $attachment;

    // Category management
    public $categoryName;
    public $categoryDescription;
    public $categoryColor = '#6B7280';
    public $showCategoryForm = false;

    public $editingId = null;
    public $search = '';
    public $dateFrom;
    public $dateTo;
    public $categoryFilter = 'all';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'receipt_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    protected $messages = [
        'description.required' => 'La descripción es obligatoria',
        'description.max' => 'La descripción no puede exceder 255 caracteres',
        'amount.required' => 'El monto es obligatorio',
        'amount.numeric' => 'El monto debe ser un valor numérico',
        'amount.min' => 'El monto debe ser mayor o igual a 0',
        'expense_date.required' => 'La fecha del gasto es obligatoria',
        'expense_date.date' => 'La fecha debe ser válida',
        'expense_category_id.required' => 'Debe seleccionar una categoría',
        'expense_category_id.exists' => 'La categoría seleccionada no es válida',
        'receipt_number.max' => 'El número de recibo no puede exceder 50 caracteres',
        'notes.max' => 'Las notas no pueden exceder 500 caracteres',
        'attachment.file' => 'El adjunto debe ser un archivo',
        'attachment.mimes' => 'El archivo debe ser JPG, JPEG, PNG o PDF',
        'attachment.max' => 'El archivo no puede exceder 2MB',
    ];

    public function mount()
    {
        $this->expense_date = date('Y-m-d');
        $this->dateFrom = date('Y-m-01');
        $this->dateTo = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'description' => $this->description,
                'amount' => $this->amount,
                'expense_date' => $this->expense_date,
                'expense_category_id' => $this->expense_category_id,
                'receipt_number' => $this->receipt_number,
                'notes' => $this->notes,
            ];

            if ($this->attachment) {
                $path = $this->attachment->store('expenses', 'public');
                $data['attachment_path'] = $path;
            }

            if ($this->editingId) {
                $expense = Expense::findOrFail($this->editingId);
                $expense->update($data);
                $this->dispatch('expense-updated');
            } else {
                Expense::create($data);
                $this->dispatch('expense-created');
            }

            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('expense-error', message: $e->getMessage());
        }
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        
        $this->editingId = $expense->id;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->expense_category_id = $expense->expense_category_id;
        $this->receipt_number = $expense->receipt_number;
        $this->notes = $expense->notes;
    }

    public function delete($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            
            // Delete attachment if exists
            if ($expense->attachment_path) {
                \Storage::disk('public')->delete($expense->attachment_path);
            }

            $expense->delete();
            $this->dispatch('expense-deleted');
        } catch (\Exception $e) {
            $this->dispatch('expense-error', message: $e->getMessage());
        }
    }

    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:100',
            'categoryDescription' => 'nullable|string|max:255',
            'categoryColor' => 'required|string|size:7',
        ], [
            'categoryName.required' => 'El nombre de la categoría es obligatorio',
            'categoryName.max' => 'El nombre no puede exceder 100 caracteres',
            'categoryDescription.max' => 'La descripción no puede exceder 255 caracteres',
            'categoryColor.required' => 'Debe seleccionar un color',
            'categoryColor.size' => 'El formato del color no es válido',
        ]);

        try {
            ExpenseCategory::create([
                'name' => $this->categoryName,
                'description' => $this->categoryDescription,
                'color' => $this->categoryColor,
            ]);

            $this->reset(['categoryName', 'categoryDescription', 'categoryColor', 'showCategoryForm']);
            $this->categoryColor = '#6B7280';
            $this->dispatch('category-created');
        } catch (\Exception $e) {
            $this->dispatch('expense-error', message: $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'description',
            'amount',
            'expense_category_id',
            'receipt_number',
            'notes',
            'attachment',
            'editingId',
        ]);

        $this->expense_date = date('Y-m-d');
    }

    public function render()
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();

        $expenses = Expense::query()
            ->with(['category', 'user'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('receipt_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('expense_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('expense_date', '<=', $this->dateTo);
            })
            ->when($this->categoryFilter !== 'all', function($query) {
                $query->where('expense_category_id', $this->categoryFilter);
            })
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        $totalExpenses = Expense::query()
            ->when($this->dateFrom, function($query) {
                $query->whereDate('expense_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('expense_date', '<=', $this->dateTo);
            })
            ->when($this->categoryFilter !== 'all', function($query) {
                $query->where('expense_category_id', $this->categoryFilter);
            })
            ->sum('amount');

        return view('livewire.expense-manager', [
            'categories' => $categories,
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
        ])->layout('layouts.app');
    }
}
