<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\Pagination;
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
        
        // Получаем все доступные голосования
        $query = Voting::findAllVoting();
        $count_voting = clone $query;
        $pages = new Pagination([
            'totalCount' => $count_voting->count(),
            'pageSize' => 15,
            'defaultPageSize' => 15,
        ]);
        
        $view_all_voting = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        return $this->render('index', [
            'view_all_voting' => $view_all_voting,
            'pages' => $pages,
        ]);
        
    }
    
    /*
     * Создание голосования
     * 
     * @param array $type_voting Тип голосования (Весь дом, конкретный подъезд)
     */
    public function actionCreate() {
        
        $model = new VotingForm();
        $model->voting = new Voting();
        $model->setAttributes(Yii::$app->request->post());
        
        $type_voting = Voting::getTypeVoting();
        
        if (Yii::$app->request->post() && $model->validate()) {
            // Приводим дату завершения, дату окончания к формату бд
            $model->voting->voting_date_start = Yii::$app->formatter->asDatetime($model->voting->voting_date_start, 'php:Y-m-d H:i:s');
            $model->voting->voting_date_end = Yii::$app->formatter->asDatetime($model->voting->voting_date_end, 'php:Y-m-d H:i:s');
            // Получаем загружаемую оложку для голосования
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Вызываем метод на загрузку обложки, при успехе - получаем полный путь к загруженной обложке
            $path = $model->upload();
            if ($model->imageFile && $path) {
                $model->voting->voting_image = $path;
            }
            // Сбрасываем путь загруженного изображения
            $model->imageFile = null; 
            // Сохраняем модель
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Product has been created.');
                return $this->redirect(['view', 'voting' => $model->voting->voting_id]);
            }
        }
                
        return $this->render('create', [
            'model' => $model,
            'type_voting' => $type_voting]);
    }
    
    /*
     * Просмотр страницы голосования
     */
    public function actionView($voting) {
        
        $model = new VotingForm();
        $model->voting = Voting::findOne($voting);
        
        if ($model->voting == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model->setAttributes(Yii::$app->request->post());
        
        $type_voting = Voting::getTypeVoting();        
        
        if (Yii::$app->request->post()) {
            
            // Получаем загружаемую оложку для голосования
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Вызываем метод на загрузку обложки, при успехе - получаем полный путь к загруженной обложке
            $path = $model->upload();
            
            if ($model->imageFile && $path) {
                $model->voting->voting_image = $path;
            }
            $model->save();
            
            Yii::$app->getSession()->setFlash('success', 'Product has been updated.');
            return $this->redirect(['view', 'voting' => $model->voting->voting_id]);
        }
        return $this->render('view', [
            'model' => $model,
            'type_voting' => $type_voting]);
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
