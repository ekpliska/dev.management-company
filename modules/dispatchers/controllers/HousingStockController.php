<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Houses;
    use app\models\CharacteristicsHouse;
    use app\models\Flats;
    use app\models\Image;

/**
 * Жилищный фонд
 */
class HousingStockController extends AppDispatchersController {
    
    public function actionIndex() {
        
        // Из куки получаем выбранный дом
        $house_cookie = $this->actionReadCookies('choosing-house-d');
        
        $houses_list = Houses::getAllHouses();
        
        return $this->render('index', [
            'houses_list' => $houses_list,
        ]);
        
    }
    
    /*
     * Загрузка характеристики дома
     * Квартиры
     * Прикрепленные документы
     */
    public function actionViewCharacteristicHouse() {
        
        $house_id = Yii::$app->request->post('house');
        $this->setCookieChooseHouse($house_id);
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $characteristics = CharacteristicsHouse::getCharacteristicsByHouse($house_id);
            $flats = Flats::getFlatsByHouse($house_id);
            $files = Image::getAllFilesByHouse($house_id, 'Houses');
            
            $data_characteristics = $this->renderPartial('data/characteristics_house', ['characteristics' => $characteristics]);
            $data_flats = $this->renderPartial('data/view_flats', ['flats' => $flats]);
//            $data_files = $this->renderPartial('data/view_upload_files', ['files' => $files]);
            
//            return ['data' => $data_characteristics, 'flats' => $data_flats, 'files' => $data_files];
            return ['data' => $data_characteristics, 'flats' => $data_flats];
        }
    }
    
    /*
     * Установка куки выбранного дома
     */
    public function setCookieChooseHouse($value) {
        
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie ([
            'name' => 'choosing-house-d',
            'value' => $value,
            'expire' => time() + 60*60*24*7,
        ]));
    }
    
}
