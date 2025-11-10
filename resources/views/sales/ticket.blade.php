<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #{{ $sale->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            background: white;
        }

        .ticket {
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .business-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .business-info {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .sale-info {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .sale-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .items-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .items-header {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .item-row {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
        }

        .totals {
            border-top: 1px solid #000;
            padding-top: 8px;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .payment-method {
            text-align: center;
            margin-bottom: 15px;
            padding: 5px;
            background: #f0f0f0;
            border: 1px solid #ccc;
        }

        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Estilos específicos para impresión */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            
            body {
                width: 80mm;
                font-size: 10px;
            }
            
            .ticket {
                max-width: 80mm;
                width: 80mm;
                margin: 0;
                padding: 5mm;
            }
            
            .no-print {
                display: none !important;
            }
            
            .business-name {
                font-size: 14px;
            }
            
            .total-final {
                font-size: 12px;
            }
        }

        /* Botón de imprimir solo visible en pantalla */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background: #2563eb;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Imprimir</button>
    
    <div class="ticket">
        <!-- Encabezado del negocio -->
        <div class="header">
            @if($settings->ticket_header)
            <div class="business-info" style="margin-bottom: 8px;">{{ $settings->ticket_header }}</div>
            @endif
            
            <div class="business-name">{{ strtoupper($businessSettings->business_name ?? 'MI TIENDA') }}</div>
            
            @if($settings->show_tax_id && $businessSettings->business_tax_id)
            <div class="business-info">NIT/RFC: {{ $businessSettings->business_tax_id }}</div>
            @endif
            
            @if($settings->show_phone && $businessSettings->business_phone)
            <div class="business-info">Tel: {{ $businessSettings->business_phone }}</div>
            @endif
            
            @if($settings->show_address && $businessSettings->business_address)
            <div class="business-info">{{ $businessSettings->business_address }}</div>
            @endif
            
            @if($settings->show_email && $businessSettings->business_email)
            <div class="business-info">{{ $businessSettings->business_email }}</div>
            @endif
        </div>

        <!-- Información de la venta -->
        <div class="sale-info">
            <div class="sale-info-row">
                <span><strong>Ticket:</strong></span>
                <span>{{ $sale->receipt_number ?? ($businessSettings->receipt_prefix ?? 'RV') . '-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="sale-info-row">
                <span><strong>Fecha:</strong></span>
                <span>{{ $sale->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="sale-info-row">
                <span><strong>Cajero:</strong></span>
                <span>{{ $sale->user->name }}</span>
            </div>
            @if($sale->customer)
            <div class="sale-info-row">
                <span><strong>Cliente:</strong></span>
                <span>{{ $sale->customer->name }}</span>
            </div>
            @endif
        </div>

        <!-- Productos vendidos -->
        <div class="items-table">
            <div class="items-header">PRODUCTOS VENDIDOS</div>
            
            @foreach($sale->items as $item)
            <div class="item-row">
                <div class="item-name">{{ $item->product->name }}</div>
                <div class="item-details">
                    <span>{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</span>
                    <span>${{ number_format($item->subtotal, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Totales -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($sale->total, 2) }}</span>
            </div>
            <div class="total-row">
                <span>IVA (0%):</span>
                <span>$0.00</span>
            </div>
            <div class="total-row total-final">
                <span>TOTAL:</span>
                <span>${{ number_format($sale->total, 2) }}</span>
            </div>
        </div>

        <!-- Método de pago -->
        <div class="payment-method">
            <strong>MÉTODO DE PAGO: {{ strtoupper($sale->payment_method) }}</strong>
        </div>

        <!-- Pie del ticket -->
        <div class="footer">
            @if($settings->ticket_footer)
            <div class="thank-you">{{ strtoupper($settings->ticket_footer) }}</div>
            @elseif($businessSettings->receipt_footer)
            <div class="thank-you">{{ strtoupper($businessSettings->receipt_footer) }}</div>
            @else
            <div class="thank-you">¡GRACIAS POR SU COMPRA!</div>
            @endif
            <div>Conserve este ticket</div>
            <div>como comprobante de compra</div>
            <br>
            <div>{{ $businessSettings->business_name ?? 'MI TIENDA' }}</div>
            <div>{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <script>
        // Auto-imprimir si se abre con parámetro
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>