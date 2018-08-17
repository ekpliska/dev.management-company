<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use app\models\PersonalAccount;
        
/*
 * Общий контроллер модуля Clients
 * Наследуется всеми остальными контроллерами
 */
    
class AppClientsController extends Controller {
    
    public $_list;
    public $_choosing;

    
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
        ];
    }

    public function actions() {
        return [
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }
    
    public function init() {
        
        $session = Yii::$app->session;
        // $session->destroy();
        
        $_choosing_account = $session->get('choosingAccount');
        
        if (!$session->isActive) {
            $session->open();
        }
        
        // Проверяем ключ сессии для dropDownList
        if (!$_choosing_account) {
            $session->set('choosingAccount', key($this->getListAccount(30)));
        }
        
        return $this->_choosing = $session->get('choosingAccount');
        
    }

    /*
     * Получить список всех лицевых счетов
     */
    public function getListAccount($user) {
        
        return $this->_list = PersonalAccount::getListAccountByUserID($user);
        
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
            return ['success' => $account_id];
        }
        return ['success' => false];;
    }
    
}
