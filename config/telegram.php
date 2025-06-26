<?php

/*
 * Конфиг для телеграм логера.
 */

return [
    'telegram' => [
        'bot_token' => env('BOT_TOKEN'), // Токен бота
        'channel_id' => env('CHANNEL_ID'), // Канал, куда будут отправляться логи
        'enabled' => filter_var(env('TELEGRAM_LOGGER_ENABLED', false), FILTER_VALIDATE_BOOL), // Включение/выключение логера
    ],
];
