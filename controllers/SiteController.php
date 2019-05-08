<?php

    namespace app\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use app\models\LoginForm;
    use app\models\PasswordResetRequestForm;
    use app\models\SiteSettings;
    use app\models\SmsSettings;

class SiteController extends Controller
{

    public function behaviors() {
        return [            
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'login', 'logout'],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->can('clients') || Yii::$app->user->can('clients_rent')) {
                        return $this->redirect(['/clients']);
                    } elseif (Yii::$app->user->can('dispatcher')) {
                        return $this->redirect(['/dispatchers']);
                    } elseif (Yii::$app->user->can('administrator')) {
                        return $this->redirect(['/managers']);
                    }
                },
                'rules' => [
                    [
                        'actions' => ['index', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionError() {
        
        $this->layout = '@app/views/layouts/error-layout';
        
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['message' => 'Страница не найдена']);
        }
    }
    
    /**
     * Главная страница, вход в систему
     */
    public function actionIndex() {
        
        $welcome_text = SiteSettings::find()
                ->where(['id' => 1])
                ->asArray()
                ->one();
        
        return $this->render('index', [
            'welcome_text' => !empty($welcome_text) ? $welcome_text['welcome_text'] : 'welcome_text',
        ]);
    }
    
    /**
     * Форма входа в систему
     */
    public function actionLogin() {
        
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->can('clients') || Yii::$app->user->can('clients_rent')) {
                return $this->redirect(['/clients']);
            } elseif (Yii::$app->user->can('dispatcher')) {
                return $this->redirect(['/dispatchers']);
            } elseif (Yii::$app->user->can('administrator')) {
                return $this->redirect(['/managers']);
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /*
     * Выход из системы
     */
    public function actionLogout() {
        
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /*
     * Запрос на восстановление пароля
     */
    public function actionRequestPasswordReset() {
        
        $model = new PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->resetPassword()) {
                Yii::$app->session->setFlash('error', 'К сожаления во время восстановления произошла ошибка. Повторите попытку позже');
                return $this->redirect(['request-password-reset']);
            } else {
                Yii::$app->session->setFlash('success', 'Новый пароль был выслан в СМС на указанный номер');
                return $this->redirect(['request-password-reset']);
            }
        }
        return $this->render('request-password-reset', ['model' => $model]);
        
    }
    
    /*
     * Отправка СМС кода на указанный номер телефона
     */
    public function actionResetPassword() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $phone = Yii::$app->request->post('phoneNumber');
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Текущее время
        $current_time = time();
        // Время хранения смс-кода
        $expired_at = Yii::$app->session->has('reset_expired_at') ? Yii::$app->session['reset_expired_at'] : 0;
        // Если прошло время, смс код не валидный
        if ($current_time > $expired_at) {
            // Данные сессии очищаем
            Yii::$app->session->destroy();
        }
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            // Генерируем СМС код
            $sms_code = mt_rand(10000, 99999);
            // Отправляем смс на указанный номер телефона
            if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_RECOVERY_PASSWORD, $phone, $sms_code)) {
                return ['success' => false, 'message' => $result];
            }
            // Записываем в сессию СМС-код и время его действия (10 минут)
            Yii::$app->session->set('reset_sms_code', $sms_code);
            Yii::$app->session->set('reset_expired_at', time() + 10*60);
            return ['time' => $sms_code];
        }
        
    }
    
    public function actionResult($status = 'success') {
        
        $this->layout = '@app/views/layouts/result-layout';
        return $this->render('result', [
            'status_name' => $status,
        ]);
    }
   
    public function actionTestAccount() {

        $this->layout = false;
        return $this->render('test-account');

    }
    
}
