<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\ActiveDataProvider;
    use yii\web\UploadedFile;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Requests;
    use app\models\User;
    use app\models\Houses;
    use app\modules\clients\models\FilterForm;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\TypeRequests;

/**
 * Заявки
 */
class RequestsController extends AppClientsController
{
    
    /**
     * Главная страница
     */
    public function actionIndex()
    {
        
        $user_info = $this->permisionUser();
        
        // Получаем виды заявок
        $type_requests = TypeRequests::getTypeNameArray();
        
        // Получаем пользовательские статусы для заявок
        $status_requests = Requests::getUserStatusRequests();
       
        // Модель для фильтра по типу заявок
        $model_filter = new FilterForm();
        
        // В датапровайдер собираем все заявки по текущему пользователю
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByAccountID($this->_choosing),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ]
        ]);

        /*
         * Определяем модель добавления новой заявки
         * Если данные получены и провалидированы сохраняем заявку
         */
        // $model = new AddRequest();
        $model = new Requests([
                'scenario' => Requests::SCENARIO_ADD_REQUEST,
            ]);
        
        // Получить ID выбранного лицевого счета из глобального списка dropDownList
        $accoint_id = $this->_choosing;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addRequest($accoint_id)) {
                $model->gallery = UploadedFile::getInstances($model, 'gallery');
                $model->uploadGallery();
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'all_requests' => $all_requests,
            'type_requests' => $type_requests,
            'status_requests' => $status_requests,
            'model' => $model,
            'model_filter' => $model_filter,
        ]);
    }
    
    /*
     * Страница отдельной заявки
     */    
    public function actionViewRequest($request_numder) {
        
        $user_info = $this->permisionUser();

        // Ищем заявку по уникальному номеру
        $request_info = Requests::findRequestByIdent($request_numder);
        
        /*
         * Если заявка не найдена или передан номер заявки не принадлежащий пользователю, 
         * кидаем исключение
         */
        if ($request_info === null || !ArrayHelper::keyExists($request_info->account, $this->_list)) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request_info->id])->all();
        
        /*
         * Для комментариев к заявке
         */
        $comments = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);
        $comments_find = CommentsToRequest::findCommentsById($request_info->id);        
        $user_house = Houses::findByAccountId($request_info->account);
        
        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model->load(Yii::$app->request->post())) {
                $model->sendComment($request_info->id);
            }
        }
        
        return $this->render('view-request', [
            'request_info' => $request_info, 
            'user_house' => $user_house, 
            'all_images' => $images,
            'comments' => $comments,
            'comments_find' => $comments_find,
        ]);
    }
    
    /*
     * Сортировка заявок по
     * @param integer $type_id ID тип заявки, 
     * @param integer $account_id ID лицевого счета, 
     * @param integer $status ID статус заявки
     */
    public function actionFilterByTypeRequest($type_id, $account_id, $status) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (!is_numeric($type_id) && !is_numeric($account_id) && !is_numeric($status)) {
            return ['status' => false];
        }
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $model_filter = new FilterForm();
            $all_requests = $model_filter->searchRequest($type_id, $account_id, $status);
            return $this->renderAjax('grid/grid', ['all_requests' => $all_requests]);
        }
        
        return ['status' => false];
    }
    
    /*
     * Метод, проверяет существование пользователя по текущему ID (user->identity->user_id)
     * Пользователь имеет доступ только к странице своего профиля
     * В противном случае выводим исключение
     */
    public function permisionUser() {
        
        $_user_id = Yii::$app->user->identity->user_id;
        $user_info = User::findByUser($_user_id);
        
        if ($user_info) {
            return $user_info;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }        
    }    
            
 }
