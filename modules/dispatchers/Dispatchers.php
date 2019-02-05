<?php

    namespace app\modules\dispatchers;
    use yii\base\Module;

/**
 * Модуль "Диспетчер"
 */
class Dispatchers extends Module
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
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
