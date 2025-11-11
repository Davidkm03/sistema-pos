<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class CreateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create-test {user?} {--count=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear notificaciones de prueba para un usuario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user') ?? 1;
        $count = (int) $this->option('count');

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        $this->info("Creando {$count} notificaciones de prueba para {$user->name}...");

        $notificationTypes = [
            [
                'type' => 'stock_low',
                'title' => '⚠️ Stock Bajo',
                'message' => 'El producto "Coca-Cola 1.5L" tiene stock bajo (5 unidades). Stock mínimo: 10',
                'data' => ['product_id' => 1, 'current_stock' => 5, 'min_stock' => 10]
            ],
            [
                'type' => 'sale_completed',
                'title' => '💰 Venta Completada',
                'message' => 'Venta #123 completada por $45.000',
                'data' => ['sale_id' => 123, 'total' => 45000]
            ],
            [
                'type' => 'large_sale',
                'title' => '🚀 Venta Grande',
                'message' => 'Venta #124 por $150.000 realizada por Juan Pérez',
                'data' => ['sale_id' => 124, 'total' => 150000, 'user_name' => 'Juan Pérez']
            ],
            [
                'type' => 'system_error',
                'title' => '🚨 Error del Sistema',
                'message' => 'Error al procesar pago con tarjeta de crédito',
                'data' => ['error' => 'Payment gateway timeout', 'context' => 'POS Checkout']
            ],
            [
                'type' => 'new_quote',
                'title' => '📋 Nueva Cotización',
                'message' => 'Nueva cotización #45 creada para "Tienda ABC"',
                'data' => ['quote_id' => 45, 'customer_name' => 'Tienda ABC']
            ],
            [
                'type' => 'quote_converted',
                'title' => '✅ Cotización Convertida',
                'message' => 'Cotización #45 de "Tienda ABC" convertida a venta #125',
                'data' => ['quote_id' => 45, 'sale_id' => 125, 'customer_name' => 'Tienda ABC']
            ],
            [
                'type' => 'goal_achieved',
                'title' => '🎯 Meta Alcanzada',
                'message' => '¡Felicitaciones! Se alcanzó la meta de ventas del mes con $500.000',
                'data' => ['goal_name' => 'Ventas Mensuales', 'target' => 500000, 'achieved' => 520000]
            ],
        ];

        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            $typeData = $notificationTypes[array_rand($notificationTypes)];

            Notification::createForUser(
                $user->id,
                $typeData['type'],
                $typeData['title'],
                $typeData['message'],
                $typeData['data']
            );

            $created++;
        }

        $this->info("✅ {$created} notificaciones de prueba creadas exitosamente para {$user->name}");
        $this->info("📧 Total de notificaciones no leídas: " . Notification::getUnreadCountForUser($user->id));

        return 0;
    }
}
