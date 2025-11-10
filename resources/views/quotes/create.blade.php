<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nueva Cotización
            </h2>
            <a href="{{ route('quotes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Seleccionar Productos</h3>
                            
                            <!-- Búsqueda de productos -->
                            <div class="mb-4">
                                <input type="text" id="productSearch" 
                                       placeholder="Buscar producto por nombre o código..." 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Grid de productos -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-96 overflow-y-auto" id="productsGrid">
                                @foreach($products as $product)
                                <button type="button" 
                                        class="product-card p-3 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition text-left"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        onclick="addToQuote(this)">
                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Stock: {{ $product->stock }}</div>
                                    <div class="text-sm font-bold text-indigo-600 mt-1">${{ number_format($product->price, 0) }}</div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Detalles de Cotización y Carrito -->
                    <div>
                        <!-- Detalles de la cotización -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Cliente (Opcional)</label>
                                        <button type="button" onclick="openCustomerModal()" 
                                                class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Nuevo Cliente
                                        </button>
                                    </div>
                                    <select name="customer_id" id="customerSelect" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Sin cliente</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Válida Hasta (Opcional)</label>
                                    <input type="date" name="valid_until" 
                                           min="{{ now()->addDay()->format('Y-m-d') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas/Observaciones</label>
                                    <textarea name="notes" rows="3" 
                                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Condiciones, descuentos especiales, etc."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Carrito -->
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Items de Cotización</h3>
                            
                            <div id="quoteItems" class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                <p class="text-sm text-gray-500 text-center py-4">No hay items agregados</p>
                            </div>

                            <!-- Resumen -->
                            <div class="border-t-2 border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-semibold" id="subtotalDisplay">$0</span>
                                </div>
                                @if(setting('tax_enabled', false))
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">IVA ({{ setting('tax_rate', 19) }}%):</span>
                                    <span class="font-semibold" id="taxDisplay">$0</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span>TOTAL:</span>
                                    <span class="text-indigo-600" id="totalDisplay">$0</span>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="mt-6 space-y-2">
                                <button type="submit" id="submitBtn"
                                        class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
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
