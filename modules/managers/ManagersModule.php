<?php

    namespace app\modules\managers;
    use yii\filters\AccessControl;
    use yii\base\Module;

/**
 * Модуль "Администратор"
 */
class ManagersModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\managers\controllers';
    
    public function behaviors() {
       return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
