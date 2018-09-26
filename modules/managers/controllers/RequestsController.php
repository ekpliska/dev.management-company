<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\TypeRequests;
    use app\modules\managers\models\form\RequestForm;
    use app\models\Applications;
    use app\modules\managers\models\form\CommentForm;
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
        
        return $this->render('requests', [
            'model' => $model,
            'type_request' => $type_request,
            'flat' => $flat,
        ]);
    }
    
    /*
     * Просмотр и редактирование заявок
     */
    public function actionView($request_number) {
        
        $request = Applications::findByNubmer($request_number);
        
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
                $model_comment->sendComments($request['applications_id']);
            }
        }
                
        $comments_find = CommentsToRequest::getCommentByRequest($request['applications_id']);
        
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
}
