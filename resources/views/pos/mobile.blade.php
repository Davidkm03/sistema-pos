<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#4F46E5">
    <title>POS Móvil - {{ setting('business_name', 'Sistema POS') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Animate.css for SweetAlert2 animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        /* Variables CSS para mobile */
        :root {
            --safe-area-inset-top: env(safe-area-inset-top);
            --safe-area-inset-bottom: env(safe-area-inset-bottom);
            --header-height: 64px;
            --bottom-nav-height: 60px;
            --fab-size: 56px;
        }
        
        /* Ocultar scrollbar pero mantener funcionalidad */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        /* Animaciones suaves */
        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Bottom sheet handle */
        .bottom-sheet-handle {
            width: 40px;
            height: 4px;
            background: #D1D5DB;
            border-radius: 2px;
            margin: 8px auto;
        }
        
        /* Tap highlight optimization */
        * {
            -webkit-tap-highlight-color: rgba(79, 70, 229, 0.1);
        }
        
        /* Touch action optimization */
        .touch-optimized {
            touch-action: manipulation;
        }
        
        /* Fix para x-cloak (evitar parpadeo de Alpine.js) */
        [x-cloak] { 
            display: none !important; 
        }
        
        /* Asegurar opacidad completa en botones importantes */
        .bg-indigo-600 {
            background-color: #4F46E5 !important;
            opacity: 1 !important;
        }
        
        .text-white {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="mobilePOS()">
    
    <!-- Header compacto -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-white border-b border-gray-200" 
            style="height: var(--header-height); padding-top: var(--safe-area-inset-top);">
        <div class="flex items-center justify-between h-full px-4">
            <!-- Menú hamburguesa -->
            <button @click="openMenu = true" class="p-2 -ml-2 touch-optimized">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Buscador (expandible) -->
            <div class="flex-1 mx-3" x-show="!searchExpanded">
                <button @click="searchExpanded = true" 
                        class="w-full flex items-center px-3 py-2 bg-gray-100 rounded-lg text-left touch-optimized">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-sm text-gray-500">Buscar productos...</span>
                </button>
            </div>
            
            <!-- Indicador de estado -->
            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-600">Turno 1</span>
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full bg-green-500 mr-1"></div>
                    <span class="text-gray-500 hidden sm:inline">Online</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Menú lateral (Sidebar) -->
    <div x-show="openMenu" 
         x-cloak
         @click="openMenu = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div @click.stop 
             x-show="openMenu"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="bg-white w-80 max-w-[85vw] h-full shadow-2xl overflow-y-auto">
            
            <!-- Header del menú -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Menú</h2>
                    <button @click="openMenu = false" class="p-2 hover:bg-indigo-500 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-indigo-200 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Opciones del menú -->
            <nav class="p-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('pos.mobile') }}" class="flex items-center gap-3 px-4 py-3 bg-indigo-50 text-indigo-600 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>POS Móvil</span>
                </a>
                
                <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Ventas</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Productos</span>
                </a>
                
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Reportes</span>
                </a>
                
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Configuración</span>
                </a>
                
                <hr class="my-4">
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- Search Expandido (Modal) -->
    <div x-show="searchExpanded"
         x-cloak
         @click="searchExpanded = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div @click.stop 
             x-show="searchExpanded"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="-translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="-translate-y-full"
             class="bg-white w-full max-h-[80vh] rounded-b-3xl shadow-2xl overflow-hidden flex flex-col"
             style="padding-top: var(--safe-area-inset-top);">
            
            <!-- Header del search -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <button @click="searchExpanded = false" class="p-2 -ml-2">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <input type="text" 
                           x-model="searchQuery"
                           @input="filterProducts()"
                           placeholder="Buscar por nombre o SKU..."
                           autofocus
                           class="flex-1 px-4 py-2 bg-gray-100 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button @click="searchQuery = ''; filterProducts()" 
                            x-show="searchQuery"
                            class="p-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Resultados del search -->
            <div class="flex-1 overflow-y-auto p-4">
                <div x-show="!searchQuery" class="text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-sm">Escribe para buscar productos</p>
                </div>
                
                <div x-show="searchQuery && filteredProducts.length === 0" class="text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm">No se encontraron productos</p>
                </div>
                
                <!-- Lista de productos filtrados -->
                <div class="space-y-2">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product.id, product.name, product.price, product.image); searchExpanded = false; searchQuery = ''"
                             class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 active:bg-gray-100 cursor-pointer touch-optimized">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                                <img x-show="product.image" 
                                     :src="product.image ? `/storage/${product.image}` : ''" 
                                     :alt="product.name"
                                     class="w-full h-full object-cover">
                                <div x-show="!product.image" class="w-full h-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900 truncate" x-text="product.name"></h4>
                                <p class="text-xs text-gray-500" x-text="product.sku"></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-indigo-600" x-text="'$' + product.price.toLocaleString()"></p>
                                <p class="text-xs text-gray-500">Stock: <span x-text="product.stock"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenido principal con tabs -->
    <main class="fixed inset-0 overflow-hidden" 
          style="padding-top: calc(var(--header-height) + var(--safe-area-inset-top)); 
                 padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-inset-bottom));">
        
        <!-- Vista de Vender (siempre visible) -->
        <div class="h-full overflow-y-auto hide-scrollbar pb-24">
            <!-- Categorías horizontales -->
            <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
                <div class="flex gap-2 px-4 py-3 overflow-x-auto hide-scrollbar">
                    <button @click="selectedCategory = null" 
                            :class="selectedCategory === null ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap touch-optimized smooth-transition">
                        Todos
                    </button>
                    @foreach($categories as $category)
                    <button @click="selectedCategory = {{ $category->id }}" 
                            :class="selectedCategory === {{ $category->id }} ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap touch-optimized smooth-transition">
                        {{ $category->name }}
                        <span class="ml-1 opacity-70">({{ $category->products_count }})</span>
                    </button>
                    @endforeach
                </div>
            </div>
            
            <!-- Grid de productos -->
            <div class="p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Productos Disponibles</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col"
                         x-show="selectedCategory === null || selectedCategory === {{ $product->category_id }}">
                        <!-- Imagen del producto -->
                        <div class="aspect-square bg-gray-100 relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain"
                                     loading="lazy">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Badge de stock bajo -->
                            @if($product->stock <= $product->min_stock)
                            <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                ¡{{ $product->stock }}!
                            </div>
                            @endif
                        </div>
                        
                        <!-- Info del producto -->
                        <div class="p-3 flex-1">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1" style="min-height: 2.5rem;">
                                {{ $product->name }}
                            </h4>
                            <p class="text-lg font-bold text-indigo-600">
                                ${{ number_format($product->price, 0) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Stock: {{ $product->stock }}
                            </p>
                        </div>
                        
                        <!-- Botón agregar - SIEMPRE VISIBLE -->
                        <button @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image }}')" 
                                type="button"
                                style="background-color: #4F46E5 !important; color: white !important; opacity: 1 !important;"
                                class="w-full bg-indigo-600 text-white py-3 text-sm font-bold touch-optimized smooth-transition active:bg-indigo-700 hover:bg-indigo-700 flex items-center justify-center gap-2 opacity-100 border-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span style="color: white !important;">AGREGAR</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
    
    <!-- Carrito Bottom Sheet -->
    <div x-show="cart.length > 0" 
         x-cloak
         class="fixed bottom-0 left-0 right-0 z-30 bg-white border-t-2 border-indigo-600 rounded-t-2xl shadow-2xl smooth-transition opacity-100"
         :class="cartExpanded ? 'h-[70vh]' : 'h-auto'"
         style="padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-inset-bottom)); background-color: #ffffff !important;">
        
        <!-- Handle para drag -->
        <div @click="cartExpanded = !cartExpanded" class="cursor-pointer pt-2">
            <div class="bottom-sheet-handle"></div>
        </div>
        
        <!-- Header del carrito -->
        <div @click="cartExpanded = !cartExpanded" class="px-4 py-3 flex items-center justify-between cursor-pointer">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-semibold text-gray-900" x-text="cart.length + ' items'"></span>
                <span class="text-indigo-600 font-bold text-lg" x-text="'$' + cartTotal.toLocaleString()"></span>
            </div>
            <div class="flex items-center gap-2">
                <button @click.stop="clearCart()" class="p-1.5 text-red-500 hover:bg-red-50 rounded-full touch-optimized" title="Vaciar carrito">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                <svg class="w-5 h-5 text-gray-400 smooth-transition" :class="cartExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
        
        <!-- Contenido del carrito (solo si está expandido) -->
        <div x-show="cartExpanded" class="overflow-y-auto h-[calc(70vh-120px)] px-4">
            <template x-for="(item, index) in cart" :key="index">
                <div class="flex items-center gap-3 py-3 border-b border-gray-200">
                    <!-- Imagen -->
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                        <img x-show="item.image" :src="'/storage/' + item.image" :alt="item.name" class="w-full h-full object-contain">
                        <div x-show="!item.image" class="flex items-center justify-center h-full">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 text-sm truncate" x-text="item.name"></h4>
                        <p class="text-indigo-600 font-semibold" x-text="'$' + item.price.toLocaleString()"></p>
                    </div>
                    
                    <!-- Controles cantidad mejorados -->
                    <div class="flex items-center gap-2">
                        <button @click="updateQuantity(index, item.quantity - 1)" 
                                type="button"
                                style="background-color: #F3F4F6 !important; opacity: 1 !important;"
                                class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center touch-optimized active:bg-gray-200 border-0 transition-colors duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #374151 !important;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        
                        <!-- Input táctil para cantidad -->
                        <input 
                            type="number" 
                            inputmode="numeric"
                            pattern="[0-9]*"
                            min="1"
                            :value="item.quantity"
                            @input="updateQuantity(index, parseInt($event.target.value) || 1)"
                            @focus="$event.target.select()"
                            style="color: #111827 !important; background-color: #F9FAFB !important; border: 1px solid #E5E7EB !important;"
                            class="w-16 h-10 text-center font-semibold rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        />
                        
                        <button @click="updateQuantity(index, item.quantity + 1)" 
                                type="button"
                                style="background-color: #4F46E5 !important; color: white !important; opacity: 1 !important;"
                                class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center touch-optimized active:bg-indigo-700 border-0 transition-colors duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
            
            <!-- Resumen -->
            <div class="py-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold" x-text="'$' + cartSubtotal.toLocaleString()"></span>
                </div>
                @if(setting('tax_enabled', false))
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">IVA ({{ setting('tax_rate', 19) }}%):</span>
                    <span class="font-semibold" x-text="'$' + cartTax.toLocaleString()"></span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                    <span>TOTAL:</span>
                    <span class="text-indigo-600" x-text="'$' + cartTotal.toLocaleString()"></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAB (Floating Action Button) -->
    <div x-show="cart.length > 0" 
         x-cloak
         class="fixed z-50 touch-optimized opacity-100"
         style="bottom: calc(var(--bottom-nav-height) + var(--safe-area-inset-bottom) + 16px); right: 16px;">
        <button @click="openCheckout()" 
                class="w-14 h-14 bg-indigo-600 rounded-full shadow-lg flex items-center justify-center text-white smooth-transition active:scale-95 hover:bg-indigo-700 opacity-100"
                style="background-color: #4F46E5 !important;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </button>
    </div>
    
    <!-- Modal de Checkout -->
    <div x-show="checkoutOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center sm:justify-center"
         @click.self="closeCheckout()">
        
        <div x-show="checkoutOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95"
             class="bg-white w-full sm:max-w-lg sm:rounded-2xl rounded-t-2xl max-h-[90vh] overflow-y-auto"
             style="padding-bottom: var(--safe-area-inset-bottom);">
            
            <!-- Header del modal -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Cobrar Venta</h2>
                <button @click="closeCheckout()" class="p-2 -mr-2 touch-optimized">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Resumen de la venta -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold" x-text="'$' + cartSubtotal.toLocaleString()"></span>
                    </div>
                    @if(setting('tax_enabled', false))
                    <div class="flex justify-between">
                        <span class="text-gray-600">IVA ({{ setting('tax_rate', 19) }}%):</span>
                        <span class="font-semibold" x-text="'$' + cartTax.toLocaleString()"></span>
                    </div>
                    @endif
                    <div x-show="discountAmount > 0" class="flex justify-between text-orange-600">
                        <span>Descuento (<span x-text="discountPercent"></span>%):</span>
                        <span class="font-semibold">-$<span x-text="discountAmount.toLocaleString()"></span></span>
                    </div>
                    <div x-show="tipAmount > 0" class="flex justify-between text-indigo-600">
                        <span>Propina:</span>
                        <span class="font-semibold" x-text="'$' + tipAmount.toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-300">
                        <span>TOTAL:</span>
                        <span class="text-indigo-600" x-text="'$' + finalTotal.toLocaleString()"></span>
                    </div>
                </div>
            </div>
            
            <!-- Métodos de pago -->
            <div class="px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Método de Pago</h3>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="paymentMethod = 'efectivo'" 
                            :class="paymentMethod === 'efectivo' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border-2 touch-optimized smooth-transition">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium text-sm">Efectivo</span>
                    </button>
                    
                    <button @click="paymentMethod = 'tarjeta'" 
                            :class="paymentMethod === 'tarjeta' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border-2 touch-optimized smooth-transition">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span class="font-medium text-sm">Tarjeta</span>
                    </button>
                    
                    <button @click="paymentMethod = 'link'" 
                            :class="paymentMethod === 'link' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border-2 touch-optimized smooth-transition">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <span class="font-medium text-sm">Link de Pago</span>
                    </button>
                    
                    <button @click="paymentMethod = 'billetera'" 
                            :class="paymentMethod === 'billetera' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border-2 touch-optimized smooth-transition">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Billetera</span>
                    </button>
                </div>
            </div>
            
            <!-- Monto recibido (solo para efectivo) -->
            <div x-show="paymentMethod === 'efectivo'" class="px-6 py-4 border-t border-gray-200">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Monto Recibido</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400 font-semibold">$</span>
                    <input type="number" 
                           x-model.number="receivedAmount"
                           placeholder="0"
                           class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg text-lg font-semibold focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200"
                           inputmode="numeric">
                </div>
                
                <!-- Botones rápidos de montos -->
                <div class="grid grid-cols-4 gap-2 mt-3">
                    <template x-for="amount in [5000, 10000, 20000, 50000]" :key="amount">
                        <button @click="receivedAmount = amount" 
                                class="py-2 px-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium touch-optimized active:bg-gray-200"
                                x-text="'$' + (amount/1000) + 'k'">
                        </button>
                    </template>
                </div>
                
                <!-- Cambio -->
                <div x-show="changeAmount > 0" class="mt-4 p-3 bg-green-50 border-2 border-green-200 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-green-700 font-medium">Cambio:</span>
                        <span class="text-2xl font-bold text-green-600" x-text="'$' + changeAmount.toLocaleString()"></span>
                    </div>
                </div>
                
                <div x-show="receivedAmount > 0 && changeAmount < 0" class="mt-4 p-3 bg-red-50 border-2 border-red-200 rounded-lg">
                    <span class="text-red-700 text-sm font-medium">⚠️ Monto insuficiente</span>
                </div>
            </div>
            
            <!-- Descuento -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Descuento (Opcional)</h3>
                <div class="grid grid-cols-4 gap-2">
                    <button @click="setDiscountPercent(0)" 
                            :class="discountPercent === 0 ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        Sin descuento
                    </button>
                    <button @click="setDiscountPercent(5)" 
                            :class="discountPercent === 5 ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        5%
                    </button>
                    <button @click="setDiscountPercent(10)" 
                            :class="discountPercent === 10 ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        10%
                    </button>
                    <button @click="setDiscountPercent(15)" 
                            :class="discountPercent === 15 ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        15%
                    </button>
                </div>
                
                <!-- Descuento personalizado -->
                <div class="mt-3">
                    <label class="block text-xs text-gray-600 mb-1">Descuento personalizado (Máx: {{ auth()->user()->getMaxDiscountAllowed() }}%)</label>
                    <div class="relative">
                        <input type="number" 
                               x-model.number="customDiscount"
                               @input="setCustomDiscount()"
                               placeholder="0"
                               max="{{ auth()->user()->getMaxDiscountAllowed() }}"
                               step="0.5"
                               class="w-full pl-4 pr-10 py-2 border-2 border-gray-300 rounded-lg text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200"
                               inputmode="decimal">
                        <span class="absolute right-3 top-2.5 text-gray-400 text-sm">%</span>
                    </div>
                </div>
                
                <!-- Razón del descuento (si es >= 5%) -->
                <div x-show="discountPercent >= 5" class="mt-3">
                    <label class="block text-xs text-gray-600 mb-1">Razón del descuento *</label>
                    <input type="text" 
                           x-model="discountReason"
                           placeholder="Ej: Cliente frecuente, promoción, etc."
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                </div>
            </div>
            
            <!-- Propina opcional -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Propina (Opcional)</h3>
                <div class="grid grid-cols-4 gap-2">
                    <button @click="tipPercent = 0; customTip = 0" 
                            :class="tipPercent === 0 && customTip === 0 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        Sin propina
                    </button>
                    <button @click="tipPercent = 5; customTip = 0" 
                            :class="tipPercent === 5 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        5%
                    </button>
                    <button @click="tipPercent = 10; customTip = 0" 
                            :class="tipPercent === 10 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        10%
                    </button>
                    <button @click="tipPercent = 15; customTip = 0" 
                            :class="tipPercent === 15 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'"
                            class="py-2 px-3 rounded-lg border-2 text-sm font-medium touch-optimized">
                        15%
                    </button>
                </div>
                
                <!-- Propina personalizada -->
                <div class="mt-3">
                    <label class="block text-xs text-gray-600 mb-1">Propina personalizada</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                        <input type="number" 
                               x-model.number="customTip"
                               @input="tipPercent = 0"
                               placeholder="0"
                               class="w-full pl-8 pr-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200"
                               inputmode="numeric">
                    </div>
                </div>
            </div>
            
            <!-- Botón procesar venta -->
            <div class="px-6 py-4 border-t border-gray-200">
                <button @click="processSale()" 
                        type="button"
                        :disabled="!paymentMethod"
                        :class="paymentMethod ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed'"
                        :style="paymentMethod ? 'background-color: #4F46E5 !important; color: white !important; opacity: 1 !important;' : 'background-color: #D1D5DB !important; color: #9CA3AF !important; opacity: 1 !important;'"
                        class="w-full py-4 rounded-lg text-white font-bold text-lg touch-optimized smooth-transition border-0">
                    <span x-show="paymentMethod === 'efectivo'" style="color: inherit !important;">Cobrar $<span x-text="finalTotal.toLocaleString()"></span></span>
                    <span x-show="paymentMethod === 'tarjeta'" style="color: inherit !important;">Procesar Tarjeta</span>
                    <span x-show="paymentMethod === 'link'" style="color: inherit !important;">Generar Link de Pago</span>
                    <span x-show="paymentMethod === 'billetera'" style="color: inherit !important;">Cobrar con Billetera</span>
                    <span x-show="!paymentMethod" style="color: inherit !important;">Selecciona método de pago</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 thumb-zone-easy" 
         style="height: var(--bottom-nav-height); padding-bottom: var(--safe-area-inset-bottom);">
        <div class="flex items-center justify-around h-full">
            <a href="{{ route('pos.mobile') }}" 
                    :class="activeTab === 'sell' ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-xs mt-1 font-medium">Vender</span>
                <div x-show="cart.length > 0" class="absolute top-2 right-8 w-5 h-5 bg-indigo-600 text-white text-xs rounded-full flex items-center justify-center font-bold" x-text="cart.length"></div>
            </a>
            
            <a href="{{ route('sales.index') }}" 
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition text-gray-400 hover:text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-xs mt-1">Ventas</span>
            </a>
            
            <a href="{{ route('products.index') }}" 
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition text-gray-400 hover:text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="text-xs mt-1">Productos</span>
            </a>
            
            <a href="{{ route('reports.index') }}" 
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition text-gray-400 hover:text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="text-xs mt-1">Reportes</span>
            </a>
            
            <a href="{{ route('settings.index') }}" 
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition text-gray-400 hover:text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
                <span class="text-xs mt-1">Más</span>
            </a>
        </div>
    </nav>
    
    @livewireScripts
    
    <script>
        function mobilePOS() {
            return {
                activeTab: 'sell',
                cart: [],
                cartExpanded: false,
                searchExpanded: false,
                selectedCategory: null,
                openMenu: false,
                checkoutOpen: false,
                paymentMethod: '',
                receivedAmount: 0,
                tipPercent: 0,
                customTip: 0,
                discountPercent: 0,
                customDiscount: 0,
                discountReason: '',
                maxDiscount: {{ auth()->user()->getMaxDiscountAllowed() }},
                requireReasonFrom: {{ setting('require_reason_from', 5) }},
                searchQuery: '',
                filteredProducts: [],
                allProducts: @json($productsForJS),
                
                get cartSubtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                
                get cartTax() {
                    const taxEnabled = {{ setting('tax_enabled', false) ? 'true' : 'false' }};
                    if (!taxEnabled) return 0;
                    const taxRate = {{ setting('tax_rate', 19) / 100 }};
                    return Math.round(this.cartSubtotal * taxRate);
                },
                
                get cartTotal() {
                    return this.cartSubtotal + this.cartTax;
                },
                
                get discountAmount() {
                    if (this.discountPercent > 0) {
                        return Math.round(this.cartTotal * (this.discountPercent / 100));
                    }
                    return 0;
                },
                
                setDiscountPercent(percent) {
                    if (percent > this.maxDiscount) {
                        Swal.fire({
                            title: 'Descuento No Permitido',
                            text: `Tu límite máximo de descuento es ${this.maxDiscount}%`,
                            icon: 'warning',
                            confirmButtonColor: '#F59E0B'
                        });
                        return;
                    }
                    this.discountPercent = percent;
                    this.customDiscount = 0;
                },
                
                setCustomDiscount() {
                    if (this.customDiscount > this.maxDiscount) {
                        Swal.fire({
                            title: 'Descuento No Permitido',
                            text: `Tu límite máximo de descuento es ${this.maxDiscount}%`,
                            icon: 'warning',
                            confirmButtonColor: '#F59E0B'
                        });
                        this.customDiscount = this.maxDiscount;
                    }
                    this.discountPercent = this.customDiscount;
                },
                
                filterProducts() {
                    if (!this.searchQuery || this.searchQuery.trim() === '') {
                        this.filteredProducts = [];
                        return;
                    }
                    
                    const query = this.searchQuery.toLowerCase().trim();
                    this.filteredProducts = this.allProducts.filter(product => {
                        return product.name.toLowerCase().includes(query) || 
                               product.sku.toLowerCase().includes(query);
                    });
                },
                
                addToCart(id, name, price, image) {
                    const existing = this.cart.find(item => item.id === id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({ id, name, price, image, quantity: 1 });
                    }
                    
                    // Vibración ligera
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                    
                    // Toast de éxito
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: `${name} agregado`,
                        text: existing ? `Cantidad: ${existing.quantity}` : 'Añadido al carrito'
                    });
                    
                    // Auto-expandir carrito si es el primer item
                    if (this.cart.length === 1) {
                        setTimeout(() => {
                            this.cartExpanded = true;
                        }, 300);
                    }
                },
                
                updateQuantity(index, newQuantity) {
                    if (newQuantity <= 0) {
                        const itemName = this.cart[index].name;
                        this.cart.splice(index, 1);
                        
                        // Toast de eliminación
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        
                        Toast.fire({
                            icon: 'info',
                            title: 'Producto eliminado',
                            text: itemName
                        });
                        
                        if (this.cart.length === 0) {
                            this.cartExpanded = false;
                        }
                    } else {
                        this.cart[index].quantity = newQuantity;
                    }
                    
                    if (navigator.vibrate) {
                        navigator.vibrate(30);
                    }
                },
                
                clearCart() {
                    if (this.cart.length === 0) return;
                    
                    Swal.fire({
                        title: '¿Vaciar carrito?',
                        text: `Se eliminarán ${this.cart.length} producto(s)`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Sí, vaciar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cart = [];
                            this.cartExpanded = false;
                            
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'success',
                                title: 'Carrito vaciado'
                            });
                        }
                    });
                },
                
                openCheckout() {
                    this.checkoutOpen = true;
                    this.cartExpanded = false; // Colapsar carrito al abrir checkout
                    if (navigator.vibrate) {
                        navigator.vibrate([50, 100, 50]);
                    }
                },
                
                closeCheckout() {
                    this.checkoutOpen = false;
                    this.paymentMethod = '';
                    this.receivedAmount = 0;
                    this.tipPercent = 0;
                    this.customTip = 0;
                    this.discountPercent = 0;
                    this.customDiscount = 0;
                    this.discountReason = '';
                },
                
                get tipAmount() {
                    if (this.customTip > 0) {
                        return this.customTip;
                    }
                    return Math.round(this.cartTotal * (this.tipPercent / 100));
                },
                
                get finalTotal() {
                    return this.cartTotal - this.discountAmount + this.tipAmount;
                },
                
                get changeAmount() {
                    if (this.paymentMethod === 'efectivo' && this.receivedAmount > 0) {
                        return this.receivedAmount - this.finalTotal;
                    }
                    return 0;
                },
                
                processSale() {
                    if (!this.paymentMethod) {
                        Swal.fire({
                            title: 'Método de Pago',
                            text: 'Selecciona un método de pago',
                            icon: 'warning',
                            confirmButtonColor: '#F59E0B',
                            showClass: {
                                popup: 'animate__animated animate__headShake'
                            }
                        });
                        return;
                    }
                    
                    if (this.paymentMethod === 'efectivo' && this.receivedAmount < this.finalTotal) {
                        Swal.fire({
                            title: 'Monto Insuficiente',
                            text: 'El monto recibido es menor al total de la venta',
                            icon: 'error',
                            confirmButtonColor: '#EF4444',
                            showClass: {
                                popup: 'animate__animated animate__shakeX'
                            }
                        });
                        return;
                    }
                    
                    // Validar razón de descuento
                    if (this.discountPercent >= this.requireReasonFrom && !this.discountReason.trim()) {
                        Swal.fire({
                            title: 'Razón Requerida',
                            text: `Debes indicar la razón del descuento cuando es mayor o igual a ${this.requireReasonFrom}%`,
                            icon: 'warning',
                            confirmButtonColor: '#F59E0B',
                            showClass: {
                                popup: 'animate__animated animate__headShake'
                            }
                        });
                        return;
                    }
                    
                    // Preparar datos para el backend
                    const saleData = {
                        items: this.cart.map(item => ({
                            product_id: item.id,
                            quantity: item.quantity,
                            price: item.price
                        })),
                        payment_method: this.paymentMethod === 'tarjeta' ? 'tarjeta_debito' : this.paymentMethod,
                        customer_id: null, // Por ahora sin cliente
                        tip_amount: this.tipAmount,
                        discount_percentage: this.discountPercent,
                        discount_reason: this.discountReason || null,
                    };
                    
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
                    
                    // Hacer petición al backend
                    fetch('{{ route("pos.procesar-venta") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(saleData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cerrar loading
                            Swal.close();
                            
                            // Vibración de éxito
                            if (navigator.vibrate) {
                                navigator.vibrate([100, 50, 100, 50, 200]);
                            }
                            
                            // Mostrar éxito con opción de imprimir
                            Swal.fire({
                                title: '¡Venta Exitosa! 🎉',
                                html: `
                                    <div class="text-left p-4">
                                        <p class="mb-2"><strong>Ticket:</strong> #${data.sale_id.toString().padStart(6, '0')}</p>
                                        <p class="mb-2"><strong>Total:</strong> <span class="text-green-600 text-xl font-bold">$${data.total.toLocaleString()}</span></p>
                                        ${this.paymentMethod === 'efectivo' && this.changeAmount > 0 ? `<p class="mb-2"><strong>Cambio:</strong> <span class="text-blue-600 font-bold">$${this.changeAmount.toLocaleString()}</span></p>` : ''}
                                        <p class="text-sm text-gray-600 mt-4">¿Desea imprimir el ticket?</p>
                                    </div>
                                `,
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#10B981',
                                cancelButtonColor: '#6B7280',
                                confirmButtonText: 'Imprimir',
                                cancelButtonText: 'Cerrar',
                                reverseButtons: true,
                                showClass: {
                                    popup: 'animate__animated animate__bounceIn'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Abrir ticket para imprimir
                                    this.printTicket(data.sale_id);
                                }
                            });
                            
                            // Limpiar carrito
                            this.cart = [];
                            this.closeCheckout();
                            
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'No se pudo procesar la venta',
                                icon: 'error',
                                confirmButtonColor: '#EF4444',
                                showClass: {
                                    popup: 'animate__animated animate__shakeX'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error de Conexión',
                            text: 'No se pudo conectar con el servidor. Verifica tu conexión.',
                            icon: 'error',
                            confirmButtonColor: '#EF4444',
                            showClass: {
                                popup: 'animate__animated animate__shakeX'
                            }
                        });
                    });
                },
                
                printTicket(saleId) {
                    const ticketUrl = `{{ url('/ventas') }}/${saleId}/ticket?print=1`;
                    const ticketWindow = window.open(ticketUrl, '_blank', 'width=400,height=600');
                }
            }
        }
    </script>
</body>
</html>
