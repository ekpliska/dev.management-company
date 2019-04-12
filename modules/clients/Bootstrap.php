<?php

    namespace app\modules\clients;
    use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface {
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules(
            [
                // Собственники, главная
                'clients' => 'clients/clients/index',
                'clients/<action>' => 'clients/clients/<action>',
                
                // Собственники, отдельная новость
                'news/<slug:[\w-]+>' => 'clients/news/view',
                'news/block/<block:[\w-]+>' => 'clients/news/index',
                'news' => 'clients/news/index',
                
                // Собственники, голосование
                'voting/<voting_id:\d+>' => 'clients/voting/view',
                'voting' => 'clients/voting/index',
                'voting/<action>' => 'clients/voting/<action>',
                
                // Собственники, заявки
                'requests/filter-by-type-request' => 'clients/requests/filter-by-type-request',
                'requests/add-answer-request' => 'clients/requests/add-answer-request',
                'requests/add-score-request' => 'clients/requests/add-score-request',
                'requests/close-grade-window' => 'clients/requests/close-grade-window',
                'requests/<request_number:\w+>' => 'clients/requests/view-request',
                'requests' => 'clients/requests/index',
                
                // Собственники, услуги
                'paid-services/filter-category-services' => 'clients/paid-services/filter-category-services',
                'paid-services/search-by-specialist' => 'clients/paid-services/search-by-specialist',
                'paid-services/order-services' => 'clients/paid-services/order-services',
                'paid-services/add-score-request' => 'clients/paid-services/add-score-request',
                'paid-services' => 'clients/paid-services/index',
                
                // Собственники, Платежи и квитанции
                'payments' => 'clients/payments/index',
                'payments/<action>' => 'clients/payments/<action>',
                
                // Собсвенники, Показания приборов учета
                'counters' => 'clients/counters/index',
                'counters/<action>' => 'clients/counters/<action>',
                
                // Собственники, лицевой счет
                'personal-account' => 'clients/personal-account/index',
                'personal-account/<action>' => 'clients/personal-account/<action>',
                
            ]
        );
    }
}