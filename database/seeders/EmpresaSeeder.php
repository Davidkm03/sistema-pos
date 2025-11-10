<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            [
                'nombre' => 'Tienda Principal',
                'rfc' => 'TPR123456ABC',
                'direccion' => 'Av. Principal #123, Centro, Ciudad de México',
                'telefono' => '55-1234-5678',
                'email' => 'contacto@tiendaprincipal.com',
                'sitio_web' => 'https://tiendaprincipal.com',
                'moneda' => 'MXN',
                'iva_porcentaje' => 16.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Sucursal Norte',
                'rfc' => 'SNO987654XYZ',
                'direccion' => 'Blvd. Norte #456, Guadalupe, Monterrey',
                'telefono' => '81-9876-5432',
                'email' => 'contacto@sucursalnorte.com',
                'sitio_web' => 'https://sucursalnorte.com',
                'moneda' => 'MXN',
                'iva_porcentaje' => 16.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Tienda Sur',
                'rfc' => 'TSU456789DEF',
                'direccion' => 'Calle Sur #789, Colonia Del Valle, Guadalajara',
                'telefono' => '33-4567-8901',
                'email' => 'contacto@tiendasur.com',
                'sitio_web' => 'https://tiendasur.com',
                'moneda' => 'MXN',
                'iva_porcentaje' => 16.00,
                'activo' => true,
            ],
        ];

        foreach ($empresas as $empresaData) {
            Empresa::create($empresaData);
        }

        $this->command->info('✅ Empresas creadas exitosamente:');
        $this->command->info('   - Tienda Principal');
        $this->command->info('   - Sucursal Norte');
        $this->command->info('   - Tienda Sur');
    }
}

