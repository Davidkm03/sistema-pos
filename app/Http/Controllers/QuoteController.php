<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:quotes.view')->only(['index', 'show']);
        $this->middleware('permission:quotes.create')->only(['create', 'store']);
        $this->middleware('permission:quotes.edit')->only(['edit', 'update']);
        $this->middleware('permission:quotes.delete')->only('destroy');
        $this->middleware('permission:quotes.convert')->only('convertToSale');
    }

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
        $products = Product::where('status', 'active')->orderBy('name')->get();
        
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
        
        return view('quotes.show', compact('quote'));
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
        $products = Product::where('status', 'active')->orderBy('name')->get();
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
                'user_id' => Auth::id(),
                'customer_id' => $quote->customer_id,
                'subtotal' => $quote->subtotal,
                'tax' => $quote->tax,
                'discount' => $quote->discount,
                'total' => $quote->total,
                'payment_method' => 'efectivo', // Valor por defecto
                'status' => 'completed'
            ]);

            // Crear items y descontar inventario
            foreach ($quote->items as $quoteItem) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $quoteItem->product_id,
                    'quantity' => $quoteItem->quantity,
                    'price' => $quoteItem->price,
                    'subtotal' => $quoteItem->subtotal
                ]);

                // Descontar inventario
                $product = Product::find($quoteItem->product_id);
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
}
