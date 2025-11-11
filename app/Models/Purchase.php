<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($purchase) {
            if (!$purchase->empresa_id) {
                $purchase->empresa_id = Auth::user()->empresa_id;
            }
            if (!$purchase->user_id) {
                $purchase->user_id = Auth::id();
            }
            if (!$purchase->purchase_number) {
                $purchase->purchase_number = self::generatePurchaseNumber();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empresa_id',
        'supplier_id',
        'user_id',
        'purchase_number',
        'purchase_date',
        'status',
        'subtotal',
        'tax',
        'total',
        'notes',
        'received_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'purchase_date' => 'date',
        'received_at' => 'datetime',
    ];

    /**
     * Get the empresa that owns the purchase.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Get the supplier that owns the purchase.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user that created the purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the purchase.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Generate a unique purchase number.
     */
    public static function generatePurchaseNumber(): string
    {
        $empresaId = Auth::user()->empresa_id;
        $prefix = 'PC';
        $year = date('Y');
        $month = date('m');
        
        $lastPurchase = self::withoutGlobalScope(EmpresaScope::class)
            ->where('empresa_id', $empresaId)
            ->where('purchase_number', 'like', "{$prefix}-{$year}{$month}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->purchase_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s%s%04d', $prefix, $year, $month, $newNumber);
    }

    /**
     * Scope for pending purchases.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for received purchases.
     */
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    /**
     * Scope for cancelled purchases.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'received' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'received' => 'Recibida',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }
}
