<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\payments\PaymentForm;

/**
 * Платежная система
 */
class PaymentsController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['get-public-key', 'index'],
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
            'get-public-key' => ['get'],
            'index' => ['post'],
        ];
    }
    
    /*
     * Получение merchantPulicId
     */
    public function actionGetPublicKey() {
        
        $key = Yii::$app->params['payments_system']['publicId'] ? Yii::$app->params['payments_system']['publicId'] : null;
        return [
            'merchantPublicId' => $key,
        ];
    }
    
    
    /*
     * Отправка данных на платеж
     * {
     *      "account_number": "",
     *      "receipt_period": "",
     *      "receipt_num": "",
     *      "receipt_sum": "",
     *      "client_name": "",
     *      "cryptogram_packet": ""
     * }
     */
    public function actionIndex() {
        
        $model = new PaymentForm();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if (!$result = $model->sendPayment()) {
            return $model;
        }
        return $result;
    }
    
}
