<div class="max-w-7xl mx-auto p-6 space-y-6">
    
    {{-- SECCIÓN 1 - FORMULARIO --}}
    @can('create-products')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                @if($editingId)
                    Editar Producto
                @else
                    Nuevo Producto
                @endif
            </h3>
        </div>
        
        <form wire:submit.prevent="save" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" 
                           id="name"
                           wire:model="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select id="category_id"
                            wire:model="category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SKU --}}
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" 
                           id="sku"
                           wire:model="sku" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                    <input type="number" 
                           id="price"
                           step="0.01"
                           wire:model="price" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cost --}}
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Costo</label>
                    <input type="number" 
                           id="cost"
                           step="0.01"
                           wire:model="cost" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cost') border-red-500 @enderror">
                    @error('cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number"
                           id="stock"
                           wire:model="stock"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TAX CONFIGURATION --}}
                @if(tax_enabled())
                    <div class="md:col-span-2 lg:col-span-3 border-t pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">⚖️ Configuración de IVA</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tax Type --}}
                            <div>
                                <label for="tax_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de IVA</label>
                                <select id="tax_type"
                                        wire:model.live="tax_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tax_type') border-red-500 @enderror">
                                    <option value="standard">Estándar ({{ get_tax_rate() }}%)</option>
                                    <option value="exempt">Exento (0%) - Con derecho</option>
                                    <option value="excluded">Excluido (0%) - Sin derecho</option>
                                    <option value="custom">Tasa Personalizada</option>
                                </select>
                                @error('tax_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Custom Tax Rate --}}
                            @if($tax_type === 'custom')
                                <div>
                                    <label for="custom_tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tasa Personalizada (%)</label>
                                    <input type="number"
                                           id="custom_tax_rate"
                                           step="0.01"
                                           wire:model="custom_tax_rate"
                                           placeholder="Ej: 5"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('custom_tax_rate') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Ej: 5 para productos de canasta básica</p>
                                    @error('custom_tax_rate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- Price Preview --}}
                        @if($price)
                            <div class="bg-gray-50 border border-gray-200 rounded p-3 mt-3">
                                @if(tax_included_in_price())
                                    <p class="text-sm text-gray-700">
                                        <strong>Precio ingresado:</strong> {{ format_currency($price) }} <span class="text-gray-500">(IVA incluido)</span>
                                    </p>
                                @else
                                    <p class="text-sm text-gray-700">
                                        <strong>Precio ingresado:</strong> {{ format_currency($price) }} <span class="text-gray-500">(sin IVA)</span>
                                    </p>
                                    @php
                                        $taxRate = $tax_type === 'custom' ? ($custom_tax_rate ?? 0) : ($tax_type === 'standard' ? get_tax_rate() : 0);
                                        $priceWithTax = calculate_price_with_tax($price, $taxRate);
                                    @endphp
                                    <p class="text-sm text-gray-700 mt-1">
                                        <strong>Precio final:</strong> {{ format_currency($priceWithTax) }} <span class="text-green-600">(con IVA)</span>
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Image --}}
                <div class="md:col-span-2 lg:col-span-3">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    
                    <div class="flex flex-col space-y-3">
                        {{-- File input con opción de cámara --}}
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="file"
                                   id="image"
                                   wire:model="image"
                                   accept="image/*"
                                   capture="environment"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                            
                            {{-- Botón para analizar con IA --}}
                            @if($image)
                                <button type="button"
                                        wire:click="analyzeImage"
                                        wire:loading.attr="disabled"
                                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-md hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50 whitespace-nowrap">
                                    <span wire:loading.remove wire:target="analyzeImage">🤖 Analizar con IA</span>
                                    <span wire:loading wire:target="analyzeImage">⏳ Analizando...</span>
                                </button>
                            @endif
                        </div>
                        
                        {{-- Preview de la imagen --}}
                        @if($image)
                            <div class="mt-2">
                                <img src="{{ $image->temporaryUrl() }}" 
                                     alt="Preview" 
                                     class="h-32 w-auto object-contain rounded-lg border border-gray-300">
                            </div>
                        @endif
                        
                        {{-- Descripción sugerida por IA --}}
                        @if(session()->has('ai_description'))
                            <div class="mt-2 p-3 bg-purple-50 border border-purple-200 rounded-md">
                                <p class="text-xs font-semibold text-purple-800 mb-1">💡 Descripción sugerida por IA:</p>
                                <p class="text-sm text-purple-700">{{ session('ai_description') }}</p>
                            </div>
                        @endif
                    </div>
                    
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <p class="mt-1 text-xs text-gray-500">
                        📸 En móvil: selecciona "Tomar foto" para usar la cámara. Luego presiona "Analizar con IA" para auto-completar los datos del producto.
                    </p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3 mt-6">
                @if($editingId)
                    <button type="button" 
                            wire:click="resetForm"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancelar
                    </button>
                @endif
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading>Guardando...</span>
                </button>
            </div>
        </form>
    </div>
    @endcan

    {{-- SECCIÓN 2 - LISTA --}}
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Listado de Productos</h3>
        </div>
        
        <div class="p-6">
            {{-- Search --}}
            <div class="mb-6">
                <input type="text" 
                       wire:model.live="search"
                       placeholder="Buscar por nombre o SKU..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-10 h-10 rounded-lg object-contain bg-white border border-gray-200">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->sku }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->stock >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @can('edit-products')
                                    <button wire:click="edit({{ $product->id }})" 
                                            class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        Editar
                                    </button>
                                    @endcan
                                    
                                    @can('delete-products')
                                    <button onclick="confirmDelete({{ $product->id }})" 
                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Eliminar
                                    </button>
                                    @endcan
                                    
                                    @cannot('edit-products')
                                    @cannot('delete-products')
                                    <span class="px-3 py-1 text-gray-500 text-sm italic">Solo lectura</span>
                                    @endcannot
                                    @endcannot
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No se encontraron productos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Función para confirmar eliminación de producto
    function confirmDelete(productId) {
        Swal.fire({
            title: '¿Eliminar producto?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.delete(productId);
            }
        });
    }

    // Escuchar evento de Livewire cuando se guarda exitosamente
    $wire.on('product-saved', (event) => {
        const message = event.message || 'Producto guardado exitosamente';
        const isEdit = event.isEdit || false;
        
        Swal.fire({
            title: isEdit ? '¡Actualizado!' : '¡Guardado!',
            text: message,
            icon: 'success',
            confirmButtonColor: '#10B981',
            timer: 2000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut'
            }
        });
    });

    // Escuchar evento cuando se elimina
    $wire.on('product-deleted', () => {
        Swal.fire({
            title: '¡Eliminado!',
            text: 'El producto ha sido eliminado exitosamente',
            icon: 'success',
            confirmButtonColor: '#10B981',
            timer: 2000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            }
        });
    });

    // Escuchar evento de error
    $wire.on('product-error', (event) => {
        const message = event.message || 'Ocurrió un error';
        
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#EF4444',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });
</script>
@endscript
