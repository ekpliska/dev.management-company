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
        $behaviors['authenticator']['only'] = ['index', 'switch-email', 'switch-push'];
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
            'index' => ['post'],
            'switch-email' => ['post'],
            'switch-push' => ['post'],
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
        
        $user = $this->getUser();
        $settings = new Settings($user, $data_post);
        return $settings->getSettings($data_post);
        
    }
    
    /*
     * Смена состояния email-уведомлений
     * {"enabled":true/false}
     */
    public function actionSwitchEmail() {
        $data_post = Yii::$app->request->getBodyParam('enabled');
        if (!isset($data_post) && $data_post == null) {
            return false;
        }
        $user = $this->getUser();
        $settings = new Settings($user);
        return $settings->switchStatusEmail((bool)$data_post);        
    }
    
    /*
     * Смена состояния push-уведомлений
     * {
     *      "token": "string key",
     *      "enabled": true/false}
     * }
     */
    public function actionSwitchPush() {
        $token_post = Yii::$app->request->getBodyParam('token');
        $enabled_post = Yii::$app->request->getBodyParam('enabled');
        if ((!isset($token_post) || !isset($token_post)) && (empty($token_post) || $enabled_post)) {
            return false;
        }
        $user = $this->getUser();
        $settings = new Settings($user, $token_post);
        return $settings->switchStatusPush((bool)$enabled_post);        
    }
    
    private function getUser() {
        return User::findOne(['user_id' => Yii::$app->user->getId()]);
    }
    
}
