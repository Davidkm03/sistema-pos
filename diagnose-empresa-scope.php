<?php

/**
 * Script de diagnóstico para verificar problemas con empresa_id
 * 
 * Ejecutar con: php diagnose-empresa-scope.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;

echo "\n=== DIAGNÓSTICO DE MULTI-TENANCY (empresa_id) ===\n\n";

// 1. Verificar usuarios sin empresa_id
echo "1. Usuarios sin empresa_id:\n";
$usersWithoutEmpresa = User::whereNull('empresa_id')->get();
if ($usersWithoutEmpresa->count() > 0) {
    echo "   ⚠️  PROBLEMA: Hay {$usersWithoutEmpresa->count()} usuarios sin empresa_id\n";
    foreach ($usersWithoutEmpresa as $user) {
        echo "      - ID: {$user->id}, Email: {$user->email}, Name: {$user->name}\n";
    }
} else {
    echo "   ✅ Todos los usuarios tienen empresa_id asignado\n";
}

// 2. Verificar ventas sin empresa_id
echo "\n2. Ventas sin empresa_id:\n";
$salesWithoutEmpresa = Sale::withoutGlobalScopes()->whereNull('empresa_id')->count();
if ($salesWithoutEmpresa > 0) {
    echo "   ⚠️  PROBLEMA: Hay {$salesWithoutEmpresa} ventas sin empresa_id\n";
    $samples = Sale::withoutGlobalScopes()->whereNull('empresa_id')->limit(5)->get();
    foreach ($samples as $sale) {
        echo "      - Sale ID: {$sale->id}, Total: {$sale->total}, User ID: {$sale->user_id}, Date: {$sale->created_at}\n";
    }
} else {
    echo "   ✅ Todas las ventas tienen empresa_id asignado\n";
}

// 3. Verificar productos sin empresa_id
echo "\n3. Productos sin empresa_id:\n";
$productsWithoutEmpresa = Product::withoutGlobalScopes()->whereNull('empresa_id')->count();
if ($productsWithoutEmpresa > 0) {
    echo "   ⚠️  PROBLEMA: Hay {$productsWithoutEmpresa} productos sin empresa_id\n";
} else {
    echo "   ✅ Todos los productos tienen empresa_id asignado\n";
}

// 4. Verificar categorías sin empresa_id
echo "\n4. Categorías sin empresa_id:\n";
$categoriesWithoutEmpresa = Category::withoutGlobalScopes()->whereNull('empresa_id')->count();
if ($categoriesWithoutEmpresa > 0) {
    echo "   ⚠️  PROBLEMA: Hay {$categoriesWithoutEmpresa} categorías sin empresa_id\n";
} else {
    echo "   ✅ Todas las categorías tienen empresa_id asignado\n";
}

// 5. Verificar clientes sin empresa_id
echo "\n5. Clientes sin empresa_id:\n";
$customersWithoutEmpresa = Customer::withoutGlobalScopes()->whereNull('empresa_id')->count();
if ($customersWithoutEmpresa > 0) {
    echo "   ⚠️  PROBLEMA: Hay {$customersWithoutEmpresa} clientes sin empresa_id\n";
} else {
    echo "   ✅ Todos los clientes tienen empresa_id asignado\n";
}

// 6. Mostrar estadísticas por empresa
echo "\n6. Estadísticas por empresa:\n";
$empresas = \App\Models\Empresa::all();
foreach ($empresas as $empresa) {
    $userCount = User::where('empresa_id', $empresa->id)->count();
    $saleCount = Sale::withoutGlobalScopes()->where('empresa_id', $empresa->id)->count();
    $productCount = Product::withoutGlobalScopes()->where('empresa_id', $empresa->id)->count();
    
    echo "   Empresa: {$empresa->nombre} (ID: {$empresa->id})\n";
    echo "      - Usuarios: {$userCount}\n";
    echo "      - Ventas: {$saleCount}\n";
    echo "      - Productos: {$productCount}\n";
}

// 7. Verificar si el EmpresaScope está funcionando
echo "\n7. Prueba del EmpresaScope:\n";
$totalSalesGlobal = Sale::withoutGlobalScopes()->count();
echo "   - Ventas totales (sin scope): {$totalSalesGlobal}\n";

// Simular autenticación con diferentes usuarios
$testUsers = User::whereNotNull('empresa_id')->take(2)->get();
foreach ($testUsers as $user) {
    auth()->login($user);
    $salesWithScope = Sale::count();
    echo "   - Ventas para user '{$user->email}' (empresa_id: {$user->empresa_id}): {$salesWithScope}\n";
    auth()->logout();
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n\n";

echo "RECOMENDACIONES:\n";
echo "1. Si hay usuarios sin empresa_id, asignarles uno manualmente o con un script\n";
echo "2. Si hay ventas/productos sin empresa_id, ejecutar un script de corrección\n";
echo "3. Verificar que el middleware de autenticación siempre valide empresa_id\n";
echo "4. Revisar que no se use withoutGlobalScopes() en controladores/vistas\n\n";
