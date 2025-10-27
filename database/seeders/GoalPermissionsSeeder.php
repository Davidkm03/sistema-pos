<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GoalPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create goal permissions
        $goalPermissions = [
            'view-goals',
            'create-goals',
            'edit-goals',
            'delete-goals',
        ];

        foreach ($goalPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get roles
        $adminRole = Role::findByName('Admin');
        $supervisorRole = Role::findByName('Supervisor');
        $cajeroRole = Role::findByName('Cajero');

        // Assign all goal permissions to Admin (Admin already has all permissions, but we'll add explicitly)
        $adminRole->givePermissionTo($goalPermissions);

        // Assign all goal permissions to Supervisor
        $supervisorRole->givePermissionTo($goalPermissions);

        // Assign only view-goals to Cajero
        $cajeroRole->givePermissionTo('view-goals');

        $this->command->info('Goal permissions created and assigned successfully!');
        $this->command->info('- Admin: All goal permissions');
        $this->command->info('- Supervisor: All goal permissions');
        $this->command->info('- Cajero: view-goals only');
    }
}
