<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\TicketSetting;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index(Request $request)
    {
        // Detectar si es dispositivo móvil
        if ($this->isMobileDevice($request)) {
            return redirect()->route('pos.mobile');
        }

        $categories = Category::withCount(['products' => function($query) { 
            $query->where('stock', '>', 0); 
        }])->get();

        $products = Product::where('stock', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->take(12)
            ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    /**
     * Detectar si el dispositivo es móvil
     */
    private function isMobileDevice(Request $request): bool
    {
        $userAgent = $request->header('User-Agent');
        
        // Lista de patrones de user agents móviles
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 
            'BlackBerry', 'Windows Phone', 'Opera Mini', 
            'IEMobile', 'webOS'
        ];
        
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                // Excluir tablets si lo deseas (opcional)
                // if (stripos($userAgent, 'iPad') !== false) {
                //     return false;
                // }
                return true;
            }
        }
        
        return false;
    }

    public function mobile()
    {
        $categories = Category::withCount(['products' => function($query) { 
            $query->where('stock', '>', 0); 
        }])->get();

        $products = Product::where('stock', '>', 0)
            ->with('category')
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('pos.mobile', compact('categories', 'products'));
    }
    
    /**
     * Buscar productos para el POS móvil
     */
    public function searchProducts(Request $request)
    {
        $search = $request->input('search', '');
        $categoryId = $request->input('category_id');
        
        $query = Product::where('stock', '>', 0)
            ->with('category');
        
        // Filtrar por búsqueda
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por categoría
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $products = $query->orderBy('name')
                         ->limit(50)
                         ->get();
        
        return response()->json([
            'success' => true,
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'min_stock' => $product->min_stock,
                    'category_name' => $product->category->name ?? 'Sin categoría',
                    'image' => $product->image,
                ];
            })
        ]);
    }
    
    public function loadMoreProducts(Request $request)
    {
        $offset = $request->input('offset', 12);
        $limit = $request->input('limit', 12);
        
        $products = Product::where('stock', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->skip($offset)
            ->take($limit)
            ->get();
        
        $totalProducts = Product::where('stock', '>', 0)->count();
        $remaining = max(0, $totalProducts - ($offset + $products->count()));
        
        return response()->json([
            'success' => true,
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category_name' => $product->category->name,
                    'image_url' => $product->image_url ?? null,
                ];
            }),
            'remaining' => $remaining,
        ]);
    }
    
    public function procesarVenta(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:efectivo,tarjeta_debito,tarjeta_credito,transferencia',
            'transfer_type' => 'nullable|string',
            'transfer_reference' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id'
        ]);
        
        try {
            DB::beginTransaction();

            // Obtener configuración de negocio
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

            // Crear la venta
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'total' => 0,
                'payment_method' => $request->payment_method,
                'status' => 'completada',
                'document_type' => $documentType,
                'receipt_number' => $receiptNumber,
                'invoice_number' => $invoiceNumber,
            ]);
            
            // Si es transferencia, guardar los detalles adicionales
            if ($request->payment_method === 'transferencia' && ($request->transfer_type || $request->transfer_reference)) {
                PaymentDetail::create([
                    'sale_id' => $sale->id,
                    'payment_method' => 'transferencia',
                    'amount' => 0, // Se actualizará después con el total
                    'transfer_type' => $request->transfer_type,
                    'transfer_reference' => $request->transfer_reference,
                ]);
            }
            
            $total = 0;
            
            // Procesar cada item de la venta
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                // Verificar stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }
                
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                
                // Crear el item de venta
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
                
                // Actualizar stock
                $product->decrement('stock', $item['quantity']);
            }
            
            // Actualizar total de la venta
            $sale->update(['total' => $total]);

            // Si hay un PaymentDetail, actualizar su monto
            if ($request->payment_method === 'transferencia') {
                $paymentDetail = $sale->paymentDetails()->first();
                if ($paymentDetail) {
                    $paymentDetail->update(['amount' => $total]);
                }
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Venta procesada exitosamente',
                'sale_id' => $sale->id,
                'total' => $total
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 400);
        }
    }
    
    public function ticket($id)
    {
        $sale = Sale::with(['items.product', 'user', 'customer', 'paymentDetails'])
                   ->findOrFail($id);
        
        $settings = TicketSetting::getSettings();
        
        return view('sales.ticket', compact('sale', 'settings'));
    }
    
    public function show($id)
    {
        $sale = Sale::with(['items.product', 'user', 'customer', 'paymentDetails'])
                   ->findOrFail($id);
        
        return view('sales.show', compact('sale'));
    }
}
