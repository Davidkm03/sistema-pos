<div>
    {{-- Goal Form - Only show if user can create/edit goals --}}
    @can('create-goals')
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
            {{ $editingId ? 'Editar Meta' : 'Nueva Meta de Ganancia' }}
        </h3>

        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre de la Meta
                    </label>
                    <input type="text" 
                           id="name"
                           wire:model="name" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Meta Mensual Enero 2025">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Target Amount --}}
                <div>
                    <label for="target_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Monto Objetivo ($)
                    </label>
                    <input type="number" 
                           id="target_amount"
                           wire:model="target_amount" 
                           step="0.01"
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="0.00">
                    @error('target_amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha de Inicio
                    </label>
                    <input type="date" 
                           id="start_date"
                           wire:model="start_date" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha de Fin
                    </label>
                    <input type="date" 
                           id="end_date"
                           wire:model="end_date" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    {{ $editingId ? 'Actualizar' : 'Crear Meta' }}
                </button>
                @if($editingId)
                    <button type="button" 
                            wire:click="resetForm"
                            class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                        Cancelar
                    </button>
                @endif
            </div>
        </form>
    </div>
    @endcan

    {{-- Goals List --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
            Metas Registradas
        </h3>

        @if($goals->isEmpty())
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                No hay metas registradas aún.
            </p>
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
                            'completed' => 'border-green-500 bg-green-50 dark:bg-green-900/20',
                            'cancelled' => 'border-gray-400 bg-gray-50 dark:bg-gray-700',
                            'active' => $goal->isExpired() 
                                ? 'border-red-500 bg-red-50 dark:bg-red-900/20' 
                                : 'border-blue-500 bg-blue-50 dark:bg-blue-900/20',
                            default => 'border-gray-300 bg-white dark:bg-gray-800',
                        };
                        
                        $statusBadge = match($goal->status) {
                            'completed' => '<span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">✓ Completada</span>',
                            'cancelled' => '<span class="px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">✕ Cancelada</span>',
                            'active' => $goal->isExpired() 
                                ? '<span class="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">⚠ Expirada</span>'
                                : '<span class="px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">● Activa</span>',
                            default => '<span class="px-2 py-1 bg-gray-400 text-white text-xs font-semibold rounded-full">?</span>',
                        };
                    @endphp

                    <div class="border-l-4 {{ $cardClass }} rounded-lg p-4 shadow-sm">
                        {{-- Header --}}
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-gray-100 text-base">
                                    {{ $goal->name }}
                                </h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($goal->start_date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($goal->end_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                {!! $statusBadge !!}
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mb-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Progreso</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-gray-100">{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all {{ $progress >= 100 ? 'bg-green-500' : 'bg-blue-500' }}" 
                                     style="width: {{ min($progress, 100) }}%">
                                </div>
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-1 mb-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Ganancia Actual:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    ${{ number_format($currentProfit, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Meta:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    ${{ number_format($goal->target_amount, 2) }}
                                </span>
                            </div>
                            @if($goal->status === 'active' && !$goal->isExpired())
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Falta:</span>
                                    <span class="font-semibold {{ $remainingAmount > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                        ${{ number_format(abs($remainingAmount), 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Días restantes:</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $daysRemaining }}
                                    </span>
                                </div>
                                @if($daysRemaining > 0 && $remainingAmount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Promedio diario:</span>
                                        <span class="font-semibold text-blue-600 dark:text-blue-400">
                                            ${{ number_format($dailyNeeded, 2) }}
                                        </span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Actions --}}
                        @if($goal->status !== 'cancelled')
                            <div class="flex gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                @can('edit-goals')
                                    @if($goal->status === 'active' && !$goal->isExpired())
                                        <button wire:click="edit({{ $goal->id }})" 
                                                class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded transition-colors">
                                            Editar
                                        </button>
                                    @endif
                                    
                                    @if($goal->status === 'active' && $progress >= 100 && !$goal->isCompleted())
                                        <button onclick="confirmComplete({{ $goal->id }})" 
                                                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded transition-colors">
                                            ✓ Completar
                                        </button>
                                    @endif

                                    @if($goal->status === 'active')
                                        <button onclick="confirmCancel({{ $goal->id }})" 
                                                class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors">
                                            ✕ Cancelar
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $goals->links() }}
            </div>
        @endif
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
