<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" 
               class="w-12 h-12 bg-white border-2 border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 transition-all shadow-md">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900">Gestión de Gastos</h1>
                <p class="text-sm text-gray-600 mt-1">Registra y controla los gastos de tu negocio</p>
            </div>
        </div>
        <button wire:click="showCategoryForm = !showCategoryForm" 
                class="px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors shadow-md flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Nueva Categoría
        </button>
    </div>

    <!-- Category Form -->
    @if($showCategoryForm)
    <div class="bg-white rounded-2xl shadow-xl border-2 border-indigo-200 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-700">
            <h2 class="text-xl font-bold text-white">Nueva Categoría de Gasto</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre</label>
                    <input type="text" wire:model="categoryName" 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                           placeholder="Ej: Servicios públicos">
                    @error('categoryName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                    <input type="text" wire:model="categoryDescription" 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                           placeholder="Opcional">
                    @error('categoryDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Color</label>
                    <input type="color" wire:model="categoryColor" 
                           class="w-full h-10 rounded-lg border-gray-300">
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button wire:click="saveCategory" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors">
                    Guardar Categoría
                </button>
                <button wire:click="showCategoryForm = false" 
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition-colors">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Expense Form -->
    <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-orange-700">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $editingId ? 'Editar Gasto' : 'Registrar Nuevo Gasto' }}
            </h2>
        </div>

        <div class="p-6">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Categoría <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="expense_category_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Monto <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="amount" min="0" step="0.01"
                               class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                               placeholder="0.00">
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Expense Date -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Fecha del Gasto <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="expense_date" 
                               class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200">
                        @error('expense_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="description" 
                               class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                               placeholder="Descripción del gasto">
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Receipt Number -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            No. de Recibo
                        </label>
                        <input type="text" wire:model="receipt_number" 
                               class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                               placeholder="Opcional">
                        @error('receipt_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Notas
                        </label>
                        <textarea wire:model="notes" rows="2"
                                  class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                  placeholder="Notas adicionales"></textarea>
                        @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Attachment -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Adjunto (Foto/PDF)
                        </label>
                        <input type="file" wire:model="attachment" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        @error('attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Actualizar' : 'Registrar Gasto' }}
                    </button>

                    @if($editingId)
                    <button type="button" wire:click="resetForm" 
                            class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition-colors shadow-md">
                        Cancelar
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" wire:model.live="search" 
                   class="w-full rounded-xl border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 py-3 font-medium"
                   placeholder="Buscar gasto...">
        </div>
        <div>
            <input type="date" wire:model.live="dateFrom" 
                   class="w-full rounded-xl border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 py-3 font-medium">
        </div>
        <div>
            <input type="date" wire:model.live="dateTo" 
                   class="w-full rounded-xl border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 py-3 font-medium">
        </div>
        <div>
            <select wire:model.live="categoryFilter" 
                    class="w-full rounded-xl border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 py-3 font-bold">
                <option value="all">Todas las categorías</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Total Summary -->
    <div class="bg-gradient-to-r from-red-600 to-orange-700 rounded-2xl shadow-xl p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-bold opacity-90">Total de Gastos</div>
                <div class="text-3xl font-black mt-1">${{ number_format($totalExpenses, 0, ',', '.') }}</div>
            </div>
            <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Categoría</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Descripción</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Monto</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-gray-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $expense->expense_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold rounded-full" 
                                  style="background-color: {{ $expense->category->color }}20; color: {{ $expense->category->color }}">
                                {{ $expense->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $expense->description }}</div>
                            @if($expense->receipt_number)
                            <div class="text-xs text-gray-500">Recibo: {{ $expense->receipt_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-black text-red-600">${{ number_format($expense->amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $expense->id }})" 
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Editar
                                </button>
                                <button onclick="confirmDelete({{ $expense->id }})" 
                                        class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">No hay gastos registrados</h4>
                                <p class="text-gray-600 font-medium">Comienza registrando tu primer gasto</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(expenseId) {
        Swal.fire({
            title: '¿Eliminar gasto?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', expenseId);
            }
        });
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('expense-created', () => {
            Swal.fire({
                icon: 'success',
                title: 'Gasto registrado',
                text: 'El gasto se ha registrado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('expense-updated', () => {
            Swal.fire({
                icon: 'success',
                title: 'Gasto actualizado',
                text: 'El gasto se ha actualizado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('expense-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Gasto eliminado',
                text: 'El gasto se ha eliminado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('category-created', () => {
            Swal.fire({
                icon: 'success',
                title: 'Categoría creada',
                text: 'La categoría se ha creado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('expense-error', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: event.message || 'Ocurrió un error al procesar la solicitud',
                confirmButtonColor: '#DC2626'
            });
        });
    });
</script>
@endpush
