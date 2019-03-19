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
                'clients/<block:\w+>' => 'clients/clients/index',
                'clients' => 'clients/clients/index',
                
                // Собственники, отдельная новость
                'news/<slug>' => 'clients/news/view-news',
                
                // Собственники, голосование
//                'voting/<voting_id>' => 'clients/voting/view-voting',
                'voting' => 'clients/voting/index',
                'voting/<action>' => 'voting/voting/<action>',
                
                // Собственники, заявки
                'requests/filter-by-type-request' => 'clients/requests/filter-by-type-request',
                'requests/add-answer-request' => 'clients/requests/add-answer-request',
                'requests/add-score-request' => 'clients/requests/add-score-request',
                'requests/<request_number>' => 'clients/requests/view-request',
                'requests' => 'clients/requests/index',
                
                // Собственники, услуги
                'paid-services/filter-category-services' => 'clients/paid-services/filter-category-services',
                'paid-services/search-by-specialist' => 'clients/paid-services/search-by-specialist',
                'paid-services/order-services' => 'clients/paid-services/order-services',
                'paid-services/add-score-request' => 'clients/paid-services/add-score-request',
                'paid-services' => 'clients/paid-services/index',
                
                // Собственники, лицевой счет
                'personal-account' => 'clients/personal-account/index',
                'personal-account/<action>' => 'clients/personal-account/<action>',
                
            ]
        );
    }
}