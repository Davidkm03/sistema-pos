<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $businessSettings->business_name ?? config('app.name') }} - Sistema POS</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-900 min-h-screen">
    
    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Decorative background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-4xl w-full">
            <!-- Main Card -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                <!-- Header with Logo -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-12 text-center">
                    <div class="flex justify-center mb-6">
                        @if($businessSettings->logo_url)
                            <div class="h-24 w-24 rounded-2xl bg-white shadow-xl p-3 flex items-center justify-center">
                                <img src="{{ $businessSettings->logo_url }}" alt="{{ $businessSettings->business_name }}" class="h-full w-full object-contain">
                            </div>
                        @else
                            <div class="h-24 w-24 rounded-2xl bg-white shadow-xl flex items-center justify-center">
                                <span class="text-5xl font-bold text-blue-600">{{ substr($businessSettings->business_name ?? 'S', 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-3">
                        {{ $businessSettings->business_name ?? 'Sistema POS' }}
                    </h1>
                    
                    <p class="text-blue-100 text-lg font-medium">
                        Sistema de Punto de Venta Profesional
                    </p>
                </div>

                <!-- Content -->
                <div class="px-8 py-12">
                    @auth
                        <!-- Authenticated View -->
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-medium mb-6">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Sesión activa
                            </div>
                            
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                ¡Bienvenido de nuevo!
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-300 mb-8">
                                Accede a tu panel de control para gestionar ventas, inventario y más.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-lg shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                Ir al Dashboard
                            </a>
                            
                            <a href="{{ route('pos.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-blue-600 dark:hover:border-blue-400 text-gray-700 dark:text-gray-200 font-semibold text-lg transition-all hover:bg-gray-50 dark:hover:bg-gray-800">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Punto de Venta
                            </a>
                        </div>
                    @else
                        <!-- Guest View -->
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                Bienvenido al Sistema
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-300 mb-8">
                                Inicie sesión para acceder al panel de administración y gestionar su negocio.
                            </p>
                        </div>

                        <div class="flex justify-center mb-8">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-3 px-10 py-5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xl shadow-2xl shadow-blue-500/40 transition-all transform hover:scale-105">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Iniciar Sesión
                            </a>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Seguro</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Datos protegidos</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Rápido</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ventas eficientes</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Reportes</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Análisis completo</p>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Business Info Footer -->
                @if($businessSettings->business_address || $businessSettings->business_phone || $businessSettings->business_email)
                <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                        @if($businessSettings->business_phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="font-medium">{{ $businessSettings->business_phone }}</span>
                            </div>
                        @endif
                        
                        @if($businessSettings->business_email)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">{{ $businessSettings->business_email }}</span>
                            </div>
                        @endif
                        
                        @if($businessSettings->business_address)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-medium">{{ $businessSettings->business_address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer Note -->
            <div class="text-center mt-8 text-gray-500 dark:text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} {{ $businessSettings->business_name ?? config('app.name') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

</body>
</html>
