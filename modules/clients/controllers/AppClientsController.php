<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use yii\web\Response;
    use app\models\User;
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
     * Метод, проверяет существование пользователя по текущему ID (user->identity->user_id)
     * Пользователь имеет доступ только к странице своего профиля
     * В противном случае выводим исключение
     */
    public function permisionUser() {
        
        $user_id = Yii::$app->user->identity->user_id;
        $user_info = User::findByUser($user_id);
        
        if ($user_info) {
            return $user_info;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }        
    }    
    
        
    /*
     * Метод получает ID лицевого счета из глобального списка
     * 
     */
    public function actionCurrentAccount() {
        
        $account_id = Yii::$app->request->post('idAccount');
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        /*
         * Если в $account_id пришел не верный ID лицевого счета
         * то в куку и сессию его не записываем
         * Возвращается предыдущее выбранное значение
         */
        if (!is_numeric($account_id)) {
            return ['status' => false];
        }
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->session->set('_userAccount', $account_id);
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => '_userAccount',
                'value' => $account_id,
                'expire' => time() + 60*60*24*7,
            ]));
            return ['status' => true];
        }
        return ['status' => false];;
    }
    
}
