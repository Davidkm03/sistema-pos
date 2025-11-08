<div>
    <style>[x-cloak] { display: none !important; }</style>
    
    {{-- Header Modernizado --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-red-600 rounded-2xl shadow-2xl flex items-center justify-center transform hover:scale-105 transition-transform">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Gestión de Ventas</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">Administra y anula ventas realizadas</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:ring-4 focus:ring-gray-200 transition-all shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Dashboard
            </a>
        </div>

        {{-- Filtros Modernizados --}}
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-orange-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-red-600">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Filtros de Búsqueda</h3>
                        <p class="text-xs text-orange-100">Encuentra ventas específicas</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Búsqueda --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </label>
                        <input type="text"
                               wire:model.live.debounce.300ms="searchTerm"
                               placeholder="ID o # de recibo..."
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-medium">
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Estado
                        </label>
                        <select wire:model.live="statusFilter"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold">
                            <option value="all">✨ Todas</option>
                            <option value="completada">✅ Completadas</option>
                            <option value="anulada">❌ Anuladas</option>
                            <option value="corregida">🔄 Corregidas</option>
                        </select>
                    </div>

                    {{-- Fecha Desde --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Desde
                        </label>
                        <input type="date"
                               wire:model.live="dateFrom"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold">
                    </div>

                    {{-- Fecha Hasta --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Hasta
                        </label>
                        <input type="date"
                               wire:model.live="dateTo"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla Modernizada --}}
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-orange-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-red-600">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Ventas Registradas</h3>
                        <p class="text-xs text-orange-100">Gestiona y anula ventas</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-2 divide-orange-100">
                    <thead class="bg-gradient-to-r from-orange-50 to-red-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    ID
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Cajero
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Cliente
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Total
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Estado
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                    Acciones
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">
                                        #{{ str_pad($sale->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $sale->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500 font-medium">{{ $sale->created_at->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600 to-red-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                            {{ substr($sale->user->name ?? 'N', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $sale->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900">{{ $sale->customer->name ?? 'Cliente General' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-base font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">
                                        ${{ number_format($sale->total, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $sale->statusBadge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        {{-- Botón Ver Detalles --}}
                                        <button style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;"
                                                class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-bold text-xs hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95"
                                                title="Ver detalles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        {{-- Botón Anular (solo si está completada) --}}
                                        @if($sale->status === 'completada')
                                            <button wire:click="openCancelModal({{ $sale->id }})"
                                                    style="background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%) !important;"
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg font-bold text-xs hover:from-red-700 hover:to-red-800 focus:ring-4 focus:ring-red-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95"
                                                    title="Anular venta">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Botón Imprimir --}}
                                        <button style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;"
                                                class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-bold text-xs hover:from-green-700 hover:to-emerald-700 focus:ring-4 focus:ring-green-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95"
                                                title="Imprimir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 mb-3">No se encontraron ventas</h3>
                                    <p class="text-gray-600 font-medium">Intenta ajustar los filtros de búsqueda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-t-2 border-orange-100">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    {{-- Modal de Anulación Modernizado --}}
    @if($showCancelModal && $saleToCancel)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-cloak
             class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div @click.away="show = false" 
                 class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl border-2 border-red-200 transform transition-all">
                
                {{-- Header del Modal --}}
                <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-pink-600 rounded-t-2xl">
                    <div class="flex items-center justify-between text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black">Anular Venta #{{ str_pad($saleToCancel->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-sm text-red-100 font-medium">Proceso de anulación irreversible</p>
                            </div>
                        </div>
                        <button wire:click="closeCancelModal" 
                                class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Contenido Scrollable --}}
                <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                    {{-- Advertencia --}}
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-xl p-4 mb-6">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-yellow-800 mb-1">⚠️ Advertencia Importante</p>
                                <p class="text-sm text-yellow-700">
                                    La anulación de una venta es <strong>irreversible</strong>.
                                    Se revertirá el inventario automáticamente y se registrará en el log de auditoría.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Información de la Venta --}}
                    <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-5 mb-6 border-2 border-gray-200">
                        <h4 class="text-sm font-black text-gray-700 uppercase mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información de la Venta
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-3 border-2 border-gray-100">
                                <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Fecha</span>
                                <span class="text-sm font-black text-gray-900">{{ $saleToCancel->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border-2 border-gray-100">
                                <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Cajero</span>
                                <span class="text-sm font-black text-gray-900">{{ $saleToCancel->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border-2 border-gray-100">
                                <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Cliente</span>
                                <span class="text-sm font-black text-gray-900">{{ $saleToCancel->customer->name ?? 'Cliente General' }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border-2 border-red-200 bg-gradient-to-br from-red-50 to-pink-50">
                                <span class="text-xs font-bold text-red-600 uppercase block mb-1">Total</span>
                                <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-pink-600">${{ number_format($saleToCancel->total, 2) }}</span>
                            </div>
                        </div>

                        {{-- Productos --}}
                        <div class="mt-4">
                            <p class="text-sm font-black text-gray-700 uppercase mb-3">📦 Productos ({{ $saleToCancel->saleItems->count() }})</p>
                            <div class="space-y-2 max-h-40 overflow-y-auto bg-white rounded-lg p-3 border-2 border-gray-100">
                                @foreach($saleToCancel->saleItems as $item)
                                    <div class="flex justify-between items-center text-sm pb-2 border-b border-gray-100 last:border-0">
                                        <span class="font-semibold text-gray-700">
                                            {{ $item->product->name ?? 'Producto eliminado' }}
                                            <span class="text-red-600 font-black">x{{ $item->quantity }}</span>
                                        </span>
                                        <span class="font-black text-gray-900">${{ number_format($item->total, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Formulario de Anulación --}}
                    <div class="space-y-4">
                        {{-- Razón de Anulación --}}
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Razón de anulación
                                <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="selectedReason"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-semibold @error('selectedReason') border-red-500 @enderror">
                                <option value="">Seleccione una razón...</option>
                                @foreach($cancellationReasons as $reason)
                                    <option value="{{ $reason['id'] }}">
                                        {{ $reason['text'] }}
                                        @if($reason['requires_approval']) 🔒 (Requiere aprobación admin) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedReason')
                                <p class="mt-2 text-sm font-bold text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Descripción Detallada --}}
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Descripción detallada
                                <span class="text-red-500">*</span>
                                <span class="text-gray-500 font-normal text-xs">(mínimo 20 caracteres)</span>
                            </label>
                            <textarea wire:model="detailedReason"
                                      rows="4"
                                      placeholder="Explique detalladamente el motivo de la anulación..."
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-medium @error('detailedReason') border-red-500 @enderror"></textarea>
                            @error('detailedReason')
                                <p class="mt-2 text-sm font-bold text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer Sticky con Botones --}}
                <div class="sticky bottom-0 px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-t-2 border-gray-200 rounded-b-2xl flex justify-end gap-3">
                    <button wire:click="closeCancelModal"
                            class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-black hover:bg-gray-100 hover:border-gray-400 focus:ring-4 focus:ring-gray-200 transition-all shadow-md">
                        Cancelar
                    </button>
                    <button wire:click="confirmCancellation"
                            style="background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%) !important;"
                            class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl font-black hover:from-red-700 hover:to-pink-700 focus:ring-4 focus:ring-red-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
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
