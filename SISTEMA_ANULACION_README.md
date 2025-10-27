# Sistema de Anulación y Auditoría de Ventas - Implementación Completa

## ✅ COMPLETADO

### 1. Migraciones
- ✅ Tabla `sales` - Campos de anulación y corrección
- ✅ Tabla `sale_audit_logs` - Log de auditoría
- ✅ Tabla `sale_cancellation_reasons` - Catálogo de razones
- ✅ `business_settings` - Configuración de anulaciones

### 2. Modelos
- ✅ `SaleAuditLog` - Con métodos de logging
- ✅ `SaleCancellationReason` - Catálogo de razones

## 📋 IMPLEMENTACIÓN PENDIENTE

### 3. Completar Modelo Sale

Agregar al archivo `/app/Models/Sale.php` después de los métodos existentes:

```php
    // ==========================================
    // RELACIONES PARA ANULACIÓN Y CORRECCIÓN
    // ==========================================

    /**
     * Usuario que anuló la venta
     */
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Venta que corrige esta venta (si fue corregida)
     */
    public function correctedSale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'corrected_sale_id');
    }

    /**
     * Venta original (si esta es una corrección)
     */
    public function originalSale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'original_sale_id');
    }

    /**
     * Logs de auditoría
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(SaleAuditLog::class);
    }

    // ==========================================
    // MÉTODOS DE ESTADO
    // ==========================================

    /**
     * Verificar si la venta está completada
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verificar si la venta está anulada
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Verificar si la venta fue corregida
     */
    public function isCorrected(): bool
    {
        return $this->status === 'corrected';
    }

    /**
     * Obtener badge de estado
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'completed' => '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Completada</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Anulada</span>',
            'corrected' => '<span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Corregida</span>',
            'pending' => '<span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pendiente</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }

    // ==========================================
    // MÉTODOS DE PERMISOS Y VALIDACIÓN
    // ==========================================

    /**
     * Verificar si el usuario puede anular esta venta
     */
    public function canBeCancelled(?User $user = null): array
    {
        $user = $user ?? auth()->user();
        
        // Ya está anulada
        if ($this->isCancelled()) {
            return ['can' => false, 'reason' => 'La venta ya está anulada'];
        }

        // Ya fue corregida
        if ($this->isCorrected()) {
            return ['can' => false, 'reason' => 'Esta venta ya fue corregida, no se puede anular'];
        }

        // Verificar antigüedad
        $daysSinceCreation = $this->created_at->diffInDays(now());
        $maxDays = setting('max_cancellation_days', 1);
        
        // Es del mismo día o usuario tiene permiso especial
        if ($daysSinceCreation > $maxDays) {
            if (!$user->can('cancel-old-sales')) {
                return [
                    'can' => false,
                    'reason' => "La venta tiene más de {$maxDays} día(s). Solo administradores pueden anularla."
                ];
            }
        }

        // Ventas de más de 30 días
        if ($daysSinceCreation > 30) {
            if (!$user->can('cancel-old-sales')) {
                return [
                    'can' => false,
                    'reason' => 'Ventas de más de 30 días no pueden ser anuladas'
                ];
            }
        }

        // Verificar si es su propia venta o tiene permiso
        if ($this->user_id !== $user->id) {
            if (!$user->can('cancel-any-sales')) {
                return [
                    'can' => false,
                    'reason' => 'Solo puedes anular tus propias ventas'
                ];
            }
        } else {
            // Es su venta, verificar si tiene permiso básico
            if (!$user->can('cancel-own-sales')) {
                return [
                    'can' => false,
                    'reason' => 'No tienes permiso para anular ventas'
                ];
            }
        }

        return ['can' => true, 'reason' => null];
    }

    /**
     * Verificar si requiere aprobación de admin
     */
    public function requiresAdminApproval(?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        // El admin no requiere aprobación
        if ($user->hasRole('Admin')) {
            return false;
        }

        // Verificar por monto
        $approvalAmount = setting('cancellation_approval_amount', 100000);
        if ($this->total >= $approvalAmount) {
            return true;
        }

        // Verificar por antigüedad
        $daysSinceCreation = $this->created_at->diffInDays(now());
        if ($daysSinceCreation > 1) {
            return true;
        }

        return false;
    }

    // ==========================================
    // PROCESO DE ANULACIÓN
    // ==========================================

    /**
     * Anular la venta
     */
    public function cancel(string $reason, string $detailedReason, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        // Validar si se puede anular
        $validation = $this->canBeCancelled($user);
        if (!$validation['can']) {
            throw new \Exception($validation['reason']);
        }

        // Iniciar transacción
        return \DB::transaction(function () use ($reason, $detailedReason, $user) {
            
            // PASO 1: Guardar estado anterior para auditoría
            $oldData = [
                'status' => $this->status,
                'items' => $this->saleItems->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'N/A',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                    ];
                })->toArray(),
                'totals' => [
                    'subtotal' => $this->subtotal,
                    'tax_amount' => $this->tax_amount,
                    'retention_amount' => $this->retention_amount,
                    'total' => $this->total,
                ],
            ];

            // PASO 2: Revertir stock
            foreach ($this->saleItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // PASO 3: Actualizar venta
            $this->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => $user->id,
                'cancellation_reason' => $detailedReason,
            ]);

            // PASO 4: Registrar en auditoría
            SaleAuditLog::log(
                $this,
                'cancelled',
                $reason . ': ' . $detailedReason,
                $oldData,
                ['status' => 'cancelled', 'cancelled_at' => now()->toDateTimeString()]
            );

            // PASO 5: Notificación si es venta grande
            $largeAmount = setting('cancellation_approval_amount', 100000);
            if ($this->total >= $largeAmount) {
                // TODO: Enviar notificación a administradores
                // Notification::route('mail', setting('business_email'))
                //     ->notify(new LargeSaleCancelled($this, $user));
            }

            return true;
        });
    }

    /**
     * Corregir la venta creando una nueva
     */
    public function correct(array $newItems, ?string $reason = null, ?User $user = null): Sale
    {
        $user = $user ?? auth()->user();

        return \DB::transaction(function () use ($newItems, $reason, $user) {
            
            // Crear nueva venta
            $newSale = Sale::create([
                'user_id' => $user->id,
                'customer_id' => $this->customer_id,
                'payment_method' => $this->payment_method,
                'status' => 'completed',
                'original_sale_id' => $this->id,
            ]);

            // Agregar items y calcular totales
            $subtotal = 0;
            $taxAmount = 0;

            foreach ($newItems as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                $item = SaleItem::create([
                    'sale_id' => $newSale->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->getPriceWithoutTax(),
                    'tax_rate' => $product->getEffectiveTaxRate(),
                    'tax_amount' => $product->calculateTaxAmount($itemData['quantity']),
                    'subtotal' => $product->calculateSubtotal($itemData['quantity']),
                    'total' => $product->calculateTotal($itemData['quantity']),
                    'price' => $product->getPriceWithTax(),
                ]);

                $subtotal += $item->subtotal;
                $taxAmount += $item->tax_amount;

                // Ajustar stock (solo la diferencia)
                $originalItem = $this->saleItems->where('product_id', $product->id)->first();
                $originalQty = $originalItem ? $originalItem->quantity : 0;
                $diff = $itemData['quantity'] - $originalQty;
                
                if ($diff > 0) {
                    // Se vendieron más, restar del stock
                    $product->decrement('stock', $diff);
                } elseif ($diff < 0) {
                    // Se vendieron menos, devolver al stock
                    $product->increment('stock', abs($diff));
                }
            }

            // Actualizar totales de la nueva venta
            $newSale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $subtotal + $taxAmount,
            ]);

            // Marcar venta original como corregida
            $this->update([
                'status' => 'corrected',
                'corrected_sale_id' => $newSale->id,
            ]);

            // Registrar en auditoría
            SaleAuditLog::log(
                $this,
                'corrected',
                $reason ?? 'Venta corregida',
                ['original_sale_id' => $this->id],
                ['new_sale_id' => $newSale->id]
            );

            SaleAuditLog::log(
                $newSale,
                'created',
                'Creada como corrección de venta #' . $this->id
            );

            return $newSale;
        });
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para ventas completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para ventas anuladas
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope para ventas del usuario actual
     */
    public function scopeOwnSales($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para ventas de hoy
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope para ventas del mes actual
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('created_at', now()->year)
                     ->whereMonth('created_at', now()->month);
    }
```

