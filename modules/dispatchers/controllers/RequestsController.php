<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Requests;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\StatusRequest;

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
     * 
     */
    private function setStatusRequest($request_id) {
        
        $request = Requests::findOne($request_id);
        return $request->setSatusRequest(StatusRequest::STATUS_IN_WORK);
        
    }
    
}
