<?php
$params = require __DIR__ . '/params.php';

return [
    'class' => 'yii\swiftmailer\Mailer',
    /*
     *  useFileTransport
     *      true    Сохранять отправленные письма на диске в директории проекта runtime/mail
     *      false   Отправлять письма адресатам
     */
    'useFileTransport' => true,
    'messageConfig' => [
        'charset' => 'UTF-8',
        'from' => [$params['email_subscriber']['company_email'] => $params['email_subscriber']['company_name']],
    ],
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => '',       // SMTP Host
        'username' => '',   // Полное имя пользователя, указывать вместе с символом @
        'password' => '',   // Пароль от учетной записи
        'port' => '',       // SMTP Порт
        'encryption' => '', // Укажите протокол защиты tls или ssl
    ],    
];