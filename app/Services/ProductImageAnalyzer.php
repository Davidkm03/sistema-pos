<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductImageAnalyzer
{
    protected $apiKey;
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Analizar una imagen de producto y extraer información
     * 
     * @param string $imageBase64 Imagen en formato base64
     * @return array|null Información del producto extraída
     */
    public function analyzeProductImage($imageBase64)
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => 'gpt-4o-mini', // Modelo más económico con visión
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un experto en identificación de productos para sistemas de punto de venta. Analiza la imagen y extrae información del producto de forma precisa y concisa en español.'
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Analiza esta imagen de producto y proporciona la siguiente información en formato JSON:
{
  "nombre": "Nombre corto del producto (máximo 50 caracteres)",
  "descripcion": "Descripción detallada del producto incluyendo marca, tamaño, características principales",
  "categoria_sugerida": "Categoría apropiada (Alimentos, Bebidas, Electrónica, Hogar, Ropa, Otros)",
  "precio_estimado": "Precio estimado en pesos colombianos (solo el número, sin símbolo)",
  "codigo_barras": "Código de barras si es visible, null si no",
  "confianza": "Nivel de confianza del análisis (alta, media, baja)"
}

Proporciona SOLO el JSON, sin texto adicional.'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:image/jpeg;base64,{$imageBase64}"
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.3, // Baja temperatura para respuestas más precisas
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Unexpected OpenAI response format', ['data' => $data]);
                return null;
            }

            $content = $data['choices'][0]['message']['content'];
            
            // Extraer JSON del contenido (por si viene con markdown)
            if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
                $jsonContent = $matches[0];
                $productInfo = json_decode($jsonContent, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $productInfo;
                }
            }

            Log::error('Failed to parse JSON from OpenAI response', ['content' => $content]);
            return null;

        } catch (\Exception $e) {
            Log::error('Error analyzing product image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Verificar si el servicio está configurado y disponible
     * 
     * @return bool
     */
    public function isAvailable()
    {
        return !empty($this->apiKey);
    }
}
