<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use app\models\RegistrationForm;
    use app\models\User;
    use app\models\signup\SignupStepOne;
    use app\models\PersonalAccount;

/**
 * Регистрация
 */
class SignupController extends Controller {
    
    public function actionIndex() {
        
        $session = Yii::$app->session;
        
        $model = new RegistrationForm();
        $model_step_one = new SignupStepOne();
        $model_step_two = new \app\models\signup\SignupStepTwo();
        
        if ($model_step_one->load(Yii::$app->request->post()) && $model_step_one->validate()) {

            $data = Yii::$app->request->post()['SignupStepOne'];
            $this->setSessionStepOne($data);
            
            if ($session->get('step_one')) {
                return $this->renderAjax('form/step_two', ['model_step_two' => $model_step_two]);
            }
            
        }
        
//        if ($model->load(Yii::$app->request->post())) {
//            if ($model->validate()) {
//                
//                Yii::$app->session->setFlash('registration-done', 'Для подтверждения регистрации пройдите по ссылке, указанной в письме');
//                
//                $data_model = new User();                
//                $data_model = $model->registration();
//                return $this->goHome();
//                
//            } else {
//                Yii::$app->session->setFlash('registration-error', 'При регистрации возникла ошибка');
//            }
//        }
        
        return $this->render('index', [
            'model' => $model,
            'model_step_one' => $model_step_one]);
        
    }
    
    
    public function actionValidateStepOne() {
        
        $account = Yii::$app->request->post('account');
        $summ = Yii::$app->request->post('summ');
        $square = Yii::$app->request->post('square');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $is_account = PersonalAccount::findAccountBeforeRegister($account, $summ, $square);
            
            if (!$is_account) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        return ['success' => false];
    }
    
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
}
