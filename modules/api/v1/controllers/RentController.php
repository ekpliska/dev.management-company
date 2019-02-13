<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\ActiveController;
    use yii\helpers\Url;
    use app\modules\api\v1\models\RentForm;
    use app\modules\api\v1\models\Rent;
    
/**
 * Арендатор
 */
class RentController extends ActiveController {
    
    public $modelClass = 'app\models\Rents';
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }
    
    public function actions() {
        
        $actions = parent::actions();
        
        // Удаляем действие Index
        unset($actions['index']);
        // Удаляем действие Create, переопределяем свой
        unset($actions['create']);
        unset($actions['view']);

        return $actions;
    }
    
    public function actionCreate() {
        
        $model = new RentForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($rent_id = $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'rent_id' => $rent_id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Ошибка создания учетной записи арендатора.');
        }
        return $model;
        
    }
    
    public function actionView($rent_id) {
        
        $rent_info = Rent::rentInfo($rent_id);
        
        if (empty($rent_info)) {
            return ['success' => false];
        }

        unset($rent_info['user']);
        
        return $rent_info;
    }
    
}

//{
//  "rents_name": "два",
//  "rents_surname": "раз", 
//  "rents_second_name": "раз", 
//  "rents_mobile": "+7 (123) 123-70-70", 
//  "rents_email": "jhk@dsdf.sdf", 
//  "client_id": "1", 
//  "password": "123456", 
//  "password_repeat": "123456",
//  "account_number": "123123"
//}