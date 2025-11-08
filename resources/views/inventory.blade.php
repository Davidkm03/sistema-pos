<x-app-layout>
    <!-- Estilo para Alpine.js x-cloak -->
    <style>
        [x-cloak] { 
            display: none !important; 
        }
    </style>
    
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">
                        {{ __('Gestión de Inventario') }}
                    </h2>
                    <p class="text-xs text-gray-500">Controla movimientos y stock de productos</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-blue-200 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Componente Livewire de Gestión de Inventario -->
            <div class="bg-gradient-to-br from-white to-gray-50 overflow-hidden shadow-2xl sm:rounded-2xl border-2 border-blue-100">
                <div class="p-4 sm:p-6 lg:p-8">
                    <livewire:inventory-manager />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
