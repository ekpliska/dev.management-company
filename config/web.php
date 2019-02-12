<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$sms = require __DIR__ . '/sms.php';
$client_api = require __DIR__ . '/client_api.php';

$config = [
    'id' => 'management-company',
    'name' => 'Management Company',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru',
    // Подключение модулей
    'modules' => [
        // Модуль для Собственников и Арендаторов
        'clients' => [
            'class' => 'app\modules\clients\ClientsModule',
            'layout' => 'main-clients',
        ],
        // Модуль для Администратора
        'managers' => [
            'class' => 'app\modules\managers\ManagersModule',
            'layout' => 'main-managers',
        ],
        // Модуль для Диспетчера
        'dispatchers' => [
            'class' => 'app\modules\dispatchers\Dispatchers',
            'layout' => 'main-dispatchers',
        ],
        // Модуль API
        'v1' => [
            'class' => 'app\modules\api\v1\Module',
        ],
        /*
         * Расширение использует загрузку файлов
         */
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            'imagesStorePath' => '@webroot/upload/store', //path to origin images
            'imagesCachePath' => '@webroot/upload/cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick' 
            // 'placeHolderPath' => '@web/images/placeHolder.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
            'imageCompressionQuality' => 100, // Optional. Default value is 85.
        ],        
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'LPpnZwt-SMNcuRhZ-S24tU9dMRSkvSkF',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        
        /*
         * Компонент Профиль пользователя
         */
        'userProfile' => [
            'class' => 'app\modules\clients\components\UserProfile',
        ],

        /*
         * Компонент Профиль пользователя
         */
        'userProfileCompany' => [
            'class' => 'app\modules\managers\components\UserProfileCompany',
        ],        
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            
        ],        
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        /*
         * Настройка session и cookie
         */
//        'session' => [
//            'timeout' => 1800,
//            'class' => 'yii\web\DbSession',
//            'sessionTable' => 'user_session',
//            /*
//             * Настройка параметров cookie
//             */
//            'cookieParams' => [
//                // Если true, cookie будет недоступен через JavaScript
//                'httpOnly' => false,
//                /*
//                 * Путь на сервере, на котором будут доступены cookie
//                 * По умолчанию '/' куки будут доступны для всего домена
//                 */
//                'path' => '/',
//            ],
//        ],
        
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => false, // Отключение автологина на основе фалов cookies
//            'absoluteAuthTimeout' => 1200, // Время сессии 20мин
//            'authTimeout' => 600, // Автоматический выход из сиситемы, когда пользователь неактивен 10мин
            'loginUrl' => ['site/login'],
            'as afterLogin' => 'app\behaviors\LoginTimestampBehavior',
            
            /*
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => '.example.com',
            ],
             */
        ],        
        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
         * Настройка ЧПУ
         */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
                'registration' => 'site/registration',
                'request-password-reset' => 'site/request-password-reset',
                
//                '<module:clients>/clients/<block:\w+>' => '<module>/clients/index',
                
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/news',
                    'extraPatterns' => [
                        'GET /' => 'index',
                        'GET {id}/view' => 'view',
                    ],
                    
                ],
                
            ],
        ],
        'sms' => $sms,
        'client_api' => $client_api,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
