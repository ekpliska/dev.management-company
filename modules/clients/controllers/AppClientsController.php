<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use app\modules\clients\behaviors\checkPersonalAccount;
    use app\models\PersonalAccount;
        
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
                '_lists' => '_lists',
                '_current_account_id' => '_current_account_id',
                '_current_account_number' => '_current_account_number',
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
    
    /*
     * Смена выбора текущего лицевого счета
     * Текущий лицевой счет устанавливается в БД, как статус STATUS_CURRENT
     * dropDownList Лицевой счет
     */
    public function actionCheckAccount() {

        // ID Текущего лицевого счета
        $current_account_id = $this->_current_account_id;
        // Из пост запроса получаем ID лицевого счета и собственника
        $new_current_account_id = Yii::$app->request->post('newCurrentAccount');
        
        $client_id = Yii::$app->userProfile->clientID;
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            if (!PersonalAccount::changeCurrentAccount($current_account_id, $new_current_account_id)) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка смены текущего лицевого счета. Обновите страницу и повторите действие заново']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Ваш текущий лицевой счет успешно изменен']);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->goHome();
        
    }
    
}
