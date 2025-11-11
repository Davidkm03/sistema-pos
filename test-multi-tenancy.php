<?php

/**
 * Test Manual de Multi-Tenancy
 * 
 * Ejecutar con: php test-multi-tenancy.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;

echo "\n=== TEST DE MULTI-TENANCY ===\n\n";

// Obtener usuarios de diferentes empresas
$users = User::whereNotNull('empresa_id')
    ->select('id', 'email', 'empresa_id', 'name')
    ->get()
    ->groupBy('empresa_id');

if ($users->count() < 2) {
    echo "⚠️  Necesitas al menos 2 empresas con usuarios para este test\n";
    exit(1);
}

echo "Empresas encontradas: " . $users->count() . "\n\n";

foreach ($users as $empresaId => $empresaUsers) {
    echo "--- EMPRESA ID: {$empresaId} ---\n";
    
    $user = $empresaUsers->first();
    echo "Probando con usuario: {$user->email}\n";
    
    // Simular login
    Auth::login($user);
    
    // Probar consultas
    $sales = Sale::count();
    $products = Product::count();
    $categories = Category::count();
    
    echo "  Ventas (con scope): {$sales}\n";
    echo "  Productos (con scope): {$products}\n";
    echo "  Categorías (con scope): {$categories}\n";
    
    // Probar consultas sin scope
    $allSales = Sale::withoutGlobalScopes()->where('empresa_id', $empresaId)->count();
    $allProducts = Product::withoutGlobalScopes()->where('empresa_id', $empresaId)->count();
    
    echo "  Ventas (sin scope, filtrado manual): {$allSales}\n";
    echo "  Productos (sin scope, filtrado manual): {$allProducts}\n";
    
    // Verificar que coincidan
    if ($sales === $allSales && $products === $allProducts) {
        echo "  ✅ SCOPE FUNCIONANDO CORRECTAMENTE\n";
    } else {
        echo "  ❌ ERROR: Los números no coinciden\n";
    }
    
    Auth::logout();
    echo "\n";
}

// Test de usuario SIN empresa_id (si existe)
echo "--- TEST: Usuario sin empresa_id ---\n";
$userWithoutEmpresa = User::whereNull('empresa_id')->first();

if ($userWithoutEmpresa) {
    echo "⚠️  Usuario sin empresa_id encontrado: {$userWithoutEmpresa->email}\n";
    Auth::login($userWithoutEmpresa);
    
    $sales = Sale::count();
    $products = Product::count();
    
    echo "  Ventas retornadas: {$sales}\n";
    echo "  Productos retornados: {$products}\n";
    
    if ($sales === 0 && $products === 0) {
        echo "  ✅ SCOPE BLOQUEÓ CORRECTAMENTE (retorna vacío)\n";
    } else {
        echo "  ❌ PROBLEMA DE SEGURIDAD: Se están retornando datos sin empresa_id\n";
    }
    
    Auth::logout();
} else {
    echo "✅ No hay usuarios sin empresa_id\n";
}

echo "\n=== FIN DEL TEST ===\n\n";
