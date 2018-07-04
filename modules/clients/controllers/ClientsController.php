<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use yii\web\UploadedFile;
    use app\models\User;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Clients;
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

        $user = User::findOne(['user_login' => $username]);
        // $current_image = $user->user_photo;
        
        if ($username === null || !$user) {
            throw new NotFoundHttpException('Вы обратились к несуществющей странице');
        }
        
        $client = Clients::findByUser($user->user_account_id);
        
        $clients_rent = new ClientsRentForm();
        
        if ($clients_rent->load(Yii::$app->request->post())) {
            $clients_rent->addNewClient();
        }
        
        if ($user->load(Yii::$app->request->post())) {
            
            if ($user->uploadPhoto($username)) {
                $this->refresh();
            }
        }
        
        /*
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
            'clients_rent' => $clients_rent,
            'client' => $client,
        ]);
    }
    
}
