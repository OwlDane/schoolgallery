<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            [ 'role' => 'system', 'content' => 'You are an assistant for a school gallery website. Answer briefly and helpfully about this site and general education.' ],
        ];
        foreach ($history as $turn) {
            if (isset($turn['role'], $turn['content'])) {
                $messages[] = [ 'role' => $turn['role'], 'content' => (string) $turn['content'] ];
            }
        }
        $messages[] = [ 'role' => 'user', 'content' => $userMessage ];

        try {
            $endpoint = config('services.qwen.endpoint', 'https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions');

            $payload = [
                'model' => config('services.qwen.model', 'qwen-plus'),
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
                Log::warning('Qwen API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal mengambil respon dari AI. Coba lagi nanti.',
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
                'error' => 'Terjadi kesalahan. Coba lagi nanti.',
            ], 500);
        }
    }
}


