<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentDetail extends Model
{
    protected $fillable = [
        'sale_id',
        'payment_method',
        'transfer_type',
        'transfer_reference',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relación con la venta
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Obtener el nombre del método de pago
     */
    public function getPaymentMethodNameAttribute(): string
    {
        $methods = get_payment_methods();
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Obtener el nombre del tipo de transferencia
     */
    public function getTransferTypeNameAttribute(): ?string
    {
        if (!$this->transfer_type) {
            return null;
        }

        $types = get_transfer_types();
        return $types[$this->transfer_type] ?? $this->transfer_type;
    }

    /**
     * Obtener detalles completos del pago
     */
    public function getFullDetailsAttribute(): string
    {
        $details = $this->payment_method_name;

        if ($this->payment_method === 'transferencia' && $this->transfer_type) {
            $details .= ' - ' . $this->transfer_type_name;
            
            if ($this->transfer_reference) {
                $details .= ' (Ref: ' . $this->transfer_reference . ')';
            }
        }

        return $details;
    }
}
