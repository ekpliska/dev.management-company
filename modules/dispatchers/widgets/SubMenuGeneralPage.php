<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 * Нафигационное меню, главная страница
 */
class SubMenuGeneralPage extends Widget {
    
    public $general_navbar = [
        'requests' => 'Заявки',
        'paid-requests' => 'Услуги',
    ];
    
    public function run() {
        
        return $this->render('submenugeneralpage/default', [
            'general_navbar' => $this->general_navbar,
        ]);
    }
    
}
