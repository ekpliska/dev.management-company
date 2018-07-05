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
        $rent_new = new ClientsRentForm();
        
        if ($username === null || !$user || !$client) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        if ($client->is_rent) {
            $rent = Rents::findByClient($client->id);
            $is_rent = true;
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
        
        /* 
        if (
                $user->load(Yii::$app->request->post()) && $client->load(Yii::$app->request->post()) && 
                ($rent && $rent->load(Yii::$app->request->post()))
            ) {
            $isValid = $user->validate();
            $isValid = $client->validate() && $isValid;
            if ($isValid) {
                $user->uploadPhoto($username);
                $client->save(false);
                $rent->save();
                $this->refresh();
            }
        }
        */
        
        /*
        $current_image = $user->user_photo; 
        if ($user->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($user, 'user_photo');
            if ($file) {
                $user->user_photo = $file;
                $dir = Yii::getAlias('images/users/');
                $file_name = $user->user_login . '_' . $user->user_photo->baseName . '.' . $user->user_photo->extension;
                $user->user_photo->saveAs($dir . $file_name);
                $user->user_photo = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $user->user_photo = $current_image;
            }
            
            if ($user->validate() && $user->save()) {
                return $this->refresh();
            }
        }
         */
        
        return $this->render('profile', [
            'user' => $user,
            'client' => $client,
            'rent' => $rent,
            'rent_new' => $rent_new,
            'is_rent' => $is_rent,
        ]);
    }    
    
    public function actionDeleteRent() {
        
        $rent_id = Yii::$app->request->post('rent_id');
        
       if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $status = Yii::$app->request->post('status');

            $info = Rents::findOne(['rents_id' => $rent_id]);
            
            if (!$status) {
                $info->delete();
                return 'Удаляем запись';
            }
        }
        
    }
    
    public function actionAddRent() {
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            return 'add rent';
        }
    }
    
}
