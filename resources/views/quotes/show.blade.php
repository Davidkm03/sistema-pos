<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cotización {{ $quote->quote_number }}
            </h2>
            <a href="{{ route('quotes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Detalles de la cotización -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $quote->quote_number }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Creada: {{ $quote->created_at->format('d/m/Y H:i') }}</p>
                                @if($quote->valid_until)
                                <p class="text-sm mt-1">
                                    <span class="font-medium">Válida hasta:</span>
                                    <span class="{{ $quote->isExpired() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                        {{ $quote->valid_until->format('d/m/Y') }}
                                        @if($quote->isExpired())
                                            <span class="text-xs">(Vencida)</span>
                                        @endif
                                    </span>
                                </p>
                                @endif
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $quote->getStatusBadgeClass() }}">
                                {{ $quote->getStatusLabel() }}
                            </span>
                        </div>

                        <!-- Información del cliente -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Cliente</h4>
                            @if($quote->customer)
                                <p class="text-gray-700">{{ $quote->customer->name }}</p>
                                @if($quote->customer->email)
                                <p class="text-sm text-gray-600">{{ $quote->customer->email }}</p>
                                @endif
                                @if($quote->customer->phone)
                                <p class="text-sm text-gray-600">{{ $quote->customer->phone }}</p>
                                @endif
                            @else
                                <p class="text-gray-500">Sin cliente asignado</p>
                            @endif
                        </div>

                        <!-- Items -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Items</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($quote->items as $item)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $item->product->name ?? 'Producto eliminado' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                                ${{ number_format($item->price, 0) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">
                                                ${{ number_format($item->subtotal, 0) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="border-t-2 border-gray-200 pt-4">
                            <div class="flex justify-end">
                                <div class="w-64 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-semibold">${{ number_format($quote->subtotal, 0) }}</span>
                                    </div>
                                    @if($quote->tax > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">IVA:</span>
                                        <span class="font-semibold">${{ number_format($quote->tax, 0) }}</span>
                                    </div>
                                    @endif
                                    @if($quote->discount > 0)
                                    <div class="flex justify-between text-sm text-red-600">
                                        <span>Descuento:</span>
                                        <span class="font-semibold">-${{ number_format($quote->discount, 0) }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                                        <span>TOTAL:</span>
                                        <span class="text-indigo-600">${{ number_format($quote->total, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        @if($quote->notes)
                        <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                            <h4 class="font-semibold text-gray-900 mb-2">Notas/Observaciones</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $quote->notes }}</p>
                        </div>
                        @endif

                        <!-- Información de conversión -->
                        @if($quote->status === 'convertida' && $quote->convertedSale)
                        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                            <h4 class="font-semibold text-gray-900 mb-2">Convertida a Venta</h4>
                            <p class="text-sm text-gray-700">
                                Convertida el {{ $quote->converted_at->format('d/m/Y H:i') }}
                            </p>
                            <a href="{{ route('sales.show', $quote->converted_to_sale_id) }}" 
                               class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-800">
                                Ver venta #{{ $quote->converted_to_sale_id }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones -->
                <div>
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6 space-y-3">
                        <h4 class="font-semibold text-gray-900 mb-4">Acciones</h4>

                        <!-- Imprimir -->
                        <a href="{{ route('quotes.print', $quote) }}" 
                           target="_blank"
                           class="block w-full px-4 py-2 bg-green-600 text-white text-center rounded-lg font-semibold hover:bg-green-700 transition">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir
                        </a>

                        <!-- Editar (solo si está pendiente) -->
                        @can('quotes.edit')
                        @if($quote->status === 'pendiente')
                        <a href="{{ route('quotes.edit', $quote) }}" 
                           class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg font-semibold hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                        @endif
                        @endcan

                        <!-- Convertir a venta -->
                        @can('quotes.convert')
                        @if($quote->isConvertible())
                        <form action="{{ route('quotes.convert', $quote) }}" method="POST" 
                              onsubmit="return confirm('¿Deseas convertir esta cotización a venta? Esto descontará el inventario.');">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Convertir a Venta
                            </button>
                        </form>
                        @endif
                        @endcan

                        <!-- Eliminar -->
                        @can('quotes.delete')
                        @if($quote->status !== 'convertida')
                        <form action="{{ route('quotes.destroy', $quote) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar esta cotización?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                        @endif
                        @endcan

                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                <span class="font-medium">Creado por:</span> {{ $quote->user->name }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $quote->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
