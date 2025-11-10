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
                            <!-- Imprimir Formato Normal -->
                            <a href="{{ route('quotes.print', $quote) }}" 
                               target="_blank"
                               class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                <span>Imprimir (A4)</span>
                            </a>

                            <!-- Imprimir Formato POS -->
                            <a href="{{ route('quotes.print-pos', $quote) }}" 
                               target="_blank"
                               class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Imprimir (Ticket POS)</span>
                            </a>

                            <!-- Enviar por Email -->
                            <button type="button" onclick="showEmailModal()"
                                    class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Enviar por Email</span>
                            </button>

                            <!-- Enviar por WhatsApp -->
                            <button type="button" onclick="sendWhatsApp()"
                                    class="group flex items-center justify-center gap-2 w-full px-4 py-3 bg-[#25D366] hover:bg-[#1fb855] text-white rounded-xl font-bold transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span>Enviar por WhatsApp</span>
                            </button>

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

    <!-- Modal de Enviar Email -->
    <div id="emailModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Enviar Cotización por Email
                </h3>
            </div>
            <form id="emailForm" action="{{ route('quotes.send-email', $quote) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico del Destinatario
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ $quote->customer ? $quote->customer->email : '' }}"
                           required
                           placeholder="ejemplo@correo.com"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                    <p class="text-xs text-gray-500 mt-1">
                        Se enviará la cotización #{{ $quote->quote_number }} a este correo
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="hideEmailModal()"
                            class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showEmailModal() {
            document.getElementById('emailModal').classList.remove('hidden');
        }

        function hideEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera de él
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideEmailModal();
            }
        });

        // Manejar envío del formulario
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Deshabilitar botón y mostrar loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            // Enviar formulario
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideEmailModal();
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Email Enviado!',
                        text: data.message || 'La cotización ha sido enviada exitosamente.',
                        confirmButtonColor: '#059669'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo enviar el email. Por favor, verifica la configuración SMTP.',
                        confirmButtonColor: '#059669'
                    });
                }
            })
            .catch(error => {
                hideEmailModal();
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al enviar el email. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#059669'
                });
            });
        });

        // Función para enviar por WhatsApp
        function sendWhatsApp() {
            const quote = @json($quote);
            const businessSettings = @json($businessSettings);
            
            // Construir mensaje
            let message = `*Cotización ${quote.quote_number}*\n\n`;
            message += `Hola${quote.customer ? ' ' + quote.customer.name : ''},\n\n`;
            message += `Le enviamos la siguiente cotización de *${businessSettings.business_name}*:\n\n`;
            message += `*Detalles:*\n`;
            message += `📅 Fecha: ${new Date(quote.created_at).toLocaleDateString('es-ES')}\n`;
            if(quote.valid_until) {
                message += `⏰ Válida hasta: ${new Date(quote.valid_until).toLocaleDateString('es-ES')}\n`;
            }
            message += `\n*Productos:*\n`;
            
            @foreach($quote->items as $item)
            message += `• {{ $item->product->name }}\n`;
            message += `  Cantidad: {{ $item->quantity }} | Precio: ${{ number_format($item->price, 2) }}\n`;
            message += `  Subtotal: ${{ number_format($item->subtotal, 2) }}\n\n`;
            @endforeach
            
            message += `*Resumen:*\n`;
            message += `Subtotal: ${{ number_format($quote->subtotal, 2) }}\n`;
            @if($quote->tax > 0)
            message += `IVA: ${{ number_format($quote->tax, 2) }}\n`;
            @endif
            @if($quote->discount > 0)
            message += `Descuento: -${{ number_format($quote->discount, 2) }}\n`;
            @endif
            message += `*TOTAL: ${{ number_format($quote->total, 2) }} {{ $businessSettings->currency ?? 'COP' }}*\n\n`;
            
            if(quote.notes) {
                message += `_Notas: ${quote.notes}_\n\n`;
            }
            
            message += `Para más información contáctenos:\n`;
            if(businessSettings.business_phone) {
                message += `📞 ${businessSettings.business_phone}\n`;
            }
            if(businessSettings.business_email) {
                message += `✉️ ${businessSettings.business_email}\n`;
            }
            if(businessSettings.business_address) {
                message += `📍 ${businessSettings.business_address}`;
            }
            
            // Codificar mensaje para URL
            const encodedMessage = encodeURIComponent(message);
            
            // Obtener teléfono del cliente o pedir uno
            if(quote.customer && quote.customer.phone) {
                // Limpiar teléfono (quitar espacios, guiones, etc)
                const phone = quote.customer.phone.replace(/\D/g, '');
                const whatsappUrl = `https://wa.me/${phone}?text=${encodedMessage}`;
                window.open(whatsappUrl, '_blank');
            } else {
                // Pedir número de teléfono
                Swal.fire({
                    title: 'Número de WhatsApp',
                    input: 'text',
                    inputLabel: 'Ingrese el número de teléfono (con código de país)',
                    inputPlaceholder: 'Ej: 573001234567',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Debes ingresar un número de teléfono';
                        }
                        if (!/^\d{10,15}$/.test(value.replace(/\D/g, ''))) {
                            return 'Número de teléfono inválido';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const phone = result.value.replace(/\D/g, '');
                        const whatsappUrl = `https://wa.me/${phone}?text=${encodedMessage}`;
                        window.open(whatsappUrl, '_blank');
                    }
                });
            }
        }

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
