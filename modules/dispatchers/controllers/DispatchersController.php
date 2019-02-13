<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\modules\dispatchers\models\UserRequests;
    use app\modules\dispatchers\models\News;
    use app\modules\dispatchers\models\form\RequestForm;
    use app\models\TypeRequests;

/**
 * Диспетчеры
 */
class DispatchersController extends AppDispatchersController {
    
    /*
     * Диспетчеры, главная страница
     */
    public function actionIndex($block = 'requests') {
        
        $type_requests = TypeRequests::getTypeNameArray();
        $flat = [];
        
        switch ($block) {
            case 'requests':
                $model = new RequestForm();
                $user_lists = UserRequests::getRequestsByUser();
                $news_lists = News::getNewsList($count_news = 7);
                break;
            case 'paid-requests':
                $model = [];
                $user_lists = [];
                $requests_lists = [];
                break;
        }
        
        return $this->render('index', [
            'block' => $block,
            'model' => $model,
            'user_lists' => $user_lists,
            'news_lists' => $news_lists,
            'type_requests' => $type_requests,
            'flat' => $flat,
        ]);
        
    }
    
    /*
     * Метод просмотра списка заявок для выбранного пользователя
     */
    public function actionShowUserRequests() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user_id = Yii::$app->request->post('userID');
        $type = Yii::$app->request->post('type');
        
        if (Yii::$app->request->isAjax) {
            switch ($type) {
                case 'requests':
                    $request_list = UserRequests::getRequestsByUserID($user_id);
                    break;
                case 'paid-request':
                    break;
                default:
                    return ['success' => false];
            }
            
            $data = $this->renderPartial("request-block/{$type}_list", ['user_lists' => $request_list]);
            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }
    
    /*
     * Валидация форм
     */
    public function actionValidationForm($form) {
        
    }
    
}
