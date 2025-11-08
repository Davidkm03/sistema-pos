<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-20px) rotate(3deg); }
                66% { transform: translateY(-10px) rotate(-3deg); }
            }
            
            @keyframes blob {
                0%, 100% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
            }
            
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes pulse-slow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.8; }
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
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
            
            .animate-slide-in-up {
                animation: slideInUp 0.6s ease-out;
            }
            
            .animate-pulse-slow {
                animation: pulse-slow 3s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 dark:from-gray-900 dark:via-slate-900 dark:to-gray-900">
            
            <!-- Animated Background Blobs -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-slate-300 dark:bg-slate-700 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gray-300 dark:bg-gray-700 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-slate-400 dark:bg-slate-600 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            </div>
            
            <!-- Floating Icons -->
            <div class="absolute top-20 left-20 text-slate-300 dark:text-slate-700 opacity-30 animate-float">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            
            <div class="absolute bottom-20 right-20 text-slate-300 dark:text-slate-700 opacity-30 animate-float animation-delay-2000">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            
            <div class="absolute top-1/3 right-1/4 text-slate-300 dark:text-slate-700 opacity-30 animate-float animation-delay-4000">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 w-full max-w-md px-6 animate-slide-in-up">
                
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-block group">
                        <div class="transform transition-all duration-300 hover:scale-110">
                            <x-application-logo class="w-24 h-24 mx-auto drop-shadow-2xl" />
                        </div>
                    </a>
                    
                    <h1 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
                        Bienvenido de Vuelta
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Ingresa tus credenciales para continuar
                    </p>
                </div>

                <!-- Card Container -->
                <div class="bg-white dark:bg-gray-800 backdrop-blur-xl shadow-2xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transform transition-all duration-300 hover:shadow-gray-400/20 dark:hover:shadow-slate-900/50 hover:-translate-y-1">
                    <div class="px-8 py-10">
                        {{ $slot }}
                    </div>
                    
                    <!-- Decorative bottom bar -->
                    <div class="h-1 bg-gradient-to-r from-slate-600 via-gray-700 to-slate-600"></div>
                </div>
                
                <!-- Back to Home Link -->
                <div class="text-center mt-6">
                    <a href="/" class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors group">
                        <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
