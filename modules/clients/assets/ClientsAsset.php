<?php

    namespace app\modules\clients\assets;
    use yii\web\AssetBundle;

/*
 * Комплек ресурсов 
 * Модуль "Собстенники"
 */
class ClientsAsset extends AssetBundle {
    
    public $sourcePath = '@app/modules/clients/web';
    
    public $css = [];
    
    public $js = [
        'js/validation_forms.js',
    ];
}
