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
    
    public $css = [
        'css/clients/clients-style.css',
        'css/clients/media-clients-style.css',
        'css/form-style.css',
        'js/lib-rating-plugin/jquery.raty.css',
        'js/lib-owlcarousel/assets/owl.carousel.min.css',
        'js/lib-owlcarousel/assets/owl.theme.default.min.css'
    ];
    
    public $js = [
        'js/clients/clients_js.js',
        'js/lib-rating-plugin/jquery.raty.js',
        'js/lib-rating-plugin/jquery.raty.js',
        'js/lib-owlcarousel/owl.carousel.min.js',
    ];
    
    public $depends = [];

}
