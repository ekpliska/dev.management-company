<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\models\News;

/**
 * Профиль Админимтратора
 *
 */
class ManagersController extends AppManagersController {
    
    
    public function actionError() {
        
        $exception = Yii::$app->errorHandler->exception;
        $exception->statusCode == '404' ? $this->view->title = "Ошибка 404" : '';
        $exception->statusCode == '403' ? $this->view->title = "Доступ запрещён" : '';
        $exception->statusCode == '500' ? $this->view->title = "Внутренняя ошибка сервера" : '';
        
        return $this->render('error', ['message' => $exception->getMessage()]);
    }
    
    public function actionIndex() {
        
        $news_content = News::getAllNewsAndVoting();
        return $this->render('index', [
            'news_content' => $news_content,
        ]);
        
    }
    
}