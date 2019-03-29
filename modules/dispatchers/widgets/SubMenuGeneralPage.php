<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 * Нафигационное меню, главная страница
 */
class SubMenuGeneralPage extends Widget {
    
    public $count_requests = 0;
    public $count_paid_requests = 0;


    public $general_navbar = [
        'requests' => 'Заявки',
        'paid-requests' => 'Услуги',
    ];
    
    public function init() {
        
        parent::init();
        
    }
    
    public function run() {
        
        return $this->render('submenugeneralpage/default', [
            'general_navbar' => $this->general_navbar,
        ]);
    }
    
}
