<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">Nueva Cotización</h2>
                    <p class="text-xs text-gray-500">Crea un nuevo presupuesto para tus clientes</p>
                </div>
            </div>
            <a href="{{ route('quotes.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Volver</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ session('error') }}',
                            confirmButtonColor: '#EF4444',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        });
                    });
                </script>
            @endif

            @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Errores de validación',
                            html: `
                                <ul class="text-left text-sm">
                                    @foreach($errors->all() as $error)
                                        <li class="text-red-600">• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            `,
                            confirmButtonColor: '#EF4444'
                        });
                    });
                </script>
            @endif

            <form action="{{ route('quotes.store') }}" method="POST" id="quoteForm">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Columna Izquierda: Productos -->
                    <div class="lg:col-span-2">
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                                <div class="flex items-center gap-3 text-white">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">Seleccionar Productos</h3>
                                        <p class="text-xs text-indigo-100">Agrega productos a la cotización</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <!-- Búsqueda de productos -->
                                <div class="mb-4">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" id="productSearch" 
                                               placeholder="Buscar producto por nombre o código..." 
                                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all">
                                    </div>
                                </div>

                                <!-- Grid de productos -->
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-96 overflow-y-auto" id="productsGrid">
                                    @foreach($products as $product)
                                    <button type="button" 
                                            class="product-card p-3 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg hover:scale-105 transition-all duration-200 text-left bg-gradient-to-br from-white to-gray-50"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}"
                                            onclick="addToQuote(this)">
                                        <div class="text-sm font-bold text-gray-900 truncate mb-1">{{ $product->name }}</div>
                                        <div class="flex items-center gap-1 text-xs text-gray-500 mb-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            Stock: {{ $product->stock }}
                                        </div>
                                        <div class="text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                                            ${{ number_format($product->price, 0) }}
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Detalles de Cotización y Carrito -->
                    <div>
                        <!-- Detalles de la cotización -->
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden mb-6">
                            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                                <div class="flex items-center gap-3 text-white">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">Detalles</h3>
                                        <p class="text-xs text-indigo-100">Información de la cotización</p>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-1">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Cliente (Opcional)
                                            </label>
                                            <button type="button" onclick="openCustomerModal()" 
                                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 hover:scale-105 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Nuevo
                                            </button>
                                        </div>
                                        <select name="customer_id" id="customerSelect" class="w-full rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 font-medium transition-all">
                                            <option value="">Sin cliente</option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Válida Hasta (Opcional)
                                        </label>
                                        <input type="date" name="valid_until" 
                                               min="{{ now()->addDay()->format('Y-m-d') }}"
                                               class="w-full rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 font-medium transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            Notas/Observaciones
                                        </label>
                                        <textarea name="notes" rows="3" 
                                                  class="w-full rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 font-medium transition-all"
                                                  placeholder="Condiciones, descuentos especiales, etc."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carrito -->
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden sticky top-6">
                            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                                <div class="flex items-center gap-3 text-white">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">Items de Cotización</h3>
                                        <p class="text-xs text-indigo-100">Productos agregados</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div id="quoteItems" class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium">No hay items agregados</p>
                                        <p class="text-xs text-gray-400 mt-1">Selecciona productos de la izquierda</p>
                                    </div>
                                </div>

                                <!-- Resumen -->
                                <div class="border-t-2 border-indigo-100 pt-4 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 font-semibold">Subtotal:</span>
                                        <span class="font-black text-gray-900" id="subtotalDisplay">$0</span>
                                    </div>
                                    @if(setting('tax_enabled', false))
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 font-semibold">IVA ({{ setting('tax_rate', 19) }}%):</span>
                                        <span class="font-black text-gray-900" id="taxDisplay">$0</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between text-lg font-black border-t-2 border-indigo-100 pt-3">
                                        <span class="text-gray-800">TOTAL:</span>
                                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600" id="totalDisplay">$0</span>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="mt-6 space-y-3">
                                    <button type="submit" id="submitBtn"
                                            style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"
                                            class="w-full px-4 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
                                        disabled>
                                    Guardar Cotización
                                </button>
                                <a href="{{ route('quotes.index') }}" 
                                   class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center rounded-lg font-semibold hover:bg-gray-300 transition">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let quoteItems = [];
        const taxEnabled = {{ setting('tax_enabled', false) ? 'true' : 'false' }};
        const taxRate = {{ setting('tax_rate', 19) / 100 }};

        function addToQuote(button) {
            const productId = button.dataset.id;
            const productName = button.dataset.name;
            const productPrice = parseFloat(button.dataset.price);

            // Buscar si ya existe
            const existing = quoteItems.find(item => item.product_id === productId);
            
            if (existing) {
                existing.quantity++;
                
                // Animación de feedback
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });
                
                Toast.fire({
                    icon: 'info',
                    title: `Cantidad actualizada: ${existing.quantity}`
                });
            } else {
                quoteItems.push({
                    product_id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
                
                // Animación de éxito
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });
                
                Toast.fire({
                    icon: 'success',
                    title: 'Producto agregado'
                });
            }

            updateQuoteDisplay();
        }

        function updateQuantity(index, newQuantity) {
            if (newQuantity <= 0) {
                Swal.fire({
                    title: '¿Eliminar producto?',
                    text: "Se eliminará este producto de la cotización",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        quoteItems.splice(index, 1);
                        updateQuoteDisplay();
                        
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        
                        Toast.fire({
                            icon: 'success',
                            title: 'Producto eliminado'
                        });
                    } else {
                        // Restaurar la cantidad
                        document.querySelectorAll('.quantity-input')[index].value = quoteItems[index].quantity;
                    }
                });
            } else {
                quoteItems[index].quantity = parseInt(newQuantity);
                updateQuoteDisplay();
            }
        }

        function removeItem(index) {
            const itemName = quoteItems[index].name;
            
            Swal.fire({
                title: '¿Eliminar producto?',
                text: `¿Deseas eliminar "${itemName}" de la cotización?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    quoteItems.splice(index, 1);
                    updateQuoteDisplay();
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Producto eliminado'
                    });
                }
            });
        }

        function updateQuoteDisplay() {
            const container = document.getElementById('quoteItems');
            const form = document.getElementById('quoteForm');
            const submitBtn = document.getElementById('submitBtn');

            // Limpiar campos ocultos anteriores
            document.querySelectorAll('.item-input').forEach(el => el.remove());

            if (quoteItems.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No hay items agregados</p>
                        <p class="text-xs text-gray-400">Selecciona productos del panel izquierdo</p>
                    </div>
                `;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                
                container.innerHTML = quoteItems.map((item, index) => `
                    <div class="flex items-center gap-2 p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-md transition">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">${item.name}</p>
                            <p class="text-xs text-gray-500">$${item.price.toLocaleString('es-CO')} c/u</p>
                            <p class="text-xs font-semibold text-indigo-600 mt-1">Subtotal: $${(item.price * item.quantity).toLocaleString('es-CO')}</p>
                        </div>
                        <div class="flex items-center gap-1 bg-white rounded-lg border border-gray-200 p-1">
                            <button type="button" onclick="updateQuantity(${index}, ${item.quantity - 1})"
                                    class="w-7 h-7 flex items-center justify-center bg-gray-100 hover:bg-red-100 text-gray-700 hover:text-red-600 rounded transition font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" value="${item.quantity}" min="1"
                                   onchange="updateQuantity(${index}, this.value)"
                                   class="quantity-input w-14 text-center border-0 bg-transparent py-1 text-sm font-semibold text-gray-900 focus:ring-0">
                            <button type="button" onclick="updateQuantity(${index}, ${item.quantity + 1})"
                                    class="w-7 h-7 flex items-center justify-center bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-600 rounded transition font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <button type="button" onclick="removeItem(${index})"
                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                `).join('');

                // Agregar campos ocultos al formulario
                quoteItems.forEach((item, index) => {
                    ['product_id', 'quantity', 'price'].forEach(field => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `items[${index}][${field}]`;
                        input.value = item[field];
                        input.className = 'item-input';
                        form.appendChild(input);
                    });
                });
            }

            // Actualizar totales
            const subtotal = quoteItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = taxEnabled ? Math.round(subtotal * taxRate) : 0;
            const total = subtotal + tax;

            document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toLocaleString('es-CO');
            if (taxEnabled) {
                document.getElementById('taxDisplay').textContent = '$' + tax.toLocaleString('es-CO');
            }
            document.getElementById('totalDisplay').textContent = '$' + total.toLocaleString('es-CO');
        }

        // Búsqueda de productos
        document.getElementById('productSearch').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const isVisible = name.includes(search);
                card.style.display = isVisible ? 'block' : 'none';
                if (isVisible) visibleCount++;
            });
            
            // Mensaje si no hay resultados
            const grid = document.getElementById('productsGrid');
            let noResults = grid.querySelector('.no-results');
            
            if (visibleCount === 0 && !noResults) {
                noResults = document.createElement('div');
                noResults.className = 'no-results col-span-full text-center py-8';
                noResults.innerHTML = `
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No se encontraron productos</p>
                    <p class="text-xs text-gray-400">Intenta con otro término de búsqueda</p>
                `;
                grid.appendChild(noResults);
            } else if (visibleCount > 0 && noResults) {
                noResults.remove();
            }
        });

        // Modal de nuevo cliente
        function openCustomerModal() {
            document.getElementById('customerModal').classList.remove('hidden');
            document.getElementById('customerForm').querySelector('input[name="name"]').focus();
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').classList.add('hidden');
            document.getElementById('customerForm').reset();
        }

        function saveCustomer() {
            const form = document.getElementById('customerForm');
            const formData = new FormData(form);

            // Validación básica
            if (!formData.get('name')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre del cliente es requerido',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }

            // Mostrar loading
            Swal.fire({
                title: 'Guardando cliente...',
                html: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('customers.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Agregar el nuevo cliente al select
                    const option = new Option(data.customer.name, data.customer.id, true, true);
                    document.getElementById('customerSelect').add(option);
                    closeCustomerModal();
                    
                    // Mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente creado!',
                        text: 'El cliente ha sido agregado exitosamente',
                        confirmButtonColor: '#10B981',
                        timer: 2500,
                        timerProgressBar: true,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al crear cliente',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al crear cliente. Por favor intenta de nuevo.',
                    confirmButtonColor: '#EF4444'
                });
            });
        }

        // Validación antes de enviar
        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            if (quoteItems.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Debes agregar al menos un producto a la cotización',
                    confirmButtonColor: '#F59E0B'
                });
                return false;
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('customerModal');
                if (!modal.classList.contains('hidden')) {
                    closeCustomerModal();
                }
            }
        });
    </script>
    @endpush

    <!-- Modal de Nuevo Cliente -->
    <div id="customerModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Nuevo Cliente</h3>
                <button type="button" onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="customerForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" name="phone" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de ID</label>
                        <select name="tax_id_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccionar</option>
                            <option value="CC">CC - Cédula</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">CE - Extranjería</option>
                            <option value="PAS">Pasaporte</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de ID</label>
                        <input type="text" name="tax_id" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="saveCustomer()" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                        Guardar Cliente
                    </button>
                    <button type="button" onclick="closeCustomerModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
