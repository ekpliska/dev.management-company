<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;

/**
 * Общий контроллер "Диспетчер"
 */
class AppDispatchersController extends Controller {
    
    /*
     * Назначение прав доступа к модулю "Диспетчер"
     */
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
    
    public function permisionUser() {
        return Yii::$app->profileDispatcher;
    }
    
}
