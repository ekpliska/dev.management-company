<?php

    namespace app\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Response;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use app\models\RegistrationForm;
    use app\models\LoginForm;
    use app\models\User;

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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Главная страница. вход в систему
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
     * Форма регистрации
     */
    public function actionRegistration() {
        
        $model = new RegistrationForm();
                
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                
                Yii::$app->session->setFlash('registration-done', 'Регистрация ОК, проверить email');
                
                $data_model = new User();                
                
                if ($data_model = $model->registration()) {
                    return $this->goHome();
                }                
            } else {
                Yii::$app->session->setFlash('registration-error', 'Ошибка при регистрации');
            }
        }
        
        return $this->render('registration', ['model' => $model]);
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
            return $this->goBack();
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

//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        }
//        return $this->render('contact', [
//            'model' => $model,
//        ]);
//    }

//    public function actionAbout()
//    {
//        return $this->render('about');
//    }
    
}
