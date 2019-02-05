<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use app\modules\api\v1\models\LoginForm;

/**
 * API контроллер
 */
class ApiController extends Controller
{
    
    protected function verbs() {
        
        return [
            'login' => ['post'],
        ];
    }
    
    /**
     * Загрушка на /index
     */
    public function actionIndex() {
        return 'api';
    }
    
    /*
     * Авторизация
     */
    public function actionLogin() {
        
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model;
        }
        
    }
    
    
    
}
