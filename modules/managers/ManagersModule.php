<?php

    namespace app\modules\managers;
    use yii\base\Module;
    use yii\web\ErrorHandler;

/**
 * Модуль "Администратор"
 */
class ManagersModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\managers\controllers';
    
    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();

        \Yii::configure($this, [
            'components' => [
                'errorHandler' => [
                    'class' => ErrorHandler::className(),
                    'errorAction' => '/managers/app-managers/error',
                ]
            ],
        ]);

        /** @var ErrorHandler $handler */
        $handler = $this->get('errorHandler');
        \Yii::$app->set('errorHandler', $handler);
        $handler->register();
    }
}
