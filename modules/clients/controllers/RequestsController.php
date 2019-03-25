<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\ActiveDataProvider;
    use yii\web\UploadedFile;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Requests;
    use app\modules\clients\models\_searchForm\FilterStatusRequest;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\TypeRequests;
    use app\models\StatusRequest;
    use app\models\RequestQuestions;
    use app\models\RequestAnswers;

/**
 * Заявки
 */
class RequestsController extends AppClientsController
{
    
    /**
     * Главная страница
     * 
     * @param array $type_requests Массив всех видом зявок
     * @param array $status_requests Пользовательские статусы заявок
     * @param integer $account_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     */
    public function actionIndex() {
        
        $account_id = $this->_current_account_id;
        
        $type_requests = TypeRequests::getTypeNameArray();
        $status_requests = StatusRequest::getUserStatusRequests();
       
        // В датапровайдер собираем все заявки по текущему пользователю
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByAccountID($account_id),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => (Yii::$app->params['countRec']['client']) ? Yii::$app->params['countRec']['client'] : 15,
            ]
        ]);

        /*
         * Определяем модель добавления новой заявки
         * Если данные получены и провалидированы сохраняем заявку
         */
        
        $model = new Requests([
                'scenario' => Requests::SCENARIO_ADD_REQUEST,
            ]);
        
        if ($model->load(Yii::$app->request->post())) {
            $request_number = $model->addRequest($account_id);
            if ($request_number) {
                $model->gallery = UploadedFile::getInstances($model, 'gallery');
                $model->uploadGallery();
                return $this->redirect(['view-request', 'request_number' => $request_number]);
            }
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка создания заявки. Обновите страницу и повторите действие заново']);
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'all_requests' => $all_requests,
            'type_requests' => $type_requests,
            'status_requests' => $status_requests,
            'model' => $model,
        ]);
    }
    
    /*
     * Страница отдельной заявки
     */    
    public function actionViewRequest($request_number) {

        $account_id = $this->_current_account_id;
        
        // Ищем заявку по уникальному номеру
        $request_info = Requests::findRequestByIdent($request_number, $account_id);
        
        /*
         * Если заявка не найдена или передан номер заявки не принадлежащий пользователю, 
         * кидаем исключение
         */
        if ($request_info === null || !ArrayHelper::keyExists($request_info['requests_account_id'], $this->_lists)) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request_info['requests_id']])->all();
        
        /*
         * Для комментариев к заявке
         */
        $comments = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);
        $comments_find = CommentsToRequest::findCommentsById($request_info['requests_id']);        
        
        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model->load(Yii::$app->request->post())) {
                $model->sendComment($request_info['requests_id']);
            }
        }
        
        return $this->render('view-request', [
            'request_info' => $request_info, 
            'all_images' => $images,
            'comments' => $comments,
            'comments_find' => $comments_find,
        ]);
    }
    
    /*
     * Сортировка заявок по
     *      @param integer $account_id ID лицевого счета, 
     *      @param integer $status ID статус заявки
     */
    public function actionFilterByTypeRequest() {
        
        $status = Yii::$app->request->post('status');
        
        $account_id = $this->_current_account_id;
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!is_numeric($status)) {
            return ['status' => false];
        }
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $model_filter = new FilterStatusRequest();
            $all_requests = $model_filter->searchRequest($status, $account_id);
            return $this->renderPartial('data/grid', ['all_requests' => $all_requests]);
        }
        
        return ['status' => false];
    }
    
    /*
     * Отправка ответа на вопрос
     */
    public function actionAddAnswerRequest() {
        
       Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Получаем с пост запроса ID заявки, ID вопроса, ответ
        $request_id = Yii::$app->request->post('requestID');
        $question_id = Yii::$app->request->post('questionsID');
        $answer = Yii::$app->request->post('answer');
        
        // Проверяем на корректность пришедшие данные
        if (!is_numeric($request_id) || !is_numeric($question_id) && !is_numeric($answer)) {
            return ['success' => false];
        }

        // Если пришел ajax запрос
        if ($request_id && $question_id && $answer && Yii::$app->request->isAjax) {
            if (RequestAnswers::setAnswer($request_id, $question_id, $answer)) {
                return ['success' => true];
            }
        }
        
        return ['success' => false]; 
    }
    
    /*
     * Обработка Ajax запроса на добавления оценки для заявки
     */
    public function actionAddScoreRequest() {

        // Получаем с пост запроса оценку и ID заявки
        $score = Yii::$app->request->post('grade') ? Yii::$app->request->post('grade') : -1;
        $request_id = Yii::$app->request->post('requestID');
        $request = Requests::findByID($request_id);
        
        // Проверяем на корректность пришедшие данные
        if (empty($request) || !isset($request)) {
            Yii::$app->session->setFlash('error', ['message' => 'Возникла внутренная ошибка. Обновите страницу и повторите действие заново']);
            return $this->redirect(['view-request', 'request_number' => $request['requests_ident']]);
        }

        // Если пришел ajax запрос
        if ($score && $request_id && Yii::$app->request->isAjax) {
            // Вызываем метод добавления оценки из модели
            if ($request->addGrade($score)) {
                Yii::$app->session->setFlash('success', ['message' => 'Спасибо, ваша оценка принята']);
                return $this->redirect(['view-request', 'request_number' => $request['requests_ident']]);
            }
        }
        
        Yii::$app->session->setFlash('error', ['message' => 'Возникла внутренная ошибка. Обновите страницу и повторите действие заново']);
        return $this->redirect(['view-request', 'request_number' => $request['requests_ident']]);        
    }
    
    /*
     * Добавить оценка завершенной заявки
     */
    public function actionAddGrade($request) {
        
        $request_info = Requests::findByID($request);
        
        if ($request_info == null || $request_info['status'] != StatusRequest::STATUS_CLOSE) {
            return $this->goHome();
        }
        
        $questions = RequestQuestions::getAllQuestions($request_info['requests_type_id']);
        $type_answer = [
            '1' => 'Нет',
            '2' => 'Да',
        ];
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('form/add-grade', [
                'request' => $request,
                'type_answer' => $type_answer,
                'questions' => $questions,
            ]);
        }
        
    }
    
    /*
     * Закрыть модальное окно ""
     * При закрытии модального окна, отправляем запрос на удаление ответов
     */
    public function actionCloseGradeWindow($request) {
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (!RequestAnswers::deleteAll(['anwswer_request_id' => $request])) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        return ['success' => false];
        
    }
    
    public function actionUploadImage() {
        
        return true;
        
    }

 }
