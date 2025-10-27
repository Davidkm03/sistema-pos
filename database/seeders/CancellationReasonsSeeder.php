<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SaleCancellationReason;

class CancellationReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            [
                'reason' => 'Error en productos (item incorrecto)',
                'requires_admin_approval' => false,
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'reason' => 'Error en precio',
                'requires_admin_approval' => false,
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'reason' => 'Error en cantidad',
                'requires_admin_approval' => false,
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'reason' => 'Cliente no pagó',
                'requires_admin_approval' => false,
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'reason' => 'Devolución por garantía',
                'requires_admin_approval' => true,
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'reason' => 'Devolución de dinero',
                'requires_admin_approval' => true,
                'active' => true,
                'sort_order' => 6,
            ],
            [
                'reason' => 'Fraude detectado',
                'requires_admin_approval' => true,
                'active' => true,
                'sort_order' => 7,
            ],
            [
                'reason' => 'Venta duplicada',
                'requires_admin_approval' => false,
                'active' => true,
                'sort_order' => 8,
            ],
            [
                'reason' => 'Cliente solicitó cancelación',
                'requires_admin_approval' => true,
                'active' => true,
                'sort_order' => 9,
            ],
            [
                'reason' => 'Otra razón (especificar en descripción)',
                'requires_admin_approval' => false,
                'active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($reasons as $reason) {
            SaleCancellationReason::firstOrCreate(
                ['reason' => $reason['reason']],
                $reason
            );
        }

        $this->command->info('✓ Razones de anulación creadas exitosamente');
    }
}
