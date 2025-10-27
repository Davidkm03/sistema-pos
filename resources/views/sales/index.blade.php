@extends('layouts.app')

@section('content')
@php
    $sales = \App\Models\Sale::with(['user', 'customer', 'paymentDetails'])->latest()->paginate(15);
@endphp

<div class="container mx-auto px-4 py-6">
    <!-- Título Principal -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Historial de Ventas</h1>
        <p class="text-gray-600">Consulta y gestiona todas las ventas realizadas</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Búsqueda -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" 
                           placeholder="Buscar por cliente, usuario o ID..." 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Método de Pago -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta_debito">💳 Tarjeta Débito</option>
                        <option value="tarjeta_credito">💳 Tarjeta Crédito</option>
                        <option value="transferencia">📱 Transferencia</option>
                    </select>
                </div>

                <!-- Botón Filtrar -->
                <div class="flex items-end">
                    <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Lista de Ventas
            </h3>
        </div>

        @if($sales->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método de Pago</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $sale->id }}
                            </td>

                            <!-- Fecha/Hora -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ $sale->created_at->format('d/m/Y') }}</div>
                                    <div class="text-gray-500">{{ $sale->created_at->format('H:i') }}</div>
                                </div>
                            </td>

                            <!-- Usuario -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->user->name ?? 'Sin usuario' }}
                            </td>

                            <!-- Cliente -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->customer->name ?? 'Cliente General' }}
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ${{ number_format((float) $sale->total, 2) }}
                            </td>

                            <!-- Método de Pago -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $paymentMethod = $sale->payment_method;
                                    $paymentIcon = '';
                                    $paymentText = '';
                                    $paymentClass = 'bg-blue-100 text-blue-800';
                                    
                                    switch($paymentMethod) {
                                        case 'efectivo':
                                            $paymentIcon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>';
                                            $paymentText = '💵 Efectivo';
                                            $paymentClass = 'bg-green-100 text-green-800';
                                            break;
                                        case 'tarjeta_debito':
                                            $paymentIcon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>';
                                            $paymentText = '💳 Débito';
                                            $paymentClass = 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'tarjeta_credito':
                                            $paymentIcon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>';
                                            $paymentText = '💳 Crédito';
                                            $paymentClass = 'bg-purple-100 text-purple-800';
                                            break;
                                        case 'transferencia':
                                            $paymentIcon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                                            $paymentText = '📱 Transferencia';
                                            $paymentClass = 'bg-indigo-100 text-indigo-800';
                                            // Obtener detalles de transferencia si existen
                                            $paymentDetail = $sale->paymentDetails->first();
                                            if ($paymentDetail && $paymentDetail->transfer_type) {
                                                $paymentText = '📱 ' . ucfirst($paymentDetail->transfer_type);
                                            }
                                            break;
                                        case 'card':
                                        case 'tarjeta':
                                            $paymentIcon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>';
                                            $paymentText = '💳 Tarjeta';
                                            $paymentClass = 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'cash':
                                        default:
                                            $paymentIcon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>';
                                            $paymentText = '💵 Efectivo';
                                            $paymentClass = 'bg-green-100 text-green-800';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentClass }}">
                                    {!! $paymentIcon !!}
                                    {{ $paymentText }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = $sale->status ?? 'completed';
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    switch($status) {
                                        case 'completed':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Completada';
                                            break;
                                        case 'pending':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Pendiente';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'Cancelada';
                                            break;
                                        default:
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Completada';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('sales.show', $sale->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        @else
            <!-- Estado Vacío -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h.01M9 16h.01"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay ventas registradas</h3>
                <p class="text-gray-500 mb-4">Comienza realizando tu primera venta en el punto de venta.</p>
                <a href="{{ route('pos.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ir al Punto de Venta
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
