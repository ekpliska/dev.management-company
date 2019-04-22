<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\models\TokenPushMobile;

/**
 * Получение токена для рассылки push-уведомлений
 */
class TokenPushController extends Controller {
        
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index'];
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
        ];
    }
    
    /*
     * Получение токена мобильного устройства для рассылки PUSH-уведомлений
     * {"push_token": "key"}
     */
    public function actionIndex() {
        
        $data_post = Yii::$app->request->post();
        if (!isset($data_post['push_token']) || empty($data_post['push_token'])) {
            return false;
        }
        
        $token = new TokenPushMobile();
        return $token->setPushToken($data_post['push_token']);
        
    }
    
}