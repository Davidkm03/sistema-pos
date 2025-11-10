<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Sale extends Model
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empresa_id',
        'user_id',
        'customer_id',
        'total',
        'subtotal',
        'tax_amount',
        'retention_amount',
        'payment_method',
        'status',
        'document_type',
        'receipt_number',
        'invoice_number',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'corrected_sale_id',
        'original_sale_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'retention_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer that owns the sale.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the sale items for the sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Alias for items relationship
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the payment details for the sale.
     */
    public function paymentDetails(): HasMany
    {
        return $this->hasMany(PaymentDetail::class);
    }

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

    /**
     * Calculate total profit (ganancia) for this sale
     * Profit = (price - cost) * quantity for each item
     */
    public function getTotalProfit(): float
    {
        $totalProfit = 0;

        foreach ($this->saleItems as $item) {
            if ($item->product) {
                // Profit = (selling price - product cost) * quantity
                $itemProfit = ($item->price - $item->product->cost) * $item->quantity;
                $totalProfit += $itemProfit;
            }
        }

        return $totalProfit;
    }

    /**
     * Scope a query to only include completed sales.
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', 'completada');
    }

    /**
     * Scope a query to only include today's sales.
     */
    public function scopeToday(Builder $query): void
    {
        $query->whereDate('created_at', Carbon::today());
    }

    // ==========================================
    // MÉTODOS PARA CÁLCULO DE IMPUESTOS
    // ==========================================

    /**
     * Calcular totales de la venta basándose en los items
     */
    public function calculateTotals(): array
    {
        $subtotal = 0;
        $taxByRate = []; // IVA agrupado por tasa
        $totalTax = 0;
        
        foreach ($this->saleItems as $item) {
            $subtotal += $item->subtotal;
            $totalTax += $item->tax_amount;
            
            // Agrupar IVA por tasa
            $rate = $item->tax_rate;
            if (!isset($taxByRate[$rate])) {
                $taxByRate[$rate] = 0;
            }
            $taxByRate[$rate] += $item->tax_amount;
        }

        $retentionAmount = 0;
        if ($this->customer) {
            $retentionAmount = $this->customer->calculateRetention($subtotal + $totalTax);
        }

        $total = $subtotal + $totalTax - $retentionAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($totalTax, 2),
            'tax_by_rate' => $taxByRate,
            'retention_amount' => round($retentionAmount, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Actualizar los totales de la venta
     */
    public function updateTotals(): void
    {
        $totals = $this->calculateTotals();
        
        $this->subtotal = $totals['subtotal'];
        $this->tax_amount = $totals['tax_amount'];
        $this->retention_amount = $totals['retention_amount'];
        $this->total = $totals['total'];
        
        $this->save();
    }

    /**
     * Obtener desglose de IVA por tasa
     */
    public function getTaxBreakdown(): array
    {
        $breakdown = [];
        
        foreach ($this->saleItems as $item) {
            $rate = $item->tax_rate;
            
            if (!isset($breakdown[$rate])) {
                $breakdown[$rate] = [
                    'rate' => $rate,
                    'subtotal' => 0,
                    'tax' => 0,
                ];
            }
            
            $breakdown[$rate]['subtotal'] += $item->subtotal;
            $breakdown[$rate]['tax'] += $item->tax_amount;
        }

        return array_values($breakdown);
    }

    /**
     * Obtener items agrupados por tipo de IVA
     */
    public function getItemsByTaxType(): array
    {
        $groups = [
            'standard' => [],
            'exempt' => [],
            'excluded' => [],
        ];

        foreach ($this->saleItems as $item) {
            if ($item->product) {
                $taxType = $item->product->tax_type ?? 'standard';
                $groups[$taxType][] = $item;
            }
        }

        return $groups;
    }

    /**
     * Verificar si la venta tiene retención
     */
    public function hasRetention(): bool
    {
        return $this->retention_amount > 0;
    }

    /**
     * Obtener información de métodos de pago
     */
    public function getPaymentInfo(): string
    {
        // Si tiene payment_details, usar esos
        if ($this->paymentDetails->isNotEmpty()) {
            return $this->paymentDetails->map(function ($detail) {
                return $detail->full_details . ': ' . format_currency($detail->amount);
            })->join(', ');
        }

        // Sino, usar el payment_method simple
        $methods = get_payment_methods();
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Verificar si es pago mixto
     */
    public function hasMixedPayment(): bool
    {
        return $this->paymentDetails->count() > 1;
    }

    /**
     * Obtener el total formateado con toda la información
     */
    public function getFormattedTotalInfo(): array
    {
        return [
            'subtotal' => format_currency($this->subtotal),
            'tax_amount' => format_currency($this->tax_amount),
            'retention_amount' => format_currency($this->retention_amount),
            'total' => format_currency($this->total),
            'tax_breakdown' => $this->getTaxBreakdown(),
        ];
    }

    // ==========================================
    // MÉTODOS DE ESTADO
    // ==========================================

    /**
     * Verificar si la venta está completada
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ['completed', 'completada']);
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
            'completada' => '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Completada</span>',
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

                $unitPrice = $product->getPriceWithoutTax();
                $taxRate = $product->getEffectiveTaxRate();
                $taxAmt = calculate_tax($unitPrice * $itemData['quantity'], $taxRate);
                $itemSubtotal = $unitPrice * $itemData['quantity'];

                $item = SaleItem::create([
                    'sale_id' => $newSale->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmt,
                    'subtotal' => $itemSubtotal,
                    'total' => $itemSubtotal + $taxAmt,
                    'price' => $product->price,
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
    // SCOPES ADICIONALES
    // ==========================================

    /**
     * Scope para ventas anuladas
     */
    public function scopeCancelled(Builder $query): void
    {
        $query->where('status', 'cancelled');
    }

    /**
     * Scope para ventas del usuario actual
     */
    public function scopeOwnSales(Builder $query, $userId = null): void
    {
        $userId = $userId ?? auth()->id();
        $query->where('user_id', $userId);
    }

    /**
     * Scope para ventas del mes actual
     */
    public function scopeThisMonth(Builder $query): void
    {
        $query->whereYear('created_at', now()->year)
              ->whereMonth('created_at', now()->month);
    }

    // ==========================================
    // MÉTODOS DE NUMERACIÓN DE DOCUMENTOS
    // ==========================================

    /**
     * Obtener el siguiente número de recibo (por empresa)
     */
    public static function getNextReceiptNumber(): int
    {
        $settings = BusinessSetting::current();
        $empresaId = auth()->user()->empresa_id;

        $lastSale = self::withoutGlobalScopes()
                        ->where('empresa_id', $empresaId)
                        ->where('document_type', 'receipt')
                        ->whereNotNull('receipt_number')
                        ->orderByRaw('CAST(receipt_number AS INTEGER) DESC')
                        ->first();

        if (!$lastSale) {
            return (int) ($settings->receipt_counter ?? 1);
        }

        return (int) $lastSale->receipt_number + 1;
    }

    /**
     * Obtener el siguiente número de factura (validando rango DIAN) por empresa
     */
    public static function getNextInvoiceNumber(): int
    {
        $settings = BusinessSetting::current();
        $empresaId = auth()->user()->empresa_id;

        // Verificar que la configuración de facturación esté completa
        if (!$settings->range_from || !$settings->range_to) {
            throw new \Exception('La configuración de facturación no está completa. Configure el rango autorizado por la DIAN.');
        }

        $lastInvoice = self::withoutGlobalScopes()
                          ->where('empresa_id', $empresaId)
                          ->where('document_type', 'invoice')
                          ->whereNotNull('invoice_number')
                          ->orderByRaw('CAST(invoice_number AS INTEGER) DESC')
                          ->first();

        $nextNumber = $lastInvoice ? ((int) $lastInvoice->invoice_number + 1) : (int) $settings->range_from;

        // Validar que no exceda el rango
        if ($nextNumber > $settings->range_to) {
            throw new \Exception('Rango de facturación agotado. Debe solicitar una nueva resolución a la DIAN.');
        }

        // Alertar si está cerca del límite (80%)
        $rangeSize = $settings->range_to - $settings->range_from + 1;
        $used = $nextNumber - $settings->range_from + 1;
        $percentage = ($used / $rangeSize) * 100;

        if ($percentage > 80) {
            // Aquí podrías disparar una notificación
            \Log::warning("Rango de facturación al {$percentage}%. Quedan " . ($settings->range_to - $nextNumber) . " facturas.");
        }

        return $nextNumber;
    }

    /**
     * Obtener el porcentaje de uso del rango de facturación
     */
    public static function getInvoiceRangeUsagePercentage(): float
    {
        $settings = BusinessSetting::current();

        if (!$settings->range_from || !$settings->range_to) {
            return 0;
        }

        $lastInvoice = self::where('document_type', 'invoice')
                          ->whereNotNull('invoice_number')
                          ->orderByRaw('CAST(invoice_number AS INTEGER) DESC')
                          ->first();

        if (!$lastInvoice) {
            return 0;
        }

        $rangeSize = $settings->range_to - $settings->range_from + 1;
        $used = $lastInvoice->invoice_number - $settings->range_from + 1;

        return round(($used / $rangeSize) * 100, 2);
    }

    /**
     * Obtener el número de documento formateado
     */
    public function getFormattedDocumentNumber(): string
    {
        $settings = BusinessSetting::current();

        if ($this->document_type === 'receipt' && $this->receipt_number) {
            return ($settings->receipt_prefix ?? 'RV') . '-' . str_pad($this->receipt_number, 6, '0', STR_PAD_LEFT);
        }

        if ($this->document_type === 'invoice' && $this->invoice_number) {
            return ($settings->invoice_prefix ?? 'FV') . '-' . str_pad($this->invoice_number, 6, '0', STR_PAD_LEFT);
        }

        return 'N/A';
    }

    /**
     * Obtener el nombre del tipo de documento
     */
    public function getDocumentTypeName(): string
    {
        $types = [
            'none' => 'Sin documento',
            'receipt' => 'Recibo de Venta',
            'invoice' => 'Factura',
            'electronic_invoice' => 'Factura Electrónica',
        ];

        return $types[$this->document_type] ?? $this->document_type;
    }
}
