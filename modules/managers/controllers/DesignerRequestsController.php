<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\CategoryServices;
    use app\models\Services;
    use app\modules\managers\models\form\ServiceForm;
    use app\models\Units;

/**
 * Конструктор заявок
 */
class DesignerRequestsController extends AppManagersController {
    
    public function actionIndex($section = 'requests') {
        
        $results = [];
        
        $model_category = new CategoryServices();
        $model_service = new ServiceForm();
        
        switch ($section) {
            case 'requests':
                $results = [];
                break;
            case 'paid-services':
                $results = [
                    'categories' => CategoryServices::getCategoryNameArray(),
                    'services' => Services::getServicesNameArray(),
                    'units' => Units::getUnitsArray(),
                ];
                break;
        }
        
        return $this->render('index', [
            'section' => $section,
            'model_category' => $model_category,
            'model_service' => $model_service,
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
            case 'new-service':
                $model = new ServiceForm();
                break;
            case 'edit-service-form':
                $model = new Services();
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
            case 'new-service':
                $model = new ServiceForm();
                $section = 'paid-services';
                break;
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($form == 'new-service') {
                $file = UploadedFile::getInstance($model, 'service_image');
                $model->service_image = $file;
                $record = $model->save($file);
                return $this->redirect(['index', 'section' => $section]);
            }
            $record = $model->save();
            return $this->redirect(['index', 'section' => $section]);
        }
        
    }
    
    public function actionShowServices ($category_id) {
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $services_list = Services::getPayServices($category_id);
            $data = $this->renderAjax('data/services-list', ['services_list' => $services_list]);
            return ['success' => true, 'data' => $data];
        }
        
        return ['success' => false];
    }
    
    public function actionDeleteRecord ($type, $record_id) {
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            switch ($type) {
                case 'category':
                    $result = CategoryServices::findOne($record_id);                    
                    break;
                default:
                    Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления. Обновите страницу и повторите действие еще раз']);
                    return $this->redirect(Yii::$app->request->referrer);
            }
            
            if (!$result->delete()) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления. Обновите страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            Yii::$app->session->setFlash('success', ['message' => 'Выбранная запись была успешно удалена']);
            return $this->redirect(Yii::$app->request->referrer);
            
        }
    }
    
    
    /*
     * Загрузка модального окна на редактирование услуги
     * Сохранение данных
     */
    public function actionEditService($service_id) {
        
        $model = Services::findOne($service_id);
        $categories_list = CategoryServices::getCategoryNameArray();
        $units = Units::getUnitsArray();
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('modal/edit-service', [
                'model' => $model,
                'categories_list' => $categories_list,
                'units' => $units,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'section' => 'paid-services']);
        }
        
    }
}
