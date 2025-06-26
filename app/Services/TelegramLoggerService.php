<?php

namespace App\Services;

use App\Contracts\Services\LoggerServiceInterface;
use Http;
use Log;
use Throwable;

readonly class TelegramLoggerService implements LoggerServiceInterface
{
    private string $botToken;
    private string $channelId;
    private string $apiUrl;
    public function __construct()
    {
         $this->botToken = config('telegram.telegram.bot_token');
         $this->channelId = config('telegram.telegram.channel_id');
         $this->apiUrl = "https://api.telegram.org/bot$this->botToken/sendMessage";
    }

    public function log(mixed $logData): void
    {
        if (!config('telegram.telegram.enabled')) {
            return;
        }

        $message = is_string($logData) ? $logData : print_r($logData, true);

        try {
            Http::post($this->apiUrl, [
                'chat_id' => $this->channelId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}
