<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductVoiceService;
use Illuminate\Support\Facades\Auth;

class VoiceProductCreator extends Component
{
    public $voiceTranscript = '';
    public $voiceProcessing = false;
    public $voiceExtractedData = null;
    public $showModal = false;

    protected $listeners = ['openVoiceModal' => 'open'];

    public function open()
    {
        $this->showModal = true;
        $this->reset(['voiceTranscript', 'voiceExtractedData']);
    }

    public function transcribeAudio($base64Audio)
    {
        try {
            $this->voiceProcessing = true;

            // Decode base64 audio
            $audioData = base64_decode($base64Audio);
            
            // Save temporarily
            $tempPath = storage_path('app/temp_audio_' . uniqid() . '.webm');
            file_put_contents($tempPath, $audioData);

            // Call Whisper API
            $whisperService = new \App\Services\WhisperService();
            $transcript = $whisperService->transcribe($tempPath);

            // Delete temp file
            unlink($tempPath);

            if (!$transcript) {
                throw new \Exception('No se pudo transcribir el audio');
            }

            $this->voiceTranscript = $transcript;
            $this->voiceProcessing = false;

        } catch (\Exception $e) {
            $this->voiceProcessing = false;
            $this->dispatch('voice-error', message: 'Error transcribiendo: ' . $e->getMessage());
        }
    }

    public function close()
    {
        $this->showModal = false;
        $this->reset(['voiceTranscript', 'voiceExtractedData', 'voiceProcessing']);
    }

    public function processVoiceInput()
    {
        if (empty($this->voiceTranscript)) {
            $this->dispatch('voice-error', message: 'No hay texto para procesar');
            return;
        }

        try {
            $this->voiceProcessing = true;
            
            $voiceService = new ProductVoiceService();
            $extractedData = $voiceService->extractProductData($this->voiceTranscript);
            
            if (!$extractedData) {
                throw new \Exception('No se pudo extraer información del producto');
            }

            $this->voiceExtractedData = $extractedData;
            $this->voiceProcessing = false;

        } catch (\Exception $e) {
            $this->voiceProcessing = false;
            $this->dispatch('voice-error', message: $e->getMessage());
        }
    }

    public function createFromVoice()
    {
        if (!$this->voiceExtractedData) {
            $this->dispatch('voice-error', message: 'No hay datos para crear el producto');
            return;
        }

        try {
            $empresaId = Auth::user()->empresa_id;
            $data = $this->voiceExtractedData;

            // Buscar categoría existente similar o crear nueva
            $categoryName = $data['categoria'];
            
            // Buscar categoría exacta primero
            $category = Category::where('empresa_id', $empresaId)
                ->where('name', $categoryName)
                ->first();
            
            // Si no existe, buscar similar (case-insensitive, con % LIKE)
            if (!$category) {
                $category = Category::where('empresa_id', $empresaId)
                    ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($categoryName) . '%'])
                    ->first();
            }
            
            // Si aún no existe, crear nueva
            if (!$category) {
                $category = Category::create([
                    'name' => $categoryName,
                    'empresa_id' => $empresaId,
                    'description' => 'Categoría creada automáticamente por voz'
                ]);
            }

            // Generar SKU automático
            $lastProduct = Product::where('empresa_id', $empresaId)
                ->orderBy('id', 'desc')
                ->first();
            
            $nextNumber = $lastProduct ? (intval(substr($lastProduct->sku, -4)) + 1) : 1;
            $sku = 'EMP' . $empresaId . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Crear producto
            $product = Product::create([
                'name' => $data['nombre'],
                'sku' => $sku,
                'category_id' => $category->id,
                'price' => $data['precio'],
                'cost' => $data['costo'] ?? 0,
                'stock' => $data['stock'] ?? 0,
                'empresa_id' => $empresaId,
                'tax_included' => true
            ]);

            $this->dispatch('voice-product-created', productName: $product->name);
            $this->dispatch('product-created'); // Evento para refrescar tabla
            $this->close();

        } catch (\Exception $e) {
            $this->dispatch('voice-error', message: 'Error al crear producto: ' . $e->getMessage());
        }
    }

    public function cancelVoiceInput()
    {
        $this->reset(['voiceTranscript', 'voiceExtractedData', 'voiceProcessing']);
    }

    public function render()
    {
        return view('livewire.voice-product-creator');
    }
}
