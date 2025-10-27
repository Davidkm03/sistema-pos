<div class="container mx-auto px-4 py-6">
    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Log de Auditoría
            </h1>
            <p class="text-gray-600 mt-1">Historial de todas las acciones realizadas sobre las ventas</p>
        </div>
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
                    placeholder="ID venta o usuario..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Acción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Acción
                </label>
                <select
                    wire:model.live="actionFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">Todas</option>
                    <option value="cancelled">Anulación</option>
                    <option value="corrected">Corrección</option>
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

    {{-- Tabla de Logs --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha y Hora
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Venta ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acción
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Razón
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('sales.show', $log->sale_id) }}" class="text-blue-600 hover:text-blue-800">
                                    #{{ $log->sale_id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->action === 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Anulación
                                    </span>
                                @elseif($log->action === 'corrected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Corrección
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $log->reason ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    wire:click="openDetailsModal({{ $log->id }})"
                                    class="text-blue-600 hover:text-blue-900"
                                    title="Ver detalles"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm">No se encontraron registros de auditoría</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>

    {{-- Modal de Detalles --}}
    @if($showDetailsModal && $selectedLog)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
                {{-- Encabezado del Modal --}}
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-xl font-bold text-gray-900">
                        Detalles del Log de Auditoría
                    </h3>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Información General --}}
                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Fecha y Hora:</span>
                            <span class="text-gray-900">{{ $selectedLog->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Usuario:</span>
                            <span class="text-gray-900">{{ $selectedLog->user->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Venta ID:</span>
                            <span class="text-gray-900">#{{ $selectedLog->sale_id }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Acción:</span>
                            @if($selectedLog->action === 'cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Anulación
                                </span>
                            @elseif($selectedLog->action === 'corrected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Corrección
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Razón --}}
                    @if($selectedLog->reason)
                        <div class="mt-4">
                            <p class="font-medium text-gray-700 mb-1">Razón:</p>
                            <p class="text-gray-900 bg-white p-3 rounded border border-gray-200">{{ $selectedLog->reason }}</p>
                        </div>
                    @endif

                    {{-- Descripción Detallada --}}
                    @if($selectedLog->detailed_reason)
                        <div class="mt-4">
                            <p class="font-medium text-gray-700 mb-1">Descripción Detallada:</p>
                            <p class="text-gray-900 bg-white p-3 rounded border border-gray-200">{{ $selectedLog->detailed_reason }}</p>
                        </div>
                    @endif
                </div>

                {{-- Datos Antes y Después --}}
                <div class="mt-4 grid grid-cols-2 gap-4">
                    {{-- Datos Anteriores --}}
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Datos Anteriores:</h4>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 max-h-64 overflow-y-auto">
                            <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($selectedLog->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>

                    {{-- Datos Nuevos --}}
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Datos Nuevos:</h4>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 max-h-64 overflow-y-auto">
                            <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($selectedLog->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>

                {{-- Botón Cerrar --}}
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button
                        wire:click="closeDetailsModal"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
