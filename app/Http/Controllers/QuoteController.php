<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\BusinessSetting;
use App\Mail\QuoteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Quote::with(['customer', 'user', 'items']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $quotes = $query->latest()->paginate(20);

        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('quotes.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'valid_until' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calcular totales
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $taxEnabled = setting('tax_enabled', false);
            $taxRate = $taxEnabled ? setting('tax_rate', 19) / 100 : 0;
            $tax = round($subtotal * $taxRate, 2);
            $discount = $request->discount ?? 0;
            $total = $subtotal + $tax - $discount;

            // Crear cotización
            $quote = Quote::create([
                'empresa_id' => Auth::user()->empresa_id,
                'quote_number' => Quote::generateQuoteNumber(),
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'valid_until' => $request->valid_until,
                'notes' => $request->notes,
                'status' => 'pendiente'
            ]);

            // Crear items
            foreach ($request->items as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price']
                ]);
            }

            DB::commit();

            return redirect()->route('quotes.show', $quote)
                ->with('success', 'Cotización creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear cotización: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        $quote->load(['customer', 'user', 'items.product', 'convertedSale']);
        $businessSettings = BusinessSetting::current();
        
        return view('quotes.show', compact('quote', 'businessSettings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        if ($quote->status !== 'pendiente') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Solo se pueden editar cotizaciones pendientes.');
        }

        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $quote->load('items.product');
        
        return view('quotes.edit', compact('quote', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        if ($quote->status !== 'pendiente') {
            return back()->with('error', 'Solo se pueden editar cotizaciones pendientes.');
        }

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'required|in:pendiente,aprobada,rechazada',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calcular totales
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $taxEnabled = setting('tax_enabled', false);
            $taxRate = $taxEnabled ? setting('tax_rate', 19) / 100 : 0;
            $tax = round($subtotal * $taxRate, 2);
            $discount = $request->discount ?? 0;
            $total = $subtotal + $tax - $discount;

            // Actualizar cotización
            $quote->update([
                'customer_id' => $request->customer_id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => $request->status,
                'valid_until' => $request->valid_until,
                'notes' => $request->notes,
            ]);

            // Eliminar items antiguos y crear nuevos
            $quote->items()->delete();
            foreach ($request->items as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price']
                ]);
            }

            DB::commit();

            return redirect()->route('quotes.show', $quote)
                ->with('success', 'Cotización actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar cotización: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        if ($quote->status === 'convertida') {
            return back()->with('error', 'No se puede eliminar una cotización ya convertida a venta.');
        }

        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Cotización eliminada exitosamente.');
    }

    /**
     * Convertir cotización a venta
     */
    public function convertToSale(Quote $quote)
    {
        if (!$quote->isConvertible()) {
            return back()->with('error', 'Esta cotización no se puede convertir a venta.');
        }

        DB::beginTransaction();
        try {
            // Crear venta
            $sale = Sale::create([
                'empresa_id' => Auth::user()->empresa_id,
                'user_id' => Auth::id(),
                'customer_id' => $quote->customer_id,
                'subtotal' => $quote->subtotal,
                'tax_amount' => $quote->tax,
                'discount_amount' => $quote->discount,
                'total' => $quote->total,
                'payment_method' => 'efectivo', // Valor por defecto
                'status' => 'completada',
                'document_type' => 'receipt',
                'receipt_number' => Sale::getNextReceiptNumber(),
            ]);

            // Crear items y descontar inventario
            foreach ($quote->items as $quoteItem) {
                $product = Product::find($quoteItem->product_id);
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $quoteItem->product_id,
                    'quantity' => $quoteItem->quantity,
                    'unit_price' => $product->getPriceWithoutTax(),
                    'price' => $quoteItem->price,
                    'tax_rate' => $product->getEffectiveTaxRate(),
                    'tax_amount' => calculate_tax($quoteItem->subtotal, $product->getEffectiveTaxRate()),
                    'subtotal' => $quoteItem->subtotal,
                    'total' => $quoteItem->subtotal + calculate_tax($quoteItem->subtotal, $product->getEffectiveTaxRate()),
                ]);

                // Descontar inventario
                $product->decrement('stock', $quoteItem->quantity);
            }

            // Actualizar cotización
            $quote->update([
                'status' => 'convertida',
                'converted_to_sale_id' => $sale->id,
                'converted_at' => now()
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Cotización convertida a venta exitosamente. Se descontó el inventario.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al convertir cotización: ' . $e->getMessage());
        }
    }

    /**
     * Imprimir cotización
     */
    public function print(Quote $quote)
    {
        $quote->load(['customer', 'user', 'items.product']);
        
        return view('quotes.print', compact('quote'));
    }

    /**
     * Imprimir cotización en formato POS (impresora térmica)
     */
    public function printPos(Quote $quote)
    {
        $quote->load(['customer', 'user', 'items.product', 'convertedSale']);
        
        return view('quotes.print-pos', compact('quote'));
    }

    /**
     * Enviar cotización por email
     */
    public function sendEmail(Request $request, Quote $quote)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Obtener configuración SMTP global (del super-admin)
            $smtpSettings = BusinessSetting::smtp();

            // Validar que la configuración SMTP esté completa
            if (!$smtpSettings) {
                $message = 'El servidor SMTP no está configurado. Contacte al administrador del sistema.';
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }
                
                return back()->with('error', $message);
            }

            // Obtener configuración del negocio del usuario actual (para datos en el email)
            $businessSettings = BusinessSetting::current();

            // Configurar SMTP dinámicamente desde configuración global
            Config::set('mail.mailers.smtp.host', $smtpSettings->smtp_host);
            Config::set('mail.mailers.smtp.port', $smtpSettings->smtp_port ?? 587);
            Config::set('mail.mailers.smtp.encryption', $smtpSettings->smtp_encryption ?? 'tls');
            Config::set('mail.mailers.smtp.username', $smtpSettings->smtp_username);
            Config::set('mail.mailers.smtp.password', $smtpSettings->smtp_password);
            Config::set('mail.from.address', $smtpSettings->smtp_from_address);
            Config::set('mail.from.name', $smtpSettings->smtp_from_name ?? $businessSettings->business_name);

            // Cargar relaciones necesarias
            $quote->load(['customer', 'user', 'items.product']);

            // Enviar email
            Mail::to($request->email)->send(new QuoteMail($quote, $businessSettings));

            $message = 'Cotización enviada exitosamente a ' . $request->email;
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error al enviar cotización por email: ' . $e->getMessage());
            
            $message = 'Error al enviar email: ' . $e->getMessage();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return back()->with('error', $message);
        }
    }
}
