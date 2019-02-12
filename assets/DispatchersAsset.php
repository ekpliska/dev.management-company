<?php

    namespace app\assets;
    use yii\web\AssetBundle;

/**
 * Комплект ресурсов,
 * Модуль "Диспетчеры"
 */
class DispatchersAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/dispatchers/dispatchers-style.css',
        'css/form-style_manager.css',
    ];
    
    public $js = [
        'js/dispatchers/dispatchers_js.js',
        'js/lib-rating-plugin/jquery.raty.js',
    ];
    
    public $depends = [];

    
}
