<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illumina    /**
     * Delete user
     */
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validar que el usuario pertenezca a la misma empresa (excepto super-admin)
            if (!auth()->user()->hasRole('super-admin') && $user->empresa_id !== auth()->user()->empresa_id) {
                $this->dispatch('user-error', message: 'No tienes permiso para eliminar este usuario');
                return;
            }
            
            // Prevent deleting own account
            if ($user->id === auth()->user()->id) {
                $this->dispatch('user-error', message: 'No puedes eliminar tu propia cuenta');
                return;
            }
            
            $user->delete();
            $this->dispatch('user-deleted');
        } catch (\Exception $e) {
            $this->dispatch('user-error', message: 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }ash;
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
    public $empresa_id;
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
            'empresa_id' => 'required|exists:empresas,id',
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
            $currentUser = auth()->user();
            
            // Validar que Admin solo pueda crear/editar usuarios de su empresa
            if (!$currentUser->hasRole('super-admin') && $this->empresa_id != $currentUser->empresa_id) {
                $this->dispatch('user-error', message: 'Solo puedes gestionar usuarios de tu empresa');
                return;
            }

            if ($this->editingId) {
                // Update existing user
                $user = User::findOrFail($this->editingId);
                
                // Validar que el usuario a editar pertenezca a la misma empresa
                if (!$currentUser->hasRole('super-admin') && $user->empresa_id !== $currentUser->empresa_id) {
                    $this->dispatch('user-error', message: 'No tienes permiso para editar este usuario');
                    return;
                }
                
                $user->name = $this->name;
                $user->email = $this->email;
                $user->empresa_id = $this->empresa_id;
                
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
                    'empresa_id' => $this->empresa_id,
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
        
        // Validar que el usuario pertenezca a la misma empresa (excepto super-admin)
        if (!auth()->user()->hasRole('super-admin') && $user->empresa_id !== auth()->user()->empresa_id) {
            $this->dispatch('user-error', message: 'No tienes permiso para editar este usuario');
            return;
        }
        
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()?->id;
        $this->empresa_id = $user->empresa_id;
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
            'empresa_id',
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
        $currentUser = auth()->user();
        
        // Build query for users
        $query = User::with('roles', 'empresa');

        // Filtrar por empresa: Solo super-admin puede ver todos los usuarios
        // Los demás solo ven usuarios de su empresa
        if (!$currentUser->hasRole('super-admin')) {
            $query->where('empresa_id', $currentUser->empresa_id);
        }

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
        
        // Get all empresas for the form dropdown
        // Super-admin puede asignar cualquier empresa, Admin solo su empresa
        if ($currentUser->hasRole('super-admin')) {
            $empresas = \App\Models\Empresa::orderBy('nombre')->get();
        } else {
            // Admin solo puede asignar usuarios a su propia empresa
            $empresas = \App\Models\Empresa::where('id', $currentUser->empresa_id)->get();
            // Auto-asignar empresa_id si no está seleccionada
            if (!$this->empresa_id) {
                $this->empresa_id = $currentUser->empresa_id;
            }
        }

        return view('livewire.user-manager', [
            'users' => $users,
            'roles' => $roles,
            'empresas' => $empresas,
        ]);
    }
}
