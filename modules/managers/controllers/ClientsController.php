<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\Response;
    use yii\web\NotFoundHttpException;
    use yii\data\ActiveDataProvider;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\Clients;
    use app\modules\managers\models\User;
    use app\models\PersonalAccount;
    use app\models\Rents;
    use app\modules\managers\models\AddRent;

/**
 * Клиенты
 */
class ClientsController extends AppManagersController {
    
    /*
     * Главная страница
     * 
     * Собственники
     */
    public function actionIndex() {
        
        $client_list = new ActiveDataProvider([
            'query' => Clients::getListClients(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 30,
            ]
        ]);
        
        return $this->render('index', [
            'client_list' => $client_list,
        ]);
        
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
        
        $$is_rent = false;
        
        $client_info = Clients::findById($client_id);
        $account_info = PersonalAccount::findByNumber($account_number);
        $list_account = PersonalAccount::findByClient($client_id, true);
        $user_info = User::findByClientId($client_id);
        
        $user_info->scenario = User::SCENARIO_EDIT_CLIENT_PROFILE;
        
        $add_rent = new AddRent();
        
        if ($account_info->personal_rent_id) {
            $is_rent = true;
            $edit_rent = Rents::findOne(['rents_id' => $account_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $edit_rent = null;
        }
        
        if ($client_info == null || $account_info == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        if ($user_info->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($user_info, 'user_photo');
            $user_info->uploadPhoto($file);
            $this->updateClientInfo($client_info, $edit_rent);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('view-client', [
            'is_rent' => $is_rent,
            'client_info' => $client_info,
            'user_info' => $user_info,
            'account_choosing' => $account_info->account_id,
            'list_account' => $list_account,
            'rent_info' => $edit_rent,
            'add_rent' => $add_rent,
        ]);
        
    }
    
    public function updateClientInfo($client_info, $edit_rent) {
        
        // Если переключатель Арендатор пришел из пост
        if (isset($_POST['is_rent'])) {
            if ($client_info->load(Yii::$app->request->post())) {
                
                $add_rent = Yii::$app->request->post('AddRent');
                
                // Сохраняем данные существующего арендатора
                if ($edit_rent !== null) {
                    if ($edit_rent->load(Yii::$app->request->post())) {
                        // Если есть ошибки валидации
                        if ($edit_rent->hasErrors()) {
                            Yii::$app->session->setFlash('profile-admin-error');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        $edit_rent->save();
                    }
                }
                
                if ($add_rent !== null) {
                    $rent = new AddRent();
                    if ($rent->load(Yii::$app->request->post())) {
                        if ($rent->hasErrors()) {
                            Yii::$app->session->setFlash('profile-admin-error');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        $rent->addNewRent();
                    }
                }
                
                Yii::$app->session->setFlash('profile-admin');
                $client_info->save();
            }
        } else {
            // Если переключатель Арендатор, не пришли из пост, сохраняем данные только собственника
            if ($client_info->load(Yii::$app->request->post())) {
                Yii::$app->session->setFlash('profile-admin');
                $client_info->save();
            }
        }
        
    }
    
    /*
     * Блокировать/Разблокировать Собсвенника
     * 
     * На главной странице, для талицы
     */
    public function actionBlockClient() {
                
        $client_id = Yii::$app->request->post('clientId');
        $status = Yii::$app->request->post('statusClient');
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByClientId($client_id);
            $user_info->block($client_id, $status);
            return ['status' => $status, 'client_id' => $client_id];
        }
        
        return ['status' => false];
    }

    /*
     * Блокировать/Разблокировать Собственника
     * 
     * На странице просмотра информации о Собственнике
     */
    public function actionBlockClientInView() {
                
        $user_id = Yii::$app->request->post('userId');
        $status = Yii::$app->request->post('statusClient');
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByID($user_id);
            $user_info->blockInView($user_id, $status);
            return ['status' => $status, 'user_id' => $user_id];
        }
        
        return ['status' => false];
    }
    
    
}
