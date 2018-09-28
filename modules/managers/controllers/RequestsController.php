<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\TypeRequests;
    use app\models\CategoryServices;
    use app\modules\managers\models\form\RequestForm;
    use app\modules\managers\models\form\PaidRequestForm;
    use app\modules\managers\models\Requests;
    use app\modules\managers\models\PaidServices;
    use app\models\CommentsToRequest;
    use app\models\Image;

/**
 * Заявки
 */
class RequestsController extends AppManagersController {
    
    /*
     * Заявки, главная страница
     */
    public function actionRequests() {
        
        $model = new RequestForm();
        $type_request = TypeRequests::getTypeNameArray();
        $flat = [];
        
        $requests = new ActiveDataProvider([
            'query' => Requests::getAllRequests(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('requests', [
            'model' => $model,
            'type_request' => $type_request,
            'flat' => $flat,
            'requests' => $requests,
        ]);
    }
    
    public function actionPaidServices() {
        
        $model = new PaidRequestForm();
        $servise_category = CategoryServices::getCategoryNameArray();
        $servise_name = [];
        $flat = [];
        
        return $this->render('paid-services', [
            'model' => $model,
            'servise_category' => $servise_category,
            'servise_name' => $servise_name,
            'flat' => $flat,
        ]);
    }
    
    /*
     * Просмотр и редактирование заявок
     */
    public function actionViewRequest($request_number) {
        
        $request = Requests::findRequestByIdent($request_number);
        
        if (!isset($request) && $request == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
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
                
        $comments_find = CommentsToRequest::getCommentByRequest($request['requests_id']);
        
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
     * Просомтр и редактирование заявки, на платную услугу
     */
    public function actionViewPaidRequest($request_number) {
        
        $paid_request = PaidServices::findRequestByIdent($request_number);
        
        if (!isset($paid_request) && $paid_request == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
//        $model_comment = new CommentsToRequest([
//            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
//        ]);

        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
//        if (Yii::$app->request->isPjax) {
//            $model_comment = new CommentsToRequest([
//                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
//            ]);        
//            if ($model_comment->load(Yii::$app->request->post())) {
//                $model_comment->sendComments($request['requests_id']);
//            }
//        }
                
//        $comments_find = CommentsToRequest::getCommentByRequest($request['requests_id']);
        
//        return $this->render('view-request', [
//            'request' => $request,
//            'model_comment' => $model_comment,
//            'comments_find' => $comments_find,
//            'all_images' => $images,
//        ]);
        
        return $this->render('view-paid-request', [
            'paid_request' => $paid_request,
        ]);
    }
    
    /*
     * Метод сохранения созданной заявки
     */
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view-paid-request', 'request_number' => $number]);
        }
    }
    
    /*
     * Метод сохранения созданной заявки на платную услугу
     */
    public function actionCreatePaidRequest() {
        
        $model = new PaidRequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view-paid-request', 'request_number' => $number]);
        }
    }    
    
    /*
     * Валидация формы в модальном окне "Создать заявку"
     * Валидация формы в модальном окне "Создать заявку на платную услугу"
     */
    public function actionValidationForm($form) {
        
        if ($form == 'new-request') {
            $model = new RequestForm();
        } elseif ($form == 'paid-request') {
            $model = new PaidRequestForm();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    public function actionSwitchStatusRequest() {
        
        $status = Yii::$app->request->post('statusId');
        $request_id = Yii::$app->request->post('requestId');
        
        if (Yii::$app->request->isAjax) {
            $request = Requests::findOne($request_id);
            $request->switchStatus($status);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => $request, 'status' => $status];
        }
        
        return ['success' => false];
    }
    
    /*
     * Назначение диспетчера
     */
    public function actionChooseDispatcher() {
        
        $request_id = Yii::$app->request->post('requestId');
        $dispatcher_id = Yii::$app->request->post('dispatcherId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $request = Requests::findByID($request_id);
            $request->chooseDispatcher($dispatcher_id);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Назначение специалиста
     */
    public function actionChooseSpecialist() {
        
        $request_id = Yii::$app->request->post('requestId');
        $specialist_id = Yii::$app->request->post('specialistId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $request = Requests::findByID($request_id);
            $request->chooseSpecialist($specialist_id);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }
    
}
