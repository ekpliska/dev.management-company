<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 *  Навигационное меню
 */
class NavMenu extends Widget {
    
    public $view_name = 'menu';


    public $menu_array = [
        'requests' => [
            'link' => 'requests/index',
            'name' => 'Заявки',
        ],
        'paid-services' => [
            'link' => 'paid-services/index',
            'name' => 'Услуги',
        ],
        '#1' => [
            'link' => 'receipts-payments/index',
            'name' => 'Платежи и квитанции',
        ],
        '#2' => [
            'link' => 'counters/index',
            'name' => 'Показания приборов учета',
        ],
        'news' => [
            'link' => 'news/index',
            'name' => 'Новости',
        ],
        'voting' => [
            'link' => 'voting/index',
            'name' => 'Опрос',
        ],
    ];
    
    public function run() {
        
        return $this->render("navmenu/{$this->view_name}", [
            'menu_array' => $this->menu_array,
        ]);
        
    }

}
