<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of roles and permissions
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            // Agrupar permisos por módulo (ej: "view-products" -> "products")
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        });

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for editing role permissions
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role permissions
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // No permitir editar super-admin
        if ($role->name === 'super-admin') {
            return redirect()->back()->with('error', 'No se puede modificar el rol de Super Admin');
        }

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Permisos actualizados para el rol: {$role->name}");
    }

    /**
     * Store a new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$request->name}' creado exitosamente");
    }

    /**
     * Delete a role
     */
    public function destroy(Role $role)
    {
        // No permitir eliminar super-admin
        if ($role->name === 'super-admin') {
            return redirect()->back()->with('error', 'No se puede eliminar el rol de Super Admin');
        }

        // Verificar si hay usuarios con este rol
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', "No se puede eliminar el rol '{$role->name}' porque hay {$role->users()->count()} usuario(s) asignado(s)");
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$roleName}' eliminado exitosamente");
    }
}
