<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ setting('business_name', 'Sistema POS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Banner de Configuración de Negocio --}}
            @php
                $hasBusinessConfig = \App\Models\BusinessSetting::where('user_id', auth()->id())->exists();
            @endphp
            
            @if(!$hasBusinessConfig)
                <div class="mb-6 bg-gray-50 border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Configura tu negocio</h3>
                                <p class="text-sm text-gray-600">Personaliza el nombre, logo y datos de tu negocio.</p>
                            </div>
                            <a href="{{ route('settings.business') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                                Configurar
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
            {{-- Widget de Meta Activa --}}
            @php
                $currentGoal = \App\Models\Goal::current()->first();
            @endphp
            
            @if($currentGoal)
                @php
                    $currentProfit = $currentGoal->getCurrentProfit();
                    $progress = $currentGoal->getProgressPercentage();
                    $daysRemaining = $currentGoal->getDaysRemaining();
                    $remainingAmount = $currentGoal->getRemainingAmount();
                    $dailyNeeded = $currentGoal->getDailyProfitNeeded();
                    $isCompleted = $currentGoal->isCompleted();
                    $isExpired = $currentGoal->isExpired();
                @endphp
                
                <div class="mb-6 bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $currentGoal->name }}</h3>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($currentGoal->start_date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($currentGoal->end_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if($isCompleted)
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 font-medium rounded-full text-xs">
                                        Completada
                                    </span>
                                @elseif($isExpired)
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 font-medium rounded-full text-xs">
                                        Expirada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-900 text-white font-medium rounded-full text-xs">
                                        Activa
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-gray-600">Progreso</span>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-500 bg-gray-900" 
                                     style="width: {{ min($progress, 100) }}%">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Ganancia Actual</p>
                                <p class="text-base font-semibold text-gray-900">${{ number_format($currentProfit, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Meta Objetivo</p>
                                <p class="text-base font-semibold text-gray-900">${{ number_format($currentGoal->target_amount, 2) }}</p>
                            </div>
                            @if(!$isCompleted && !$isExpired)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1">Falta Alcanzar</p>
                                    <p class="text-base font-semibold text-gray-900">
                                        ${{ number_format(abs($remainingAmount), 2) }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1">
                                        @if($daysRemaining > 0)
                                            Promedio Diario ({{ $daysRemaining }}d)
                                        @else
                                            Último Día
                                        @endif
                                    </p>
                                    <p class="text-base font-semibold text-gray-900">
                                        ${{ number_format($dailyNeeded, 2) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Action Button --}}
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('goals.index') }}" 
                               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">
                                Ver todas las metas →
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- No Active Goal - Show Create Button --}}
                <div class="mb-6 bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5 text-center">
                        <h3 class="text-base font-semibold text-gray-900 mb-1">No hay una meta activa</h3>
                        <p class="text-sm text-gray-600 mb-4">Establece una meta de ganancia para hacer seguimiento del rendimiento</p>
                        <a href="{{ route('goals.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                            Crear Nueva Meta
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Productos -->
                <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Productos</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\Product::count() }}</p>
                    </div>
                </div>

                <!-- Categorías -->
                <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Categorías</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\Category::count() }}</p>
                    </div>
                </div>

                <!-- Clientes -->
                <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Clientes</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\Customer::count() }}</p>
                    </div>
                </div>

                <!-- Ventas Totales -->
                <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Ventas Totales</p>
                        <p class="text-3xl font-semibold text-gray-900">${{ number_format(\App\Models\Sale::sum('total'), 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <!-- Acciones Rápidas -->
                <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">Acciones Rápidas</h3>
                        <div class="space-y-2">
                            <a href="{{ route('pos.index') }}" class="block w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                                Punto de Venta
                            </a>
                            <a href="{{ route('products.index') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-900 border border-gray-200 font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                                Gestionar Productos
                            </a>
                            <a href="{{ route('reports.index') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-900 border border-gray-200 font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                                Ver Reportes
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Productos con Bajo Stock -->
                <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">Productos con Bajo Stock</h3>
                        <div class="space-y-2">
                            @php
                                $lowStockProducts = \App\Models\Product::where('stock', '<=', 10)->limit(5)->get();
                            @endphp
                            
                            @forelse($lowStockProducts as $product)
                                <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg border border-gray-100">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->sku }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-200 text-gray-700">
                                        {{ $product->stock }} unid.
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 text-sm">Stock suficiente en todos los productos</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bienvenida -->
            <div class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5">
                    <p class="text-gray-600 text-sm">¡Bienvenido, <span class="font-semibold text-gray-900">{{ Auth::user()->name }}</span>! Usa el menú de navegación para acceder a todas las funcionalidades.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
