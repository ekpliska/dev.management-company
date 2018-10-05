<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Voting;
    use app\models\Houses;
    use app\helpers\FormatHelpers;
    use app\modules\managers\models\form\VotingForm;

/**
 *  Голосование
 */
class VotingController extends AppManagersController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        return $this->render('index');
        
    }
    
    /*
     * Создание голосования
     * 
     * @param array $type_voting Тип голосования (Весь дом, конкретный подъезд)
     */
    public function actionCreate() {
        
        $model = new VotingForm();
        
        $type_voting = Voting::getTypeVoting();
        $object_vote = [];
        
        return $this->render('create', [
            'model' => $model,
            'type_voting' => $type_voting,
            'object_vote' => $object_vote,
        ]);
        
    }
    
    /*
     * Зависимый переключатель типа голосования
     *      Для конкретного дома
     *      Для конкретного подъезда
     */
    public function actionForWhomVoting($status) {
        
        $houses = Houses::getHousesList();
        
        if ($status == 0) {
            foreach ($houses as $house) {
                $name = FormatHelpers::formatFullAdress($house['houses_town'], $house['houses_street'], $house['houses_number_house']);
                echo '<option value="' . $house['houses_id'] . '">' . $name . '</option>';
            }            
        } elseif ($status == 1) {
            foreach ($houses as $house) {
                $name = FormatHelpers::formatFullAdress($house['houses_town'], $house['houses_street'], $house['houses_number_house'], $house['houses_porch']);
                return '<option value="' . $house['houses_id'] . '">' . $name . '</option>';
            }            
        }
        
    }
    
    
}
