<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use app\modules\api\v1\models\LoginForm;
    use app\modules\api\v1\models\RegisterForm;
    use app\modules\api\v1\models\ResetPassword;

/**
 * API контроллер
 */
class ApiController extends Controller
{
    
    protected function verbs() {
        
        return [
            'login' => ['post'],
            'sign-up' => ['post'],
            'finish' => ['get'],
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
    
    /*
     * Регистрация
     */
    public function actionSignUp($step = 'one') {
        
        switch ($step) {
            // Проверка существования лицевого счета
            case 'one':
                // {"account_number": "5", "last_summ": "5885.59", "square": "59.90"}
                $data_step_one = Yii::$app->request->bodyParams;
                $result = RegisterForm::registerStepOne($data_step_one);
                break;
            // Проверка email
            case 'two':
                // {"email": "client_123@gmail.com", "password": "123456"}
                $data_step_two = Yii::$app->request->bodyParams;
                $result = RegisterForm::registerStepTwo($data_step_two);
                break;
            // Отправка СМС-кода
            case 'three':
                // {"phone": "+7 (999) 999-99-99"}
                $data_step_three = Yii::$app->request->bodyParams;
                $result = RegisterForm::registerStepThree($data_step_three);
                break;
            // Завершение регистрации
            case 'finish':
                $result = RegisterForm::registerStepFinish();
                break;
        }
        
        return $result;
        
    }
    
    /*
     * Восстановление пароля
     */
    public function actionResetPassword() {
    }
    
    
}
