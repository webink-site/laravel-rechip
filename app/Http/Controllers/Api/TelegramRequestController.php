<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelegramRequest;
use App\Models\TelegramRegion;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Api as TelegramApi;

class TelegramRequestController extends Controller
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new TelegramApi(config('telegram.bot_token'));
    }

    public function submit(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'region_code' => 'required|string|max:10',
            'request_data' => 'required|string',
            'contact' => 'required|string',
            'product' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Некорректные данные заявки', 'messages' => $validator->errors()], 422);
        }

        // Сохранение заявки
        $telegramRequest = TelegramRequest::create([
            'request_data' => $this->decodeUnicode($request->input('request_data')),
            'contact' => $request->input('contact'),
            'product' => $request->input('product') ? $request->input('product') : '',
            'region_code' => $request->input('region_code'),
            'status' => 'new',
        ]);

        // Маршрутизация заявки по регионам
        $region = TelegramRegion::where('region_code', $telegramRequest->region_code)->first();
        if ($region) {
            $manager = TelegramUser::where('username', $region->telegram_account)->first();
            if ($manager) {
                $text = "<b>Контакт: </b>" . e($telegramRequest->contact) . "\n";
                if ($telegramRequest->product) {
                    $text .= "<b>Продукт: </b>" . e($telegramRequest->product) . "\n";
                }
                $text .= e($telegramRequest->request_data);

                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Спам', 'callback_data' => "spam_{$telegramRequest->id}"],
                            ['text' => 'Обработать', 'callback_data' => "completed_{$telegramRequest->id}"],
                        ]
                    ]
                ];

                $sendStatus = $this->telegram->sendMessage([
                    'chat_id' => $manager->user_id,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode($keyboard),
                ]);

                if (!$sendStatus->getOk()) {
                    $telegramRequest->update(['status' => 'error']);
                    return response()->json(['error' => 'Ошибка отправки сообщения'], 500);
                }
            }
        } else {
            return response()->json(['error' => 'Неправильный регион'], 422);
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
            return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16BE');
        }, $str);
    }
}
