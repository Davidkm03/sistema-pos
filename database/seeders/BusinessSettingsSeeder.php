<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar al usuario admin
        $admin = User::where('email', 'admin@sistema.com')->first();

        if (!$admin) {
            $admin = User::first(); // Si no existe, tomar el primer usuario
        }

        if ($admin) {
            // Crear configuración por defecto para el admin
            BusinessSetting::updateOrCreate(
                ['user_id' => $admin->id],
                [
                    'business_name' => 'Mi Tienda POS',
                    'business_address' => 'Calle Principal #123, Colonia Centro',
                    'business_phone' => '+52 55 1234 5678',
                    'business_email' => 'contacto@mitienda.com',
                    'business_tax_id' => 'ABC123456XYZ',
                    'receipt_footer' => '¡Gracias por su compra! Vuelva pronto',
                    'primary_color' => '#3B82F6',
                    'secondary_color' => '#10B981',
                    'timezone' => 'America/Mexico_City',
                    'currency' => 'MXN',
                ]
            );

            $this->command->info('✅ Configuración de negocio creada para el usuario: ' . $admin->email);
        } else {
            $this->command->warn('⚠️  No se encontró ningún usuario para crear la configuración');
        }
    }
}
