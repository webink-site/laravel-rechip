<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Auto;
use Illuminate\Support\Facades\Http;

class AutoController extends Controller
{
    /**
     * Обработка запроса для работы с автомобилями.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request)
    {
        // Получение параметров из запроса
        $mark_id = $request->query('mark_id');
        $model_id = $request->query('model_id');
        $generation_id = $request->query('generation_id');
        $product_id = $request->query('product_id');

        if ($product_id) {
            return $this->getCarByProductId($product_id);
        }

        if ($mark_id && $model_id && $generation_id) {
            return $this->getCarsByGeneration($generation_id, $model_id, $mark_id);
        }

        if ($model_id) {
            return $this->getSubCategories($model_id, $mark_id);
        }

        if ($mark_id) {
            return $this->getChildCategories($mark_id);
        }

        return $this->getTopLevelCategories();
    }

    /**
     * Получение данных об автомобиле по product_id (SKU).
     *
     * @param string $product_id
     * @return JsonResponse
     */
    private function getCarByProductId($product_id)
    {
        // Поиск автомобиля по SKU
        $car = Auto::where('carbase_modification_id', $product_id)->first();

        if (!$car) {
            return response()->json([
                'error' => 'Товар не найден'
            ], 404);
        }

        // Получение связанных услуг (основных и дополнительных)
        $services = $this->formatServices($car);

        // Формирование итогового ответа
        return response()->json([
            'modification' => $this->getModificationFromExternalApi($car),
            'services' => $services,
        ]);
    }

    /**
     * Форматирование связанных с авто услуг
     *
     * @param Auto $car
     * @return array
     */
    private function formatServices($car)
    {
        // Основные услуги
        $mainServices = $car->services()->get()->map(function ($service) {
            return [
                'service' => $service->id,
                'price' => $service->pivot->price . ' ₽'
            ];
        })->toArray();

        // Дополнительные услуги
        $additionalServices = $car->additionalServices()->get()->map(function ($service) {
            return [
                'ID' => $service->id,
                'name' => $service->service_name,
                'description' => $service->description,
                'image' => $service->image,
                'price' => $service->pivot->price . ' ₽'
            ];
        })->toArray();

        // Получаем данные для "этапов" из поля stages_increase_params
        $stages = $car->stages_increase_params ?? [];

        // Формирование итоговой структуры
        return [
            'ID' => $car->id,
            'name' => $car->auto_full_name,
            'main_services' => $mainServices,
            'additional_services' => $additionalServices,
            'stage' => $stages,  // Используем данные из поля stages_increase_params
        ];
    }


    /**
     * Запрос к внешнему API для получения данных о модификации.
     *
     * @param Auto $car
     * @return array|null
     */
    private function getModificationFromExternalApi(Auto $car)
    {
        $base_url = 'https://cars-base.ru/api/cars';
        $api_key = 'd1e353ef7'; // API-ключ

        // Формируем URL для запроса к внешнему API
        $url = "{$base_url}/" . urlencode($car->mark_id) . "/" . urlencode($car->model_id) . "/" . urlencode($car->generation_id) . "?key={$api_key}";

        // Выполняем HTTP-запрос
        $response = Http::get($url);

        // Проверяем успешность запроса
        if ($response->failed()) {
            return null;
        }

        // Декодируем JSON-ответ
        $data = $response->json();

        // Поиск модификации, соответствующей product_id
        foreach ($data as $car_data) {
            foreach ($car_data['modifications'] as $modification) {
                if ($modification['complectation-id'] === $car->carbase_modification_id) {
                    return $modification;
                }
            }
        }

        return null; // Если модификация не найдена
    }

    /**
     * Получение автомобилей по generation_id, model_id и mark_id.
     *
     * @param int $generation_id
     * @param int $model_id
     * @param int $mark_id
     * @return JsonResponse
     */
    private function getCarsByGeneration($generation_id, $model_id, $mark_id)
    {
        $generation_id = str_replace('-', ' - ', $generation_id);

        // Поиск автомобилей по указанным параметрам
        $cars = Auto::where('generation', $generation_id)
            ->where('model_id', $model_id)
            ->where('mark_id', $mark_id)
            ->select('configuration', 'carbase_modification_id', 'modification')
            ->get();

        $response = [];

        // Получаем уникальные конфигурации
        $configurations = $cars->pluck('configuration')->unique();

        // Формируем ответ
        foreach ($configurations as $configuration) {
            // Для каждой конфигурации добавляем только уникальные автомобили по имени (modification)
            $response[$configuration] = $cars->filter(function ($car) use ($configuration) {
                return $car->configuration === $configuration;
            })->unique('modification') // Уникальные по полю 'modification'
            ->map(function ($car) {
                return [
                    'id' => $car->carbase_modification_id,
                    'name' => $car->modification,
                ];
            })->values()->all(); // Применяем values() чтобы сбросить ключи
        }

        return response()->json($response);
    }


