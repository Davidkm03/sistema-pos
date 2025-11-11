<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyReportService
{
    protected $empresaId;

    public function __construct()
    {
        // Get empresa_id from authenticated user if available
        if (Auth::check()) {
            $this->empresaId = Auth::user()->empresa_id;
        }
    }

    /**
     * Set empresa_id manually (for scheduled tasks)
     */
    public function setEmpresaId($empresaId)
    {
        $this->empresaId = $empresaId;
        return $this;
    }

    /**
     * Get today's sales summary for the empresa
     */
    public function getSalesToday()
    {
        $today = Carbon::today();
        
        $query = Sale::whereDate('created_at', $today)
            ->where('status', 'completed');
        
        // Filter by empresa_id if set
        if ($this->empresaId) {
            $query->where('empresa_id', $this->empresaId);
        }
        
        $sales = $query->get();

        return [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('total'),
            'average_ticket' => $sales->avg('total'),
            'sales' => $sales
        ];
    }

    /**
     * Get profit estimation for today
     */
    public function getProfitToday()
    {
        $today = Carbon::today();
        
        $query = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'completed');
        
        // Filter by empresa_id if set
        if ($this->empresaId) {
            $query->where('sales.empresa_id', $this->empresaId);
        }
        
        $profitData = $query->select(
                DB::raw('SUM(sale_items.price * sale_items.quantity) as revenue'),
                DB::raw('SUM(products.cost * sale_items.quantity) as cost')
            )
            ->first();

        $revenue = $profitData->revenue ?? 0;
        $cost = $profitData->cost ?? 0;
        $profit = $revenue - $cost;
        $margin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

        return [
            'revenue' => $revenue,
            'cost' => $cost,
            'profit' => $profit,
            'margin_percent' => round($margin, 1)
        ];
    }

    /**
     * Get products with low stock (less than 10 units)
     */
    public function getLowStockProducts($threshold = 10)
    {
        return Product::where('stock', '>', 0)
            ->where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get(['id', 'name', 'stock', 'price', 'cost']);
    }

    /**
     * Get products at risk of running out tomorrow based on today's sales velocity
     */
    public function getProductsRiskTomorrow()
    {
        $today = Carbon::today();
        
        // Get sales velocity (units sold today)
        $query = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'completed');
        
        // Filter by empresa_id if set
        if ($this->empresaId) {
            $query->where('sales.empresa_id', $this->empresaId);
        }
        
        $velocityData = $query->select(
                'products.id',
                'products.name',
                'products.stock',
                DB::raw('SUM(sale_items.quantity) as units_sold_today')
            )
            ->groupBy('products.id', 'products.name', 'products.stock')
            ->having('units_sold_today', '>', 0)
            ->get();

        // Filter products that will run out tomorrow at current velocity
        $atRisk = $velocityData->filter(function ($item) {
            return $item->stock <= ($item->units_sold_today * 1.5); // 1.5x safety margin
        })->take(3);

        return $atRisk;
    }

    /**
     * Detect frequent product combinations (combos)
     */
    public function getFrequentCombos($minOccurrences = 3)
    {
        // Get sales from last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        // Find products frequently bought together
        $query = DB::table('sale_items as si1')
            ->join('sale_items as si2', 'si1.sale_id', '=', 'si2.sale_id')
            ->join('products as p1', 'si1.product_id', '=', 'p1.id')
            ->join('products as p2', 'si2.product_id', '=', 'p2.id')
            ->join('sales', 'si1.sale_id', '=', 'sales.id')
            ->where('si1.product_id', '<', 'si2.product_id') // Avoid duplicates (A-B vs B-A)
            ->where('sales.created_at', '>=', $thirtyDaysAgo)
            ->where('sales.status', 'completed');
        
        // Filter by empresa_id if set
        if ($this->empresaId) {
            $query->where('sales.empresa_id', $this->empresaId);
        }
        
        $combos = $query->select(
                'p1.id as product1_id',
                'p1.name as product1_name',
                'p1.price as product1_price',
                'p2.id as product2_id',
                'p2.name as product2_name',
                'p2.price as product2_price',
                DB::raw('COUNT(*) as times_together')
            )
            ->groupBy('p1.id', 'p1.name', 'p1.price', 'p2.id', 'p2.name', 'p2.price')
            ->having('times_together', '>=', $minOccurrences)
            ->orderBy('times_together', 'desc')
            ->limit(3)
            ->get();

        return $combos->map(function ($combo) {
            $individualPrice = $combo->product1_price + $combo->product2_price;
            $suggestedComboPrice = round($individualPrice * 0.92); // 8% discount

            return [
                'product1' => $combo->product1_name,
                'product2' => $combo->product2_name,
                'times_together' => $combo->times_together,
                'individual_price' => $individualPrice,
                'suggested_combo_price' => $suggestedComboPrice,
                'savings' => $individualPrice - $suggestedComboPrice
            ];
        });
    }

    /**
     * Generate AI recommendation using OpenAI GPT-4o-mini
     */
    public function generateAIRecommendation(array $salesData, array $profitData, $lowStock, $atRisk)
    {
        $apiKey = config('services.openai.api_key');
        
        if (!$apiKey) {
            return "OpenAI no configurado. Activa la API en settings.";
        }

        $prompt = $this->buildPromptForAI($salesData, $profitData, $lowStock, $atRisk);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un asesor experto en retail y gestión de inventarios en Colombia. Da recomendaciones cortas, prácticas y en español.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 200,
                    'temperature' => 0.7
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Sin recomendación IA';
            }

            return "Error OpenAI: " . $response->status();
            
        } catch (\Exception $e) {
            return "Error IA: " . $e->getMessage();
        }
    }

    /**
     * Build prompt for AI based on business data
     */
    private function buildPromptForAI($salesData, $profitData, $lowStock, $atRisk)
    {
        $lowStockNames = $lowStock->pluck('name')->take(3)->implode(', ');
        $atRiskNames = $atRisk->pluck('name')->implode(', ');

        $prompt = "Analisis de negocio hoy:\n\n";
        $prompt .= "Ventas: {$salesData['total_sales']} transacciones, ${$salesData['total_revenue']}\n";
        $prompt .= "Margen: {$profitData['margin_percent']}%\n";
        
        if ($lowStockNames) {
            $prompt .= "Stock bajo: {$lowStockNames}\n";
        }
        
        if ($atRiskNames) {
            $prompt .= "Se agotaran manana: {$atRiskNames}\n";
        }

        $prompt .= "\nDa UNA recomendacion practica en maximo 2 lineas para mejorar ventas o inventario manana.";

        return $prompt;
    }

    /**
     * Format full WhatsApp report message
     */
    public function formatWhatsAppMessage(
        array $salesData,
        array $profitData,
        $lowStock,
        $atRisk,
        $combos,
        string $aiRecommendation
    ): string {
        $currency = '$';
        $message = "*REPORTE DIARIO*\n\n";

        // Sales summary
        $message .= "*Ventas de hoy*\n";
        $message .= "• Total vendido: {$currency}" . number_format($salesData['total_revenue'], 0) . "\n";
        $message .= "• Transacciones: {$salesData['total_sales']}\n";
        $message .= "• Ticket promedio: {$currency}" . number_format($salesData['average_ticket'], 0) . "\n\n";

        // Profit
        $message .= "*Ganancias*\n";
        $message .= "• Utilidad estimada: {$currency}" . number_format($profitData['profit'], 0) . "\n";
        $message .= "• Margen: {$profitData['margin_percent']}%\n\n";

        // Products at risk
        if ($atRisk->isNotEmpty()) {
            $message .= "*Se agotaran manana*\n";
            foreach ($atRisk as $product) {
                $message .= "• {$product->name} (quedan {$product->stock})\n";
            }
            $message .= "\n";
        }

        // Low stock
        if ($lowStock->isNotEmpty()) {
            $message .= "*Stock bajo*\n";
            foreach ($lowStock->take(3) as $product) {
                $message .= "• {$product->name}: {$product->stock} unidades\n";
            }
            $message .= "\n";
        }

        // Frequent combos
        if ($combos->isNotEmpty()) {
            $message .= "*Combos sugeridos* (compran juntos)\n";
            foreach ($combos as $combo) {
                $message .= "• {$combo['product1']} + {$combo['product2']}\n";
                $message .= "  {$combo['times_together']} veces - Precio combo: {$currency}" . number_format($combo['suggested_combo_price'], 0) . "\n";
            }
            $message .= "\n";
        }

        // AI recommendation
        $message .= "*Sugerencia IA*\n";
        $message .= $aiRecommendation . "\n\n";

        $message .= "---\n";
        $message .= "_Reporte automatico - " . now()->format('d/m/Y H:i') . "_";

        return $message;
    }

    /**
     * Generate WhatsApp Web URL (no API required)
     */
    public function getWhatsAppWebUrl(string $phoneNumber, string $message): string
    {
        // Clean phone number (remove spaces, dashes, etc.)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure it starts with country code (57 for Colombia if not present)
        if (!str_starts_with($cleanPhone, '57') && strlen($cleanPhone) === 10) {
            $cleanPhone = '57' . $cleanPhone;
        }

        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
    }
}
