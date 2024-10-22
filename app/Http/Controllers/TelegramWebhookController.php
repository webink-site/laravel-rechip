<?php

namespace App\Http\Controllers;

use App\Models\TelegramRequest;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Telegram\Bot\Api as TelegramApi;

class TelegramWebhookController extends Controller
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new TelegramApi(config('telegram.bot_token'));
    }

    public function handleWebhook(Request $request)
    {
        $update = $this->telegram->getWebhookUpdates();

        if ($update->isType('callback_query')) {
            $callback = $update->getCallbackQuery();
            $data = $callback->getData();
            $chatId = $callback->getMessage()->getChat()->getId();
            $messageId = $callback->getMessage()->getMessageId();

            if (strpos($data, 'spam_') === 0) {
                $requestId = intval(substr($data, 5));
                $this->handleSpamRequest($requestId, $chatId, $messageId);
            } elseif (strpos($data, 'completed_') === 0) {
                $requestId = intval(substr($data, 10));
                $this->handleCompletedRequest($requestId, $chatId, $messageId);
            }
        } elseif ($update->isType('message')) {
            $message = $update->getMessage();
            $text = $message->getText();
            $chatId = $message->getChat()->getId();
            $user = $message->getFrom();

            $this->saveTelegramUser($user);

            if ($text == '/start') {
                $options = config('telegram');
                $startMessage = $options['bot_start_message'] ?? 'Добро пожаловать!';
                $this->sendMessage($chatId, $startMessage);
            } elseif ($text == '/stop') {
                $options = config('telegram');
                $stopMessage = $options['bot_stop_message'] ?? 'До свидания!';
                $this->sendMessage($chatId, $stopMessage);
                $this->deleteTelegramUser($user->getId());
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function handleSpamRequest($requestId, $chatId, $messageId)
    {
        $telegramRequest = TelegramRequest::find($requestId);
        if ($telegramRequest) {
            $telegramRequest->update(['status' => 'spam']);

            $this->telegram->deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);
        }
    }

    private function handleCompletedRequest($requestId, $chatId, $messageId)
    {
        $telegramRequest = TelegramRequest::find($requestId);
        if ($telegramRequest) {
            $telegramRequest->update(['status' => 'completed']);

            $text = "<b>✅ Заявка #{$telegramRequest->id} отработана!</b>\n" .
                "<b>Контакт: </b>" . e($telegramRequest->contact) . "\n";

            if ($telegramRequest->product) {
                $text .= "<b>Продукт: </b>" . e($telegramRequest->product) . "\n";
            }

            $text .= e($telegramRequest->request_data);

            $this->telegram->editMessageText([
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);
        }
    }

    private function saveTelegramUser($user)
    {
        TelegramUser::updateOrCreate(
            ['user_id' => $user->getId()],
            [
                'username' => $user->getUsername(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
            ]
        );
    }

    private function deleteTelegramUser($userId)
    {
        TelegramUser::where('user_id', $userId)->delete();
    }

    private function sendMessage($chatId, $text, $keyboard = null)
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($keyboard) {
            $params['reply_markup'] = json_encode($keyboard);
        }

        $this->telegram->sendMessage($params);
    }
}
