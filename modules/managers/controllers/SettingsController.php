<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\base\Model;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Organizations;
    use app\models\Departments;
    use app\models\Posts;
    use app\models\Partners;
    use app\models\SliderSettings;

/**
 * Тарифы
 */
class SettingsController extends AppManagersController {
    
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
        
        if (Model::loadMultiple($partners, Yii::$app->request->post()) && Model::validateMultiple($partners)) {
            foreach ($partners as $partner) {
                $partner->save(false);
            }
            return $this->redirect('partners-list');
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
        
        if (Model::loadMultiple($sliders, Yii::$app->request->post()) && Model::validateMultiple($sliders)) {
            foreach ($sliders as $slider) {
                $slider->save(false);
            }
            return $this->redirect('slider-settings');
        }
        
        return $this->render('slider-settings', [
            'sliders' => $sliders,
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
            case 'partner';
                $model = new Partners();
                $link = 'partners-list';
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
