<?php
/*
 * Настройка параметров SMS
 */

return [
    'class' => 'app\components\sms\Sms',
    'login' => env('SMS_LOGIN'),  // Логин
    'password' => env('SMS_PASSWORD'),   // Пароль или MD5-хеш в нижнем регистре
    'post' => true,     // Использовать POST запрос
    'https' => true,    // Использовать HTTPS протокол
    'charset' => 'utf-8',   // Кодировка
    'debug' => false,   // Дебаг
];