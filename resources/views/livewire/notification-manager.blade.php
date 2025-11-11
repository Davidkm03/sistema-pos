<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <svg class="inline-block w-7 h-7 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notificaciones
            </h2>
            
            @if($notifications->total() > 0)
                <button wire:click="markAllAsRead" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Marcar todas como leídas
                </button>
            @endif
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select wire:model.live="typeFilter" id="typeFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos los tipos</option>
                        <option value="stock_low">Stock Bajo</option>
                        <option value="sale_completed">Venta Completada</option>
                        <option value="large_sale">Venta Grande</option>
                        <option value="system_error">Error del Sistema</option>
                        <option value="new_quote">Nueva Cotización</option>
                        <option value="quote_converted">Cotización Convertida</option>
                        <option value="goal_achieved">Meta Alcanzada</option>
                    </select>
                </div>

                <div>
                    <label for="readFilter" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select wire:model.live="readFilter" id="readFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="unread">No leídas</option>
                        <option value="read">Leídas</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button wire:click="clearFilters" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Lista de Notificaciones -->
        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ $notification->isUnread() ? 'border-l-4 border-l-blue-500' : '' }} transition-all hover:shadow-md">
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        @if($notification->isUnread())
                                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                        @endif
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                        <span class="ml-3 px-2 py-1 text-xs font-medium rounded-full {{ $notification->isUnread() ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $notification->type }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 ml-4">
                                    @if($notification->isUnread())
                                        <button wire:click="markAsRead({{ $notification->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Marcar como leída">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button wire:click="deleteNotification({{ $notification->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay notificaciones</h3>
                <p class="text-gray-600">No tienes notificaciones en este momento.</p>
            </div>
        @endif
    </div>
</div>
