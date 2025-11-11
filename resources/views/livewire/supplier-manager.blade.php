<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Gestion de Proveedores
                </h2>
                <p class="mt-1 text-sm text-gray-600">Administra tus proveedores y contactos</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 mb-6 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ $editingId ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
                </h3>
            </div>
            
            <form wire:submit.prevent="save" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre del Proveedor *
                        </label>
                        <input type="text"
                               id="name"
                               wire:model="name"
                               placeholder="Ej: Distribuidora ABC"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact Name --}}
                    <div>
                        <label for="contact_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Persona de Contacto
                        </label>
                        <input type="text"
                               id="contact_name"
                               wire:model="contact_name"
                               placeholder="Ej: Juan Perez"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('contact_name') border-red-500 @enderror">
                        @error('contact_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Correo Electronico
                        </label>
                        <input type="email"
                               id="email"
                               wire:model="email"
                               placeholder="correo@ejemplo.com"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Telefono
                        </label>
                        <input type="text"
                               id="phone"
                               wire:model="phone"
                               placeholder="Ej: 3001234567"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Direccion
                        </label>
                        <input type="text"
                               id="address"
                               wire:model="address"
                               placeholder="Calle 123 #45-67, Bogota"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Notas
                        </label>
                        <textarea id="notes"
                                  wire:model="notes"
                                  rows="3"
                                  placeholder="Notas adicionales sobre el proveedor..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('notes') border-red-500 @enderror"></textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Active Status --}}
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="is_active"
                               wire:model="is_active"
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">
                            Proveedor Activo
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Actualizar' : 'Guardar' }} Proveedor
                    </button>

                    @if($editingId)
                        <button type="button"
                                wire:click="resetForm"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </button>
                    @endif
                </div>
            </form>
        </div>

        {{-- Search Bar --}}
        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text"
                       wire:model.live="search"
                       placeholder="Buscar por nombre, contacto, email o telefono..."
                       class="flex-1 px-4 py-2 border-0 focus:ring-0 font-medium placeholder-gray-400">
            </div>
        </div>

        {{-- Suppliers Table --}}
        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 border-b-2 border-gray-100">
                <h3 class="text-lg font-semibold text-white">Lista de Proveedores</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Telefono</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 transition-colors" wire:key="supplier-{{ $supplier->id }}">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $supplier->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $supplier->contact_name ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $supplier->email ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $supplier->phone ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleActive({{ $supplier->id }})"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold transition-all {{ $supplier->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                        {{ $supplier->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit({{ $supplier->id }})"
                                                class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="window.confirmDelete({{ $supplier->id }}, `{{ $supplier->name }}`)"
                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-lg font-semibold text-gray-500">No hay proveedores registrados</p>
                                        <p class="text-sm text-gray-400">Crea tu primer proveedor usando el formulario arriba</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($suppliers->hasPages())
                <div class="px-6 py-4 border-t-2 border-gray-100">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.confirmDelete = function(id, name) {
        if (confirm(`¿Estas seguro de eliminar el proveedor "${name}"?`)) {
            @this.call('delete', id);
        }
    };

    // Toast notifications
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('supplier-created', (event) => {
            showToast(event.message, 'success');
        });

        Livewire.on('supplier-updated', (event) => {
            showToast(event.message, 'success');
        });

        Livewire.on('supplier-deleted', (event) => {
            showToast(event.message, 'success');
        });

        Livewire.on('supplier-toggled', (event) => {
            showToast(event.message, 'success');
        });

        Livewire.on('supplier-error', (event) => {
            showToast(event.message, 'error');
        });
    });

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg text-white font-semibold z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endpush
