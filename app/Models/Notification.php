<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'empresa_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new EmpresaScope);
    }

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Métodos
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    // Métodos estáticos para crear notificaciones
    public static function createForUser(int $userId, string $type, string $title, string $message, array $data = []): self
    {
        $user = User::find($userId);

        return static::create([
            'user_id' => $userId,
            'empresa_id' => $user->empresa_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    // Métodos para obtener conteos
    public static function getUnreadCountForUser(int $userId): int
    {
        return static::forUser($userId)->unread()->count();
    }

    public static function getRecentForUser(int $userId, int $limit = 10)
    {
        return static::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
