<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogSearchRequest;
use App\Http\Resources\CatalogResource;
use App\Http\Resources\OptionalServiceResource;
use Illuminate\Http\JsonResponse;
use App\Models\Service;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Configuration;
use App\Models\Engine;
use App\Models\Catalog;

class CatalogController extends Controller
{
    /**
     * Обработать поисковый запрос для каталога.
     *
     * @param CatalogSearchRequest $request
     * @return JsonResponse
     */
    public function search(CatalogSearchRequest $request): JsonResponse
    {
        // Получаем параметры из запроса
        $service_slug        = $request->input('service');
        $catalog_slug        = $request->input('catalog');
        $brand_slug          = $request->input('brand');
        $model_slug          = $request->input('model');
        $configuration_slug  = $request->input('configuration');
        $engine_slug         = $request->input('engine');

        // Находим услугу по слагу
        $service = Service::where('slug', $service_slug)->first();

        // Если указан catalog_slug, используем альтернативный путь
        if ($catalog_slug) {
            // Находим каталог по слагу и услуге
            $catalog = Catalog::with([
                'brand',
                'carModel',
                'configuration',
                'engine',
                'chipTuningParam',
                'catalogOptionalServices.service',
            ])
                ->where('service_id', $service->id)
                ->where('slug', $catalog_slug)
                ->first();

            if (!$catalog) {
                return response()->json(['error' => 'Каталог не найден для предоставленных параметров.'], 404);
            }

            // Получаем опциональные услуги
            $optional_services = OptionalServiceResource::collection($catalog->catalogOptionalServices);

            // Готовим ответ с использованием CatalogResource и включаем optional_services внутри data
            $resource = new CatalogResource($catalog);
            $response = $resource->toArray($request);
            $response['optional_services'] = $optional_services;

            return response()->json(['data' => $response], 200);
        }

        // Иначе, обрабатываем фильтрацию по параметрам
        // Определяем уровень фильтрации
        $filterLevel = 0;
        if ($brand_slug) $filterLevel++;
        if ($model_slug) $filterLevel++;
        if ($configuration_slug) $filterLevel++;
        if ($engine_slug) $filterLevel++;
        if ($engine_slug && !$configuration_slug) $filterLevel++;

        switch ($filterLevel) {
            case 0:
                // Только service - возвращаем список брендов
                $brands = Brand::whereHas('catalogs', function ($query) use ($service) {
                    $query->where('service_id', $service->id);
                })->get(['name', 'slug', 'catalog_image']);

                return response()->json($brands);

            case 1:
                // service + brand - возвращаем список моделей
                if (!$brand_slug) {
                    return response()->json(['error' => 'Необходим бренд для получения моделей.'], 400);
                }

                $brand = Brand::where('slug', $brand_slug)->first();
                if (!$brand) {
                    return response()->json(['error' => 'Бренд не найден.'], 404);
                }

                $models = CarModel::whereHas('catalogs', function ($query) use ($service, $brand) {
                    $query->where('service_id', $service->id)
                        ->where('brand_id', $brand->id);
                })->get(['name', 'slug', 'catalog_image']);

                return response()->json($models);

            case 2:
                // service + brand + model - возвращаем список конфигураций.
                // Если для выбранной модели ни одной конфигурации не найдено, возвращаем сразу данные по двигателям.
                if (!$model_slug) {
                    return response()->json(['error' => 'Необходима модель для получения конфигураций.'], 400);
                }

                $brand = Brand::where('slug', $brand_slug)->first();
                if (!$brand) {
                    return response()->json(['error' => 'Бренд не найден.'], 404);
                }

                $model = CarModel::where('slug', $model_slug)->first();
                if (!$model) {
                    return response()->json(['error' => 'Модель не найдена.'], 404);
                }

                // Получаем конфигурации по записям каталога, где configuration_id заполнена
                $configurations = Configuration::whereHas('catalogs', function ($query) use ($service, $brand, $model) {
                    $query->where('service_id', $service->id)
                        ->where('brand_id', $brand->id)
                        ->where('model_id', $model->id)
                        ->whereNotNull('configuration_id');
                })->get(['name', 'slug']);

                // Получаем записи каталога, где configuration_id = null для получения информации по двигателям
                $catalogsWithoutConfig = Catalog::with('engine')
                    ->where('service_id', $service->id)
                    ->where('brand_id', $brand->id)
                    ->where('model_id', $model->id)
                    ->whereNull('configuration_id')
                    ->get();

                // Группируем каталоги по engine_id
                $groupedCatalogs = $catalogsWithoutConfig->groupBy('engine_id');

                $engineItems = $groupedCatalogs->map(function ($group) {
                    $catalogRecord = $group->first();
                    $engine = $catalogRecord->engine;
                    if ($engine && $engine->volume !== null) {
                        $computedName = $engine->volume . ' ' . $engine->power . ' л.с.';
                    } else {
                        // Если объем не заполнен, берем slug из каталога и преобразуем:
                        $computedName = str_replace('l-s', 'л.с.', $catalogRecord->slug);
                        $computedName = str_replace('-', ' ', $computedName);
                    }
                    return [
                        'slug'   => $engine->slug,
                        'volume' => ($engine && $engine->volume !== null) ? $engine->volume : null,
                        'power'  => ($engine && $engine->power) ? $engine->power : null,
                        'name'   => $computedName,
                    ];
                })->values();

                // Если не найдены конфигурации, сразу возвращаем данные по двигателям
                if ($configurations->isEmpty()) {
                    return response()->json($engineItems);
                }

                // Если конфигурации найдены, преобразуем их в структуру, идентичную структуре engine,
                // но с volume и power равными null. Кроме того, если slug конфигурации заканчивается на "-л-с",
                // то в поле slug ответа кладем slug соответствующего engine.
                $configItems = $configurations->map(function ($configuration) use ($service, $brand, $model) {
                    $configuration_name = $configuration->name;
                    if (preg_match('/-л-с$/u', $configuration->slug)) {
                        $configuration_name = preg_replace('/\s+\S+\s+\S+$/u', '', $configuration->name);
                    }
                    return [
                        'slug'   => $configuration->slug,
                        'name'   => $configuration_name,
                    ];
                });

                // Объединяем результаты: сначала конфигурации, затем данные по двигателям, если таковые имеются
                if ($engineItems->isNotEmpty()) {
                    $result = $configItems->merge($engineItems);
                } else {
                    $result = $configItems;
                }

                return response()->json($result);

            case 3:
                // service + brand + model + configuration - возвращаем список двигателей
                if (!$configuration_slug) {
                    return response()->json(['error' => 'Необходима конфигурация для получения двигателей.'], 400);
                }

                $brand = Brand::where('slug', $brand_slug)->first();
                if (!$brand) {
                    return response()->json(['error' => 'Бренд не найден.'], 404);
                }

                $model = CarModel::where('slug', $model_slug)->first();
                if (!$model) {
                    return response()->json(['error' => 'Модель не найдена.'], 404);
                }

                $configuration = Configuration::where('slug', $configuration_slug)->first();
                if (!$configuration) {
                    // Фолбэк: если конфигурация не найдена, ищем записи каталога,
                    // где configuration_id = null, для бренда, модели и по двигателю (используем configuration_slug как slug двигателя)
                    $engine = Engine::where('slug', $configuration_slug)->first();
                    if (!$engine) {
                        return response()->json(['error' => 'Конфигурация не найдена.'], 404);
                    }
                    $catalog = Catalog::with([
                        'brand',
                        'carModel',
                        'engine',
                        'chipTuningParam',
                        'catalogOptionalServices.service',
                    ])
                        ->where('service_id', $service->id)
                        ->where('brand_id', $brand->id)
                        ->where('model_id', $model->id)
                        ->whereNull('configuration_id')
                        ->where('engine_id', $engine->id)
                        ->first();

                    if (!$catalog) {
                        return response()->json(['error' => 'Каталог не найден для предоставленных параметров.'], 404);
                    }
                    $resource = new CatalogResource($catalog);
                    return response()->json($resource);
                }

                // Получаем записи каталога по заданной конфигурации
                $catalogsForConfig = Catalog::with('engine')
                    ->where('service_id', $service->id)
                    ->where('brand_id', $brand->id)
                    ->where('model_id', $model->id)
                    ->where('configuration_id', $configuration->id)
                    ->get();

                $groupedCatalogs = $catalogsForConfig->groupBy('engine_id');
                $engineItems = $groupedCatalogs->map(function ($group) {
                    $catalogRecord = $group->first();
                    $engine = $catalogRecord->engine;
                    if ($engine && $engine->volume !== null) {
                        $computedName = $engine->volume . ' ' . $engine->power . ' л.с.';
                    } else {
                        $computedName = str_replace('l-s', 'л.с.', $catalogRecord->slug);
                        $computedName = str_replace('-', ' ', $computedName);
                    }
                    return [
                        'slug'   => $engine->slug,
                        'volume' => ($engine && $engine->volume !== null) ? $engine->volume : null,
                        'power'  => ($engine && $engine->power) ? $engine->power : null,
                        'name'   => $computedName,
                    ];
                })->values();

                return response()->json($engineItems);

            case 4:
                // service + brand + model + configuration + engine - возвращаем детальный каталог
                if (!$engine_slug) {
                    return response()->json(['error' => 'Необходим двигатель для получения каталога.'], 400);
                }

                $brand = Brand::where('slug', $brand_slug)->first();
                if (!$brand) {
                    return response()->json(['error' => 'Бренд не найден.'], 404);
                }

                $model = CarModel::where('slug', $model_slug)->first();
                if (!$model) {
                    return response()->json(['error' => 'Модель не найдена.'], 404);
                }

                $configuration = null;
                if ($configuration_slug) {
                    $configuration = Configuration::where('slug', $configuration_slug)->first();
                    if (!$configuration) {
                        return response()->json(['error' => 'Конфигурация не найдена.'], 404);
                    }
                }

                $engine = Engine::where('slug', $engine_slug)->first();
                if (!$engine) {
                    return response()->json(['error' => 'Двигатель не найден.'], 404);
                }

                // Ищем соответствующий каталог
                $catalog = Catalog::with([
                    'brand',
                    'carModel',
                    'configuration',
                    'engine',
                    'chipTuningParam',
                    'catalogOptionalServices.service',
                ])
                    ->where('service_id', $service->id)
                    ->where('brand_id', $brand->id)
                    ->where('model_id', $model->id)
                    ->where('configuration_id', $configuration?->id)
                    ->where('engine_id', $engine->id)
                    ->first();

                if (!$catalog) {
                    return response()->json(['error' => 'Каталог не найден для предоставленных параметров.'], 404);
                }

                // Готовим итоговый ответ с использованием CatalogResource и включаем optional_services внутри data
                $resource = new CatalogResource($catalog);

                return response()->json($resource);

            default:
                return response()->json(['error' => 'Неверная комбинация параметров.'], 400);
        }
    }
}