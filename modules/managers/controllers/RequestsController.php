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
        
        return $this->render('index', [
            'model' => $model,
            'service_categories' => $service_categories,
        ]);
    }
    
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['index']);
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
