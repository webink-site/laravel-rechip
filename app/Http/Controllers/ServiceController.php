<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Получить список всех услуг.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Получение всех услуг
        $services = Service::all();

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * Получить детали конкретной услуги.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // Получение услуги по ID с связанными каталогами
        $service = Service::with(['catalogs'])->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Услуга не найдена.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $service,
        ]);
    }
}