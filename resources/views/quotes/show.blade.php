<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">Cotización {{ $quote->quote_number }}</h2>
                    <p class="text-xs text-gray-500">Detalle completo del presupuesto</p>
                </div>
            </div>
            <a href="{{ route('quotes.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Volver</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '{{ session("success") }}',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        });
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ session("error") }}',
                            confirmButtonColor: '#4F46E5',
                            showClass: {
                                popup: 'animate__animated animate__shakeX'
                            }
                        });
                    });
                </script>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Detalles de la cotización -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Header Card -->
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                            <div class="flex items-center justify-between text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-black">{{ $quote->quote_number }}</h3>
                                        @if($quote->created_at)
                                        <p class="text-xs text-indigo-100">Creada: {{ $quote->created_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-bold text-sm shadow-lg {{ $quote->getStatusBadgeClass() }}">
                                    {{ $quote->getStatusLabel() }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($quote->valid_until)
                            <div class="mb-4 p-4 rounded-xl {{ $quote->isExpired() ? 'bg-red-50 border-2 border-red-200' : 'bg-blue-50 border-2 border-blue-200' }}">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 {{ $quote->isExpired() ? 'text-red-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-bold {{ $quote->isExpired() ? 'text-red-700' : 'text-blue-700' }}">
                                        Válida hasta: {{ $quote->valid_until->format('d/m/Y') }}
                                        @if($quote->isExpired())
                                            <span class="text-xs ml-2 px-2 py-1 bg-red-600 text-white rounded-full">VENCIDA</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @endif

                            <!-- Información del cliente -->
                            <div class="mb-6 p-5 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border-2 border-indigo-100">
                                <h4 class="font-black text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Cliente
                                </h4>
                                @if($quote->customer)
                                    <p class="text-gray-900 font-bold text-lg mb-1">{{ $quote->customer->name }}</p>
                                    @if($quote->customer->email)
                                    <p class="text-sm text-gray-600 flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $quote->customer->email }}
                                    </p>
                                    @endif
                                    @if($quote->customer->phone)
                                    <p class="text-sm text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $quote->customer->phone }}
                                    </p>
                                    @endif
                                @else
                                    <p class="text-gray-500 font-medium flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        Sin cliente asignado
                                    </p>
                                @endif
                            </div>

                            <!-- Items -->
                            <div>
                                <h4 class="font-black text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Productos
                                </h4>
                                <div class="overflow-x-auto rounded-xl border-2 border-indigo-100">
                                    <table class="min-w-full divide-y-2 divide-indigo-100">
                                        <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Producto</th>
                                                <th class="px-4 py-3 text-center text-xs font-black text-gray-700 uppercase tracking-wider">Cantidad</th>
                                                <th class="px-4 py-3 text-right text-xs font-black text-gray-700 uppercase tracking-wider">Precio Unit.</th>
                                                <th class="px-4 py-3 text-right text-xs font-black text-gray-700 uppercase tracking-wider">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($quote->items as $item)
                                            <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all">
                                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                    {{ $item->product->name ?? 'Producto eliminado' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-bold text-center">
                                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">
                                                    ${{ number_format($item->price, 0) }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 text-right">
                                                    ${{ number_format($item->subtotal, 0) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="border-t-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-purple-50 p-6">
                            <div class="flex justify-end">
                                <div class="w-full md:w-80 bg-white p-6 rounded-2xl border-2 border-indigo-100 shadow-lg">
                                    <h4 class="text-sm font-black text-gray-700 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        RESUMEN
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-semibold">Subtotal:</span>
                                            <span class="font-black text-gray-900">${{ number_format($quote->subtotal, 0) }}</span>
                                        </div>
                                        @if($quote->tax > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-semibold">IVA:</span>
                                            <span class="font-black text-gray-900">${{ number_format($quote->tax, 0) }}</span>
                                        </div>
                                        @endif
                                        @if($quote->discount > 0)
                                        <div class="flex justify-between text-sm text-red-600">
                                            <span class="font-semibold">Descuento:</span>
                                            <span class="font-black">-${{ number_format($quote->discount, 0) }}</span>
                                        </div>
                                        @endif
                                        <div class="flex justify-between text-xl font-black border-t-2 border-indigo-100 pt-3">
                                            <span class="text-gray-800">TOTAL:</span>
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">${{ number_format($quote->total, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Notas -->
                        @if($quote->notes)
                        <div class="mt-6 p-5 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border-2 border-yellow-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-900 mb-2 flex items-center gap-2">Notas/Observaciones</h4>
                                    <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $quote->notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Información de conversión -->
                        @if($quote->status === 'convertida' && $quote->convertedSale)
                        <div class="mt-6 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-900 mb-2">Convertida a Venta</h4>
                                    @if($quote->converted_at)
                                    <p class="text-sm text-gray-700 mb-3">
                                        Convertida el {{ $quote->converted_at->format('d/m/Y H:i') }}
                                    </p>
                                    @endif
                                    <a href="{{ route('sales.show', $quote->converted_to_sale_id) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold text-sm hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all shadow-lg">
                                        Ver venta #{{ $quote->converted_to_sale_id }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Panel de Acciones -->
                <div class="lg:sticky lg:top-6">
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                            <div class="flex items-center gap-3 text-white">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black">Acciones</h4>
                            </div>
                        </div>

                        <div class="p-6 space-y-3">
                            <!-- Imprimir -->
                            <a href="{{ route('quotes.print', $quote) }}" 
                               target="_blank"
                               class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                <span>Imprimir</span>
                            </a>

                            <!-- Editar (solo si está pendiente) -->
                            @can('quotes.edit')
                            @if($quote->status === 'pendiente')
                            <a href="{{ route('quotes.edit', $quote) }}" 
                               class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Editar</span>
                            </a>
                            @endif
                            @endcan

                            <!-- Convertir a venta -->
                            @can('quotes.convert')
                            @if($quote->isConvertible())
                            <form id="convertForm" action="{{ route('quotes.convert', $quote) }}" method="POST">
                                @csrf
                                <button type="button" onclick="confirmConvert()"
                                        class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Convertir a Venta</span>
                                </button>
                            </form>
                            @endif
                            @endcan

                            <!-- Eliminar -->
                            @can('quotes.delete')
                            @if($quote->status !== 'convertida')
                            <form id="deleteForm" action="{{ route('quotes.destroy', $quote) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()"
                                        class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl font-bold hover:from-red-700 hover:to-rose-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Eliminar</span>
                                </button>
                            </form>
                            @endif
                            @endcan

                            <!-- Información del creador -->
                            <div class="pt-4 border-t-2 border-indigo-100">
                                <div class="p-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-indigo-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Creado por</p>
                                            <p class="text-sm font-black text-gray-900 truncate">{{ $quote->user->name ?? 'N/A' }}</p>
                                            @if($quote->created_at)
                                            <p class="text-xs text-gray-600 mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $quote->created_at->format('d/m/Y H:i') }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmConvert() {
            Swal.fire({
                title: '¿Convertir a venta?',
                html: `
                    <p class="text-gray-600 mb-2">Esta acción:</p>
                    <ul class="text-left text-sm text-gray-700">
                        <li>✓ Creará una venta con estos productos</li>
                        <li>✓ Descontará del inventario</li>
                        <li>✓ Marcará la cotización como convertida</li>
                    </ul>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, convertir',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('convertForm').submit();
                }
            });
        }

        function confirmDelete() {
            Swal.fire({
                title: '¿Eliminar cotización?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
