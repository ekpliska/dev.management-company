<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use app\models\RegistrationForm;
    use app\models\User;
    use app\models\signup\SignupStepOne;
    use app\models\signup\SignupStepTwo;
    use app\models\signup\SignupStepThree;

/**
 * Регистрация
 */
class SignupController extends Controller {
    
    const STEP_ONE = 'one';
    const STEP_TWO = 'two';
    const STEP_THREE = 'three';


    public $current_array = [];
    public $user_info_array = [];    


    public function actionIndex() {
        
        $model_step_one = new SignupStepOne();
        $model_step_two = new SignupStepTwo();
        $model_step_three = new SignupStepThree();
        
        $is_step_one = $this->postDataStepOne($model_step_one);
        $is_step_two = $this->postDataStepTwo($model_step_two);
        $is_step_three = $this->postDataStepThree($model_step_three);
        
        return $this->render('index', [
            'model_step_one' => $model_step_one,
            'model_step_two' => $model_step_two,
            'model_step_three' => $model_step_three,
            'is_step_one' => $is_step_one,
            'is_step_two' => $is_step_two,
            'is_step_three' => $is_step_three,
        ]);
        
    }
    
    /*
     * Обработка данных рагистрации шаг один
     */
    private function postDataStepOne($data) {
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {
            $post_data = Yii::$app->request->post()['SignupStepOne'];
            if ($this->setSessionSteps(self::STEP_ONE, $post_data)) {
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
            if ($this->setSessionSteps(self::STEP_TWO, $post_data)) {
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
            if ($this->setSessionSteps(self::STEP_THREE, $post_data)) {
                $this->setCurrentRegisterData();
                
                var_dump($this->current_array); die();
                $model = new RegistrationForm();
                $model->registration($this->current_array);
                return $this->redirect(['site/login']);
            }
        }
        return false;
    }    
    
    private function setSessionSteps($step_number, $data) {
        
        if ($step_number == null || $data == null) {
            return false;
        }
        
        $session = Yii::$app->session;
        
        switch ($step_number) {
            case self::STEP_ONE:
                $session['account'] = $data['account_number'];
                $session['last_summ'] = $data['last_summ'];
                $session['square'] = $data['square'];
                $session['count_step'] = 1;
                $this->setCurrentRegisterData(self::STEP_ONE);
                break;
            case self::STEP_TWO:
                $session['email'] = $data['email'];
                $session['password'] = $data['password'];
                $session['count_step'] = 2;
                $this->setCurrentRegisterData(self::STEP_TWO);
                break;
            case self::STEP_THREE:
                $session['phone'] = $data['phone'];
                $session['count_step'] = 3;
                $this->setCurrentRegisterData(self::STEP_THREE);
                break;
        }
        
        return true;
        
    }

//
//    /*
//     * Запись в сессию данных первого шага регистрации
//     */
//    private function setSessionStepOne($data) {
//        
//        if ($data == null) {
//            return false;
//        }
//        $session = Yii::$app->session;
//        $session['account'] = $data['account_number'];
//        $session['last_summ'] = $data['last_summ'];
//        $session['square'] = $data['square'];
//        $session['count_step'] = 1;
//        
//        return true;
//    }
//    
//    
//    /*
//     * Запись в сессию данных первого шага регистрации
//     */
//    private function setSessionStepTwo($data) {
//
//        if ($data == null) {
//            return false;
//        }
//        
//        $session = Yii::$app->session;
//        $session['email'] = $data['email'];
//        $session['password'] = $data['password'];
//        $session['count_step'] = 2;
//        
//        return true;
//    }
//    
//    /*
//     * Запись в сессию данных первого шага регистрации
//     */
//    private function setSessionStepThree($data) {
//
//        if ($data == null) {
//            return false;
//        }
//        
//        $session = Yii::$app->session;
//
//        $session['phone'] = $data['phone'];
//        $session['count_step'] = 3;
//        
//        return true;
//    }
//    
    /*
     * Отправка СМС кода на указанный номер телефона
     */
    public function actionSendSmsToRegister() {
        
        $phone = Yii::$app->request->post('phoneNumber');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            // Генерируем СМС код, и записываем его в сессию
            $sms_code = mt_rand(10000, 99999);
            Yii::$app->session->set('sms_code', $sms_code);
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['nubmer' => $sms_code];
        }
    }
    
    private function setCurrentRegisterData($step) {
        
        switch ($step) {
            case self::STEP_ONE:
                $this->current_array = [
                    'account' => Yii::$app->session->get('account'),
                    'last_summ' => Yii::$app->session->get('last_summ'),
                    'square' => Yii::$app->session->get('square'),                    
                ];
                break;
            case self::STEP_TWO:
                $this->current_array[] = [
                    'email' => Yii::$app->session->get('email'),
                    'password' => Yii::$app->session->get('password'),                    
                ];
                break;
            case self::STEP_THREE:
                $this->current_array[] = [
                    'phone' => Yii::$app->session->get('phone'),
                    'user_info' => Yii::$app->session->get('UserInfo'),
                ];
        }
        return true;
    }

}