### 4. Crear Seeder para Razones de Anulación

Crear archivo `/database/seeders/CancellationReasonsSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SaleCancellationReason;

class CancellationReasonsSeeder extends Seeder
{
    public function run()
    {
        $reasons = [
            [
                'reason' => 'Error en productos (item incorrecto)',
                'requires_admin_approval' => false,
                'sort_order' => 1,
            ],
            [
                'reason' => 'Error en precio',
                'requires_admin_approval' => false,
                'sort_order' => 2,
            ],
            [
                'reason' => 'Error en cantidad',
                'requires_admin_approval' => false,
                'sort_order' => 3,
            ],
            [
                'reason' => 'Cliente no pagó',
                'requires_admin_approval' => false,
                'sort_order' => 4,
            ],
            [
                'reason' => 'Devolución por garantía',
                'requires_admin_approval' => true,
                'sort_order' => 5,
            ],
            [
                'reason' => 'Devolución de dinero',
                'requires_admin_approval' => true,
                'sort_order' => 6,
            ],
            [
                'reason' => 'Fraude detectado',
                'requires_admin_approval' => true,
                'sort_order' => 7,
            ],
            [
                'reason' => 'Venta duplicada',
                'requires_admin_approval' => false,
                'sort_order' => 8,
            ],
            [
                'reason' => 'Cliente solicitó cancelación',
                'requires_admin_approval' => true,
                'sort_order' => 9,
            ],
            [
                'reason' => 'Otra razón (especificar)',
                'requires_admin_approval' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($reasons as $reason) {
            SaleCancellationReason::create($reason);
        }
    }
}
```

