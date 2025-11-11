<div class="relative">
    <!-- Botón del dropdown -->
    <button
        wire:click="toggleDropdown"
        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200"
        title="Notificaciones"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        <!-- Badge de notificaciones no leídas -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center min-w-[20px]">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    @if($showDropdown)
        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Notificaciones</h3>
                <a
                    href="{{ route('notifications.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                >
                    Ver todas
                </a>
            </div>

            <!-- Lista de notificaciones -->
            <div class="max-h-80 overflow-y-auto">
                @if(count($notifications) > 0)
                    @foreach($notifications as $notification)
                        <div class="border-b border-gray-100 last:border-b-0 {{ $notification['read_at'] ? 'bg-gray-50' : 'bg-blue-50' }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-start space-x-3">
                                    <!-- Icono según tipo -->
                                    <div class="flex-shrink-0">
                                        {!! $this->getIconForType($notification['type']) !!}
                                    </div>

                                    <!-- Contenido -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification['title'] }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $notification['message'] }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">
                                            {{ $this->getTimeAgo($notification['created_at']) }}
                                        </p>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex items-center space-x-2">
                                        @if(!$notification['read_at'])
                                            <button
                                                wire:click="markAsRead({{ $notification['id'] }})"
                                                class="text-blue-600 hover:text-blue-800 text-sm"
                                                title="Marcar como leída"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @endif

                                        <button
                                            wire:click="deleteNotification({{ $notification['id'] }})"
                                            class="text-red-600 hover:text-red-800 text-sm"
                                            title="Eliminar notificación"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p class="text-sm">No tienes notificaciones</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            @if(count($notifications) > 0)
                <div class="border-t border-gray-200 p-3">
                    <button
                        wire:click="deleteAllRead"
                        class="text-sm text-red-600 hover:text-red-800 font-medium w-full block text-center"
                    >
                        Limpiar notificaciones leídas
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- Overlay para cerrar el dropdown al hacer click fuera -->
    @if($showDropdown)
        <div
            wire:click="toggleDropdown"
            class="fixed inset-0 z-40"
            style="margin-top: 0;"
        ></div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:loaded', () => {
    // Polling para actualizar notificaciones cada 30 segundos
    setInterval(() => {
        $wire.call('loadNotifications');
    }, 30000); // 30 segundos

    // Escuchar eventos de notificaciones desde otros componentes
    Livewire.on('notification-created', () => {
        $wire.call('loadNotifications');
        // Mostrar notificación toast si está disponible
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'info',
                title: 'Nueva notificación'
            });
        }
    });

    // Actualizar notificaciones cuando la página gana foco
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            $wire.call('loadNotifications');
        }
    });
});
</script>
@endpush