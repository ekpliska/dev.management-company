<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Services;
    use app\models\Rates;
    use app\modules\managers\models\form\ServiceForm;
    use app\models\Units;
    use app\models\CategoryServices;
    use app\modules\managers\models\ServicesRates;

/**
 * Услуги
 */
class ServicesController extends AppManagersController {
    
    /*
     * Услуги, главная страница
     */
    public function actionIndex() {
        
        $services = new ActiveDataProvider([
            'query' => ServicesRates::getAllServices(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('index', ['services' => $services]);
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
                return $this->redirect(['edit-service', 'service_id' => $service_id]);
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
    /*
     * Редактирование услуги
     */
    public function actionEditService($service_id) {
        
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
        
        return $this->render('edit-service', [
            'service' => $service,
            'rate' => $rate,
            'service_categories' => $service_categories,
            'service_types' => $service_types,
            'units' => $units]);
        
    }
    
    /*
     * Переключение типа услуги Услуга/Платная
     * для таблица все услуги
     */
    public function actionCheckTypeService() {
        
        $service_id = Yii::$app->request->post('serviceId');
        $type = Yii::$app->request->post('typeService');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {            
            $service = Services::findByID($service_id);
            $service->checkType($type);
            return ['status' => true, 'type' => $type];
        }
        return ['status' => false];
    }
    
    /*
     * Запрос удаление услуги
     */
    public function actionConfirmDeleteService() {
        
        $service_id = Yii::$app->request->post('serviceId');
        
        if (Yii::$app->request->isAjax) {
            $service = Services::findByID($service_id);
            if (!$service->delete()) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('services-admin', [
                    'success' => true,
                    'message' => 'Услуга ' . $service->services_name . ' была успешно удалена',
            ]);
            return $this->redirect(['index']);
        }
        
        return $this->goHome();
        
    }
}
