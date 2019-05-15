<?php

    namespace app\controllers;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use app\models\User;
    use app\models\unsubscribe\UnsubscribeModel;

/**
 * Отписка от рассылки
 */
class UnsubscribeController extends Controller {
    
    public function behaviors() {
        return [            
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }
    
    public function actionIndex($qs = null) {
        
        $this->layout = '@app/views/layouts/unsubscribe-layout';
        
        if ($qs == null) {
            return $this->render('index', ['success' => false]);
        }
        
        $user_email = utf8_encode(base64_decode($qs));
        
        // Проверяем валидность переданного электронного адреса
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('index', ['success' => false]);
        }
        
        // Находим пользователя по переданному электронному адресу
        $user = User::findOne(['user_email' => $user_email]);
        $unsubscribe = new UnsubscribeModel($user);
        
        if (!$unsubscribe->unsubscribe()) {
            return $this->render('index', ['success' => false]);
        }
        
        return $this->render('index', ['success' => true]);
        
    }
    
}
