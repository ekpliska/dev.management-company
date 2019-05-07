<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\web\ServerErrorHttpException;
    use app\modules\api\v1\models\UserProfile;
    use app\modules\api\v1\models\profile\ChangePasswordForm;

/**
 * Профиль пользователя
 */
class ProfileController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
              HttpBasicAuth::className(),
              HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }
    
    public function verbs() {
        return [
            'index' => ['get'],
            'update' => ['post'],
            'change-password' => ['post'],
        ];
    }
    
    /*
     * Просмотр профиля
     */
    public function actionIndex() {
        
        return $this->userProfile();
        
    }
    
    /*
     * Редактирование профиля Собсвенника
     * {"mobile": "+7 (999) 999-99-99", "other_phone": "+7 (9999) 999-99-99", "email": "email@email.com"}
     */
    public function actionUpdate() {
        
        $data_post = Yii::$app->getRequest()->getBodyParams();
        if (empty($data_post['mobile']) || empty($data_post['email'])) {
            return ['success' => false];
        }
        
        $model = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (!$model->updateUser($data_post)) {
            return $model;
        }
        
        return ['success' => true];
        
    }
    
    /*
     * Редактирование профиля Арендатора
     * {"old_password": "123456", "new_password": "123456"}
     */
    public function actionChangePassword() {
        
        $user = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        
        $model = new ChangePasswordForm($user);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($result = $model->changePassword()) {
            return $result;
        } else {
            return $model;
        }
        
    }
    
    private function userProfile() {
        return UserProfile::userProfile(Yii::$app->user->id);
    }
        
}
