<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$sms = require __DIR__ . '/sms.php';
$client_api = require __DIR__ . '/client_api.php';
$session_settings = require __DIR__ . '/session_settings.php';
$payment_system = require __DIR__ . '/payment_system.php';
$mailer_config = require __DIR__ . '/mailer_config.php';

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
        'session' => $session_settings,
        
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => (YII_ENV_DEV) ? true: false, // Отключение автологина на основе фалов cookies
            'absoluteAuthTimeout' => (YII_ENV_DEV) ? null : 1200, // Время сессии 20мин
            'authTimeout' => (YII_ENV_DEV) ? null : 1200, // Автоматический выход из сиситемы, когда пользователь неактивен 20мин
            'autoRenewCookie' => true,
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
        'mailer' => $mailer_config,
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
                'result/<status:[\w-]+>' => 'site/result',
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
                        'POST set-grade/<request_id>' => 'set-grade',
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
                        'POST payments-history/<account:[\w-]+>' => 'payments-history',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/receipts',
                    'extraPatterns' => [
                        'POST get-receipts/<account:[\w-]+>' => 'get-receipts',
                        'POST get-receipt-pdf' => 'get-receipt-pdf',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/counters',
                    'extraPatterns' => [
                        'POST get-counter/<account:[\w-]+>/<id_counter:[\w-]+>' => 'get-counter',
                        'GET view/<account:[\w-]+>' => 'view',
                        'POST send-indications/<account:[\w-]+>' => 'send-indications',
                        'GET order-request/<account:[\w-]+>/<id_counter:[\w-]+>' => 'order-request',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/chat',
                    'extraPatterns' => [
                        'GET get-messages/<type:[\w-]+>/<chat_id:[\w-]+>' => 'get-messages',
                        'GET /' => 'index',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/token-push',
                    'extraPatterns' => [
                        'POST /' => 'index',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/notifications',
                    'extraPatterns' => [
                        'GET view/<note_id:[\d-]+>' => 'view',
                        'GET /' => 'index',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/settings-app',
                    'extraPatterns' => [
                        'POST /' => 'index',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/support',
                    'extraPatterns' => [
                        'POST post3ds/<account_number:[\w-]+>/<period:[\w-]+>' => 'post3ds',
                        'GET /' => 'index',
                    ]
                ],                
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'v1/payments',
                    'extraPatterns' => [
                        'POST post3ds/<account_number:[\w-]+>/<period:[\w-]+>' => 'post3ds',
                        'POST /' => 'index',
                    ]
                ],                
            ],
        ],
        'sms' => $sms,
        'client_api' => $client_api,
        'paymentSystem' => $payment_system,
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
