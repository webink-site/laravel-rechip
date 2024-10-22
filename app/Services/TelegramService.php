<?php

namespace App\Services;

use Telegram\Bot\Api as TelegramApi;

class TelegramService
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new TelegramApi(config('telegram.bot_token'));
    }

    public function sendMessage($chatId, $text, $keyboard = null)
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($keyboard) {
            $params['reply_markup'] = json_encode($keyboard);
        }

        return $this->telegram->sendMessage($params);
    }

    public function editMessageText($chatId, $messageId, $text, $parseMode = 'HTML')
    {
        return $this->telegram->editMessageText([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => $parseMode,
        ]);
    }

    public function deleteMessage($chatId, $messageId)
    {
        return $this->telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);
    }
}
