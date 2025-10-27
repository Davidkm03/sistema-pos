<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    // Public properties for form
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role_id;
    public $editingId = null;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
        ];

        if ($this->editingId) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editingId;
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Save user (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                // Update existing user
                $user = User::findOrFail($this->editingId);
                $user->name = $this->name;
                $user->email = $this->email;
                
                // Only update password if provided
                if ($this->password) {
                    $user->password = Hash::make($this->password);
                }
                
                $user->save();
                
                // Sync role
                $role = Role::findOrFail($this->role_id);
                $user->syncRoles([$role->name]);
                
                $this->dispatch('user-saved', message: 'Usuario actualizado exitosamente', isEdit: true);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);
                
                // Assign role
                $role = Role::findOrFail($this->role_id);
                $user->assignRole($role->name);
                
                $this->dispatch('user-saved', message: 'Usuario creado exitosamente', isEdit: false);
            }

            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('user-error', message: 'Error al guardar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Edit user - load data into form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()?->id;
        // Don't load password
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting own account
            if ($user->id === auth()->id()) {
                $this->dispatch('user-error', message: 'No puedes eliminar tu propia cuenta');
                return;
            }
            
            $user->delete();
            $this->dispatch('user-deleted');
        } catch (\Exception $e) {
            $this->dispatch('user-error', message: 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Reset form - clear all properties and validations
     */
    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'role_id',
            'editingId'
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
        // Build query for users
        $query = User::with('roles');

        // Apply search filter if search term exists
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Paginate users
        $users = $query->paginate(10);

        // Get all roles for the form dropdown
        $roles = Role::all();

        return view('livewire.user-manager', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
