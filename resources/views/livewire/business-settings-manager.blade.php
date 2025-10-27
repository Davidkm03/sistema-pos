<div>
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">⚙️ Configuración del Negocio</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- FORMULARIO -->
                    <div>
                        <form wire:submit.prevent="save" class="space-y-6">
                            
                            <!-- Nombre del Negocio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Negocio *
                                </label>
                                <input type="text" wire:model="business_name" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Mi Tienda">
                                @error('business_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Logo del Negocio
                                </label>
                                
                                @if ($existing_logo && !$business_logo)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($existing_logo) }}" alt="Logo actual" class="h-20 w-auto rounded">
                                        <button type="button" wire:click="removeLogo" 
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            Eliminar logo
                                        </button>
                                    </div>
                                @endif

                                @if ($business_logo)
                                    <div class="mb-2">
                                        <img src="{{ $business_logo->temporaryUrl() }}" alt="Preview" class="h-20 w-auto rounded">
                                        <button type="button" wire:click="$set('business_logo', null)" 
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            Cancelar
                                        </button>
                                    </div>
                                @endif

                                <input type="file" wire:model="business_logo" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG. Máximo 2MB</p>
                                @error('business_logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Dirección -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección
                                </label>
                                <textarea wire:model="business_address" rows="2"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Calle Principal #123, Colonia Centro"></textarea>
                                @error('business_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <input type="text" wire:model="business_phone"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="+52 55 1234 5678">
                                @error('business_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" wire:model="business_email"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="contacto@mitienda.com">
                                @error('business_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- RFC/Tax ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    RFC / NIT / Tax ID
                                </label>
                                <input type="text" wire:model="business_tax_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="ABC123456XYZ">
                                @error('business_tax_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Mensaje Footer Ticket -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mensaje al pie del ticket
                                </label>
                                <textarea wire:model="receipt_footer" rows="2"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="¡Gracias por su compra!"></textarea>
                                @error('receipt_footer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Color Primario -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Color Primario *
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="color" wire:model.live="primary_color"
                                            class="h-10 w-20 rounded border-gray-300">
                                        <input type="text" wire:model="primary_color"
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="#3B82F6">
                                    </div>
                                    @error('primary_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Color Secundario -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Color Secundario *
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="color" wire:model.live="secondary_color"
                                            class="h-10 w-20 rounded border-gray-300">
                                        <input type="text" wire:model="secondary_color"
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="#10B981">
                                    </div>
                                    @error('secondary_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Moneda -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Moneda *
                                </label>
                                <select wire:model="currency"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Zona Horaria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Zona Horaria *
                                </label>
                                <select wire:model="timezone"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach($timezones as $tz => $name)
                                        <option value="{{ $tz }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('timezone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- SECCIÓN TRIBUTARIA -->
                            <div class="border-t-2 border-gray-200 pt-6 mt-6">
                                <h3 class="text-lg font-semibold mb-4">⚖️ Configuración Tributaria</h3>

                                <!-- Activar IVA -->
                                <div class="mb-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="tax_enabled" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                                        <span class="font-medium">Activar IVA</span>
                                    </label>
                                </div>

                                @if($tax_enabled)
                                    <!-- Nombre del impuesto -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Impuesto</label>
                                        <input type="text" wire:model="tax_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <!-- Tasa general -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tasa General de IVA (%)</label>
                                        <input type="number" step="0.01" wire:model="tax_rate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <!-- Precios incluyen IVA -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">¿Los precios incluyen IVA?</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" wire:model="tax_included_in_price" value="1" class="mr-2">
                                                <span>Sí, los precios YA incluyen IVA</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" wire:model="tax_included_in_price" value="0" class="mr-2">
                                                <span>No, el IVA se suma al precio</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Retenciones -->
                                    <div class="border-t pt-4 mt-4">
                                        <label class="flex items-center cursor-pointer mb-4">
                                            <input type="checkbox" wire:model.live="retention_enabled" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                                            <span class="font-medium">Activar Retenciones en la Fuente</span>
                                        </label>

                                        @if($retention_enabled)
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tasa de Retención (%)</label>
                                                    <input type="number" step="0.01" wire:model="retention_rate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Aplicar desde ($)</label>
                                                    <input type="number" wire:model="applies_retention_from" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Requisitos -->
                                    <div class="border-t pt-4 mt-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="tax_id_required" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                                            <span class="font-medium">Exigir NIT del cliente para vender</span>
                                        </label>
                                    </div>

                                    <!-- Régimen del negocio -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Régimen Tributario del Negocio</label>
                                        <select wire:model="business_tax_regime" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="simplified">Régimen Simplificado</option>
                                            <option value="common">Régimen Común</option>
                                        </select>
                                    </div>

                                    <!-- Ayuda -->
                                    <div class="bg-blue-50 border border-blue-200 rounded p-4 mt-4">
                                        <h3 class="font-semibold mb-2">📘 Información</h3>
                                        <ul class="text-sm space-y-1">
                                            <li><strong>Exento:</strong> Productos con IVA 0% pero con derecho a descontar IVA</li>
                                            <li><strong>Excluido:</strong> Productos con IVA 0% SIN derecho a descontar IVA</li>
                                            <li><strong>Retención:</strong> Se aplica a ventas mayores al monto configurado con clientes en régimen común</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- CONFIGURACIÓN DE DOCUMENTOS -->
                            <div class="border-t pt-6 mt-6">
                                <h3 class="text-lg font-semibold mb-4">📄 Configuración de Documentos</h3>

                                <!-- Tipo de Documento -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de documento
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{ 'border-blue-500 bg-blue-50': $wire.billing_type === 'none' }">
                                            <input type="radio" wire:model.live="billing_type" value="none" class="mr-3">
                                            <div>
                                                <div class="font-medium">No generar documentos</div>
                                                <div class="text-xs text-gray-600">Solo registra ventas sin documento</div>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{ 'border-blue-500 bg-blue-50': $wire.billing_type === 'simple_receipt' }">
                                            <input type="radio" wire:model.live="billing_type" value="simple_receipt" class="mr-3">
                                            <div>
                                                <div class="font-medium">Recibo de venta ⭐ Recomendado</div>
                                                <div class="text-xs text-gray-600">Para negocios NO obligados a facturar</div>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{ 'border-blue-500 bg-blue-50': $wire.billing_type === 'invoice' }">
                                            <input type="radio" wire:model.live="billing_type" value="invoice" class="mr-3">
                                            <div>
                                                <div class="font-medium">Factura tradicional</div>
                                                <div class="text-xs text-gray-600">Con resolución DIAN</div>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{ 'border-blue-500 bg-blue-50': $wire.billing_type === 'electronic_invoice' }">
                                            <input type="radio" wire:model.live="billing_type" value="electronic_invoice" class="mr-3">
                                            <div>
                                                <div class="font-medium">Factura electrónica</div>
                                                <div class="text-xs text-gray-600">Integración con proveedor FE</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Configuración de Recibo Simple -->
                                @if($billing_type === 'simple_receipt')
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                        <h4 class="font-medium text-green-800 mb-3">Configuración de Recibo de Venta</h4>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Prefijo</label>
                                                <input type="text" wire:model="receipt_prefix" maxlength="10" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="RV">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Número inicial</label>
                                                <input type="number" wire:model="receipt_counter" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="1">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Encabezado personalizado</label>
                                            <textarea wire:model="receipt_header" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Texto adicional que aparecerá en el encabezado del recibo"></textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model="show_tax_disclaimer" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                                                <span class="text-sm">Mostrar disclaimer "Documento no válido como factura"</span>
                                            </label>
                                        </div>

                                        <div class="bg-white border border-green-300 rounded p-3 text-sm">
                                            <p class="font-medium mb-1">✅ Ventajas:</p>
                                            <ul class="list-disc list-inside text-xs space-y-1 text-gray-600">
                                                <li>No necesita trámites con DIAN</li>
                                                <li>Numeración libre</li>
                                                <li>Ideal para régimen simplificado</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <!-- Configuración de Facturación con DIAN -->
                                @if($billing_type === 'invoice')
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <h4 class="font-medium text-yellow-800 mb-3">Configuración de Factura DIAN</h4>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Prefijo de factura</label>
                                            <input type="text" wire:model="invoice_prefix" maxlength="10" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="FV">
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de resolución DIAN *</label>
                                            <input type="text" wire:model="dian_resolution" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="18760000001234">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha resolución *</label>
                                                <input type="date" wire:model="resolution_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha vencimiento *</label>
                                                <input type="date" wire:model="resolution_expiry" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Rango desde *</label>
                                                <input type="number" wire:model="range_from" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="1">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Rango hasta *</label>
                                                <input type="number" wire:model="range_to" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="5000">
                                            </div>
                                        </div>

                                        <div class="bg-white border border-yellow-300 rounded p-3 text-sm">
                                            <p class="font-medium mb-1 text-yellow-800">⚠️ Importante:</p>
                                            <ul class="list-disc list-inside text-xs space-y-1 text-gray-600">
                                                <li>Todos los campos son obligatorios</li>
                                                <li>El sistema alertará al alcanzar 80% del rango</li>
                                                <li>Debe solicitar nueva resolución antes de agotar</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                @if($billing_type === 'electronic_invoice')
                                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                                        <p class="text-purple-800 font-medium">🚧 Funcionalidad en desarrollo</p>
                                        <p class="text-sm text-gray-600 mt-2">La facturación electrónica estará disponible próximamente</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Botón Guardar -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                    💾 Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- PREVIEW DEL TICKET -->
                    <div>
                        <div class="sticky top-6">
                            <h3 class="text-lg font-semibold mb-4">👁️ Vista Previa del Ticket</h3>
                            <div class="bg-white border-2 border-gray-200 rounded-lg p-6 shadow-lg font-mono text-sm">
                                <!-- Logo Preview -->
                                @if ($business_logo)
                                    <div class="text-center mb-4">
                                        <img src="{{ $business_logo->temporaryUrl() }}" alt="Logo" class="h-16 mx-auto">
                                    </div>
                                @elseif($existing_logo)
                                    <div class="text-center mb-4">
                                        <img src="{{ Storage::url($existing_logo) }}" alt="Logo" class="h-16 mx-auto">
                                    </div>
                                @endif

                                <!-- Header -->
                                <div class="text-center border-b-2 border-dashed border-gray-300 pb-3 mb-3">
                                    <h4 class="font-bold text-lg" style="color: {{ $primary_color }}">
                                        {{ $business_name ?: 'Mi Tienda' }}
                                    </h4>
                                    @if($business_address)
                                        <p class="text-xs text-gray-600 mt-1">{{ $business_address }}</p>
                                    @endif
                                    @if($business_phone)
                                        <p class="text-xs text-gray-600">Tel: {{ $business_phone }}</p>
                                    @endif
                                    @if($business_email)
                                        <p class="text-xs text-gray-600">{{ $business_email }}</p>
                                    @endif
                                    @if($business_tax_id)
                                        <p class="text-xs text-gray-600">RFC: {{ $business_tax_id }}</p>
                                    @endif
                                </div>

                                <!-- Ejemplo de productos -->
                                <div class="space-y-2 text-xs border-b-2 border-dashed border-gray-300 pb-3 mb-3">
                                    <div class="flex justify-between">
                                        <span>1x Producto Ejemplo</span>
                                        <span class="font-semibold">${{ number_format(100, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>2x Otro Producto</span>
                                        <span class="font-semibold">${{ number_format(200, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="text-right font-bold text-base mb-4" style="color: {{ $secondary_color }}">
                                    TOTAL: ${{ number_format(300, 2) }} {{ $currency }}
                                </div>

                                <!-- Footer -->
                                <div class="text-center text-xs text-gray-600 border-t-2 border-dashed border-gray-300 pt-3">
                                    <p>{{ $receipt_footer ?: '¡Gracias por su compra!' }}</p>
                                    <p class="mt-2">{{ now()->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Escuchar evento de notificación con SweetAlert2
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                console.log('Notify event received:', event);
                console.log('Event type:', typeof event);
                console.log('Is Array:', Array.isArray(event));

                // Extraer los datos dependiendo del formato
                let type, message;
                
                if (Array.isArray(event) && event.length > 0) {
                    // Formato array
                    const data = event[0];
                    type = data.type || 'success';
                    message = data.message || 'Operación exitosa';
                } else if (typeof event === 'object') {
                    // Formato objeto directo o parámetros nombrados
                    type = event.type || event[0] || 'success';
                    message = event.message || event[1] || 'Operación exitosa';
                } else {
                    // Fallback
                    type = 'success';
                    message = 'Operación exitosa';
                }

                console.log('Showing notification:', { type, message });

                // Crear Toast con SweetAlert2
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: type,
                    title: message
                });
            });
        });
    </script>
</div>
