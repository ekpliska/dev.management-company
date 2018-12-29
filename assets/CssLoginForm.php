<?php

    namespace app\assets;
    use yii\web\AssetBundle;

/*
 * Комплек ресурсов 
 * Модуль "Собстенники"
 */    
class CssLoginForm extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/form.css',
    ];
    
    public $js = [];
    
    public $depends = [];
}
