<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PinjepConfig;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'context' => 'nullable|array',
        ]);

        $apiKey = config('services.qwen.api_key');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'Qwen API key is missing. Contact administrator.',
            ], 500);
        }

        $userMessage = $request->input('message');
        $history = $request->input('context', []);

        $messages = [
            [ 'role' => 'system', 'content' => PinjepConfig::systemPrompt() ],
            // Few-shot to shape tone
            [ 'role' => 'user', 'content' => 'Apa jurusan di SMKN 4 Bogor?' ],
            [ 'role' => 'assistant', 'content' => 'Ada empat: Teknik Otomotif (TO), Teknik Pengelasan & Fabrikasi Logam (TPL), Teknik Jaringan Komputer & Telekomunikasi (TJKT), dan Pengembangan Perangkat Lunak & Gim (PPLG). Mau bahas salah satunya?' ],
        ];
        foreach ($history as $turn) {
            if (isset($turn['role'], $turn['content'])) {
                $messages[] = [ 'role' => $turn['role'], 'content' => (string) $turn['content'] ];
            }
        }
        $messages[] = [ 'role' => 'user', 'content' => $userMessage ];

        try {
            // Default ke OpenRouter jika tidak di-set di config
            $endpoint = config('services.qwen.endpoint', 'https://openrouter.ai/api/v1/chat/completions');

            $payload = [
                // Default model diset ke DeepSeek R1 (free) di OpenRouter
                'model' => config('services.qwen.model', 'deepseek/deepseek-r1-0528:free'),
                'messages' => $messages,
                'temperature' => 0.2,
                'max_tokens' => 512,
            ];

            // Default headers
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ];
            // OpenRouter recommended headers (optional)
            if (str_contains($endpoint, 'openrouter.ai')) {
                $headers['HTTP-Referer'] = config('app.url');
                $headers['X-Title'] = config('app.name', 'School Gallery');
            }

            $response = Http::withHeaders($headers)->post($endpoint, $payload);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();
                Log::warning('OpenRouter/Qwen API error', [
                    'status' => $status,
                    'body' => $body,
                    'endpoint' => $endpoint,
                    'model' => $payload['model'] ?? null,
                ]);
                // Pesan error yang lebih ramah untuk kasus umum
                if ($status === 401) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Kunci API OpenRouter/Qwen tidak valid atau tidak diterima. Periksa QWEN_API_KEY di .env.',
                    ], 502);
                }
                if ($status === 400 && str_contains(strtolower($body), 'not a valid model')) {
                    return response()->json([
                        'success' => false,
                        'error' => 'ID model tidak valid. Periksa variabel QWEN_MODEL di .env dan gunakan model yang tersedia di OpenRouter.',
                    ], 502);
                }
                return response()->json([
                    'success' => false,
                    'error' => 'Pinjep lagi sibuk/terputus koneksi. Coba lagi sebentar ya.',
                ], 502);
            }

            $data = $response->json();
            $answer = $data['choices'][0]['message']['content'] ?? null;
            if (!$answer) {
                return response()->json([
                    'success' => false,
                    'error' => 'Respon AI kosong. Coba lagi.',
                ], 502);
            }

            return response()->json([
                'success' => true,
                'answer' => $answer,
            ]);
        } catch (\Throwable $e) {
            Log::error('Chatbot ask error', [ 'error' => $e->getMessage() ]);
            return response()->json([
                'success' => false,
                'error' => 'Pinjep lagi ada kendala. Coba beberapa saat lagi.',
            ], 500);
        }
    }
}


