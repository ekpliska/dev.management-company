<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\CategoryServices;
    use app\modules\managers\models\form\RequestForm;

/**
 * Заявки
 */
class RequestsController extends AppManagersController {
    
    /*
     * Заявки, главная страница
     */
    public function actionIndex() {
        $model = new RequestForm();
        $service_categories = CategoryServices::getCategoryNameArray();
        $service_name = [];
        
        return $this->render('index', [
            'model' => $model,
            'service_categories' => $service_categories,
            'service_name' => $service_name,
        ]);
    }
    
    /*
     * Просмотр и редактирование заявок
     */
    public function actionView($request_number) {
        return $this->render('view');
    }
    
    /*
     * Метод сохранения созданной заявки
     */
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view', 'request_number' => $number]);
        }
    }
    
    /*
     * Валидация формы в модальном окне "Создать заявку"
     */
    public function actionValidationForm() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
}
