<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 *  Навигационное меню
 */
class NavMenu extends Widget {
    
    public $menu_array = [
        'personal-account' => [
            'link' => 'personal-account/index',
            'name' => 'Лицевой счет',
        ],
        'requests' => [
            'link' => 'requests/index',
            'name' => 'Заявки',
        ],
        'clients' => [
            'link' => 'clients/index',
            'name' => 'Новости',
        ],
        'paid-services' => [
            'link' => 'paid-services/index',
            'name' => 'Услуги',
        ],
        'voting' => [
            'link' => 'voting/index',
            'name' => 'Опрос',
        ],
    ];
    
    public $child_array = [
        '0' => [
            'link' => 'personal-account/receipts-of-hapu',
            'name' => 'Квитанция ЖКУ',
            'parent_item' => 'personal-account',
        ],
        '1' => [
            'link' => 'personal-account/payments',
            'name' => 'Платежи',
            'parent_item' => 'personal-account',
        ],
        '2' => [
            'link' => 'personal-account/counters',
            'name' => 'Показания приборов учета',
            'parent_item' => 'personal-account',
        ],
    ];


    public function run() {
        
        return $this->render('navmenu/clients-menu', [
            'menu_array' => $this->menu_array,
            'child_array' => $this->child_array,
        ]);
        
    }

}
