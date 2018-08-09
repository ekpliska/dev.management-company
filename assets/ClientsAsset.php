<?php

    namespace app\assets;
    use yii\web\AssetBundle;

/*
 * Комплек ресурсов 
 * Модуль "Собстенники"
 */
class ClientsAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [];
    
    public $js = [
        'js/clients/clients_js.js',
    ];
    
    public $depends = [];

}
