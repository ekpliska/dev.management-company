<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$sms = require __DIR__ . '/sms.php';
$client_api = require __DIR__ . '/client_api.php';
$orangedata_client = require __DIR__ . '/orange_data_client.php';

$config = [
    'id' => 'elsa-company',
    'name' => 'Electronic Smart Assistant',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\modules\clients\Bootstrap',
        'app\modules\dispatchers\Bootstrap',
        'app\modules\managers\Bootstrap',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@orangedata'   => '@app/orangedata',
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
            'class' => 'app\modules\dispatchers\DispatchersModule',
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
            'imageCompressionQuality' => 60, // Optional. Default value is 85.
        ],        
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'LPpnZwt-SMNcuRhZ-S24tU9dMRSkvSkF',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'baseUrl'=> '',
        ],
        
        /*
         * Компонент Профиль пользователя, Собственник, Арендатор
         */
        'userProfile' => [
            'class' => 'app\modules\clients\components\UserProfile',
        ],

        /*
         * Компонент Профиль пользователя, Диспетчер
         */
        'profileDispatcher' => [
            'class' => 'app\modules\dispatchers\components\ProfileDispatcher',
        ],

        /*
         * Компонент Профиль пользователя, Администратор
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
//            'authTimeout' => 1200, // Автоматический выход из сиситемы, когда пользователь неактивен 20мин
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
            // Сохранять отправленные письма на диске
            'useFileTransport' => true,
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.ukr.net',
//                'username' => 'user@ukr.net',
//                'password' => 'password',
//                'port' => '2525',
//                'encryption' => 'ssl',
//            ],
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
                'signup' => 'signup/index',
                'remove-notifications' => 'notification/remove-notifications',
                'one-notification/<notice_id:[\w-]+>' => 'notification/one-notification',
                
                // REST Rules
                [
                    'class' => 'yii\rest\UrlRule', 
                    'pluralize' => false, 
                    'controller' => 'v1/news',
                    'extraPatterns' => [
                       'GET view/<id:\d+>' => 'view',
                     ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/api',
                    'extraPatterns' => [
                        'POST sign-up/<step>' => 'sign-up',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/requests',
                    'extraPatterns' => [
                        'GET view/<request_id>' => 'view',
                        'POST /' => 'index',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/paid-requests',
                    'extraPatterns' => [
                        'GET get-services/<category_id>' => 'get-services',
                        'GET info-service/<service_id>' => 'info-service',
                        'GET view/<request_id>' => 'view',
                        'GET /' => 'index',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/rent',
                    'extraPatterns' => [
                        'GET view/<account:[\w-]+>' => 'view',
                        'POST update/<rent_id:[\w-]+>' => 'update',
                        'GET delete/<rent_id:[\w-]+>' => 'delete',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/vote',
                    'extraPatterns' => [
                        'GET become-participant/<vote_id:[\w-]+>' => 'become-participant',
                        'GET get-results/<vote_id:[\w-]+>' => 'get-results',
                        'POST set-answers/<vote_id:[\w-]+>' => 'set-answers',
                        'GET get-questions/<vote_id:[\w-]+>' => 'get-questions',
                        'GET vote-list/<account:[\w-]+>' => 'vote-list',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/personal-account',
                    'extraPatterns' => [
                        'GET view/<account:[\w-]+>' => 'view',
                        'GET payments-history/<account:[\w-]+>' => 'payments-history',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/receipts',
                    'extraPatterns' => [
                        'GET view/<account:[\w-]+>' => 'view',
                        'POST get-receipts/<account:[\w-]+>' => 'get-receipts',
                        'POST get-receipt-pdf' => 'get-receipt-pdf',
                    ]
                ],                
            ],
        ],
        'sms' => $sms,
        'client_api' => $client_api,
        'orangedata_client' => $orangedata_client,
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
