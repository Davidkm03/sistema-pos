<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductVoiceService
{
    private $apiKey;
    private $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Check if OpenAI API is available
     */
    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Process spoken text and extract product data
     * 
     * @param string $spokenText Text transcribed from voice
     * @return array|null Product data or null if failed
     */
    public function extractProductData(string $spokenText): ?array
    {
        if (!$this->isAvailable()) {
            Log::warning('OpenAI API key not configured');
            return null;
        }

        try {
            $systemPrompt = $this->getSystemPrompt();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $spokenText
                    ]
                ],
                'temperature' => 0.3, // Low temperature for consistent extraction
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['choices'][0]['message']['content'] ?? null;
                
                if ($content) {
                    // Parse JSON response
                    $productData = json_decode($content, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $this->validateAndCleanData($productData);
                    }
                }
            } else {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing voice input', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }

    /**
     * Get system prompt for product extraction
     */
    private function getSystemPrompt(): string
    {
        return <<<PROMPT
Eres un asistente que extrae datos de productos desde texto hablado en español colombiano.

TAREA:
Extraer: nombre, categoría, precio de venta, costo y stock inicial.

REGLAS:
1. Los precios pueden estar en palabras: "dos mil quinientos" = 2500, "mil" = 1000
2. Cantidades en palabras: "cincuenta" = 50, "cien" = 100
3. Categorías comunes: Bebidas, Snacks, Comidas, Lácteos, Aseo, Panadería, Dulces, Otros
4. Si falta algún dato, usar null
5. El nombre puede incluir tamaño/presentación (500ml, 1L, etc)
6. Stock por defecto es 0 si no se menciona

FORMATO DE SALIDA:
Devuelve SOLO un objeto JSON válido sin explicaciones:
{
  "nombre": "string",
  "categoria": "string",
  "precio": number,
  "costo": number,
  "stock": number
}

EJEMPLOS:

Entrada: "Coca Cola 500ml categoría bebidas precio dos mil quinientos costo mil quinientos stock cincuenta"
Salida: {"nombre":"Coca Cola 500ml","categoria":"Bebidas","precio":2500,"costo":1500,"stock":50}

Entrada: "Papas Margarita de snacks vale mil quinientos el costo es ochocientos tengo treinta"
Salida: {"nombre":"Papas Margarita","categoria":"Snacks","precio":1500,"costo":800,"stock":30}

Entrada: "Pan tajado panadería tres mil dos mil hay veinte"
Salida: {"nombre":"Pan Tajado","categoria":"Panadería","precio":3000,"costo":2000,"stock":20}

Entrada: "Leche entera"
Salida: {"nombre":"Leche Entera","categoria":"Lácteos","precio":null,"costo":null,"stock":0}

IMPORTANTE:
- Si el usuario menciona "vale", "precio", "cuesta" → es precio de venta
- Si menciona "costo", "me cuesta", "compro a" → es costo
- Si menciona "hay", "tengo", "stock" → es stock
- Capitaliza el nombre del producto
- Categoría con primera letra mayúscula
PROMPT;
    }

    /**
     * Validate and clean extracted data
     */
    private function validateAndCleanData(array $data): array
    {
        return [
            'nombre' => !empty($data['nombre']) ? trim($data['nombre']) : null,
            'categoria' => !empty($data['categoria']) ? ucfirst(strtolower(trim($data['categoria']))) : null,
            'precio' => isset($data['precio']) && is_numeric($data['precio']) ? (float)$data['precio'] : null,
            'costo' => isset($data['costo']) && is_numeric($data['costo']) ? (float)$data['costo'] : null,
            'stock' => isset($data['stock']) && is_numeric($data['stock']) ? (int)$data['stock'] : 0,
        ];
    }
}
