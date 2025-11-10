<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- ALERTAS DE STOCK BAJO Y AGOTADO --}}
    @if($outOfStockProducts->count() > 0)
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-300 rounded-2xl p-5 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-lg font-black text-red-900 mb-2">⚠️ Productos Agotados ({{ $outOfStockProducts->count() }})</h4>
                <p class="text-sm text-red-700 mb-3 font-semibold">Los siguientes productos NO tienen stock disponible:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($outOfStockProducts as $product)
                    <div class="bg-white rounded-lg px-3 py-2 border-2 border-red-200 flex items-center justify-between">
                        <span class="font-bold text-gray-900 text-sm">{{ $product->name }}</span>
                        <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full font-black">0 unidades</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($lowStockProducts->count() > 0)
    <div class="bg-gradient-to-r from-yellow-50 to-orange-100 border-2 border-orange-300 rounded-2xl p-5 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-lg font-black text-orange-900 mb-2">⚡ Stock Bajo ({{ $lowStockProducts->count() }})</h4>
                <p class="text-sm text-orange-700 mb-3 font-semibold">Productos con 10 unidades o menos:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($lowStockProducts as $product)
                    <div class="bg-white rounded-lg px-3 py-2 border-2 border-orange-200 flex items-center justify-between">
                        <span class="font-bold text-gray-900 text-sm">{{ $product->name }}</span>
                        <span class="bg-orange-600 text-white text-xs px-2 py-1 rounded-full font-black">{{ $product->stock }} unidades</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- SECCIÓN 1 - FORMULARIO DE MOVIMIENTO DE INVENTARIO --}}
    <div class="bg-gradient-to-br from-white to-gray-50 shadow-2xl rounded-2xl border-2 border-blue-100 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-cyan-600">
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Registrar Movimiento de Inventario</h3>
                    <p class="text-xs text-blue-100">Entrada, salida o ajuste de stock</p>
                </div>
            </div>
        </div>
        
        <form wire:submit.prevent="save" class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Product --}}
                <div class="lg:col-span-2">
                    <label for="product_id" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Producto
                    </label>
                    <select id="product_id"
                            wire:model="product_id" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium @error('product_id') border-red-500 @enderror">
                        <option value="">Seleccionar producto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} - {{ $product->sku }} (Stock: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Tipo de Movimiento
                    </label>
                    <select id="type"
                            wire:model="type" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold @error('type') border-red-500 @enderror">
                        <option value="entrada">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                            </svg>
                            Entrada
                        </option>
                        <option value="salida">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                            </svg>
                            Salida
                        </option>
                        <option value="ajuste">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                            </svg>
                            Ajuste
                        </option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Quantity --}}
                <div>
                    <label for="quantity" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                        @if($type === 'ajuste')
                            Nuevo Stock
                        @else
                            Cantidad
                        @endif
                    </label>
                    <input type="number" 
                           id="quantity"
                           wire:model="quantity" 
                           min="1"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-bold text-blue-700 @error('quantity') border-red-500 @enderror">
                    @error('quantity')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Reason --}}
                <div class="lg:col-span-4">
                    <label for="reason" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Razón / Observación
                    </label>
                    <textarea id="reason"
                              wire:model="reason" 
                              rows="3"
                              placeholder="Descripción del movimiento..."
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium resize-none @error('reason') border-red-500 @enderror"></textarea>
                    @error('reason')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t-2 border-gray-200">
                <button type="button" 
                        wire:click="resetForm"
                        class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all flex items-center justify-center gap-2 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Limpiar
                </button>
                <button type="submit" 
                        style="background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%) !important;"
                        class="px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-black text-lg hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2"
                        wire:loading.attr="disabled">
                    <svg wire:loading.remove class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg wire:loading class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove class="font-black">REGISTRAR MOVIMIENTO</span>
                    <span wire:loading class="font-black">REGISTRANDO...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- SECCIÓN 2 - HISTORIAL DE MOVIMIENTOS --}}
    <div class="bg-gradient-to-br from-white to-gray-50 shadow-2xl rounded-2xl border-2 border-gray-200 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-gray-700 to-gray-900">
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Historial de Movimientos</h3>
                    <p class="text-xs text-gray-300">Registro completo de entradas y salidas</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            {{-- Table --}}
            <div class="overflow-x-auto rounded-xl border-2 border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Razón</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Usuario</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($movements as $movement)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 transition-all duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $movement->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $movement->created_at->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-gray-900">{{ $movement->product->name }}</div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                        <span class="text-xs text-gray-500 font-mono">{{ $movement->product->sku }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movement->type === 'entrada')
                                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-black rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                                            </svg>
                                            Entrada
                                        </span>
                                    @elseif($movement->type === 'salida')
                                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-black rounded-xl bg-gradient-to-r from-red-500 to-orange-500 text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                                            </svg>
                                            Salida
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-black rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Ajuste
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movement->type === 'entrada')
                                        <div class="inline-flex items-center gap-1 px-3 py-2 bg-green-100 rounded-xl border-2 border-green-200">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-lg font-black text-green-700">{{ $movement->quantity }}</span>
                                        </div>
                                    @elseif($movement->type === 'salida')
                                        <div class="inline-flex items-center gap-1 px-3 py-2 bg-red-100 rounded-xl border-2 border-red-200">
                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-lg font-black text-red-700">{{ $movement->quantity }}</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-1 px-3 py-2 bg-blue-100 rounded-xl border-2 border-blue-200">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-lg font-black text-blue-700">{{ $movement->quantity }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    @if($movement->reason)
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 font-medium">{{ $movement->reason }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md">
                                            {{ strtoupper(substr($movement->user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">{{ $movement->user->name }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-bold text-lg">No hay movimientos registrados</p>
                                        <p class="text-gray-400 text-sm mt-1">Registra el primer movimiento de inventario</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Escuchar evento de Livewire cuando se guarda exitosamente
    $wire.on('movement-saved', (event) => {
        const message = event.message || 'Movimiento registrado exitosamente';
        
        Swal.fire({
            title: '¡Registrado!',
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

    // Escuchar evento cuando hay error
    $wire.on('movement-error', (event) => {
        const message = event.message || 'Ocurrió un error al registrar el movimiento';
        
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
