<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="mb-4">
        <div class="relative">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Buscar por nombre o código..." 
                autofocus
                class="w-full px-4 py-3 pl-10 pr-4 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-all duration-200"
            >
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Indicador de carga -->
        <div wire:loading class="mt-2 text-sm text-blue-600 flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando...
        </div>
    </div>

    <!-- Resultados -->
    <div class="space-y-2">
        @if(count($results) > 0)
            @foreach($results as $product)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                    <!-- Imagen del producto -->
                    <div class="flex-shrink-0 w-16 h-16 mr-4">
                        @if(isset($product['image']) && $product['image'])
                            <img src="{{ asset('storage/' . $product['image']) }}" 
                                 alt="{{ $product['name'] }}" 
                                 class="w-full h-full object-cover rounded-md">
                        @else
                            <div class="w-full h-full bg-gray-300 rounded-md flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Información del producto -->
                    <div class="flex-1 min-w-0">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center">
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $product['name'] }}
                                </h3>
                                <p class="text-xs text-gray-600">
                                    {{ $product['category']['name'] ?? 'Sin categoría' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    SKU: {{ $product['sku'] }}
                                </p>
                            </div>
                            
                            <div class="text-right md:text-left">
                                <p class="text-sm font-bold text-green-600">
                                    ${{ number_format($product['price'], 2) }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    Stock: {{ $product['stock'] }}
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button 
                                    wire:click="selectProduct({{ $product['id'] }})"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                >
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @elseif(strlen($search) >= 2)
            <div class="text-center py-8">
                <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0118 12M6 20.291A7.962 7.962 0 016 12m0 8.291zm12 0A7.962 7.962 0 0018 12m0 8.291zm-6 2a9.963 9.963 0 01-6-2m12 0a9.963 9.963 0 01-6 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-600 mb-1">
                        No se encontraron productos
                    </h3>
                    <p class="text-sm text-gray-500">
                        Intenta con otro término de búsqueda
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
