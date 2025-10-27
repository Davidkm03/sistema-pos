<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleCancellationReason extends Model
{
    protected $fillable = [
        'reason',
        'requires_admin_approval',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'requires_admin_approval' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope para solo razones activas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope ordenado
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('reason');
    }

    /**
     * Obtener razones para select
     */
    public static function forSelect(): array
    {
        return self::active()
            ->ordered()
            ->pluck('reason', 'id')
            ->toArray();
    }

    /**
     * Verificar si requiere aprobación de admin
     */
    public function requiresApproval(): bool
    {
        return $this->requires_admin_approval;
    }
}
