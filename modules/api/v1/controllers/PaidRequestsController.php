<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\paidRequests\ServiceLists;
    use app\modules\api\v1\models\paidRequests\PaidServiceLists;
    use app\modules\api\v1\models\paidRequests\PaidRequestForm;

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
            'only' => ['index', 'get-services', 'info-service', 'history', 'create', 'view'],
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
            'history' => ['post'],
            'create' => ['post'],
            'View' => ['get'],
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
    
    /*
     * Все заявки на текущий лицевой счет
     * {"account": "1"}
     */
    public function actionHistory() {
        
        $data_post = Yii::$app->getRequest()->getBodyParams('account');
        
        if (empty($data_post)) {
            return ['success' => false];
        }
        
        $history_lists = PaidServiceLists::getAllPaidRequests($data_post['account']);
        
        return $history_lists ? $history_lists : ['success' => false];
    }
    
    /*
     * Создание заявки
     * {
     *      "account": "1", 
     *      "category_id": "3",
     *      "service_id": "8",
     *      "request_body": "Text of request"
     * }
     */
    public function actionCreate() {
        
        $model = new PaidRequestForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            return ['success' => true];
        } else {
            return ['success' => false];
        }
        
        
    }
    
    /*
     * Просмотр отдальной заявки на платную услугу
     */
    public function actionView($request_id) {
        
        $request_info = PaidServiceLists::getBodyRequest($request_id);
        
        return $request_info;
        
    }
    
}
