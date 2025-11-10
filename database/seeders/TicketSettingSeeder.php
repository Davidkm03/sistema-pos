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
            'ticket_header' => '¡Bienvenido!',
            'ticket_footer' => '¡Gracias por su compra!',
            'show_tax_id' => true,
            'show_address' => true,
            'show_phone' => true,
            'show_email' => false,
        ]);
    }
}
