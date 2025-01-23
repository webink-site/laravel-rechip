<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Catalog;
use App\Models\Service;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Configuration;
use App\Models\Engine;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Генерация sitemap.xml с полной структурой каталога';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Базовый URL вашего сайта
        $baseUrl = config('app.url');

        // Добавление главной страницы
        $sitemap->add(Url::create($baseUrl)
            ->setLastModificationDate(now())
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // 1. Генерация страниц услуг
        $this->info('Генерация страниц услуг...');
        Service::select('id', 'slug')->distinct()->chunk(100, function ($services) use ($sitemap, $baseUrl) {
            foreach ($services as $service) {
                $serviceUrl = "{$baseUrl}/services/{$service->slug}";
                $sitemap->add(Url::create($serviceUrl)
                    ->setLastModificationDate($service->updated_at ?? now())
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

                $this->info('Генерация брендов услуги ' . $service->slug);
                // 2. Генерация страниц брендов для каждой услуги
                $this->generateBrands($service, $sitemap, $baseUrl);
            }
        });

        // Сохранение sitemap.xml в публичную директорию
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml успешно сгенерирован.');
    }

    /**
     * Генерация страниц брендов для конкретной услуги
     */
    protected function generateBrands(Service $service, Sitemap $sitemap, string $baseUrl)
    {
        // Получение уникальных brand_id для данной услуги
        $brandIds = Catalog::where('service_id', $service->id)->distinct()->pluck('brand_id');

        // Получение соответствующих брендов
        Brand::whereIn('id', $brandIds)->select('id', 'slug')->chunk(100, function ($brands) use ($service, $sitemap, $baseUrl) {
            foreach ($brands as $brand) {
                $brandUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}";
                $sitemap->add(Url::create($brandUrl)
                    ->setLastModificationDate($brand->updated_at ?? now())
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

                $this->info('Генерация моделей услуги ' . $service->slug . ' бренда ' . $brand->slug );
                // 3. Генерация страниц моделей для каждой услуги и бренда
                $this->generateModels($service, $brand, $sitemap, $baseUrl);
            }
        });
    }

    /**
     * Генерация страниц моделей для конкретной услуги и бренда
     */
    protected function generateModels(Service $service, Brand $brand, Sitemap $sitemap, string $baseUrl)
    {
        // Получение уникальных model_id для данной услуги и бренда
        $modelIds = Catalog::where('service_id', $service->id)
            ->where('brand_id', $brand->id)
            ->distinct()
            ->pluck('model_id');

        // Получение соответствующих моделей
        CarModel::whereIn('id', $modelIds)->select('id', 'slug')->chunk(100, function ($models) use ($service, $brand, $sitemap, $baseUrl) {
            foreach ($models as $model) {
                $modelUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}/{$model->slug}";
                $sitemap->add(Url::create($modelUrl)
                    ->setLastModificationDate($model->updated_at ?? now())
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

                $this->info('Генерация конфигурации услуги ' . $service->slug . ' бренда ' . $brand->slug . ' модели ' . $model->slug );
                // 4. Генерация страниц конфигураций для каждой услуги, бренда и модели
                $this->generateConfigurations($service, $brand, $model, $sitemap, $baseUrl);
            }
        });
    }

    /**
     * Генерация страниц конфигураций для конкретной услуги, бренда и модели
     */
    protected function generateConfigurations(Service $service, Brand $brand, CarModel $model, Sitemap $sitemap, string $baseUrl)
    {
        // Получение уникальных configuration_id для данной услуги, бренда и модели
        $configurationIds = Catalog::where('service_id', $service->id)
            ->where('brand_id', $brand->id)
            ->where('model_id', $model->id)
            ->distinct()
            ->pluck('configuration_id');

        // Получение соответствующих конфигураций
        Configuration::whereIn('id', $configurationIds)->select('id', 'slug')->chunk(100, function ($configurations) use ($service, $brand, $model, $sitemap, $baseUrl) {
            foreach ($configurations as $config) {
                $configUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}/{$model->slug}/{$config->slug}";
                $sitemap->add(Url::create($configUrl)
                    ->setLastModificationDate($config->updated_at ?? now())
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

                $this->info('Генерация двигателей услуги ' . $service->slug . ' бренда ' . $brand->slug . ' модели ' . $model->slug . ' конфигурации ' . $config->slug);
                // 5. Генерация страниц двигателей для каждой услуги, бренда, модели и конфигурации
                $this->generateEngines($service, $brand, $model, $config, $sitemap, $baseUrl);
            }
        });
    }

    /**
     * Генерация страниц двигателей для конкретной услуги, бренда, модели и конфигурации
     */
    protected function generateEngines(Service $service, Brand $brand, CarModel $model, Configuration $config, Sitemap $sitemap, string $baseUrl)
    {
        // Получение уникальных engine_id для данной услуги, бренда, модели и конфигурации
        $engineIds = Catalog::where('service_id', $service->id)
            ->where('brand_id', $brand->id)
            ->where('model_id', $model->id)
            ->where('configuration_id', $config->id)
            ->distinct()
            ->pluck('engine_id');

        // Получение соответствующих двигателей
        Engine::whereIn('id', $engineIds)->select('id', 'slug')->chunk(100, function ($engines) use ($service, $brand, $model, $config, $sitemap, $baseUrl) {
            foreach ($engines as $engine) {
                $engineUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}/{$model->slug}/{$config->slug}/{$engine->slug}";
                $sitemap->add(Url::create($engineUrl)
                    ->setLastModificationDate($engine->updated_at ?? now())
                    ->setPriority(0.4)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        });
    }
}