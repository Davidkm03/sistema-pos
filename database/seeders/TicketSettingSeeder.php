<?php

namespace Database\Seeders;

use App\Models\TicketSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketSetting::create([
            'business_name' => 'Mi Tienda POS',
            'address' => 'Calle Principal #123, Ciudad',
            'phone' => '+58 424-1234567',
            'email' => 'contacto@mitienda.com',
            'tax_id' => 'J-12345678-9',
            'ticket_header' => '¡Bienvenido!',
            'ticket_footer' => '¡Gracias por su compra!',
            'show_tax_id' => true,
            'show_address' => true,
            'show_phone' => true,
            'show_email' => false,
            'receipt_prefix' => 'VT',
            'receipt_number' => 1,
            'receipt_padding' => 6,
        ]);
    }
}
