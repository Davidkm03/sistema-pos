<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">Editar Cotización {{ $quote->quote_number ?? 'N/A' }}</h2>
                    <p class="text-xs text-gray-500">Modifica los detalles de la cotización</p>
                </div>
            </div>
            <a href="{{ route('quotes.show', $quote) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="hidden sm:inline">Cancelar</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '{{ session("success") }}',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
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
                            html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                            confirmButtonColor: '#4F46E5',
                            showClass: {
                                popup: 'animate__animated animate__shakeX'
                            }
                        });
                    });
                </script>
            @endif

            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Editar Cotización</h3>
                            <p class="text-xs text-indigo-100">Actualiza los datos del presupuesto</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('quotes.update', $quote) }}" method="POST" id="quoteForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Cliente -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Cliente
                                </label>
                                <select name="customer_id" id="customer_id" class="w-full rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 font-medium transition-all">
                                    <option value="">Sin cliente</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $quote->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->phone ?? 'Sin teléfono' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Válida hasta -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Válida hasta (opcional)
                                </label>
                                <input type="date" name="valid_until" value="{{ old('valid_until', $quote->valid_until?->format('Y-m-d')) }}"
                                       class="w-full rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 font-medium transition-all">
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Productos
                                </h3>
                                <button type="button" onclick="addProduct()" 
                                        style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Producto
                                </button>
                            </div>

                            <div id="products-container" class="space-y-3">
                            @foreach($quote->items as $index => $item)
                            <div class="product-row grid grid-cols-12 gap-3 items-end p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg shadow-sm border border-gray-200">
                                <div class="col-span-5">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Producto</label>
                                    <select name="items[{{ $index }}][product_id]" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm product-select" required onchange="updatePrice(this)">
                                        <option value="">Seleccionar...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-price="{{ $product->price }}"
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - ${{ number_format($product->price, 0) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Cantidad</label>
                                    <input type="number" name="items[{{ $index }}][quantity]" 
                                           value="{{ $item->quantity }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm quantity-input" 
                                           min="1" step="1" required onchange="calculateSubtotal(this)">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Precio</label>
                                    <input type="number" name="items[{{ $index }}][price]" 
                                           value="{{ $item->price }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm price-input" 
                                           min="0" step="1" required onchange="calculateSubtotal(this)">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Subtotal</label>
                                    <input type="text" class="w-full rounded-lg border-gray-300 bg-gray-100 text-sm subtotal-display" readonly value="${{ number_format($item->subtotal, 0) }}">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removeProduct(this)" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                        <!-- Totales -->
                        <div class="border-t-2 border-indigo-100 pt-6 mt-6">
                            <div class="flex justify-end">
                                <div class="w-full md:w-80 bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-2xl border-2 border-indigo-100">
                                    <h4 class="text-sm font-black text-gray-700 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        RESUMEN
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-semibold">Subtotal:</span>
                                            <span class="font-black text-gray-900" id="subtotal-display">${{ number_format($quote->subtotal, 0) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-semibold">IVA (19%):</span>
                                            <span class="font-black text-gray-900" id="tax-display">${{ number_format($quote->tax, 0) }}</span>
                                        </div>
                                        <div class="flex justify-between text-xl font-black border-t-2 border-indigo-200 pt-3">
                                            <span class="text-gray-800">TOTAL:</span>
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600" id="total-display">${{ number_format($quote->total, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t-2 border-gray-200">
                            <a href="{{ route('quotes.show', $quote) }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let productIndex = {{ count($quote->items) }};

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        function addProduct() {
            const container = document.getElementById('products-container');
            const row = document.createElement('div');
            row.className = 'product-row grid grid-cols-12 gap-3 items-end p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg shadow-sm border border-gray-200';
            row.style.animation = 'fadeInDown 0.3s ease-out';
            row.innerHTML = `
                <div class="col-span-5">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Producto</label>
                    <select name="items[${productIndex}][product_id]" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm product-select" required onchange="updatePrice(this)">
                        <option value="">Seleccionar...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - ${{ number_format($product->price, 0) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cantidad</label>
                    <input type="number" name="items[${productIndex}][quantity]" value="1" 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm quantity-input" 
                           min="1" step="1" required onchange="calculateSubtotal(this)">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Precio</label>
                    <input type="number" name="items[${productIndex}][price]" value="0" 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm price-input" 
                           min="0" step="1" required onchange="calculateSubtotal(this)">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Subtotal</label>
                    <input type="text" class="w-full rounded-lg border-gray-300 bg-gray-100 text-sm subtotal-display" readonly value="$0">
                </div>
                <div class="col-span-1">
                    <button type="button" onclick="removeProduct(this)" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(row);
            productIndex++;
            
            Toast.fire({
                icon: 'success',
                title: 'Línea agregada'
            });
        }

        function removeProduct(button) {
            const row = button.closest('.product-row');
            const productRows = document.querySelectorAll('.product-row');
            
            if (productRows.length === 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se puede eliminar',
                    text: 'Debe haber al menos un producto',
                    confirmButtonColor: '#4F46E5'
                });
                return;
            }

            Swal.fire({
                title: '¿Eliminar línea?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    row.style.animation = 'fadeOutUp 0.3s ease-out';
                    setTimeout(() => {
                        row.remove();
                        calculateTotals();
                        Toast.fire({
                            icon: 'success',
                            title: 'Línea eliminada'
                        });
                    }, 300);
                }
            });
        }

        function updatePrice(select) {
            const row = select.closest('.product-row');
            const priceInput = row.querySelector('.price-input');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            priceInput.value = price;
            calculateSubtotal(priceInput);
        }

        function calculateSubtotal(input) {
            const row = input.closest('.product-row');
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = quantity * price;
            row.querySelector('.subtotal-display').value = '$' + subtotal.toLocaleString('es-CO');
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                subtotal += quantity * price;
            });

            const tax = subtotal * 0.19;
            const total = subtotal + tax;

            document.getElementById('subtotal-display').textContent = '$' + subtotal.toLocaleString('es-CO');
            document.getElementById('tax-display').textContent = '$' + Math.round(tax).toLocaleString('es-CO');
            document.getElementById('total-display').textContent = '$' + Math.round(total).toLocaleString('es-CO');
        }

        // Form submission con validación
        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            const productRows = document.querySelectorAll('.product-row');
            
            if (productRows.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe agregar al menos un producto',
                    confirmButtonColor: '#4F46E5'
                });
                return false;
            }

            let hasEmptyProducts = false;
            productRows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                if (!productSelect.value) {
                    hasEmptyProducts = true;
                }
            });

            if (hasEmptyProducts) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un producto en todas las líneas',
                    confirmButtonColor: '#4F46E5'
                });
                return false;
            }
        });

        // Calcular totales al cargar
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotals();
        });
    </script>
    @endpush
</x-app-layout>
