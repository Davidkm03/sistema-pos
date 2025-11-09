<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class QuotesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos de cotizaciones
        $permissions = [
            'quotes.view' => 'Ver cotizaciones',
            'quotes.create' => 'Crear cotizaciones',
            'quotes.edit' => 'Editar cotizaciones',
            'quotes.delete' => 'Eliminar cotizaciones',
            'quotes.convert' => 'Convertir cotización a venta',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(['name' => $name]);
        }

        // Asignar permisos a roles existentes
        
        // Super Admin - Todos los permisos
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(array_keys($permissions));
        }

        // Admin - Todos los permisos
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $admin->givePermissionTo(array_keys($permissions));
        }

        // Supervisor - Todos los permisos
        $supervisor = Role::where('name', 'Supervisor')->first();
        if ($supervisor) {
            $supervisor->givePermissionTo(array_keys($permissions));
        }

        // Cajero - Solo ver y crear cotizaciones (no puede convertir a venta)
        $cajero = Role::where('name', 'Cajero')->first();
        if ($cajero) {
            $cajero->givePermissionTo([
                'quotes.view',
                'quotes.create',
            ]);
        }

        $this->command->info('✅ Permisos de cotizaciones creados y asignados correctamente.');
        $this->command->info('   - Super Admin: Todos los permisos');
        $this->command->info('   - Admin: Todos los permisos');
        $this->command->info('   - Supervisor: Todos los permisos');
        $this->command->info('   - Cajero: Ver y crear solamente');
    }
}
