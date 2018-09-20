<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Services;
    use app\modules\managers\models\form\ServiceForm;
    use app\models\Units;
    use app\models\CategoryServices;

/**
 * Услуги
 */
class ServicesController extends AppManagersController {
    
    /*
     * Услуги, главная страница
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    /*
     * Новая услуга
     */
    public function actionCreate() {
        
        $model = new ServiceForm();
        $service_categories = CategoryServices::getCategoryNameArray();
        $service_types = Services::getTypeNameArray();
        $units = Units::getUnitsArray();
        
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['index']);
        }
        
        return $this->render('create', [
            'model' => $model,
            'service_categories' => $service_categories,
            'service_types' => $service_types,
            'units' => $units,
        ]);
    }
    
}
