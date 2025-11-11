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
                <h1 class="text-3xl font-black text-gray-900">Gestión de Clientes</h1>
                <p class="text-sm text-gray-600 mt-1">Administra tus clientes y consulta su historial de compras</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ $editingId ? 'Editar Cliente' : 'Nuevo Cliente' }}
            </h2>
        </div>

        <div class="p-6">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="Nombre completo del cliente">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="text" wire:model="phone" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="Número de teléfono">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" wire:model="email" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="correo@ejemplo.com">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tax ID Type -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Tipo de Documento <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="tax_id_type" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                        @error('tax_id_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tax ID -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Número de Documento
                        </label>
                        <input type="text" wire:model="tax_id" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="Número de documento">
                        @error('tax_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tax Regime -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Régimen Tributario <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="tax_regime" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <option value="simplified">Régimen Simplificado</option>
                            <option value="common">Régimen Común</option>
                        </select>
                        @error('tax_regime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Retention Agent -->
                    <div class="flex items-center pt-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_retention_agent" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-200 mr-2">
                            <span class="text-sm font-bold text-gray-700">Es agente de retención</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Actualizar' : 'Guardar' }}
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

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" wire:model.live="search" 
                   class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 font-medium"
                   placeholder="Buscar cliente por nombre, teléfono, email o documento...">
            <svg class="absolute left-4 top-3.5 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                            Documento
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                            Régimen
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-black text-gray-700 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $customer->name }}</div>
                            @if($customer->email)
                            <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($customer->phone)
                            <div class="text-sm text-gray-900">{{ $customer->phone }}</div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <span class="font-bold text-gray-700">{{ $customer->tax_id_type }}:</span>
                                <span class="text-gray-900">{{ $customer->tax_id ?: 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $customer->tax_regime === 'common' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                                {{ $customer->tax_regime === 'common' ? 'Común' : 'Simplificado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="viewHistory({{ $customer->id }})" 
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Historial
                                </button>
                                <button wire:click="edit({{ $customer->id }})" 
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Editar
                                </button>
                                <button onclick="confirmDelete({{ $customer->id }})" 
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">No hay clientes registrados</h4>
                                <p class="text-gray-600 font-medium">Comienza agregando tu primer cliente usando el formulario de arriba</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

    <!-- History Modal -->
    @if($showHistoryModal && $selectedCustomer)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeHistoryModal">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden" wire:click.stop>
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Historial de Compras</h3>
                <button wire:click="closeHistoryModal" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <!-- Customer Info -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">{{ $selectedCustomer->name }}</h4>
                    @php
                        $stats = $selectedCustomer->getStatistics();
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-sm font-bold text-blue-700">Total Compras</div>
                            <div class="text-2xl font-black text-blue-900">{{ $stats['total_purchases'] }}</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="text-sm font-bold text-green-700">Total Gastado</div>
                            <div class="text-2xl font-black text-green-900">${{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="text-sm font-bold text-purple-700">Ticket Promedio</div>
                            <div class="text-2xl font-black text-purple-900">${{ number_format($stats['average_ticket'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Sales -->
                <h5 class="text-md font-bold text-gray-900 mb-4">Últimas 10 Compras</h5>
                <div class="space-y-3">
                    @forelse($selectedCustomer->sales as $sale)
                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-bold text-gray-900">Venta #{{ $sale->id }}</div>
                                <div class="text-sm text-gray-600">{{ $sale->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-black text-gray-900">${{ number_format($sale->total, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-600">{{ $sale->items->count() }} items</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-8">No hay compras registradas</div>
                    @endforelse
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button wire:click="closeHistoryModal" 
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(customerId) {
        Swal.fire({
            title: '¿Estás seguro?',
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
                @this.call('delete', customerId);
            }
        });
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('customer-created', () => {
            Swal.fire({
                icon: 'success',
                title: 'Cliente creado',
                text: 'El cliente se ha registrado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('customer-updated', () => {
            Swal.fire({
                icon: 'success',
                title: 'Cliente actualizado',
                text: 'Los datos se han actualizado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('customer-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Cliente eliminado',
                text: 'El cliente se ha eliminado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('customer-error', (event) => {
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
