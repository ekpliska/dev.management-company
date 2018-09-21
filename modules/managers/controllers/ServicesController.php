<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
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
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'service_image');
            $model->service_image = $file;
            $service_id = $model->save($file);
            if ($service_id) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => true,
                    'message' => 'Новая услуга была успешно добавлена',
                ]);
                return $this->redirect(['index', 'service_id' => $service_id]);
            } else {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'service_categories' => $service_categories,
            'service_types' => $service_types,
            'units' => $units,
        ]);
    }
    
}
