<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $businessSettings->business_name ?? config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
    
    <nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    @if($businessSettings->logo_url)
                        <img src="{{ $businessSettings->logo_url }}" alt="{{ $businessSettings->business_name }}" class="h-10 w-10 rounded-lg object-cover">
                    @else
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white font-bold text-xl">{{ substr($businessSettings->business_name ?? 'T', 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $businessSettings->business_name ?? 'Mi Tienda' }}</h1>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">Registrarse</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="relative overflow-hidden py-20 sm:py-32">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 via-purple-600/5 to-pink-600/5 dark:from-blue-600/10 dark:via-purple-600/10 dark:to-pink-600/10"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold mb-6">
                    ✨ Bienvenido a {{ $businessSettings->business_name ?? 'Mi Tienda' }}
                </span>
                
                <h1 class="text-5xl sm:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    Las Mejores <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Ofertas</span> del Momento
                </h1>
                
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    Descubre nuestros productos destacados y aprovecha increíbles descuentos en artículos seleccionados
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('pos.index') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold text-lg shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">Ver Productos</a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold text-lg shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">Comenzar Ahora</a>
                    @endauth
                    
                    <a href="#promociones" class="px-8 py-4 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-blue-600 dark:hover:border-blue-400 text-gray-700 dark:text-gray-200 font-semibold text-lg transition-all">Ver Promociones</a>
                </div>
            </div>
        </div>
    </section>

    @if($featuredProducts->count() > 0)
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">🌟 Productos Destacados</h2>
                <p class="text-gray-600 dark:text-gray-400">Los favoritos de nuestros clientes</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredProducts as $product)
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-3 py-1 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-xs font-bold shadow-lg">⭐ DESTACADO</span>
                    </div>
                    
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl font-bold text-gray-400 dark:text-gray-500">{{ substr($product->name, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ $product->category->name ?? 'General' }}</span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $product->name }}</h3>
                        
                        <div class="flex items-baseline gap-2 mb-4">
                            @if($product->is_on_sale && $product->sale_price)
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">\${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-gray-500 line-through">\${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">\${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Stock: <span class="font-semibold">{{ $product->stock }}</span></span>
                            @if($product->is_on_sale && $product->calculated_discount_percentage)
                                <span class="px-2 py-1 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-bold">-{{ $product->calculated_discount_percentage }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($saleProducts->count() > 0)
    <section id="promociones" class="py-16 bg-gradient-to-br from-red-50 via-pink-50 to-purple-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-1.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm font-semibold mb-4">🔥 ¡Ofertas Limitadas!</div>
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">Productos en Promoción</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Aprovecha estos descuentos increíbles</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($saleProducts as $product)
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border-2 border-red-200 dark:border-red-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute top-4 left-4 z-10">
                        <div class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold shadow-lg transform -rotate-3">
                            <div class="text-lg leading-none">-{{ $product->calculated_discount_percentage ?? $product->discount_percentage }}%</div>
                            <div class="text-xs">OFF</div>
                        </div>
                    </div>
                    
                    <div class="aspect-square bg-gradient-to-br from-red-100 to-pink-100 dark:from-red-900/20 dark:to-pink-900/20">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-5xl font-bold text-red-300 dark:text-red-700">{{ substr($product->name, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="mb-2">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ $product->category->name ?? 'General' }}</span>
                        </div>
                        
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 min-h-[3rem]">{{ $product->name }}</h3>
                        
                        <div class="mb-3">
                            <span class="text-sm text-gray-500 dark:text-gray-400 line-through">\${{ number_format($product->price, 2) }}</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">\${{ number_format($product->final_price, 2) }}</span>
                                <span class="text-xs text-gray-600 dark:text-gray-400">Ahorra \${{ number_format($product->discount_amount, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600 dark:text-gray-400">Stock: <span class="font-semibold">{{ $product->stock }}</span></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($featuredProducts->count() === 0 && $saleProducts->count() === 0)
    <section class="py-32">
        <div class="max-w-3xl mx-auto text-center px-4">
            <div class="inline-block p-6 rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Próximamente...</h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">Estamos preparando productos increíbles para ti. ¡Vuelve pronto!</p>
            @auth
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Administrar Productos
                </a>
            @endauth
        </div>
    </section>
    @endif

    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        @if($businessSettings->logo_url)
                            <img src="{{ $businessSettings->logo_url }}" alt="{{ $businessSettings->business_name }}" class="h-8 w-8 rounded-lg object-cover">
                        @else
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-white font-bold">{{ substr($businessSettings->business_name ?? 'T', 0, 1) }}</span>
                            </div>
                        @endif
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $businessSettings->business_name ?? 'Mi Tienda' }}</span>
                    </div>
                    @if($businessSettings->business_address || $businessSettings->business_phone || $businessSettings->business_email)
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            @if($businessSettings->business_address)
                                <p>{{ $businessSettings->business_address }}</p>
                            @endif
                            @if($businessSettings->business_phone)
                                <p>{{ $businessSettings->business_phone }}</p>
                            @endif
                            @if($businessSettings->business_email)
                                <p>{{ $businessSettings->business_email }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Dashboard</a></li>
                            <li><a href="{{ route('pos.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Punto de Venta</a></li>
                            <li><a href="{{ route('products.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Productos</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Iniciar Sesión</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Registrarse</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase mb-4">Información</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $businessSettings->receipt_footer ?? '¡Gracias por tu preferencia!' }}</p>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $businessSettings->business_name ?? config('app.name') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>
