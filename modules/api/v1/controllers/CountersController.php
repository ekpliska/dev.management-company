<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\models\PersonalAccount;
    use app\modules\api\v1\models\counters\Counters;

/**
 * Приборы учета
 */
class CountersController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['view', 'get-counter', 'send-indications', 'order-request'];
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
            'get-counter' => ['post'],
            'send-indications' => ['post'],
            'order-request' => ['get'],
        ];
    }
    
    /*
     * Получить список приборов учета по текущему лицевому счету
     */
    public function actionView($account) {
        
        $personal_account = $this->findAccount($account);
        if (!$personal_account) {
            return ['success' => false];
        }
        
        $counters = new Counters($personal_account);
        return $counters->getCountesList();
        
    }
    
    /*
     * Получить показания по текущему прибору учета
     * {"month": "04", "year": "2019"}
     */
    public function actionGetCounter($account, $id_counter) {
        
        $personal_account = $this->findAccount($account);
        if (!$personal_account) {
            return ['success' => false];
        }
        
        $data_post = Yii::$app->request->getBodyParams();
        $month = isset($data_post['month']) ? $data_post['month'] : null;
        $year = isset($data_post['year']) ? $data_post['year'] : null;
        
        $counters = new Counters($personal_account, $month, $year);
        return $counters->getCounterInfo($id_counter);
        
    }
    
    /*
     * Отправка показаний
     * {"counter_id": "1", "indication": "4"}
     */
    public function actionSendIndications($account) {

        $personal_account = $this->findAccount($account);
        if (!$personal_account) {
            return ['success' => false];
        }

        $data_post = Yii::$app->request->getBodyParams();
        $counters = new Counters($personal_account);
        
        return $counters->setIndication($data_post);
        
    }
    
    /*
     * Сформировать автоматическую заявку на "Поверку прибора учета"
     */
    public function actionOrderRequest($account, $id_counter) {
        
        $personal_account = $this->findAccount($account);
        if (!$personal_account) {
            return ['success' => false];
        }
        
        $counters = new Counters($personal_account);
        return $counters->order($id_counter);
        
    }
    
    /*
     * Проверка существования лицевого счета
     */
    private function findAccount($account) {
        
        $personal_account = PersonalAccount::findOne(['account_number' => $account]);
        if (empty($personal_account)) {
            return false;
        }
        return $personal_account;
    }
    
    
}
