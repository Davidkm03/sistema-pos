<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
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
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'price',
        'cost',
        'stock',
        'image',
        'tax_type',
        'tax_rate',
        'is_featured',
        'is_on_sale',
        'sale_price',
        'discount_percentage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'integer',
        'tax_rate' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_on_sale' => 'boolean',
        'sale_price' => 'decimal:2',
        'discount_percentage' => 'integer',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the empresa that owns the product.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Get the sale items for the product.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the formatted price attribute.
     */
    public function getPriceFormattedAttribute(): string
    {
        return '$' . number_format((float) $this->price, 2);
    }

    /**
     * Get the image URL attribute.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }

    // ==========================================
    // MÉTODOS PARA CÁLCULO DE IVA
    // ==========================================

    /**
     * Obtener la tasa de IVA efectiva del producto
     */
    public function getEffectiveTaxRate(): float
    {
        if (!tax_enabled()) {
            return 0;
        }

        // Productos exentos o excluidos
        if (in_array($this->tax_type, ['exempt', 'excluded'])) {
            return 0;
        }

        // Tasa personalizada del producto o tasa general del sistema
        return $this->tax_rate ?? get_tax_rate();
    }

    /**
     * Obtener el precio base sin IVA
     */
    public function getPriceWithoutTax(): float
    {
        if (!tax_enabled()) {
            return (float) $this->price;
        }

        // Si los precios incluyen IVA, extraer el precio sin IVA
        if (tax_included_in_price()) {
            return calculate_price_without_tax($this->price, $this->getEffectiveTaxRate());
        }

        // Si los precios no incluyen IVA, el precio ya está sin IVA
        return (float) $this->price;
    }

    /**
     * Obtener el precio con IVA incluido
     */
    public function getPriceWithTax(): float
    {
        if (!tax_enabled()) {
            return (float) $this->price;
        }

        // Si los precios ya incluyen IVA
        if (tax_included_in_price()) {
            return (float) $this->price;
        }

        // Si los precios no incluyen IVA, calcular el precio con IVA
        return calculate_price_with_tax($this->price, $this->getEffectiveTaxRate());
    }

    /**
     * Calcular el monto del IVA para una cantidad específica
     */
    public function calculateTaxAmount(int $quantity = 1): float
    {
        if (!tax_enabled()) {
            return 0;
        }

        $basePrice = $this->getPriceWithoutTax();
        $taxRate = $this->getEffectiveTaxRate();
        
        return calculate_tax($basePrice * $quantity, $taxRate);
    }

    /**
     * Calcular el subtotal sin IVA para una cantidad específica
     */
    public function calculateSubtotal(int $quantity = 1): float
    {
        return $this->getPriceWithoutTax() * $quantity;
    }

    /**
     * Calcular el total con IVA para una cantidad específica
     */
    public function calculateTotal(int $quantity = 1): float
    {
        $subtotal = $this->calculateSubtotal($quantity);
        $tax = $this->calculateTaxAmount($quantity);
        
        return $subtotal + $tax;
    }

    /**
     * Obtener el nombre del tipo de IVA
     */
    public function getTaxTypeName(): string
    {
        if (!tax_enabled()) {
            return 'Sin IVA';
        }

        return get_tax_type_label($this->tax_type ?? 'standard');
    }

    /**
     * Verificar si el producto está exento de IVA
     */
    public function isExempt(): bool
    {
        return $this->tax_type === 'exempt';
    }

    /**
     * Verificar si el producto está excluido de IVA
     */
    public function isExcluded(): bool
    {
        return $this->tax_type === 'excluded';
    }

    /**
     * Verificar si el producto tiene IVA
     */
    public function hasTax(): bool
    {
        return tax_enabled() && !in_array($this->tax_type, ['exempt', 'excluded']);
    }

    /**
     * Formatear precio mostrando si incluye o no IVA
     */
    public function getFormattedPriceWithTaxInfo(): string
    {
        $price = format_currency($this->price);
        
        if (!tax_enabled()) {
            return $price;
        }

        if ($this->isExempt()) {
            return $price . ' (Exento)';
        }

        if ($this->isExcluded()) {
            return $price . ' (Excluido)';
        }

        if (tax_included_in_price()) {
            return $price . ' (IVA incluido)';
        }

        return $price . ' + IVA';
    }

    // ==========================================
    // MÉTODOS PARA PROMOCIONES
    // ==========================================

    /**
     * Obtener el precio final (con descuento si aplica)
     */
    public function getFinalPriceAttribute(): float
    {
        if ($this->is_on_sale && $this->sale_price) {
            return (float) $this->sale_price;
        }
        return (float) $this->price;
    }

    /**
     * Calcular el descuento en dinero
     */
    public function getDiscountAmountAttribute(): float
    {
        if ($this->is_on_sale && $this->sale_price) {
            return (float) ($this->price - $this->sale_price);
        }
        return 0;
    }

    /**
     * Calcular el porcentaje de descuento real
     */
    public function getCalculatedDiscountPercentageAttribute(): int
    {
        if ($this->is_on_sale && $this->sale_price && $this->price > 0) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return $this->discount_percentage ?? 0;
    }

    /**
     * Scope para productos destacados
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true)->where('stock', '>', 0);
    }

    /**
     * Scope para productos en promoción
     */
    public function scopeOnSale(Builder $query): void
    {
        $query->where('is_on_sale', true)->where('stock', '>', 0);
    }
}
