<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use yii\helpers\Url;
    use app\models\Payments;

/**
 * Квитанции
 */
class ReceiptsController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['get-receipts', 'get-receipt-pdf'];
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
            'get-receipts' => ['post'],
            'get-receipt-pdf' => ['post'],
        ];
    }
    
    /*
     * Получить список всех квитанций по текущему Лицевому счету
     * Сразу все или за указанный приод
     * {"period_start": "2018-04", "period_end": "2019-01"}
     */
    public function actionGetReceipts($account) {
        
        $data_post = Yii::$app->request->getBodyParams();
        
        $date_start = empty($data_post['period_start']) ? null : $data_post['period_start'];
        $date_end = empty($data_post['period_end']) ? date('Y-n') : $data_post['period_end'];
        
        $receipts_lists = $this->getReceiptList($account, $date_start, $date_end);
        
        return $receipts_lists;
        
    }
    
    /*
     * Получить квитанцию
     * {"account": "1", "period": "2018-09"}
     */
    public function actionGetReceiptPdf() {
        
        $data_post = Yii::$app->request->getBodyParams();
        if (empty($data_post['account']) || empty($data_post['period'])) {
            return ['success' => false];
        }
        
        // Получаем даные по квитанции
        $get_receipt = $this->getReceiptList($data_post['account'], $data_post['period'], $data_post['period']);
        // Устанавливаем статус для квитанции
        $status_payment = isset($get_receipt[0]['Статус квитанции']) == 'Не оплачена' ? 'not paid' : 'paid';
        // Если статус "Не оплачена", то проверяем наличие платежа на нашей стороне
        if (isset($get_receipt[0]['Статус квитанции']) === 'Не оплачена') {
            // Проверяем статус платежа по текущей квитанции
            $status_payment = Payments::getStatusPayment($data_post['period'], $data_post['account']);
        }
        
        // Формируем путь в PDF квитацнии на сервере
        $file_path = Yii::getAlias('@web') . "receipts/" . $data_post['account'] . "/" . $data_post['period'] . ".pdf";
        if (!file_exists($file_path)) {
            return [
                'message' => "Приносим извинения. Квитанция {$data_post['period']} на сервере не найдена.",
                'status_payment' => $status_payment
            ];
        } else {
            // Возвращаем абсолютный путь и статус платежа
            return [
                'receipt_pdf' => Url::base(true) . '/' . $file_path,
                'status_payment' => $status_payment];
        }
        
    }
    
    /*
     * Запрос на выдачу квитанций/квитанции
     */
    private function getReceiptList($account, $date_start, $date_end) {
        
        $array_request = [
            'Номер лицевого счета' => $account,
            'Период начало' => $date_start,
            'Период конец' => $date_end,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        return Yii::$app->client_api->getReceipts($data_json);
        
    }
    
}
