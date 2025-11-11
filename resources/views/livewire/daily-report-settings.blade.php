<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-t-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold flex items-center gap-3">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        Reporte Diario por WhatsApp
                    </h2>
                    <p class="mt-2 text-green-100">
                        Recibe automáticamente un análisis completo del día con IA
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <div class="text-4xl font-bold">📊</div>
                        <div class="text-sm mt-1">Auto IA</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="bg-white rounded-b-2xl shadow-xl p-8">
            
            {{-- Features Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <div class="text-3xl mb-2">$</div>
                    <div class="font-bold text-blue-900">Ventas & Ganancias</div>
                    <div class="text-sm text-blue-700">Total, margen, ticket promedio</div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                    <div class="text-3xl mb-2">#</div>
                    <div class="font-bold text-orange-900">Alertas de Stock</div>
                    <div class="text-sm text-orange-700">Productos que se agotaran</div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                    <div class="text-3xl mb-2">+</div>
                    <div class="font-bold text-purple-900">Combos Sugeridos</div>
                    <div class="text-sm text-purple-700">Productos que se venden juntos</div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <div class="text-3xl mb-2">AI</div>
                    <div class="font-bold text-green-900">Recomendacion IA</div>
                    <div class="text-sm text-green-700">GPT-4o-mini analiza tu negocio</div>
                </div>
            </div>

            {{-- Settings Form --}}
            <form wire:submit.prevent="save" class="space-y-6">
                
                {{-- Enable/Disable Toggle --}}
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border-2 border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Activar Reporte Diario
                            </label>
                            <p class="text-sm text-gray-600 mt-1 ml-8">
                                Recibe análisis automático todos los días a la hora configurada
                            </p>
                        </div>
                        <div class="ml-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="whatsapp_daily_report_enabled" class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Time Selection --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Hora del Reporte
                    </label>
                    <input 
                        type="time" 
                        wire:model="whatsapp_report_time"
                        class="w-full md:w-auto px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 font-mono text-lg"
                    >
                    <p class="text-sm text-gray-500 mt-2">
                        Hora de Colombia (GMT-5). Recomendado: 19:00 (7pm)
                    </p>
                    @error('whatsapp_report_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- WhatsApp Number --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Numero de WhatsApp del Dueno
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            wire:model="owner_whatsapp"
                            placeholder="3001234567"
                            class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Ingresa el número sin espacios ni guiones. Ej: 3001234567 (Colombia)
                    </p>
                    @error('owner_whatsapp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Include Combos --}}
                <div class="bg-purple-50 rounded-xl p-6 border-2 border-purple-200">
                    <div class="flex items-start gap-3">
                        <input 
                            type="checkbox" 
                            wire:model="whatsapp_report_include_combos"
                            class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                            id="include_combos"
                        >
                        <div class="flex-1">
                            <label for="include_combos" class="font-bold text-purple-900 cursor-pointer">
                                Incluir analisis de combos frecuentes
                            </label>
                            <p class="text-sm text-purple-700 mt-1">
                                El reporte mostrara que productos se compran juntos y sugerira precios de combo
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Example Message --}}
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                    <div class="flex items-start gap-3 mb-3">
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="font-bold text-green-900 text-lg">Ejemplo de Reporte</h3>
                            <p class="text-sm text-green-700 mt-1">Asi se vera el mensaje que recibiras:</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 font-mono text-sm text-gray-800 whitespace-pre-line border border-green-300">*REPORTE DIARIO*

*Ventas de hoy*
• Total vendido: $985,200
• Transacciones: 47
• Ticket promedio: $20,961

*Ganancias*
• Utilidad estimada: $315,264
• Margen: 32%

*Se agotaran manana*
• Arroz Diana (quedan 8)
• Azucar (quedan 12)
• Coca-Cola 2L (quedan 15)

*Combos sugeridos*
• Hamburguesa + Papitas
  23 veces - Precio combo: $18,400

*Sugerencia IA*
Compra 5 cajas de Coca-Cola. El proveedor BebidasMar tiene mejor precio.

---
_Reporte automatico - 10/11/2025 19:00_</div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Guardar Configuración
                    </button>
                    
                    <button 
                        type="button"
                        wire:click="testReport"
                        class="px-6 py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                        </svg>
                        Probar Ahora
                    </button>
                </div>

                {{-- Info Note --}}
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-bold mb-1">Configuracion del Servidor</p>
                            <p>Para que el reporte se envie automaticamente, debes configurar un cron job en el servidor:</p>
                            <code class="block bg-blue-100 px-3 py-2 rounded-lg mt-2 font-mono text-xs">
                                * * * * * cd /ruta/del/proyecto && php artisan schedule:run >> /dev/null 2>&1
                            </code>
                            <p class="mt-2">Consulta la documentacion para mas detalles.</p>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('settings-saved', (event) => {
        Swal.fire({
            icon: 'success',
            title: '¡Guardado!',
            text: event.message,
            confirmButtonColor: '#10B981',
            timer: 2500,
            timerProgressBar: true
        });
    });

    $wire.on('report-generated', (event) => {
        Swal.fire({
            icon: 'info',
            title: 'Reporte Generado',
            html: `
                <p class="mb-3">${event.message}</p>
                <p class="text-sm text-gray-600">El comando genero un link de WhatsApp. Revisa la consola del servidor o los logs para obtenerlo.</p>
            `,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: 'Entendido'
        });
    });

    $wire.on('report-error', (event) => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: event.message,
            confirmButtonColor: '#EF4444'
        });
    });
</script>
@endscript
