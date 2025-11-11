<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ setting('business_name', config('app.name', 'Laravel')) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Animate.css for SweetAlert2 animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <!-- Dynamic Color Styles -->
        <style>
            :root {
                --primary-color: {{ setting('primary_color', '#3B82F6') }};
                --secondary-color: {{ setting('secondary_color', '#10B981') }};
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Fixed Header with Notifications -->
            <div class="lg:ml-64 transition-all duration-300">
                <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 right-0 left-0 lg:left-64 z-40">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between h-16">
                            <!-- Page Title (if provided) -->
                            <div class="flex-1">
                                @isset($header)
                                    <div class="text-sm text-gray-600">
                                        {{ $header }}
                                    </div>
                                @endisset
                            </div>

                            <!-- User Actions -->
                            <div class="flex items-center space-x-4">
                                <!-- Notifications Dropdown -->
                                @livewire('notification-dropdown')
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content with Header Offset -->
                <div class="pt-16">
                    <!-- Page Content -->
                    <main>
                        {{ $slot ?? '' }}
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
        @livewireScripts

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

        <!-- Scripts adicionales de componentes -->
        @stack('scripts')
        </body>
</html>
