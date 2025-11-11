<?php

/**
 * Script para corregir datos sin empresa_id en producción
 * 
 * ADVERTENCIA: Este script modifica la base de datos
 * Ejecutar SOLO si el diagnóstico encontró problemas
 * 
 * Ejecutar con: php fix-empresa-id.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Empresa;

echo "\n=== CORRECCIÓN DE DATOS SIN empresa_id ===\n\n";
echo "⚠️  ADVERTENCIA: Este script modificará la base de datos\n";
echo "Presiona ENTER para continuar o Ctrl+C para cancelar...\n";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);

echo "\n--- Paso 1: Verificar empresas disponibles ---\n";
$empresas = Empresa::all();
if ($empresas->count() === 0) {
    echo "❌ ERROR: No hay empresas en el sistema\n";
    echo "Debes crear al menos una empresa primero\n";
    exit(1);
}

echo "Empresas encontradas:\n";
foreach ($empresas as $emp) {
    echo "  {$emp->id}. {$emp->nombre}\n";
}

// Determinar empresa por defecto (la primera)
$empresaDefault = $empresas->first();
echo "\nEmpresa por defecto: {$empresaDefault->nombre} (ID: {$empresaDefault->id})\n";

echo "\n--- Paso 2: Corregir usuarios sin empresa_id ---\n";
$usersWithoutEmpresa = User::whereNull('empresa_id')->get();

if ($usersWithoutEmpresa->count() > 0) {
    echo "Encontrados {$usersWithoutEmpresa->count()} usuarios sin empresa_id:\n";
    
    foreach ($usersWithoutEmpresa as $user) {
        echo "  - {$user->email} (ID: {$user->id})\n";
        echo "    ¿Asignar empresa_id = {$empresaDefault->id}? (y/n/[numero]): ";
        $handle = fopen ("php://stdin","r");
        $response = trim(fgets($handle));
        
        if ($response === 'y' || $response === '') {
            $user->empresa_id = $empresaDefault->id;
            $user->save();
            echo "    ✅ Asignado empresa_id = {$empresaDefault->id}\n";
        } elseif (is_numeric($response)) {
            $empresaId = (int) $response;
            if (Empresa::find($empresaId)) {
                $user->empresa_id = $empresaId;
                $user->save();
                echo "    ✅ Asignado empresa_id = {$empresaId}\n";
            } else {
                echo "    ❌ Empresa ID {$empresaId} no existe. Saltando...\n";
            }
        } else {
            echo "    ⏭️  Saltado\n";
        }
    }
} else {
    echo "✅ Todos los usuarios tienen empresa_id\n";
}

echo "\n--- Paso 3: Corregir ventas sin empresa_id ---\n";
$salesWithoutEmpresa = Sale::withoutGlobalScopes()->whereNull('empresa_id')->get();

if ($salesWithoutEmpresa->count() > 0) {
    echo "Encontradas {$salesWithoutEmpresa->count()} ventas sin empresa_id\n";
    echo "Se asignará la empresa_id del usuario que hizo la venta\n";
    
    foreach ($salesWithoutEmpresa as $sale) {
        if ($sale->user && $sale->user->empresa_id) {
            $sale->empresa_id = $sale->user->empresa_id;
            $sale->save();
            echo "  ✅ Venta #{$sale->id} → empresa_id = {$sale->empresa_id}\n";
        } else {
            echo "  ⚠️  Venta #{$sale->id} - Usuario no tiene empresa. Asignando default: {$empresaDefault->id}\n";
            $sale->empresa_id = $empresaDefault->id;
            $sale->save();
        }
    }
} else {
    echo "✅ Todas las ventas tienen empresa_id\n";
}

echo "\n--- Paso 4: Corregir productos sin empresa_id ---\n";
$productsWithoutEmpresa = Product::withoutGlobalScopes()->whereNull('empresa_id')->get();

if ($productsWithoutEmpresa->count() > 0) {
    echo "Encontrados {$productsWithoutEmpresa->count()} productos sin empresa_id\n";
    echo "¿Asignar empresa por defecto ({$empresaDefault->nombre})? (y/n): ";
    $handle = fopen ("php://stdin","r");
    $response = trim(fgets($handle));
    
    if ($response === 'y') {
        Product::withoutGlobalScopes()
            ->whereNull('empresa_id')
            ->update(['empresa_id' => $empresaDefault->id]);
        echo "✅ Productos actualizados\n";
    } else {
        echo "⏭️  Saltado\n";
    }
} else {
    echo "✅ Todos los productos tienen empresa_id\n";
}

echo "\n--- Paso 5: Corregir categorías sin empresa_id ---\n";
$categoriesWithoutEmpresa = Category::withoutGlobalScopes()->whereNull('empresa_id')->get();

if ($categoriesWithoutEmpresa->count() > 0) {
    echo "Encontradas {$categoriesWithoutEmpresa->count()} categorías sin empresa_id\n";
    echo "¿Asignar empresa por defecto ({$empresaDefault->nombre})? (y/n): ";
    $handle = fopen ("php://stdin","r");
    $response = trim(fgets($handle));
    
    if ($response === 'y') {
        Category::withoutGlobalScopes()
            ->whereNull('empresa_id')
            ->update(['empresa_id' => $empresaDefault->id]);
        echo "✅ Categorías actualizadas\n";
    } else {
        echo "⏭️  Saltado\n";
    }
} else {
    echo "✅ Todas las categorías tienen empresa_id\n";
}

echo "\n--- Paso 6: Corregir clientes sin empresa_id ---\n";
$customersWithoutEmpresa = Customer::withoutGlobalScopes()->whereNull('empresa_id')->get();

if ($customersWithoutEmpresa->count() > 0) {
    echo "Encontrados {$customersWithoutEmpresa->count()} clientes sin empresa_id\n";
    echo "¿Asignar empresa por defecto ({$empresaDefault->nombre})? (y/n): ";
    $handle = fopen ("php://stdin","r");
    $response = trim(fgets($handle));
    
    if ($response === 'y') {
        Customer::withoutGlobalScopes()
            ->whereNull('empresa_id')
            ->update(['empresa_id' => $empresaDefault->id]);
        echo "✅ Clientes actualizados\n";
    } else {
        echo "⏭️  Saltado\n";
    }
} else {
    echo "✅ Todos los clientes tienen empresa_id\n";
}

echo "\n--- Paso 7: Corregir proveedores sin empresa_id ---\n";
$suppliersWithoutEmpresa = Supplier::withoutGlobalScopes()->whereNull('empresa_id')->get();

if ($suppliersWithoutEmpresa->count() > 0) {
    echo "Encontrados {$suppliersWithoutEmpresa->count()} proveedores sin empresa_id\n";
    echo "¿Asignar empresa por defecto ({$empresaDefault->nombre})? (y/n): ";
    $handle = fopen ("php://stdin","r");
    $response = trim(fgets($handle));
    
    if ($response === 'y') {
        Supplier::withoutGlobalScopes()
            ->whereNull('empresa_id')
            ->update(['empresa_id' => $empresaDefault->id]);
        echo "✅ Proveedores actualizados\n";
    } else {
        echo "⏭️  Saltado\n";
    }
} else {
    echo "✅ Todos los proveedores tienen empresa_id\n";
}

echo "\n=== CORRECCIÓN COMPLETADA ===\n";
echo "\nPróximos pasos:\n";
echo "1. Ejecutar: php diagnose-empresa-scope.php (para verificar)\n";
echo "2. Limpiar cache: php artisan cache:clear\n";
echo "3. Cerrar todas las sesiones: php artisan tinker → \\DB::table('sessions')->truncate();\n";
echo "4. Probar login con diferentes usuarios\n\n";
