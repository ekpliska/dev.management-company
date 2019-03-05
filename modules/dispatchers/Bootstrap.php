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
                'dispatchers/requests/<block:[\w-]+>' => 'dispatchers/requests/index',
                'dispatchers/requests' => 'dispatchers/requests/index',
                'dispatchers/requests/view-request/<request_number:\d+>' => 'dispatchers/requests/view-request',
                
                // Новости
                'dispatchers/news/view/<slug:[\w-]+>' => 'dispatchers/news/view',
                'dispatchers/news' => 'dispatchers/news/index',
                
                // Жилой массив
                'dispatchers/housing-stock' => 'dispatchers/housing-stock/index',
                
                // Отчеты
                'dispatchers/reports' => 'dispatchers/reports/index',
                
                // Профиль
                'dispatchers/profile' => 'dispatchers/profile/index'
                
            ]
        );
    }
    
}