<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\helpers\ArrayHelper;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Requests;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\StatusRequest;
    use app\modules\dispatchers\models\RequestsList;

/**
 * Заявки, Платные услуги
 */
class RequestsController extends AppDispatchersController {
    
    public function actionIndex($block = 'requests') {
        
        return $this->render('index');
        
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
                    $this->setStatusRequest($request_id);
                    return ['success' => true];
                    break;
                case 'paid-requests':
//                    $paid_request = PaidServices::findOne($request_id);
//                    $paid_request->chooseSpecialist($specialist_id);
                    return ['success' => true];
                    break;
                default:
                    return ['success' => false];                    
                    break;
            }
        }
        
        return ['success' => false];
        
    }
    
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
                    $request->setSatusRequest($request_status);
                    break;
                case 'paid-requests':
                    break;
                default:
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
