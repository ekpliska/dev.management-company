<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;

/**
 * Навигационное меню, администратор
 */
class NavManager extends Widget {
    
    public $menu_array = [
        'clients' => [
            'name' => 'Собственники',
            'link' => 'clients/index',
        ],
        'dispatchers' => [
            'name' => 'Диспетчеры',
            'link' => 'employees/dispatchers',
        ],
        'specialists' => [
            'name' => 'Специалисты',
            'link' => 'employees/specialists',
        ],
        'administrators' => [
            'name' => 'Администраторы',
            'link' => 'employees/administrators',
        ],
        'requests' => [
            'name' => 'Заявки',
            'link' => 'requests/index',
        ],
        'paid-requests' => [
            'name' => 'Платные услуги',
            'link' => 'paid-requests/index',
        ],
        'news' => [
            'name' => 'Новости',
            'link' => 'news/index',
        ],
        'voting' => [
            'name' => 'Голосование',
            'link' => 'voting/index',
        ],
        'designer-requests' => [
            'name' => 'Конструктор заявок',
            'link' => 'designer-requests/index',
        ],
        'housing-stock' => [
            'name' => 'Жилищный фонд',
            'link' => 'housing-stock/index',
        ],
        'settings' => [
            'name' => 'Настройки',
            'link' => 'settings/index',
        ],
    ];
    
    public function run() {
        
        return $this->render('navmanager/default', [
            'menu' => $this->menu_array,
        ]);
    }
    
}
