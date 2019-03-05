<?php

    namespace app\modules\dispatchers;
    use yii\base\Module;
    use yii\filters\AccessControl;
    use yii\web\ErrorHandler;

/**
 * Модуль "Диспетчер"
 */
class DispatchersModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\dispatchers\controllers';
    
    public function behaviors() {
       return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['dispatcher'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init() {
        
        parent::init();

        \Yii::configure($this, [
            'components' => [
                'errorHandler' => [
                    'class' => ErrorHandler::className(),
                    'errorAction' => '/dispatchers/dispatchers/error',
                ]
            ],
        ]);

        /** @var ErrorHandler $handler */
        $handler = $this->get('errorHandler');
        \Yii::$app->set('errorHandler', $handler);
        $handler->register();    
        
    }
}
