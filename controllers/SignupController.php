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
    
    public function actionIndex() {
        
        $session = Yii::$app->session;
        
        $model = new RegistrationForm();
        $model_step_one = new SignupStepOne();
        $model_step_two = new SignupStepTwo();
        $model_step_three = new SignupStepThree();
        
        $is_step_one = $this->postDataStepOne($model_step_one);
        $is_step_two = $this->postDataStepTwo($model_step_two);
        $is_step_three = $this->postDataStepThree($model_step_three);
        
        return $this->render('index', [
            'model' => $model,
            'model_step_one' => $model_step_one,
            'model_step_two' => $model_step_two,
            'model_step_three' => $model_step_three,
        ]);
        
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
     * Обработка данных рагистрации шаг один
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
     * Обработка данных рагистрации шаг один
     */
    private function postDataStepThree($data) {
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {
            $post_data = Yii::$app->request->post()['SignupStepThree'];
            if ($this->setSessionStepThree($post_data)) {
                return Yii::$app->session->get('count_step');
            }
        }
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
        $session['count_step'] = 1;
        
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
        $session['count_step'] = 2;
        
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
        
        $session['phone'] = $data['email'];
        $session['sms_code'] = $data['password'];
        
        return true;
    }
    
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
}
