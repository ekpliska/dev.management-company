<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use app\modules\api\v1\models\LoginForm;
    use app\modules\api\v1\models\RegisterForm;
    use app\models\SmsSettings;
    use app\models\User;
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
            'send-sms' => ['post'],
            'reset-password' => ['post']
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
     * {"sms_code": "code"}
     */
    public function actionResetPassword() {
        
        $_code = Yii::$app->request->getBodyParam('sms_code');
        
        if (!Yii::$app->session->has('user_phone') || !Yii::$app->session->has('sms_code')) {
            return ['message' => 'Не верно указан смс код'];
        }        
        // Берем данные из сессии
        $phone = Yii::$app->session->get('user_phone');
        $sms_code = Yii::$app->session->get('sms_code');
        // Ищем пользователя по номеру телефона
        $user = User::findOne(['user_mobile' => $phone]);
        
        if ($user == null) {
            return ['message' => 'Пользователь с указанным номером телефона не найден'];
        }
        if ($sms_code != $_code) {
            return ['message' => 'Не верно указан смс код'];
        }
        
        // Модель смены номера телефона
        $model = new ResetPassword($user, $sms_code);
        if (!$model->changePassword()) {
            return ['message' => 'Ошибка сброса пароля. Попробуйте ещё раз'];
        }
        
        return ['success' => true];
    }
    
    /*
     * Отправка СМС кода на смену пароля учетной записи
     * {"phone": "+7 (000) 000-00-00"}
     */
    public function actionSendSms() {
        
        Yii::$app->session->destroy();
        
        $_phone = Yii::$app->request->getBodyParam('phone');
        if (empty($_phone) || !isset($_phone)) {
            return ['message' => 'Укажите номер телефона'];
        }
        
        // Формируем случайный смс-код
        $sms_code = mt_rand(10000, 99999);
        $phone = preg_replace('/[^0-9]/', '', $_phone);
        
        Yii::$app->session['user_phone'] = $_phone;
        Yii::$app->session['sms_code'] = $sms_code;
        
        // Отправляем смс на указанный номер телефона
        if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_RECOVERY_PASSWORD, $phone, $sms_code)) {
            return ['success' => false, 'message' => $result];
        }
        
        return ['sucess' => true];
        
    }
    
    
}
