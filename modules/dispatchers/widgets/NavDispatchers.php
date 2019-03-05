<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 *  Навигационное меню, диспетчеры
 */
class NavDispatchers extends Widget {
    
    public $view_name = 'default';

    public $menu_array = [
        'dispatchers' => [
            'link' => 'dispatchers/index',
            'name' => 'Главная',
        ],
        'requests' => [
            'link' => 'requests/index',
            'name' => 'Заявки',
        ],
        'news' => [
            'link' => 'news/index',
            'name' => 'Новости',
        ],
        'housing-stock' => [
            'link' => 'housing-stock/index',
            'name' => 'Жилищный фонд',
        ],
        'reports' => [
            'link' => 'reports/index',
            'name' => 'Отчеты',
        ],
    ];
    
    public function run() {
        
        return $this->render("navdispatchers/{$this->view_name}", [
            'menu_array' => $this->menu_array,
        ]);
        
    }

}
