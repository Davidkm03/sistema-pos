<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800">
                        Editar Cotización {{ $quote->quote_number ?? 'N/A' }}
                    </h2>
                    <p class="text-xs text-gray-500">Modifica los detalles de la cotización</p>
                </div>
            </div>

            <a href="{{ route('quotes.show', $quote) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm"
               title="Volver sin guardar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Cancelar</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

            {{-- Flash de éxito --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: @json(session('success')),
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    });
                </script>
            @endif

            {{-- Errores de validación (corregido: sin Blade dentro de strings) --}}
            @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const errs = @json($errors->all());
                        const list = '<ul class="text-left text-sm" style="padding-left:20px;margin:0;">'
                                   + errs.map(e => `<li class="text-red-600">• ${e}</li>`).join('')
                                   + '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Errores de validación',
                            html: list,
                            confirmButtonColor: '#4F46E5'
                        });
                    });
                </script>
            @endif

            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border-2 border-indigo-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Editar Cotización</h3>
                            <p class="text-xs text-indigo-100">Actualiza los datos del presupuesto</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('quotes.update', $quote) }}" method="POST" id="quoteForm" class="p-6">
                    @csrf
                    @method('PUT')

                    {{-- Preferencias dinámicas para JS --}}
                    @php
                        $currencySymbol = setting('currency_symbol', '$');
                        $taxEnabled = (bool) setting('tax_enabled', true);
                        $taxRate = (float) setting('tax_rate', 19);
                    @endphp

                    <input type="hidden" id="cfg-currency-symbol" value="{{ $currencySymbol }}">
                    <input type="hidden" id="cfg-tax-enabled" value="{{ $taxEnabled ? '1' : '0' }}">
                    <input type="hidden" id="cfg-tax-rate" value="{{ $taxRate }}">
                    <input type="hidden" name="status" value="{{ $quote->status }}">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Columna izquierda: Datos de cabecera y productos --}}
                        <div class="lg:col-span-2 space-y-6">

                            {{-- Cabecera --}}
                            <section aria-labelledby="sec-datos" class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                <h4 id="sec-datos" class="text-sm font-black text-gray-900 mb-4">Datos de la cotización</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Cliente --}}
                                    <div>
                                        <label for="customer_id" class="block text-sm font-semibold text-gray-700 mb-1">Cliente</label>
                                        <select name="customer_id" id="customer_id"
                                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                                aria-describedby="customerHelp">
                                            <option value="">Sin cliente</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id', $quote->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} {{ $customer->phone ? '— '.$customer->phone : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p id="customerHelp" class="text-xs text-gray-500 mt-1">Opcional, puedes dejarlo vacío.</p>
                                    </div>

                                    {{-- Válida hasta --}}
                                    <div>
                                        <label for="valid_until" class="block text-sm font-semibold text-gray-700 mb-1">Válida hasta (opcional)</label>
                                        <input type="date" id="valid_until" name="valid_until"
                                               value="{{ old('valid_until', $quote->valid_until?->format('Y-m-d')) }}"
                                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </section>

                            {{-- Productos --}}
                            <section aria-labelledby="sec-productos" class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 id="sec-productos" class="text-sm font-black text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Productos
                                    </h4>

                                    <button type="button" id="btnAddProduct"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Agregar producto
                                    </button>
                                </div>

                                <div id="products-container" class="space-y-3">
                                    {{-- Fila inicial por cada item existente --}}
                                    @foreach($quote->items as $index => $item)
                                        <div class="product-row grid grid-cols-12 gap-3 items-end p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200">
                                            <div class="col-span-12 sm:col-span-5">
                                                <label class="block text-xs font-medium text-gray-700 mb-1" for="product_{{ $index }}">Producto</label>
                                                <select id="product_{{ $index }}" name="items[{{ $index }}][product_id]"
                                                        class="product-select w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                        required>
                                                    <option value="">Seleccionar…</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}"
                                                                data-price="{{ $product->price }}"
                                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }} — {{ $currencySymbol }}{{ number_format($product->price, 0) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1" for="qty_{{ $index }}">Cantidad</label>
                            <input id="qty_{{ $index }}" type="number" name="items[{{ $index }}][quantity]"
                                   value="{{ max(1, (int) $item->quantity) }}"
                                   min="1" step="1"
                                   class="quantity-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   required>
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1" for="price_{{ $index }}">Precio</label>
                            <input id="price_{{ $index }}" type="number" name="items[{{ $index }}][price]"
                                   value="{{ max(0, (int) $item->price) }}"
                                   min="0" step="1"
                                   class="price-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   required>
                        </div>

                        <div class="col-span-10 sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Subtotal</label>
                            <input type="text" class="subtotal-display w-full rounded-lg border-gray-200 bg-gray-100 text-sm" readonly
                                   value="{{ $currencySymbol }}{{ number_format($item->subtotal, 0) }}">
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <button type="button" class="btn-remove-row w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm"
                                    title="Eliminar línea">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Estado vacío (se muestra si 0 filas) --}}
            <div id="empty-state" class="hidden text-center py-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 font-medium">No hay productos agregados</p>
                <p class="text-xs text-gray-400 mt-1">Usa “Agregar producto” para comenzar</p>
            </div>
        </section>
    </div>

    {{-- Columna derecha: Resumen --}}
    <aside class="space-y-6">
        <div class="sticky top-6 bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-2xl border-2 border-indigo-100">
            <h4 class="text-sm font-black text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                RESUMEN
            </h4>

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 font-semibold">Subtotal:</span>
                    <span class="font-black text-gray-900" id="subtotal-display" aria-live="polite">
                        {{ $currencySymbol }}{{ number_format($quote->subtotal, 0) }}
                    </span>
                </div>

                @if($taxEnabled)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 font-semibold">IVA ({{ number_format($taxRate, 0) }}%):</span>
                        <span class="font-black text-gray-900" id="tax-display" aria-live="polite">
                            {{ $currencySymbol }}{{ number_format($quote->tax, 0) }}
                        </span>
                    </div>
                @endif

                <div class="flex justify-between text-xl font-black border-t-2 border-indigo-200 pt-3">
                    <span class="text-gray-800">TOTAL:</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600"
                          id="total-display" aria-live="polite">
                        {{ $currencySymbol }}{{ number_format($quote->total, 0) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('quotes.show', $quote) }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>

            <button type="submit" id="btnSubmit"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Guardar cambios
            </button>
        </div>
    </aside>
</div>
</form>
</div>
</div>
</div>

{{-- Template de fila de producto para duplicar vía JS --}}
<template id="tpl-product-row">
    <div class="product-row grid grid-cols-12 gap-3 items-end p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200">
        <div class="col-span-12 sm:col-span-5">
            <label class="block text-xs font-medium text-gray-700 mb-1">Producto</label>
            <select class="product-select w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                <option value="">Seleccionar…</option>
                {{-- Opciones se inyectan por JS --}}
            </select>
        </div>

        <div class="col-span-6 sm:col-span-2">
            <label class="block text-xs font-medium text-gray-700 mb-1">Cantidad</label>
            <input type="number" class="quantity-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" min="1" step="1" value="1" required>
        </div>

        <div class="col-span-6 sm:col-span-2">
            <label class="block text-xs font-medium text-gray-700 mb-1">Precio</label>
            <input type="number" class="price-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" min="0" step="1" value="0" required>
        </div>

        <div class="col-span-10 sm:col-span-2">
            <label class="block text-xs font-medium text-gray-700 mb-1">Subtotal</label>
            <input type="text" class="subtotal-display w-full rounded-lg border-gray-200 bg-gray-100 text-sm" readonly value="$0">
        </div>

        <div class="col-span-2 sm:col-span-1">
            <button type="button" class="btn-remove-row w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm" title="Eliminar línea">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
    // ===== Config =====
    const PRODUCTS = {!! json_encode($products->map(function($p) { return ['id' => $p->id, 'name' => $p->name, 'price' => (int) $p->price]; })->values()) !!};

    const CURRENCY = document.getElementById('cfg-currency-symbol').value || '$';
    const TAX_ENABLED = document.getElementById('cfg-tax-enabled').value === '1';
    const TAX_RATE = parseFloat(document.getElementById('cfg-tax-rate').value || '19') / 100;

    const fmt = new Intl.NumberFormat('es-CO');

    const container = document.getElementById('products-container');
    const emptyState = document.getElementById('empty-state');
    const subtotalEl = document.getElementById('subtotal-display');
    const taxEl = document.getElementById('tax-display');
    const totalEl = document.getElementById('total-display');
    const form = document.getElementById('quoteForm');
    const btnAdd = document.getElementById('btnAddProduct');
    const btnSubmit = document.getElementById('btnSubmit');

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });

    // ===== Helpers =====
    function money(n){ return `${CURRENCY}${fmt.format(Math.round(n || 0))}`; }

    function toggleEmptyState(){
        const rows = container.querySelectorAll('.product-row');
        emptyState.classList.toggle('hidden', rows.length > 0);
    }

    function buildOptions(){
        const frag = document.createDocumentFragment();
        PRODUCTS.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.dataset.price = p.price;
            opt.textContent = `${p.name} — ${money(p.price)}`;
            frag.appendChild(opt);
        });
        return frag;
    }

    function attachRowEvents(row){
        const sel = row.querySelector('.product-select');
        const qty = row.querySelector('.quantity-input');
        const price = row.querySelector('.price-input');
        const subtotal = row.querySelector('.subtotal-display');
        const btnRemove = row.querySelector('.btn-remove-row');

        // Cambiar precio al seleccionar producto
        sel.addEventListener('change', () => {
            const opt = sel.options[sel.selectedIndex];
            const p = parseFloat(opt?.dataset?.price || '0');
            if (!isFinite(p) || p < 0) return;
            price.value = p;
            updateRowSubtotal();
            validateForm();
        });

        // Validaciones suaves
        qty.addEventListener('input', () => {
            if (qty.value === '') return;
            qty.value = Math.max(1, parseInt(qty.value, 10) || 1);
            updateRowSubtotal();
            validateForm();
        });

        price.addEventListener('input', () => {
            if (price.value === '') return;
            price.value = Math.max(0, parseInt(price.value, 10) || 0);
            updateRowSubtotal();
            validateForm();
        });

        function updateRowSubtotal(){
            const q = Math.max(1, parseInt(qty.value, 10) || 1);
            const pr = Math.max(0, parseFloat(price.value) || 0);
            subtotal.value = money(q * pr);
            calculateTotals();
        }

        // Eliminar fila
        btnRemove.addEventListener('click', () => {
            const rows = container.querySelectorAll('.product-row');
            if (rows.length <= 1){
                Swal.fire({
                    icon: 'warning',
                    title: 'No se puede eliminar',
                    text: 'Debe existir al menos un producto',
                    confirmButtonColor: '#4F46E5'
                });
                return;
            }
            row.remove();
            calculateTotals();
            toggleEmptyState();
            validateForm();
            Toast.fire({ icon: 'success', title: 'Línea eliminada' });
        });

        // Inicial
        updateRowSubtotal();
    }

    function addRow(){
        const tpl = document.getElementById('tpl-product-row');
        const row = tpl.content.firstElementChild.cloneNode(true);
        // Inject options
        row.querySelector('.product-select').appendChild(buildOptions());
        container.appendChild(row);
        attachRowEvents(row);
        toggleEmptyState();
        Toast.fire({ icon: 'success', title: 'Línea agregada' });
    }

    function calculateTotals(){
        let subtotal = 0;
        container.querySelectorAll('.product-row').forEach(row => {
            const qty = Math.max(1, parseInt(row.querySelector('.quantity-input').value, 10) || 1);
            const price = Math.max(0, parseFloat(row.querySelector('.price-input').value) || 0);
            subtotal += qty * price;
        });

        const tax = TAX_ENABLED ? subtotal * TAX_RATE : 0;
        const total = subtotal + tax;

        subtotalEl.textContent = money(subtotal);
        if (TAX_ENABLED && taxEl) taxEl.textContent = money(tax);
        totalEl.textContent = money(total);
    }

    function validateForm(){
        // Reglas UX: al menos una fila y todos con producto seleccionado
        const rows = container.querySelectorAll('.product-row');
        if (rows.length === 0){ btnSubmit.disabled = true; return; }

        let valid = true;
        rows.forEach((row, idx) => {
            const sel = row.querySelector('.product-select');
            const hasValue = Boolean(sel.value);
            sel.classList.toggle('border-red-400', !hasValue);
            if (!hasValue) valid = false;

            // Asegurar names para enviar al backend en orden
            const q = row.querySelector('.quantity-input');
            const p = row.querySelector('.price-input');
            sel.name = `items[${idx}][product_id]`;
            q.name   = `items[${idx}][quantity]`;
            p.name   = `items[${idx}][price]`;
        });

        btnSubmit.disabled = !valid;
    }

    // ===== Events =====
    document.addEventListener('DOMContentLoaded', () => {
        // Si no hay filas (edge cases), agrega una
        if (container.querySelectorAll('.product-row').length === 0){
            addRow();
        } else {
            // Conectar eventos a filas existentes renderizadas por Blade
            container.querySelectorAll('.product-row').forEach(attachRowEvents);
            toggleEmptyState();
            calculateTotals();
            validateForm();
        }
    });

    document.getElementById('btnAddProduct').addEventListener('click', addRow);

    form.addEventListener('submit', (e) => {
        // Validación final
        const rows = container.querySelectorAll('.product-row');
        if (rows.length === 0){
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Agrega al menos un producto', confirmButtonColor: '#4F46E5' });
            return false;
        }
        let invalid = false;
        rows.forEach(row => {
            const sel = row.querySelector('.product-select');
            if (!sel.value) invalid = true;
        });
        if (invalid){
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Selecciona producto en todas las líneas', confirmButtonColor: '#4F46E5' });
            return false;
        }
    });
</script>
@endpush
</x-app-layout>
