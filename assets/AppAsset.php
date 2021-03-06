<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

    namespace app\assets;
    use yii\web\AssetBundle;

/**
 * Комплект ресурсов
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/main.css',
        'css/media.css',
        'css/checkbox.css',
        '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        'js/lib-magnific-popup-master/magnific-popup.css'
    ];
    
    public $js = [
        'js/common.js',
        'js/jquery.maskedinput.js',
        'js/lib-magnific-popup-master/jquery.magnific-popup.min.js'
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 