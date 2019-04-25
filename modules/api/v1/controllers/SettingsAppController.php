<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\settings\Settings;
    use app\models\User;

/**
 * Настройки мобильного приложения
 */
class SettingsAppController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index'],
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
            'index' => ['post'],
        ];
    }
    
    /*
     * Запрос состояния push, email - уведомлений
     * {"token": "string"}
     */
    public function actionIndex() {
        
        $data_post = Yii::$app->request->getBodyParam('token');
        if (!isset($data_post) && $data_post == null) {
            return false;
        }
        
        $user = User::findOne(['user_id' => Yii::$app->user->getId()]);
        $settings = new Settings($user, $data_post);
        return $settings->getSettings($data_post);
        
    }
    
}
