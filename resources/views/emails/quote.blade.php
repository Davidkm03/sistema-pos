<x-mail::message>
# Cotización #{{ $quote->quote_number }}

Estimado/a {{ $quote->customer ? $quote->customer->name : 'Cliente' }},

Nos complace enviarle la siguiente cotización:

---

## Detalles de la Cotización

**Fecha:** {{ $quote->created_at->format('d/m/Y') }}  
**Válida hasta:** {{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : 'No especificada' }}  
**Estado:** {{ ucfirst($quote->status) }}

---

## Productos/Servicios

<x-mail::table>
| Producto | Cantidad | Precio Unit. | Subtotal |
|:---------|:--------:|-------------:|---------:|
@foreach($quote->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | ${{ number_format($item->price, 2) }} | ${{ number_format($item->subtotal, 2) }} |
@endforeach
</x-mail::table>

---

## Resumen

- **Subtotal:** ${{ number_format($quote->subtotal, 2) }}
@if($quote->tax > 0)
- **IVA ({{ setting('tax_rate', 19) }}%):** ${{ number_format($quote->tax, 2) }}
@endif
@if($quote->discount > 0)
- **Descuento:** -${{ number_format($quote->discount, 2) }}
@endif
- **TOTAL:** **${{ number_format($quote->total, 2) }} {{ $businessSettings->currency ?? 'MXN' }}**

@if($quote->notes)
---

## Notas Adicionales

{{ $quote->notes }}
@endif

---

<x-mail::button :url="route('quotes.show', $quote->id)" color="success">
Ver Cotización Completa
</x-mail::button>

Si tiene alguna pregunta o desea proceder con esta cotización, no dude en contactarnos.

Saludos cordiales,

**{{ $businessSettings->business_name }}**  
@if($businessSettings->business_phone)
📞 {{ $businessSettings->business_phone }}  
@endif
@if($businessSettings->business_email)
✉️ {{ $businessSettings->business_email }}  
@endif
@if($businessSettings->business_address)
📍 {{ $businessSettings->business_address }}
@endif

</x-mail::message>
