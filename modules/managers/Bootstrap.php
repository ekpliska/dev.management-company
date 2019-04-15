<?php

    namespace app\modules\managers;
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
                '/managers' => 'managers/managers/index',
                
                // Собственники
                'managers/clients' => 'managers/clients/index',
                'managers/clients/view-client/<client_id>/<account_number>' => 'managers/clients/view-client',
                'managers/clients/receipts-of-hapu/<client_id>/<account_number>' => 'managers/clients/receipts-of-hapu',
                'managers/clients/payments/<client_id>/<account_number>' => 'managers/clients/payments',
                'managers/clients/counters/<client_id>/<account_number>' => 'managers/clients/counters',
                'managers/clients/account-info/<client_id>/<account_number>' => 'managers/clients/account-info',
                
                // Сотрудники
                'managers/employee-form/<new_employee>' => 'managers/employee-form/index',
                'managers/employee-form/employee-profile/<type>/<employee_id>' => 'managers/employee-form/employee-profile',
                'managers/employee-form/change-password/<user_id>' => 'managers/employee-form/change-password',
                
                // Заявкм
                'managers/requests' => 'managers/requests/index',
                'managers/requests/view-request/<request_number>' => 'managers/requests/view-request',

                // Платные услуги
                'managers/paid-requests' => 'managers/paid-requests/index',
                'managers/paid-requests/view-paid-request/<request_number>' => 'managers/paid-requests/view-paid-request',
                
                // Новости
                'managers/news' => 'managers/news/index',
                'managers/news/view/<slug:[\w-]+>' => 'managers/news/view',
                'managers/news/<action>' => 'managers/news/<action>',
                
                // Голосование
                'managers/voting' => 'managers/voting/index',
                'managers/voting/view/<voting_id>' => 'managers/voting/view',
//                'managers/voting/<action>' => 'managers/voting/<action>',
                
                // Конструктор заявок
                'managers/designer-requests' => 'managers/designer-requests/index',
                'managers/designer-requests/<action>' => 'managers/designer-requests/<action>',
                
                // Жилищный фонд
                'managers/housing-stock' => 'managers/housing-stock/index',
                'managers/housing-stock/<action>' => 'managers/housing-stock/<action>',
                
                // Настройки
                'managers/settings' => 'managers/settings/index',
                
            ]
        );
    }
}