<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\models\Houses;
    use app\modules\managers\models\searchForm\searchClients;

/**
 * Дополнительное под меню
 */
class SubMenu extends Widget {
    
    /*
     * @param $view_name string Имя вида дополнительного навигационного меню
     * @param $client_id integer Для навигационного меню в профиле Собственника, требуется передача ID Собственника
     * @param $account_number integer Для навигационного меню в профиле Собственника, требуется передача номера лицевого счета
     */
    public $view_name = '';
    public $client_id = null;
    public $account_number = null;

    public function init() {
        
        if ($this->view_name == null) {
            throw new \yii\base\InvalidConfigException('Не указан вид дополнительного навигационного меню');
        }
        parent::init();
    }
    
    public function run() {
        
        // Массив для передачи необходимых параметров в вид
        $params = [];
        
        switch ($this->view_name) {
            case 'clients': 
                $model = new searchClients();
                break;
            case 'profile':
                // Меню профиля Собственника
                $params = $this->subMenuProfile();
                break;
            case 'voting':
                $params = $this->adressLists();
                break;
            case 'news':
                $params = $this->newsSubMenu();
                break;
            case 'designer-request':
                $params = $this->designerSubMenu();
        }
        
        return $this->render('submenu/' . $this->view_name, [
            'params' => $params,
            'client_id' => $this->client_id,
            'account_number' => $this->account_number,
            'model' => $model,
        ]);
        
    }
    
    /*
     * Подменю для профиля Собственника
     */
    private function subMenuProfile() {
        
        return $params = [
            'view-client' => [
                'label' => 'Профиль',
                'link' => 'clients/view-client',
            ],
            'receipts-of-hapu' => [
                'label' => 'Квитанция ЖКУ',
                'link' => 'clients/receipts-of-hapu',
            ],
            'payments' => [
                'label' => 'Платежи',
                'link' => 'clients/payments',
            ],
            'counters' => [
                'label' => 'Показания приборов учета',
                'link' => 'clients/counters',
            ],
            'account-info' => [
                'label' => 'Общая информация по лицевому счету',
                'link' => 'clients/account-info',
            ],
        ];
    }
    
    /*
     * Адреса жилого массива
     */
    private function adressLists() {
        
        return $params = Houses::getAdressHousesList();
        
    }
    
    /*
     * Дополнительные разделы для "Новостей"
     */
    private function newsSubMenu() {
        
        $sub_news_array = [
            'news' => 'Новости',
            'adverts' => 'Рекламная запись',
        ];
        
        return $sub_news_array;
        
    }
    
    /*
     * Дополнительные разделы для "Конструктора заявок"
     */
    private function designerSubMenu() {
        
        $sub_designer_array = [
            'requests' => 'Заявки',
            'paid-services' => 'Платные услуги',
        ];
        
        return $sub_designer_array;
        
    }
    
}
