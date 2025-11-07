<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Permisos del Rol: ') . ucfirst($role->name) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Warning for Super Admin -->
            @if($role->name === 'super-admin')
                <div class="mb-4 bg-purple-100 border border-purple-400 text-purple-700 px-4 py-3 rounded relative flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>El rol de Super Admin tiene todos los permisos y no puede ser modificado.</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Role Information -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                                    <div class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ ucfirst($role->name) }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Permisos Actuales</label>
                                    <div class="mt-1 text-lg font-semibold text-indigo-600">
                                        {{ $role->permissions->count() }} de {{ $permissions->flatten()->count() }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Usuarios Asignados</label>
                                    <div class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ $role->users()->count() }} usuarios
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Grid -->
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Permisos Disponibles</h3>
                                @if($role->name !== 'super-admin')
                                    <button 
                                        type="button"
                                        onclick="toggleAllPermissions()"
                                        class="text-sm text-indigo-600 hover:text-indigo-800"
                                    >
                                        Seleccionar/Deseleccionar Todo
                                    </button>
                                @endif
                            </div>

                            @foreach($permissions as $module => $modulePermissions)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-md font-semibold text-gray-800 capitalize flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            {{ ucfirst($module) }}
                                        </h4>
                                        @if($role->name !== 'super-admin')
                                            <button 
                                                type="button"
                                                onclick="toggleModulePermissions('{{ $module }}')"
                                                class="text-xs text-indigo-600 hover:text-indigo-800"
                                            >
                                                Seleccionar Todos
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                        @foreach($modulePermissions as $permission)
                                            <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer">
                                                <input 
                                                    type="checkbox" 
                                                    name="permissions[]" 
                                                    value="{{ $permission->id }}"
                                                    data-module="{{ $module }}"
                                                    @if($role->permissions->contains($permission->id)) checked @endif
                                                    @if($role->name === 'super-admin') disabled @endif
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                >
                                                <span class="ml-2 text-sm text-gray-700">
                                                    {{ str_replace('-', ' ', ucfirst(explode('-', $permission->name)[0])) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a 
                                href="{{ route('admin.roles.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                            
                            @if($role->name !== 'super-admin')
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Guardar Cambios
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAllPermissions() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]:not([disabled])');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        function toggleModulePermissions(module) {
            const checkboxes = document.querySelectorAll(`input[data-module="${module}"]:not([disabled])`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }
    </script>
</x-app-layout>
