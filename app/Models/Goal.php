<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Goal extends Model
{
    protected $fillable = [
        'name',
        'target_amount',
        'start_date',
        'end_date',
        'status',
        'user_id',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relación con User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get current profit within goal period
     */
    public function getCurrentProfit(): float
    {
        $sales = Sale::whereBetween('created_at', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ])
            ->with('saleItems.product')
            ->get();

        $totalProfit = 0;

        foreach ($sales as $sale) {
            $totalProfit += $sale->getTotalProfit();
        }

        return $totalProfit;
    }

    /**
     * Calcular el porcentaje de progreso
     */
    public function getProgressPercentage(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        $currentProfit = $this->getCurrentProfit();
        $percentage = ($currentProfit / $this->target_amount) * 100;

        return min($percentage, 100); // Máximo 100%
    }

    /**
     * Verificar si la meta está completada
     */
    public function isCompleted(): bool
    {
        return $this->getCurrentProfit() >= $this->target_amount;
    }

    /**
     * Obtener días restantes
     */
    public function getDaysRemaining(): int
    {
        $today = Carbon::today();
        $endDate = Carbon::parse($this->end_date);

        if ($today->greaterThan($endDate)) {
            return 0;
        }

        return $today->diffInDays($endDate);
    }

    /**
     * Verificar si la meta está vencida
     */
    public function isExpired(): bool
    {
        return Carbon::today()->greaterThan($this->end_date);
    }

    /**
     * Calcular ganancia diaria promedio necesaria para cumplir la meta
     */
    public function getDailyProfitNeeded(): float
    {
        $daysRemaining = $this->getDaysRemaining();
        
        if ($daysRemaining <= 0) {
            return 0;
        }

        $currentProfit = $this->getCurrentProfit();
        $remaining = $this->target_amount - $currentProfit;

        if ($remaining <= 0) {
            return 0;
        }

        return $remaining / $daysRemaining;
    }

    /**
     * Obtener monto restante para alcanzar la meta
     */
    public function getRemainingAmount(): float
    {
        $remaining = $this->target_amount - $this->getCurrentProfit();
        return max($remaining, 0);
    }

    /**
     * Scope para metas activas
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para metas en el período actual
     */
    public function scopeCurrent(Builder $query): Builder
    {
        $today = Carbon::today();
        
        return $query->where('status', 'active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    /**
     * Scope para metas completadas
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para metas canceladas
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }
}
