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
        
        // Получаем все активные лицевые счета Собственника
        $accounts = PersonalAccount::findByClient($user_info->user_client_id);
        
        // Статус наличия у собственника арендатора
        $is_rent = false;
        if (Rents::isRent($client->id)) {
            $is_rent = true;
        } else {
            $is_rent = false;
        }
        
        $_filter_form = new \app\modules\clients\models\FilterForm();
        
        
        
        
        return $this->render('index', [
            'user' => $user_info,
            'accounts' => $accounts,
            'is_rent' => $is_rent,
            '_filter_form' => $_filter_form,
        ]);
    }
    
    /*
     * Профиль пользователя
     */
    public function actionProfile() {
        
        $user_info = $this->permisionUser();
        var_dump($user_info); die;
        
        // Статус наличия у собственника арендатора
        $is_rent = Rents::isRent();
        if (Rents::isRent()) {
            echo 'yes';
        } else {
            echo 'no';
        }
        
        
        
        $client = Clients::findOne(['clients_id' => $user_info->user_client_id]);
        
        if (!$client) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        if ($client->is_rent) {
            $rent = Rents::findByRent($client->id);
            $is_rent = true;
        }

        if (
                $user_info->load(Yii::$app->request->post()) && 
                $client->load(Yii::$app->request->post())
            ) {
            $isValid = $user_info->validate();
            $isValid = $client->validate() && $isValid;
            if ($isValid) {
                Yii::$app->session->setFlash('success', 'Профиль обновлен');
                $user_info->uploadPhoto($username);
                $client->save(false);

                if ($rent) {
                    if ($rent->load(Yii::$app->request->post()) && $rent->validate()) {
                        $rent->save();
                        return $this->refresh();
                    }
                }                
                
                return $this->refresh();
                
            } else {
                Yii::$app->session->set('error', 'Произошла ошбка');
            }
        }
        
        return $this->render('profile', [
            'user' => $user_info,
            'client' => $client,
            'rent' => $rent,
            // 'rent_new' => $rent_new,
            'is_rent' => $is_rent,
        ]);
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
            
            $data = $this->renderAjax('_form/rent-view',['model' => $model, 'model_rent' => $model_rent]);
            return ['status' => true, 'model' => $model, 'data' => $data];
            
        }
        return ['status' => false];
        
    }
    
}
