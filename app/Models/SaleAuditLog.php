<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleAuditLog extends Model
{
    // Solo created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'sale_id',
        'action',
        'performed_by',
        'reason',
        'detailed_reason',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con la venta
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Relación con el usuario que realizó la acción
     */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Alias para performedBy (compatibilidad)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Obtener el nombre de la acción
     */
    public function getActionNameAttribute(): string
    {
        $actions = [
            'created' => 'Creada',
            'cancelled' => 'Anulada',
            'corrected' => 'Corregida',
            'modified' => 'Modificada',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Obtener badge de color según acción
     */
    public function getActionBadgeClassAttribute(): string
    {
        $classes = [
            'created' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'corrected' => 'bg-blue-100 text-blue-800',
            'modified' => 'bg-yellow-100 text-yellow-800',
        ];

        return $classes[$this->action] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Crear log de auditoría
     */
    public static function log(
        Sale $sale,
        string $action,
        ?string $reason = null,
        ?array $oldData = null,
        ?array $newData = null
    ): self {
        return self::create([
            'sale_id' => $sale->id,
            'action' => $action,
            'performed_by' => auth()->id(),
            'reason' => $reason,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Scope para filtrar por acción
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('performed_by', $userId);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
