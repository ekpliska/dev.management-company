<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\Pagination;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Voting;
    use app\models\Houses;
    use app\helpers\FormatHelpers;
    use app\models\RegistrationInVoting;
    use app\modules\managers\models\form\VotingForm;
    use app\modules\managers\models\Clients;
    use app\modules\managers\models\searchForm\searchVote;

/**
 *  Голосование
 */
class VotingController extends AppManagersController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        // Загружаем модель поиска
        $search_model = new searchVote();
        
        $query = $search_model->search(Yii::$app->request->queryParams);
        
        $count_voting = clone $query;
        $pages = new Pagination([
            'totalCount' => $count_voting->count(),
            'pageSize' => 15,
            'defaultPageSize' => 15,
        ]);
        
        $view_all_voting = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        
        $house_lists = Houses::getHousesList(false);
        
        return $this->render('index', [
            'search_model' => $search_model,
            'view_all_voting' => $view_all_voting,
            'pages' => $pages,
            'house_lists' => $house_lists,
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
        
        $houses_array = Houses::getAdressHousesList();
        
        if (Yii::$app->request->post() && $model->validate()) {
            // Приводим дату завершения, дату окончания к формату бд
//            $model->voting->voting_date_start = Yii::$app->formatter->asDatetime($model->voting->voting_date_start, 'php:Y-m-d H:i:s');
//            $model->voting->voting_date_end = Yii::$app->formatter->asDatetime($model->voting->voting_date_end, 'php:Y-m-d H:i:s');
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
                Yii::$app->session->setFlash('success', ['message' => 'Новое голосование было успешно создано']);
                return $this->redirect(['view', 'voting' => $model->voting->voting_id]);
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
                
        return $this->render('create', [
            'model' => $model,
            'type_voting' => $type_voting,
            'houses_array' => $houses_array]);
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
        
        $houses_array = Houses::getAdressHousesList();
        
        // Получаем информаию о участниках голосования
        $participants = RegistrationInVoting::getParticipants($voting);
        
        if (Yii::$app->request->post()) {
            
            // Получаем загружаемую оложку для голосования
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Вызываем метод на загрузку обложки, при успехе - получаем полный путь к загруженной обложке
            $path = $model->upload();
            
            if ($model->imageFile && $path) {
                $model->voting->voting_image = $path;
            }
            $model->imageFile = null;
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Изменения были успешно сохранены']);
            return $this->redirect(['view', 'voting' => $model->voting->voting_id]);
        }
        return $this->render('view', [
            'model' => $model,
            'participants' => $participants,
            'type_voting' => $type_voting,
            'houses_array' => $houses_array]);
    }
    
    /*
     * Запрос на удаление голосования
     */
    public function actionConfirmDeleteVoting() {
        
        $voting_id = Yii::$app->request->post('votingId');
        
        if (Yii::$app->request->isAjax) {
            $voting = Voting::findByID($voting_id);
            if (!$voting->delete()) {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Голосование ' . $voting->voting_title . ' было успешно удалено']);
            return $this->redirect(['index']);
        }
        return ['success' => false];
    }
    
    /*
     * Запрос на завешение голосования
     */
    public function actionConfirmCloseVoting() {
        
        $voting_id = Yii::$app->request->post('votingId');
        $current_time = strtotime(date('Y-m-d'));        
        
        if (Yii::$app->request->isAjax) {
            $voting = Voting::findByID($voting_id);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($current_time < strtotime($voting->voting_date_end)) {
                return [
                    'success' => true, 
                    'close' => 'ask', 
                    'message' => 'Дата завершения голосования отличается от текущей, все равно завершить голосование',
                    'title' => $voting->voting_title];
            } else {
                return [
                    'success' => true, 
                    'close' => 'yes', 
                    'message' => 'Вы действительно хотите завершить голосование ',
                    'title' => $voting->voting_title];
            }
        }
        return ['success' => false];
    }
    
    public function actionCloseVoting() {
        
        $voting_id = Yii::$app->request->post('votingId');
        if (Yii::$app->request->isAjax) {
            $voting = Voting::findByID($voting_id);
            if (!$voting->closeVoting()){
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('success', ['message' => 'Статус голосования ' . $voting->voting_title . ' изменился на "Завершено"']);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
    }
    
    /*
     * Фильтр данных по ID дома
     * 
     * @param $type string:
     *      Голосование
     */
    public function actionFilterByHouseAdress($house_id) {

        if (Yii::$app->request->isPost) {
                        
            $results = Voting::find()->where(['voting_house_id' => $house_id])->asArray();
            $count_voting = clone $results;
            $pages = new Pagination([
                'totalCount' => $count_voting->count(),
                'pageSize' => 15,
                'defaultPageSize' => 15,
            ]);
        
            $view_all_voting = $results->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();            
            
            $data = $this->renderPartial('data/view_all_voting', [
                'view_all_voting' => $view_all_voting, 'pages' => $pages]);
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;            
            return ['success' => true, 'data' => $data];
            
        }
        return ['success' => false];
        
    }
    
    /*
     * Загрузка профиля пользователя в модальном окне
     */
    public function actionViewProfile($user_id) {
        
        $user_info = Clients::getClientInfo($user_id);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('modal/view-user-profile', [
                'user_info' => $user_info]);
        }
        
    }
    
    
}
