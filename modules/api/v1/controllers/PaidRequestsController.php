<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\paidRequests\ServiceLists;

/**
 * Платные услуги
 */
class PaidRequestsController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'get-services', 'info-service'],
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
            'index' => ['get'],
            'get-services' => ['get'],
            'info-service' => ['get'],
        ];
    }

    /*
     * Все услуги по категориям
     */
    public function actionIndex() {
        
        $service_lists = ServiceLists::gellFullServiceList();
        return $service_lists ? $service_lists : ['success' => false];
        
    }
    
    /*
     * Услуги по заданной категории
     */
    public function actionGetServices($category_id) {
        
        $service_lists = ServiceLists::getServicesByCatgory($category_id);
        return $service_lists ? $service_lists : ['success' => false];
        
    }

    /*
     * Информация по отдельной услуги
     */
    public function actionInfoService($service_id) {
        
        $service_info = ServiceLists::getServiceInfo($service_id);
        return $service_info ? $service_info : ['success' => false];
        
    }
    
}
