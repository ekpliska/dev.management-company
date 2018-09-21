<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Services;
    use app\models\Rates;
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
                return $this->redirect(['update-service', 'service_id' => $service_id]);
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
    
    public function actionUpdateService($service_id) {
        
        $service = Services::findByID($service_id);
        $rate = Rates::findByServiceID($service_id);
        
        if ($service === null || $rate === null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несущствующей странице');
        }
        
        $service_categories = CategoryServices::getCategoryNameArray();
        $service_types = Services::getTypeNameArray();
        $units = Units::getUnitsArray();
        
        if ($service->load(Yii::$app->request->post()) && $rate->load(Yii::$app->request->post())) {
            $is_valid = $service->validate();
            $is_valid = $rate->validate() && $is_valid;
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($service, 'services_image');
                $service->uploadImage($file);
                $rate->save();
                Yii::$app->session->setFlash('services-admin', [
                    'success' => true,
                    'message' => 'Новая услуга была успешно добавлена',
                ]);
            } else {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);                
            }
        }
        
        return $this->render('update-service', [
            'service' => $service,
            'rate' => $rate,
            'service_categories' => $service_categories,
            'service_types' => $service_types,
            'units' => $units]);
        
    }
    
}
