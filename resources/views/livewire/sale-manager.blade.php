<div class="container mx-auto px-4 py-6">
    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Gestión de Ventas
        </h1>
    </div>

    {{-- Sección de Filtros --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Búsqueda --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar
                </label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchTerm"
                    placeholder="ID o # de recibo..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Estado --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <select
                    wire:model.live="statusFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">Todas</option>
                    <option value="completada">Completadas</option>
                    <option value="anulada">Anuladas</option>
                    <option value="corregida">Corregidas</option>
                </select>
            </div>

            {{-- Fecha Desde --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Desde
                </label>
                <input
                    type="date"
                    wire:model.live="dateFrom"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Fecha Hasta --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Hasta
                </label>
                <input
                    type="date"
                    wire:model.live="dateTo"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
        </div>
    </div>

    {{-- Tabla de Ventas --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cajero
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $sale->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->customer->name ?? 'Cliente General' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ${{ number_format($sale->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $sale->statusBadge !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    {{-- Botón Ver Detalles --}}
                                    <button
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Ver detalles"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    {{-- Botón Anular (solo si está completada) --}}
                                    @if($sale->status === 'completada')
                                        <button
                                            wire:click="openCancelModal({{ $sale->id }})"
                                            class="text-red-600 hover:text-red-900"
                                            title="Anular venta"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Botón Imprimir --}}
                                    <button
                                        class="text-gray-600 hover:text-gray-900"
                                        title="Imprimir ticket"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm">No se encontraron ventas con los filtros seleccionados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $sales->links() }}
        </div>
    </div>

    {{-- Modal de Anulación --}}
    @if($showCancelModal && $saleToCancel)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
                {{-- Encabezado del Modal --}}
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-xl font-bold text-gray-900">
                        Anular Venta #{{ $saleToCancel->id }}
                    </h3>
                    <button wire:click="closeCancelModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Advertencia --}}
                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Advertencia:</strong> La anulación de una venta es irreversible.
                                Se revertirá el inventario y se registrará en el log de auditoría.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Información de la Venta --}}
                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Fecha:</span>
                            <span class="text-gray-900">{{ $saleToCancel->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Cajero:</span>
                            <span class="text-gray-900">{{ $saleToCancel->user->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Cliente:</span>
                            <span class="text-gray-900">{{ $saleToCancel->customer->name ?? 'Cliente General' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Total:</span>
                            <span class="text-gray-900 font-bold">${{ number_format($saleToCancel->total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Productos --}}
                    <div class="mt-4">
                        <p class="font-medium text-gray-700 mb-2">Productos:</p>
                        <div class="space-y-1 max-h-32 overflow-y-auto">
                            @foreach($saleToCancel->saleItems as $item)
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>{{ $item->product->name ?? 'Producto eliminado' }} x{{ $item->quantity }}</span>
                                    <span>${{ number_format($item->total, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Formulario de Anulación --}}
                <div class="mt-4">
                    {{-- Razón de Anulación --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Razón de anulación <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="selectedReason"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('selectedReason') border-red-500 @enderror"
                        >
                            <option value="">Seleccione una razón...</option>
                            @foreach($cancellationReasons as $reason)
                                <option value="{{ $reason['id'] }}">
                                    {{ $reason['text'] }}
                                    @if($reason['requires_approval'])
                                        (Requiere aprobación admin)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('selectedReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción Detallada --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción detallada <span class="text-red-500">*</span>
                            <span class="text-gray-500 font-normal">(mínimo 20 caracteres)</span>
                        </label>
                        <textarea
                            wire:model="detailedReason"
                            rows="4"
                            placeholder="Explique detalladamente el motivo de la anulación..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('detailedReason') border-red-500 @enderror"
                        ></textarea>
                        @error('detailedReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button
                        wire:click="closeCancelModal"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Cancelar
                    </button>
                    <button
                        wire:click="confirmCancellation"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        Confirmar Anulación
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Script para alertas --}}
    @script
    <script>
        $wire.on('show-alert', (event) => {
            const data = event[0] || event;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Éxito' : 'Error',
                    text: data.message,
                    confirmButtonColor: data.type === 'success' ? '#10b981' : '#ef4444',
                });
            } else {
                alert(data.message);
            }
        });
    </script>
    @endscript
</div>
