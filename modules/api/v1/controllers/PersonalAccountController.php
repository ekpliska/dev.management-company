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
        $behaviors['authenticator']['only'] = ['view', 'payments-history', 'create'];
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
