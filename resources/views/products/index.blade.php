<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Componente Livewire de Gestión de Productos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-gray-600">Administra el inventario y catálogo de productos de tu negocio</p>
                    </div>
                    @livewire('product-manager')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
