<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends Component
{
    use WithPagination;

    public $typeFilter = '';
    public $readFilter = '';
    protected $queryString = [
        'typeFilter' => ['except' => ''],
        'readFilter' => ['except' => ''],
    ];

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingReadFilter()
    {
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            $this->dispatch('refreshNotifications');
            session()->flash('success', 'Notificación marcada como leída.');
        }
    }

    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        $this->dispatch('refreshNotifications');
        session()->flash('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->delete();
            $this->dispatch('refreshNotifications');
            session()->flash('success', 'Notificación eliminada.');
        }
    }

    public function clearFilters()
    {
        $this->typeFilter = '';
        $this->readFilter = '';
        $this->resetPage();
    }

    public function getIconForType($type)
    {
        return match ($type) {
            'stock_low' => '',
            'sale_completed' => '',
            'large_sale' => '',
            'system_error' => '',
            'new_quote' => '',
            'quote_converted' => '',
            'goal_achieved' => '',
            default => '',
        };
    }

    public function render()
    {
        $query = Notification::forUser(auth()->id())
            ->orderBy('created_at', 'desc');

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->readFilter === 'read') {
            $query->read();
        } elseif ($this->readFilter === 'unread') {
            $query->unread();
        }

        $notifications = $query->paginate(20);

        return view('livewire.notification-manager', [
            'notifications' => $notifications,
        ]);
    }
}
