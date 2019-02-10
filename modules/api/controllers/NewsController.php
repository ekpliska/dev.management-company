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
class NewsController extends Controller {
    
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
            'index' => ['post']
        ];
    }
    
    public function actionIndex($id) {
        
        $user = Yii::$app->user->id;
        
        return $id;
        
    }   
    
    
}
