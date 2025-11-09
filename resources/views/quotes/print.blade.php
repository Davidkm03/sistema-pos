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

        @media print {
            @page {
                margin: 1cm;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }

        .quote-document {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
        }

        .business-info {
            flex: 1;
        }

        .business-name {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }

        .business-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }

        .quote-info {
            text-align: right;
        }

        .quote-number {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .quote-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pendiente { background: #FEF3C7; color: #92400E; }
        .status-aprobada { background: #D1FAE5; color: #065F46; }
        .status-rechazada { background: #FEE2E2; color: #991B1B; }
        .status-convertida { background: #DBEAFE; color: #1E40AF; }

        .parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .party-box {
            padding: 15px;
            background: #F9FAFB;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }

        .party-title {
            font-size: 13px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .party-details {
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .items-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .items-table thead {
            background: #F3F4F6;
        }

        .items-table th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            border-bottom: 2px solid #D1D5DB;
        }

        .items-table th:nth-child(2),
        .items-table th:nth-child(3),
        .items-table th:nth-child(4) {
            text-align: right;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 12px;
            color: #1F2937;
        }

        .items-table td:nth-child(2),
        .items-table td:nth-child(3),
        .items-table td:nth-child(4) {
            text-align: right;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .totals {
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 12px;
        }

        .total-row.subtotal,
        .total-row.tax,
        .total-row.discount {
            border-bottom: 1px solid #E5E7EB;
        }

        .total-row.final {
            font-size: 16px;
            font-weight: bold;
            color: #4F46E5;
            padding-top: 12px;
            border-top: 2px solid #4F46E5;
        }

        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background: #FFFBEB;
            border-left: 4px solid #F59E0B;
            border-radius: 4px;
        }

        .notes-title {
            font-size: 12px;
            font-weight: bold;
            color: #92400E;
            margin-bottom: 8px;
        }

        .notes-content {
            font-size: 11px;
            color: #78350F;
            white-space: pre-line;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }

        .print-button:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(239, 68, 68, 0.1);
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <svg style="display: inline-block; width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Imprimir Cotización
    </button>

    <div class="quote-document" style="position: relative;">
        @if($quote->status === 'rechazada')
        <div class="watermark">RECHAZADA</div>
        @elseif($quote->status === 'convertida')
        <div class="watermark" style="color: rgba(59, 130, 246, 0.1);">CONVERTIDA</div>
        @elseif($quote->valid_until && $quote->isExpired())
        <div class="watermark" style="color: rgba(239, 68, 68, 0.1);">VENCIDA</div>
        @endif

        <!-- Header -->
        <div class="header">
            <div class="business-info">
                <div class="business-name">{{ setting('business_name', 'Mi Negocio') }}</div>
                <div class="business-details">
                    @if(setting('business_address'))
                    Dirección: {{ setting('business_address') }}<br>
                    @endif
                    @if(setting('business_phone'))
                    Teléfono: {{ setting('business_phone') }}<br>
                    @endif
                    @if(setting('business_email'))
                    Email: {{ setting('business_email') }}<br>
                    @endif
                    @if(setting('business_nit'))
                    NIT: {{ setting('business_nit') }}
                    @endif
                </div>
            </div>
            <div class="quote-info">
                <div class="quote-label">COTIZACIÓN</div>
                <div class="quote-number">{{ $quote->quote_number }}</div>
                <span class="status-badge status-{{ $quote->status }}">
                    {{ $quote->getStatusLabel() }}
                </span>
            </div>
        </div>

        <!-- Parties (Cliente y Vendedor) -->
        <div class="parties">
            <div class="party-box">
                <div class="party-title">Cliente</div>
                <div class="party-details">
                    @if($quote->customer)
                        <strong>{{ $quote->customer->name }}</strong><br>
                        @if($quote->customer->document)
                        Doc: {{ $quote->customer->document }}<br>
                        @endif
                        @if($quote->customer->email)
                        Email: {{ $quote->customer->email }}<br>
                        @endif
                        @if($quote->customer->phone)
                        Tel: {{ $quote->customer->phone }}
                        @endif
                    @else
                        Cliente general
                    @endif
                </div>
            </div>

            <div class="party-box">
                <div class="party-title">Detalles de Cotización</div>
                <div class="party-details">
                    <strong>Fecha:</strong> {{ $quote->created_at->format('d/m/Y') }}<br>
                    @if($quote->valid_until)
                    <strong>Válida hasta:</strong> 
                    <span style="{{ $quote->valid_until && $quote->isExpired() ? 'color: #DC2626; font-weight: bold;' : '' }}">
                        {{ $quote->valid_until->format('d/m/Y') }}
                        @if($quote->isExpired())
                        (Vencida)
                        @endif
                    </span><br>
                    @endif
                    <strong>Vendedor:</strong> {{ $quote->user->name }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Producto / Servicio</th>
                    <th>Cant.</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Producto eliminado' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 0) }}</td>
                    <td><strong>${{ number_format($item->subtotal, 0) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals">
                <div class="total-row subtotal">
                    <span>Subtotal:</span>
                    <span>${{ number_format($quote->subtotal, 0) }}</span>
                </div>
                @if($quote->tax > 0)
                <div class="total-row tax">
                    <span>IVA:</span>
                    <span>${{ number_format($quote->tax, 0) }}</span>
                </div>
                @endif
                @if($quote->discount > 0)
                <div class="total-row discount">
                    <span>Descuento:</span>
                    <span style="color: #DC2626;">-${{ number_format($quote->discount, 0) }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>TOTAL:</span>
                    <span>${{ number_format($quote->total, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($quote->notes)
        <div class="notes-section">
            <div class="notes-title">Notas / Observaciones</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </div>
        @endif

        <!-- Conversion Info -->
        @if($quote->status === 'convertida' && $quote->convertedSale)
        <div class="notes-section" style="background: #DBEAFE; border-left-color: #3B82F6;">
            <div class="notes-title" style="color: #1E40AF;">Información de Conversión</div>
            <div class="notes-content" style="color: #1E3A8A;">
                Esta cotización fue convertida a venta el {{ $quote->converted_at->format('d/m/Y H:i') }}<br>
                Venta #{{ $quote->converted_to_sale_id }}
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Esta es una cotización, NO es una factura.</strong></p>
            <p>Los precios son válidos hasta la fecha indicada y están sujetos a disponibilidad de inventario.</p>
            <p style="margin-top: 10px;">Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
