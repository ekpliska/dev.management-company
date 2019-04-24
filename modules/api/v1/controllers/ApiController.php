<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use app\modules\api\v1\models\LoginForm;
    use app\modules\api\v1\models\RegisterForm;

/**
 * API контроллер
 */
class ApiController extends Controller
{
    
    protected function verbs() {
        
        return [
            'login' => ['post'],
            'sign-up' => ['post'],
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
     * {"phone": "+7 (000) 000-00-00"}
     */
    public function actionResetPassword() {
    }
    
    private function sendSmsCode($phone) {
        
        // Формируем случайный смс-код
        $sms_code = mt_rand(10000, 99999);
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $sms_code = mt_rand(10000, 99999);
        // Отправляем смс на указанный номер телефона
        if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_REGISTER, $phone, $sms_code)) {
            return ['success' => false, 'message' => $result];
        }
        return true;
    }
    
    
}
