<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Crear notificación para un usuario específico
     */
    public function createForUser(int $userId, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::createForUser($userId, $type, $title, $message, $data);
    }

    /**
     * Crear notificación para múltiples usuarios
     */
    public function createForUsers(array $userIds, string $type, string $title, string $message, array $data = []): Collection
    {
        $notifications = collect();

        foreach ($userIds as $userId) {
            $notifications->push($this->createForUser($userId, $type, $title, $message, $data));
        }

        return $notifications;
    }

    /**
     * Crear notificación para todos los usuarios de una empresa
     */
    public function createForCompany(int $empresaId, string $type, string $title, string $message, array $data = []): Collection
    {
        $userIds = User::where('empresa_id', $empresaId)->pluck('id')->toArray();
        return $this->createForUsers($userIds, $type, $title, $message, $data);
    }

    /**
     * Notificación de stock bajo
     */
    public function notifyLowStock(Product $product): Collection
    {
        $userIds = User::where('empresa_id', $product->empresa_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Supervisor', 'super-admin']);
            })
            ->pluck('id')
            ->toArray();

        $title = 'Stock Bajo';
        $message = "El producto '{$product->name}' tiene stock bajo ({$product->stock}). Stock mínimo: {$product->min_stock}";

        return $this->createForUsers($userIds, 'stock_low', $title, $message, [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'current_stock' => $product->stock,
            'min_stock' => $product->min_stock,
        ]);
    }

    /**
     * Notificación de venta completada
     */
    public function notifySaleCompleted(Sale $sale): Notification
    {
        $user = $sale->user;

        $title = 'Venta Completada';
        $message = "Venta #{$sale->id} completada por $" . number_format((float) $sale->total, 0, ',', '.');

        return $this->createForUser($user->id, 'sale_completed', $title, $message, [
            'sale_id' => $sale->id,
            'total' => $sale->total,
            'payment_method' => $sale->payment_method,
        ]);
    }

    /**
     * Notificación de venta grande
     */
    public function notifyLargeSale(Sale $sale, float $threshold = 100000): ?Collection
    {
        if ((float) $sale->total < $threshold) {
            return null;
        }

        $userIds = User::where('empresa_id', $sale->empresa_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Supervisor', 'super-admin']);
            })
            ->pluck('id')
            ->toArray();

        $title = '🚀 Venta Grande';
        $message = "Venta #{$sale->id} por $" . number_format((float) $sale->total, 0, ',', '.') . " realizada por {$sale->user->name}";

        return $this->createForUsers($userIds, 'large_sale', $title, $message, [
            'sale_id' => $sale->id,
            'total' => $sale->total,
            'user_name' => $sale->user->name,
        ]);
    }

    /**
     * Notificación de error del sistema
     */
    public function notifySystemError(string $error, string $context = ''): Collection
    {
        $userIds = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'super-admin']);
            })
            ->pluck('id')
            ->toArray();

        $title = '🚨 Error del Sistema';
        $message = "Se ha producido un error: {$error}";
        if ($context) {
            $message .= " | Contexto: {$context}";
        }

        return $this->createForUsers($userIds, 'system_error', $title, $message, [
            'error' => $error,
            'context' => $context,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Notificación de nueva cotización
     */
    public function notifyNewQuote(int $empresaId, int $quoteId, string $customerName): Collection
    {
        $userIds = User::where('empresa_id', $empresaId)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Supervisor', 'Vendedor', 'super-admin']);
            })
            ->pluck('id')
            ->toArray();

        $title = '📋 Nueva Cotización';
        $message = "Nueva cotización #{$quoteId} creada para {$customerName}";

        return $this->createForUsers($userIds, 'new_quote', $title, $message, [
            'quote_id' => $quoteId,
            'customer_name' => $customerName,
        ]);
    }

    /**
     * Notificación de cotización convertida a venta
     */
    public function notifyQuoteConverted(int $quoteId, int $saleId, string $customerName): Collection
    {
        $userIds = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Supervisor', 'Vendedor', 'super-admin']);
            })
            ->pluck('id')
            ->toArray();

        $title = 'Cotización Convertida';
        $message = "Cotización #{$quoteId} de {$customerName} convertida a venta #{$saleId}";

        return $this->createForUsers($userIds, 'quote_converted', $title, $message, [
            'quote_id' => $quoteId,
            'sale_id' => $saleId,
            'customer_name' => $customerName,
        ]);
    }

    /**
     * Notificación de meta alcanzada
     */
    public function notifyGoalAchieved(int $empresaId, string $goalName, float $target, float $achieved): Collection
    {
        $userIds = User::where('empresa_id', $empresaId)
            ->pluck('id')
            ->toArray();

        $title = 'Meta Alcanzada';
        $message = "¡Felicitaciones! Se ha alcanzado la meta '{$goalName}' con $" . number_format($achieved, 0, ',', '.') . " de $" . number_format($target, 0, ',', '.');

        return $this->createForUsers($userIds, 'goal_achieved', $title, $message, [
            'goal_name' => $goalName,
            'target' => $target,
            'achieved' => $achieved,
        ]);
    }

    /**
     * Limpiar notificaciones antiguas (más de 30 días)
     */
    public function cleanupOldNotifications(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}
