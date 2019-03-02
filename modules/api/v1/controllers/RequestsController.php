<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\web\ServerErrorHttpException;
    

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
            'only' => ['create', 'view'],
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
            'create' => ['post'], 
            'view' => ['get'],
        ];
    }    

    /*
     * Создание Заявки
     * Запрос: 
     * {
     *      "account": "1234567890", 
     *      "type_request": "Освещение", 
     *      "body_request": "Текст Заявки",
     *      "user_mobile": "+7 (999) 999-99-99",
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
