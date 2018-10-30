<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use app\models\RegistrationForm;
    use app\models\User;
    use app\models\signup\SignupStepOne;

/**
 * Регистрация
 */
class SignupController extends Controller {
    
    public function actionIndex() {
        
        $model = new RegistrationForm();
        $model_step_one = new SignupStepOne();
        
        if ($model_step_one->load(Yii::$app->request->post()) && $model_step_one->validate()) {
            return 'here';
            
        }
        
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                
                Yii::$app->session->setFlash('registration-done', 'Для подтверждения регистрации пройдите по ссылке, указанной в письме');
                
                $data_model = new User();                
                $data_model = $model->registration();
                return $this->goHome();
                
            } else {
                Yii::$app->session->setFlash('registration-error', 'При регистрации возникла ошибка');
            }
        }
        
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
            $is_account = \app\models\PersonalAccount::findAccountBeforeRegister($account, $summ, $square);
            
            if (!$is_account) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        return ['success' => false];
    }
}
