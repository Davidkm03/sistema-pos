<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\TicketSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class SaleCart extends Component
{
    public array $cartItems = [];
    public string $paymentMethod = 'efectivo';

    // Tax properties
    public $customer_id = null;
    public $selected_customer = null;
    public $transfer_type = null;
    public $transfer_reference = null;

    // Totals
    public $cartSubtotal = 0;
    public $cartTaxAmount = 0;
    public $cartRetentionAmount = 0;

    #[On('productSelected')]
    public function addProduct($product)
    {
        // Get full product with tax info
        $productModel = Product::find($product['id']);

        if (!$productModel || $productModel->stock <= 0) {
            $this->dispatch('stock-error', message: 'Producto sin stock disponible.');
            return;
        }

        // Buscar si el producto ya está en el carrito
        $existingIndex = null;
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $product['id']) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Si existe, incrementar quantity y recalcular
            $this->cartItems[$existingIndex]['quantity'] += 1;
            $this->recalculateItem($existingIndex);
        } else {
            // Calcular valores con IVA
            $quantity = 1;
            $unitPrice = $productModel->getPriceWithoutTax();
            $taxRate = $productModel->getEffectiveTaxRate();
            $taxAmount = calculate_tax($unitPrice * $quantity, $taxRate);
            $subtotal = $unitPrice * $quantity;
            $total = $subtotal + $taxAmount;

            // Si no existe, agregar nuevo item al carrito
            $this->cartItems[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'], // Precio original para referencia
                'unit_price' => $unitPrice,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'total' => $total,
                'stock' => $product['stock']
            ];
        }

        $this->calculateTotals();
    }

    private function recalculateItem($index)
    {
        $productModel = Product::find($this->cartItems[$index]['id']);
        $quantity = $this->cartItems[$index]['quantity'];
        $unitPrice = $productModel->getPriceWithoutTax();
        $taxRate = $productModel->getEffectiveTaxRate();
        $taxAmount = calculate_tax($unitPrice * $quantity, $taxRate);
        $subtotal = $unitPrice * $quantity;
        $total = $subtotal + $taxAmount;

        $this->cartItems[$index]['unit_price'] = $unitPrice;
        $this->cartItems[$index]['tax_rate'] = $taxRate;
        $this->cartItems[$index]['tax_amount'] = $taxAmount;
        $this->cartItems[$index]['subtotal'] = $subtotal;
        $this->cartItems[$index]['total'] = $total;
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
        if ($this->customer_id && retention_enabled()) {
            $customer = \App\Models\Customer::find($this->customer_id);
            if ($customer && method_exists($customer, 'calculateRetention')) {
                $retentionAmount = $customer->calculateRetention($subtotal + $taxAmount);
            }
        }

        $this->cartSubtotal = $subtotal;
        $this->cartTaxAmount = $taxAmount;
        $this->cartRetentionAmount = $retentionAmount;
    }

    public function removeProduct($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems); // Reindexar array
        $this->calculateTotals();
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeProduct($index);
            return;
        }

        // Verificar si la cantidad excede el stock disponible
        if ($quantity > $this->cartItems[$index]['stock']) {
            $this->dispatch('stock-error', message: 'La cantidad solicitada excede el stock disponible.');
            return;
        }

        // Actualizar quantity y recalcular
        $this->cartItems[$index]['quantity'] = $quantity;
        $this->recalculateItem($index);
        $this->calculateTotals();
    }

    public function getTotal()
    {
        return $this->cartSubtotal + $this->cartTaxAmount - $this->cartRetentionAmount;
    }

    public function completeSale()
    {
        // Validar que el carrito no esté vacío
        if (empty($this->cartItems)) {
            $this->dispatch('sale-error', message: 'El carrito está vacío. Agregue productos antes de completar la venta.');
            return;
        }

        try {
            DB::transaction(function () {
                // Obtener configuración de tickets y negocio
                $ticketSettings = TicketSetting::getSettings();
                $businessSettings = \App\Models\BusinessSetting::current();

                // Determinar tipo de documento según configuración
                $billingType = $businessSettings->billing_type ?? 'simple_receipt';
                $documentType = 'none';
                $receiptNumber = null;
                $invoiceNumber = null;

                if ($billingType === 'simple_receipt') {
                    $documentType = 'receipt';
                    $receiptNumber = Sale::getNextReceiptNumber();
                } elseif ($billingType === 'invoice') {
                    $documentType = 'invoice';
                    $invoiceNumber = Sale::getNextInvoiceNumber();
                } elseif ($billingType === 'electronic_invoice') {
                    $documentType = 'electronic_invoice';
                    $invoiceNumber = Sale::getNextInvoiceNumber();
                }

                // Crear registro en Sale con número de documento e información tributaria
                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $this->customer_id,
                    'subtotal' => $this->cartSubtotal,
                    'tax_amount' => $this->cartTaxAmount,
                    'retention_amount' => $this->cartRetentionAmount,
                    'total' => $this->getTotal(),
                    'payment_method' => $this->paymentMethod,
                    'status' => 'completada',
                    'document_type' => $documentType,
                    'receipt_number' => $receiptNumber,
                    'invoice_number' => $invoiceNumber,
                ]);

                // Crear SaleItem por cada item del carrito y reducir stock
                foreach ($this->cartItems as $item) {
                    // Crear item con información tributaria
                    $saleItemData = [
                        'sale_id' => $sale->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'] ?? $item['price'],
                        'tax_rate' => $item['tax_rate'] ?? 0,
                        'tax_amount' => $item['tax_amount'] ?? 0,
                        'subtotal' => $item['subtotal'] ?? 0,
                        'total' => $item['total'] ?? $item['subtotal'],
                        'price' => $item['total'] ?? $item['subtotal'], // Para compatibilidad
                    ];

                    SaleItem::create($saleItemData);

                    // Reducir stock del producto
                    Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
                }

                // Crear detalle de pago si es necesario
                if ($this->paymentMethod === 'transferencia' && ($this->transfer_type || $this->transfer_reference)) {
                    \App\Models\PaymentDetail::create([
                        'sale_id' => $sale->id,
                        'payment_method' => $this->paymentMethod,
                        'transfer_type' => $this->transfer_type,
                        'transfer_reference' => $this->transfer_reference,
                        'amount' => $this->getTotal(),
                    ]);
                }

                // Limpiar el carrito
                $this->clearCart();

                // Disparar evento de venta completada con información
                $this->dispatch('sale-completed',
                    saleId: $sale->id,
                    receiptNumber: $sale->receipt_number,
                    total: $sale->total
                );

                // Disparar evento de venta completada (para compatibilidad con impresión)
                $this->dispatch('saleCompleted', saleId: $sale->id);
            });
        } catch (\Exception $e) {
            $this->dispatch('sale-error', message: 'Error al completar la venta: ' . $e->getMessage());
        }
    }

    public function clearCart()
    {
        $this->cartItems = [];
        $this->customer_id = null;
        $this->selected_customer = null;
        $this->transfer_type = null;
        $this->transfer_reference = null;
        $this->cartSubtotal = 0;
        $this->cartTaxAmount = 0;
        $this->cartRetentionAmount = 0;
    }

    public function printTicket($saleId)
    {
        $sale = Sale::with(['items.product', 'user'])->findOrFail($saleId);
        $settings = TicketSetting::getSettings();
        $businessSettings = \App\Models\BusinessSetting::current();
        
        $this->dispatch('openTicketPrintWindow', 
            sale: $sale->toArray(),
            settings: $settings->toArray(),
            businessSettings: is_object($businessSettings) ? (array) $businessSettings : $businessSettings->toArray()
        );
    }

    public function render()
    {
        $total = $this->getTotal();
        
        return view('livewire.sale-cart', compact('total'));
    }
}
