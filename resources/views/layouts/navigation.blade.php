<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        @if(setting('business_logo'))
                            <img src="{{ setting()->logo_url }}" alt="{{ setting('business_name') }}" class="h-10 w-auto transition-transform group-hover:scale-105">
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-lg" 
                                     style="background: linear-gradient(135deg, {{ setting('primary_color', '#3B82F6') }} 0%, {{ setting('secondary_color', '#10B981') }} 100%);">
                                    {{ substr(setting('business_name', 'POS'), 0, 1) }}
                                </div>
                                <span class="text-lg font-bold text-gray-800 group-hover:text-gray-900 transition-colors">
                                    {{ setting('business_name', config('app.name', 'POS')) }}
                                </span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:flex sm:items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    
                    @can('access-pos')
                    <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">
                        POS
                    </x-nav-link>
                    @endcan
                    
                    @can('view-products')
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        Productos
                    </x-nav-link>
                    @endcan
                    
                    @can('view-inventory')
                    <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                        Inventario
                    </x-nav-link>
                    @endcan
                    
                    @canany(['view-sales', 'view-all-sales'])
                    <x-dropdown align="top" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-700 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <span>Ventas</span>
                                <svg class="ms-1 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('sales.index')">
                                Ver Ventas
                            </x-dropdown-link>

                            @canany(['cancel-own-sales', 'cancel-any-sales'])
                            <x-dropdown-link :href="route('sales.manager')">
                                Gestión de Ventas
                            </x-dropdown-link>
                            @endcanany

                            @can('view-audit-log')
                            <x-dropdown-link :href="route('sales.audit')">
                                Log de Auditoría
                            </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                    @endcanany

                    @can('view-reports')
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        Reportes
                    </x-nav-link>
                    @endcan
                    
                    @can('view-goals')
                    <x-nav-link :href="route('goals.index')" :active="request()->routeIs('goals.*')">
                        Metas
                    </x-nav-link>
                    @endcan
                    
                    <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                        Configuración
                    </x-nav-link>
                    
                    @role('Admin')
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        Usuarios
                    </x-nav-link>
                    @endrole
                    
                    @role('super-admin')
                    <x-dropdown align="top" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-700 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Admin</span>
                                <svg class="ms-1 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('admin.roles.index')">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    Gestión de Roles
                                </div>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none transition-all duration-200">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm" 
                                 style="background: linear-gradient(135deg, {{ setting('primary_color', '#3B82F6') }} 0%, {{ setting('secondary_color', '#10B981') }} 100%);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left">
                                <div class="font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}</div>
                            </div>
                            <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- User Info Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" 
                                     style="background: linear-gradient(135deg, {{ setting('primary_color', '#3B82F6') }} 0%, {{ setting('secondary_color', '#10B981') }} 100%);">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                                    <div class="text-xs font-medium text-blue-600 mt-1">
                                        {{ Auth::user()->getRoleNames()->first() ?? 'Sin rol' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            Mi Perfil
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                Cerrar Sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @can('access-pos')
            <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">
                POS
            </x-responsive-nav-link>
            @endcan
            
            @can('view-products')
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Productos
            </x-responsive-nav-link>
            @endcan
            
            @can('view-inventory')
            <x-responsive-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                Inventario
            </x-responsive-nav-link>
            @endcan
            
            @canany(['view-sales', 'view-all-sales'])
            <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                Ventas
            </x-responsive-nav-link>

            @canany(['cancel-own-sales', 'cancel-any-sales'])
            <x-responsive-nav-link :href="route('sales.manager')" :active="request()->routeIs('sales.manager')">
                → Gestión de Ventas
            </x-responsive-nav-link>
            @endcanany

            @can('view-audit-log')
            <x-responsive-nav-link :href="route('sales.audit')" :active="request()->routeIs('sales.audit')">
                → Log de Auditoría
            </x-responsive-nav-link>
            @endcan
            @endcanany

            @can('view-reports')
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                Reportes
            </x-responsive-nav-link>
            @endcan
            
            {{-- Metas - Admin, Supervisor y Cajero pueden ver --}}
            @can('view-goals')
            <x-responsive-nav-link :href="route('goals.index')" :active="request()->routeIs('goals.*')">
                Metas
            </x-responsive-nav-link>
            @endcan
            
            <!-- Configuración General -->
            <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                ⚙️ Configuración
            </x-responsive-nav-link>
            
            @role('Admin')
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                Usuarios
            </x-responsive-nav-link>
            @endrole
            
            @role('super-admin')
            <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Super Admin - Roles
                </div>
            </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="text-xs text-blue-600 mt-1">
                    <span class="font-semibold">Rol:</span> {{ Auth::user()->getRoleNames()->first() ?? 'Sin rol' }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
