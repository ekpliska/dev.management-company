<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\modules\api\v1\models\RentForm;
    use app\modules\api\v1\models\Rent;
    
/**
 * Арендатор
 */
class RentController extends Controller {
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete', 'view'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete', 'edit'],
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
            'update' => ['post'],
            'delete' => ['get'],
        ];
    }    
    
    /*
     * Добавление арендатора
     */
    public function actionCreate() {
        
        $model = new RentForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            return ['success' => true];
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Ошибка создания учетной записи арендатора.');
        }
        return $model;
        
    }
    
    /*
     * Просмотр, профиля арендатора
     */
    public function actionView($account) {
        
        $rent_info = Rent::rentInfo($account);
        
        if (empty($rent_info)) {
            return ['success' => false];
        }
        unset($rent_info['user'], $rent_info['account']);
        return $rent_info;
    }
    
    /*
     * Редактирование, профиля арендатора
     * {
     *      "rents_name": "Фамилия", 
     *      "rents_second_name": "Имя", 
     *      "rents_surname": "Отчество", 
     *      "rents_mobile": "+7 (000) 111-11-11",
     *      "email": "rent_email@gmail.com"
     * }
     */
    public function actionUpdate($rent_id) {
        
        $rent_info = Rent::findOne(['rents_id' => $rent_id]);
        if (empty($rent_info)) {
            return ['success' => false];
        }
        
        $rent_info->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($rent_info->updateInfo()) {
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /*
     * Удаление арендатора
     */
    public function actionDelete($rent_id) {
        
        $rent_info = Rent::findOne(['rents_id' => $rent_id]);
        if (empty($rent_info)) {
            return ['success' => false];
        }
        
        if (!$rent_info->delete()) {
            return ['success' => false];
        }
        
        return ['success' => true];
        
        
    }
    
}