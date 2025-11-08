<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosController;
use App\Livewire\TicketSettings;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rutas de POS - Accesibles por Admin, Supervisor y Cajero
    Route::middleware(['permission:access-pos'])->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::get('/pos/mobile', [PosController::class, 'mobile'])->name('pos.mobile');
        Route::get('/pos/load-more', [PosController::class, 'loadMoreProducts'])->name('pos.load-more');
        Route::post('/pos/search', [PosController::class, 'searchProducts'])
            ->middleware('throttle:60,1') // 60 búsquedas por minuto
            ->name('pos.search');
        Route::post('/pos/procesar-venta', [PosController::class, 'procesarVenta'])
            ->middleware(['permission:process-sales', 'throttle:30,1']) // 30 ventas por minuto
            ->name('pos.procesar-venta');
    });
    
    // Ruta para ver ticket de venta
    Route::get('/ventas/{id}/ticket', [PosController::class, 'ticket'])->name('sales.ticket');
    
    // Rutas de Productos - Requiere permiso view-products
    Route::middleware(['permission:view-products'])->group(function () {
        Route::get('/productos', function () {
            return view('products.index');
        })->name('products.index');
    });
    
    // Rutas de Inventario - Requiere permiso view-inventory
    Route::middleware(['permission:view-inventory'])->group(function () {
        Route::get('/inventario', function () {
            return view('inventory');
        })->name('inventory.index');
    });
    
    // Rutas de Gestión de Ventas (Anulación y Corrección) - DEBE IR ANTES de /ventas/{id}
    Route::middleware(['permission:cancel-own-sales|cancel-any-sales'])->group(function () {
        Route::get('/ventas/gestion', \App\Livewire\SaleManager::class)->name('sales.manager');
    });

    // Rutas de Log de Auditoría - DEBE IR ANTES de /ventas/{id}
    Route::middleware(['permission:view-audit-log'])->group(function () {
        Route::get('/ventas/auditoria', \App\Livewire\AuditLog::class)->name('sales.audit');
    });

    // Rutas de Ventas - Requiere permiso view-sales
    Route::middleware(['permission:view-sales|view-all-sales'])->group(function () {
        Route::get('/ventas', function () {
            return view('sales.index');
        })->name('sales.index');

        Route::get('/ventas/{id}', [PosController::class, 'show'])->name('sales.show');
    });
    
    // Rutas de Reportes - Solo Admin y Supervisor
    Route::middleware(['permission:view-reports'])->group(function () {
        Route::get('/reportes', function () {
            return view('reports.index');
        })->name('reports.index');
        
        Route::get('/reportes/periodo', function () {
            $fechaDesde = request('fecha_desde');
            $fechaHasta = request('fecha_hasta');
            
            $ventas = \App\Models\Sale::whereBetween('created_at', [
                $fechaDesde . ' 00:00:00',
                $fechaHasta . ' 23:59:59'
            ])->get();
            
            return response()->json([
                'total_ventas' => $ventas->count(),
                'total_ingresos' => $ventas->sum('total')
            ]);
        })->name('reports.periodo');
    });
    
    // Rutas de Metas - Requiere permiso view-goals
    Route::middleware(['permission:view-goals'])->group(function () {
        Route::get('/metas', function () {
            return view('goals.index');
        })->name('goals.index');
    });
    
    // Rutas de Configuración - Solo Admin y Supervisor
    Route::middleware(['permission:manage-settings'])->group(function () {
        Route::get('/configuracion/tickets', TicketSettings::class)->name('settings.tickets');
    });
    
    // Configuración General del Sistema - Todos los usuarios
    Route::get('/configuracion', function () {
        return view('settings.main');
    })->name('settings.index');
    
    // Configuración de Negocio - Todos los usuarios pueden configurar su negocio
    Route::get('/configuracion/negocio', function () {
        return view('settings.business');
    })->name('settings.business');
    
    // Rutas de Gestión de Usuarios - Solo Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/usuarios', function () {
            return view('users.index');
        })->name('users.index');
    });
    
    // Rutas de Administración de Roles y Permisos - Solo Super Admin
    Route::middleware(['role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/roles', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}/edit', [App\Http\Controllers\RolePermissionController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [App\Http\Controllers\RolePermissionController::class, 'update'])->name('roles.update');
        Route::post('/roles', [App\Http\Controllers\RolePermissionController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{role}', [App\Http\Controllers\RolePermissionController::class, 'destroy'])->name('roles.destroy');
    });
});

require __DIR__.'/auth.php';