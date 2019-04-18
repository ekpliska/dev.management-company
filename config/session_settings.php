<?php

/* 
 * Настройки сессии
 */

if (YII_ENV_DEV) {
    return [];
} else {
    return [
//        'timeout' => 1200,
//        'class' => 'yii\web\DbSession',
//        'sessionTable' => 'user_session',
        /*
         * Настройка параметров cookie
         */
//        'cookieParams' => [
//            // Если true, cookie будет недоступен через JavaScript
//            'httpOnly' => false,
//            /*
//             * Путь на сервере, на котором будут доступены cookie
//             * По умолчанию '/' куки будут доступны для всего домена
//             */
//            'path' => '/',
//        ],
    ];
}