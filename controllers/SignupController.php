<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use app\models\RegistrationForm;
    use app\models\User;
    use app\models\signup\SignupStepOne;
    use app\models\PersonalAccount;
    use app\models\signup\SignupStepTwo;

/**
 * Регистрация
 */
class SignupController extends Controller {
    
    public function actionIndex() {
        
        $session = Yii::$app->session;
        
        $model = new RegistrationForm();
        $model_step_one = new SignupStepOne();
        $model_step_two = new SignupStepTwo();
        
        $is_step_one = $this->postDataStepOne($model_step_one);
        $is_step_two = $this->postDataStepTwo($model_step_two);
        
        return $this->render('index', [
            'model' => $model,
            'model_step_one' => $model_step_one,
            'model_step_two' => $model_step_two,
            'is_step_one' => $is_step_one,
            'is_step_two' => $is_step_two,
        ]);
        
    }
    
    /*
     * Обработка данных рагистрации шаг один
     */
    private function postDataStepOne($data) {
        
        
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {

            $post_data = Yii::$app->request->post()['SignupStepOne'];
            if ($this->setSessionStepOne($post_data)) {
                return Yii::$app->session->get('step_one');
            }
        }
        
    }
    
    
    /*
     * Обработка данных рагистрации шаг один
     */
    private function postDataStepTwo($data) {
        
        
        if ($data->load(Yii::$app->request->post()) && $data->validate()) {
            return 'here';

            $post_data = Yii::$app->request->post()['SignupStepTwo'];
            if ($this->setSessionStepTwo($post_data)) {
                return Yii::$app->session->get('step_two');
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
        $session['step_one'] = true;
        
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
        
 var_dump($_SESSION); die();        
        
        $session['email'] = $data['account_number'];
        $session['password'] = $data['last_summ'];
        $session['step_one'] = true;
        $session['step_two'] = true;
        
        return true;
    }
}
