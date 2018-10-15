<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Houses;
    use app\models\CharacteristicsHouse;
    use app\models\Flats;

/**
 * Жилищный фонд
 */
class EstatesController extends AppManagersController {
    
    public function actionIndex() {
        
        // Из куки получаем выбранных дом
        $house_cookie = $this->actionReadCookies();
        
        $houses_list = Houses::getAllHouses();
        
        $characteristics = $house_cookie ? CharacteristicsHouse::getCharacteristicsByHouse($house_cookie) : null;
        $flats = $house_cookie ? Flats::getFlatsByHouse($house_cookie) : null;
        
        return $this->render('index', [
            'houses_list' => $houses_list,
            'characteristics' => $characteristics,
            'flats' => $flats,
            'house_cookie' => $house_cookie,
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
    
    /*
     * Загрузка характеристики дома
     * Квартиры
     */
    public function actionViewCharacteristicHouse() {
        
        $house_id = Yii::$app->request->post('house');
        $this->setCookieChooseHouse($house_id);
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $characteristics = CharacteristicsHouse::getCharacteristicsByHouse($house_id);
            $flats = Flats::getFlatsByHouse($house_id);
            
            $data_characteristics = $this->renderPartial('data/characteristics_house', ['characteristics' => $characteristics]);
            $data_flats = $this->renderPartial('data/view_flats', ['flats' => $flats]);
            
            return ['data' => $data_characteristics, 'flats' => $data_flats];
        }
    }
    
    /*
     * Удаление выбранной характеристики
     */
    public function actionDeleteCharacteristic(){
        $characteristic_id = Yii::$app->request->post('charId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $characteristic = CharacteristicsHouse::findOne($characteristic_id);
            if (!$characteristic->delete()) {
                return ['success' => false];
            } else {
                return ['success' => true];
            }
        }
        return ['success' => false];
    }
    
    /*
     * Установка куки выбранного дома
     */
    public function setCookieChooseHouse($value) {
        
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie ([
            'name' => 'choosingHouse',
            'value' => $value,
            'expire' => time() + 60*60*24*7,
        ]));
        
    }
    
}
