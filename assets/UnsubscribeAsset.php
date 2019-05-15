<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

    namespace app\assets;
    use yii\web\AssetBundle;

/**
 * Комплект ресурсов для страницы "Описаться от рассылки"
 */
class UnsubscribeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/unsubscribe/unsubscribe-style.css',
    ];
    
    public $js = [];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 