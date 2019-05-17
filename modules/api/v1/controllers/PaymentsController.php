<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\payments\PaymentForm;
    use app\modules\api\v1\models\payments\Payments;

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
            'only' => ['get-public-key', 'index', 'post3ds'],
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
            'post3ds' => ['post'],
        ];
    }
    
    /*
     * Получение merchantPulicId
     */
    public function actionGetPublicKey() {
        
        $key = Yii::$app->paymentSystem->public_id ? Yii::$app->paymentSystem->public_id : null;
        return ['merchantPublicId' => $key];
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
    
    /*
     * Проведение платежа
     * 
     * на API платежной системы (из POST)
     *      $md              параметр TransactionId из ответа сервера
     *      $pa_res          Значение одноименного параметра
     * 
     * на наш API
     *      $account_number  Номер лицевого счета
     *      $period          Период оплаты квитанции
     */
    public function actionPost3ds($account_number, $period) {
        
        if (empty($account_number) || empty($period)) {
            return false;
        }
        
        $data_md = Yii::$app->request->getBodyParam('MD');
        $data_pres = Yii::$app->request->getBodyParam('PaRes');
        
        $payment = Payments::setStatusPayment($data_md, $data_pres, $data_pres, $period);
        
        return $payment;
    }
    
}
