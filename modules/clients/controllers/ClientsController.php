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
    public function actionProfile($username) {
        
        $is_rent = false;

        $user = User::findOne(['user_login' => $username]);
        $client = Clients::findOne(['clients_account_id' => $user->user_account_id]);        
        
        if ($username === null || !$user || !$client) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        if ($client->is_rent) {
            $rent = Rents::findByRent($client->clients_id);
            $is_rent = true;
        }
        
        $new_rent = new ClientsRentForm();
        
        if ($new_rent->load(Yii::$app->request->post('registration-form'))) {
            return var_dump('test');
        }

        if (
                $user->load(Yii::$app->request->post()) && 
                $client->load(Yii::$app->request->post())
            ) {
            $isValid = $user->validate();
            $isValid = $client->validate() && $isValid;
            if ($isValid) {
                Yii::$app->session->setFlash('success', 'Профиль обновлен');
                $user->uploadPhoto($username);
                $client->save(false);
                
                if ($rent && $rent->load(Yii::$app->request->post()) && $rent->validate()) {                    
                    $rent->save();
                }
                
                return $this->refresh();
            } else {
                Yii::$app->session->set('error', 'Произошла ошбка');
            }
        }
        
        return $this->render('profile', [
            'user' => $user,
            'client' => $client,
            'rent' => $rent,
            // 'rent_new' => $rent_new,
            'is_rent' => $is_rent,
        ]);
    }    
    
    public function actionDeleteRent() {
        
        $rent_id = Yii::$app->request->post('rent_id');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $status = Yii::$app->request->post('status');

            $info = Rents::findOne(['rents_id' => $rent_id]);
            $user = User::findOne(['user_rent_id' => $rent_id]);
            
            if (!$status) {
                $info->delete();
                $user->delete();
                return 'Удаляем запись';
            }
        }
        
    }
    
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
    
    public function actionTest($client_id) {
        
//        $model = new ClientsRentForm();
//        $rent_id = Yii::$app->request->post('rent_id');
//        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
//            if ($model->load(Yii::$app->request->post())) {
//                $model->addNewClient();
//                return $rent_id;
//            }
//        }
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
