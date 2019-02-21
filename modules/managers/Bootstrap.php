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
                'managers' => 'managers/managers/index'
            ]
        );
    }
}