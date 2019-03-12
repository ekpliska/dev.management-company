<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\web\ServerErrorHttpException;
    use app\modules\api\v1\models\Requests;
    use app\modules\api\v1\models\RequestForm;
    

/**
 * Заявки
 */
class RequestsController extends Controller
{
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'create', 'view'],
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
            'index' => ['post'],
            'create' => ['post'], 
            'view' => ['get'],
        ];
    }
    
    /*
     * Получение всех заявок собсвенника
     * по указанному лицевому счету
     * 
     * {"account": "1"}
     */
    public function actionIndex() {
        
        $data_post = Yii::$app->getRequest()->getBodyParams();
        
        if (empty($data_post['account'])) {
            return [
                'success' => false,
                'message' => 'Не указан лицевой счет',
            ];
        }
        $requests_list = Requests::getAllRequests($data_post['account']);
        
        return $requests_list;
        
    }
    
    /*
     * Просмотр отдельной заявки
     */
    public function actionView($request_id) {
        
        $request = Requests::findRequestByID($request_id);
        return $request;
        
    }

    /*
     * Создание Заявки
     * Запрос: 
     * {
     *      "account": "1234567890", 
     *      "type_request": "Освещение", 
     *      "request_body": "Текст Заявки",
     *      "gallery": {"1": "image_1", "2": "image_2"}
     * }
     */
    public function actionCreate() {
        
        $model = new RequestForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($result = $model->save()) {
            return [
                'success' => true,
                'request_id' => $result,
            ];
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Ошибка создания создания заявки.');
        }
        return $model;
    }
    
    
}
