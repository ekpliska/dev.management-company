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
    
    /*
     * Метод получения cookies
     * Работает для запоминания значений выбранных их списков в Разделах:
     * Жилищный фонд, ID выбранного дома
     * Главная страница, ID выбранного пользователя
     */
    public function actionReadCookies($cookie_name) {

        $name_cookie = "{$cookie_name}";
        if (Yii::$app->request->cookies->has($name_cookie)) {
            return Yii::$app->request->cookies->get($name_cookie)->value;
        }
        
    }
    
}
