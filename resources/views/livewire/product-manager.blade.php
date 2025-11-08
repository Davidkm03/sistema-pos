<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- SECCIÓN 1 - FORMULARIO PARA CREAR (Solo si NO está editando) --}}
    @can('create-products')
    @if(!$editingId)
    <div class="bg-gradient-to-br from-white to-gray-50 shadow-2xl rounded-2xl border-2 border-indigo-100 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Nuevo Producto</h3>
                    <p class="text-xs text-indigo-100">Completa la información del producto</p>
                </div>
            </div>
        </div>
        
        <form wire:submit.prevent="save" class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Nombre
                    </label>
                    <input type="text" 
                           id="name"
                           wire:model="name" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Categoría
                    </label>
                    <select id="category_id"
                            wire:model="category_id" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium @error('category_id') border-red-500 @enderror">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- SKU --}}
                <div>
                    <label for="sku" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        SKU / Código
                    </label>
                    <input type="text" 
                           id="sku"
                           wire:model="sku" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium @error('sku') border-red-500 @enderror">
                    @error('sku')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Precio de Venta
                    </label>
                    <input type="number" 
                           id="price"
                           step="0.01"
                           wire:model="price" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all font-bold text-green-700 @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Cost --}}
                <div>
                    <label for="cost" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Costo
                    </label>
                    <input type="number" 
                           id="cost"
                           step="0.01"
                           wire:model="cost" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold text-orange-700 @error('cost') border-red-500 @enderror">
                    @error('cost')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label for="stock" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Stock Disponible
                    </label>
                    <input type="number"
                           id="stock"
                           wire:model="stock"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-bold text-blue-700 @error('stock') border-red-500 @enderror">
                    @error('stock')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Producto</label>
                    
                    <div class="flex flex-col space-y-3">
                        {{-- Botones para subir/tomar foto --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            {{-- Botón para tomar foto (móvil) --}}
                            <label for="image-camera" class="cursor-pointer">
                                <div class="flex items-center justify-center px-4 py-3 border-2 border-dashed border-blue-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700">Tomar Foto</span>
                                </div>
                                <input type="file"
                                       id="image-camera"
                                       wire:model="image"
                                       accept="image/*"
                                       capture="environment"
                                       class="hidden">
                            </label>

                            {{-- Botón para subir desde galería --}}
                            <label for="image-gallery" class="cursor-pointer">
                                <div class="flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Subir Imagen</span>
                                </div>
                                <input type="file"
                                       id="image-gallery"
                                       wire:model="image"
                                       accept="image/*"
                                       class="hidden">
                            </label>
                        </div>

                        {{-- Preview y botón de análisis con IA --}}
                        @if($image)
                            <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    {{-- Preview de la imagen --}}
                                    <div class="flex-shrink-0">
                                        <img src="{{ $image->temporaryUrl() }}" 
                                             alt="Preview" 
                                             class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300 shadow-sm">
                                    </div>
                                    
                                    {{-- Botón de análisis --}}
                                    <div class="flex-1 flex flex-col justify-center space-y-2">
                                        <div class="flex items-center text-sm text-green-600">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Imagen cargada correctamente
                                        </div>
                                        <button type="button"
                                                wire:click="analyzeImage"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50 shadow-md transform transition hover:scale-105">
                                            <svg wire:loading.remove wire:target="analyzeImage" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                            <svg wire:loading wire:target="analyzeImage" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span wire:loading.remove wire:target="analyzeImage" class="font-semibold">Analizar con IA</span>
                                            <span wire:loading wire:target="analyzeImage" class="font-semibold">Analizando...</span>
                                        </button>
                                        <p class="text-xs text-gray-500 text-center">
                                            La IA identificará el producto y completará los campos automáticamente
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Descripción sugerida por IA --}}
                        @if(session()->has('ai_description'))
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-blue-50 border-2 border-purple-300 rounded-lg shadow-sm">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-purple-900 mb-1">Información detectada por IA:</p>
                                        <p class="text-sm text-purple-800">{{ session('ai_description') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @error('image')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t-2 border-gray-200">
                @if($editingId)
                    <button type="button" 
                            wire:click="resetForm"
                            class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 hover:border-red-300 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all flex items-center justify-center gap-2 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </button>
                @endif
                <button type="submit" 
                        style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;"
                        class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-black text-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2"
                        wire:loading.attr="disabled">
                    <svg wire:loading.remove class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg wire:loading class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove class="font-black">@if($editingId) ACTUALIZAR @else GUARDAR @endif PRODUCTO</span>
                    <span wire:loading class="font-black">GUARDANDO...</span>
                </button>
            </div>
        </form>
    </div>
    @endif
    @endcan

    {{-- MODAL DE EDICIÓN --}}
    @if($editingId)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" 
             @click="show = false; $wire.resetForm()"></div>
        
        <!-- Modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden transform transition-all"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 @click.stop>
                
                <!-- Header del Modal -->
                <div class="px-6 py-5 bg-gradient-to-r from-yellow-500 to-orange-500 sticky top-0 z-10">
                    <div class="flex items-center justify-between text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black">Editar Producto</h3>
                                <p class="text-sm text-yellow-100">Actualiza la información del producto</p>
                            </div>
                        </div>
                        <button @click="show = false; $wire.resetForm()" 
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/20 hover:bg-white/30 transition-all active:scale-90">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal (con scroll) -->
                <form wire:submit.prevent="save" class="overflow-y-auto max-h-[calc(90vh-180px)]">
                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            {{-- Name --}}
                            <div>
                                <label for="modal-name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Nombre
                                </label>
                                <input type="text" 
                                       id="modal-name"
                                       wire:model="name" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="modal-category_id" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Categoría
                                </label>
                                <select id="modal-category_id"
                                        wire:model="category_id" 
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium @error('category_id') border-red-500 @enderror">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div>
                                <label for="modal-sku" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    SKU / Código
                                </label>
                                <input type="text" 
                                       id="modal-sku"
                                       wire:model="sku" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium @error('sku') border-red-500 @enderror">
                                @error('sku')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Price --}}
                            <div>
                                <label for="modal-price" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Precio de Venta
                                </label>
                                <input type="number" 
                                       id="modal-price"
                                       step="0.01"
                                       wire:model="price" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all font-bold text-green-700 @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Cost --}}
                            <div>
                                <label for="modal-cost" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Costo
                                </label>
                                <input type="number" 
                                       id="modal-cost"
                                       step="0.01"
                                       wire:model="cost" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all font-semibold text-orange-700 @error('cost') border-red-500 @enderror">
                                @error('cost')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Stock --}}
                            <div>
                                <label for="modal-stock" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Stock Disponible
                                </label>
                                <input type="number"
                                       id="modal-stock"
                                       wire:model="stock"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-bold text-blue-700 @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Nota: Los campos de TAX y de imagen se pueden copiar del formulario principal si los necesitas --}}
                        </div>
                    </div>

                    <!-- Footer con botones (sticky) -->
                    <div class="sticky bottom-0 px-6 py-4 bg-gradient-to-t from-gray-50 to-white border-t-2 border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                            <button type="button" 
                                    @click="show = false; $wire.resetForm()"
                                    class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all flex items-center justify-center gap-2 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                            <button type="submit" 
                                    style="background: linear-gradient(135deg, #F59E0B 0%, #EA580C 100%) !important;"
                                    class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-xl font-black text-lg hover:from-yellow-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 disabled:opacity-50 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2"
                                    wire:loading.attr="disabled">
                                <svg wire:loading.remove class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg wire:loading class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove class="font-black">ACTUALIZAR PRODUCTO</span>
                                <span wire:loading class="font-black">ACTUALIZANDO...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- SECCIÓN 2 - LISTA --}}
    <div class="bg-gradient-to-br from-white to-gray-50 shadow-2xl rounded-2xl border-2 border-gray-200 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-gray-700 to-gray-900">
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Listado de Productos</h3>
                    <p class="text-xs text-gray-300">Gestiona tu inventario completo</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            {{-- Search --}}
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Buscar por nombre o SKU..."
                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-gray-900 placeholder-gray-400 shadow-sm">
                </div>
            </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-xl border-2 border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Imagen</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-16 h-16 rounded-xl object-cover bg-white border-2 border-gray-200 shadow-md">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center border-2 border-gray-300 shadow-sm">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $product->category->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                        <span class="text-sm font-mono font-semibold text-gray-700">{{ $product->sku }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-black bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                        ${{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->stock >= 10)
                                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-black rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $product->stock }}
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-black rounded-xl bg-gradient-to-r from-yellow-500 to-orange-500 text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-black rounded-xl bg-gradient-to-r from-red-500 to-orange-500 text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            AGOTADO
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        @can('edit-products')
                                        <button wire:click="edit({{ $product->id }})" 
                                                class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 font-bold shadow-md hover:shadow-lg transition-all active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </button>
                                        @endcan
                                        
                                        @can('delete-products')
                                        <button onclick="confirmDelete({{ $product->id }})" 
                                                class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl hover:from-red-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-red-500 font-bold shadow-md hover:shadow-lg transition-all active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
                                        @endcan
                                        
                                        @cannot('edit-products')
                                        @cannot('delete-products')
                                        <span class="px-4 py-2 bg-gray-100 text-gray-500 text-xs font-semibold italic rounded-xl border border-gray-200">🔒 Solo lectura</span>
                                        @endcannot
                                        @endcannot
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-bold text-lg">No se encontraron productos</p>
                                        <p class="text-gray-400 text-sm mt-1">Intenta con otros términos de búsqueda</p>
                                    </div>
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
