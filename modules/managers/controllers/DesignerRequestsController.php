<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\CategoryServices;
    use app\models\Services;

/**
 * Конструктор заявок
 */
class DesignerRequestsController extends AppManagersController {
    
    public function actionIndex($section = 'requests') {
        
        $results = [];
        
        $model_category = new CategoryServices();
        
        switch ($section) {
            case 'requests':
                $results = [];
                break;
            case 'paid-services':
                $results = [
                    'categories' => CategoryServices::getCategoryNameArray(),
                    'services' => Services::getServicesNameArray(),
                ];
                break;
        }
        
        return $this->render('index', [
            'section' => $section,
            'model_category' => $model_category,
            'results' => $results,
        ]);
        
    }
    
    /*
     * Валидация форм
     */
    public function actionValidationForm($form) {
        
        switch ($form) {
            case 'new-category':
                $model = new CategoryServices();
                break;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
    }
    
    /*
     * Обработка форм
     */
    public function actionCreateRecord($form) {
        
        switch ($form) {
            case 'new-category':
                $model = new CategoryServices();
                $section = 'paid-services';
                break;
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['index', 'section' => $section]);
        }
        
    }
}
