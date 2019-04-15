<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use yii\helpers\Url;

/**
 * Квитанции
 */
class ReceiptsController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['view', 'get-receipts', 'get-receipt-pdf'];
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
            'view' => ['get'],
            'get-receipts' => ['post'],
            'get-receipt-pdf' => ['post'],
        ];
    }
    
    /*
     * Получить список всех квитанций по текущему Лицевому счету
     */
    public function actionView($account) {
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-n');
        
        $array_request = [
            'Номер лицевого счета' => $account,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
        return $receipts_lists;
        
    }
    
    /*
     * Запрос квитанций по заданному периоду
     * {"period_start": "2018-04", "period_end": "2019-01"}
     * 
     * Format of date is YYYY-MM
     */
    public function actionGetReceipts($account) {
        
        $data_post = Yii::$app->request->getBodyParams();
        if (empty($data_post['period_start']) || empty($data_post['period_end'])) {
            return ['success' => false];
        }
        
        $date_start = Yii::$app->formatter->asDate($data_post['period_start'], 'YYYY-MM');
        $date_end = Yii::$app->formatter->asDate($data_post['period_end'], 'YYYY-MM');
        
        $data_array = [
            'Номер лицевого счета' => $account,
            'Период начало' => $date_start,
            'Период конец' => $date_end
        ];        
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
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
        
        // Формируем путь в PDF квитацнии на сервере
        $file_path = Yii::getAlias('@web') . "receipts/" . $data_post['account'] . "/" . $data_post['period'] . ".pdf";
        
        if (!file_exists($file_path)) {
            return ['message' => "Приносим извинения. Квитанция {$data_post['period']} на сервере не найдена."];
        } else {
            // Возвращаем абсолютный путь
            return ['receipt_pdf' => Url::base(true) . $file_path];
        }
        
    }
    
}
