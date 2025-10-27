<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSetting extends Model
{
    protected $fillable = [
        'ticket_header',
        'ticket_footer',
        'show_tax_id',
        'show_address',
        'show_phone',
        'show_email',
    ];

    protected $casts = [
        'show_tax_id' => 'boolean',
        'show_address' => 'boolean',
        'show_phone' => 'boolean',
        'show_email' => 'boolean',
    ];

    /**
     * Get the singleton instance of ticket settings
     * Solo para diseño visual del ticket
     */
    public static function getSettings()
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'ticket_footer' => '¡Gracias por su compra!',
                'show_tax_id' => true,
                'show_address' => true,
                'show_phone' => true,
                'show_email' => true,
            ]
        );
    }
}
