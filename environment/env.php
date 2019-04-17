<?php

require_once(__DIR__ . '/helpers.php');

if (YII_ENV_DEV) {
    $dotenv = Dotenv\Dotenv::create(__DIR__, '.env-local');
} else {
    $dotenv = Dotenv\Dotenv::create(__DIR__);
}

$dotenv->load();