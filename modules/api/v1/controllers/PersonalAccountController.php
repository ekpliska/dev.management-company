<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\modules\api\v1\models\personalAccount\Info;
    
/**
 * Лицевой счет
 */
class PersonalAccountController extends Controller {
    
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
     * Информация по лицевому счету
     */
    public function actionView($account) {
        
        $account_info = Info::getInfo($account);
        
        return $account_info ? $account_info : ['success' => false];
        
    }
    
}
