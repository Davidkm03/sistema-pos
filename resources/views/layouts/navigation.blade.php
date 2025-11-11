<nav x-data="{ 
    open: false, 
    operationsOpen: true, 
    managementOpen: false, 
    salesOpen: false, 
    documentsOpen: false, 
    analysisOpen: false, 
    systemOpen: false 
}" class="relative z-50">
    <!-- Mobile Menu Button -->
    <button @click="open = !open" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-2xl border-2 border-white/30 hover:scale-110 transition-transform">
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Mobile Overlay -->
    <div x-show="open" 
         @click="open = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <div x-show="open || window.innerWidth >= 1024"
         @resize.window="if (window.innerWidth >= 1024) open = false"
         x-transition:enter="lg:transition-none transition ease-out duration-300"
         x-transition:enter-start="lg:transform-none -translate-x-full"
         x-transition:enter-end="lg:transform-none translate-x-0"
         x-transition:leave="lg:transition-none transition ease-in duration-200"
         x-transition:leave-start="lg:transform-none translate-x-0"
         x-transition:leave-end="lg:transform-none -translate-x-full"
         class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-gray-900 via-blue-900 to-indigo-900 shadow-2xl border-r-4 border-blue-400 flex flex-col z-50"
         style="display: none;"
         x-init="if (window.innerWidth >= 1024) $el.style.display = 'flex'">
        
        <!-- Logo / Header -->
        <div class="px-6 py-6 border-b-2 border-white/20">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                @if(setting('business_logo'))
                    <img src="{{ setting()->logo_url }}" alt="{{ setting('business_name') }}" class="h-12 w-auto transition-transform group-hover:scale-110 drop-shadow-xl">
                @else
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-2xl transform group-hover:rotate-12 transition-all duration-300" 
                             style="background: linear-gradient(135deg, {{ setting('primary_color', '#3B82F6') }} 0%, {{ setting('secondary_color', '#10B981') }} 100%);">
                            {{ substr(setting('business_name', 'POS'), 0, 1) }}
                        </div>
                        <div>
                            <div class="text-lg font-black text-white group-hover:text-blue-200 transition-colors drop-shadow-lg">
                                {{ setting('business_name', config('app.name', 'POS')) }}
                            </div>
                            <div class="text-xs font-semibold text-blue-300">Sistema POS</div>
                        </div>
                    </div>
                @endif
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               @click="open = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Operaciones Dropdown -->
            <div class="space-y-1">
                <button @click="operationsOpen = !operationsOpen" 
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs(['pos.*', 'products.*', 'inventory.*']) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>Operaciones</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="operationsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="operationsOpen" x-collapse class="ml-4 space-y-1">
                    @can('access-pos')
                    <a href="{{ route('pos.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('pos.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Punto de Venta
                    </a>
                    @endcan

                    @can('view-products')
                    <a href="{{ route('products.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Productos
                    </a>
                    @endcan

                    @can('view-inventory')
                    <a href="{{ route('inventory.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('inventory.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Inventario
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Gestión Comercial Dropdown -->
            <div class="space-y-1">
                <button @click="managementOpen = !managementOpen" 
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs(['customers.*', 'suppliers.*', 'purchases.*', 'expenses.*', 'categories.*']) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Gestión Comercial</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="managementOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="managementOpen" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('customers.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Clientes
                    </a>

                    <a href="{{ route('suppliers.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Proveedores
                    </a>

                    <a href="{{ route('purchases.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('purchases.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Compras
                    </a>

                    <a href="{{ route('expenses.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Gastos
                    </a>

                    @can('manage-settings')
                    <a href="{{ route('categories.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Categorías
                    </a>
                    @endcan
                </div>
            </div>

            @canany(['view-sales', 'view-all-sales'])
            <!-- Ventas Dropdown -->
            <div class="space-y-1">
                <button @click="salesOpen = !salesOpen" 
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('sales.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Ventas</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="salesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="salesOpen" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('sales.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('sales.index') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver Ventas
                    </a>
                    
                    @canany(['cancel-own-sales', 'cancel-any-sales'])
                    <a href="{{ route('sales.manager') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('sales.manager') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Gestión
                    </a>
                    @endcanany
                    
                    @can('view-audit-log')
                    <a href="{{ route('sales.audit') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('sales.audit') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Auditoría
                    </a>
                    @endcan
                </div>
            </div>
            @endcanany

            <!-- Documentos Dropdown -->
            <div class="space-y-1">
                <button @click="documentsOpen = !documentsOpen" 
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('quotes.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Documentos</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="documentsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="documentsOpen" x-collapse class="ml-4 space-y-1">
                    @can('quotes.view')
                    <a href="{{ route('quotes.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('quotes.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Cotizaciones
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Análisis Dropdown -->
            <div class="space-y-1">
                <button @click="analysisOpen = !analysisOpen" 
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs(['reports.*', 'goals.*']) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Análisis</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="analysisOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="analysisOpen" x-collapse class="ml-4 space-y-1">
                    @can('view-reports')
                    <a href="{{ route('reports.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Reportes
                    </a>
                    @endcan

                    @can('view-goals')
                    <a href="{{ route('goals.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('goals.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Metas
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Sistema Dropdown -->
            <div class="space-y-1">
                <button @click="systemOpen = !systemOpen" 
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs(['settings.*', 'users.*', 'admin.*']) ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg border-2 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Sistema</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="systemOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="systemOpen" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('settings.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuración
                    </a>

                    @role('Admin|super-admin')
                    <a href="{{ route('users.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Usuarios
                    </a>
                    @endrole

                    @role('super-admin')
                    <a href="{{ route('admin.roles.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Gestión de Roles
                    </a>
                    
                    <a href="{{ route('admin.empresas.index') }}" 
                       @click="open = false"
                       class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.empresas.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Gestión de Empresas
                    </a>
                    @endrole
                </div>
            </div>

        <!-- User Section at Bottom -->
        <div class="px-3 py-4 border-t-2 border-white/20">
            <div class="flex items-center gap-3 px-4 py-3 bg-white/10 rounded-xl border-2 border-white/20 backdrop-blur-sm">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-lg border-2 border-white/30" 
                     style="background: linear-gradient(135deg, {{ setting('primary_color', '#3B82F6') }} 0%, {{ setting('secondary_color', '#10B981') }} 100%);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-black text-white text-sm truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs font-semibold text-blue-200 truncate">{{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-red-400 hover:text-white hover:bg-red-600/20 border-2 border-transparent hover:border-red-400/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>
