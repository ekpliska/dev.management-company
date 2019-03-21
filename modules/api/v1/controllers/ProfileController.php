<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\web\ServerErrorHttpException;
    use app\modules\api\v1\models\UserProfile;

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
        if (empty($data_post['mobile']) || empty($data_post['home_phone']) || empty($data_post['email'])) {
            return ['success' => false];
        }
        
        $model = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (!$model->updateUser($data_post)) {
            return ['success' => false];
        }
        
        return ['success' => true];
        
    }
    
    private function userProfile() {
        return UserProfile::userProfile(Yii::$app->user->id);
    }
        
}