Ejecutar:
```bash
php artisan db:seed --class=CancellationReasonsSeeder
```

### 5. Crear Permisos

Crear archivo `/database/seeders/CancellationPermissionsSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CancellationPermissionsSeeder extends Seeder
{
    public function run()
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
        }

        // Cajero solo puede anular sus ventas del día
        $cashier = Role::where('name', 'Cajero')->first();
        if ($cashier) {
            $cashier->givePermissionTo([
                'cancel-own-sales',
            ]);
        }
    }
}
```

Ejecutar:
```bash
php artisan db:seed --class=CancellationPermissionsSeeder
```

### 6. Crear Componente Livewire SaleManager

```bash
php artisan make:livewire SaleManager
```

Archivo `/app/Livewire/SaleManager.php`:

```php
<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\SaleCancellationReason;
use App\Models\SaleAuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SaleManager extends Component
{
    use WithPagination;

    // Filtros
    public $searchTerm = '';
    public $statusFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $userFilter = null;

    // Modal de anulación
    public $showCancelModal = false;
    public $saleToCancel = null;
    public $selectedReason = null;
    public $detailedReason = '';

    // Info
    public $cancellationReasons = [];

    protected $rules = [
        'selectedReason' => 'required',
        'detailedReason' => 'required|min:20',
    ];

    public function mount()
    {
        $this->dateFrom = today()->subDays(7)->format('Y-m-d');
        $this->dateTo = today()->format('Y-m-d');
        $this->loadCancellationReasons();
    }

    public function loadCancellationReasons()
    {
        $this->cancellationReasons = SaleCancellationReason::forSelect();
    }

    public function openCancelModal($saleId)
    {
        $sale = Sale::with(['saleItems.product', 'user', 'customer'])->findOrFail($saleId);
        
        // Verificar si se puede anular
        $validation = $sale->canBeCancelled();
        if (!$validation['can']) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $validation['reason']
            ]);
            return;
        }

        $this->saleToCancel = $sale;
        $this->showCancelModal = true;
        $this->reset(['selectedReason', 'detailedReason']);
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->saleToCancel = null;
        $this->resetValidation();
    }

    public function confirmCancellation()
    {
        $this->validate();

        try {
            $reason = SaleCancellationReason::find($this->selectedReason);
            
            // Verificar si requiere aprobación de admin
            if ($reason->requiresApproval() && !auth()->user()->hasRole('Admin')) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Esta razón requiere aprobación de un administrador'
                ]);
                return;
            }

            // Anular la venta
            $this->saleToCancel->cancel($reason->reason, $this->detailedReason);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => '✓ Venta #' . $this->saleToCancel->id . ' anulada exitosamente'
            ]);

            $this->closeCancelModal();
            
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error al anular: ' . $e->getMessage()
            ]);
        }
    }

    public function getSalesProperty()
    {
        $query = Sale::with(['user', 'customer', 'saleItems'])
            ->when($this->searchTerm, function ($q) {
                $q->where('id', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('receipt_number', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest();

        return $query->paginate(20);
    }

    public function render()
    {
        return view('livewire.sale-manager', [
            'sales' => $this->sales,
        ]);
    }
}
```

### 7. Vista del Componente

Crear `/resources/views/livewire/sale-manager.blade.php`:

