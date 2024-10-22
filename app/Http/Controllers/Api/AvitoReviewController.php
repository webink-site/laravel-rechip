<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AvitoReviewController extends Controller
{
    /**
     * Обработчик запроса на получение отзывов
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getReviews(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);

        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                return response()->json(['error' => 'Unable to get access token'], 500);
            }

            $reviews = $this->getAvitoReviews($accessToken, $limit, $offset);

            if (!$reviews) {
                return response()->json(['error' => 'Unable to get reviews'], 500);
            }

            return response()->json($reviews, 200);
        } catch (\Exception $e) {
            // Логируем ошибку для отладки
            Log::error('Avito API error: ' . $e->getMessage());

            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    /**
     * Получение access token для Avito API
     *
     * @return string|null
     */
    private function getAccessToken(): ?string
    {
        // Используем кэширование для хранения токена на время его жизни
        return Cache::remember('avito_access_token', 3600, function () {
            $response = Http::asForm()->post(config('services.avito.token_url'), [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.avito.client_id'),
                'client_secret' => config('services.avito.client_secret'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            // Логируем ошибку для отладки
            Log::error('Failed to retrieve Avito access token.', [
                'response_body' => $response->body(),
            ]);

            return null;
        });
    }

    /**
     * Получение отзывов с Avito API
     *
     * @param string $accessToken
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    private function getAvitoReviews(string $accessToken, int $limit, int $offset): ?array
    {
        $response = Http::withToken($accessToken)->get(config('services.avito.reviews_url'), [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        // Логируем ошибку для отладки
        Log::error('Failed to retrieve Avito reviews.', [
            'status' => $response->status(),
            'response_body' => $response->body(),
        ]);

        return null;
    }
}