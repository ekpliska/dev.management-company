<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 *  Навигационное меню
 */
class NavMenu extends Widget {
    
    public $menu_array = [
        '0' => ['personal-account' => 'Лицевой счет'],
        '1' => ['requests' => 'Заявки'],
        '2' => ['clients' => 'Новости'],
        '3' => ['paid-services' => 'Услуги'],
        '4' => ['voting' => 'Опрос'],
    ];
    
    public function run() {
        
        return $this->render('navmenu/clients-menu', ['menu_array' => $this->menu_array]);
        
    }
    
}
