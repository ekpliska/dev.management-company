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
        
        unset($actions['index'], $actions['create'], $actions['view']);

        return $actions;
    }
    
    public function actionCreate() {
        
        $model = new RentForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($rent_id = $model->save()) {
            return ['success' => true];
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