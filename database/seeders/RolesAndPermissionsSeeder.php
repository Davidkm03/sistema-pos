<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Permisos de POS
            'access-pos',
            'process-sales',
            
            // Permisos de productos
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Permisos de inventario
            'view-inventory',
            'manage-inventory',
            
            // Permisos de ventas
            'view-sales',
            'view-all-sales',
            'delete-sales',
            
            // Permisos de reportes
            'view-reports',
            
            // Permisos de metas de ganancia
            'view-goals',
            'create-goals',
            'edit-goals',
            'delete-goals',
            
            // Permisos de configuración
            'manage-settings',
            
            // Permisos de usuarios
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'assign-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear rol Admin con todos los permisos
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Crear rol Supervisor
        $supervisorRole = Role::create(['name' => 'Supervisor']);
        $supervisorRole->givePermissionTo([
            'access-pos',
            'process-sales',
            'view-products',
            'create-products',
            'edit-products',
            'view-inventory',
            'manage-inventory',
            'view-all-sales',
            'view-reports',
            'view-goals',
            'create-goals',
            'edit-goals',
            'delete-goals',
            'manage-settings',
        ]);

        // Crear rol Cajero
        $cajeroRole = Role::create(['name' => 'Cajero']);
        $cajeroRole->givePermissionTo([
            'access-pos',
            'process-sales',
            'view-products',
            'view-sales', // Solo sus propias ventas
            'view-goals', // Solo puede ver metas, no editar
        ]);

        // Asignar rol Admin al primer usuario (si existe)
        $user = User::first();
        if ($user) {
            $user->assignRole('Admin');
            $this->command->info("Rol 'Admin' asignado al usuario: {$user->email}");
        }

        $this->command->info('Roles y permisos creados exitosamente!');
        $this->command->info('Roles creados: Admin, Supervisor, Cajero');
    }
}
