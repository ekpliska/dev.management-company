<?php
$params = require __DIR__ . '/params.php';

return [
    'class' => 'yii\swiftmailer\Mailer',
    // Сохранять отправленные письма на диске
    'useFileTransport' => true,
    'messageConfig' => [
        'charset' => 'UTF-8',
        'from' => [$params['company_email'] => $params['company_name']],
    ],
//    'transport' => [
//        'class' => 'Swift_SmtpTransport',
//        'host' => '',
//        'username' => '',
//        'password' => '',
//        'port' => '',
//        'encryption' => '',
//    ],    
];