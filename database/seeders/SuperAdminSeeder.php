<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear rol de super admin si no existe
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        
        // Obtener todos los permisos existentes
        $allPermissions = Permission::all();
        
        // Asignar todos los permisos al super admin
        if ($allPermissions->count() > 0) {
            $superAdminRole->syncPermissions($allPermissions);
            $this->command->info("✅ Asignados {$allPermissions->count()} permisos al rol super-admin");
        }
        
        $this->command->info('✅ Rol super-admin creado/actualizado exitosamente');
    }
}
