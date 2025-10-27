# Sistema Tributario Colombiano - POS

## ✅ COMPLETADO

### 1. Migraciones de Base de Datos
- ✅ `business_settings` - Configuración tributaria (IVA, retenciones)
- ✅ `products` - Tipos de IVA por producto (standard, exempt, excluded)
- ✅ `sales` - Desglose fiscal (subtotal, tax_amount, retention_amount)
- ✅ `sale_items` - IVA por item vendido
- ✅ `customers` - Información tributaria (NIT, régimen, retención)
- ✅ `payment_details` - Métodos de pago mixtos y transferencias

### 2. Modelos Actualizados
- ✅ `Product` - Métodos de cálculo de IVA
- ✅ `Customer` - Validación de NIT y retención
- ✅ `Sale` - Cálculos fiscales y totales
- ✅ `SaleItem` - Desglose de impuestos
- ✅ `PaymentDetail` - Nuevo modelo para pagos

### 3. Helper de Funciones Tributarias
- ✅ `calculate_tax()` - Calcular IVA
- ✅ `calculate_price_with_tax()` - Precio con IVA
- ✅ `calculate_price_without_tax()` - Extraer precio sin IVA
- ✅ `calculate_retention()` - Calcular retención
- ✅ `validate_nit()` - Validar NIT con dígito verificador
- ✅ `format_nit()` - Formatear NIT
- ✅ Funciones auxiliares para listas y configuración

## 📋 PENDIENTE DE IMPLEMENTACIÓN

### 4. Componentes Livewire

#### A. BusinessSettingsManager - Configuración Tributaria

Agregar al componente `BusinessSettingsManager.php`:

```php
// Propiedades tributarias
public $tax_enabled = false;
public $tax_name = 'IVA';
public $tax_rate = 19.00;
public $tax_included_in_price = false;
public $retention_enabled = false;
public $retention_rate = 3.5;
public $applies_retention_from = 800000;
public $tax_id_required = false;
public $business_tax_regime = 'simplified';
```

Agregar a `mount()`:
```php
if ($settings) {
    $this->tax_enabled = $settings->tax_enabled;
    $this->tax_name = $settings->tax_name;
    $this->tax_rate = $settings->tax_rate;
    $this->tax_included_in_price = $settings->tax_included_in_price;
    $this->retention_enabled = $settings->retention_enabled;
    $this->retention_rate = $settings->retention_rate;
    $this->applies_retention_from = $settings->applies_retention_from;
    $this->tax_id_required = $settings->tax_id_required;
    $this->business_tax_regime = $settings->business_tax_regime;
}
```

Agregar a `save()`:
```php
$data['tax_enabled'] = $this->tax_enabled;
$data['tax_name'] = $this->tax_name;
$data['tax_rate'] = $this->tax_rate;
$data['tax_included_in_price'] = $this->tax_included_in_price;
$data['retention_enabled'] = $this->retention_enabled;
$data['retention_rate'] = $this->retention_rate;
$data['applies_retention_from'] = $this->applies_retention_from;
$data['tax_id_required'] = $this->tax_id_required;
$data['business_tax_regime'] = $this->business_tax_regime;
```

Vista blade - Agregar después de la sección actual:
```blade
<!-- Sección de Configuración Tributaria -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">⚖️ Configuración Tributaria</h2>
    
    <!-- Activar IVA -->
    <div class="mb-4">
        <label class="flex items-center">
            <input type="checkbox" wire:model="tax_enabled" class="mr-2">
            <span class="font-medium">Activar IVA</span>
        </label>
    </div>

    @if($tax_enabled)
        <!-- Nombre del impuesto -->
        <div class="mb-4">
            <label class="block mb-2">Nombre del Impuesto</label>
            <input type="text" wire:model="tax_name" class="w-full border rounded px-3 py-2">
        </div>

        <!-- Tasa general -->
        <div class="mb-4">
            <label class="block mb-2">Tasa General de IVA (%)</label>
            <input type="number" step="0.01" wire:model="tax_rate" class="w-full border rounded px-3 py-2">
        </div>

        <!-- Precios incluyen IVA -->
        <div class="mb-4">
            <label class="block mb-2">¿Los precios incluyen IVA?</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" wire:model="tax_included_in_price" value="1" class="mr-2">
                    <span>Sí, los precios YA incluyen IVA</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" wire:model="tax_included_in_price" value="0" class="mr-2">
                    <span>No, el IVA se suma al precio</span>
                </label>
            </div>
        </div>

        <!-- Retenciones -->
        <div class="border-t pt-4 mt-4">
            <label class="flex items-center mb-4">
                <input type="checkbox" wire:model="retention_enabled" class="mr-2">
                <span class="font-medium">Activar Retenciones en la Fuente</span>
            </label>

            @if($retention_enabled)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">Tasa de Retención (%)</label>
                        <input type="number" step="0.01" wire:model="retention_rate" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2">Aplicar desde ($)</label>
                        <input type="number" wire:model="applies_retention_from" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            @endif
        </div>

        <!-- Requisitos -->
        <div class="border-t pt-4 mt-4">
            <label class="flex items-center">
                <input type="checkbox" wire:model="tax_id_required" class="mr-2">
                <span class="font-medium">Exigir NIT del cliente para vender</span>
            </label>
        </div>

        <!-- Régimen del negocio -->
        <div class="mt-4">
            <label class="block mb-2">Régimen Tributario del Negocio</label>
            <select wire:model="business_tax_regime" class="w-full border rounded px-3 py-2">
                <option value="simplified">Régimen Simplificado</option>
                <option value="common">Régimen Común</option>
            </select>
        </div>

        <!-- Ayuda -->
        <div class="bg-blue-50 border border-blue-200 rounded p-4 mt-4">
            <h3 class="font-semibold mb-2">📘 Información</h3>
            <ul class="text-sm space-y-1">
                <li><strong>Exento:</strong> Productos con IVA 0% pero con derecho a descontar IVA</li>
                <li><strong>Excluido:</strong> Productos con IVA 0% SIN derecho a descontar IVA</li>
                <li><strong>Retención:</strong> Se aplica a ventas mayores al monto configurado con clientes en régimen común</li>
            </ul>
        </div>
    @endif
</div>
```

