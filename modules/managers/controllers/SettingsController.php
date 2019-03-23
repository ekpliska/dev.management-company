<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Organizations;
    use app\models\Departments;
    use app\models\Posts;
    use app\models\Partners;
    use app\models\SliderSettings;
    use app\models\SiteSettings;
    use app\models\FaqSettings;
    use app\models\SmsSettings;

/**
 * Тарифы
 */
class SettingsController extends AppManagersController {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'service-duty',
                            'partners-list',
                            'slider-settings',
                            'site-settings',
                            'faq-settings',
                            'sms-settings',
                            'delete-record',
                            'validate-form',
                            'create-record',
                            'switch-status-slider',
                        ],
                        'allow' => true,
                        'roles' => ['SettingsView', 'SettingsEdit']
                    ],
                ],
            ],
        ];
    }
    
    /*
     * Настройка, главная страница
     * Реквизиты компании
     */
    public function actionIndex() {
        
        $model = Organizations::findOne(['organizations_id' => 1]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['index']);
        }
        
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    
    /*
     * Подразделения, Должности
     */
    public function actionServiceDuty() {
        
        $departments = Departments::find()->indexBy('department_id')->all();
        $posts = Posts::find()->indexBy('post_id')->all();
        $department_lists = Departments::getArrayDepartments();
        
        $department_model = new Departments();
        $post_model = new Posts();
        
        if (Model::loadMultiple($departments, Yii::$app->request->post()) && Model::validateMultiple($departments)) {
            foreach ($departments as $department) {
                $department->save(false);
            }
            return $this->redirect('service-duty');
        }

        if (Model::loadMultiple($posts, Yii::$app->request->post()) && Model::validateMultiple($posts)) {
            foreach ($posts as $post) {
                $post->save();
            }
            return $this->redirect('service-duty');
        }

        return $this->render('service-duty', [
            'departments' => $departments,
            'posts' => $posts,
            'department_lists' => $department_lists,
            'department_model' => $department_model,
            'post_model' => $post_model,
        ]);
        
    }
    
    /*
     * Партнеры
     */
    public function actionPartnersList() {
        
        $partners = Partners::find()->all();
        
        $model = new Partners();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $logo = UploadedFile::getInstance($model, 'image_logo');
            $model->image_logo = $logo;
            if ($model->upload()) {
                return $this->redirect('partners-list');
            }
        }
        
        return $this->render('partners-list', [
           'partners' => $partners,
            'model' => $model,
        ]);
        
    }
    
    /*
     * Настройки слайдера
     */
    public function actionSliderSettings() {
        
        $sliders = SliderSettings::find()->all();
        
        $model = new SliderSettings();
        
        if (Model::loadMultiple($sliders, Yii::$app->request->post()) && Model::validateMultiple($sliders)) {
            foreach ($sliders as $slider) {
                $slider->save(false);
            }
            return $this->redirect('slider-settings');
        }
        
        return $this->render('slider-settings', [
            'sliders' => $sliders,
            'model' => $model,
        ]);
        
    }
    
    /*
     * Настройки API
     */
    public function actionSiteSettings() {
        
        $model = SiteSettings::findOne(['id' => 1]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect('site-settings');
        }
        
        return $this->render('site-settings', ['model' => $model]);
        
    }
    
    /*
     * FAQ
     */
    public function actionFaqSettings() {
        
        $faq_settings = FaqSettings::find()->all();
        
        $model = new FaqSettings();
        
        if (Model::loadMultiple($faq_settings, Yii::$app->request->post()) && Model::validateMultiple($faq_settings)) {
            foreach ($faq_settings as $faq_setting) {
                $faq_setting->save(false);
            }
            return $this->redirect('faq-settings');
        }
        
        return $this->render('faq-settings', [
            'faq_settings' => $faq_settings,
            'model' => $model,
        ]);
        
    }
    
    /*
     * СМС оповещения
     */
    public function actionSmsSettings() {
        
        $sms_notices = SmsSettings::find()->all();
        
        if (Model::loadMultiple($sms_notices, Yii::$app->request->post()) && Model::validateMultiple($sms_notices)) {
            foreach ($sms_notices as $notice) {
                $notice->save(false);
            }
            return $this->redirect('sms-settings');
        }
        
        return $this->render('sms-settings', [
            'sms_notices' => $sms_notices,
        ]);
        
    }
    
    /*
     * Удаление выбранного подразделения, должности
     */
    public function actionDeleteRecord($item, $type) {

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            switch ($type) {
                case 'department':
                    $result = Departments::findOne($item);
                    $link = 'service-duty';
                    break;
                case 'post':
                    $result = Posts::findOne($item);
                    $link = 'service-duty';
                    break;
                case 'partner':
                    $result = Partners::findOne($item);
                    $link = 'partners-list';
                    break;
                case 'slider':
                    $result = SliderSettings::findOne($item);
                    $link = 'slider-settings';
                    break;
                case 'faq':
                    $result = FaqSettings::findOne($item);
                    $link = 'faq-settings';
                    break;
            }

            if (!empty($result)) {
                $result->delete();
            }
        }
        
        return $this->redirect($link);
        
    }
    
    /*
     * Валидация форм
     */
    public function actionValidateForm($form) {
        
        switch ($form) {
            case 'add-department':
                $model = new Departments();
                break;
            case 'add-post';
                $model = new Posts();
                break;
            case 'add-partner';
                $model = new Partners();
                break;
            case 'add-slider';
                $model = new SliderSettings();
                break;
            case 'add-faq';
                $model = new FaqSettings();
                break;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    /*
     * Сохранение новых
     * Поразделение, Должность, Партнер 
     */
    public function actionCreateRecord($model) {
       
        switch ($model) {
            case 'department':
                $model = new Departments();
                $link = 'service-duty';
                break;
            case 'post';
                $link = 'service-duty';
                $model = new Posts();
                break;
//            case 'partner';
//                $model = new Partners();
//                $link = 'partners-list';
//                break;
            case 'slider';
                $model = new SliderSettings();
                $link = 'slider-settings';
                break;
            case 'faq';
                $model = new FaqSettings();
                $link = 'faq-settings';
                break;
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect($link);
        }
        
    }
    
    /*
     * Переключение статуса слайдера
     */
    public function actionSwitchStatusSlider($item) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            $slider = SliderSettings::findOne($item);
            if (empty($slider)) {
                return ['success' => false];
            }
            
            $slider->switchStatus();
            
            return [
                'success' => true, 
                'item' => $item,
            ];
            
        }
        
        return ['success' => false];
        
    }
    
}
