<?php

/*
 * Загрушка на устаревшие версии браузера IE
 */
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (
        stripos($user_agent, 'MSIE 6.0') == true 
        || stripos($user_agent, 'MSIE 7.0') == true 
        || stripos($user_agent, 'MSIE 7.0b') == true 
        || stripos($user_agent, 'MSIE 8.0') == true 
        || stripos($user_agent, 'MSIE 9.0') == true
    ) {
        require 'browsers-info.html';
        exit();
}

// development
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// production
//defined('YII_DEBUG') or define('YII_DEBUG', false);
//defined('YII_ENV') or define('YII_ENV', 'prod');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Environment
require __DIR__ . '/../environment/env.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
