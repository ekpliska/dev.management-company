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
            'post3ds' => ['get'],
        ];
    }
    
    /*
     * Получение merchantPulicId
     */
    public function actionGetPublicKey() {
        
        $key = Yii::$app->params['payments_system']['publicId'] ? Yii::$app->params['payments_system']['publicId'] : null;
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
     * на API платежной системы
     *      $md              параметр TransactionId из ответа сервера
     *      $pa_res          Значение одноименного параметра
     * 
     * на наш API
     *      $account_number  Номер лицевого счета
     *      $period          Период оплаты квитанции
     */
    public function actionPost3ds($account_number, $period) {
        
        if (empty($md) || empty($pa_res) || empty($account_number) || empty($period)) {
            return false;
        }
        
        $payment = Payments::setStatusPayment($md, $pa_res, $account_number, $period);
        
        return 'here';
    }
    
}
