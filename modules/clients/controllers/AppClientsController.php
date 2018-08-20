<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use app\modules\clients\components\checkPersonalAccount;
        
/*
 * Общий контроллер модуля Clients
 * Наследуется всеми остальными контроллерами
 */
    
class AppClientsController extends Controller {
    
    /*
     * Назначение прав доступа к модулю "Клиенты"
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['clients'],
                    ],
                ],
            ],
            'accountList' => [
                'class' => checkPersonalAccount::className(),
                '_list' => '_list',
                '_choosing' => '_choosing',
            ],
        ];
    }

    public function actions() {
        return [
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }
        
    /*
     * Метод получает ID лицевого счета из глобального списка
     * 
     */
    public function actionCurrentAccount() {
        
        $account_id = Yii::$app->request->post('idAccount');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->session->set('choosingAccount', $account_id);
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'choosingAccount',
                'value' => $account_id,
            ]));
            return ['success' => $account_id];
        }
        return ['success' => false];;
    }
    
}
