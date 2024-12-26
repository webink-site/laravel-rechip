<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
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
        // Получение всех услуг с SEO-настройками
        $services = Service::with(["seoSettings"])->get();

        return response()->json([
            'success' => true,
            'data'    => ServiceResource::collection($services),
        ]);
    }
}