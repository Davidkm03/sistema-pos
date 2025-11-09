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
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
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
            } else {
                quoteItems.push({
                    product_id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
            }

            updateQuoteDisplay();
        }

        function updateQuantity(index, newQuantity) {
            if (newQuantity <= 0) {
                quoteItems.splice(index, 1);
            } else {
                quoteItems[index].quantity = parseInt(newQuantity);
            }
            updateQuoteDisplay();
        }

        function removeItem(index) {
            quoteItems.splice(index, 1);
            updateQuoteDisplay();
        }

        function updateQuoteDisplay() {
            const container = document.getElementById('quoteItems');
            const form = document.getElementById('quoteForm');
            const submitBtn = document.getElementById('submitBtn');

            // Limpiar campos ocultos anteriores
            document.querySelectorAll('.item-input').forEach(el => el.remove());

            if (quoteItems.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No hay items agregados</p>';
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
                container.innerHTML = quoteItems.map((item, index) => `
                    <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">${item.name}</p>
                            <p class="text-xs text-gray-500">$${item.price.toLocaleString()} c/u</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="updateQuantity(${index}, ${item.quantity - 1})"
                                    class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">-</button>
                            <input type="number" value="${item.quantity}" min="1"
                                   onchange="updateQuantity(${index}, this.value)"
                                   class="w-12 text-center border-gray-300 rounded py-1 text-sm">
                            <button type="button" onclick="updateQuantity(${index}, ${item.quantity + 1})"
                                    class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">+</button>
                        </div>
                        <button type="button" onclick="removeItem(${index})"
                                class="text-red-600 hover:text-red-800">
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

            document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toLocaleString();
            if (taxEnabled) {
                document.getElementById('taxDisplay').textContent = '$' + tax.toLocaleString();
            }
            document.getElementById('totalDisplay').textContent = '$' + total.toLocaleString();
        }

        // Búsqueda de productos
        document.getElementById('productSearch').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                card.style.display = name.includes(search) ? 'block' : 'none';
            });
        });

        // Modal de nuevo cliente
        function openCustomerModal() {
            document.getElementById('customerModal').classList.remove('hidden');
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
                alert('El nombre es requerido');
                return;
            }

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
                    alert('Cliente creado exitosamente');
                } else {
                    alert('Error al crear cliente: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al crear cliente. Por favor intenta de nuevo.');
            });
        }
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