#### B. ProductManager - Gestión de IVA por Producto

Agregar propiedades:
```php
public $tax_type = 'standard';
public $custom_tax_rate = null;
```

En el formulario de producto, agregar:
```blade
<!-- Configuración de IVA -->
@if(tax_enabled())
    <div class="mb-4">
        <label class="block mb-2">Tipo de IVA</label>
        <select wire:model="tax_type" class="w-full border rounded px-3 py-2">
            <option value="standard">Estándar ({{ get_tax_rate() }}%)</option>
            <option value="exempt">Exento (0%) - Con derecho</option>
            <option value="excluded">Excluido (0%) - Sin derecho</option>
            <option value="custom">Tasa Personalizada</option>
        </select>
    </div>

    @if($tax_type === 'custom')
        <div class="mb-4">
            <label class="block mb-2">Tasa Personalizada (%)</label>
            <input type="number" step="0.01" wire:model="custom_tax_rate" class="w-full border rounded px-3 py-2">
            <p class="text-sm text-gray-500 mt-1">Ej: 5 para productos de canasta básica</p>
        </div>
    @endif

    <!-- Mostrar precio con/sin IVA -->
    <div class="bg-gray-50 border rounded p-3">
        @if(tax_included_in_price())
            <p class="text-sm">Precio ingresado: <strong>{{ format_currency($price) }}</strong> (IVA incluido)</p>
        @else
            <p class="text-sm">Precio ingresado: <strong>{{ format_currency($price) }}</strong></p>
            <p class="text-sm">Precio final: <strong>{{ format_currency(calculate_price_with_tax($price)) }}</strong> (con IVA)</p>
        @endif
    </div>
@endif
```

#### C. SaleCart - Cálculos en POS

Este es el componente más complejo. Necesitarás:

1. **Propiedades adicionales:**
```php
public $customer_id = null;
public $selected_customer = null;
public $payment_method = 'efectivo';
public $transfer_type = null;
public $transfer_reference = null;
```

2. **Método para agregar producto con IVA:**
```php
public function addProduct($productId)
{
    $product = Product::find($productId);
    
    if (!$product || $product->stock <= 0) {
        return;
    }

    $quantity = 1;
    $unitPrice = $product->getPriceWithoutTax();
    $taxRate = $product->getEffectiveTaxRate();
    $taxAmount = calculate_tax($unitPrice * $quantity, $taxRate);
    $subtotal = $unitPrice * $quantity;
    $total = $subtotal + $taxAmount;

    $this->cartItems[] = [
        'product_id' => $product->id,
        'name' => $product->name,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'tax_rate' => $taxRate,
        'tax_amount' => $taxAmount,
        'subtotal' => $subtotal,
        'total' => $total,
    ];

    $this->calculateTotals();
}

public function calculateTotals()
{
    $subtotal = 0;
    $taxAmount = 0;
    
    foreach ($this->cartItems as $item) {
        $subtotal += $item['subtotal'];
        $taxAmount += $item['tax_amount'];
    }

    $retentionAmount = 0;
    if ($this->selected_customer && retention_enabled()) {
        $customer = Customer::find($this->customer_id);
        if ($customer) {
            $retentionAmount = $customer->calculateRetention($subtotal + $taxAmount);
        }
    }

    $this->cartTotal = $subtotal + $taxAmount - $retentionAmount;
    $this->cartSubtotal = $subtotal;
    $this->cartTaxAmount = $taxAmount;
    $this->cartRetentionAmount = $retentionAmount;
}
```

