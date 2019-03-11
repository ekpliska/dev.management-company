<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\helpers\Html;
    use app\models\RegistrationForm;
    use app\models\signup\SignupStepOne;
    use app\models\signup\SignupStepTwo;
    use app\models\signup\SignupStepThree;
    use app\models\SmsSettings;

/**
 * Регистрация
 */
class SignupController extends Controller {
    
    public $current_array = [];
    public $user_info_array = [];
    
    const STATUS_SUCCESS_TRUE = 'success-true';
    const STATUS_SUCCESS_FALSE = 'success-false';


    public function actionIndex() {

//        Yii::$app->session->destroy();
        
        $stage = $this->setSessionStep();
        
        $model_step_one = new SignupStepOne();
        $model_step_two = new SignupStepTwo();
        $model_step_three = new SignupStepThree();
        
        $is_step_one = $this->postDataStepOne($model_step_one);
        $is_step_two = $this->postDataStepTwo($model_step_two);
        $is_step_three = $this->postDataStepThree($model_step_three);
        
        return $this->render('index', [
            'stage' => $stage,
            'model_step_one' => $model_step_one,
            'model_step_two' => $model_step_two,
            'model_step_three' => $model_step_three,
        ]);
        
    }
    
    /*
     * Смена шага регистрации
     * Каждый успешный шаг записывается в куку
     */
    private function setSessionStep($step = null) {
        
        $session = Yii::$app->session;
        
        if (!isset($session['step_one'])) {
            $session['step_one'] = self::STATUS_SUCCESS_TRUE;
            $session['step_two'] = self::STATUS_SUCCESS_FALSE;
            $session['step_three'] = self::STATUS_SUCCESS_FALSE;
            return true;
        }
        
    }
    
    /*
     * Обработка данных рагистрации шаг один
     */
    private function postDataStepOne($data) {
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {
            $post_data = Yii::$app->request->post()['SignupStepOne'];
            if ($this->setSessionStepOne($post_data)) {
                return Yii::$app->session->get('count_step');
            }
        }
    }
    
    
    /*
     * Обработка данных рагистрации шаг два
     */
    private function postDataStepTwo($data) {
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {
            $post_data = Yii::$app->request->post()['SignupStepTwo'];
            if ($this->setSessionStepTwo($post_data)) {
                return Yii::$app->session->get('count_step');
            }
        }
    }
    
    /*
     * Обработка данных рагистрации шаг три
     */
    private function postDataStepThree($data) {
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {
            $post_data = Yii::$app->request->post()['SignupStepThree'];
            if ($this->setSessionStepThree($post_data)) {
                $this->setCurrentRegisterData();
                
                $model = new RegistrationForm();
                if ($model->registration($this->current_array)) {
                    $this->setSessionStep();
                    Yii::$app->session->setFlash('success', 
                            'Благодарим за регистрацию. Для входа на портал используйте номер вашего лицевого счета и пароль указанные при регистрации. '
                            . Html::a('Перейти на страницу входа', ['site/login']));
                    return true;
                }
                $this->setSessionStep();
                Yii::$app->session->setFlash('error', 'К сожаления во время регистрации произошла ошибка. Повторите попытку позже');
                return false;
            }
        }
        return false;
    }    
    
    /*
     * Запись в сессию данных первого шага регистрации
     */
    private function setSessionStepOne($data) {
        
        if ($data == null) {
            return false;
        }
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        $session['account'] = $data['account_number'];
        $session['last_summ'] = $data['last_summ'];
        $session['square'] = $data['square'];
        $session['step_one'] = self::STATUS_SUCCESS_FALSE;
        $session['step_two'] = self::STATUS_SUCCESS_TRUE;
        $session['step_three'] = self::STATUS_SUCCESS_FALSE;
        
        return true;
    }
    
    
    /*
     * Запись в сессию данных первого шага регистрации
     */
    private function setSessionStepTwo($data) {

        if ($data == null) {
            return false;
        }
        
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        
        $session['email'] = $data['email'];
        $session['password'] = $data['password'];
        $session['step_one'] = self::STATUS_SUCCESS_FALSE;
        $session['step_two'] = self::STATUS_SUCCESS_FALSE;
        $session['step_three'] = self::STATUS_SUCCESS_TRUE;
        
        return true;
    }
    
    /*
     * Запись в сессию данных первого шага регистрации
     */
    private function setSessionStepThree($data) {

        if ($data == null) {
            return false;
        }
        
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        
        $session['phone'] = $data['phone'];
        $session['count_step'] = 3;
        
        return true;
    }
    
    /*
     * Отправка СМС кода на указанный номер телефона
     */
    public function actionSendSmsToRegister() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $phone = Yii::$app->request->post('phoneNumber');
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            // Генерируем СМС код, и записываем его в сессию
            $sms_code = mt_rand(10000, 99999);
            // Отправляем смс на указанный номер телефона
            if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_REGISTER, $phone, $sms_code)) {
                return ['success' => false, 'message' => $result];
            }
            Yii::$app->session->set('sms_code', $sms_code);
            return ['success' => true, 'sms_code' => $sms_code];
        }
        
    }
    
    private function setCurrentRegisterData() {
        
        return $this->current_array = [
            'account' => Yii::$app->session->get('account'),
            'last_summ' => Yii::$app->session->get('last_summ'),
            'square' => Yii::$app->session->get('square'),
            'email' => Yii::$app->session->get('email'),
            'password' => Yii::$app->session->get('password'),
            'phone' => Yii::$app->session->get('phone'),
            'user_info' => Yii::$app->session->get('UserInfo'),
        ];
    }

}
