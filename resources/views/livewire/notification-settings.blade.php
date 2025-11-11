<div>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Configuración de Notificaciones</h3>
                    <p class="text-sm text-gray-600">Personaliza qué tipos de notificaciones deseas recibir</p>
                </div>
                <button wire:click="resetToDefaults" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Restablecer valores por defecto
                </button>
            </div>

            <form wire:submit.prevent="saveSettings" class="space-y-6">
                <!-- Notificaciones de Inventario -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Inventario
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_stock_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones de stock bajo</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Recibe alertas cuando los productos estén por debajo del stock mínimo</p>
                    </div>
                </div>

                <!-- Notificaciones de Ventas -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Ventas
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_sale_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones de ventas completadas</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Recibe confirmación cuando se complete una venta</p>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_large_sale_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones de ventas grandes</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Alertas para ventas por encima del umbral establecido</p>

                        <div class="ml-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Umbral para ventas grandes ($)
                            </label>
                            <input type="number" wire:model="settings.large_sale_threshold"
                                   class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   min="1000" step="1000">
                        </div>
                    </div>
                </div>

                <!-- Notificaciones de Sistema -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Sistema
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_system_error_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones de errores del sistema</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Alertas cuando ocurran errores críticos en el sistema</p>
                    </div>
                </div>

                <!-- Notificaciones de Cotizaciones -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Cotizaciones
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_quote_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones de cotizaciones</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Alertas para nuevas cotizaciones y conversiones</p>
                    </div>
                </div>

                <!-- Notificaciones de Metas -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Metas y Objetivos
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_goal_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones de metas alcanzadas</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Felicitaciones cuando se alcancen objetivos de venta</p>
                    </div>
                </div>

                <!-- Configuración Avanzada -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuración Avanzada
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_push_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones push en el navegador</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Mostrar notificaciones emergentes en el navegador</p>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="settings.enable_email_notifications"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Notificaciones por email</span>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                Próximamente
                            </span>
                        </label>
                        <p class="text-xs text-gray-500 ml-5">Enviar notificaciones importantes por email</p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" wire:click="resetToDefaults"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Restablecer
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
</div>