3. **Vista del carrito con desglose:**
```blade
<!-- Resumen de Totales -->
<div class="bg-white rounded-lg shadow p-4">
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span>Subtotal (sin IVA):</span>
            <span class="font-semibold">{{ format_currency($cartSubtotal) }}</span>
        </div>
        
        <div class="flex justify-between">
            <span>IVA (19%):</span>
            <span class="font-semibold">{{ format_currency($cartTaxAmount) }}</span>
        </div>
        
        <div class="border-t pt-2 flex justify-between">
            <span>Subtotal + IVA:</span>
            <span class="font-semibold">{{ format_currency($cartSubtotal + $cartTaxAmount) }}</span>
        </div>
        
        @if($cartRetentionAmount > 0)
            <div class="flex justify-between text-red-600">
                <span>Retención (-3.5%):</span>
                <span class="font-semibold">-{{ format_currency($cartRetentionAmount) }}</span>
            </div>
        @endif
        
        <div class="border-t pt-2 flex justify-between text-lg">
            <span class="font-bold">TOTAL A PAGAR:</span>
            <span class="font-bold text-green-600">{{ format_currency($cartTotal) }}</span>
        </div>
    </div>
</div>

<!-- Métodos de Pago -->
<div class="mt-4">
    <label class="block mb-2">Método de Pago</label>
    <select wire:model="payment_method" class="w-full border rounded px-3 py-2">
        <option value="efectivo">Efectivo</option>
        <option value="tarjeta_debito">Tarjeta Débito</option>
        <option value="tarjeta_credito">Tarjeta Crédito</option>
        <option value="transferencia">Transferencia</option>
    </select>

    @if($payment_method === 'transferencia')
        <div class="mt-2">
            <select wire:model="transfer_type" class="w-full border rounded px-3 py-2">
                <option value="">Seleccionar tipo...</option>
                <option value="nequi">Nequi</option>
                <option value="daviplata">Daviplata</option>
                <option value="bancolombia">Bancolombia</option>
                <option value="llave">Llave (PSE)</option>
                <option value="otro">Otro</option>
            </select>
            
            <input type="text" wire:model="transfer_reference" 
                   placeholder="Número de referencia (opcional)"
                   class="w-full border rounded px-3 py-2 mt-2">
        </div>
    @endif
</div>
```

4. **Método para completar venta:**
```php
public function completeSale()
{
    // Crear la venta
    $sale = Sale::create([
        'user_id' => auth()->id(),
        'customer_id' => $this->customer_id,
        'subtotal' => $this->cartSubtotal,
        'tax_amount' => $this->cartTaxAmount,
        'retention_amount' => $this->cartRetentionAmount,
        'total' => $this->cartTotal,
        'payment_method' => $this->payment_method,
        'status' => 'completada',
    ]);

    // Crear los items con IVA
    foreach ($this->cartItems as $item) {
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['unit_price'],
            'tax_rate' => $item['tax_rate'],
            'tax_amount' => $item['tax_amount'],
            'subtotal' => $item['subtotal'],
            'total' => $item['total'],
            'price' => $item['total'], // Para compatibilidad
        ]);

        // Reducir stock
        $product = Product::find($item['product_id']);
        $product->decrement('stock', $item['quantity']);
    }

    // Crear detalle de pago
    PaymentDetail::create([
        'sale_id' => $sale->id,
        'payment_method' => $this->payment_method,
        'transfer_type' => $this->transfer_type,
        'transfer_reference' => $this->transfer_reference,
        'amount' => $this->cartTotal,
    ]);

    // Limpiar carrito
    $this->reset(['cartItems', 'customer_id', 'payment_method']);
    
    return redirect()->route('ticket', ['sale' => $sale->id]);
}
```

### 5. Vista de Tickets

Modificar la vista del ticket para mostrar:

