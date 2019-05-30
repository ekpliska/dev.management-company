<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use yii\helpers\Url;
    use app\models\Payments;
    use app\models\SiteSettings;
    use app\models\PersonalAccount;

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
        
        // Получаем даные по квитанции
        $get_receipt = $this->getReceiptList($data_post['account'], $data_post['period'], $data_post['period']);
        // Определяем статус квитанции
        $status_payment = isset($get_receipt) ? $get_receipt[0]['Статус квитанции'] : null;
        // Проверяем статус платежа по текущей квитанции
        if ($status_payment == 'Не оплачена') {
            $status_payment = Payments::getStatusPayment($data_post['period'], $data_post['account']);
        } else {
            $status_payment = 'paid';
        }
        
        // Получаем ID дома для текущего лицевого счета
        $house_id = $this->getHouseID($data_post['account']);
        
        // Получаем путь к квитанциям
        $path_to_receipts = SiteSettings::getReceiptsUrl();
        // Формируем url для квитанции
        $file_path = $path_to_receipts . "{$house_id}/{$data_post['period']}/{$data_post['account']}.pdf";
        $headers = @get_headers($file_path);
        if (strpos($headers[0], '200')) {
            return [
                'receipt_pdf' => $file_path,
                'status_payment' => $status_payment];
        } else {
            return [
                'message' => "Приносим извинения. Квитанция {$data_post['period']} на сервере не найдена.",
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
    
    /*
     * Формирование url квитанции
     */
    private function getHouseID($account) {
        
        $query = PersonalAccount::find()
                ->select(['houses_id'])
                ->joinWith(['flat', 'flat.house'])
                ->where(['account_number' => $account])
                ->asArray()
                ->one();
        
        return $query['houses_id'];
    }
    
}