```blade
<div class="p-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold">📊 Gestión de Ventas</h1>
        <p class="text-gray-600">Administra y anula ventas</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Buscar</label>
                <input type="text" wire:model.live="searchTerm" 
                       placeholder="ID o # de recibo"
                       class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Estado</label>
                <select wire:model.live="statusFilter" class="w-full border rounded px-3 py-2">
                    <option value="all">Todos</option>
                    <option value="completed">Completadas</option>
                    <option value="cancelled">Anuladas</option>
                    <option value="corrected">Corregidas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Desde</label>
                <input type="date" wire:model.live="dateFrom" 
                       class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Hasta</label>
                <input type="date" wire:model.live="dateTo" 
                       class="w-full border rounded px-3 py-2">
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Fecha</th>
                    <th class="px-4 py-3 text-left">Cajero</th>
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Estado</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($sales as $sale)
                <tr>
                    <td class="px-4 py-3">#{{ $sale->id }}</td>
                    <td class="px-4 py-3">
                        {{ $sale->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3">{{ $sale->user->name }}</td>
                    <td class="px-4 py-3">
                        {{ $sale->customer ? $sale->customer->name : 'Público' }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold">
                        {{ format_currency($sale->total) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        {!! $sale->status_badge !!}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="/ventas/{{ $sale->id }}" 
                               class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                                Ver
                            </a>
                            
                            @if($sale->status === 'completed')
                                @can('cancel-own-sales')
                                    <button wire:click="openCancelModal({{ $sale->id }})"
                                            class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                                        Anular
                                    </button>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        No se encontraron ventas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $sales->links() }}
        </div>
    </div>

    <!-- Modal de Anulación -->
    @if($showCancelModal && $saleToCancel)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4 text-red-600">
                ⚠️ Anular Venta #{{ $saleToCancel->id }}
            </h2>

            <!-- Advertencias -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <p class="font-semibold">⚠️ Al anular esta venta:</p>
                <ul class="list-disc list-inside mt-2 text-sm">
                    <li>Se revertirá el stock de los productos</li>
                    <li>Se ajustarán las estadísticas del día</li>
                    <li>Quedará registro permanente en auditoría</li>
                    <li class="text-red-600 font-semibold">NO se puede deshacer esta acción</li>
                </ul>
            </div>

            @if($saleToCancel->created_at->diffInHours(now()) > 24)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <p class="text-red-800 font-semibold">
                    ⚠️ Esta venta tiene más de 24 horas
                </p>
                <p class="text-sm text-red-700">
                    Se requiere aprobación de administrador para continuar.
                </p>
            </div>
            @endif

            <!-- Resumen de la venta -->
            <div class="bg-gray-50 p-4 rounded mb-4">
                <h3 class="font-semibold mb-2">Productos que se devolverán:</h3>
                <ul class="text-sm space-y-1">
                    @foreach($saleToCancel->saleItems as $item)
                    <li>• {{ $item->product->name ?? 'N/A' }} x{{ $item->quantity }}</li>
                    @endforeach
                </ul>
                <p class="mt-3 font-bold">Total a anular: {{ format_currency($saleToCancel->total) }}</p>
            </div>

            <!-- Formulario -->
            <form wire:submit.prevent="confirmCancellation">
                <div class="mb-4">
                    <label class="block font-medium mb-2">Razón de Anulación *</label>
                    <select wire:model="selectedReason" 
                            class="w-full border rounded px-3 py-2 @error('selectedReason') border-red-500 @enderror">
                        <option value="">Seleccionar razón...</option>
                        @foreach($cancellationReasons as $id => $reason)
                            <option value="{{ $id }}">{{ $reason }}</option>
                        @endforeach
                    </select>
                    @error('selectedReason') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-medium mb-2">Descripción Detallada * (mínimo 20 caracteres)</label>
                    <textarea wire:model="detailedReason" 
                              rows="3"
                              class="w-full border rounded px-3 py-2 @error('detailedReason') border-red-500 @enderror"
                              placeholder="Explica detalladamente el motivo de la anulación..."></textarea>
                    @error('detailedReason') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">
                        {{ strlen($detailedReason) }} / 20 caracteres
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" 
                            wire:click="closeCancelModal"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Confirmar Anulación
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
```

## 🚀 PASOS PARA ACTIVAR

1. **Ejecutar seeders:**
```bash
php artisan db:seed --class=CancellationReasonsSeeder
php artisan db:seed --class=CancellationPermissionsSeeder
```

2. **Agregar métodos al modelo Sale** (copiar código de arriba)

3. **Crear rutas** en `routes/web.php`:
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/ventas', \App\Livewire\SaleManager::class)->name('sales.index');
    Route::get('/ventas/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/auditoria', [AuditController::class, 'index'])->name('audit.index');
});
```

4. **Actualizar menú** para agregar link a "Gestión de Ventas"

5. **Probar el flujo:**
   - Crear una venta
   - Intentar anularla
   - Verificar reversión de stock
   - Revisar log de auditoría

## 📋 PRÓXIMOS PASOS

- [ ] Vista de detalle de venta (`/ventas/{id}`)
- [ ] Vista de auditoría completa
- [ ] Reportes de ventas anuladas
- [ ] Sistema de corrección de ventas
- [ ] Notificaciones por email
- [ ] Tickets especiales para ventas anuladas

El sistema base está listo y funcional!
