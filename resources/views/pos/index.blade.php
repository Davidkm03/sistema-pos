<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">
                        {{ __('Punto de Venta') }}
                    </h2>
                    <p class="text-xs text-gray-500">Sistema POS Moderno</p>
                </div>
            </div>
            <a href="{{ route('pos.mobile') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-indigo-200 text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Versión Móvil
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Layout de 2 Columnas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Columna Izquierda - Búsqueda de Productos (60% - 2/3) -->
                <div class="lg:col-span-2">
                    <!-- Búsqueda de Productos -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-4 border-2 border-indigo-100">
                        <div class="p-4 sm:p-6">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="searchInput" 
                                       placeholder="Buscar productos por nombre o código..."
                                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-gray-900 placeholder-gray-400"
                                       onkeyup="searchProducts()">
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Categorías -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-4 border-2 border-gray-100">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Categorías
                                </h3>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 sm:gap-3">
                                <button onclick="filterByCategory('all')" 
                                        class="category-btn px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-150 text-sm font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2"></path>
                                    </svg>
                                    Todos <span class="ml-1 bg-white/20 px-2 py-0.5 rounded-full text-xs">({{ $products->count() }})</span>
                                </button>
                                @foreach($categories as $category)
                                    <button onclick="filterByCategory('{{ $category->name }}')" 
                                            class="category-btn px-4 sm:px-6 py-2.5 sm:py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-150 text-sm font-semibold transform hover:-translate-y-0.5 hover:shadow-md active:scale-95">
                                        @if($category->name === 'Bebidas')
                                            <svg class="w-4 h-4 inline mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        @elseif($category->name === 'Snacks')
                                            <svg class="w-4 h-4 inline mr-1.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                            </svg>
                                        @elseif($category->name === 'Alimentos')
                                            <svg class="w-4 h-4 inline mr-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                            </svg>
                                        @elseif($category->name === 'Limpieza')
                                            <svg class="w-4 h-4 inline mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 inline mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2"></path>
                                            </svg>
                                        @endif
                                        {{ $category->name }} <span class="ml-1 text-xs opacity-70">({{ $category->products_count }})</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Grid de Productos -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border-2 border-gray-100">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center gap-2 mb-4 sm:mb-6">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Productos Disponibles
                                </h3>
                            </div>
                            
                            <!-- Grid de productos -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                                @foreach($products as $product)
                                    <div class="product-card bg-white border-2 border-gray-200 rounded-xl p-3 sm:p-4 hover:shadow-xl hover:border-indigo-400 transition-all duration-200 cursor-pointer group transform hover:-translate-y-1 hover:scale-105" 
                                         data-category="{{ $product->category->name }}"
                                         data-name="{{ $product->name }}"
                                         data-sku="{{ $product->sku }}">
                                        
                                        <!-- Stock badge -->
                                        @if($product->stock <= 10)
                                            <div class="mb-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-full text-xs font-bold shadow-md">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    ¡Solo {{ $product->stock }}!
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Imagen del producto -->
                                        <div class="w-full aspect-square bg-gray-50 rounded-xl mb-3 flex items-center justify-center border-2 border-gray-100 overflow-hidden p-2 group-hover:border-indigo-200 transition-colors">
                                            @if($product->image)
                                                <img src="{{ $product->image_url }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div style="display:none;" class="w-full h-full flex items-center justify-center">
                                                    @if($product->category->name === 'Bebidas')
                                                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    @elseif($product->category->name === 'Snacks')
                                                        <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                        </svg>
                                                    @elseif($product->category->name === 'Alimentos')
                                                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @else
                                                @if($product->category->name === 'Bebidas')
                                                    <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                @elseif($product->category->name === 'Snacks')
                                                    <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                    </svg>
                                                @elseif($product->category->name === 'Alimentos')
                                                    <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <!-- Información del producto -->
                                        <div class="space-y-2">
                                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-indigo-600 transition duration-150 line-clamp-2 leading-snug">
                                                {{ $product->name }}
                                            </h4>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xl sm:text-2xl font-black bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                                    ${{ number_format($product->price, 0) }}
                                                </span>
                                                <span class="text-xs px-2 py-1 rounded-lg {{ $product->stock <= 10 ? 'bg-red-100 text-red-700 font-bold' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $product->stock }}u
                                                </span>
                                            </div>
                                            <button 
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}"
                                                data-stock="{{ $product->stock }}"
                                                onclick="addToCart(this)"
                                                style="background-color: #4F46E5 !important; opacity: 1 !important;"
                                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-xl text-sm font-bold hover:from-indigo-700 hover:to-purple-700 active:scale-95 transition-all duration-150 shadow-md hover:shadow-lg flex items-center justify-center gap-2 border-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                <span style="color: white !important;">AGREGAR</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(\App\Models\Product::where('stock', '>', 0)->count() == 0)
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-semibold">No hay productos disponibles</p>
                                    <p class="text-gray-400 text-sm mt-1">Agrega productos desde el inventario</p>
                                </div>
                            @endif
                            
                            <!-- Paginación o Ver Más -->
                            @if(\App\Models\Product::where('stock', '>', 0)->count() > 12)
                                <div class="text-center mt-6">
                                    <button onclick="loadMoreProducts()" id="loadMoreBtn" 
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-xl hover:from-gray-200 hover:to-gray-300 transition-all duration-150 font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span id="loadMoreText">Ver Más Productos ({{ \App\Models\Product::where('stock', '>', 0)->count() - 12 }} restantes)</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Productos con Bajo Stock (Alerta) -->
                    @php
                        $lowStockProducts = \App\Models\Product::where('stock', '<=', 10)->where('stock', '>', 0)->take(6)->get();
                    @endphp
                    @if($lowStockProducts->count() > 0)
                        <div class="bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    ⚠️ Productos con Bajo Stock
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($lowStockProducts as $product)
                                        @can('edit-products')
                                        <a href="{{ route('products.index') }}?edit={{ $product->id }}" class="bg-white border border-red-200 rounded-lg p-3 hover:shadow-md hover:border-red-400 transition duration-150 cursor-pointer group">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 text-sm group-hover:text-blue-600 transition">{{ $product->name }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $product->sku }}</p>
                                                    <p class="text-lg font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                                                    <p class="text-xs text-blue-600 mt-1 opacity-0 group-hover:opacity-100 transition">
                                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Click para agregar stock
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 group-hover:bg-red-200 transition">
                                                        {{ $product->stock }} left
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                        @else
                                        <div class="bg-white border border-red-200 rounded-lg p-3 hover:shadow-md transition duration-150">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 text-sm">{{ $product->name }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $product->sku }}</p>
                                                    <p class="text-lg font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                        {{ $product->stock }} left
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @endcan
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Columna Derecha - Carrito de Venta (40% - 1/3) -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-white to-gray-50 overflow-hidden shadow-2xl sm:rounded-2xl sticky top-4 border-2 border-indigo-100">
                        <!-- Header del carrito con gradiente -->
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4 sm:p-6">
                            <div class="flex items-center justify-between text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">
                                            Carrito de Venta
                                        </h3>
                                        <p class="text-xs text-indigo-100">Agrega productos</p>
                                    </div>
                                </div>
                                <span id="cart-count" class="w-10 h-10 bg-white text-indigo-600 rounded-full flex items-center justify-center text-lg font-black shadow-lg">
                                    0
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <!-- Contenido del carrito -->
                            <div id="cart-container" class="mb-6 min-h-[200px] max-h-[400px] overflow-y-auto">
                                <!-- Se llenará con JavaScript -->
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-semibold">Carrito vacío</p>
                                    <p class="text-gray-400 text-sm">Agrega productos para empezar</p>
                                </div>
                            </div>
                            
                            <!-- Resumen del total -->
                            <div class="border-t-2 border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-semibold">Subtotal:</span>
                                    <span id="cart-subtotal" class="text-lg font-bold text-gray-900">$0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-semibold">IVA (19%):</span>
                                    <span id="cart-tax" class="text-lg font-bold text-gray-900">$0</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t-2 border-gray-200">
                                    <span class="text-xl font-black text-gray-900">TOTAL:</span>
                                    <span id="cart-total" class="text-2xl font-black bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">$0</span>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="space-y-3 mt-6">
                                <!-- Método de pago -->
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Método de Pago
                                    </label>
                                    <select id="paymentMethod" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-semibold text-gray-900 transition-all" onchange="handlePaymentMethodChange()">
                                        <option value="efectivo">💵 Efectivo</option>
                                        <option value="tarjeta_debito">💳 Tarjeta Débito</option>
                                        <option value="tarjeta_credito">💳 Tarjeta Crédito</option>
                                        <option value="transferencia">📱 Transferencia</option>
                                    </select>
                                </div>

                                <!-- Detalles de Transferencia (oculto por defecto) -->
                                <div id="transferDetails" class="hidden space-y-3 p-4 bg-blue-50 rounded-xl border-2 border-blue-200">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Transferencia</label>
                                        <select id="transferType" class="w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold">
                                            <option value="">Seleccionar...</option>
                                            <option value="nequi">Nequi</option>
                                            <option value="daviplata">Daviplata</option>
                                            <option value="bancolombia">Bancolombia</option>
                                            <option value="llave">Llave (PSE)</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Referencia (opcional)</label>
                                        <input type="text" id="transferReference" placeholder="Número de referencia" class="w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    </div>
                                </div>
                                
                                <button onclick="processSale()" 
                                        style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;"
                                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 px-4 rounded-xl font-black text-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-150 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    PROCESAR VENTA
                                </button>
                                <button onclick="clearCart()" class="w-full bg-white border-2 border-gray-200 text-gray-700 py-3 px-4 rounded-xl font-bold hover:bg-gray-50 hover:border-red-300 hover:text-red-600 transition-all duration-150 flex items-center justify-center gap-2 active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Limpiar Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Carrito de compras
        let cart = [];
        let currentProductsOffset = 12; // Contador de productos cargados

        // Manejar cambio de método de pago
        function handlePaymentMethodChange() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            const transferDetails = document.getElementById('transferDetails');

            if (paymentMethod === 'transferencia') {
                transferDetails.classList.remove('hidden');
            } else {
                transferDetails.classList.add('hidden');
            }
        }

        // Agregar producto al carrito
        function addToCart(button) {
            const product = {
                id: parseInt(button.dataset.id),
                name: button.dataset.name,
                price: parseFloat(button.dataset.price),
                stock: parseInt(button.dataset.stock),
                quantity: 1
            };
            
            // Verificar si el producto ya está en el carrito
            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                // Si existe, incrementar cantidad (verificar stock)
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity += 1;
                } else {
                    Swal.fire({
                        title: 'Stock Insuficiente',
                        text: `Solo quedan ${product.stock} unidades disponibles`,
                        icon: 'warning',
                        confirmButtonColor: '#F59E0B',
                        timer: 2000,
                        showClass: {
                            popup: 'animate__animated animate__headShake'
                        }
                    });
                    return;
                }
            } else {
                // Si no existe, agregar al carrito
                cart.push(product);
            }
            
            updateCartDisplay();
            
            // Toast de éxito
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            
            Toast.fire({
                icon: 'success',
                title: `${product.name} agregado`
            });
        }

        // Remover producto del carrito
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        }

        // Actualizar cantidad en el carrito
        function updateQuantity(productId, newQuantity) {
            const item = cart.find(item => item.id === productId);
            if (item && newQuantity > 0 && newQuantity <= item.stock) {
                item.quantity = newQuantity;
                updateCartDisplay();
            }
        }

        // Limpiar carrito
        function clearCart() {
            if (cart.length === 0) {
                Swal.fire({
                    title: 'Carrito Vacío',
                    text: 'No hay productos en el carrito',
                    icon: 'info',
                    confirmButtonColor: '#6B7280',
                    timer: 1500
                });
                return;
            }
            
            Swal.fire({
                title: '¿Limpiar carrito?',
                text: `Se eliminarán ${cart.length} producto(s) del carrito`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6B7280',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = [];
                    updateCartDisplay();
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Carrito limpiado'
                    });
                }
            });
        }

        // Calcular total del carrito
        function getCartTotal() {
            return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        }

        // Actualizar visualización del carrito
        function updateCartDisplay() {
            const cartContainer = document.getElementById('cart-container');
            const cartCount = document.getElementById('cart-count');
            const cartTotal = document.getElementById('cart-total');
            const cartSubtotal = document.getElementById('cart-subtotal');
            const cartTax = document.getElementById('cart-tax');
            
            // Actualizar contador
            if (cartCount) {
                cartCount.textContent = cart.length;
            }
            
            // Calcular subtotal, IVA y total
            const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            const tax = subtotal * 0.19; // IVA 19%
            const total = subtotal + tax;
            
            // Actualizar totales
            if (cartSubtotal) {
                cartSubtotal.textContent = `$${subtotal.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            }
            if (cartTax) {
                cartTax.textContent = `$${tax.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            }
            if (cartTotal) {
                cartTotal.textContent = `$${total.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            }
            
            // Actualizar contenido del carrito
            if (cartContainer) {
                if (cart.length === 0) {
                    cartContainer.innerHTML = `
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-400 font-semibold">Carrito vacío</p>
                            <p class="text-gray-400 text-sm">Agrega productos para empezar</p>
                        </div>
                    `;
                } else {
                    let cartHTML = '<div class="space-y-3">';
                    cart.forEach(item => {
                        cartHTML += `
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border-2 border-gray-100 hover:border-indigo-200 transition-all">
                                <div class="flex-1 pr-3">
                                    <h4 class="font-bold text-gray-900 text-sm mb-1">${item.name}</h4>
                                    <p class="text-xs text-gray-500 font-semibold">$${item.price.toLocaleString('es-CO')} × ${item.quantity}</p>
                                    <p class="text-sm font-black bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mt-1">
                                        $${(item.price * item.quantity).toLocaleString('es-CO')}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Botón decrementar -->
                                    <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" 
                                            class="w-8 h-8 flex items-center justify-center bg-white border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all active:scale-90"
                                            ${item.quantity <= 1 ? 'disabled' : ''}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Cantidad -->
                                    <span class="w-12 text-center font-black text-gray-900">${item.quantity}</span>
                                    
                                    <!-- Botón incrementar -->
                                    <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" 
                                            class="w-8 h-8 flex items-center justify-center bg-white border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-green-50 hover:border-green-300 hover:text-green-600 transition-all active:scale-90"
                                            ${item.quantity >= item.stock ? 'disabled' : ''}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Botón eliminar -->
                                    <button onclick="removeFromCart(${item.id})" 
                                            class="w-8 h-8 flex items-center justify-center bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-lg hover:from-red-600 hover:to-orange-600 transition-all shadow-md hover:shadow-lg active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    cartHTML += '</div>';
                    cartContainer.innerHTML = cartHTML;
                }
            }
        }

        // Filtrar productos por categoría
        function filterByCategory(category) {
            const products = document.querySelectorAll('.product-card');
            const buttons = document.querySelectorAll('.category-btn');
            
            // Actualizar botones activos
            buttons.forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white');
                btn.classList.add('bg-white', 'border-2', 'border-gray-200', 'text-gray-700');
            });
            
            // Marcar botón activo
            event.target.classList.remove('bg-white', 'border-2', 'border-gray-200', 'text-gray-700');
            event.target.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white');
            
            // Filtrar productos
            products.forEach(product => {
                const productCategory = product.dataset.category;
                if (category === 'all' || productCategory === category) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Búsqueda de productos
        function searchProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');
            let foundCount = 0;
            
            productCards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const sku = card.dataset.sku.toLowerCase();
                
                if (name.includes(searchTerm) || sku.includes(searchTerm)) {
                    card.style.display = 'block';
                    foundCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Opcional: mostrar mensaje si no hay resultados
            if (searchTerm && foundCount === 0) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                
                Toast.fire({
                    icon: 'info',
                    title: 'No se encontraron productos'
                });
            }
        }

        // Cargar más productos
        async function loadMoreProducts() {
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const loadMoreText = document.getElementById('loadMoreText');
            const productGrid = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4');
            
            if (!productGrid) {
                console.error('No se encontró el grid de productos');
                return;
            }
            
            // Deshabilitar botón y mostrar loading
            loadMoreBtn.disabled = true;
            loadMoreText.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Cargando...';
            
            try {
                const response = await fetch(`{{ route('pos.load-more') }}?offset=${currentProductsOffset}&limit=12`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success && data.products.length > 0) {
                    // Renderizar nuevos productos - simplemente agregarlos al final del grid
                    data.products.forEach(product => {
                        const productCard = createProductCard(product);
                        productGrid.appendChild(productCard);
                    });
                    
                    // Actualizar offset
                    currentProductsOffset += data.products.length;
                    
                    // Actualizar texto del botón o ocultarlo si no hay más productos
                    if (data.remaining > 0) {
                        loadMoreText.innerHTML = `Ver Más Productos (${data.remaining} restantes)`;
                        loadMoreBtn.disabled = false;
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }
                    
                    // Mensaje de éxito
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: `${data.products.length} productos más cargados`
                    });
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            } catch (error) {
                console.error('Error al cargar más productos:', error);
                loadMoreText.innerHTML = 'Ver Más Productos';
                loadMoreBtn.disabled = false;
                
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudieron cargar más productos: ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            }
        }

        // Crear tarjeta de producto
        function createProductCard(product) {
            const div = document.createElement('div');
            div.className = 'product-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg hover:border-blue-300 transition-all duration-200 cursor-pointer group transform hover:-translate-y-1';
            div.dataset.category = product.category_name;
            div.dataset.name = product.name;
            div.dataset.sku = product.sku;
            
            // Determinar el color de la categoría
            let categoryColor = 'gray';
            if (product.category_name === 'Bebidas') categoryColor = 'blue';
            else if (product.category_name === 'Snacks') categoryColor = 'orange';
            else if (product.category_name === 'Alimentos') categoryColor = 'red';
            else if (product.category_name === 'Limpieza') categoryColor = 'green';
            
            // Icono según categoría
            let categoryIcon = '';
            if (product.category_name === 'Bebidas') {
                categoryIcon = '<svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
            } else if (product.category_name === 'Snacks') {
                categoryIcon = '<svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>';
            } else if (product.category_name === 'Alimentos') {
                categoryIcon = '<svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>';
            } else {
                categoryIcon = '<svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>';
            }
            
            div.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs px-2 py-1 bg-${categoryColor}-100 text-${categoryColor}-800 rounded-full font-medium">
                        ${product.category_name}
                    </span>
                    ${product.stock <= 10 ? '<span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full font-medium">¡Poco stock!</span>' : ''}
                </div>
                
                <div class="w-full h-32 bg-white rounded-lg mb-3 flex items-center justify-center border border-gray-200 overflow-hidden p-2">
                    ${product.image_url ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-contain">` : categoryIcon}
                </div>
                
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition duration-150 line-clamp-2">
                        ${product.name}
                    </h4>
                    <p class="text-xs text-gray-500 font-mono">${product.sku}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold text-green-600">
                            $${parseFloat(product.price).toFixed(2)}
                        </span>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">Stock:</div>
                            <span class="text-sm font-semibold ${product.stock <= 10 ? 'text-red-600' : 'text-gray-700'}">
                                ${product.stock}
                            </span>
                        </div>
                    </div>
                    <button 
                        data-id="${product.id}"
                        data-name="${product.name}"
                        data-price="${product.price}"
                        data-stock="${product.stock}"
                        onclick="addToCart(this)"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2.5 px-3 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-150 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0 0M6 12h6m0 0h6"></path>
                        </svg>
                        Agregar al Carrito
                    </button>
                </div>
            `;
            
            return div;
        }


        // Procesar venta
        async function processSale() {
            if (cart.length === 0) {
                Swal.fire({
                    title: 'Carrito Vacío',
                    text: 'Agrega productos al carrito antes de procesar la venta',
                    icon: 'warning',
                    confirmButtonColor: '#F59E0B',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    }
                });
                return;
            }
            
            const paymentMethod = document.getElementById('paymentMethod').value;
            const transferType = document.getElementById('transferType')?.value || null;
            const transferReference = document.getElementById('transferReference')?.value || null;
            const total = getCartTotal();

            // Nombre del método de pago para mostrar
            let paymentMethodName = '💵 Efectivo';
            if (paymentMethod === 'tarjeta_debito') paymentMethodName = '💳 Tarjeta Débito';
            else if (paymentMethod === 'tarjeta_credito') paymentMethodName = '💳 Tarjeta Crédito';
            else if (paymentMethod === 'transferencia') {
                paymentMethodName = `📱 Transferencia${transferType ? ' (' + transferType + ')' : ''}`;
            }

            // Confirmación antes de procesar
            const result = await Swal.fire({
                title: '¿Procesar Venta?',
                html: `
                    <div class="text-left p-4">
                        <p class="mb-2"><strong>Productos:</strong> ${cart.length}</p>
                        <p class="mb-2"><strong>Total:</strong> <span class="text-green-600 text-xl font-bold">$${total.toFixed(2)}</span></p>
                        <p class="mb-2"><strong>Método de pago:</strong> ${paymentMethodName}</p>
                        ${transferReference ? `<p class="mb-2 text-sm"><strong>Ref:</strong> ${transferReference}</p>` : ''}
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, procesar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            });
            
            if (!result.isConfirmed) return;
            
            // Preparar datos para enviar
            const saleData = {
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price
                })),
                payment_method: paymentMethod,
                transfer_type: transferType,
                transfer_reference: transferReference,
                customer_id: null // Por ahora sin cliente específico
            };
            
            try {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando venta...',
                    html: 'Por favor espera',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch('{{ route("pos.procesar-venta") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(saleData)
                });
                
                const responseData = await response.json();
                
                if (responseData.success) {
                    // Cerrar loading
                    Swal.close();
                    
                    // Mostrar éxito con opción de imprimir
                    const successResult = await Swal.fire({
                        title: '¡Venta Exitosa! 🎉',
                        html: `
                            <div class="text-left p-4">
                                <p class="mb-2"><strong>Ticket:</strong> #${responseData.sale_id.toString().padStart(6, '0')}</p>
                                <p class="mb-2"><strong>Total:</strong> <span class="text-green-600 text-xl font-bold">$${responseData.total.toFixed(2)}</span></p>
                                <p class="text-sm text-gray-600 mt-4">¿Desea imprimir el ticket?</p>
                            </div>
                        `,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: '<i class="fas fa-print"></i> Imprimir',
                        cancelButtonText: 'Cerrar',
                        reverseButtons: true,
                        showClass: {
                            popup: 'animate__animated animate__bounceIn'
                        }
                    });
                    
                    if (successResult.isConfirmed) {
                        printTicket(responseData.sale_id);
                    }
                    
                    // Limpiar carrito
                    cart = [];
                    updateCartDisplay();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: responseData.message || 'No se pudo procesar la venta',
                        icon: 'error',
                        confirmButtonColor: '#EF4444',
                        showClass: {
                            popup: 'animate__animated animate__shakeX'
                        }
                    });
                }
                
            } catch (error) {
                console.error('Error al procesar venta:', error);
                Swal.fire({
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor',
                    icon: 'error',
                    confirmButtonColor: '#EF4444',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    }
                });
            }
        }
        
        // Imprimir ticket
        function printTicket(saleId) {
            const ticketUrl = `{{ url('/ventas') }}/${saleId}/ticket?print=1`;
            const ticketWindow = window.open(ticketUrl, '_blank', 'width=400,height=600');
        }

        // Inicializar cuando cargue la página
        document.addEventListener('DOMContentLoaded', function() {
            updateCartDisplay();
        });
    </script>
</x-app-layout>