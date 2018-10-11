<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Houses;

/**
 * Жилищный фонд
 */
class EstatesController extends AppManagersController {
    
    public function actionIndex() {
        
        $houses_list = Houses::getAllHouses();
        
        return $this->render('index', [
            'houses_list' => $houses_list,
        ]);
        
    }
    
    /*
     * Загрузка модального окна на редактирование описания дома
     * Сохранение данных
     */
    public function actionUpdateDescription($house_id) {
        
        $model = Houses::findOne($house_id);
        $model->scenario = Houses::SCENARIO_EDIT_DESCRIPRION;
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form/edit_description', [
                'model' => $model]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
    }
    
    /*
     * Валидация формы редактирование описания дома
     */
    public function actionEditDescriptionValidate() {
        
        $model = new Houses();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
    }
    
}
