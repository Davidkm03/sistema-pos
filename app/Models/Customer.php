<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
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
        'name',
        'phone',
        'email',
        'tax_id_type',
        'tax_id',
        'tax_regime',
        'is_retention_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_retention_agent' => 'boolean',
    ];

    /**
     * Get the sales for the customer.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the quotes for the customer.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Get the empresa that owns the customer.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Get customer statistics.
     */
    public function getStatistics(): array
    {
        $totalPurchases = $this->sales()->count();
        $totalSpent = $this->sales()->sum('total');
        $lastPurchase = $this->sales()->latest()->first();
        $averageTicket = $totalPurchases > 0 ? $totalSpent / $totalPurchases : 0;

        return [
            'total_purchases' => $totalPurchases,
            'total_spent' => $totalSpent,
            'average_ticket' => $averageTicket,
            'last_purchase_date' => $lastPurchase?->created_at,
        ];
    }

    // ==========================================
    // MÉTODOS TRIBUTARIOS
    // ==========================================

    /**
     * Verificar si el cliente requiere retención
     */
    public function requiresRetention(float $saleAmount): bool
    {
        if (!retention_enabled()) {
            return false;
        }

        // Verificar si es agente de retención o régimen común
        if (!$this->is_retention_agent && $this->tax_regime !== 'common') {
            return false;
        }

        // Verificar si el monto supera el mínimo
        $minAmount = (float) setting('applies_retention_from', 800000);
        return $saleAmount >= $minAmount;
    }

    /**
     * Calcular retención para un monto
     */
    public function calculateRetention(float $amount): float
    {
        if (!$this->requiresRetention($amount)) {
            return 0;
        }

        return calculate_retention($amount, $this);
    }

    /**
     * Obtener NIT formateado
     */
    public function getFormattedTaxId(): string
    {
        if (!$this->tax_id) {
            return 'N/A';
        }

        if ($this->tax_id_type === 'NIT') {
            return format_nit($this->tax_id);
        }

        return $this->tax_id;
    }

    /**
     * Validar NIT si corresponde
     */
    public function validateTaxId(): bool
    {
        if (!$this->tax_id) {
            return true; // No requerido
        }

        if ($this->tax_id_type === 'NIT') {
            return validate_nit($this->tax_id);
        }

        return true; // Otros tipos de documento no se validan
    }

    /**
     * Obtener nombre del régimen tributario
     */
    public function getTaxRegimeName(): string
    {
        $regimes = get_tax_regimes();
        return $regimes[$this->tax_regime] ?? $this->tax_regime;
    }

    /**
     * Obtener nombre del tipo de documento
     */
    public function getTaxIdTypeName(): string
    {
        $types = get_tax_id_types();
        return $types[$this->tax_id_type] ?? $this->tax_id_type;
    }

    /**
     * Obtener información fiscal completa
     */
    public function getTaxInfo(): array
    {
        return [
            'tax_id_type' => $this->tax_id_type,
            'tax_id_type_name' => $this->getTaxIdTypeName(),
            'tax_id' => $this->tax_id,
            'tax_id_formatted' => $this->getFormattedTaxId(),
            'tax_regime' => $this->tax_regime,
            'tax_regime_name' => $this->getTaxRegimeName(),
            'is_retention_agent' => $this->is_retention_agent,
        ];
    }

    /**
     * Scope para clientes con retención
     */
    public function scopeWithRetention($query)
    {
        return $query->where(function ($q) {
            $q->where('is_retention_agent', true)
              ->orWhere('tax_regime', 'common');
        });
    }
}
