<div>
    <style>[x-cloak] { display: none !important; }</style>
    
    {{-- Header Modernizado --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-amber-600 rounded-2xl shadow-2xl flex items-center justify-center transform hover:scale-105 transition-transform">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Gestión de Metas</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">Establece y monitorea tus objetivos de ganancia</p>
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
            {{-- Goal Form - Only show if user can create/edit goals --}}
            @can('create-goals')
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-orange-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-amber-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">{{ $editingId ? 'Editar Meta' : 'Nueva Meta de Ganancia' }}</h3>
                            <p class="text-xs text-orange-100">{{ $editingId ? 'Actualiza los datos de la meta' : 'Crea una nueva meta para tu negocio' }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                <div class="p-6">
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    Nombre de la Meta
                                </label>
                                <input type="text" 
                                       id="name"
                                       wire:model="name" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold"
                                       placeholder="Ej: Meta Mensual Enero 2025">
                                @error('name')
                                    <span class="text-red-500 text-sm font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Target Amount --}}
                            <div>
                                <label for="target_amount" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Monto Objetivo ($)
                                </label>
                                <input type="number" 
                                       id="target_amount"
                                       wire:model="target_amount" 
                                       step="0.01"
                                       min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold"
                                       placeholder="0.00">
                                @error('target_amount')
                                    <span class="text-red-500 text-sm font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label for="start_date" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha de Inicio
                                </label>
                                <input type="date" 
                                       id="start_date"
                                       wire:model="start_date" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold">
                                @error('start_date')
                                    <span class="text-red-500 text-sm font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label for="end_date" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha de Fin
                                </label>
                                <input type="date" 
                                       id="end_date"
                                       wire:model="end_date" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold">
                                @error('end_date')
                                    <span class="text-red-500 text-sm font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" 
                                    style="background: linear-gradient(135deg, #EA580C 0%, #F59E0B 100%) !important;"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 text-white rounded-xl font-bold hover:from-orange-700 hover:to-amber-700 focus:ring-4 focus:ring-orange-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $editingId ? 'Actualizar Meta' : 'Crear Meta' }}
                            </button>
                            @if($editingId)
                                <button type="button" 
                                        wire:click="resetForm"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:ring-4 focus:ring-gray-200 transition-all shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancelar
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            @endcan

            {{-- Goals List --}}
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-orange-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-amber-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Metas Registradas</h3>
                            <p class="text-xs text-orange-100">Monitorea el progreso de tus objetivos</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($goals->isEmpty())
                        <div class="text-center py-16">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 mb-3">No hay metas registradas</h3>
                            <p class="text-gray-600 font-medium">Crea tu primera meta para comenzar a monitorear tu progreso</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($goals as $goal)
                                @php
                                    $currentProfit = $goal->getCurrentProfit();
                                    $progress = $goal->getProgressPercentage();
                                    $daysRemaining = $goal->getDaysRemaining();
                                    $remainingAmount = $goal->getRemainingAmount();
                                    $dailyNeeded = $goal->getDailyProfitNeeded();
                                    
                                    // Determine card color based on status
                                    $cardClass = match($goal->status) {
                                        'completed' => 'border-green-500 bg-gradient-to-br from-green-50 to-emerald-50',
                                        'cancelled' => 'border-gray-400 bg-gradient-to-br from-gray-50 to-slate-50',
                                        'active' => $goal->isExpired() 
                                            ? 'border-red-500 bg-gradient-to-br from-red-50 to-pink-50' 
                                            : 'border-blue-500 bg-gradient-to-br from-blue-50 to-cyan-50',
                                        default => 'border-gray-300 bg-white',
                                    };
                                    
                                    $statusBadge = match($goal->status) {
                                        'completed' => '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r from-green-500 to-emerald-500 shadow-md">✓ Completada</span>',
                                        'cancelled' => '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r from-gray-500 to-slate-500 shadow-md">✕ Cancelada</span>',
                                        'active' => $goal->isExpired() 
                                            ? '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r from-red-500 to-pink-500 shadow-md">⚠ Expirada</span>'
                                            : '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gradient-to-r from-blue-500 to-cyan-500 shadow-md">● Activa</span>',
                                        default => '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-bold text-xs text-white bg-gray-400 shadow-md">?</span>',
                                    };
                                @endphp

                                <div class="border-l-4 {{ $cardClass }} rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    {{-- Header --}}
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h4 class="font-black text-gray-900 text-lg mb-1">
                                                {{ $goal->name }}
                                            </h4>
                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-semibold">{{ \Carbon\Carbon::parse($goal->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($goal->end_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ml-2">
                                            {!! $statusBadge !!}
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                                Progreso
                                            </span>
                                            <span class="text-sm font-black {{ $progress >= 100 ? 'text-green-600' : 'text-blue-600' }}">{{ number_format($progress, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 shadow-inner overflow-hidden">
                                            <div class="h-4 rounded-full transition-all duration-500 {{ $progress >= 100 ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-blue-500 to-cyan-500' }}" 
                                                 style="width: {{ min($progress, 100) }}%">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Stats --}}
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between items-center text-sm bg-white/50 rounded-lg px-3 py-2">
                                            <span class="text-gray-700 font-semibold flex items-center gap-1">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Ganancia Actual:
                                            </span>
                                            <span class="font-black text-blue-600">
                                                ${{ number_format($currentProfit, 2) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm bg-white/50 rounded-lg px-3 py-2">
                                            <span class="text-gray-700 font-semibold flex items-center gap-1">
                                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                                </svg>
                                                Meta:
                                            </span>
                                            <span class="font-black text-orange-600">
                                                ${{ number_format($goal->target_amount, 2) }}
                                            </span>
                                        </div>
                                        @if($goal->status === 'active' && !$goal->isExpired())
                                            <div class="flex justify-between items-center text-sm bg-white/50 rounded-lg px-3 py-2">
                                                <span class="text-gray-700 font-semibold flex items-center gap-1">
                                                    <svg class="w-4 h-4 {{ $remainingAmount > 0 ? 'text-amber-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                    </svg>
                                                    Falta:
                                                </span>
                                                <span class="font-black {{ $remainingAmount > 0 ? 'text-amber-600' : 'text-green-600' }}">
                                                    ${{ number_format(abs($remainingAmount), 2) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center text-sm bg-white/50 rounded-lg px-3 py-2">
                                                <span class="text-gray-700 font-semibold flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Días restantes:
                                                </span>
                                                <span class="font-black text-purple-600">
                                                    {{ $daysRemaining }}
                                                </span>
                                            </div>
                                            @if($daysRemaining > 0 && $remainingAmount > 0)
                                                <div class="flex justify-between items-center text-sm bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg px-3 py-2 border border-indigo-200">
                                                    <span class="text-gray-700 font-semibold flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                        </svg>
                                                        Promedio diario:
                                                    </span>
                                                    <span class="font-black text-indigo-600">
                                                        ${{ number_format($dailyNeeded, 2) }}
                                                    </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    @if($goal->status !== 'cancelled')
                                        <div class="flex gap-2 mt-4 pt-4 border-t-2 border-gray-200">
                                            @can('edit-goals')
                                                @if($goal->status === 'active' && !$goal->isExpired())
                                                    <button wire:click="edit({{ $goal->id }})" 
                                                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg font-bold text-xs hover:from-yellow-600 hover:to-amber-600 focus:ring-4 focus:ring-yellow-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar
                                                    </button>
                                                @endif
                                                
                                                @if($goal->status === 'active' && $progress >= 100 && !$goal->isCompleted())
                                                    <button onclick="confirmComplete({{ $goal->id }})" 
                                                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg font-bold text-xs hover:from-green-600 hover:to-emerald-600 focus:ring-4 focus:ring-green-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Completar
                                                    </button>
                                                @endif

                                                @if($goal->status === 'active')
                                                    <button onclick="confirmCancel({{ $goal->id }})" 
                                                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg font-bold text-xs hover:from-red-600 hover:to-pink-600 focus:ring-4 focus:ring-red-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Cancelar
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6 px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-t-2 border-orange-100 rounded-b-xl">
                            {{ $goals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for SweetAlert confirmations --}}
    <script>
        function confirmCancel(goalId) {
            Swal.fire({
                title: '¿Cancelar esta meta?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, mantener'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('cancel', goalId);
                }
            });
        }

        function confirmComplete(goalId) {
            Swal.fire({
                title: '¿Marcar como completada?',
                text: "Esta meta se marcará como completada exitosamente",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, completar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('markCompleted', goalId);
                }
            });
        }

        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('goal-saved', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: event.isEdit ? '¡Meta Actualizada!' : '¡Meta Creada!',
                    text: event.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            Livewire.on('goal-cancelled', () => {
                Swal.fire({
                    icon: 'info',
                    title: 'Meta Cancelada',
                    text: 'La meta ha sido cancelada',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            Livewire.on('goal-completed', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Meta Completada!',
                    text: '¡Felicitaciones! La meta ha sido marcada como completada',
                    timer: 2500,
                    showConfirmButton: false
                });
            });

            Livewire.on('goal-error', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: event.message,
                    confirmButtonColor: '#3b82f6'
                });
            });
        });
    </script>
</div>
