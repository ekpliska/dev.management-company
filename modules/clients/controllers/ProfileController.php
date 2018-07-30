<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use yii\web\UploadedFile;
    use yii\web\NotFoundHttpException;
    use app\models\User;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\PersonalAccount;
    
    

/**
 * Default controller for the `clients` module
 */
class ProfileController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $client = Clients::findOne(['clients_id' => $user_info->user_client_id]);
        
        // Получаем все активные лицевые счета Собственника (для dropDownList)
        $accounts_list = PersonalAccount::findByClient($user_info->user_client_id);
        
        // Получаем ифнормацию по лицевому счету Собственника
        $accounts_info = PersonalAccount::findByClientProfile($user_info->user_client_id); 
        
        /* Статус наличия у собственника арендатора
         * Если имеется Арендатор, то загружаем данные Арендатор для формы
         */
        $is_rent = false;
        if (Rents::isRent($client->id)) {
            $is_rent = true;
            $model_rent = Rents::findOne(['rents_id' => $accounts_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $model_rent = null;
        }
        
        // Форма добавить Арендатора
        $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        
        return $this->render('index', [
            'user' => $user_info,
            'accounts_list' => $accounts_list,
            'is_rent' => $is_rent,
            'accounts_info' => $accounts_info,
            'model_rent' => $model_rent,
            'add_rent' => $add_rent,
        ]);
    }
    
    public function actionUpdateProfile($form) {
        
        $user_info = $this->findUser(Yii::$app->user->id);
        
        if (Yii::$app->request->isPost) {
            if ($user_info->load(Yii::$app->request->post())) {
                
                try {
                    $file = UploadedFile::getInstance($user_info, 'user_photo');
                    $user_info->uploadPhoto($file);
                    
                    // Заполняем массив данными нового Арендатора
                    $data_rent = Yii::$app->request->post('ClientsRentForm');
                    $rent_form = new ClientsRentForm($data_rent);
                            
                    if (array_filter($data_rent) && !empty($data_rent)) {
                        $rent_form->saveRentToUser($data_rent, '1');
                    } else {
                        echo 'no';
                    }
                    
                    Yii::$app->session->setFlash('success', 'Профиль обновлен');
                    
                } catch (Exception $ex) {
                    Yii::$app->errorHandler->logException($ex);
                    Yii::$app->session->setFlash('error', $ex->getMessage());                    
                }
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->goHome();
    }
    
    
    /*
     * Удаление учетной записи арендатора
     */
    public function actionDeleteRent() {
        
        $rent_id = Yii::$app->request->post('rent_id');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $status = Yii::$app->request->post('status');

            $rents_info = Rents::findOne(['rents_id' => $rent_id]);
            $user_info = User::findOne(['user_rent_id' => $rent_id]);
            
            if (!$status) {
                 $rents_info->delete();
                 $user_info->delete();
                return 'Удаляем запись';
            }
        }
        
    }

    /*
     * Отвязать арендатора от лицевого счета
     * Статус учетной записи арендатора для входа на портал - заблокирован
     */
    public function actionUndoRent() {
        
        $rent_id = Yii::$app->request->post('rent_id');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $status = Yii::$app->request->post('status');

            $rents_info = Rents::findOne(['rents_id' => $rent_id]);
            $user_info = User::findOne(['user_rent_id' => $rent_id]);
            
            if (!$status) {
                $rents_info->rents_account_id = null;
                $rents_info->isActive = Rents::STATUS_DISABLED;
                $user_info->status = User::STATUS_BLOCK;
                $rents_info->save(false);
                $user_info->save(false);
                return 'Отвязываем запись';
            }
        }
        
    }
    
    /*
     * Добавить запись арендатора
     * Создать для него учетную запись для входа на портал
     */
    public function actionAddRent() {
        
        $model = new ClientsRentForm();
        $client_id = Yii::$app->request->post('client_id');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                $model->addNewClient($client_id);
                // return $rent_id;
                return true;
            }
        }
    }
    
    public function actionAddFormRent($client_id) {        
        return $this->renderAjax('_rent_form', ['rent_new' => new ClientsRentForm(), 'client_id' => $client_id]);
    }

    /*
     * Метод, проверяет существование пользователя по текущему ID (user->identity->user_id)
     * Пользователь имеет доступ только к странице своего профиля
     * В противном случае выводим исключение
     */
    public function permisionUser() {
        
        $_user_id = Yii::$app->user->identity->user_id;
        $user_info = User::findByUser($_user_id);
        
        if ($user_info) {
            return $user_info;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }        
    }
    
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
    }
    
    /*
     * Фильтр выбора лицевого счета
     */
    public function actionCheckAccount() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            
            // Из пост рапроса получаем ID лицевого счета и собственника
            $account_id = Yii::$app->request->post('dataAccount');
            $client_id = Yii::$app->request->post('dataClient');
            
            // Ищем арендатора, закрепленного за указанным лицевым счетом
            $model = PersonalAccount::findByRent($account_id, $client_id);
            
            /*
             * Если арендатор существует, генерирем для него модель
             */
            $model_rent = Rents::findOne(['rents_id' => $model->personal_rent_id]);
            // Форма добавить Арендатора
            $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
            
            $data = $this->renderAjax('_form/rent-view',['model' => $model, 'model_rent' => $model_rent, 'add_rent' => $add_rent]);
            
            return ['status' => true, 'model' => $model, 'data' => $data];
            
        }
        return ['status' => false];
        
    }
    
}
