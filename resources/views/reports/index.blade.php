<x-app-layout>
    <style>[x-cloak] { display: none !important; }</style>
    
    @php
        // Productos más vendidos
        $productosVendidos = \Illuminate\Support\Facades\DB::table('sale_items')
            ->select(
                'products.name as product_name',
                \Illuminate\Support\Facades\DB::raw('SUM(sale_items.quantity) as total_quantity'),
                \Illuminate\Support\Facades\DB::raw('SUM(sale_items.subtotal) as total_generated')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->take(10)
            ->get();

        // Ventas por usuario
        $ventasPorUsuario = \Illuminate\Support\Facades\DB::table('sales')
            ->select(
                'users.name as user_name',
                \Illuminate\Support\Facades\DB::raw('COUNT(sales.id) as total_sales'),
                \Illuminate\Support\Facades\DB::raw('SUM(sales.total) as total_amount')
            )
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->groupBy('sales.user_id', 'users.name')
            ->orderBy('total_amount', 'desc')
            ->get();
            
        // Ventas de los últimos 7 días
        $ventasUltimos7Dias = \Illuminate\Support\Facades\DB::table('sales')
            ->select(
                \Illuminate\Support\Facades\DB::raw('DATE(created_at) as fecha'),
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_ventas'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as total_ingresos')
            )
            ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
            ->groupBy(\Illuminate\Support\Facades\DB::raw('DATE(created_at)'))
            ->orderBy('fecha', 'asc')
            ->get();
            
        // Ventas por método de pago
        $ventasPorMetodo = \Illuminate\Support\Facades\DB::table('sales')
            ->select(
                'payment_method',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_ventas'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as total_ingresos')
            )
            ->groupBy('payment_method')
            ->get();
    @endphp

    {{-- Header Modernizado --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl shadow-2xl flex items-center justify-center transform hover:scale-105 transition-transform">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Reportes de Ventas</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">Análisis y estadísticas de tu negocio</p>
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

        <div class="space-y-6">
            
            <!-- SECCIÓN DE GRÁFICOS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico 1: Ventas de los últimos 7 días -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-blue-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-cyan-600">
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Ventas Últimos 7 Días</h3>
                                <p class="text-xs text-blue-100">Tendencia semanal</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <canvas id="ventasChart" height="280"></canvas>
                    </div>
                </div>

                <!-- Gráfico 2: Ventas por Método de Pago -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-purple-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600">
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Ventas por Método de Pago</h3>
                                <p class="text-xs text-purple-100">Distribución de pagos</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <canvas id="metodosPagoChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 3: Productos Más Vendidos (Ancho completo) -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-green-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Top 10 Productos Más Vendidos</h3>
                            <p class="text-xs text-green-100">Ranking de productos</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="productosChart" height="100"></canvas>
                </div>
            </div>
            
            <!-- SECCIÓN 1 - Ventas por Período -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Ventas por Período</h3>
                            <p class="text-xs text-indigo-100">Selecciona el rango de fechas</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Formulario de Fechas -->
                    <form class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha Desde
                                </label>
                                <input type="date" 
                                       name="fecha_desde"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha Hasta
                                </label>
                                <input type="date" 
                                       name="fecha_hasta"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                        style="background: linear-gradient(135deg, #4F46E5 0%, #3B82F6 100%) !important;"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-blue-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Resultado del Reporte -->
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border-2 border-indigo-100">
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-black text-gray-900 mb-2">Selecciona un rango de fechas</h4>
                            <p class="text-gray-600 font-medium">Los resultados aparecerán aquí una vez generes el reporte</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid para Secciones 2 y 3 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- SECCIÓN 2 - Productos Más Vendidos -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-green-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Productos Más Vendidos</h3>
                                <p class="text-xs text-green-100">Top productos por cantidad</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($productosVendidos->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y-2 divide-green-100">
                                    <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                    Producto
                                                </div>
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                    </svg>
                                                    Cantidad
                                                </div>
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Total
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($productosVendidos as $producto)
                                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200">
                                            <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                                {{ $producto->product_name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r from-blue-500 to-cyan-500 shadow-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                    {{ number_format($producto->total_quantity) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                                ${{ number_format((float) $producto->total_generated, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black text-gray-900 mb-2">No hay datos disponibles</h4>
                                <p class="text-gray-600 font-medium">No hay datos de productos vendidos</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SECCIÓN 3 - Ventas por Usuario -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-purple-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600">
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Ventas por Usuario</h3>
                                <p class="text-xs text-purple-100">Rendimiento del equipo</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($ventasPorUsuario->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y-2 divide-purple-100">
                                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Usuario
                                                </div>
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Ventas
                                                </div>
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Total
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($ventasPorUsuario as $usuario)
                                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-200">
                                            <td class="px-4 py-3 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                                        {{ strtoupper(substr($usuario->user_name, 0, 2)) }}
                                                    </div>
                                                    <span class="font-bold text-gray-900">{{ $usuario->user_name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r from-purple-500 to-pink-500 shadow-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    {{ number_format($usuario->total_sales) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                                ${{ number_format((float) $usuario->total_amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black text-gray-900 mb-2">No hay datos disponibles</h4>
                                <p class="text-gray-600 font-medium">No hay datos de ventas por usuario</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración global de Chart.js
            Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";
            Chart.defaults.color = '#6B7280';
            
            // ========================================
            // GRÁFICO 1: Ventas de los últimos 7 días
            // ========================================
            const ventasData = @json($ventasUltimos7Dias);
            
            // Preparar datos para el gráfico de línea
            const fechas = ventasData.map(v => {
                const fecha = new Date(v.fecha);
                return fecha.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
            });
            const totalesVentas = ventasData.map(v => v.total_ventas);
            const totalesIngresos = ventasData.map(v => parseFloat(v.total_ingresos));
            
            const ventasCtx = document.getElementById('ventasChart');
            if (ventasCtx) {
                new Chart(ventasCtx, {
                    type: 'line',
                    data: {
                        labels: fechas,
                        datasets: [
                            {
                                label: 'Número de Ventas',
                                data: totalesVentas,
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Ingresos ($)',
                                data: totalesIngresos,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            if (context.datasetIndex === 1) {
                                                label += '$' + context.parsed.y.toFixed(2);
                                            } else {
                                                label += context.parsed.y;
                                            }
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Número de Ventas'
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Ingresos ($)'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toFixed(0);
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // ========================================
            // GRÁFICO 2: Ventas por Método de Pago
            // ========================================
            const metodosPagoData = @json($ventasPorMetodo);
            
            const metodos = metodosPagoData.map(m => m.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta');
            const ingresosMetodos = metodosPagoData.map(m => parseFloat(m.total_ingresos));
            
            const metodosPagoCtx = document.getElementById('metodosPagoChart');
            if (metodosPagoCtx) {
                new Chart(metodosPagoCtx, {
                    type: 'doughnut',
                    data: {
                        labels: metodos,
                        datasets: [{
                            data: ingresosMetodos,
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(59, 130, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgb(34, 197, 94)',
                                'rgb(59, 130, 246)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 13
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // ========================================
            // GRÁFICO 3: Top 10 Productos Más Vendidos
            // ========================================
            const productosData = @json($productosVendidos);
            
            const nombreProductos = productosData.map(p => {
                // Limitar nombre a 20 caracteres
                return p.product_name.length > 20 ? p.product_name.substring(0, 20) + '...' : p.product_name;
            });
            const cantidadesProductos = productosData.map(p => parseInt(p.total_quantity));
            
            const productosCtx = document.getElementById('productosChart');
            if (productosCtx) {
                new Chart(productosCtx, {
                    type: 'bar',
                    data: {
                        labels: nombreProductos,
                        datasets: [{
                            label: 'Unidades Vendidas',
                            data: cantidadesProductos,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y', // Barras horizontales
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return 'Vendidas: ' + context.parsed.x + ' unidades';
                                    },
                                    title: function(context) {
                                        // Mostrar nombre completo en tooltip
                                        return productosData[context[0].dataIndex].product_name;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>