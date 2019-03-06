<?php

    namespace app\modules\dispatchers;
    use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface {
    
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules(
            [
                // Главная страница
                'dispatchers/list-request/<block>' => 'dispatchers/dispatchers/index',
                'dispatchers' => 'dispatchers/dispatchers/index',
             
                // Заявки
                'dispatchers/requests/view-request/<request_number:[\w-]+>' => 'dispatchers/requests/view-request',
                '/dispatchers/requests/view-paid-request/<request_number:[\w-]+>' => '/dispatchers/requests/view-paid-request',
                'dispatchers/requests/validation-form/<form:[\w-]+>' => 'dispatchers/requests/validation-form',
                'dispatchers/requests/type/<block:[\w-]+>' => 'dispatchers/requests/index',
                'dispatchers/requests' => 'dispatchers/requests/index',
                
                // Новости
                'dispatchers/news/view/<slug:[\w-]+>' => 'dispatchers/news/view',
                'dispatchers/news' => 'dispatchers/news/index',
                
                // Жилой массив
                'dispatchers/housing-stock' => 'dispatchers/housing-stock/index',
                
                // Отчеты
                'dispatchers/reports' => 'dispatchers/reports/index',
                
                // Профиль
                'dispatchers/profile' => 'dispatchers/profile/index',
                
                // Собсвенники
                'dispatchers/clients/view-client/<client_id:[\d-]+>/<account_number:[\w-]+>' => 'dispatchers/clients/view-client',
                'dispatchers/clients/receipts-of-hapu/<client_id:[\d-]+>/<account_number:[\w-]+>' => 'dispatchers/clients/receipts-of-hapu',
                'dispatchers/clients/payments/<client_id:[\d-]+>/<account_number:[\w-]+>' => 'dispatchers/clients/payments',
                'dispatchers/clients/counters/<client_id:[\d-]+>/<account_number:[\w-]+>' => 'dispatchers/clients/counters',
                'dispatchers/clients/account-info/<client_id:[\d-]+>/<account_number:[\w-]+>' => 'dispatchers/clients/account-info',
                'dispatchers/clients' => 'dispatchers/clients/index',
                
            ]
        );
    }
    
}