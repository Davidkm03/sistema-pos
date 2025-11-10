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

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role_id;
    public $empresa_id;
    public $editingId = null;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

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

    public function save()
    {
        $this->validate();

        try {
            $currentUser = auth()->user();
            
            if (!$currentUser->hasRole('super-admin') && $this->empresa_id != $currentUser->empresa_id) {
                $this->dispatch('user-error', message: 'Solo puedes gestionar usuarios de tu empresa');
                return;
            }

            if ($this->editingId) {
                $user = User::findOrFail($this->editingId);
                
                if (!$currentUser->hasRole('super-admin') && $user->empresa_id !== $currentUser->empresa_id) {
                    $this->dispatch('user-error', message: 'No tienes permiso para editar este usuario');
                    return;
                }
                
                $user->name = $this->name;
                $user->email = $this->email;
                $user->empresa_id = $this->empresa_id;
                
                if ($this->password) {
                    $user->password = Hash::make($this->password);
                }
                
                $user->save();
                
                $role = Role::findOrFail($this->role_id);
                $user->syncRoles([$role->name]);
                
                $this->dispatch('user-saved', message: 'Usuario actualizado exitosamente', isEdit: true);
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'empresa_id' => $this->empresa_id,
                ]);
                
                $role = Role::findOrFail($this->role_id);
                $user->assignRole($role->name);
                
                $this->dispatch('user-saved', message: 'Usuario creado exitosamente', isEdit: false);
            }

            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('user-error', message: 'Error al guardar usuario: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        if (!auth()->user()->hasRole('super-admin') && $user->empresa_id !== auth()->user()->empresa_id) {
            $this->dispatch('user-error', message: 'No tienes permiso para editar este usuario');
            return;
        }
        
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()?->id;
        $this->empresa_id = $user->empresa_id;
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if (!auth()->user()->hasRole('super-admin') && $user->empresa_id !== auth()->user()->empresa_id) {
                $this->dispatch('user-error', message: 'No tienes permiso para eliminar este usuario');
                return;
            }
            
            if ($user->id === auth()->user()->id) {
                $this->dispatch('user-error', message: 'No puedes eliminar tu propia cuenta');
                return;
            }
            
            $user->delete();
            $this->dispatch('user-deleted');
        } catch (\Exception $e) {
            $this->dispatch('user-error', message: 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $currentUser = auth()->user();
        $query = User::with('roles', 'empresa');

        if (!$currentUser->hasRole('super-admin')) {
            $query->where('empresa_id', $currentUser->empresa_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->paginate(10);
        $roles = Role::all();
        
        if ($currentUser->hasRole('super-admin')) {
            $empresas = \App\Models\Empresa::orderBy('nombre')->get();
        } else {
            $empresas = \App\Models\Empresa::where('id', $currentUser->empresa_id)->get();
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
