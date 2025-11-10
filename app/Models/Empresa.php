<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'rfc',
        'direccion',
        'telefono',
        'email',
        'logo',
        'sitio_web',
        'moneda',
        'iva_porcentaje',
        'activo',
    ];

    protected $casts = [
        'iva_porcentaje' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Relación con usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación con productos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relación con categorías
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relación con clientes
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Relación con ventas
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Relación con cotizaciones
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Relación con movimientos de inventario
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Relación con metas
     */
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Obtener la URL del logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo && \Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }
}
