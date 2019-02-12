<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 *  Навигационное меню, диспетчеры
 */
class NavDispatchers extends Widget {
    
    public $view_name = 'default';

    public $menu_array = [
        'reports' => [
            'link' => 'reports/index',
            'name' => 'Отчеты',
        ],
        'news' => [
            'link' => 'news/index',
            'name' => 'Новости',
        ],
        'dispatchers' => [
            'link' => 'dispatchers/index',
            'name' => 'Главная',
        ],
        'requests' => [
            'link' => 'requests/index',
            'name' => 'Заявки',
        ],
        'housing-stock' => [
            'link' => 'housing-stock/index',
            'name' => 'Жилищный фонд',
        ],
    ];
    
    public function run() {
        
        return $this->render("navdispatchers/{$this->view_name}", [
            'menu_array' => $this->menu_array,
        ]);
        
    }

}
