<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use yii\web\UploadedFile;
    use app\models\User;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Clients;
    use app\models\Rents;
    use yii\web\NotFoundHttpException;
    

/**
 * Default controller for the `clients` module
 */
class ClientsController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
     * Профиль пользователя
     */
    public function actionProfile($user, $username) {
        
        $_user = Yii::$app->user->identity;
        
        $user_info = User::findByUser($user, $username);
        
        // Пользователь должен иметь доступ только к странице своего профиля
        if ($_user->user_id <> $user_info->user_id) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $is_rent = false;       
        $client = Clients::findOne(['clients_id' => $user_info->user_client_id]);
        
        if (!$user_info || !$client) {
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
    
//    public function actionFormValidate() {
//
//        $model = new ClientsRentForm();
//        if(Yii::$app->request->isAjax && $model->load($_POST))
//        {
//            Yii::$app->response->format = 'json';
//            return \yii\widgets\ActiveForm::validate($model);
//        }
//    }

    
}
