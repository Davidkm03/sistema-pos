<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ __('Dashboard') }}
            </h2>
            <span class="text-sm text-gray-600 hidden md:block">{{ setting('business_name', 'Sistema POS') }}</span>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Saludo Personal --}}
            <div class="mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="text-white">
                            <h3 class="text-2xl sm:text-3xl font-bold mb-2">
                                ¡Hola, {{ Auth::user()->name }}! 👋
                            </h3>
                            <p class="text-indigo-100 text-sm sm:text-base">
                                {{ now()->format('l, d \d\e F \d\e Y') }}
                            </p>
                        </div>
                        <a href="{{ route('pos.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-600 rounded-xl font-bold hover:bg-indigo-50 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Ir a Vender
                        </a>
                    </div>
                </div>
            </div>

            {{-- Banner de Configuración de Negocio --}}
            @php
                $hasBusinessConfig = \App\Models\BusinessSetting::where('user_id', auth()->id())->exists();
            @endphp
            
            @if(!$hasBusinessConfig)
                <div class="mb-6 bg-yellow-50 border-2 border-yellow-200 rounded-xl overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">⚙️ Configura tu negocio</h3>
                                <p class="text-sm text-gray-700 mb-3">Personaliza el nombre, logo y datos de tu negocio para que aparezcan en los tickets.</p>
                                <a href="{{ route('settings.business') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-bold rounded-lg transition-colors shadow-md">
                                    Configurar Ahora
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tarjetas de Estadísticas Principales --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
                <!-- Total Productos -->
                <div class="bg-white rounded-xl shadow-md border-2 border-transparent hover:border-indigo-500 transition-all overflow-hidden group">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                                <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Productos</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-900">{{ \App\Models\Product::count() }}</p>
                        <p class="text-xs text-gray-500 mt-2">Total en inventario</p>
                    </div>
                </div>

                <!-- Ventas del Día -->
                <div class="bg-white rounded-xl shadow-md border-2 border-transparent hover:border-green-500 transition-all overflow-hidden group">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-600 transition-colors">
                                <svg class="w-7 h-7 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <a href="{{ route('sales.index') }}" class="text-gray-400 hover:text-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Ventas Hoy</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-900">
                            ${{ number_format(\App\Models\Sale::whereDate('created_at', today())->where('status', '!=', 'cancelada')->sum('total'), 0) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">{{ \App\Models\Sale::whereDate('created_at', today())->where('status', '!=', 'cancelada')->count() }} transacciones</p>
                    </div>
                </div>

                <!-- Clientes -->
                <div class="bg-white rounded-xl shadow-md border-2 border-transparent hover:border-purple-500 transition-all overflow-hidden group">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-600 transition-colors">
                                <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Clientes</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-900">{{ \App\Models\Customer::count() }}</p>
                        <p class="text-xs text-gray-500 mt-2">Registrados</p>
                    </div>
                </div>

                <!-- Ventas Totales -->
                <div class="bg-white rounded-xl shadow-md border-2 border-transparent hover:border-blue-500 transition-all overflow-hidden group">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                                <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <a href="{{ route('reports.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Ventas</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-900">
                            ${{ number_format(\App\Models\Sale::where('status', '!=', 'cancelada')->sum('total'), 0) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">Histórico completo</p>
                    </div>
                </div>
            </div>

            {{-- Meta Activa --}}
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
                    $progressWidth = min($progress, 100);
                @endphp
                
                <div class="mb-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-md border-2 border-amber-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $currentGoal->name }}</h3>
                                    <p class="text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($currentGoal->start_date)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($currentGoal->end_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($isCompleted)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white font-bold rounded-full text-xs shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Completada
                                    </span>
                                @elseif($isExpired)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-500 text-white font-bold rounded-full text-xs shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Expirada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-500 text-white font-bold rounded-full text-xs shadow-md animate-pulse">
                                        <span class="w-2 h-2 bg-white rounded-full"></span>
                                        Activa
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="mb-5">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-bold text-gray-700">Progreso</span>
                                <span class="text-lg font-black text-amber-600">{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-4 overflow-hidden shadow-inner border-2 border-amber-200">
                                <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-amber-400 to-orange-500 shadow-lg" 
                                     style="width: {{ $progressWidth }}%">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                            <div class="bg-white rounded-lg p-4 border-2 border-amber-100 shadow-sm">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Ganancia Actual</p>
                                <p class="text-xl font-black text-gray-900">${{ number_format($currentProfit, 0) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 border-2 border-amber-100 shadow-sm">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Meta Objetivo</p>
                                <p class="text-xl font-black text-gray-900">${{ number_format((float)$currentGoal->target_amount, 0) }}</p>
                            </div>
                            @if(!$isCompleted && !$isExpired)
                                <div class="bg-white rounded-lg p-4 border-2 border-amber-100 shadow-sm">
                                    <p class="text-xs text-gray-600 font-semibold mb-1">Falta Alcanzar</p>
                                    <p class="text-xl font-black text-gray-900">
                                        ${{ number_format(abs($remainingAmount), 0) }}
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border-2 border-amber-100 shadow-sm">
                                    <p class="text-xs text-gray-600 font-semibold mb-1">
                                        @if($daysRemaining > 0)
                                            📅 Diario ({{ $daysRemaining }}d)
                                        @else
                                            ⏰ Último Día
                                        @endif
                                    </p>
                                    <p class="text-xl font-black text-gray-900">
                                        ${{ number_format($dailyNeeded, 0) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="{{ route('goals.index') }}" 
                               class="inline-flex items-center gap-2 text-sm text-amber-700 hover:text-amber-900 font-bold transition-colors">
                                Ver todas las metas
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- No Active Goal --}}
                <div class="mb-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl shadow-md border-2 border-gray-200 overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sin meta activa</h3>
                        <p class="text-sm text-gray-600 mb-5">Establece una meta de ganancia para hacer seguimiento de tu rendimiento</p>
                        <a href="{{ route('goals.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Nueva Meta
                        </a>
                    </div>
                </div>
            @endif

            {{-- Grid de Acciones Rápidas y Productos Bajo Stock --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <a href="{{ route('pos.index') }}" 
                           class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <span class="flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Punto de Venta
                            </span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>

                        <a href="{{ route('pos.mobile') }}" 
                           class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 text-gray-900 border-2 border-gray-200 hover:border-indigo-500 font-bold rounded-xl transition-all">
                            <span class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                POS Móvil
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>

                        <a href="{{ route('products.index') }}" 
                           class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 text-gray-900 border-2 border-gray-200 hover:border-indigo-500 font-bold rounded-xl transition-all">
                            <span class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Gestionar Productos
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>

                        <a href="{{ route('reports.index') }}" 
                           class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 text-gray-900 border-2 border-gray-200 hover:border-indigo-500 font-bold rounded-xl transition-all">
                            <span class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Ver Reportes
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Productos con Bajo Stock -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Productos con Bajo Stock
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @php
                                $lowStockProducts = \App\Models\Product::where('stock', '<=', 10)->orderBy('stock', 'asc')->limit(10)->get();
                            @endphp
                            
                            @forelse($lowStockProducts as $product)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-2 border-red-200 hover:border-red-400 transition-all">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-10 h-10 bg-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 object-contain rounded-lg">
                                            @else
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 text-sm truncate">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-600">SKU: {{ $product->sku }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-black bg-red-500 text-white shadow-md flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ $product->stock }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 font-semibold">¡Todo bien!</p>
                                    <p class="text-gray-500 text-sm">Stock suficiente en todos los productos</p>
                                </div>
                            @endforelse
                        </div>
                        
                        @if($lowStockProducts->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('products.index') }}" 
                                   class="inline-flex items-center gap-2 text-sm text-red-600 hover:text-red-800 font-bold transition-colors">
                                    Ver todos los productos
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
