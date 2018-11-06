<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use yii\web\Response;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\behaviors\checkPersonalAccount;
        
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
                        'roles' => ['clients', 'clients_rent'],
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
     * Метод, формирования полного профиля для текущего пользователя
     */
    public function permisionUser() {
        return Yii::$app->userProfile;
    }    
    
        
//    /*
//     * Метод смены лицевого счета из глобально списка в хеддере,
//     * и последующая запись в сесии и куки
//     * 
//     */
//    public function actionCurrentAccount() {
//        
//        $account_id = Yii::$app->request->post('idAccount');
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        /*
//         * Если в $account_id пришел не верный ID лицевого счета
//         * то в куку и сессию его не записываем
//         * Возвращается предыдущее выбранное значение
//         */
//        if (!is_numeric($account_id) || !ArrayHelper::keyExists($account_id, $this->_list)) {
//            return ['status' => false];
//        }
//        
//        if (Yii::$app->request->isAjax) {
//            Yii::$app->session->set('_userAccount', $account_id);
//            Yii::$app->response->cookies->add(new \yii\web\Cookie([
//                'name' => '_userAccount',
//                'value' => $account_id,
//                'expire' => time() + 60*60*24*7,
//            ]));
//            return ['status' => true];
//        }
//        return ['status' => false];
//    }
    
}
