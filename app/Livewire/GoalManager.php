<?php

namespace App\Livewire;

use App\Models\Goal;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GoalManager extends Component
{
    use WithPagination, AuthorizesRequests;

    // Public properties for form
    public $name;
    public $target_amount;
    public $start_date;
    public $end_date;
    public $editingId = null;

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:200',
            'target_amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    /**
     * Custom validation messages
     */
    protected $messages = [
        'start_date.before_or_equal' => 'La fecha de inicio debe ser anterior o igual a la fecha de fin',
        'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio',
        'target_amount.min' => 'El monto objetivo debe ser mayor a 0',
    ];

    /**
     * Save goal (create or update)
     */
    public function save()
    {
        // Check permissions
        if ($this->editingId) {
            $this->authorize('edit-goals');
        } else {
            $this->authorize('create-goals');
        }
        
        $this->validate();

        try {
            if ($this->editingId) {
                // Update existing goal
                $goal = Goal::findOrFail($this->editingId);
                $goal->update([
                    'name' => $this->name,
                    'target_amount' => $this->target_amount,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                ]);
                
                $this->dispatch('goal-saved', message: 'Meta actualizada exitosamente', isEdit: true);
            } else {
                // Create new goal
                Goal::create([
                    'name' => $this->name,
                    'target_amount' => $this->target_amount,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'status' => 'active',
                    'user_id' => Auth::id(),
                ]);
                
                $this->dispatch('goal-saved', message: 'Meta creada exitosamente', isEdit: false);
            }

            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('goal-error', message: 'Error al guardar meta: ' . $e->getMessage());
        }
    }

    /**
     * Edit goal - load data into form
     */
    public function edit($id)
    {
        $this->authorize('edit-goals');
        
        $goal = Goal::findOrFail($id);
        
        $this->editingId = $goal->id;
        $this->name = $goal->name;
        $this->target_amount = $goal->target_amount;
        $this->start_date = \Carbon\Carbon::parse($goal->start_date)->format('Y-m-d');
        $this->end_date = \Carbon\Carbon::parse($goal->end_date)->format('Y-m-d');
    }

    /**
     * Cancel goal
     */
    public function cancel($id)
    {
        $this->authorize('edit-goals');
        
        try {
            $goal = Goal::findOrFail($id);
            $goal->update(['status' => 'cancelled']);
            
            $this->dispatch('goal-cancelled');
        } catch (\Exception $e) {
            $this->dispatch('goal-error', message: 'Error al cancelar meta: ' . $e->getMessage());
        }
    }

    /**
     * Mark goal as completed manually
     */
    public function markCompleted($id)
    {
        try {
            $goal = Goal::findOrFail($id);
            $goal->update(['status' => 'completed']);
            
            $this->dispatch('goal-completed');
        } catch (\Exception $e) {
            $this->dispatch('goal-error', message: 'Error al marcar meta como completada: ' . $e->getMessage());
        }
    }

    /**
     * Reset form - clear all properties and validations
     */
    public function resetForm()
    {
        $this->reset([
            'name',
            'target_amount',
            'start_date',
            'end_date',
            'editingId'
        ]);
        
        $this->resetValidation();
    }

    /**
     * Render the component
     */
    public function render()
    {
        // Get all goals ordered by created date (newest first)
        $goals = Goal::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.goal-manager', [
            'goals' => $goals,
        ]);
    }
}