```blade
<!-- Header -->
<div class="text-center mb-4">
    <h2>{{ setting('business_name') }}</h2>
    <p>NIT: {{ setting('business_tax_id') }}</p>
    <p>{{ setting('business_tax_regime') === 'simplified' ? 'Régimen Simplificado' : 'Régimen Común' }}</p>
</div>

<!-- Cliente -->
@if($sale->customer)
<div class="mb-4">
    <strong>Cliente:</strong> {{ $sale->customer->name }}<br>
    <strong>{{ $sale->customer->tax_id_type }}:</strong> {{ $sale->customer->getFormattedTaxId() }}<br>
    <strong>Régimen:</strong> {{ $sale->customer->getTaxRegimeName() }}
</div>
@endif

<!-- Items -->
<table class="w-full mb-4">
    <thead>
        <tr class="border-b">
            <th>Producto</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>IVA</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ format_currency($item->unit_price) }}</td>
            <td>{{ format_currency($item->tax_amount) }}</td>
            <td>{{ format_currency($item->total) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Totales -->
<div class="border-t pt-2">
    <div class="flex justify-between">
        <span>Subtotal:</span>
        <span>{{ format_currency($sale->subtotal) }}</span>
    </div>
    
    @foreach($sale->getTaxBreakdown() as $tax)
        <div class="flex justify-between">
            <span>IVA {{ $tax['rate'] }}%:</span>
            <span>{{ format_currency($tax['tax']) }}</span>
        </div>
    @endforeach
    
    @if($sale->retention_amount > 0)
        <div class="flex justify-between text-red-600">
            <span>Retención 3.5%:</span>
            <span>-{{ format_currency($sale->retention_amount) }}</span>
        </div>
    @endif
    
    <div class="flex justify-between font-bold text-lg border-t mt-2 pt-2">
        <span>TOTAL:</span>
        <span>{{ format_currency($sale->total) }}</span>
    </div>
</div>

<!-- Método de Pago -->
<div class="mt-4">
    <strong>Pago:</strong> {{ $sale->getPaymentInfo() }}
</div>
```

### 6. Comando de Migración de Datos

Crear: `php artisan make:command MigrateTaxData`

```php
<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Console\Command;

class MigrateTaxData extends Command
{
    protected $signature = 'tax:migrate-data';
    protected $description = 'Migrar datos existentes al nuevo sistema tributario';

    public function handle()
    {
        $this->info('Iniciando migración de datos tributarios...');

        // Actualizar productos sin tipo de IVA
        $products = Product::whereNull('tax_type')->get();
        foreach ($products as $product) {
            $product->update([
                'tax_type' => 'standard',
                'tax_rate' => null, // Usará la tasa general
            ]);
        }
        $this->info("Productos actualizados: {$products->count()}");

        // Actualizar ventas antiguas
        $sales = Sale::where('subtotal', 0)->get();
        foreach ($sales as $sale) {
            $sale->load('saleItems.product');
            
            // Recalcular con IVA
            foreach ($sale->saleItems as $item) {
                if ($item->product) {
                    $unitPrice = $item->product->getPriceWithoutTax();
                    $taxRate = $item->product->getEffectiveTaxRate();
                    $taxAmount = calculate_tax($unitPrice * $item->quantity, $taxRate);
                    $subtotal = $unitPrice * $item->quantity;
                    
                    $item->update([
                        'unit_price' => $unitPrice,
                        'tax_rate' => $taxRate,
                        'tax_amount' => $taxAmount,
                        'subtotal' => $subtotal,
                        'total' => $subtotal + $taxAmount,
                    ]);
                }
            }
            
            $sale->updateTotals();
        }
        $this->info("Ventas actualizadas: {$sales->count()}");

        $this->info('¡Migración completada!');
    }
}
```

## 🚀 PASOS PARA ACTIVAR EL SISTEMA

1. **Ejecutar migraciones** (ya hecho):
   ```bash
   php artisan migrate
   ```

2. **Migrar datos existentes**:
   ```bash
   php artisan tax:migrate-data
   ```

3. **Configurar el sistema**:
   - Ir a Configuración > Configuración Tributaria
   - Activar IVA
   - Configurar tasa general (19%)
   - Elegir si precios incluyen IVA
   - Configurar retenciones si aplica
   - Agregar NIT del negocio

4. **Configurar productos**:
   - Revisar cada producto
   - Asignar tipo de IVA correcto
   - Para productos con IVA diferente, usar tasa personalizada

5. **Probar en POS**:
   - Agregar productos al carrito
   - Verificar cálculos de IVA
   - Seleccionar cliente
   - Verificar retención si aplica
   - Completar venta
   - Imprimir ticket

## 📊 REPORTES PENDIENTES (Opcional)

- Reporte de IVA por período
- Reporte de retenciones
- Certificados de retención en PDF
- Exportación para DIAN

## 🎯 CARACTERÍSTICAS IMPLEMENTADAS

✅ Configuración flexible de IVA  
✅ Múltiples tasas de IVA por producto  
✅ Productos exentos y excluidos  
✅ Retención en la fuente automática  
✅ Validación de NIT colombiano  
✅ Métodos de pago detallados  
✅ Pagos mixtos (opcional)  
✅ Tickets con desglose fiscal  
✅ Compatibilidad con datos anteriores  

## 💡 NOTAS IMPORTANTES

- El sistema es completamente opcional (tax_enabled = false)
- Mantiene compatibilidad con ventas sin IVA
- Los cálculos se hacen en tiempo real
- Los datos históricos se preservan
- Se puede cambiar entre precios con/sin IVA
- La retención se calcula automáticamente
