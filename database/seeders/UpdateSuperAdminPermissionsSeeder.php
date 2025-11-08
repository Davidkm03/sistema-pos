<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateSuperAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔄 Actualizando permisos de Super Admin...');

        // Obtener o crear el rol super-admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        
        // Obtener TODOS los permisos existentes en el sistema
        $allPermissions = Permission::all();
        
        $this->command->info("📊 Total de permisos encontrados: {$allPermissions->count()}");
        
        // Sincronizar TODOS los permisos con super-admin
        $superAdminRole->syncPermissions($allPermissions);
        
        $this->command->info('✅ Super Admin actualizado con TODOS los permisos');
        
        // Mostrar los permisos asignados
        $this->command->info('📋 Permisos asignados:');
        foreach ($allPermissions->pluck('name') as $permission) {
            $this->command->info("   - {$permission}");
        }
        
        // También actualizar el rol Admin normal
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($allPermissions);
            $this->command->info('✅ Admin actualizado con TODOS los permisos');
        }
        
        $this->command->info('');
        $this->command->info('🎯 Resumen de permisos críticos verificados:');
        
        $criticalPermissions = [
            'cancel-own-sales',
            'cancel-any-sales',
            'cancel-old-sales',
            'view-audit-log',
            'correct-sales',
            'view-goals',
            'create-goals',
            'edit-goals',
            'delete-goals',
        ];
        
        foreach ($criticalPermissions as $perm) {
            $exists = Permission::where('name', $perm)->exists();
            $status = $exists ? '✅' : '❌';
            $this->command->info("{$status} {$perm}");
        }
    }
}
