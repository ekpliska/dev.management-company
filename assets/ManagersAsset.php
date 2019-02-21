<?php

    namespace app\assets;
    use yii\web\AssetBundle;

/*
 * Комплек ресурсов 
 * Модуль "Собстенники"
 */
class ManagersAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/managers/managers-style.css',
        'css/managers/media-managers.css',
        'css/form-style_manager.css',
    ];
    
    public $js = [
        'js/managers/managers_js.js',
        'js/lib-rating-plugin/jquery.raty.js',
    ];
    
    public $depends = [];

}
