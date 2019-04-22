<?php

return [
    'class' => 'yii\db\Connection',
//    'dsn' => env('DB_DSN'),
//    'username' => env('DB_USERNAME'),
//    'password' => env('DB_PASSWORD'),
    'dsn' => 'mysql:host=localhost;port=3306;dbname=customers_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    // Флаг включения кеширования
    'enableSchemaCache' => false,
    // Продолжительность кеширования схемы.
    'schemaCacheDuration' => 600 * 24,
    // Название компонента кеша, используемого для хранения информации о схеме
    'schemaCache' => 'cache',
];
