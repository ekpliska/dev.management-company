<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
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
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Обложка
            $file = UploadedFile::getInstance($model, 'image');
            $model->image = $file;
            
            $voting_id = $model->save($file);            
            if ($voting_id) {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => true,
                    'message' => 'Новость была успешно добавлена',
                ]);                
                return $this->redirect(['view', 'number' => $voting_id]);
            } else {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
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
                $name = FormatHelpers::formatFullAdress($house['estate_town'], $house['houses_street'], $house['houses_number_house']);
                echo '<option value="' . $house['houses_id'] . '">' . $name . '</option>';
            }            
        } elseif ($status == 1) {
            foreach ($houses as $house) {
                $name = FormatHelpers::formatFullAdress($house['estate_town'], $house['houses_street'], $house['houses_number_house'], $house['flats_porch']);
                return '<option value="' . $house['houses_id'] . '">' . $name . '</option>';
            }            
        }
        
    }
    
    
}
