<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\modules\api\v1\models\personalAccount\Info;
    use app\modules\api\v1\models\personalAccount\CreateAccount;
    
/**
 * Лицевой счет
 */
class PersonalAccountController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['view', 'payments-history', 'create', 'find-history-payments'];
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
            'payments-history' => ['get'],
            'create' => ['post'],
            'find-history-payments' => ['post']
        ];
    }
    
    /*
     * Информация по лицевому счету
     */
    public function actionView($account) {
        
        $account_info = Info::getInfo($account);
        
        return $account_info ? $account_info : ['success' => false];
    }
    
    /*
     * История платежей
     */
    public function actionPaymentsHistory($account) {
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-m-d');
        
        $array_request = [
            'Номер лицевого счета' => $account,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $payments_lists = Yii::$app->client_api->getPayments($data_json);
        
        return $payments_lists ? $payments_lists : ['success' => false];
        
    }
    
    /*
     * Поиск по истории платежей
     * {"period_start": "2018-04", "period_end": "2019-01"}
     */
    public function actionFindHistoryPayments($account) {
        
        $data_post = Yii::$app->request->getBodyParams();
        if (empty($data_post['period_start']) || empty($data_post['period_end'])) {
            return ['success' => false];
        }
        
        $date_start = Yii::$app->formatter->asDate($data_post['period_start'], 'YYYY-MM');
        $date_end = Yii::$app->formatter->asDate($data_post['period_end'], 'YYYY-MM');
        
        $data_array = [
            'Номер лицевого счета' => $account,
            'Период начало' => "{$date_start}-01",
            'Период конец' => "{$date_end}-01"
        ];        
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        $payments_lists = Yii::$app->client_api->getPayments($data_json);
        
        return $payments_lists;
        
    }
    
    /*
     * Создание лицевого счета
     * {"account_number": "20", "last_sum": "4036.85", "square": "59.8"}
     */
    public function actionCreate() {
        
        $model = new CreateAccount();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if (!$model->save()) {
            return $model;
        }
        return ['success' => true];
    }
    
}
