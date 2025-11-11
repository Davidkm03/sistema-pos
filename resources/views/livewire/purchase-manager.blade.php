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
                <h1 class="text-3xl font-black text-gray-900">Gestión de Compras</h1>
                <p class="text-sm text-gray-600 mt-1">Registra órdenes de compra y actualiza el inventario</p>
            </div>
        </div>
    </div>

    <!-- New Purchase Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Left Column - Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-700">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Nueva Orden de Compra
                    </h2>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Supplier -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Proveedor <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="supplier_id" 
                                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha de Compra <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="purchase_date" 
                                       class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                                @error('purchase_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Notas
                            </label>
                            <textarea wire:model="notes" rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                                      placeholder="Observaciones sobre la compra"></textarea>
                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Add Product Section -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6 border-2 border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-3">Agregar Producto</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div class="md:col-span-2">
                                    <select wire:model="selectedProduct" 
                                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm">
                                        <option value="">Seleccionar producto</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <input type="number" wire:model="productQuantity" min="1"
                                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm"
                                           placeholder="Cantidad">
                                </div>
                                <div>
                                    <input type="number" wire:model="productCost" min="0" step="0.01"
                                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm"
                                           placeholder="Costo">
                                </div>
                            </div>
                            <button type="button" wire:click="addToCart" 
                                    class="mt-3 w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors text-sm">
                                Agregar al Carrito
                            </button>
                        </div>

                        <!-- Cart Items -->
                        @if(count($cart) > 0)
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-700 mb-3">Items de la Compra</h3>
                            <div class="space-y-2">
                                @foreach($cart as $productId => $item)
                                <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200">
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-900 text-sm">{{ $item['name'] }}</div>
                                        <div class="text-xs text-gray-600">
                                            {{ $item['quantity'] }} x ${{ number_format($item['unit_cost'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="text-right mr-4">
                                        <div class="font-black text-gray-900">${{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                    </div>
                                    <button type="button" wire:click="removeFromCart({{ $productId }})"
                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold">
                                        Quitar
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Error message for empty cart -->
                        @error('cart') <div class="text-red-500 text-sm mb-4">{{ $message }}</div> @enderror

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors shadow-md flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Compra
                            </button>

                            <button type="button" wire:click="resetForm" 
                                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition-colors shadow-md">
                                Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden sticky top-8">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700">
                    <h2 class="text-xl font-bold text-white">Resumen</h2>
                </div>

                <div class="p-6">
                    @php
                        $subtotal = collect($cart)->sum('subtotal');
                        $tax = 0;
                        $total = $subtotal + $tax;
                    @endphp

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-bold">Productos:</span>
                            <span class="text-gray-900 font-black">{{ count($cart) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-bold">Unidades:</span>
                            <span class="text-gray-900 font-black">{{ collect($cart)->sum('quantity') }}</span>
                        </div>

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <span class="text-gray-600 font-bold">Subtotal:</span>
                            <span class="text-gray-900 font-black">${{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-bold">IVA:</span>
                            <span class="text-gray-900 font-black">${{ number_format($tax, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between pt-4 border-t-2 border-gray-300">
                            <span class="text-lg font-black text-gray-900">TOTAL:</span>
                            <span class="text-2xl font-black text-purple-600">${{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" wire:model.live="search" 
                   class="w-full rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 pl-12 py-3 font-medium"
                   placeholder="Buscar por número o proveedor...">
            <svg class="absolute left-4 top-3.5 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="statusFilter" 
                    class="w-full rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 py-3 font-bold">
                <option value="all">Todos los estados</option>
                <option value="pending">Pendientes</option>
                <option value="received">Recibidas</option>
                <option value="cancelled">Canceladas</option>
            </select>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Número</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Proveedor</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-gray-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $purchase->purchase_number }}</div>
                            <div class="text-xs text-gray-500">{{ $purchase->items->count() }} items</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $purchase->supplier->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purchase->purchase_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-black text-gray-900">${{ number_format($purchase->total, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $purchase->status === 'received' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $purchase->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $purchase->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @if($purchase->status === 'pending')
                                <button onclick="confirmReceive({{ $purchase->id }})" 
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Recibir
                                </button>
                                <button onclick="confirmCancel({{ $purchase->id }})" 
                                        class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Cancelar
                                </button>
                                @endif
                                <button onclick="confirmDelete({{ $purchase->id }})" 
                                        class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-xs font-bold">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">No hay compras registradas</h4>
                                <p class="text-gray-600 font-medium">Crea tu primera orden de compra usando el formulario</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $purchases->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmReceive(purchaseId) {
        Swal.fire({
            title: '¿Recibir compra?',
            text: 'Se actualizará el inventario con los productos de esta compra',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, recibir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('receivePurchase', purchaseId);
            }
        });
    }

    function confirmCancel(purchaseId) {
        Swal.fire({
            title: '¿Cancelar compra?',
            text: 'La compra quedará marcada como cancelada',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EAB308',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('cancelPurchase', purchaseId);
            }
        });
    }

    function confirmDelete(purchaseId) {
        Swal.fire({
            title: '¿Eliminar compra?',
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
                @this.call('delete', purchaseId);
            }
        });
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('purchase-created', () => {
            Swal.fire({
                icon: 'success',
                title: 'Compra registrada',
                text: 'La orden de compra se ha creado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('purchase-received', () => {
            Swal.fire({
                icon: 'success',
                title: 'Compra recibida',
                text: 'El inventario se ha actualizado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('purchase-cancelled', () => {
            Swal.fire({
                icon: 'info',
                title: 'Compra cancelada',
                text: 'La compra se ha cancelado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('purchase-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Compra eliminada',
                text: 'La compra se ha eliminado correctamente',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        Livewire.on('purchase-error', (event) => {
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
