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
                // объявление правил здесь
                'clients/<block:\w+>' => 'clients/clients/index',
                'clients' => 'clients/clients/index',
                
                'news/<slug>' => 'clients/news/view-news',
                
//                'voting/<voting_id>' => 'clients/voting/view-voting',
                'voting' => 'clients/voting/index',
                'voting/<action>' => 'voting/voting/<action>',
                
                'requests/filter-by-type-request' => 'clients/requests/filter-by-type-request',
                'requests/<request_number>' => 'clients/requests/view-request',
                'requests' => 'clients/requests/index',
                
                'paid-services/filter-category-services' => 'clients/paid-services/filter-category-services',
                'paid-services/search-by-specialist' => 'clients/paid-services/search-by-specialist',
                'paid-services/order-services' => 'clients/paid-services/order-services',
                'paid-services' => 'clients/paid-services/index',
                
                'personal-account' => 'clients/personal-account/index',
                'personal-account/<action>' => 'clients/personal-account/<action>',
                
                
                
//                'news/view-news/<slug:\w+>' => 'clients/news/view-news',
//                '<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                
            ]
        );
    }
}