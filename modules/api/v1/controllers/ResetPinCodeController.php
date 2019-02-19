<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
/**
 * Восстановление PIN кода на мобильно устройствие
 */
class ResetPinCodeController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view'],
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
     * На экшен приходит номер мобильного телефона
     * $param string $post_data {"mobile_phone": "+7 (999) 999-99-99"}
     */
    public function actionIndex() {
        
        $post_data = Yii::$app->request->bodyParams;
        
        if (!isset($post_data['mobile_phone']) || empty($post_data['mobile_phone'])) {
            return [
                'success' => false,
            ];
        }
        
        // Генерируем случайное число, СМС код
        $sms_code = rand(10000, 99999);
        
        // Отправляем СМС пользователю
//        Yii::$app->sms->send_sms($post_data, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "");
        
        return [
            'success' => true,
            'sms_code' => $sms_code,
        ];
        
    }
    
    
}
