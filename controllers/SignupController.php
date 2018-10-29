<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use app\models\RegistrationForm;
    use app\models\User;

/**
 * Регистрация
 */
class SignupController extends Controller {
    
    public function actionIndex() {
        
        $model = new RegistrationForm();
                
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
        
        return $this->render('index', ['model' => $model]);
        
    }
    
    
    public function actionValidateStepOne() {
        $account = Yii::$app->request->post('account');
        $summ = Yii::$app->request->post('summ');
        $square = Yii::$app->request->post('square');
        
        if (Yii::$app->request->isAjax) {
            $account = \app\models\PersonalAccount::findAccountBeforeRegister($account, $summ, $square);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            if (!$account) {
//                return ['success' => false];
//            }
            return ['success' => $account];
        }
        return ['success' => false];
    }
}
