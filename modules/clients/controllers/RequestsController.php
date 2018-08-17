<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\ActiveDataProvider;
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
//        // Делаем проверку, правильности переданных параметров в запросе url
//        $user_info = User::findByUser($user, $username);
//        
//        $client_id = $user_info->client->clients_id;
//        $rent_id = $user_info->rent->rents_id;
//        
//        // Если запрос пустой, кидаем исключение
//        if ($user_info === null) {
//            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
//        }
        
        // Получаем виды заявок
        $type_requests = TypeRequests::getTypeNameArray();
       
        // Модель для фильтра по типу заявок
        $model_filter = new FilterForm();
        
        // В датапровайдер собираем все заявки по текущему пользователю
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByUser($user_info),
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
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addRequest($user, $client_id, $rent_id)) {
                    $model->gallery = \yii\web\UploadedFile::getInstances($model, 'gallery');
                    $model->uploadGallery();
                
                Yii::$app->session->setFlash('success', 'Заявка создана');                
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка');
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'all_requests' => $all_requests,
            'type_requests' => $type_requests,
            'model' => $model,
            'model_filter' => $model_filter,
        ]);
    }
    
    /*
     * Страница отдельной заявки
     */    
    public function actionViewRequest($request_numder) {
        
        // Ищем заявку по уникальному номеру
        $request_info = Requests::findRequestByIdent($request_numder);

        // Если заявка не найдена, кидаем исключение
        if ($request_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }

        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request_info->requests_id])->all();
        
        /*
         * Для комментариев к заявке
         */
        $comments = new CommentsToRequest();
        $comments_find = CommentsToRequest::findCommentsById($request_info->requests_id);
        
        // $account_id = Yii::$app->user->identity->user_account_id;        
        $user_house = Houses::findByAccountId('1');
        
        
        $model = new CommentsToRequest();
		
        if ($model->load(Yii::$app->request->post()))
        {
            $model->sendComment($request_info->requests_id);
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
     * Фильтр заявок по типу заявки
     */
    public function actionFilterByTypeRequest() {
        
        $rent_id = Yii::$app->request->post('rent_id');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            $model_filter = new FilterForm();
            $all_requests = $model_filter->searchRequest($rent_id);
            return $this->renderAjax('grid', ['all_requests' => $all_requests]);
        }
    }
    
    
    public function actionAddComment() {
        
        $model = new CommentsToRequest();
        $client_id = Yii::$app->request->post('request_id');
        
        return $client_id;
//        $model = new ClientsRentForm();
//        
//        $client_id = Yii::$app->request->post('client_id');
//        
//        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
//            if ($model->load(Yii::$app->request->post())) {
//                $model->addNewClient($client_id);
//                // return $rent_id;
//                return true;
//            }
//        }
//        
        
    }
    
    /*
     * Метод, проверяет существование пользователя по текущему ID (user->identity->user_id)
     * Пользователь имеет доступ только к странице своего профиля
     * В противном случае выводим исключение
     */
    public function permisionUser() {
        
        $_user_id = Yii::$app->user->identity->user_id;
        $user_info = User::findByUser($_user_id);
        $user_info = \app\models\AccountToUsers::find()->andWhere([])->one();
        
        if ($user_info) {
            return $user_info;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }        
    }    
            
 }
