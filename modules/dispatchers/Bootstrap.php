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
                'dispatchers' => 'dispatchers/dispatchers/index',
                
            ]
        );
    }
    
}