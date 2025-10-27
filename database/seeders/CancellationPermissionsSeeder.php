<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CancellationPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            'cancel-own-sales' => 'Anular sus propias ventas del mismo día',
            'cancel-any-sales' => 'Anular cualquier venta reciente',
            'cancel-old-sales' => 'Anular ventas antiguas (>24 horas)',
            'view-audit-log' => 'Ver log de auditoría de ventas',
            'correct-sales' => 'Corregir ventas',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web']
            );
        }

        $this->command->info('✓ Permisos de anulación creados');

        // Asignar permisos a roles
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $admin->givePermissionTo([
                'cancel-own-sales',
                'cancel-any-sales',
                'cancel-old-sales',
                'view-audit-log',
                'correct-sales',
            ]);
            $this->command->info('✓ Permisos asignados a Admin');
        }

        // Cajero solo puede anular sus ventas del día
        $cashier = Role::where('name', 'Cajero')->first();
        if ($cashier) {
            $cashier->givePermissionTo([
                'cancel-own-sales',
            ]);
            $this->command->info('✓ Permisos asignados a Cajero');
        }
    }
}
