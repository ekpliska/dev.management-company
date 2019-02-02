<?php

    namespace app\modules\api\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\web\ServerErrorHttpException;
    use app\modules\api\models\UserProfile;

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
            'update' => ['put', 'patch'],
        ];
    }
    
    public function actionIndex() {
        
        return $this->userProfile();
        
    }
    
    private function userProfile() {
        return UserProfile::userProfile(Yii::$app->user->id);
    }
    
    public function actionUpdate() {
        
        $model = \app\models\User::findOne(Yii::$app->user->id);
        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Ошибка обновления профиля');
        }
        return $model;
    }
    
    
    
}
