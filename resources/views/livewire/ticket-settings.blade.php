<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">Configuración de Tickets</h2>

                <form wire:submit.prevent="save">
                    <!-- Información del Negocio -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Información del Negocio</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Negocio <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="business_name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('business_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    RIF/NIT/Registro
                                </label>
                                <input type="text" wire:model="tax_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('tax_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección
                                </label>
                                <input type="text" wire:model="address" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <input type="text" wire:model="phone" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" wire:model="email" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Opciones de Visualización -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Opciones de Visualización</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="show_tax_id" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 text-sm font-medium text-gray-700">
                                    Mostrar RIF/NIT en ticket
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="show_address" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 text-sm font-medium text-gray-700">
                                    Mostrar dirección en ticket
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="show_phone" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 text-sm font-medium text-gray-700">
                                    Mostrar teléfono en ticket
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="show_email" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 text-sm font-medium text-gray-700">
                                    Mostrar email en ticket
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Mensajes Personalizados -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Mensajes Personalizados</h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mensaje de Encabezado (opcional)
                                </label>
                                <input type="text" wire:model="ticket_header" 
                                    placeholder="Ej: ¡Bienvenido!"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ticket_header') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mensaje de Pie de Página
                                </label>
                                <input type="text" wire:model="ticket_footer" 
                                    placeholder="Ej: ¡Gracias por su compra!"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ticket_footer') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Numeración de Tickets -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Numeración de Tickets</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Prefijo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="receipt_prefix" 
                                    placeholder="VT"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('receipt_prefix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Ej: VT, FAC, TICK</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Número Actual <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="receipt_number" min="1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('receipt_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Próximo número a usar</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dígitos (padding) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="receipt_padding" min="1" max="10"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('receipt_padding') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Ej: 6 = 000001</p>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">Vista previa del próximo ticket:</span> 
                                {{ $receipt_prefix }}-{{ str_pad($receipt_number, $receipt_padding, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="flex justify-end">
                        <button type="submit" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Guardar Configuración</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Guardando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Escuchar evento cuando se guarda la configuración
    $wire.on('settings-saved', () => {
        Swal.fire({
            title: '¡Guardado! ✓',
            text: 'La configuración de tickets ha sido actualizada exitosamente',
            icon: 'success',
            confirmButtonColor: '#3B82F6',
            confirmButtonText: 'Entendido',
            timer: 3000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    });

    // Escuchar errores de validación si los hubiera
    $wire.on('settings-error', (event) => {
        const message = event.message || 'Error al guardar la configuración';
        
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#EF4444',
            confirmButtonText: 'Entendido',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });
</script>
@endscript
