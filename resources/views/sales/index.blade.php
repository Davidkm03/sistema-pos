<x-app-layout>
    <!-- Estilo para Alpine.js x-cloak -->
    <style>
        [x-cloak] { 
            display: none !important; 
        }
    </style>
    
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gra                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">Historial de Ventas</h2>
                    <p class="text-xs text-gray-500">Consulta y gestiona todas las ventas realizadas</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-green-200 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $sales = \App\Models\Sale::with(['user', 'customer', 'paymentDetails'])->latest()->paginate(15);
    @endphp

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-6">

            <!-- Filtros -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-green-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Filtros de Búsqueda</h3>
                            <p class="text-xs text-green-100">Encuentra ventas específicas</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Búsqueda -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Buscar
                            </label>
                            <input type="text" 
                                   placeholder="Buscar por cliente, usuario o ID..." 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all font-medium">
                        </div>

                        <!-- Método de Pago -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Método de Pago
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all font-semibold">
                                <option value="">Todos</option>
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="tarjeta_debito">💳 Tarjeta Débito</option>
                                <option value="tarjeta_credito">💳 Tarjeta Crédito</option>
                                <option value="transferencia">📱 Transferencia</option>
                            </select>
                        </div>

                        <!-- Botón Filtrar -->
                        <div class="flex items-end">
                            <button style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-4 rounded-xl font-black hover:from-green-700 hover:to-emerald-700 focus:ring-4 focus:ring-green-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tabla de Ventas -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-green-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Registro de Ventas</h3>
                            <p class="text-xs text-green-100">Historial completo de transacciones</p>
                        </div>
                    </div>
                </div>

                @if($sales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-green-100">
                            <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                            ID
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Fecha/Hora
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Usuario
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Cliente
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Total
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            Método de Pago
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Estado
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                            </svg>
                                            Acciones
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($sales as $sale)
                                <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200 group">
                                    <!-- ID -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                            #{{ str_pad($sale->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>

                                    <!-- Fecha/Hora -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $sale->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500 font-medium">{{ $sale->created_at->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Usuario -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                                {{ substr($sale->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $sale->user->name ?? 'Sin usuario' }}</span>
                                        </div>
                                    </td>

                                    <!-- Cliente -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-900">{{ $sale->customer->name ?? 'Cliente General' }}</span>
                                        </div>
                                    </td>

                                    <!-- Total -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-base font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                            ${{ number_format((float) $sale->total, 2) }}
                                        </span>
                                    </td>

                                    <!-- Método de Pago -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $paymentMethod = $sale->payment_method;
                                            $paymentText = '';
                                            $paymentGradient = '';
                                            
                                            switch($paymentMethod) {
                                                case 'efectivo':
                                                    $paymentText = '💵 Efectivo';
                                                    $paymentGradient = 'from-green-500 to-emerald-500';
                                                    break;
                                                case 'tarjeta_debito':
                                                    $paymentText = '� Débito';
                                                    $paymentGradient = 'from-blue-500 to-cyan-500';
                                                    break;
                                                case 'tarjeta_credito':
                                                    $paymentText = '💳 Crédito';
                                                    $paymentGradient = 'from-purple-500 to-pink-500';
                                                    break;
                                                case 'transferencia':
                                                    $paymentDetail = $sale->paymentDetails->first();
                                                    if ($paymentDetail && $paymentDetail->transfer_type) {
                                                        $paymentText = '📱 ' . ucfirst($paymentDetail->transfer_type);
                                                    } else {
                                                        $paymentText = '� Transferencia';
                                                    }
                                                    $paymentGradient = 'from-indigo-500 to-violet-500';
                                                    break;
                                                case 'card':
                                                case 'tarjeta':
                                                    $paymentText = '� Tarjeta';
                                                    $paymentGradient = 'from-blue-500 to-cyan-500';
                                                    break;
                                                default:
                                                    $paymentText = ucfirst($paymentMethod);
                                                    $paymentGradient = 'from-gray-500 to-slate-500';
                                                    break;
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r {{ $paymentGradient }} shadow-md">
                                            {{ $paymentText }}
                                        </span>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = $sale->status ?? 'completed';
                                            $statusGradient = '';
                                            $statusText = '';
                                            $statusIcon = '';
                                            
                                            switch($status) {
                                                case 'completed':
                                                    $statusGradient = 'from-green-500 to-emerald-500';
                                                    $statusText = 'Completada';
                                                    $statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                                    break;
                                                case 'pending':
                                                    $statusGradient = 'from-yellow-500 to-orange-500';
                                                    $statusText = 'Pendiente';
                                                    $statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                                    break;
                                                case 'cancelled':
                                                    $statusGradient = 'from-red-500 to-pink-500';
                                                    $statusText = 'Anulada';
                                                    $statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                                    break;
                                                default:
                                                    $statusGradient = 'from-gray-500 to-slate-500';
                                                    $statusText = ucfirst($status);
                                                    $statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r {{ $statusGradient }} shadow-md">
                                            {!! $statusIcon !!}
                                            {{ $statusText }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('sales.show', $sale->id) }}" 
                                           style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold text-xs hover:from-green-700 hover:to-emerald-700 focus:ring-4 focus:ring-green-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-t-2 border-green-100">
                        {{ $sales->links() }}
                    </div>
                @else
                    <!-- Estado Vacío Modernizado -->
                    <div class="text-center py-16 px-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">No hay ventas registradas</h3>
                        <p class="text-gray-600 mb-6 font-medium">Comienza realizando tu primera venta en el punto de venta</p>
                        <a href="{{ route('pos.index') }}" 
                           style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-black hover:from-green-700 hover:to-emerald-700 focus:ring-4 focus:ring-green-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ir al Punto de Venta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </x-app-layout>
