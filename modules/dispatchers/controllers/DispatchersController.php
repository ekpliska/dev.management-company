<?php

    namespace app\modules\dispatchers\controllers;
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
    
}
