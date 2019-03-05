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
                'managers' => 'managers/managers/index',
                
                'managers/clients' => 'managers/clients/index',
                'managers/clients/view-client/<client_id>/<account_number>' => 'managers/clients/view-client',
                'managers/clients/receipts-of-hapu/<client_id>/<account_number>' => 'managers/clients/receipts-of-hapu',
                'managers/clients/payments/<client_id>/<account_number>' => 'managers/clients/payments',
                'managers/clients/counters/<client_id>/<account_number>' => 'managers/clients/counters',
                'managers/clients/account-info/<client_id>/<account_number>' => 'managers/clients/account-info',
                
                'managers/employee-form/<new_employee>' => 'managers/employee-form/index',
                'managers/employee-form/employee-profile/<type>/<employee_id>' => 'managers/employee-form/employee-profile',
                'managers/employee-form/change-password/<user_id>' => 'managers/employee-form/change-password',
                
                'managers/requests' => 'managers/requests/index',
                'managers/requests/view-request/<request_number>' => 'managers/requests/view-request',
                
//                '' => '',
                
            ]
        );
    }
}