<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }

    protected $fillable = [
        'empresa_id',
        'quote_number',
        'customer_id',
        'user_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'valid_until',
        'notes',
        'converted_to_sale_id',
        'converted_at'
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'converted_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function convertedSale()
    {
        return $this->belongsTo(Sale::class, 'converted_to_sale_id');
    }

    // Métodos útiles
    public function isConvertible()
    {
        return $this->status === 'pendiente' || $this->status === 'aprobada';
    }

    public function isExpired()
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'aprobada' => 'bg-green-100 text-green-800',
            'rechazada' => 'bg-red-100 text-red-800',
            'convertida' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'convertida' => 'Convertida a Venta',
            default => $this->status,
        };
    }

    // Generar número de cotización único por empresa
    public static function generateQuoteNumber()
    {
        $empresaId = auth()->user()->empresa_id;
        
        // Obtener la última cotización de esta empresa
        $lastQuote = self::withoutGlobalScopes()
            ->where('empresa_id', $empresaId)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastQuote ? intval(substr($lastQuote->quote_number, 3)) + 1 : 1;
        return 'QT-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
