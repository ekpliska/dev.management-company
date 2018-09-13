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
        
        if ($user_info->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($user_info, 'user_photo');
            $user_info->uploadPhoto($file);
            $this->updateClientInfo($client_info, $rent_info);
        }
        
        return $this->render('view-client', [
            'is_rent' => $is_rent,
            'client_info' => $client_info,
            'user_info' => $user_info,
            'account_choosing' => $account_info->account_id,
            'list_account' => $list_account,
            'rent_info' => $rent_info,
        ]);
        
    }
    
    public function updateClientInfo($client_info, $rent_info) {
        
        if (isset($_POST['is_rent'])) {
            if ($client_info->load(Yii::$app->request->post())) {
                $client_info->save();
            }
        } else {
            if ($client_info->load(Yii::$app->request->post())) {
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
