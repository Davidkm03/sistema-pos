<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhisperService
{
    private $apiKey;
    private $model = 'whisper-1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Transcribe audio file using OpenAI Whisper API
     * 
     * @param string $audioFilePath Absolute path to audio file
     * @return string|null Transcribed text or null on error
     */
    public function transcribe(string $audioFilePath): ?string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])
            ->attach('file', file_get_contents($audioFilePath), basename($audioFilePath))
            ->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => $this->model,
                'language' => 'es', // Spanish
                'response_format' => 'json',
            ]);

            if ($response->failed()) {
                \Log::error('Whisper API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();
            return $data['text'] ?? null;

        } catch (\Exception $e) {
            \Log::error('Whisper transcription error: ' . $e->getMessage());
            return null;
        }
    }
}
