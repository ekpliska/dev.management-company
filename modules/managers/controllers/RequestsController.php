<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\TypeRequests;
    use app\modules\managers\models\form\RequestForm;
    use app\modules\managers\models\Requests;
    use app\models\CommentsToRequest;

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
    
    /*
     * Просмотр и редактирование заявок
     */
    public function actionView($request_number) {
        
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
        
        return $this->render('view', [
            'request' => $request,
            'model_comment' => $model_comment,
            'comments_find' => $comments_find,
        ]);
    }
    
    /*
     * Метод сохранения созданной заявки
     */
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view', 'request_number' => $number]);
        }
    }
    
    /*
     * Валидация формы в модальном окне "Создать заявку"
     */
    public function actionValidationForm() {
        
        $model = new RequestForm();
        
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
    
    
    public function actionChooseDispatcher() {
        
        $request_id = Yii::$app->request->post('requestId');
        $dispatcher_id = Yii::$app->request->post('dispatcherId');
        
        if (Yii::$app->request->isAjax) {
            $request = Requests::findByID($request_id);
            $request->chooseDispatcher($dispatcher_id);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true, 'data' => $request_id . ' ' . $dispatcher_id];
        }
        
        return ['success' => true];
        
    }    
}
