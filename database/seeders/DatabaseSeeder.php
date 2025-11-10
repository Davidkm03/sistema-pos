<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders de roles y permisos primero
        $this->call([
            RolesAndPermissionsSeeder::class,
            CancellationPermissionsSeeder::class,
            QuotesPermissionsSeeder::class,
            GoalPermissionsSeeder::class,
            SuperAdminSeeder::class, // Crea el super-admin y asigna todos los permisos
            EmpresaSeeder::class, // Crear empresas ANTES de crear usuarios
            BusinessSettingsSeeder::class,
            TicketSettingSeeder::class,
            CancellationReasonsSeeder::class,
        ]);
        
        // Obtener la primera empresa
        $empresaPrincipal = \App\Models\Empresa::first();
        
        // Obtener el rol de super-admin creado por SuperAdminSeeder
        $superAdminRole = Role::where('name', 'super-admin')->first();
        
        // Crear usuario administrador principal
        $admin = User::factory()->create([
            'empresa_id' => $empresaPrincipal->id,
            'name' => 'Super Administrador',
            'email' => 'admin@sistema-pos.com',
        ]);
        
        // Asignar rol de super-admin
        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
        }

        // Crear categorías
        $categories = [
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Bebidas',
                'description' => 'Bebidas frías y calientes'
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Snacks',
                'description' => 'Botanas y golosinas'
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Alimentos',
                'description' => 'Comida preparada'
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Limpieza',
                'description' => 'Productos de limpieza'
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Otros',
                'description' => 'Productos varios'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Obtener las categorías creadas
        $bebidasCategory = Category::where('name', 'Bebidas')->first();
        $snacksCategory = Category::where('name', 'Snacks')->first();
        $alimentosCategory = Category::where('name', 'Alimentos')->first();
        $limpiezaCategory = Category::where('name', 'Limpieza')->first();
        $otrosCategory = Category::where('name', 'Otros')->first();

        // Crear productos
        $products = [
            // Bebidas
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Coca Cola 600ml',
                'sku' => 'BEB-001',
                'price' => 18.00,
                'cost' => 12.60,
                'stock' => fake()->numberBetween(20, 100),
                'category_id' => $bebidasCategory->id
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Pepsi 600ml',
                'sku' => 'BEB-002',
                'price' => 17.00,
                'cost' => 11.90,
                'stock' => fake()->numberBetween(15, 80),
                'category_id' => $bebidasCategory->id
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Agua Natural 500ml',
                'sku' => 'BEB-003',
                'price' => 8.00,
                'cost' => 5.60,
                'stock' => fake()->numberBetween(30, 100),
                'category_id' => $bebidasCategory->id
            ],
            [
                'name' => 'Café Americano',
                'sku' => 'BEB-004',
                'price' => 25.00,
                'cost' => 17.50,
                'stock' => fake()->numberBetween(10, 50),
                'category_id' => $bebidasCategory->id
            ],
            
            // Snacks
            [
                'name' => 'Doritos Nacho',
                'sku' => 'SNA-001',
                'price' => 22.00,
                'cost' => 15.40,
                'stock' => fake()->numberBetween(25, 75),
                'category_id' => $snacksCategory->id
            ],
            [
                'name' => 'Sabritas Clásicas',
                'sku' => 'SNA-002',
                'price' => 20.00,
                'cost' => 14.00,
                'stock' => fake()->numberBetween(20, 60),
                'category_id' => $snacksCategory->id
            ],
            [
                'name' => 'Cheetos Torciditos',
                'sku' => 'SNA-003',
                'price' => 18.50,
                'cost' => 12.95,
                'stock' => fake()->numberBetween(15, 80),
                'category_id' => $snacksCategory->id
            ],
            [
                'name' => 'Cacahuates Japoneses',
                'sku' => 'SNA-004',
                'price' => 12.00,
                'cost' => 8.40,
                'stock' => fake()->numberBetween(10, 50),
                'category_id' => $snacksCategory->id
            ],
            [
                'name' => 'Gomitas Panditas',
                'sku' => 'SNA-005',
                'price' => 15.00,
                'cost' => 10.50,
                'stock' => fake()->numberBetween(20, 70),
                'category_id' => $snacksCategory->id
            ],

            // Alimentos
            [
                'name' => 'Torta de Jamón',
                'sku' => 'ALI-001',
                'price' => 35.00,
                'cost' => 24.50,
                'stock' => fake()->numberBetween(5, 25),
                'category_id' => $alimentosCategory->id
            ],
            [
                'name' => 'Quesadilla de Queso',
                'sku' => 'ALI-002',
                'price' => 28.00,
                'cost' => 19.60,
                'stock' => fake()->numberBetween(10, 30),
                'category_id' => $alimentosCategory->id
            ],
            [
                'name' => 'Tacos de Carnitas (3 pzas)',
                'sku' => 'ALI-003',
                'price' => 45.00,
                'cost' => 31.50,
                'stock' => fake()->numberBetween(8, 20),
                'category_id' => $alimentosCategory->id
            ],
            [
                'name' => 'Empanada de Pollo',
                'sku' => 'ALI-004',
                'price' => 22.00,
                'cost' => 15.40,
                'stock' => fake()->numberBetween(12, 35),
                'category_id' => $alimentosCategory->id
            ],

            // Limpieza
            [
                'name' => 'Detergente Ace 500g',
                'sku' => 'LIM-001',
                'price' => 32.00,
                'cost' => 22.40,
                'stock' => fake()->numberBetween(15, 40),
                'category_id' => $limpiezaCategory->id
            ],
            [
                'name' => 'Jabón Zote 200g',
                'sku' => 'LIM-002',
                'price' => 12.50,
                'cost' => 8.75,
                'stock' => fake()->numberBetween(20, 60),
                'category_id' => $limpiezaCategory->id
            ],
            [
                'name' => 'Cloro 1L',
                'sku' => 'LIM-003',
                'price' => 18.00,
                'cost' => 12.60,
                'stock' => fake()->numberBetween(10, 35),
                'category_id' => $limpiezaCategory->id
            ],

            // Otros
            [
                'name' => 'Pilas AA Duracell (2 pzas)',
                'sku' => 'OTR-001',
                'price' => 45.00,
                'cost' => 31.50,
                'stock' => fake()->numberBetween(8, 25),
                'category_id' => $otrosCategory->id
            ],
            [
                'name' => 'Encendedor BIC',
                'sku' => 'OTR-002',
                'price' => 8.50,
                'cost' => 5.95,
                'stock' => fake()->numberBetween(15, 50),
                'category_id' => $otrosCategory->id
            ],
            [
                'name' => 'Chicles Trident',
                'sku' => 'OTR-003',
                'price' => 14.00,
                'cost' => 9.80,
                'stock' => fake()->numberBetween(25, 80),
                'category_id' => $otrosCategory->id
            ]
        ];

        foreach ($products as $productData) {
            // Agregar empresa_id si no existe
            if (!isset($productData['empresa_id'])) {
                $productData['empresa_id'] = $empresaPrincipal->id;
            }
            Product::create($productData);
        }

        // Crear clientes de ejemplo
        $customers = [
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'María González López',
                'phone' => '55-1234-5678',
                'email' => 'maria.gonzalez@email.com'
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Carlos Rodríguez Pérez',
                'phone' => '55-8765-4321',
                'email' => 'carlos.rodriguez@email.com'
            ],
            [
                'empresa_id' => $empresaPrincipal->id,
                'name' => 'Ana Martínez Sánchez',
                'phone' => '55-5555-1234',
                'email' => 'ana.martinez@email.com'
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}
