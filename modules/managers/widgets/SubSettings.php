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
        'site-settings' => [
            'label' => 'Настройки портала',
            'url' => 'settings/site-settings',
        ],
        'slider-settings' => [
            'label' => 'Настройка слайдера',
            'url' => 'settings/slider-settings',
        ],
        'service-duty' => [
            'label' => 'Подразделения/Должности',
            'url' => 'settings/service-duty',
        ],
        'partners-list' => [
            'label' => 'Партнеры',
            'url' => 'settings/partners-list',
        ],
        'faq-settings' => [
            'label' => 'Часто задаваевые вопросы',
            'url' => 'settings/faq-settings',
        ],
        'sms-settings' => [
            'label' => 'СМС оповещения',
            'url' => 'settings/sms-settings',
        ],
    ];
    
    public function run() {
        
        return $this->render('subsettings/default', [
            'sub_nav' => $this->sub_nav,
        ]);
        
    }
    
}
