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
        $superAdminRole->syncPermissions($allPermissions);
        
        // Crear usuario super admin si no existe
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@sistema-pos.com'],
            [
                'name' => 'Super Administrador',
                'password' => bcrypt('SuperAdmin123!'),
            ]
        );
        
        // Asignar rol de super admin
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }
        
        $this->command->info('✅ Super Admin creado exitosamente');
        $this->command->info('📧 Email: superadmin@sistema-pos.com');
        $this->command->info('🔐 Password: SuperAdmin123!');
    }
}
