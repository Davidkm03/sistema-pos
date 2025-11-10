<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ $quote->quote_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            background: #fff;
            width: 80mm; /* Ancho estándar impresora térmica 80mm */
            margin: 0 auto;
            padding: 5mm;
        }

        /* Para impresoras de 58mm, descomentar la siguiente línea */
        /* body { width: 58mm; font-size: 11px; } */

        @media print {
            @page {
                size: 80mm auto; /* Altura automática */
                margin: 0;
            }
            
            body {
                width: 80mm;
                padding: 3mm;
            }
            
            .no-print {
                display: none !important;
            }
        }

        /* Estilos de texto */
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: 10px; }
        .large { font-size: 14px; }
        
        /* Línea divisoria */
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        
        .divider-solid {
            border-top: 1px solid #000;
            margin: 8px 0;
        }

        /* Encabezado */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 50mm;
            max-height: 25mm;
            margin: 0 auto 5px;
        }

        .business-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .business-info {
            font-size: 10px;
            line-height: 1.4;
        }

        /* Título del documento */
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            padding: 5px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        /* Información */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
        }

        /* Tabla de productos */
        .items-table {
            width: 100%;
            margin: 10px 0;
            font-size: 11px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .item-row {
            margin: 5px 0;
            page-break-inside: avoid;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        /* Totales */
        .totals {
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 11px;
        }

        .total-final {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px double #000;
            padding: 5px 0;
            margin-top: 5px;
        }

        /* Estado */
        .status {
            text-align: center;
            padding: 5px;
            margin: 10px 0;
            border: 1px solid #000;
            font-weight: bold;
        }

        /* Notas */
        .notes {
            margin-top: 10px;
            padding: 5px;
            border: 1px dashed #000;
            font-size: 10px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            line-height: 1.4;
        }

        /* Botón de impresión (solo en pantalla) */
        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-btn:hover {
            background: #4338CA;
        }

        /* Espaciado al final para corte */
        .cut-line {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Imprimir</button>

    @php
        $cur = setting('currency_symbol', '$');
        $businessName = setting('business_name', 'Mi Negocio');
        $businessAddress = setting('business_address');
        $businessPhone = setting('business_phone');
        $businessEmail = setting('business_email');
        $businessNit = setting('business_nit');
    @endphp

    <!-- ENCABEZADO -->
    <div class="header">
        @if(setting('business_logo_url'))
            <img src="{{ setting('business_logo_url') }}" alt="Logo" class="logo">
        @endif
        
        <div class="business-name">{{ $businessName }}</div>
        
        <div class="business-info">
            @if($businessNit)NIT: {{ $businessNit }}<br>@endif
            @if($businessAddress){{ $businessAddress }}<br>@endif
            @if($businessPhone)Tel: {{ $businessPhone }}<br>@endif
            @if($businessEmail){{ $businessEmail }}@endif
        </div>
    </div>

    <div class="divider-solid"></div>

    <!-- TÍTULO -->
    <div class="doc-title">COTIZACIÓN</div>

    <!-- INFORMACIÓN DEL DOCUMENTO -->
    <div class="info-row">
        <span class="info-label">No:</span>
        <span>{{ $quote->quote_number }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Fecha:</span>
        <span>{{ $quote->created_at->format('d/m/Y H:i') }}</span>
    </div>

    @if($quote->valid_until)
    <div class="info-row">
        <span class="info-label">Válida hasta:</span>
        <span>{{ $quote->valid_until->format('d/m/Y') }}</span>
    </div>
    @endif

    <div class="info-row">
        <span class="info-label">Vendedor:</span>
        <span>{{ optional($quote->user)->name ?? 'Sistema' }}</span>
    </div>

    <div class="divider"></div>

    <!-- INFORMACIÓN DEL CLIENTE -->
    <div class="bold">CLIENTE:</div>
    @if($quote->customer)
        <div style="margin-left: 5px; font-size: 11px;">
            {{ $quote->customer->name }}<br>
            @if($quote->customer->document)Doc: {{ $quote->customer->document }}<br>@endif
            @if($quote->customer->phone)Tel: {{ $quote->customer->phone }}<br>@endif
            @if($quote->customer->email){{ $quote->customer->email }}@endif
        </div>
    @else
        <div style="margin-left: 5px; font-size: 11px;">Cliente General</div>
    @endif

    <div class="divider"></div>

    <!-- ESTADO -->
    <div class="status">
        ESTADO: {{ strtoupper($quote->getStatusLabel()) }}
    </div>

    <div class="divider"></div>

    <!-- PRODUCTOS -->
    <div class="items-table">
        <div class="items-header">
            <span>DESCRIPCIÓN</span>
        </div>

        @foreach($quote->items as $item)
        <div class="item-row">
            <div class="item-name">{{ $item->product->name ?? 'Producto eliminado' }}</div>
            <div class="item-details">
                <span>{{ $item->quantity }} x {{ $cur }}{{ number_format($item->price, 0) }}</span>
                <span class="bold">{{ $cur }}{{ number_format($item->subtotal, 0) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="divider-solid"></div>

    <!-- TOTALES -->
    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>{{ $cur }}{{ number_format($quote->subtotal, 0) }}</span>
        </div>

        @if($quote->tax > 0)
        <div class="total-row">
            <span>IVA ({{ number_format(setting('tax_rate', 19), 0) }}%):</span>
            <span>{{ $cur }}{{ number_format($quote->tax, 0) }}</span>
        </div>
        @endif

        @if($quote->discount > 0)
        <div class="total-row">
            <span>Descuento:</span>
            <span>-{{ $cur }}{{ number_format($quote->discount, 0) }}</span>
        </div>
        @endif

        <div class="total-row total-final">
            <span>TOTAL:</span>
            <span>{{ $cur }}{{ number_format($quote->total, 0) }}</span>
        </div>
    </div>

    <!-- NOTAS -->
    @if($quote->notes)
    <div class="divider"></div>
    <div class="notes">
        <div class="notes-title">NOTAS:</div>
        <div>{{ $quote->notes }}</div>
    </div>
    @endif

    <!-- CONVERSIÓN A VENTA -->
    @if($quote->status === 'convertida' && $quote->convertedSale)
    <div class="divider"></div>
    <div class="notes">
        <div class="notes-title">CONVERTIDA A VENTA:</div>
        <div>
            Fecha: {{ optional($quote->converted_at)->format('d/m/Y H:i') }}<br>
            Venta #{{ $quote->converted_to_sale_id }}
        </div>
    </div>
    @endif

    <div class="divider"></div>

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>*** COTIZACIÓN - NO ES FACTURA ***</strong></p>
        <p>Precios válidos hasta la fecha indicada</p>
        <p>Sujeto a disponibilidad de inventario</p>
        <p style="margin-top: 5px;">Impreso: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- LÍNEA DE CORTE -->
    <div class="cut-line">
        - - - - - - - ✂ - - - - - - -
    </div>

    <script>
        // Auto-imprimir al cargar (opcional, comentar si no se desea)
        // window.onload = function() { setTimeout(() => window.print(), 500); };
    </script>
</body>
</html>