    /**
     * Получение дочерних категорий для указанной модели.
     *
     * @param int $model_id
     * @param int|null $mark_id
     * @return JsonResponse
     */
    private function getSubCategories($model_id, $mark_id = null)
    {
        // Поиск подкатегорий (например, модификации для модели)
        $subCategories = Auto::where('model_id', $model_id)
            ->when($mark_id, function($query) use ($mark_id) {
                return $query->where('mark_id', $mark_id);
            })
            ->select('generation')
            ->distinct()
            ->get();

        $response = [];
        foreach ($subCategories as $subCategory) {
            $response[] = [
                'id' => str_replace(' ', '', strtoupper($subCategory->generation)),
                'name' => $subCategory->generation,
                'year-from' => explode(' - ', $subCategory->generation)[0],
                'year-to' => explode(' - ', $subCategory->generation)[1] ?? null,
                'is-restyle' => false,
                'path' => [
                    'mark-id' => str_replace(' ', '-', strtoupper($mark_id)),
                    'model-id' => str_replace(' ', '-', strtoupper($model_id)),
                ],
                'configurations' => count(Auto::where('mark_id', $mark_id)->where('model_id', $model_id)->where('generation', $subCategory->generation)->select('configuration')->distinct()->get()),  // Пример
            ];
        }

        return response()->json($response);
    }

    /**
     * Получение дочерних категорий для указанного марка (mark_id).
     *
     * @param int $mark_id
     * @return JsonResponse
     */
    private function getChildCategories($mark_id)
    {
        // Поиск моделей для указанной марки
        $models = Auto::where('mark_id', $mark_id)
            ->select('model', 'model_id')
            ->distinct()
            ->get();

        $response = [];
        foreach ($models as $model) {
            $response[] = [
                'id' => $model->model_id,
                'name' => $model->model,
                'cyrillic-name' => "", // Можно заменить реальными данными
                'class' => "",         // Можно заменить реальными данными
                'year-from' => null,
                'year-to' => null,
                'path' => [
                    'mark-id' => $mark_id,
                ],
                'generations' => count(Auto::where('mark_id', $mark_id)->where('model_id', $model->model_id)->select('generation')->distinct()->get()),  // Пример
            ];
        }

        return response()->json($response);
    }

    /**
     * Получение топ-уровневых категорий.
     *
     * @return JsonResponse
     */
    private function getTopLevelCategories()
    {
        // Поиск всех брендов (марок)
        $marks = Auto::select('brand', 'mark_id')
            ->orderBy('brand', 'asc')
            ->distinct()
            ->get();

        $response = [];
        foreach ($marks as $mark) {
            $response[] = [
                'id' => $mark->mark_id,
                'name' => $mark->brand,
                'cyrillic-name' => '', // Можно заменить реальными данными
                'models' => count(Auto::where('mark_id',$mark->mark_id)->select('model')->distinct()->get()),
            ];
        }

        return response()->json($response);
    }

    /**
     * Получение данных об услугах по товару (product_id).
     *
     * @param string $modification_id
     * @return array
     */
    private function getProductServices($modification_id)
    {
        // Поиск автомобиля по carbase_modification_id
        $car = Auto::where('carbase_modification_id', $modification_id)->first();

        if (!$car) {
            return ['error' => 'Product not found'];
        }

        // Получение дополнительных услуг
        $additionalServices = $car->additionalServices()->get();
        $additionalServicesFormatted = $additionalServices->map(function ($service) {
            return [
                "ID" => $service->id,
                "name" => $service->service_name,
                "description" => $service->description,
                "image" => $service->image,
                "price" => $service->pivot->price,
            ];
        });

        // Получение основных услуг
        $mainServices = $car->services()->get();
        $mainServicesFormatted = $mainServices->map(function ($service) {
            return [
                "ID" => $service->id,
                "name" => $service->service_name,
                "short_description" => $service->short_description,
                "description" => $service->description,
                "post_title" => $service->post_title,
                "page_content" => $service->page_content,
                "image" => $service->image,
                "image_wide" => $service->image_wide,
                "minimal_prices" => $service->minimal_prices,
            ];
        });

        // Формирование итоговых данных
        return [
            'ID' => $car->id,
            'auto_full_name' => $car->auto_full_name,
            'services' => $mainServicesFormatted,
            'additional_services' => $additionalServicesFormatted,
        ];
    }
}
