<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Clients;
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Rents;

/**
 * Профиль собственника
 */
class ClientProfileController extends AppDispatchersController {
    
    public function actionIndex() {
        return $this->render('index');
    }
    
    /*
     * Просмотр сведений о Собственнике
     * 
     * @param integer $is_rent Переключатель наличия арендатора
     * @param array $client_info Информация о Собственнике
     * @param array $account_number Текущий лицевой счет
     * @param array $user_info Информация об учетной записи Собственника (Пользователь)
     */
    public function actionViewClient($client_id, $account_number) {
        
        $is_rent = false;
        
        $client_info = Clients::findById($client_id);
        $account_info = PersonalAccount::findByNumber($account_number);
        $list_account = PersonalAccount::findByClient($client_id, true);
        $user_info = User::findByClientId($client_id);
        
        if ($account_info->personal_rent_id) {
            $is_rent = true;
            $rent_info = Rents::findOne(['rents_id' => $account_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $rent_info = null;
        }
        
        if ($client_info == null || $account_info == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->render('view-client', [
            'is_rent' => $is_rent,
            'client_info' => $client_info,
            'user_info' => $user_info,
            'account_choosing' => $account_info,
            'list_account' => $list_account,
            'rent_info' => $rent_info,
        ]);
        
    }
    
}
