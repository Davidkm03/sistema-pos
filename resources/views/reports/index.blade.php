<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes') }}
        </h2>
    </x-slot>

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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- SECCIÓN DE GRÁFICOS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico 1: Ventas de los últimos 7 días -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                            Ventas Últimos 7 Días
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="ventasChart" height="280"></canvas>
                    </div>
                </div>

                <!-- Gráfico 2: Ventas por Método de Pago -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Ventas por Método de Pago
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="metodosPagoChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 3: Productos Más Vendidos (Ancho completo) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Top 10 Productos Más Vendidos
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="productosChart" height="100"></canvas>
                </div>
            </div>
            
            <!-- SECCIÓN 1 - Ventas por Período -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Ventas por Período
            </h3>
        </div>
        <div class="p-6">
            <!-- Formulario de Fechas -->
            <form class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                        <input type="date" 
                               name="fecha_desde"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                        <input type="date" 
                               name="fecha_hasta"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Generar Reporte
                        </button>
                    </div>
                </div>
            </form>

            <!-- Resultado del Reporte -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-500 mb-2">Selecciona un rango de fechas para generar el reporte</p>
                    <p class="text-sm text-gray-400">Los resultados aparecerán aquí</p>
                </div>
            </div>
        </div>
    </div>

            <!-- Grid para Secciones 2 y 3 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- SECCIÓN 2 - Productos Más Vendidos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Productos Más Vendidos
                </h3>
            </div>
            <div class="p-6">
                @if($productosVendidos->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 text-sm">Producto</th>
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 text-sm">Cantidad</th>
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 text-sm">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productosVendidos as $producto)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-3 text-sm font-medium text-gray-900">
                                        {{ $producto->product_name }}
                                    </td>
                                    <td class="py-3 px-3 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ number_format($producto->total_quantity) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-sm font-semibold text-green-600">
                                        ${{ number_format((float) $producto->total_generated, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-gray-500">No hay datos de productos vendidos</p>
                    </div>
                @endif
            </div>
        </div>

                <!-- SECCIÓN 3 - Ventas por Usuario -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Ventas por Usuario
                </h3>
            </div>
            <div class="p-6">
                @if($ventasPorUsuario->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 text-sm">Usuario</th>
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 text-sm">Ventas</th>
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 text-sm">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventasPorUsuario as $usuario)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-3 text-sm font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-purple-600 text-xs font-medium">
                                                    {{ strtoupper(substr($usuario->user_name, 0, 2)) }}
                                                </span>
                                            </div>
                                            {{ $usuario->user_name }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ number_format($usuario->total_sales) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-sm font-semibold text-green-600">
                                        ${{ number_format((float) $usuario->total_amount, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-500">No hay datos de ventas por usuario</p>
                    </div>
                @endif
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