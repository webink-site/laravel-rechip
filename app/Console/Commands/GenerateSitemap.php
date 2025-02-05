<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Catalog;
use App\Models\Service;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Configuration;
use App\Models\Engine;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate-txt';
    protected $description = 'Генерация sitemap.txt для каталога';

    public function handle()
    {
        $this->info('Начинаем генерацию sitemap.txt...');

        $baseUrl = '';
        $urls = [];

        // Главная страница
        $urls[] = "{$baseUrl}/";

        // Генерация страниц услуг
        $this->info('Генерация страниц услуг...');
        Service::select('id', 'slug')->distinct()->chunk(100, function ($services) use (&$urls, $baseUrl) {
            foreach ($services as $service) {
                $serviceUrl = "{$baseUrl}/services/{$service->slug}";
                $urls[] = $serviceUrl;

                $this->info('Генерация страниц брендов услуги '. $service->slug);
                // Генерация брендов для каждой услуги
                $this->generateBrands($service, $urls, $baseUrl);
            }
        });

        // Запись URLs в файл
        $filePath = public_path('sitemap.txt');
        file_put_contents($filePath, implode("\n", $urls));

        $this->info("sitemap.txt успешно сгенерирован: {$filePath}");

        return 0;
    }

    protected function generateBrands(Service $service, array &$urls, string $baseUrl)
    {
        $brandIds = Catalog::where('service_id', $service->id)->distinct()->pluck('brand_id');

        Brand::whereIn('id', $brandIds)->select('id', 'slug')->chunk(100, function ($brands) use (&$urls, $service, $baseUrl) {
            foreach ($brands as $brand) {
                $brandUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}";
                $urls[] = $brandUrl;

                $this->info('Генерация страниц модели услуги '. $service->slug .' бренда '. $brand->slug);
                // Генерация моделей для каждой услуги и бренда
                $this->generateModels($service, $brand, $urls, $baseUrl);
            }
        });
    }

    protected function generateModels(Service $service, Brand $brand, array &$urls, string $baseUrl)
    {
        $modelIds = Catalog::where('service_id', $service->id)
            ->where('brand_id', $brand->id)
            ->distinct()
            ->pluck('model_id');

        CarModel::whereIn('id', $modelIds)->select('id', 'slug')->chunk(100, function ($models) use (&$urls, $service, $brand, $baseUrl) {
            foreach ($models as $model) {
                $modelUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}/{$model->slug}";
                $urls[] = $modelUrl;

                $this->info('Генерация страниц конфигураций услуги '. $service->slug .' бренда '. $brand->slug . ' модели ' . $model->slug);
                // Генерация конфигураций для каждой услуги, бренда и модели
                $this->generateConfigurations($service, $brand, $model, $urls, $baseUrl);
            }
        });
    }

    protected function generateConfigurations(Service $service, Brand $brand, CarModel $model, array &$urls, string $baseUrl)
    {
        $configurationIds = Catalog::where('service_id', $service->id)
            ->where('brand_id', $brand->id)
            ->where('model_id', $model->id)
            ->distinct()
            ->pluck('configuration_id');

        Configuration::whereIn('id', $configurationIds)->select('id', 'slug')->chunk(100, function ($configs) use (&$urls, $service, $brand, $model, $baseUrl) {
            foreach ($configs as $config) {
                $configUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}/{$model->slug}/{$config->slug}";
                $urls[] = $configUrl;

                $this->info('Генерация страниц двигателей услуги '. $service->slug .' бренда '. $brand->slug . ' модели ' . $model->slug . ' конфигурации ' . $config->slug );
                // Генерация двигателей для каждой услуги, бренда, модели и конфигурации
                $this->generateEngines($service, $brand, $model, $config, $urls, $baseUrl);
            }
        });
    }

    protected function generateEngines(Service $service, Brand $brand, CarModel $model, Configuration $config, array &$urls, string $baseUrl)
    {
        $engineIds = Catalog::where('service_id', $service->id)
            ->where('brand_id', $brand->id)
            ->where('model_id', $model->id)
            ->where('configuration_id', $config->id)
            ->distinct()
            ->pluck('engine_id');

        Engine::whereIn('id', $engineIds)->select('id', 'slug')->chunk(100, function ($engines) use (&$urls, $service, $brand, $model, $config, $baseUrl) {
            foreach ($engines as $engine) {
                $engineUrl = "{$baseUrl}/services/{$service->slug}/{$brand->slug}/{$model->slug}/{$config->slug}/{$engine->slug}";
                $urls[] = $engineUrl;
            }
        });
    }
}