<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\Pagination;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Voting;
    use app\models\Houses;
    use app\models\RegistrationInVoting;
    use app\modules\managers\models\form\VotingForm;
    use app\modules\managers\models\Clients;
    use app\modules\managers\models\searchForm\searchVote;

/**
 *  Голосование
 */
class VotingController extends AppManagersController {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['VotingsView']
                    ],
                    [
                        'actions' => [
                            'create',
                            'view', 
                            'confirm-delete-voting',
                            'confirm-close-voting',
                            'close-voting',
                            'for-whom-news',
                            'view-profile',
                        ],
                        'allow' => true,
                        'roles' => ['VotingsEdit']
                    ],
                ],
            ],
        ];
    }
    
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
        
        $houses_array = [];
        
        if (Yii::$app->request->post() && $model->validate()) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('success', ['message' => 'Новое голосование было успешно создано']);
                return $this->redirect(['view', 'voting_id' => $model->voting->voting_id]);
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
    public function actionView($voting_id) {
        
        $model = new VotingForm();
        $model->voting = Voting::findOne($voting_id);
        
        if ($model->voting == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model->setAttributes(Yii::$app->request->post());
        
        $type_voting = Voting::getTypeVoting();
        
        $houses_array = empty($model->voting->voting_house_id) ? [] : Houses::getHousesList($for_list = false);
        
        // Получаем информаию о участниках голосования
        $participants = RegistrationInVoting::getParticipants($voting_id);
        
        if (Yii::$app->request->post()) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Изменения были успешно сохранены']);
            return $this->redirect(['view', 'voting_id' => $model->voting->voting_id]);
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
     * Зависимый переключатель статуса публикации
     *      Для всех
     *      Для конкретного дома
     */
    public function actionForWhomNews($status) {
        
        $str = '';
        
        // Получаем список всех домов
        $houses_list = Houses::getHousesList($for_list = true);
        
        if ($status == 'all') {
            return '<option value="0">-</option>';
        } elseif ($status == 'house') {
            foreach ($houses_list as $house) {
                $full_adress = $house['houses_gis_adress']. ', ул. ' . $house['houses_street'] . ', д. ' . $house['houses_number'];
                $str .= '<option value="' . $house['houses_id'] . '">' . $full_adress . '</option>';
            }
            return $str;
        }
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
