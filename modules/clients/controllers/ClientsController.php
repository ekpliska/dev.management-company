<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use app\models\User;
    use yii\web\UploadedFile;

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
    public function actionProfile() {
        
        $user = User::findOne(['user_id' => Yii::$app->user->identity->user_id]);
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
        
        return $this->render('profile', [
            'user' => $user,
        ]);
    }
    
}
