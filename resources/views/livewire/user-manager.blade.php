<div>
    <style>[x-cloak] { display: none !important; }</style>
    
    {{-- Header Modernizado --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-2xl flex items-center justify-center transform hover:scale-105 transition-transform">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Gestión de Usuarios</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">Administra los usuarios del sistema y sus roles</p>
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
            {{-- SECCIÓN 1 - FORMULARIO --}}
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-blue-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($editingId)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">
                                @if($editingId)
                                    Editar Usuario
                                @else
                                    Nuevo Usuario
                                @endif
                            </h3>
                            <p class="text-xs text-blue-100">
                                @if($editingId)
                                    Actualiza la información del usuario
                                @else
                                    Agrega un nuevo usuario al sistema
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nombre Completo
                            </label>
                            <input type="text" 
                                   id="name"
                                   wire:model="name" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="Ej: Juan Pérez">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Correo Electrónico
                            </label>
                            <input type="email" 
                                   id="email"
                                   wire:model="email" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="ejemplo@correo.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role_id" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Rol del Usuario
                            </label>
                            <select id="role_id"
                                    wire:model="role_id" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold @error('role_id') border-red-500 ring-2 ring-red-200 @enderror">
                                <option value="">Seleccionar rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Contraseña
                                @if($editingId)
                                    <span class="text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">(dejar vacío para no cambiar)</span>
                                @endif
                            </label>
                            <input type="password" 
                                   id="password"
                                   wire:model="password" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="••••••••">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Confirmar Contraseña
                            </label>
                            <input type="password" 
                                   id="password_confirmation"
                                   wire:model="password_confirmation" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t-2 border-blue-100">
                        @if($editingId)
                            <button type="button" 
                                    wire:click="resetForm"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:ring-4 focus:ring-gray-200 transition-all shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                        @endif
                        <button type="submit" 
                                style="background: linear-gradient(135deg, #2563EB 0%, #4F46E5 100%) !important;"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" wire:loading>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove>{{ $editingId ? 'Actualizar Usuario' : 'Guardar Usuario' }}</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN 2 - LISTA DE USUARIOS --}}
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-blue-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Listado de Usuarios</h3>
                            <p class="text-xs text-blue-100">Todos los usuarios registrados en el sistema</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    {{-- Search --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar Usuario
                        </label>
                        <input type="text" 
                               wire:model.live="search"
                               placeholder="Buscar por nombre o email..."
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium">
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-xl border-2 border-blue-100">
                        <table class="min-w-full divide-y-2 divide-blue-100">
                            <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Usuario
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                            Email
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            Rol
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Registro
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                            </svg>
                                            Acciones
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-black text-lg shadow-md">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-gray-900">{{ $user->name }}</div>
                                                    @if($user->id === auth()->id())
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Tú
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-600 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $roleName = $user->getRoleNames()->first();
                                                $badgeColor = match($roleName) {
                                                    'Admin' => 'from-purple-500 to-pink-500',
                                                    'Supervisor' => 'from-blue-500 to-cyan-500',
                                                    'Cajero' => 'from-green-500 to-emerald-500',
                                                    default => 'from-gray-400 to-slate-400',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r {{ $badgeColor }} shadow-md">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                {{ $roleName ?? 'Sin rol' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700">{{ $user->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <button wire:click="edit({{ $user->id }})" 
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg font-bold text-xs hover:from-yellow-600 hover:to-amber-600 focus:ring-4 focus:ring-yellow-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                </button>
                                                @if($user->id !== auth()->id())
                                                    <button onclick="confirmDelete({{ $user->id }})" 
                                                            class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg font-bold text-xs hover:from-red-600 hover:to-pink-600 focus:ring-4 focus:ring-red-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                @else
                                                    <button disabled
                                                            class="inline-flex items-center gap-1 px-3 py-2 bg-gray-200 text-gray-400 rounded-lg font-bold text-xs cursor-not-allowed shadow-sm"
                                                            title="No puedes eliminar tu propia cuenta">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-black text-gray-900 mb-3">No se encontraron usuarios</h3>
                                            <p class="text-gray-600 font-medium">Intenta con otro término de búsqueda</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6 px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-t-2 border-blue-100 rounded-b-xl">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Función para confirmar eliminación de usuario
    function confirmDelete(userId) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.delete(userId);
            }
        });
    }

    // Escuchar evento de Livewire cuando se guarda exitosamente
    $wire.on('user-saved', (event) => {
        const message = event.message || 'Usuario guardado exitosamente';
        const isEdit = event.isEdit || false;
        
        Swal.fire({
            title: isEdit ? '¡Actualizado!' : '¡Guardado!',
            text: message,
            icon: 'success',
            confirmButtonColor: '#10B981',
            timer: 2000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut'
            }
        });
    });

    // Escuchar evento cuando se elimina
    $wire.on('user-deleted', () => {
        Swal.fire({
            title: '¡Eliminado!',
            text: 'El usuario ha sido eliminado exitosamente',
            icon: 'success',
            confirmButtonColor: '#10B981',
            timer: 2000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            }
        });
    });

    // Escuchar evento de error
    $wire.on('user-error', (event) => {
        const message = event.message || 'Ocurrió un error';
        
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#EF4444',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });
</script>
@endscript
