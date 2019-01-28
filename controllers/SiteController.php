<?php

    namespace app\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\base\InvalidPattpException;
    use app\models\LoginForm;
    use app\models\UsailConfirmForm;
    use app\models\PasswordResetRequestForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
     * Главная страница. вход в систему
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * Форма входа в систему
     */
    public function actionLogin() {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->can('clients') || Yii::$app->user->can('clients_rent')) {
                return $this->redirect(['clients/clients']);
            } elseif (Yii::$app->user->can('dispatcher')) {
                return $this->redirect(['dispatchers/dispatchers']);
            } elseif (Yii::$app->user->can('administrator')) {
                return $this->redirect(['managers/managers']);
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

//    /*
//     * Подтверждение регистрации
//     */
//    public function actionEmailConfirm($token) {
//        try {
//            $model = new EmailConfirmForm($token);
//        } catch (InvalidParamException $e) {
//            throw new BadRequestHttpException($e->getMessage());
//        }
//        
//        if ($model->confirmEmail()) {
//            Yii::$app->getSession()->setFlash('registration-done', 'Ваш Email успешно подтверждён');
//        } else {
//            Yii::$app->getSession()->setFlash('registration-error', 'Ошибка подтверждения Email');
//        }
// 
//        return $this->goHome();
//    }
    
    /*
     * Запрос на восстановление пароля
     */
    public function actionRequestPasswordReset() {
        
        $model = new PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('success', 'На указанный email были высланы инструкции для восстановления пароля');
            } else {
                Yii::$app->session->setFlash('error', 'При восстановлении пароля произошла ошибка');
            }
        }
        return $this->render('request-password-reset', ['model' => $model]);
        
    }
    
}
