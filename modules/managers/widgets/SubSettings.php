<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;

/**
 * Навигационное меню
 */
class SubSettings extends Widget {
    
    public $sub_nav = [
        'index' => [
            'label' => 'Управляющая организация',
            'url' => 'settings/index',
        ],
        'service-duty' => [
            'label' => 'Подразделения/Должности',
            'url' => 'settings/service-duty',
        ],
        'partners-list' => [
            'label' => 'Партнеры',
            'url' => 'settings/partners-list',
        ],
        'slider-settings' => [
            'label' => 'Настройка слайдера',
            'url' => 'settings/slider-settings',
        ],
        'api-settings' => [
            'label' => 'Настройки API',
            'url' => 'settings/api-settings',
        ],
        '3' => [
            'label' => 'Частые вопросы',
            'url' => '',
        ],
    ];
    
    public function run() {
        
        return $this->render('subsettings/default', [
            'sub_nav' => $this->sub_nav,
        ]);
        
    }
    
}
