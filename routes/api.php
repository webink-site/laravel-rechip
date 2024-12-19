<?php
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramRequestController;
use App\Http\Controllers\Api\AvitoReviewController;
use App\Http\Controllers\TelegramWebhookController;

Route::get('/page', [PageController::class, 'index']);

Route::post('/telegram-requests/submit', [TelegramRequestController::class, 'submit']);
Route::post('/telegram-requests/callback', [TelegramRequestController::class, 'handleCallback']);
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handleWebhook']);

Route::get('/avito/reviews', [AvitoReviewController::class, 'getReviews']);

Route::get('/services', [ServiceController::class, 'index']);

Route::get('/catalog', [CatalogController::class, 'search']);

