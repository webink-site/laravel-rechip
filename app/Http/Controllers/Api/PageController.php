<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Models\Contact;
use App\Models\GalleryWork;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Обработка запроса для различных страниц по slug.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $slug = $request->query('slug');

        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Параметр slug обязателен.'
            ], 400);
        }

        switch ($slug) {
            case 'main':
                return $this->getMainPage();
            case 'cooperation':
                return $this->getCooperationPage();
            case 'gallery':
                return $this->getGallery($request);
            case 'contacts':
                return $this->getContacts();
            case 'services':
                return $this->getServices();
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Страница с таким slug не найдена.'
                ], 404);
        }
    }

    /**
     * Получение полей главной страницы.
     *
     * @return JsonResponse
     */
    private function getMainPage()
    {
        $page = Page::where('slug', 'main')->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Главная страница не найдена.'
            ], 404);
        }

        $page = $page->toArray();
        if($page['title'] === 'Главная'){
            for($i = 0; $i < count($page['content']['advantages']); $i++){
                $page['content']['advantages'][$i]['icon'] = Storage::disk('public')->url($page['content']['advantages'][$i]['icon']);
            }
        }

        return response()->json($page['content']);
    }

    /**
     * Получение данных страницы "Сотрудничество".
     *
     * @return JsonResponse
     */
    private function getCooperationPage()
    {
        $page = Page::where('slug', 'cooperation')->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Страница сотрудничества не найдена.'
            ], 404);
        }

        return response()->json($page->content);
    }

    /**
     * Получение портфолио работ с пагинацией.
     *
     * @param Request $request
     * @return JsonResponse
     */
    private function getGallery(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $size = (int) $request->query('size', 10);

        $gallery = GalleryWork::paginate($size, ['*'], 'page', $page);

        // Формирование постов в нужном формате
        $posts = $gallery->map(function ($item) {
            $gallery = $item->gallery;
            $gallery_new = [];
            foreach ($gallery as $image) {
                $gallery_new[] = Storage::disk('public')->url($image);
            }
            return [
                'id' => $item->id,
                'title' => $item->title,
                'content' => $item->content,
                'date' => $item->date,
                'fields' => [
                    'power_points' => $item->power_points,
                    'tuning_profit' => $item->tuning_profit,
                    'gallery' => $gallery_new,
                    'product_id' => $item->auto_id ? [$item->auto_id] : [],
                ]
            ];
        });

        return response()->json([
            'posts' => $posts,
            'count' => $gallery->total(),
            'page' => $gallery->currentPage(),
            'size' => $gallery->perPage()
        ]);
    }

    /**
     * Получение контактных данных по всем регионам.
     *
     * @return JsonResponse
     */
    private function getContacts()
    {
        $contacts = Contact::all();

        return response()->json($contacts);
    }

    /**
     * Получение информации об основных услугах на сайте.
     *
     * @return JsonResponse
     */
    private function getServices()
    {
        $services = Service::all();

        $services->each(function ($item) {
            $item->image = $item->image ? Storage::disk('public')->url($item->image) : null;
            $item->image_wide = $item->image_wide ? Storage::disk('public')->url($item->image_wide) : null;
            $minimal_prices_new = [];
            foreach($item->minimal_prices as $price){
               $price['icon'] = $price['icon'] ? Storage::disk('public')->url($price['icon']) : null;
               $minimal_prices_new[] = $price;
            }
            $item->minimal_prices = $minimal_prices_new;
            $item->seo_settings = $item->seoSettings;
        });

        return response()->json($services);
    }
}
