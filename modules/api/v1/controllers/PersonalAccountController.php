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
            'payments-history' => ['POST'],
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
     * {"period_start": "2018-04", "period_end": "2019-01"}
     */
    public function actionPaymentsHistory($account) {
        
        $data_post = Yii::$app->request->getBodyParams();
        
        $date_start = empty($data_post['period_start']) ? null : "{$data_post['period_start']}-01";
        $date_end = empty($data_post['period_end']) ? date('Y-m-d') : "{$data_post['period_end']}-28";
        
        $array_request = [
            'Номер лицевого счета' => $account,
            'Период начало' => Yii::$app->formatter->asDate($date_start, 'YYYY-MM-d'),
            'Период конец' => Yii::$app->formatter->asDate($date_end, 'YYYY-MM-d'),
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $payments_lists = Yii::$app->client_api->getPayments($data_json);
        return $payments_lists;
        
    }
    
    /*
     * Создание лицевого счета
     * {"account_number": "20", "last_sum": "4036.85", "square": "59.8"}
     */
    public function actionCreate() {
        
        if (Yii::$app->user->can('clients_rent')) {
            return [
                'success' => false,
                'message' => 'Недопустимое действие для данной учетной записи'
            ];
        }
        
        $model = new CreateAccount();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if (!$model->save()) {
            return $model;
        }
        return ['success' => true, 'message' => "Лицевой счет {$model->account_number} успешно создан"];
    }
    
}
