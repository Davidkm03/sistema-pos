<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#4F46E5">
    <title>POS Móvil - {{ setting('business_name', 'Sistema POS') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
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
        
        /* Thumb zone highlight (solo para debug) */
        .thumb-zone-easy {
            /* Verde: Zona fácil de alcanzar con el pulgar */
        }
        .thumb-zone-moderate {
            /* Amarillo: Zona moderada */
        }
        .thumb-zone-hard {
            /* Rojo: Zona difícil */
        }
        
        /* Tap highlight optimization */
        * {
            -webkit-tap-highlight-color: rgba(79, 70, 229, 0.1);
        }
        
        /* Touch action optimization */
        .touch-optimized {
            touch-action: manipulation;
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
    
    <!-- Contenido principal con tabs -->
    <main class="fixed inset-0 overflow-hidden" 
          style="padding-top: calc(var(--header-height) + var(--safe-area-inset-top)); 
                 padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-inset-bottom));">
        
        <!-- Tab: Vender (por defecto) -->
        <div x-show="activeTab === 'sell'" class="h-full overflow-y-auto hide-scrollbar pb-24">
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
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Productos Recientes</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden touch-optimized smooth-transition active:scale-95">
                        <!-- Imagen del producto -->
                        <div class="aspect-square bg-gray-100 relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Badge de stock bajo -->
                            @if($product->stock <= $product->min_stock)
                            <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                Stock: {{ $product->stock }}
                            </div>
                            @endif
                        </div>
                        
                        <!-- Info del producto -->
                        <div class="p-2">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1" style="min-height: 2.5rem;">
                                {{ $product->name }}
                            </h4>
                            <p class="text-lg font-bold text-indigo-600">
                                ${{ number_format($product->price, 0) }}
                            </p>
                        </div>
                        
                        <!-- Botón agregar -->
                        <button @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')" 
                                class="w-full bg-indigo-600 text-white py-2 text-sm font-medium touch-optimized smooth-transition active:bg-indigo-700">
                            <span class="hidden sm:inline">Agregar</span>
                            <span class="sm:hidden">+</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Tab: Órdenes -->
        <div x-show="activeTab === 'orders'" class="h-full flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Órdenes del día</p>
                <p class="text-sm mt-1">Próximamente</p>
            </div>
        </div>
        
        <!-- Tab: Inventario -->
        <div x-show="activeTab === 'inventory'" class="h-full flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <p>Inventario</p>
                <p class="text-sm mt-1">Próximamente</p>
            </div>
        </div>
        
        <!-- Tab: Reportes -->
        <div x-show="activeTab === 'reports'" class="h-full flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p>Reportes</p>
                <p class="text-sm mt-1">Próximamente</p>
            </div>
        </div>
        
        <!-- Tab: Más -->
        <div x-show="activeTab === 'more'" class="h-full overflow-y-auto hide-scrollbar">
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">Más opciones</h3>
                <div class="space-y-2">
                    <a href="{{ route('settings.index') }}" class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 touch-optimized">
                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Configuración</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 touch-optimized">
                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Mi Perfil</span>
                    </a>
                    <a href="{{ route('pos.index') }}" class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 touch-optimized">
                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">POS Desktop</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Carrito Bottom Sheet -->
    <div x-show="cart.length > 0" 
         class="fixed bottom-0 left-0 right-0 z-30 bg-white border-t-2 border-indigo-600 rounded-t-2xl shadow-2xl smooth-transition"
         :class="cartExpanded ? 'h-[70vh]' : 'h-auto'"
         style="padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-inset-bottom));">
        
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
            <svg class="w-5 h-5 text-gray-400 smooth-transition" :class="cartExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
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
                    
                    <!-- Controles cantidad -->
                    <div class="flex items-center gap-2">
                        <button @click="updateQuantity(index, item.quantity - 1)" 
                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center touch-optimized active:bg-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="w-8 text-center font-semibold" x-text="item.quantity"></span>
                        <button @click="updateQuantity(index, item.quantity + 1)" 
                                class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center touch-optimized active:bg-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">IVA (19%):</span>
                    <span class="font-semibold" x-text="'$' + cartTax.toLocaleString()"></span>
                </div>
                <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                    <span>TOTAL:</span>
                    <span class="text-indigo-600" x-text="'$' + cartTotal.toLocaleString()"></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAB (Floating Action Button) -->
    <div x-show="cart.length > 0" 
         class="fixed z-50 touch-optimized"
         style="bottom: calc(var(--bottom-nav-height) + var(--safe-area-inset-bottom) + 16px); right: 16px;">
        <button @click="openCheckout()" 
                class="w-14 h-14 bg-indigo-600 rounded-full shadow-lg flex items-center justify-center text-white smooth-transition active:scale-95 hover:bg-indigo-700">
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
                    <div class="flex justify-between">
                        <span class="text-gray-600">IVA (19%):</span>
                        <span class="font-semibold" x-text="'$' + cartTax.toLocaleString()"></span>
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
                        :disabled="!paymentMethod"
                        :class="paymentMethod ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full py-4 rounded-lg text-white font-bold text-lg touch-optimized smooth-transition">
                    <span x-show="paymentMethod === 'efectivo'">Cobrar $<span x-text="finalTotal.toLocaleString()"></span></span>
                    <span x-show="paymentMethod === 'tarjeta'">Procesar Tarjeta</span>
                    <span x-show="paymentMethod === 'link'">Generar Link de Pago</span>
                    <span x-show="paymentMethod === 'billetera'">Cobrar con Billetera</span>
                    <span x-show="!paymentMethod">Selecciona método de pago</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 thumb-zone-easy" 
         style="height: var(--bottom-nav-height); padding-bottom: var(--safe-area-inset-bottom);">
        <div class="flex items-center justify-around h-full">
            <button @click="activeTab = 'sell'" 
                    :class="activeTab === 'sell' ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-xs mt-1">Vender</span>
                <div x-show="cart.length > 0" class="absolute top-1 w-5 h-5 bg-indigo-600 text-white text-xs rounded-full flex items-center justify-center" x-text="cart.length"></div>
            </button>
            
            <button @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-xs mt-1">Órdenes</span>
            </button>
            
            <button @click="activeTab = 'inventory'" 
                    :class="activeTab === 'inventory' ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="text-xs mt-1">Inventario</span>
            </button>
            
            <button @click="activeTab = 'reports'" 
                    :class="activeTab === 'reports' ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="text-xs mt-1">Reportes</span>
            </button>
            
            <button @click="activeTab = 'more'" 
                    :class="activeTab === 'more' ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center flex-1 h-full touch-optimized smooth-transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
                <span class="text-xs mt-1">Más</span>
            </button>
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
                
                get cartSubtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                
                get cartTax() {
                    return Math.round(this.cartSubtotal * 0.19);
                },
                
                get cartTotal() {
                    return this.cartSubtotal + this.cartTax;
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
                    
                    // Auto-expandir carrito si es el primer item
                    if (this.cart.length === 1) {
                        setTimeout(() => {
                            this.cartExpanded = true;
                        }, 300);
                    }
                },
                
                updateQuantity(index, newQuantity) {
                    if (newQuantity <= 0) {
                        this.cart.splice(index, 1);
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
                },
                
                get tipAmount() {
                    if (this.customTip > 0) {
                        return this.customTip;
                    }
                    return Math.round(this.cartTotal * (this.tipPercent / 100));
                },
                
                get finalTotal() {
                    return this.cartTotal + this.tipAmount;
                },
                
                get changeAmount() {
                    if (this.paymentMethod === 'efectivo' && this.receivedAmount > 0) {
                        return this.receivedAmount - this.finalTotal;
                    }
                    return 0;
                },
                
                processSale() {
                    if (!this.paymentMethod) {
                        alert('Selecciona un método de pago');
                        return;
                    }
                    
                    if (this.paymentMethod === 'efectivo' && this.receivedAmount < this.finalTotal) {
                        alert('El monto recibido es insuficiente');
                        return;
                    }
                    
                    // Aquí iría la lógica para guardar la venta
                    alert(`Venta procesada!\nTotal: $${this.finalTotal.toLocaleString()}\nMétodo: ${this.paymentMethod}`);
                    
                    // Limpiar carrito y cerrar
                    this.cart = [];
                    this.closeCheckout();
                    
                    if (navigator.vibrate) {
                        navigator.vibrate([100, 50, 100, 50, 200]);
                    }
                }
            }
        }
    </script>
</body>
</html>
