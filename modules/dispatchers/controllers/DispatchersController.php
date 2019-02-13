<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\modules\dispatchers\models\UserRequests;
    use app\modules\dispatchers\models\News;

/**
 * Диспетчеры
 */
class DispatchersController extends AppDispatchersController {
    
    /*
     * Диспетчеры, главная страница
     */
    public function actionIndex($block = 'requests') {
        
        switch ($block) {
            case 'requests':
                $user_lists = UserRequests::getRequestsByUser();
                $news_lists = News::getNewsList($count_news = 7);
                break;
            case 'paid-requests':
                $user_lists = [];
                $requests_lists = [];
                break;
        }
        
        return $this->render('index', [
            'block' => $block,
            'user_lists' => $user_lists,
            'news_lists' => $news_lists,
        ]);
        
    }
    
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
    
}
