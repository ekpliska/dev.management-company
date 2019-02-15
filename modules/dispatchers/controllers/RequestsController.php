<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\helpers\ArrayHelper;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Requests;
    use app\models\PaidServices;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\StatusRequest;
    use app\modules\dispatchers\models\RequestsList;
    use app\modules\dispatchers\models\PaidServicesList;
    use app\modules\dispatchers\models\searchForm\searchRequests;
    use app\modules\dispatchers\models\searchForm\searchPaidRequests;
    use app\models\TypeRequests;
    use app\models\Services;
    use app\modules\dispatchers\models\Specialists;
    use app\helpers\FormatFullNameUser;

/**
 * Заявки, Платные услуги
 */
class RequestsController extends AppDispatchersController {
    
    public function actionIndex($block = 'requests') {
        
        // Загружаем виды заявок        
        $type_requests = TypeRequests::getTypeNameArray();
        // Загружаем список услуг для формы поиска
        $name_services = Services::getServicesNameArray();
        
        // Загружаем список всех спициалистов
        $specialist_lists = ArrayHelper::map(Specialists::getListSpecialists()->all(), 'id', function ($data) {
            return FormatFullNameUser::nameEmployee($data['surname'], $data['name'], $data['second_name']);
        });
        
        switch ($block) {
            case 'requests':
                // Загружаем модель поиска
                $search_model = new searchRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                break;
            case 'paid-requests':
                // Загружаем модель поиска
                $search_model = new searchPaidRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                break;
        }
        
        return $this->render('index', [
            'block' => $block,
            'type_requests' => $type_requests,
            'name_services' => $name_services,
            'specialist_lists' => $specialist_lists,
            'search_model' => $search_model,
            'results' => $results,
        ]);
        
    }
    
    public function actionViewRequest($request_number) {
        
        $request = Requests::findRequestToIdent($request_number);
        
        if ($request == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model_comment = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);

        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model_comment = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model_comment->load(Yii::$app->request->post())) {
                $model_comment->sendComments($request['requests_id']);
            }
        }
        
        $comments_find = CommentsToRequest::findCommentsById($request['requests_id']);
        
        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request['requests_id']])->all();
        
        
        return $this->render('view-request', [
            'request' => $request,
            'model_comment' => $model_comment,
            'comments_find' => $comments_find,
            'all_images' => $images,
        ]);
        
    }
    
    /*
     * Просмотр и редактирование заявки, на платную услугу
     */
    public function actionViewPaidRequest($request_number) {
        
        $paid_request = PaidServicesList::findRequestByIdent($request_number);
        
        if (!isset($paid_request) && $paid_request == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->render('view-paid-request', [
            'paid_request' => $paid_request,
        ]);
    }
    
    /*
     * Назначение специалиста для Заявок и Заявок на платные услуги
     */
    public function actionChooseSpecialist() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request_id = Yii::$app->request->post('requestId');
        $specialist_id = Yii::$app->request->post('specialistId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        if (Yii::$app->request->isAjax) {
            
            switch ($type_request) {
                case 'requests':
                    $request = RequestsList::findByID($request_id);
                    $request->chooseSpecialist($specialist_id);
                    // После назначения Диспетчером Специалиста, заявка считается "Принятой", статус "На исполнении"
                    $request->setSatusRequest(StatusRequest::STATUS_PERFORM);
                    return ['success' => true];
                    break;
                case 'paid-requests':
                    $paid_request = PaidServicesList::findOne($request_id);
                    $paid_request->chooseSpecialist($specialist_id);
                    return ['success' => true];
                    break;
                default:
                    return ['success' => false];                    
                    break;
            }
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Запрос на установку статуса "Отлонено"
     */
    public function actionConfirmRejectRequest() {
        
        $request_id = Yii::$app->request->post('requestID');
        $request_status = Yii::$app->request->post('requestStatus');
        $request_type = Yii::$app->request->post('requestType');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Проверяем существование пришедшего статуса
        if (!ArrayHelper::keyExists($request_status, StatusRequest::getStatusNameArray()) || !is_numeric($request_id)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            switch ($request_type) {
                case 'requests':
                    $request = Requests::findOne($request_id);
                    break;
                case 'paid-requests':
                    $request = PaidServices::findOne($request_id);
                    break;
                default:
                    return ['success' => false];
            }
            if (!$request->setSatusRequest($request_status)) {
                return ['success' => false];
            }
            return [
                'success' => true,
                'status_name' => StatusRequest::statusName($request_status),
            ];
        }
        
        return ['success' => true];
    }
    
}
