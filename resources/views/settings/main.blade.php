<x-app-layout>
    <style>[x-cloak] { display: none !important; }</style>
    
    {{-- Header Modernizado --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-2xl flex items-center justify-center transform hover:scale-105 transition-transform">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Centro de Configuración</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">Personaliza tu sistema según las necesidades de tu negocio</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:ring-4 focus:ring-gray-200 transition-all shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Dashboard
            </a>
        </div>

        <div class="space-y-6">
        <div class="space-y-6">
            <!-- Configuration Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Configuración del Negocio -->
                <a href="{{ route('settings.business') }}" 
                   class="group block bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-purple-100 hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-3xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-2xl font-black text-gray-900 mb-1">Mi Negocio</h3>
                                <p class="text-sm font-semibold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Personalización general</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2.5 text-sm mb-6">
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Nombre y logo del negocio</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Datos de contacto y fiscales</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Colores y branding</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Moneda y zona horaria</span>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t-2 border-purple-100">
                            <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold shadow-lg group-hover:shadow-xl transition-all">
                                <span>Configurar ahora</span>
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Configuración de Tickets -->
                <a href="{{ route('settings.tickets') }}" 
                   class="group block bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-blue-100 hover:border-blue-300 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-3xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-2xl font-black text-gray-900 mb-1">Tickets de Venta</h3>
                                <p class="text-sm font-semibold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">Formato de impresión</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2.5 text-sm mb-6">
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Encabezado y pie de página</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Numeración de recibos</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Campos a mostrar en ticket</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Vista previa de impresión</span>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t-2 border-blue-100">
                            <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-bold shadow-lg group-hover:shadow-xl transition-all">
                                <span>Configurar ahora</span>
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

            </div>

            <!-- Info Box Mejorado -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-gray-900 mb-2 flex items-center gap-2">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                            💡 Recomendación para comenzar
                        </h3>
                        <div class="space-y-3">
                            <p class="text-gray-700 font-medium leading-relaxed">
                                Para sacar el máximo provecho de tu sistema POS, te recomendamos seguir este orden:
                            </p>
                            <div class="bg-white/60 rounded-xl p-4 space-y-2">
                                <div class="flex items-start gap-3">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold flex-shrink-0">1</span>
                                    <div>
                                        <p class="font-bold text-gray-900">Configura "Mi Negocio"</p>
                                        <p class="text-sm text-gray-600">Establece el nombre, logo, datos fiscales y colores de tu marca</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-bold flex-shrink-0">2</span>
                                    <div>
                                        <p class="font-bold text-gray-900">Personaliza los "Tickets de Venta"</p>
                                        <p class="text-sm text-gray-600">Ajusta el formato de impresión según tus preferencias</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 p-3 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-amber-800">
                                    Los cambios se aplicarán <span class="underline">inmediatamente</span> en todo el sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
