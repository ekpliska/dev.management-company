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
        $behaviors['authenticator']['only'] = ['view'];
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
        ];
    }
    
    /*
     * Получить список приборов учета по текущему лицевому счету
     */
    public function actionView($account) {
        
        $personal_account = PersonalAccount::findOne(['account_number' => $account]);
        if (empty($personal_account)) {
            return ['sucess' => false];
        }
        
        $counters = new Counters($personal_account);
        return $counters->getCountesList();
        
    }
    
}
