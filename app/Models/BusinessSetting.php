<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BusinessSetting extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'logo_path',
        'business_logo',
        'business_address',
        'business_phone',
        'business_email',
        'business_tax_id',
        'receipt_footer',
        'primary_color',
        'secondary_color',
        'timezone',
        'currency',
        'tax_enabled',
        'tax_name',
        'tax_rate',
        'tax_included_in_price',
        'retention_enabled',
        'retention_rate',
        'applies_retention_from',
        'tax_id_required',
        'business_tax_regime',
        'max_cancellation_days',
        'require_cancellation_approval',
        'cancellation_approval_amount',
        'billing_type',
        'receipt_prefix',
        'receipt_counter',
        'receipt_header',
        'show_tax_disclaimer',
        'invoice_prefix',
        'dian_resolution',
        'resolution_date',
        'range_from',
        'range_to',
        'resolution_expiry',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tax_enabled' => 'boolean',
        'tax_rate' => 'decimal:2',
        'tax_included_in_price' => 'boolean',
        'retention_enabled' => 'boolean',
        'retention_rate' => 'decimal:2',
        'applies_retention_from' => 'decimal:2',
        'tax_id_required' => 'boolean',
        'require_cancellation_approval' => 'boolean',
        'cancellation_approval_amount' => 'decimal:2',
        'max_cancellation_days' => 'integer',
        'receipt_counter' => 'integer',
        'show_tax_disclaimer' => 'boolean',
        'range_from' => 'integer',
        'range_to' => 'integer',
        'resolution_date' => 'date',
        'resolution_expiry' => 'date',
    ];

    /**
     * Relación con el usuario dueño del negocio
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor para obtener la URL completa del logo
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->business_logo) {
            return null;
        }

        if (str_starts_with($this->business_logo, 'http')) {
            return $this->business_logo;
        }

        return Storage::url($this->business_logo);
    }

    /**
     * Obtener la configuración del usuario autenticado
     */
    public static function current()
    {
        if (!auth()->check()) {
            return self::defaults();
        }

        return Cache::remember('business_settings_' . auth()->id(), 3600, function () {
            $settings = self::where('user_id', auth()->id())->first();
            
            if (!$settings) {
                return self::defaults();
            }

            return $settings;
        });
    }

    /**
     * Valores por defecto si no existe configuración
     */
    public static function defaults()
    {
        return (object) [
            'business_name' => 'Mi Tienda',
            'business_logo' => null,
            'business_address' => '',
            'business_phone' => '',
            'business_email' => '',
            'business_tax_id' => '',
            'receipt_footer' => '¡Gracias por su compra!',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#10B981',
            'timezone' => 'America/Mexico_City',
            'currency' => 'MXN',
            'logo_url' => null,
            'tax_enabled' => false,
            'tax_name' => 'IVA',
            'tax_rate' => 19,
            'tax_included_in_price' => false,
            'retention_enabled' => false,
            'retention_rate' => 3.5,
            'applies_retention_from' => 800000,
            'tax_id_required' => false,
            'business_tax_regime' => 'simplified',
            'max_cancellation_days' => 1,
            'require_cancellation_approval' => false,
            'cancellation_approval_amount' => 100000,
            'billing_type' => 'simple_receipt',
            'receipt_prefix' => 'RV',
            'receipt_counter' => 1,
            'receipt_header' => null,
            'show_tax_disclaimer' => true,
            'invoice_prefix' => 'FV',
            'dian_resolution' => null,
            'resolution_date' => null,
            'range_from' => null,
            'range_to' => null,
            'resolution_expiry' => null,
        ];
    }

    /**
     * Limpiar el caché cuando se guarda
     */
    protected static function booted()
    {
        static::saved(function ($settings) {
            Cache::forget('business_settings_' . $settings->user_id);
        });

        static::deleted(function ($settings) {
            Cache::forget('business_settings_' . $settings->user_id);
        });
    }
}
