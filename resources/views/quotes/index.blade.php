<x-app-layout><x-app-layout>

    <!-- Estilo para Alpine.js x-cloak -->    <x-slot name="header">

    <style>        <div class="flex justify-between items-center">

        [x-cloak] {             <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            display: none !important;                 Cotizaciones

        }            </h2>

    </style>            @can('quotes.create')

                <a href="{{ route('quotes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">

    <x-slot name="header">                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        <div class="flex items-center justify-between">                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>

            <div class="flex items-center gap-3">                </svg>

                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">                Nueva Cotización

                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">            </a>

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>            @endcan

                    </svg>        </div>

                </div>    </x-slot>

                <div>

                    <h2 class="font-bold text-xl text-gray-800">Cotizaciones</h2>    <div class="py-8">

                    <p class="text-xs text-gray-500">Gestiona presupuestos y propuestas</p>        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                </div>            @if(session('success'))

            </div>                <script>

            <div class="flex items-center gap-3">                    document.addEventListener('DOMContentLoaded', function() {

                @can('quotes.create')                        Swal.fire({

                <a href="{{ route('quotes.create') }}"                             icon: 'success',

                   style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"                            title: '¡Éxito!',

                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">                            text: '{{ session("success") }}',

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            showConfirmButton: false,

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>                            timer: 2000,

                    </svg>                            timerProgressBar: true,

                    <span class="hidden sm:inline">Nueva Cotización</span>                            showClass: {

                    <span class="sm:hidden">Nueva</span>                                popup: 'animate__animated animate__fadeInDown'

                </a>                            },

                @endcan                            hideClass: {

            </div>                                popup: 'animate__animated animate__fadeOutUp'

        </div>                            }

    </x-slot>                        });

                    });

    @if(session('success'))                </script>

        <script>            @endif

            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({            @if(session('error'))

                    icon: 'success',                <script>

                    title: '¡Éxito!',                    document.addEventListener('DOMContentLoaded', function() {

                    text: '{{ session("success") }}',                        Swal.fire({

                    showConfirmButton: false,                            icon: 'error',

                    timer: 2000,                            title: 'Error',

                    timerProgressBar: true,                            text: '{{ session("error") }}',

                    showClass: {                            confirmButtonColor: '#4F46E5',

                        popup: 'animate__animated animate__fadeInDown'                            showClass: {

                    },                                popup: 'animate__animated animate__shakeX'

                    hideClass: {                            }

                        popup: 'animate__animated animate__fadeOutUp'                        });

                    }                    });

                });                </script>

            });            @endif

        </script>

    @endif            <!-- Filtros -->

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">

    @if(session('error'))                <form method="GET" action="{{ route('quotes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <script>                    <div>

            document.addEventListener('DOMContentLoaded', function() {                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>

                Swal.fire({                        <input type="text" name="search" value="{{ request('search') }}" 

                    icon: 'error',                               placeholder="Número o cliente..." 

                    title: 'Error',                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    text: '{{ session("error") }}',                    </div>

                    confirmButtonColor: '#4F46E5',                    

                    showClass: {                    <div>

                        popup: 'animate__animated animate__shakeX'                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>

                    }                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                });                            <option value="">Todos</option>

            });                            <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>

        </script>                            <option value="aprobada" {{ request('status') === 'aprobada' ? 'selected' : '' }}>Aprobada</option>

    @endif                            <option value="rechazada" {{ request('status') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>

                            <option value="convertida" {{ request('status') === 'convertida' ? 'selected' : '' }}>Convertida</option>

    <div class="py-4 sm:py-8">                        </select>

        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-6">                    </div>

                    

            <!-- Filtros -->                    <div class="flex items-end gap-2">

            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">

                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">                            Filtrar

                    <div class="flex items-center gap-3 text-white">                        </button>

                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">                        <a href="{{ route('quotes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            Limpiar

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>                        </a>

                            </svg>                    </div>

                        </div>                </form>

                        <div>            </div>

                            <h3 class="text-lg font-bold">Filtros de Búsqueda</h3>

                            <p class="text-xs text-indigo-100">Encuentra cotizaciones específicas</p>            <!-- Tabla de Cotizaciones -->

                        </div>            <div class="bg-white rounded-lg shadow-sm overflow-hidden">

                    </div>                <table class="min-w-full divide-y divide-gray-200">

                </div>                    <thead class="bg-gray-50">

                <div class="p-6">                        <tr>

                    <form method="GET" action="{{ route('quotes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>

                        <!-- Búsqueda -->                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>

                        <div class="md:col-span-2">                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>

                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Válida Hasta</th>

                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>

                                </svg>                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>

                                Buscar                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>

                            </label>                        </tr>

                            <input type="text"                     </thead>

                                   name="search"                     <tbody class="bg-white divide-y divide-gray-200">

                                   value="{{ request('search') }}"                        @forelse($quotes as $quote)

                                   placeholder="Número o cliente..."                         <tr class="hover:bg-gray-50 transition">

                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium">                            <td class="px-6 py-4 whitespace-nowrap">

                        </div>                                <span class="text-sm font-bold text-indigo-600">{{ $quote->quote_number }}</span>

                            </td>

                        <!-- Estado -->                            <td class="px-6 py-4 whitespace-nowrap">

                        <div>                                <span class="text-sm text-gray-900">{{ $quote->customer->name ?? 'Sin cliente' }}</span>

                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">                            </td>

                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>                                {{ $quote->created_at->format('d/m/Y') }}

                                </svg>                            </td>

                                Estado                            <td class="px-6 py-4 whitespace-nowrap text-sm">

                            </label>                                @if($quote->valid_until)

                            <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">                                    <span class="{{ $quote->valid_until && $quote->isExpired() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">

                                <option value="">Todos</option>                                        {{ $quote->valid_until->format('d/m/Y') }}

                                <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>                                        @if($quote->isExpired())

                                <option value="aprobada" {{ request('status') === 'aprobada' ? 'selected' : '' }}>Aprobada</option>                                            <span class="text-xs">(Vencida)</span>

                                <option value="rechazada" {{ request('status') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>                                        @endif

                                <option value="convertida" {{ request('status') === 'convertida' ? 'selected' : '' }}>Convertida</option>                                    </span>

                            </select>                                @else

                        </div>                                    <span class="text-gray-400">-</span>

                                @endif

                        <!-- Botones -->                            </td>

                        <div class="flex items-end gap-2">                            <td class="px-6 py-4 whitespace-nowrap">

                            <button type="submit"                                 <span class="text-sm font-semibold text-gray-900">${{ number_format($quote->total, 0) }}</span>

                                    style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"                            </td>

                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-xl font-black hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">                            <td class="px-6 py-4 whitespace-nowrap">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $quote->getStatusBadgeClass() }}">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>                                    {{ $quote->getStatusLabel() }}

                                </svg>                                </span>

                                <span class="hidden sm:inline">Filtrar</span>                            </td>

                            </button>                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                            <a href="{{ route('quotes.index') }}"                                 {{ $quote->items->count() }} item(s)

                               class="px-4 py-4 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">                            </td>

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>                                <div class="flex justify-end gap-2">

                                </svg>                                    <a href="{{ route('quotes.show', $quote) }}" 

                            </a>                                       class="text-indigo-600 hover:text-indigo-900" title="Ver">

                        </div>                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    </form>                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>

                </div>                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>

            </div>                                        </svg>

                                    </a>

            <!-- Tabla de Cotizaciones -->                                    

            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">                                    @can('quotes.edit')

                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">                                    @if($quote->status === 'pendiente')

                    <div class="flex items-center gap-3 text-white">                                    <a href="{{ route('quotes.edit', $quote) }}" 

                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">                                       class="text-blue-600 hover:text-blue-900" title="Editar">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>

                            </svg>                                        </svg>

                        </div>                                    </a>

                        <div>                                    @endif

                            <h3 class="text-lg font-bold">Registro de Cotizaciones</h3>                                    @endcan

                            <p class="text-xs text-indigo-100">Historial completo de presupuestos</p>                                    

                        </div>                                    <a href="{{ route('quotes.print', $quote) }}" 

                    </div>                                       class="text-green-600 hover:text-green-900" title="Imprimir" target="_blank">

                </div>                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>

                @if($quotes->count() > 0)                                        </svg>

                    <div class="overflow-x-auto">                                    </a>

                        <table class="min-w-full divide-y-2 divide-indigo-100">                                </div>

                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">                            </td>

                                <tr>                        </tr>

                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">                        @empty

                                        <div class="flex items-center gap-2">                        <tr>

                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            </svg>                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>

                                            Número                                </svg>

                                        </div>                                <p class="mt-2">No se encontraron cotizaciones</p>

                                    </th>                            </td>

                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">                        </tr>

                                        <div class="flex items-center gap-2">                        @endforelse

                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">                    </tbody>

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>                </table>

                                            </svg>

                                            Cliente                <!-- Paginación -->

                                        </div>                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">

                                    </th>                    {{ $quotes->links() }}

                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">                </div>

                                        <div class="flex items-center gap-2">            </div>

                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">        </div>

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>    </div>

                                            </svg></x-app-layout>

                                            Fecha
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Válida Hasta
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Total
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Estado
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                            </svg>
                                            Acciones
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($quotes as $quote)
                                <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 group">
                                    <!-- Número -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                                            {{ $quote->quote_number }}
                                        </span>
                                    </td>

                                    <!-- Cliente -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                                {{ substr($quote->customer->name ?? 'S', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $quote->customer->name ?? 'Sin cliente' }}</span>
                                        </div>
                                    </td>

                                    <!-- Fecha -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-bold text-gray-900">{{ $quote->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </td>

                                    <!-- Válida Hasta -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($quote->valid_until)
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 {{ $quote->valid_until && $quote->isExpired() ? 'text-red-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="{{ $quote->valid_until && $quote->isExpired() ? 'text-red-600 font-bold' : 'text-gray-600 font-semibold' }}">
                                                    {{ $quote->valid_until->format('d/m/Y') }}
                                                    @if($quote->isExpired())
                                                        <span class="text-xs ml-1">(Vencida)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 font-medium">Sin límite</span>
                                        @endif
                                    </td>

                                    <!-- Total -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-base font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                                            ${{ number_format($quote->total, 0) }}
                                        </span>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold text-xs text-white shadow-md {{ $quote->getStatusBadgeClass() }}">
                                            @if($quote->status === 'pendiente')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($quote->status === 'convertida')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($quote->status === 'aprobada')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            @endif
                                            {{ $quote->getStatusLabel() }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            <a href="{{ route('quotes.show', $quote) }}" 
                                               style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold text-xs hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span class="hidden lg:inline">Ver</span>
                                            </a>

                                            @can('quotes.edit')
                                            @if($quote->status === 'pendiente')
                                            <a href="{{ route('quotes.edit', $quote) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-bold text-xs hover:from-blue-600 hover:to-cyan-600 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95"
                                               title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @endif
                                            @endcan

                                            <a href="{{ route('quotes.print', $quote) }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold text-xs hover:from-green-600 hover:to-emerald-600 focus:ring-4 focus:ring-green-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95"
                                               title="Imprimir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-t-2 border-indigo-100">
                        {{ $quotes->links() }}
                    </div>
                @else
                    <!-- Estado Vacío -->
                    <div class="text-center py-16 px-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">No hay cotizaciones registradas</h3>
                        <p class="text-gray-600 mb-6 font-medium">Comienza creando tu primera cotización</p>
                        @can('quotes.create')
                        <a href="{{ route('quotes.create') }}" 
                           style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nueva Cotización
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
