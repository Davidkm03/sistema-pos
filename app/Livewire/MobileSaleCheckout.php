<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MobileSaleCheckout extends Component
{
    // Datos de la venta
    public $cartItems = [];
    public $paymentMethod = '';
    public $receivedAmount = 0;
    public $tipAmount = 0;
    public $customerId = null;
    
    // Control del modal
    public $showCheckout = false;
    
    // Resultado de la venta
    public $saleId = null;
    public $showReceipt = false;
    
    protected $rules = [
        'cartItems' => 'required|array|min:1',
        'paymentMethod' => 'required|in:efectivo,tarjeta,link,billetera',
        'receivedAmount' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        // Inicializar
    }

    /**
     * Abrir modal de checkout
     */
    public function openCheckout($items)
    {
        $this->cartItems = $items;
        $this->showCheckout = true;
    }

    /**
     * Cerrar modal de checkout
     */
    public function closeCheckout()
    {
        $this->reset(['paymentMethod', 'receivedAmount', 'tipAmount', 'showCheckout']);
    }

    /**
     * Calcular subtotal
     */
    public function getSubtotalProperty()
    {
        return collect($this->cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    /**
     * Calcular IVA (19%)
     */
    public function getTaxProperty()
    {
        return round($this->subtotal * 0.19);
    }

    /**
     * Calcular total final
     */
    public function getTotalProperty()
    {
        return $this->subtotal + $this->tax + $this->tipAmount;
    }

    /**
     * Calcular cambio (solo para efectivo)
     */
    public function getChangeProperty()
    {
        if ($this->paymentMethod === 'efectivo' && $this->receivedAmount > 0) {
            return $this->receivedAmount - $this->total;
        }
        return 0;
    }

    /**
     * Procesar la venta
     */
    public function processSale()
    {
        // Validar
        $this->validate();

        // Validar monto para efectivo
        if ($this->paymentMethod === 'efectivo') {
            if ($this->receivedAmount < $this->total) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'El monto recibido es insuficiente'
                ]);
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Crear la venta
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_id' => $this->customerId,
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax,
                'total' => $this->total,
                'payment_method' => $this->paymentMethod,
                'status' => 'completada',
                'document_type' => 'receipt',
                'receipt_number' => Sale::getNextReceiptNumber(),
            ]);

            // Crear los items de la venta
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['id']);

                if (!$product) {
                    throw new \Exception("Producto {$item['name']} no encontrado");
                }

                // Verificar stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}. Disponible: {$product->stock}");
                }

                // Crear item de venta
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->getPriceWithoutTax(),
                    'price' => $product->price,
                    'tax_rate' => $product->getEffectiveTaxRate(),
                    'tax_amount' => calculate_tax($item['price'] * $item['quantity'], $product->getEffectiveTaxRate()),
                    'subtotal' => $item['price'] * $item['quantity'],
                    'total' => ($item['price'] * $item['quantity']) + calculate_tax($item['price'] * $item['quantity'], $product->getEffectiveTaxRate()),
                ]);

                // Reducir stock
                $product->decrement('stock', $item['quantity']);
            }

            // Crear detalle de pago
            PaymentDetail::create([
                'sale_id' => $sale->id,
                'payment_method' => $this->paymentMethod,
                'amount' => $this->total,
                'reference' => $this->paymentMethod === 'efectivo' 
                    ? "Efectivo recibido: $" . number_format($this->receivedAmount, 0) 
                    : null,
            ]);

            DB::commit();

            // Guardar ID para el recibo
            $this->saleId = $sale->id;
            
            // Mostrar recibo
            $this->showCheckout = false;
            $this->showReceipt = true;

            // Notificar éxito
            $this->dispatch('sale-completed', saleId: $sale->id);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '¡Venta procesada exitosamente!'
            ]);

            // Vibración de éxito
            $this->dispatch('vibrate', pattern: [100, 50, 100, 50, 200]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al procesar venta móvil', [
                'error' => $e->getMessage(),
                'user' => Auth::id(),
                'cart' => $this->cartItems,
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cerrar recibo y limpiar
     */
    public function closeReceipt()
    {
        $this->reset();
        $this->dispatch('cart-cleared');
    }

    /**
     * Generar PDF del recibo
     */
    public function generatePDF()
    {
        if (!$this->saleId) {
            return;
        }

        // Aquí iría la lógica para generar PDF
        // Por ahora redirigimos a la vista de impresión
        return redirect()->route('sales.print', $this->saleId);
    }

    /**
     * Compartir por WhatsApp
     */
    public function shareWhatsApp()
    {
        if (!$this->saleId) {
            return;
        }

        $sale = Sale::find($this->saleId);
        $message = "🧾 *Recibo de Venta*\n\n";
        $message .= "N°: " . $sale->getFormattedDocumentNumber() . "\n";
        $message .= "Total: $" . number_format((float)$sale->total, 0) . "\n";
        $message .= "Fecha: " . $sale->created_at->format('d/m/Y H:i') . "\n\n";
        $message .= "Gracias por su compra!";

        $whatsappUrl = "https://wa.me/?text=" . urlencode($message);
        
        $this->dispatch('open-url', url: $whatsappUrl);
    }

    public function render()
    {
        return view('livewire.mobile-sale-checkout');
    }
}
