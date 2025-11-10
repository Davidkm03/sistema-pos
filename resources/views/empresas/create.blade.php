<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('admin.empresas.index') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900">Nueva Empresa</h2>
                </div>
                <p class="text-sm text-gray-600">Crea una nueva empresa en el sistema multi-tenant</p>
            </div>

            <!-- Formulario -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.empresas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div class="md:col-span-2">
                            <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2">
                                Nombre de la Empresa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   value="{{ old('nombre') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- RFC -->
                        <div>
                            <label for="rfc" class="block text-sm font-bold text-gray-700 mb-2">
                                RFC
                            </label>
                            <input type="text" 
                                   name="rfc" 
                                   id="rfc" 
                                   value="{{ old('rfc') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('rfc') border-red-500 @enderror">
                            @error('rfc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-bold text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" 
                                   name="telefono" 
                                   id="telefono" 
                                   value="{{ old('telefono') }}"
                                   placeholder="+52 55-1234-5678"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   placeholder="contacto@empresa.com"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sitio Web -->
                        <div>
                            <label for="sitio_web" class="block text-sm font-bold text-gray-700 mb-2">
                                Sitio Web
                            </label>
                            <input type="url" 
                                   name="sitio_web" 
                                   id="sitio_web" 
                                   value="{{ old('sitio_web') }}"
                                   placeholder="https://www.empresa.com"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('sitio_web') border-red-500 @enderror">
                            @error('sitio_web')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label for="direccion" class="block text-sm font-bold text-gray-700 mb-2">
                                Dirección
                            </label>
                            <textarea name="direccion" 
                                      id="direccion" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('direccion') border-red-500 @enderror"
                            >{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Moneda -->
                        <div>
                            <label for="moneda" class="block text-sm font-bold text-gray-700 mb-2">
                                Moneda <span class="text-red-500">*</span>
                            </label>
                            <select name="moneda" 
                                    id="moneda" 
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('moneda') border-red-500 @enderror">
                                <option value="MXN" {{ old('moneda', 'MXN') == 'MXN' ? 'selected' : '' }}>MXN - Peso Mexicano</option>
                                <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD - Dólar Americano</option>
                                <option value="EUR" {{ old('moneda') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="COP" {{ old('moneda') == 'COP' ? 'selected' : '' }}>COP - Peso Colombiano</option>
                            </select>
                            @error('moneda')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- IVA -->
                        <div>
                            <label for="iva_porcentaje" class="block text-sm font-bold text-gray-700 mb-2">
                                IVA (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="iva_porcentaje" 
                                   id="iva_porcentaje" 
                                   value="{{ old('iva_porcentaje', '16.00') }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-150 @error('iva_porcentaje') border-red-500 @enderror">
                            @error('iva_porcentaje')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="md:col-span-2">
                            <label for="logo" class="block text-sm font-bold text-gray-700 mb-2">
                                Logo de la Empresa
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG o GIF (máx. 2MB)</p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="activo" 
                                       value="1"
                                       {{ old('activo', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-sm font-bold text-gray-700">Empresa Activa</span>
                            </label>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('admin.empresas.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition-all duration-150">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
