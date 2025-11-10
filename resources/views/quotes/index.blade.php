<x-app-layout>
    {{-- Alpine x-cloak --}}
    <style>
        [x-cloak]{display:none!important}
    </style>

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cotizaciones
            </h2>

            @can('quotes.create')
                <a href="{{ route('quotes.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-xs uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Cotización
                </a>
            @endcan
        </div>
    </x-slot>

    {{-- Alertas SweetAlert --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    showClass: { popup: 'animate__animated animate__fadeInDown' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonColor: '#4F46E5',
                    showClass: { popup: 'animate__animated animate__shakeX' }
                });
            });
        </script>
    @endif

    {{-- Contenido --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtros --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form method="GET" action="{{ route('quotes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Número o cliente..."
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            <option value="pendiente"  @selected(request('status') === 'pendiente')>Pendiente</option>
                            <option value="aprobada"   @selected(request('status') === 'aprobada')>Aprobada</option>
                            <option value="rechazada"  @selected(request('status') === 'rechazada')>Rechazada</option>
                            <option value="convertida" @selected(request('status') === 'convertida')>Convertida</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Filtrar
                        </button>
                        <a href="{{ route('quotes.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla / Lista --}}
            @if($quotes->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Válida Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($quotes as $quote)
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- Número --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-indigo-600">{{ $quote->quote_number }}</span>
                                    </td>

                                    {{-- Cliente --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">
                                            {{ $quote->customer->name ?? 'Sin cliente' }}
                                        </span>
                                    </td>

                                    {{-- Fecha --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $quote->created_at->format('d/m/Y') }}
                                    </td>

                                    {{-- Válida hasta --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($quote->valid_until)
                                            <span class="{{ $quote->isExpired() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                                {{ $quote->valid_until->format('d/m/Y') }}
                                                @if($quote->isExpired())
                                                    <span class="text-xs">(Vencida)</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Total --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900">
                                            ${{ number_format($quote->total, 0) }}
                                        </span>
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $quote->getStatusBadgeClass() }}">
                                            {{ $quote->getStatusLabel() }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('quotes.show', $quote) }}"
                                               class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-xs hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span class="hidden lg:inline">Ver</span>
                                            </a>

                                            @can('quotes.edit')
                                                @if($quote->status === 'pendiente')
                                                    <a href="{{ route('quotes.edit', $quote) }}"
                                                       class="inline-flex items-center gap-1 px-3 py-2 bg-blue-600 text-white rounded-xl font-semibold text-xs hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-md"
                                                       title="Editar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endcan

                                            <a href="{{ route('quotes.print', $quote) }}" target="_blank"
                                               class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 text-white rounded-xl font-semibold text-xs hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition shadow-md"
                                               title="Imprimir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Paginación --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $quotes->links() }}
                    </div>
                </div>
            @else
                {{-- Estado vacío --}}
                <div class="text-center py-16 px-6 bg-white rounded-lg shadow-sm">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">No hay cotizaciones registradas</h3>
                    <p class="text-gray-600 mb-6 font-medium">Comienza creando tu primera cotización</p>
                    @can('quotes.create')
                        <a href="{{ route('quotes.create') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nueva Cotización
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
