<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Título -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Carrito de Venta</h2>

    <!-- Contenido del Carrito -->
    @if (empty($cartItems))
        <!-- Carrito Vacío -->
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h12.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z" />
            </svg>
            <p class="text-gray-500 text-lg">El carrito está vacío</p>
        </div>
    @else
        <!-- Tabla de Items -->
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($cartItems as $item)
                        <tr>
                            <!-- Producto -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                            </td>
                            
                            <!-- Precio -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($item['price'], 2) }}</div>
                            </td>
                            
                            <!-- Cantidad -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1">
                                    <button 
                                        wire:click="updateQuantity({{ $loop->index }}, {{ $item['quantity'] - 1 }})"
                                        class="p-1 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    
                                    <input 
                                        type="number" 
                                        min="1" 
                                        max="{{ $item['stock'] }}"
                                        wire:model.blur="cartItems.{{ $loop->index }}.quantity"
                                        wire:change="updateQuantity({{ $loop->index }}, $event.target.value)"
                                        class="w-16 px-2 py-1 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                    
                                    <button 
                                        wire:click="updateQuantity({{ $loop->index }}, {{ $item['quantity'] + 1 }})"
                                        class="p-1 bg-blue-500 hover:bg-blue-600 rounded-md text-white transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                                @if($item['quantity'] >= $item['stock'])
                                <div class="text-xs text-red-500 mt-1">Stock máximo</div>
                                @endif
                            </td>
                            
                            <!-- Subtotal -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($item['subtotal'], 2) }}</div>
                            </td>
                            
                            <!-- Acción -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button 
                                    wire:click="removeProduct({{ $loop->index }})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sección de Resumen -->
        <div class="border-t pt-6">
            <!-- Desglose Fiscal -->
            @if(tax_enabled())
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Resumen de Totales</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal (sin IVA):</span>
                            <span class="font-semibold">{{ format_currency($cartSubtotal) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>IVA ({{ get_tax_rate() }}%):</span>
                            <span class="font-semibold">{{ format_currency($cartTaxAmount) }}</span>
                        </div>

                        <div class="border-t pt-2 flex justify-between">
                            <span>Subtotal + IVA:</span>
                            <span class="font-semibold">{{ format_currency($cartSubtotal + $cartTaxAmount) }}</span>
                        </div>

                        @if($cartRetentionAmount > 0)
                            <div class="flex justify-between text-red-600">
                                <span>Retención (-{{ setting('retention_rate', 3.5) }}%):</span>
                                <span class="font-semibold">-{{ format_currency($cartRetentionAmount) }}</span>
                            </div>
                        @endif

                        <div class="border-t pt-2 flex justify-between text-lg">
                            <span class="font-bold">TOTAL A PAGAR:</span>
                            <span class="font-bold text-green-600">{{ format_currency($total) }}</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Total Simple (sin IVA) -->
                <div class="text-right mb-4">
                    <p class="text-lg text-gray-600">Total:</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($total, 2) }}</p>
                </div>
            @endif

            <!-- Método de Pago y Acciones -->
            <div class="flex flex-col space-y-4">
                <!-- Método de Pago -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Método de Pago</label>
                        <select
                            wire:model.live="paymentMethod"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta_debito">Tarjeta Débito</option>
                            <option value="tarjeta_credito">Tarjeta Crédito</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>

                    <!-- Detalles de Transferencia -->
                    @if($paymentMethod === 'transferencia')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Transferencia</label>
                            <select
                                wire:model="transfer_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Seleccionar tipo...</option>
                                <option value="nequi">Nequi</option>
                                <option value="daviplata">Daviplata</option>
                                <option value="bancolombia">Bancolombia</option>
                                <option value="llave">Llave (PSE)</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    @endif
                </div>

                @if($paymentMethod === 'transferencia')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Referencia (opcional)</label>
                        <input
                            type="text"
                            wire:model="transfer_reference"
                            placeholder="Ingrese número de referencia"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                @endif

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <button
                        onclick="confirmClearCart()"
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition duration-200"
                    >
                        Limpiar Carrito
                    </button>

                    <button
                        wire:click="completeSale"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="flex-1 px-8 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>Completar Venta</span>
                        <span wire:loading class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Función para confirmar limpiar carrito
    function confirmClearCart() {
        Swal.fire({
            title: '¿Limpiar carrito?',
            text: "Se eliminarán todos los productos del carrito",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6B7280',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Sí, limpiar',
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
                $wire.clearCart();
                Swal.fire({
                    title: '¡Carrito limpio!',
                    text: 'El carrito ha sido vaciado',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    showClass: {
                        popup: 'animate__animated animate__zoomIn'
                    }
                });
            }
        });
    }

    // Escuchar evento cuando se completa la venta con éxito
    $wire.on('sale-completed', (event) => {
        const saleId = event.saleId;
        const receiptNumber = event.receiptNumber || 'N/A';
        const total = event.total || 0;
        
        Swal.fire({
            title: '¡Venta Exitosa! 🎉',
            html: `
                <div class="text-left">
                    <p class="mb-2"><strong>Ticket:</strong> ${receiptNumber}</p>
                    <p class="mb-2"><strong>Total:</strong> $${parseFloat(total).toFixed(2)}</p>
                    <p class="text-sm text-gray-600 mt-4">¿Desea imprimir el ticket?</p>
                </div>
            `,
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-print"></i> Imprimir',
            cancelButtonText: 'Cerrar',
            reverseButtons: true,
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.printTicket(saleId);
            }
        });
    });

    // Escuchar evento cuando hay un error
    $wire.on('sale-error', (event) => {
        const message = event.message || 'Error al completar la venta';
        
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#EF4444',
            confirmButtonText: 'Entendido',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });

    // Escuchar cuando hay problemas de stock
    $wire.on('stock-error', (event) => {
        const message = event.message || 'La cantidad solicitada excede el stock disponible';
        
        Swal.fire({
            title: 'Stock Insuficiente',
            text: message,
            icon: 'warning',
            confirmButtonColor: '#F59E0B',
            confirmButtonText: 'Entendido',
            showClass: {
                popup: 'animate__animated animate__headShake'
            }
        });
    });

    $wire.on('saleCompleted', (event) => {
        // Obtener el ID de la venta
        const saleId = event.saleId;
        
        // Solicitar los datos para imprimir
        $wire.printTicket(saleId);
    });

    $wire.on('openTicketPrintWindow', (event) => {
        const sale = event.sale;
        const settings = event.settings;
        const businessSettings = event.businessSettings;
        
        // Generar el HTML del ticket
        let ticketHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Ticket - ${sale.receipt_number || 'N/A'}</title>
                <style>
                    @media print {
                        @page { margin: 0; }
                        body { margin: 10px; }
                    }
                    body {
                        font-family: 'Courier New', monospace;
                        width: 80mm;
                        margin: 0 auto;
                        padding: 10px;
                        font-size: 12px;
                    }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .line { border-bottom: 1px dashed #000; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 2px 0; }
                    .right { text-align: right; }
                    .large { font-size: 16px; }
                    .primary-color { color: ${businessSettings.primary_color || '#3B82F6'}; }
                    .secondary-color { color: ${businessSettings.secondary_color || '#10B981'}; }
                    .logo { max-height: 60px; margin: 10px auto; }
                </style>
            </head>
            <body>
        `;

        // Logo del negocio (si existe)
        if (businessSettings.logo_url) {
            ticketHTML += `<div class="center"><img src="${businessSettings.logo_url}" alt="Logo" class="logo"></div>`;
        }

        // Encabezado personalizado (de ticket_settings - para compatibilidad)
        if (settings.ticket_header) {
            ticketHTML += `<div class="center bold">${settings.ticket_header}</div>`;
        }

        // Nombre del negocio (ahora de business_settings)
        ticketHTML += `<div class="center bold large primary-color">${businessSettings.business_name || settings.business_name || 'Mi Tienda'}</div>`;

        // Información del negocio de business_settings (preferencia) o settings (fallback)
        const address = businessSettings.business_address || settings.address;
        const phone = businessSettings.business_phone || settings.phone;
        const email = businessSettings.business_email || settings.email;
        const taxId = businessSettings.business_tax_id || settings.tax_id;

        // Dirección
        if (settings.show_address && address) {
            ticketHTML += `<div class="center">${address}</div>`;
        }

        // Teléfono
        if (settings.show_phone && phone) {
            ticketHTML += `<div class="center">Tel: ${phone}</div>`;
        }

        // Email
        if (settings.show_email && email) {
            ticketHTML += `<div class="center">${email}</div>`;
        }

        // RIF/NIT
        if (settings.show_tax_id && taxId) {
            ticketHTML += `<div class="center">RFC/NIT: ${taxId}</div>`;
        }

        ticketHTML += `<div class="line"></div>`;

        // Título del documento según tipo
        const documentType = sale.document_type || 'receipt';
        const billingType = businessSettings.billing_type || 'simple_receipt';

        if (documentType === 'receipt' || billingType === 'simple_receipt') {
            // RECIBO DE VENTA
            ticketHTML += `<div class="center bold large">RECIBO DE VENTA</div>`;
            const receiptPrefix = businessSettings.receipt_prefix || 'RV';
            const receiptNum = sale.receipt_number || 'N/A';
            ticketHTML += `<div class="center bold">No. ${receiptPrefix}-${String(receiptNum).padStart(6, '0')}</div>`;

            // Mostrar disclaimer si está activado
            if (businessSettings.show_tax_disclaimer !== false) {
                ticketHTML += `<div class="center small" style="margin: 10px 0; padding: 5px; border: 1px solid #666;">DOCUMENTO NO VÁLIDO COMO FACTURA<br>No genera derecho a impuestos descontables</div>`;
            }

            // Encabezado personalizado
            if (businessSettings.receipt_header) {
                ticketHTML += `<div class="center small" style="margin: 5px 0;">${businessSettings.receipt_header}</div>`;
            }
        } else if (documentType === 'invoice') {
            // FACTURA TRADICIONAL CON DIAN
            ticketHTML += `<div class="center bold large">FACTURA DE VENTA</div>`;
            const invoicePrefix = businessSettings.invoice_prefix || 'FV';
            const invoiceNum = sale.invoice_number || 'N/A';
            ticketHTML += `<div class="center bold">No. ${invoicePrefix}-${String(invoiceNum).padStart(6, '0')}</div>`;

            // Información DIAN
            if (businessSettings.dian_resolution) {
                ticketHTML += `<div class="line"></div>`;
                ticketHTML += `<div class="small center">Resolución DIAN No. ${businessSettings.dian_resolution}</div>`;
                if (businessSettings.resolution_date) {
                    ticketHTML += `<div class="small center">Fecha: ${businessSettings.resolution_date}</div>`;
                }
                if (businessSettings.range_from && businessSettings.range_to) {
                    ticketHTML += `<div class="small center">Autorizado del ${businessSettings.range_from} al ${businessSettings.range_to}</div>`;
                }
                if (businessSettings.resolution_expiry) {
                    ticketHTML += `<div class="small center">Vencimiento: ${businessSettings.resolution_expiry}</div>`;
                }
            }
        } else if (documentType === 'none') {
            // Sin documento
            ticketHTML += `<div class="center bold">COMPROBANTE DE VENTA</div>`;
        }

        ticketHTML += `<div class="line"></div>`;

        // Información de la venta
        ticketHTML += `
            <div>Fecha: ${new Date(sale.created_at).toLocaleString('es-ES')}</div>
            <div>Cajero: ${sale.user?.name || 'N/A'}</div>
            <div>Método: ${sale.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta'}</div>
            <div class="line"></div>
        `;

        // Items de la venta
        ticketHTML += `<table>`;
        sale.items.forEach(item => {
            const productName = item.product?.name || 'Producto';
            const quantity = item.quantity;
            const unitPrice = parseFloat(item.unit_price || item.price).toFixed(2);
            const subtotal = parseFloat(item.subtotal || item.price * item.quantity).toFixed(2);

            ticketHTML += `
                <tr>
                    <td colspan="3" class="bold">${productName}</td>
                </tr>
                <tr>
                    <td>${quantity} x</td>
                    <td class="right">$${unitPrice}</td>
                    <td class="right">$${subtotal}</td>
                </tr>
            `;
        });
        ticketHTML += `</table>`;

        ticketHTML += `<div class="line"></div>`;

        // Desglose fiscal (si está habilitado IVA)
        const currencySymbol = businessSettings.currency === 'USD' ? '$' :
                               businessSettings.currency === 'EUR' ? '€' :
                               businessSettings.currency === 'GBP' ? '£' : '$';

        const subtotal = parseFloat(sale.subtotal || 0);
        const taxAmount = parseFloat(sale.tax_amount || 0);
        const retentionAmount = parseFloat(sale.retention_amount || 0);
        const total = parseFloat(sale.total).toFixed(2);

        // Mostrar desglose si hay información tributaria
        if (taxAmount > 0 || subtotal > 0) {
            ticketHTML += `
                <table style="font-size: 11px;">
                    <tr>
                        <td>Subtotal (sin IVA):</td>
                        <td class="right">${currencySymbol}${subtotal.toFixed(2)}</td>
                    </tr>
            `;

            if (taxAmount > 0) {
                ticketHTML += `
                    <tr>
                        <td>IVA:</td>
                        <td class="right">${currencySymbol}${taxAmount.toFixed(2)}</td>
                    </tr>
                `;
            }

            if (retentionAmount > 0) {
                ticketHTML += `
                    <tr style="color: #dc2626;">
                        <td>Retención:</td>
                        <td class="right">-${currencySymbol}${retentionAmount.toFixed(2)}</td>
                    </tr>
                `;
            }

            ticketHTML += `</table><div class="line"></div>`;
        }

        // Total con color secundario
        ticketHTML += `
            <table>
                <tr>
                    <td class="bold large">TOTAL:</td>
                    <td class="right bold large secondary-color">${currencySymbol}${total} ${businessSettings.currency || 'MXN'}</td>
                </tr>
            </table>
            <div class="line"></div>
        `;

        // Pie de página personalizado del negocio
        const footerMessage = businessSettings.receipt_footer || settings.ticket_footer;
        if (footerMessage) {
            ticketHTML += `<div class="center bold">${footerMessage}</div>`;
        }

        ticketHTML += `
                <div class="center" style="margin-top: 10px; font-size: 10px;">
                    Sistema POS - ${new Date().getFullYear()}
                </div>
            </body>
            </html>
        `;

        // Abrir ventana de impresión
        const printWindow = window.open('', '_blank', 'width=300,height=600');
        printWindow.document.write(ticketHTML);
        printWindow.document.close();
        
        // Esperar a que cargue y luego imprimir
        printWindow.onload = function() {
            printWindow.print();
            // Opcional: cerrar la ventana después de imprimir
            // printWindow.onafterprint = function() { printWindow.close(); };
        };
    });
</script>
@endscript
