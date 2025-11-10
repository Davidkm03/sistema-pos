<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'empresa_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con la configuración del negocio
     */
    public function businessSetting()
    {
        return $this->hasOne(BusinessSetting::class);
    }

    /**
     * Relación con la empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    
    /**
     * Verificar si el usuario es super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Obtener el descuento máximo que puede aplicar este usuario
     */
    public function getMaxDiscountAllowed(): float
    {
        $settings = BusinessSetting::current();

        // Super admin o Admin tienen descuento ilimitado (100%)
        if ($this->hasRole(['super-admin', 'Admin'])) {
            return (float) ($settings->max_discount_admin ?? 100);
        }

        // Cajero
        if ($this->hasRole('Cajero')) {
            return (float) ($settings->max_discount_cashier ?? 15);
        }

        // Vendedor
        if ($this->hasRole('Vendedor')) {
            return (float) ($settings->max_discount_seller ?? 10);
        }

        // Por defecto, sin descuento
        return 0;
    }
}
